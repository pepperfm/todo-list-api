<?php

declare(strict_types=1);

namespace App\Enums;

enum TaskStatusEnum: int
{
    case New = 0;

    case InProgress = 1;

    case Completed = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::New => 'New',
            self::InProgress => 'In Progress',
            self::Completed => 'Completed',
        };
    }
}
