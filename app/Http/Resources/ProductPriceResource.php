<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPriceResource extends JsonResource
{

   public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'price' => (float) $this->price,
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
