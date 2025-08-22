<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TaskStatusEnum;

class Task extends Model
{
    use HasFactory;

    protected $attributes = [
        'status' => TaskStatusEnum::New,
    ];

    protected function casts(): array
    {
        return [
            'status' => TaskStatusEnum::class,
        ];
    }
}
