<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::with('products')
            ->filter($request->all())
            ->paginate($request->input('per_page', 10), $request->input('page', 1));

        return CategoryResource::collection($categories)
            ->additional([
                'message' => 'Categories retrieved successfully.',
            ])->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        if ($request->has('products')) {
            $category->products()->sync($request->input('products'));
        }

        return (new CategoryResource($category))
            ->additional([
                'message' => 'Category created successfully.',
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return (new CategoryResource($category))
            ->additional([
                'message' => 'Category retrieved successfully.'
            ])->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        if ($request->has('products')) {
            $category->products()->sync($request->input('products'));
        }

        return (new CategoryResource($category))
            ->additional([
                'message' => 'Category updated successfully.',
            ])->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully.'], Response::HTTP_OK);
    }
}
