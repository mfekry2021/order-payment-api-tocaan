<?php

namespace App\Enums;

use App\Services\PaymentGateways\Classes\MyFatoraGateway;
use App\Services\PaymentGateways\Classes\PaymobGateway;
use App\Traits\EnumValuesTrait;

enum PaymentGateway: int
{
    use EnumValuesTrait;

    case MY_FATORA = 1;
    case PAYMOB = 2;


    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::MY_FATORA => 'My Fatora',
            self::PAYMOB => 'Paymob',
        };
    }

    /**
     * @return string
     */
    public function getPaymentEntrypoint(): string
    {
        return match ($this) {
            self::MY_FATORA => MyFatoraGateway::class,
            self::PAYMOB => PaymobGateway::class,
        };
    }
}
