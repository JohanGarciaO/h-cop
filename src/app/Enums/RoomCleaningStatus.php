<?php

namespace App\Enums;
use App\Enums\EnumUtils;

enum RoomCleaningStatus
{
    use EnumUtils;

    case READY;
    case IN_PREPARATION;

    public function label(): string
    {
        return match ($this) {
            SELF::READY => 'pronto para uso',
            SELF::IN_PREPARATION => 'em preparo',
        };
    }

}
