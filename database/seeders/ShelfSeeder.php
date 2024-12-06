<?php

namespace Database\Seeders;

use App\Models\Shelf;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShelfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shelf::create([
            'name' => 'Raf 1',
            'stock_limit' => 100,
            'warehouse_id' => 1, // Ana Depo
        ]);

        Shelf::create([
            'name' => 'Raf 2',
            'stock_limit' => 200,
            'warehouse_id' => 1, // Ana Depo
        ]);

        Shelf::create([
            'name' => 'Raf 3',
            'stock_limit' => 150,
            'warehouse_id' => 2, // Yardımcı Depo
        ]);
    }
}
