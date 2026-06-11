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
        $platforms = collect(['Tunai', 'GoPay', 'Shopeepay', 'OVO', 'Dana', 'BNI', 'BRI', 'BCA']);

        $platforms->each(function ($platform) {
            Platform::firstOrCreate(['name' => $platform]);
        });
    }
}
