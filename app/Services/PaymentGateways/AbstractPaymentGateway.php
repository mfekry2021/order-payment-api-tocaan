<?php

namespace App\Services\PaymentGateways;

use App\Services\PaymentGateways\Interfaces\PaymentGatewayInterface;

abstract class AbstractPaymentGateway implements PaymentGatewayInterface
{
    /**
     * @var string
     */
    protected string $merchantId;

    /**
     * @var string
     */
    protected string $apiKey;

    /**
     * @var string
     */
    protected string $apiSecret;
}
