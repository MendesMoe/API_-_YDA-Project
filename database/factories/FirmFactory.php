<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FirmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'address' => $this->faker->streetAddress(),
            'name' => $this->faker->firstname(),
            'phone' => str_replace(" ", "", $this->faker->phoneNumber()),
            'email' => $this->faker->email(),
            'visit_day_1' => $this->faker->randomElement([
                'lundi',
                'mardi',
                'mercredi',
            ]),
            'time_1' => $this->faker->randomElement([
                '8',
                '10',
                '14',
            ]),
            'visit_day_2' => $this->faker->randomElement([
                'mardi',
                'jeudi',
                'vendredi'
            ]),
            'time_2' => $this->faker->randomElement([
                '10',
                '14',
                '16',
                '12',
            ]),

            "title" => $this->faker->lastname(),
            "news" => $this->faker->text(),
            "image" => "5e5cb18c230d1682ae0ebe34abfdf341.jpg",
            'color' => $this->faker->randomElement([
                '#85aff2',
                '#f5ad64',
                '#80ed74',
                '#f2b1df',
            ]),
        ];
    }
}
