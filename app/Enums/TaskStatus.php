<?php

namespace App\Enums;

enum TaskStatus
{
    case PENDING;
    case COMPLETED;

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return collect(self::cases())->map->name->toArray();
    }
}
