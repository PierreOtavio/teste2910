<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VeiculoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
    return [
        'ano' => $this->faker->year(),
        'marca' => $this->faker->word(),
        'modelo' => $this->faker->word(),
        'placa' => strtoupper($this->faker->bothify('???-####')), // Gera uma placa no formato padrão
        'cor' => $this->faker->safeColorName(),
        'chassi' => strtoupper($this->faker->bothify('###############')), // 17 caracteres para o chassi
        'capacidade' => $this->faker->numberBetween(2, 7), // Exemplo de capacidade para veículos
        'km_atual' => $this->faker->numberBetween(0, 300000),
        'observacao' => $this->faker->paragraph(),
        ];
    }

}
