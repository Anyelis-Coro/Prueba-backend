<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
   public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'tax_cost' => (float) $this->tax_cost,
            'manufacturing_cost' => (float) $this->manufacturing_cost,
            // Cargamos la relación de la divisa si está disponible
            'currency' => $this->whenLoaded('currency', function () {
                return [
                    'id' => $this->currency->id,
                    'name' => $this->currency->name,
                    'symbol' => $this->currency->symbol,
                ];
            }),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
