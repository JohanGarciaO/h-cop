<?php

namespace App\Enums;
use App\Enums\EnumUtils;

enum Role: int
{
    use EnumUtils;

    case ADMIN = 1;
    case OPERATOR = 2;

    public function label(): string
    {
        return match ($this) {
            SELF::ADMIN => 'administrador',
            SELF::OPERATOR => 'operador',
        };
    }

}
