<?php

namespace App\Services\PaymentGateways\DTO;

readonly class PaymentDataDTO
{
    public function __construct(
        public float          $amount,
        public string         $currency,
        public int            $paymentGateway,
        public int            $orderId,
        public array          $items,
        public BillingDataDTO $billingData
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            amount: $data['amount'],
            currency: $data['currency'],
            paymentGateway: $data['payment_gateway'],
            orderId: $data['order_id'],
            items: $data['items'],
            billingData: $data['billing_data'],
        );
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
            'payment_methods' => $this->paymentGateway,
            'order_id' => $this->orderId,
            'items' => array_map(fn($item) => $item->toArray(), $this->items),
            'billing_data' => $this->billingData->toArray(),
        ];
    }

}
