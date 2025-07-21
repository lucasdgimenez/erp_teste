<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pedido::factory()
            ->count(20)
            ->create()
            ->each(function ($pedido) {
                if (rand(0, 1)) {
                    $cupom = Cupom::inRandomOrder()->first();
                    $pedido->cupom()->attach($cupom->id);
                }
            });
    }
}
