<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'lastname' => $this->faker->lastname(),
            'firstname' => $this->faker->firstname(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'email_verified_at' => now(),
            'birthday' => str_replace("/", "-", $this->faker->dateTimeBetween('1980-01-01', '2005-12-31')
                ->format('Y-m-d')),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
            //'role' => ('member'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
