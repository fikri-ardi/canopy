<?php

use App\Imports\BudgetSpendsImport;
use App\Models\Budget;
use App\Models\InvestmentMovement;
use App\Models\Platform;
use App\Models\Spend;
use App\Models\Status;
use App\Models\User;
use Illuminate\Support\Facades\DB;

it('replaces an existing spend with the same name in the same budget during import', function () {
    DB::beginTransaction();

    try {
        $user = User::factory()->create();
        $budget = Budget::create([
            'user_id' => $user->id,
            'name' => 'Import Duplicate Budget',
            'income' => 1000000,
        ]);
        $platform = Platform::create([
            'user_id' => $user->id,
            'name' => 'Cash',
        ]);
        $status = Status::create([
            'user_id' => $user->id,
            'body' => 'Paid',
        ]);

        Spend::create([
            'budget_id' => $budget->id,
            'platform_id' => $platform->id,
            'status_id' => $status->id,
            'name' => 'Lunch',
            'amount' => '10000',
        ]);

        new BudgetSpendsImport($user);

        $import = new App\Imports\ExpenseRowsImport($user);
        $import->collection(collect([
            collect([
                'budget_id' => $budget->id,
                'budget_name' => $budget->name,
                'budget_income' => '1000000',
                'expense_name' => 'Lunch',
                'expense_amount' => '25000',
                'expense_platform' => 'Cash',
                'expense_status' => 'Paid',
                'expense_category' => 'Unlabeled',
                'expense_created_at' => now()->toDateTimeString(),
            ]),
        ]));

        $spends = Spend::where('budget_id', $budget->id)
            ->where('name', 'Lunch')
            ->get();

        expect($spends)->toHaveCount(1)
            ->and((int) $spends->first()->getRawOriginal('amount'))->toBe(25000);
    } finally {
        DB::rollBack();
    }
});

it('syncs investment movements by imported ids during import', function () {
    DB::beginTransaction();

    try {
        $user = User::factory()->create();

        $existingMovement = InvestmentMovement::create([
            'user_id' => $user->id,
            'investment_key' => 'bbri',
            'investment_name' => 'BBRI',
            'type' => 'deposit',
            'amount' => 10000,
            'occurred_on' => now()->toDateString(),
            'note' => 'Old row',
        ]);

        $untouchedMovement = InvestmentMovement::create([
            'user_id' => $user->id,
            'investment_key' => 'bbri',
            'investment_name' => 'BBRI',
            'type' => 'deposit',
            'amount' => 12000,
            'occurred_on' => now()->subDay()->toDateString(),
            'note' => 'Untouched row',
        ]);

        new BudgetSpendsImport($user);

        $import = new App\Imports\InvestmentMovementRowsImport($user);
        $import->collection(collect([
            collect([
                'movement_id' => $existingMovement->id,
                'investment_key' => 'bbri',
                'investment_name' => 'BBRI',
                'movement_type' => 'withdrawal',
                'movement_amount' => '25000',
                'occurred_on' => now()->toDateString(),
                'note' => 'Updated row',
            ]),
            collect([
                'movement_id' => 999999,
                'investment_key' => 'bbri',
                'investment_name' => 'BBRI',
                'movement_type' => 'deposit',
                'movement_amount' => '50000',
                'occurred_on' => now()->toDateString(),
                'note' => 'Inserted row',
            ]),
        ]));

        $movements = InvestmentMovement::where('user_id', $user->id)
            ->where('investment_key', 'bbri')
            ->where('investment_name', 'BBRI')
            ->get();

        expect($movements)->toHaveCount(2)
            ->and($existingMovement->fresh()->type)->toBe('withdrawal')
            ->and($existingMovement->fresh()->amount)->toBe(25000)
            ->and($existingMovement->fresh()->note)->toBe('Updated row')
            ->and($untouchedMovement->fresh())->toBeNull()
            ->and($movements->firstWhere('note', 'Inserted row')->amount)->toBe(50000);
    } finally {
        DB::rollBack();
    }
});
