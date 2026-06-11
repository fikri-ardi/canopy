<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = collect(['Belum dialokasi', 'Dialokasi', 'Ditarik', 'Selesai']);

        $statuses->each(function ($status) {
            Status::create([
                'body' => $status
            ]);
        });
    }
}
