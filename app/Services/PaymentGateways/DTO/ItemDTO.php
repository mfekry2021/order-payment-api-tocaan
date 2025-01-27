<?php

namespace App\Services\PaymentGateways\DTO;

readonly class ItemDTO
{
    public function __construct(
        public string $name,
        public float  $amount,
        public int    $quantity
    )
    {
    }

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? '',
            amount: $data['amount'] ?? '',
            quantity: $data['quantity'] ?? 0
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'amount' => $this->amount,
            'quantity' => $this->quantity
        ];
    }
}
