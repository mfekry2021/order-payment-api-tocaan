<?php

namespace Tests\Unit\Services;

use App\Enums\PaymentGateway;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Services\PaymentService;
use App\Services\PaymentGateways\DTO\PaymentDataDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected PaymentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PaymentService();
    }

    /** @test */
    public function it_generates_checkout_url_using_correct_gateway()
    {
        $order = Order::factory()->create();
        $gatewayId = PaymentGateway::MY_FATORA->value;

        $result = $this->service->pay($order->id, $gatewayId);

        $this->assertEquals('https://test.myfatora.my/payment/checkout', $result);
    }

    /** @test */
    public function it_handles_callback_through_correct_gateway()
    {
        $gatewayId = PaymentGateway::PAYMOB->value;
        $callbackData = ['transaction' => '123'];
        $gatewayClass = PaymentGateway::PAYMOB->getPaymentEntrypoint();

        $mockGateway = Mockery::mock('overload:' . $gatewayClass);
        $mockGateway->shouldReceive('handleCallback')
            ->once()
            ->with($callbackData);

        $this->service->callback($gatewayId, $callbackData);
        $this->assertTrue(true);
        Mockery::close();
    }

    /** @test */
    public function it_prepares_payment_data_correctly()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'items' => [
                ['product_name' => 'Test', 'quantity' => 2, 'price' => 10]
            ]
        ]);

        $paymentData = $this->service->preparePaymentData($order, PaymentGateway::MY_FATORA->value);

        $this->assertInstanceOf(PaymentDataDTO::class, $paymentData);
        $this->assertEquals($order->total, $paymentData->amount);
        $this->assertEquals($order->id, $paymentData->orderId);
        $this->assertCount(1, $paymentData->items);
        $this->assertEquals($user->name, $paymentData->billingData->name);
    }

    /** @test */
    public function it_saves_payment_transaction_with_required_fields()
    {
        $order = Order::factory()->create(['total' => 100]);

        $this->service->savePaymentTransaction($order, PaymentGateway::MY_FATORA->value);

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'payment_gateway' => PaymentGateway::MY_FATORA->value,
            'total' => 100
        ]);

        $payment = Payment::first();
        $this->assertNotNull($payment->transaction_id);
    }

    /** @test */
    public function it_filters_payments_correctly()
    {
        $order = Order::factory()->create();
        Payment::factory()->create(['status' => 1, 'order_id' => $order->id]);
        Payment::factory()->create(['status' => 2, 'payment_gateway' => PaymentGateway::PAYMOB->value]);

        // Test status filter
        $results = $this->service->filterQuery(['status' => 1])->get();
        $this->assertCount(1, $results);

        // Test order_id filter
        $results = $this->service->filterQuery(['order_id' => $order->id])->get();
        $this->assertCount(1, $results);

        // Test payment_gateway filter
        $results = $this->service->filterQuery(['payment_gateway' => PaymentGateway::PAYMOB->value])->get();
        $this->assertCount(1, $results);
    }

    /** @test */
    public function it_handles_missing_user_data_gracefully()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'items' => [['quantity' => 1]]
        ]);

        $paymentData = $this->service->preparePaymentData($order, PaymentGateway::MY_FATORA->value);

        $this->assertEquals('', $paymentData->billingData->address);
        $this->assertEquals(0, $paymentData->items[0]->amount);
    }
}
