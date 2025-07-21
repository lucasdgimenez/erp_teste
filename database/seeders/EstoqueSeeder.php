<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstoqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           Estoque::insert([
            [
                'variacao_id' => 1,
                'quantidade' => 10,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'variacao_id' => 2,
                'quantidade' => 5,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'variacao_id' => 3,
                'quantidade' => 8,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
