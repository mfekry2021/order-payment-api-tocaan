<?php

namespace App\Enums;

use App\Traits\EnumValuesTrait;

enum OrderStatus: int
{
    use EnumValuesTrait;

    case PENDING = 1;
    case CONFIRMED = 2;
    case CANCELED = 3;

    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'pending',
            self::CONFIRMED => 'confirmed',
            self::CANCELED => 'cancelled',
        };
    }
}
