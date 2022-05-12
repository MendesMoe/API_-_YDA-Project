<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status' => $this->faker->randomElement([
                Order::EN_ATTENTE,
                Order::EN_COURS,
                Order::TERMINEE,
            ]),
            'comments' => $this->faker->lastname(),
            'note_admin' => $this->faker->text(),
        ];
    }
}
