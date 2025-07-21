<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PedidoItem>
 */
class PedidoItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $produto = Produto::inRandomOrder()->first();
        $variacao = Variacao::where('produto_id', $produto->id)->inRandomOrder()->first();

        $quantidade = $this->faker->numberBetween(1, 5);
        $preco = $variacao ? $variacao->preco : $produto->preco_base;

        return [
            'pedido_id' => Pedido::inRandomOrder()->first()->id,
            'produto_id' => $produto->id,
            'variacao_id' => $variacao?->id,
            'quantidade' => $quantidade,
            'preco_unitario' => $preco,
        ];
    }
}
