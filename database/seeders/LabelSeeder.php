<?php

namespace Database\Seeders;

use App\Models\Label;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    public function run(): void
    {
        collect(['elektronik', 'investasi', 'jajan', 'kebutuhan', 'tagihan', 'transport'])->each(function ($label) {
            Label::firstOrCreate(['name' => $label]);
        });
    }
}
