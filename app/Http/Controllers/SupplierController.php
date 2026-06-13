<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $suppliers = Supplier::filter($request->all())
            ->paginate($request->input('per_page', 10), $request->input('page', 1));

        return SupplierResource::collection($suppliers)
            ->additional([
                'message' => 'Suppliers retrieved successfully.'
            ])->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        $supplier = Supplier::create($request->validated());

        return (new SupplierResource($supplier))
            ->additional([
                'message' => 'Supplier created successfully.',
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return (new SupplierResource($supplier))
            ->additional([
                'message' => 'Supplier retrieved successfully.',
            ])->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->validated());

        return (new SupplierResource($supplier))
            ->additional([
                'message' => 'Supplier updated successfully.',
            ])->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return response()->json(['message' => 'Supplier deleted successfully.'], Response::HTTP_OK);
    }
}
