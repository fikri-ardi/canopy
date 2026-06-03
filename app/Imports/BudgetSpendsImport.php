<?php

namespace App\Imports;

use App\Models\Budget;
use App\Models\InvestmentMovement;
use App\Models\InvestmentTarget;
use App\Models\Label;
use App\Models\Platform;
use App\Models\Spend;
use App\Models\Status;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BudgetSpendsImport implements SkipsUnknownSheets, WithMultipleSheets
{
    public function __construct(private readonly User $user) {}

    public function sheets(): array
    {
        return [
            new ExpenseRowsImport($this->user),
            new BudgetRowsImport($this->user),
            new LabelRowsImport($this->user),
            new PlatformRowsImport($this->user),
            new StatusRowsImport($this->user),
            new InvestmentMovementRowsImport($this->user),
            new InvestmentTargetRowsImport($this->user),
        ];
    }

    public function onUnknownSheet($sheetName): void
    {
        //
    }
}

abstract class CanopyRowsImport implements ToCollection, WithHeadingRow
{
    public function __construct(protected readonly User $user) {}

    protected function integer(mixed $value): int
    {
        if (is_numeric($value)) {
            return (int) round((float) $value);
        }

        return (int) preg_replace('/[^\d-]/', '', (string) $value);
    }

    protected function date(mixed $value): ?CarbonImmutable
    {
        if (blank($value)) {
            return null;
        }

        try {
            return CarbonImmutable::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * @param  array<string, string>  $attributes
     * @return array<string, string|int>
     */
    protected function userScopedAttributes(string $table, array $attributes): array
    {
        if (Schema::hasColumn($table, 'user_id')) {
            $attributes = ['user_id' => $this->user->id] + $attributes;
        }

        return $attributes;
    }
}

class BudgetRowsImport extends CanopyRowsImport
{
    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $name = trim((string) $row->get('budget_name'));

            if ($name === '') {
                continue;
            }

            $budget = $this->ownedBudget((int) $row->get('budget_id')) ?? Budget::firstOrNew([
                'user_id' => $this->user->id,
                'name' => $name,
            ]);

            $budget->fill([
                'name' => $name,
                'income' => $this->integer($row->get('budget_income')),
            ])->save();
        }
    }

    private function ownedBudget(int $id): ?Budget
    {
        return $id > 0 ? Budget::where('user_id', $this->user->id)->find($id) : null;
    }
}

class LabelRowsImport extends CanopyRowsImport
{
    public function collection(Collection $rows): void
    {
        if (! Schema::hasTable('labels')) {
            return;
        }

        foreach ($rows as $row) {
            $name = trim((string) $row->get('label_name'));

            if ($name === '') {
                continue;
            }

            Label::firstOrCreate($this->userScopedAttributes('labels', ['name' => $name]));
        }
    }
}

class PlatformRowsImport extends CanopyRowsImport
{
    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $name = trim((string) $row->get('platform_name'));

            if ($name !== '') {
                Platform::firstOrCreate($this->userScopedAttributes('platforms', ['name' => $name]));
            }
        }
    }
}

class StatusRowsImport extends CanopyRowsImport
{
    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $body = trim((string) $row->get('status_name'));

            if ($body !== '') {
                Status::firstOrCreate($this->userScopedAttributes('statuses', ['body' => $body]));
            }
        }
    }
}

class ExpenseRowsImport extends CanopyRowsImport
{
    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $budgetName = trim((string) $row->get('budget_name'));

            if ($budgetName === '') {
                continue;
            }

            $budget = $this->resolveBudget($row, $budgetName);
            $expenseName = trim((string) $row->get('expense_name'));

            if ($expenseName === '') {
                continue;
            }

            $platform = $this->resolvePlatform(trim((string) $row->get('expense_platform')));
            $status = $this->resolveStatus(trim((string) $row->get('expense_status')));
            $label = $this->resolveLabel(trim((string) $row->get('expense_category')));

            $payload = [
                'budget_id' => $budget->id,
                'platform_id' => $platform->id,
                'status_id' => $status->id,
                'name' => $expenseName,
                'amount' => $this->integer($row->get('expense_amount')),
            ];

            if ($this->labelsReady()) {
                $payload['label_id'] = $label?->id;
            }

            if ($createdAt = $this->date($row->get('expense_created_at'))) {
                $payload['created_at'] = $createdAt;
            }

            $spend = $this->ownedSpend((int) $row->get('expense_id'));

            $spend ? $spend->update($payload) : Spend::create($payload);
        }
    }

    private function resolveBudget(Collection $row, string $budgetName): Budget
    {
        $budgetId = (int) $row->get('budget_id');
        $income = $this->integer($row->get('budget_income'));

        $budget = $budgetId > 0
            ? Budget::where('user_id', $this->user->id)->find($budgetId)
            : null;

        $budget ??= Budget::firstOrNew([
            'user_id' => $this->user->id,
            'name' => $budgetName,
        ]);

        $budget->fill([
            'name' => $budgetName,
            'income' => $income,
        ])->save();

        return $budget;
    }

    private function resolvePlatform(string $name): Platform
    {
        $name = $name !== '' ? $name : 'Imported';

        return Platform::firstOrCreate($this->userScopedAttributes('platforms', ['name' => $name]));
    }

    private function resolveStatus(string $body): Status
    {
        $body = $body !== '' ? $body : 'Imported';

        return Status::firstOrCreate($this->userScopedAttributes('statuses', ['body' => $body]));
    }

    private function resolveLabel(string $name): ?Label
    {
        if (! $this->labelsReady() || $name === '' || strtolower($name) === 'unlabeled') {
            return null;
        }

        return Label::firstOrCreate($this->userScopedAttributes('labels', ['name' => $name]));
    }

    private function ownedSpend(int $id): ?Spend
    {
        if ($id < 1) {
            return null;
        }

        return Spend::whereKey($id)
            ->whereHas('budget', fn ($query) => $query->where('user_id', $this->user->id))
            ->first();
    }

    private function labelsReady(): bool
    {
        return Schema::hasTable('labels') && Schema::hasColumn('spends', 'label_id');
    }
}

class InvestmentMovementRowsImport extends CanopyRowsImport
{
    public function collection(Collection $rows): void
    {
        if (! Schema::hasTable('investment_movements')) {
            return;
        }

        foreach ($rows as $row) {
            $key = trim((string) $row->get('investment_key'));
            $name = trim((string) $row->get('investment_name'));

            if ($key === '' || $name === '') {
                continue;
            }

            $payload = [
                'user_id' => $this->user->id,
                'investment_key' => $key,
                'investment_name' => $name,
                'type' => trim((string) $row->get('movement_type')) ?: 'withdrawal',
                'amount' => $this->integer($row->get('movement_amount')),
                'occurred_on' => $this->date($row->get('occurred_on'))?->toDateString() ?? now()->toDateString(),
                'note' => blank($row->get('note')) ? null : (string) $row->get('note'),
            ];

            $movement = $this->ownedMovement((int) $row->get('movement_id'));
            $movement ? $movement->update($payload) : InvestmentMovement::create($payload);
        }
    }

    private function ownedMovement(int $id): ?InvestmentMovement
    {
        return $id > 0 ? InvestmentMovement::where('user_id', $this->user->id)->find($id) : null;
    }
}

class InvestmentTargetRowsImport extends CanopyRowsImport
{
    public function collection(Collection $rows): void
    {
        if (! Schema::hasTable('investment_targets')) {
            return;
        }

        foreach ($rows as $row) {
            $key = trim((string) $row->get('investment_key'));
            $name = trim((string) $row->get('investment_name'));

            if ($key === '' || $name === '') {
                continue;
            }

            InvestmentTarget::updateOrCreate(
                [
                    'user_id' => $this->user->id,
                    'investment_key' => $key,
                ],
                [
                    'investment_name' => $name,
                    'target_amount' => $this->integer($row->get('target_amount')),
                ],
            );
        }
    }
}
