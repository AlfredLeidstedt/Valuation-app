<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    // Following method is not in use. Not working when I try to create more products through the terminal.
    public function definition(): array
    {
        return [
            'name'=> $this->faker->sentence(),
            'brand_id' => 1,
            'slug' => $this->faker->sentence(),
            'sku'=>  $this->faker->sentence(),
            'description'=>  $this->faker->sentence(),
            'image'=> $this->faker->sentence(),
            'quantity'=>  $this->faker->sentence(),
            'price'=> 500,
            'is_visable'=>  false,
            'is_featured'=>  false,
            'type'=>  $this->faker->sentence(),
            'published'=>$this->faker->sentence()
        ];
    }
}
