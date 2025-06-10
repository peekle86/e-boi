<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum OrderPaymentTypeEnum: int implements HasLabel
{
    case ON_RECEIPT = 1;
    case ONLINE = 2;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ON_RECEIPT => 'При отриманні',
            self::ONLINE => 'Онлайн',
        };
    }
}
