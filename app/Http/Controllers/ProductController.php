<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProductController
{
    public function index(): AnonymousResourceCollection
    {
        $products = Product::with('currency')->paginate(10);
        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request): ProductResource
    {
        $product = Product::create($request->validated());
        $product->load('currency');
        
        return new ProductResource($product);
    }

    public function show($id): ProductResource
    {
        $product = Product::with('currency')->findOrFail($id);
        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, $id): ProductResource
    {
        $product = Product::findOrFail($id);
        $product->update($request->validated());
        $product->load('currency');
        
        return new ProductResource($product);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->delete();
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
