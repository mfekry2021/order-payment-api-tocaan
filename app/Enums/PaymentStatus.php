<?php

namespace App\Enums;

use App\Traits\EnumValuesTrait;

enum PaymentStatus: int
{
    use EnumValuesTrait;

    case PENDING = 1;
    case SUCCESSFUL = 2;
    case FAILED = 3;

    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'pending',
            self::SUCCESSFUL => 'successful',
            self::FAILED => 'failed',
        };
    }
}
