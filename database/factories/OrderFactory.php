<?php

namespace Database\Factories;

use App\Models\Firm;
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
            'comments' => $this->faker->lastname(),
            'note_admin' => $this->faker->text(),
            'total' => $this->faker->randomNumber(2),
        ];
    }
}
