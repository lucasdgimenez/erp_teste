<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VariacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Variacao::insert([
            [
                'produto_id' => 1,
                'nome' => 'Tamanho P',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'produto_id' => 1,
                'nome' => 'Tamanho M',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'produto_id' => 2,
                'nome' => 'Número 40',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
