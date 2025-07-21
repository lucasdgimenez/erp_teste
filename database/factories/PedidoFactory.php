<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pedido>
 */
class PedidoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 20, 300);
        $frete = $subtotal >= 200 ? 0 : ($subtotal >= 52 && $subtotal <= 166.59 ? 15 : 20);
        $desconto = 0;
        $total = $subtotal + $frete - $desconto;

        return [
            'session_id' => Str::uuid(),
            'subtotal' => $subtotal,
            'frete' => $frete,
            'desconto' => $desconto,
            'total' => $total,
            'status' => $this->faker->randomElement(['pendente', 'pago', 'cancelado']),
            'cep' => $this->faker->postcode(),
            'endereco' => $this->faker->address(),
            'email_cliente' => $this->faker->safeEmail(),
            'cupom_id' => Cupom::inRandomOrder()->first()->id,
        ];
    }
}
