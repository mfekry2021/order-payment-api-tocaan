<?php

namespace Database\Factories;

use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(), // Automatically create a related user
            'items' => [
                ['quantity' => 2, 'price' => 50],
                ['quantity' => 1, 'price' => 100],
            ],
            'total' => 200,
            'status' => OrderStatus::PENDING->value,
        ];
    }
}
