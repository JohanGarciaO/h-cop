<?php

namespace App\Enums;

trait EnumUtils
{
    public static function getNames(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function getValues(): array 
    {
        return array_column(self::cases(), 'value');
    }
}
