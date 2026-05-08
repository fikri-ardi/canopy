<?php

namespace Database\Seeders;

use App\Models\Spend;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Spend::create([
            'budget_id' => 1,
            'platform_id' => 1,
            'status_id' => 1,
            'name' => 'Makan',
            'amount' => 300000,
        ]);
    }
}
