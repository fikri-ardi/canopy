<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $platforms = collect(['GoPay', 'ShopeePay', 'SeaBank', 'BRI', 'Cash', 'Jago', 'BNI']);

        $platforms->each(function ($platform) {
            Platform::create([
                'name' => $platform
            ]);
        });
    }
}
