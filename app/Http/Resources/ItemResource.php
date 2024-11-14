<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'weight' => $this->weight,
            'season' => $this->season,
            'size' => $this->size,
            'type' => $this->type,
            'age' => $this->age,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'packagings' => PackagingResource::collection($this->whenLoaded('packagings')),
        ];
    }
}
