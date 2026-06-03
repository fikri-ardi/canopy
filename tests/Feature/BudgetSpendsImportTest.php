<?php

use App\Imports\BudgetSpendsImport;
use App\Models\Budget;
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
