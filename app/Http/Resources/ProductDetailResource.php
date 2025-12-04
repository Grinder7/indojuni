<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_id' => $this->id,
            'category' => $this->category,
            'subcategory' => $this->subcategory,
            'type' => $this->type,
            'variant' => $this->variant,
            'brand' => $this->brand,
            'size' => $this->size,
            'unit' => $this->unit,
            'name' => $this->name,
            'stock' => $this->stock,
            'price' => $this->price,
            'description' => $this->description,
            'img' => $this->img,
        ];
    }
}
