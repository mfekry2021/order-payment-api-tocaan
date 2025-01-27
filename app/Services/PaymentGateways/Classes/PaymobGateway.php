<?php

namespace App\Services\PaymentGateways\Classes;

use App\Services\PaymentGateways\AbstractPaymentGateway;
use App\Services\PaymentGateways\DTO\PaymentDataDTO;

class PaymobGateway extends AbstractPaymentGateway
{
    public function __construct()
    {
        $this->merchantId = env('PAYMOB_MERCHANT_ID');
        $this->apiKey = env('PAYMOB_API_KEY');
        $this->apiSecret = env('PAYMOB_API_SECRET');
    }

    /**
     * @param PaymentDataDTO $paymentData
     * @return string
     */
    public function generateCheckoutUrl(PaymentDataDTO $paymentData): string
    {
        return 'https://www.paymogo.com/pay';
    }

    /**
     * @param array $response
     * @return void
     */
    public function handleCallBack(array $response): void
    {
        // TODO: Implement handleCallBack() method.
    }
}
