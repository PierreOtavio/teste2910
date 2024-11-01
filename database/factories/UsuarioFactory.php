<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UsuarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'cpf' => $this->geradorCpf(),
        ];
    }

    public function geradorCpf()
    {
        $n1 = random_int(0, 9);
        $n2 = random_int(0, 9);
        $n3 = random_int(0, 9);
        $n4 = random_int(0, 9);
        $n5 = random_int(0, 9);
        $n6 = random_int(0, 9);
        $n7 = random_int(0, 9);
        $n8 = random_int(0, 9);
        $n9 = random_int(0, 9);

        $dig1 = $n9 * 2 + $n8 * 3 + $n7 * 4 + $n6 * 5 + $n5 * 6 + $n4 * 7 + $n3 * 8 + $n2 * 9 + $n1 * 10;
        $dig1 = 11 - ($dig1 % 11);
        $dig1 = ($dig1 >= 10) ? 0 : $dig1;

        $dig2 = $n9 * 2 + $n8 * 3 + $n7 * 4 + $n6 * 5 + $n5 * 6 + $n4 * 7 + $n3 * 8 + $n2 * 9 + $n1 * 10;
        $dig2 = 11 - ($dig2 % 11);
        $dig2 = ($dig2 >= 10) ? 0 : $dig2;

        return "$n1$n2$n3.$n4$n5$n6.$n7$n8$n9-$dig1$dig2";
    }
}
