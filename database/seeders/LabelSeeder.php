<?php

namespace Database\Seeders;

use App\Models\Label;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    public function run(): void
    {
        collect(['Jajan', 'Elektronik', 'Investasi', 'Transport', 'Bills'])->each(function ($label) {
            Label::firstOrCreate(['name' => $label]);
        });
    }
}
