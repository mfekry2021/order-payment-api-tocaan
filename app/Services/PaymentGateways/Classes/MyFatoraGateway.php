<?php

namespace App\Services\PaymentGateways\Classes;

use App\Services\PaymentGateways\AbstractPaymentGateway;
use App\Services\PaymentGateways\DTO\PaymentDataDTO;
use App\Services\PaymentService;

class MyFatoraGateway extends AbstractPaymentGateway
{
    public function __construct()
    {
        $this->merchantId = env('MY_FATORA_MERCHANT_ID');
        $this->apiKey = env('MY_FATORA_API_KEY');
        $this->apiSecret = env('MY_FATORA_API_SECRET');
    }

    /**
     * @param PaymentDataDTO $paymentData
     * @return string
     */
    public function generateCheckoutUrl(PaymentDataDTO $paymentData): string
    {
        //use credential to tech checkout url
        //do some http request
        return 'https://test.myfatora.my/payment/checkout';
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
