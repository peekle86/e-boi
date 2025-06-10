<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum OrderStatusEnum: int implements HasLabel
{
    case NEW = 0;
    case PROCESSING = 1;
    case COMPLETED = 2;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::NEW => 'Новий',
            self::PROCESSING => 'В обробці',
            self::COMPLETED => 'Завершений',
        };
    }
}
