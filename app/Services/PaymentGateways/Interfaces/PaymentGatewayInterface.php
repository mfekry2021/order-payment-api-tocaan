<?php

namespace App\Services\PaymentGateways\Interfaces;

use App\Services\PaymentGateways\DTO\PaymentDataDTO;

interface PaymentGatewayInterface
{
    /**
     * @param PaymentDataDTO $paymentData
     * @return string
     */
    public function generateCheckoutUrl(PaymentDataDTO $paymentData): string;

    /**
     * @param array $response
     * @return void
     */
    public function handleCallBack(array $response): void;
}
