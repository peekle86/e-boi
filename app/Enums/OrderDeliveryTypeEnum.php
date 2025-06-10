<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum OrderDeliveryTypeEnum: int implements HasLabel
{
    case PICKUP = 1;
    case COURIER = 2;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PICKUP => 'Самовиніс',
            self::COURIER => 'Кур\'єр',
        };
    }
}
