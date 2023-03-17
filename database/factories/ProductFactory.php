<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'description' => fake()->paragraph(1),
            'quantity' => fake()->numberBetween(1,10),
            'status'=> fake()->randomElement([ Product::PRODUCT_AVAILABLE, Product::PRODUCT_UNAVAILABLE ]),
            'image' => fake()->randomElement(['producto1.png','producto2.png','producto3.png']),
            'seller_id' => User::all()->random()->id,
        ];
    }
}
