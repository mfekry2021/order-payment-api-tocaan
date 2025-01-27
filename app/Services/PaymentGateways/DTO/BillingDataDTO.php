<?php

namespace App\Services\PaymentGateways\DTO;

readonly class BillingDataDTO
{
    /**
     * @param string $name
     * @param string $email
     * @param string $phone
     * @param string $address
     */
    public function __construct(
        public string $name,
        public string $email,
        public string $phone,
        public string $address,
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
            email: $data['email'] ?? '',
            phone: $data['phone'] ?? '',
            address: $data['address'] ?? '',
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
        ];
    }
}
