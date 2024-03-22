<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SupplierTypes;

class SupplierTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SupplierTypes::create(['name' => 'Distributor']);
        SupplierTypes::create(['name' => 'Whole Seller']);
    }
}
