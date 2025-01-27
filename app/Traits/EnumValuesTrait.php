<?php

namespace App\Traits;


trait EnumValuesTrait
{
    /**
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
