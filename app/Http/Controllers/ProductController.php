<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::with('category', 'suppliers')
            ->filter($request->all())
            ->paginate($request->input('per_page', 10), $request->input('page', 1));

        return ProductResource::collection($products)
            ->additional([
                'message' => 'Products retrieved successfully.'
            ])->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        if ($request->has('suppliers')) {
            $product->suppliers()->sync($request->input('suppliers'));
        }

        return (new ProductResource($product->load('category', 'suppliers')))
            ->additional([
                'message' => 'Product created successfully.',
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return (new ProductResource($product->load('category', 'suppliers')))
            ->additional([
                'message' => 'Product retrieved successfully.',
            ])->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        if ($request->has('suppliers')) {
            $product->suppliers()->sync($request->input('suppliers'));
        }

        return (new ProductResource($product->load('category', 'suppliers')))
            ->additional([
                'message' => 'Product updated successfully.',
            ])->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully.'], Response::HTTP_OK);
    }
}
