<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
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
        // remove description and image from the response
        $products->makeHidden(['description', 'img']);

        return response()->json([
            'status' => 200,
            'message' => 'Successfully get all data',
            'data' => $products
        ], 200);
    }
}
