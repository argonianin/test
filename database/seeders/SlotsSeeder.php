<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SlotsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('slots')->insert([
            ['capacity' => 10, 'remaining' => 10],
            ['capacity' => 25, 'remaining' => 25],
            ['capacity' => 50, 'remaining' => 50],
        ]);
    }
}
