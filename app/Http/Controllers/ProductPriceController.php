<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\StoreProductPriceRequest;
use App\Http\Resources\ProductPriceResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

class ProductPriceController 
{
   
    public function index($productId): AnonymousResourceCollection
    {
        $product = Product::findOrFail($productId);
        $prices = $product->prices()->with('currency')->get();
        
        return ProductPriceResource::collection($prices);
    }

    public function store(StoreProductPriceRequest $request, $productId): ProductPriceResource
    {
        $product = Product::findOrFail($productId);
        
        $existingPrice = $product->prices()->where('currency_id', $request->currency_id)->first();
        if ($existingPrice) {
            abort(422, 'Este producto ya tiene un precio registrado en esta divisa.');
        }

        $price = $product->prices()->create($request->validated());
        $price->load('currency');

        return new ProductPriceResource($price);
    }
}