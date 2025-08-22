<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/** @mixin \App\Models\Task */
class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status->value,
            'status_label' => $this->status->getLabel(),
            'created_at' => $this->created_at->format('d-m-Y H:i'),
            'updated_at' => $this->updated_at?->format('d-m-Y H:i'),
        ];
    }
}
