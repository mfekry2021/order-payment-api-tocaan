<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentGateways\DTO\BillingDataDTO;
use App\Services\PaymentGateways\DTO\ItemDTO;
use App\Services\PaymentGateways\DTO\PaymentDataDTO;
use Illuminate\Database\Eloquent\Builder;

class PaymentService
{
    /**
     * @param int $orderId
     * @param int $paymentGatewayId
     * @return string
     */
    public function pay(int $orderId, int $paymentGatewayId): string
    {
        $order = (new OrderService())->findById($orderId);
        $paymentGateway = PaymentGateway::tryFrom($paymentGatewayId);

        //prepare data
        $paymentData = $this->preparePaymentData($order, $paymentGatewayId);
        $paymentEntryPoint = new ($paymentGateway->getPaymentEntrypoint())();
        return $paymentEntryPoint->generateCheckoutUrl($paymentData);
    }


    /**
     * @param int $paymentGatewayId
     * @param array $callbackData
     * @return void
     */
    public function callback(int $paymentGatewayId, array $callbackData): void
    {
        //call callback based on paymentGateway
        $paymentGateway = PaymentGateway::tryFrom($paymentGatewayId);
        $paymentEntryPoint = new ($paymentGateway->getPaymentEntrypoint())();
        $paymentEntryPoint->handleCallback($callbackData);
    }

    /**
     * @param Order $order
     * @param int $paymentGatewayId
     * @return PaymentDataDTO
     */
    public function preparePaymentData(Order $order, int $paymentGatewayId)
    {
        return PaymentDataDTO::fromArray([
            "amount" => $order->total ?? 0,
            "currency" => "EGP",
            "payment_gateway" => $paymentGatewayId,
            "order_id" => $order->id,
            "items" => $this->prepareItems($order->items),
            "billing_data" => $this->prepareBillingData($order->user->toArray())
        ]);
    }

    /**
     * @param array $items
     * @return array
     */
    private function prepareItems(array $items): array
    {
        $itemsPayload = [];
        foreach ($items as $item) {
            $itemsPayload[] = ItemDTO::fromArray([
                "name" => $item['product_name'] ?? '',
                "quantity" => $item['quantity'] ?? 1,
                "amount" => $item['price'] ?? 0,
            ]);
        }
        return $itemsPayload;
    }

    /**
     * @param array $userData
     * @return BillingDataDTO
     */
    private function prepareBillingData(array $userData): BillingDataDTO
    {
        return BillingDataDTO::fromArray([
            "name" => $userData["name"] ?? '',
            "email" => $userData["email"] ?? '',
            "phone" => $userData["phone"] ?? '',
            "address" => $userData["address"] ?? '',
        ]);
    }

    /**
     * @param Order $order
     * @param int $paymentGatewayId
     * @return void
     */
    public function savePaymentTransaction(Order $order, int $paymentGatewayId): void
    {
        Payment::create([
            "order_id" => $order->id,
            "payment_gateway" => $paymentGatewayId,
            "status" => rand(1, 3),
            "total" => $order->total,
            "transaction_id" => rand(11111, 99999999)
        ]);
    }

    /**
     * @param array $data
     * @return Builder
     */
    public function filterQuery(array $data = []): Builder
    {
        return Payment::query()
            ->when(!empty($data['status']), fn($q) => $q->whereStatus($data['status']))
            ->when(!empty($data['order_id']), fn($q) => $q->whereOrderId($data['order_id']))
            ->when(!empty($data['payment_gateway']), fn($q) => $q->wherePaymentGateway($data['payment_gateway']));
    }
}
