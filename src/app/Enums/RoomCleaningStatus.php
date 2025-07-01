<?php

namespace App\Enums;
use App\Enums\EnumUtils;

enum RoomCleaningStatus
{
    use EnumUtils;

    case READY;
    case IN_PREPARATION;
    case NEEDS_MAINTENANCE;

    public function label(): string
    {
        return match ($this) {
            SELF::READY => 'pronto para uso',
            SELF::IN_PREPARATION => 'em preparo',
            SELF::NEEDS_MAINTENANCE => 'precisa de manutenção',
        };
    }

    public function color(): string
    {
        return match ($this) {
            SELF::READY => 'success',
            SELF::IN_PREPARATION => 'warning',
            SELF::NEEDS_MAINTENANCE => 'danger',
        };
    }

}
