<?php

namespace Database\Factories;

use App\Models\Odetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class OdetailFactory extends Factory
{
    /**
     *
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status' => $this->faker->randomElement([
                Odetail::INDISPONIBLE,
                Odetail::DISPONIBLE,
            ]),
            'order_id' => $this->faker->randomNumber(1),
            'product_id' => $this->faker->numberBetween(1, 18),
            'price_product' => $this->faker->randomFloat(3, 3, 30),
            'qtty' => $this->faker->randomNumber(1),
            'comments' => $this->faker->text(),
        ];
    }
}
