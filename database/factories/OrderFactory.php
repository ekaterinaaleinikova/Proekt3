<?php

// database/factories/OrderFactory.php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'total_price' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
}
