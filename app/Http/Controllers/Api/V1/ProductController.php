<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Modules\Product\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllProduct(): JsonResponse
    {
        $products = $this->productService->getAllData();

        if ($products->isEmpty()) {
            return response()->json([
                'status' => 400,
                'message' => 'Data not found',
                'data' => null
            ], 200);
        }
        $data = ProductResource::collection($products);
        return response()->json([
            'status' => 200,
            'message' => 'Successfully get all data',
            'data' => $data
        ], 200);
    }

    public function getProductById(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|integer'
        ]);

        $product = $this->productService->getByProductId($validated['product_id']);
        if (!$product) {
            return response()->json([
                'status' => 400,
                'message' => 'Data not found',
                'data' => null
            ], 200);
        }
        $data = new ProductResource($product);

        return response()->json([
            'status' => 200,
            'message' => 'Successfully get data',
            'data' => $data
        ], 200);
    }

    public function getProductByName(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_name' => 'required|string',
            'limit' => 'integer|nullable'
        ]);
        $limit = $validated['limit'] ?? null;
        $product = $this->productService->searchBySimilarity("name", $validated['product_name'], $limit);
        if ($product->isEmpty()) {
            return response()->json([
                'status' => 400,
                'message' => 'Data not found',
                'data' => null
            ], 200);
        }
        $data = new ProductResource($product);
        return response()->json([
            'status' => 200,
            'message' => 'Successfully get data',
            'data' => $data
        ], 200);
    }
}
