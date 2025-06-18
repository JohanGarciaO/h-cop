<?php

namespace App\Enums;
use App\Enums\EnumUtils;

enum Gender: string
{
    use EnumUtils;
    
    case MALE = 'M';
    case FEMALE = 'F';

    public function label(): string
    {
        return match ($this) {
            self::MALE => 'Masculino',
            self::FEMALE => 'Feminino',
        };
    }
}
