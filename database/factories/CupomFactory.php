<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cupom>
 */
class CupomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'codigo' => strtoupper($this->faker->word()),
            'valor_desconto' => $this->faker->randomFloat(2, 5, 50),
            'valor_minimo' => $this->faker->randomFloat(2, 50, 150),
            'validade' => now()->addDays(rand(1, 30))
        ];
    }
}
