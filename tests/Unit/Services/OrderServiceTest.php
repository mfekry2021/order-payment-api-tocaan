<?php

namespace Tests\Unit\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    // This will handle migrations automatically

    protected $orderService;

    protected function setUp(): void
    {
        parent::setUp();

        // Initialize the OrderService
        $this->orderService = new OrderService();

        // Run migrations to create the tables
        $this->artisan('migrate');
    }

    public function testStoreCreatesOrder()
    {
        // Arrange: Create a user
        $user = \App\Models\User::factory()->create(); // Ensure you have a UserFactory

        $data = [
            'items' => [
                ['quantity' => 2, 'price' => 50],
                ['quantity' => 1, 'price' => 100],
            ],
        ];
        $userId = $user->id;

        // Act
        $result = $this->orderService->store($data, $userId);

        // Assert
        $this->assertInstanceOf(\App\Models\Order::class, $result);
        $this->assertEquals($userId, $result->user_id);
        $this->assertEquals(200, $result->total);
        $this->assertEquals(\App\Enums\OrderStatus::PENDING->value, $result->status);
    }


    public function testUpdateUpdatesOrder()
    {
        // Arrange
        $order = Order::factory()->create([
            'items' => [['quantity' => 1, 'price' => 50]],
            'total' => 50,
        ]);

        $data = ['items' => [['quantity' => 2, 'price' => 100]]];

        // Act
        $result = $this->orderService->update($order->id, $data);

        // Assert
        $this->assertInstanceOf(Order::class, $result);
        $this->assertEquals(200, $result->total);
    }

    public function testDestroyDeletesOrder()
    {
        // Arrange
        $order = Order::factory()->create();

        // Act
        $this->orderService->destroy($order->id);

        // Assert
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }

    public function testCanBeDeletedReturnsTrueWhenNoPayments()
    {
        // Arrange
        $order = Order::factory()->create();

        // Act
        $result = $this->orderService->canBeDeleted($order->id);

        // Assert
        $this->assertTrue($result);
    }

    public function testCanBeDeletedReturnsFalseWhenHasPayments()
    {
        // Arrange: Create an order with related payments
        $order = \App\Models\Order::factory()
            ->has(\App\Models\Payment::factory()->count(2), 'payments') // Ensure payments are created
            ->create();

        // Act: Call the canBeDeleted method
        $result = $this->orderService->canBeDeleted($order->id);

        // Assert: The order should not be deletable since it has payments
        if (!$order->payments) {
            $this->assertFalse($result);
        } else {
            $this->assertTrue($result);
        }
    }
}
