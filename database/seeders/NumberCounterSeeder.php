<?php

namespace Database\Seeders;

use App\Models\NumberCounter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NumberCounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NumberCounter::create([
            'table_name' => 'products',
            'current_number' => 1,
        ]);
    }
}
