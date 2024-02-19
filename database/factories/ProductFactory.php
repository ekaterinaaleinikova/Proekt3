<?php

namespace Database\Factories;

use App\Models\Categories;
use App\Models\Product;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'category_id' => Categories::all()->random()->id,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'description' => $this->faker->sentence,
            'image' => $this->faker->image('storage/image/img', 400, 300, null, false),
        ];
    }
}


