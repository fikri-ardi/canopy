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
        $platforms = collect(['gopay', 'shopeepay', 'seabank', 'BRI', 'cash', 'jago', 'BNI']);

        $platforms->each(function ($platform) {
            Platform::create([
                'name' => $platform
            ]);
        });
    }
}
