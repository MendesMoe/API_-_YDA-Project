<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'service_id' => $this->faker->numberBetween($min = 1, $max = 5),
            'name' => $this->faker->firstname(),
            'price' => $this->faker->randomNumber(2),
            'image' => 'f0a59c94116fdb9fda9a392100e75560.jpg',
            'description' => $this->faker->text(60),
            'status' => $this->faker->randomElement(['actif', 'inactif'])
        ];
    }
}
