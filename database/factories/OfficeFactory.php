<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Office>
 */
class OfficeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code' => $this->faker->unique()->numberBetween(1, 50),
            'description' => substr($this->faker->paragraph(1), 0, 20),
            'head' => $this->faker->firstName('male'),
            'head_position' => 'Office Head II',
        ];
    }
}
