<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Webhook>
 */
class WebhookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pedido_id' => Pedido::inRandomOrder()->first()->id,
            'status_recebido' => $this->faker->randomElement(['pago', 'cancelado', 'enviado']),
            'payload' => [
                'webhook_id' => $this->faker->uuid(),
                'evento' => 'atualizacao_status',
                'timestamp' => now()->toISOString(),
            ],
        ];
    }
}
