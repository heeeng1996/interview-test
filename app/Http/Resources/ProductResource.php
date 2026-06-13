<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'final_price' => (float) $this->final_price,
            'quantity' => $this->quantity,
            'image' => $this->image,
            'discount' => $this->discount,
            'rating' => (float) $this->rating,
            'review' => (float) $this->review,
            'suppliers' => SupplierResource::collection($this->whenLoaded('suppliers')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
