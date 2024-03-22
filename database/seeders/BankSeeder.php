<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Banks;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Banks::create(['name' => 'BRI']);
        Banks::create(['name' => 'BNI']);
        Banks::create(['name' => 'BCA']);
        Banks::create(['name' => 'BSI']);
        Banks::create(['name' => 'Mandiri']);
    }
}
