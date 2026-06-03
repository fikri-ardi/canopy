<?php

namespace App\Imports;

use App\Models\Budget;
use App\Models\Label;
use App\Models\Platform;
use App\Models\Spend;
use App\Models\Status;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BudgetSpendsImport implements ToCollection, WithHeadingRow
{
    public function __construct(private readonly User $user) {}

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
        $attributes = $this->userScopedAttributes('platforms', ['name' => $name]);

        return Platform::firstOrCreate($attributes);
    }

    private function resolveStatus(string $body): Status
    {
        $body = $body !== '' ? $body : 'Imported';
        $attributes = $this->userScopedAttributes('statuses', ['body' => $body]);

        return Status::firstOrCreate($attributes);
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

    /**
     * @param  array<string, string>  $attributes
     * @return array<string, string|int>
     */
    private function userScopedAttributes(string $table, array $attributes): array
    {
        if (Schema::hasColumn($table, 'user_id')) {
            $attributes = ['user_id' => $this->user->id] + $attributes;
        }

        return $attributes;
    }

    private function labelsReady(): bool
    {
        return Schema::hasTable('labels') && Schema::hasColumn('spends', 'label_id');
    }

    private function integer(mixed $value): int
    {
        if (is_numeric($value)) {
            return (int) round((float) $value);
        }

        return (int) preg_replace('/[^\d-]/', '', (string) $value);
    }

    private function date(mixed $value): ?CarbonImmutable
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
}
