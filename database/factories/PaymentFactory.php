<?php

namespace Database\Factories;

use App\Enums\PaymentGateway;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'order_id' => \App\Models\Order::factory(), // Automatically create a related order
            'total' => $this->faker->randomFloat(2, 10, 500),
            'payment_gateway' => PaymentGateway::MY_FATORA->value,
        ];
    }
}
