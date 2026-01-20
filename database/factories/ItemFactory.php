<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'sku' => Str::upper(Str::random(10)),
            'original_price' => $this->faker->randomFloat(2, 5, 30),
            'selling_price' => $this->faker->randomFloat(2, 10, 50),
            'status' => 'active',
        ];
    }
}
