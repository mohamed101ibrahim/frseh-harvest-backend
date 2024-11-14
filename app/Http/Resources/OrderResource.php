<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_date' => $this->created_at,
            'total' => $this->total,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'items' => $this->takes ? TakesResource::collection($this->takes) : [],
        ];
    }
}

