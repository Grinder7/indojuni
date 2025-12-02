<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\ModifyProductRequest;
use App\Modules\Product\ProductService;
use App\Modules\OrderDetail\OrderDetailService;

use App\Modules\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{

    public ProductService $productService;
    public OrderDetailService $orderDetail;
    public UserService $user;
    public function __construct(ProductService $productService, OrderDetailService $orderDetail, UserService $user)
    {
        $this->productService = $productService;
        $this->orderDetail = $orderDetail;
        $this->user = $user;
    }

    public function dashboard(Request $request)
    {
        $searchQuery = $request->query('search');
        if ($searchQuery === null) $searchQuery = '';
        $filter = [];
        $filter["category"] = $request->query('kategori', '');
        $filter["subcategory"] = $request->query("sub_kategori", '');
        $filter["brand"] = $request->query("brand", '');
        // dd($filter);
        $products = $this->productService->getPaginatedProduct(24, 'name', $searchQuery, $filter);
        $productFilters = $this->productService->getProductFilterOptions();
        return view('pages.admin.dashboard')->with(compact("products", "productFilters"));
    }

    public function modify(ModifyProductRequest $request)
    {
        $validate = $request->validated();
        $inputData = [
            'id' => intval($validate['id']),
            'name' => $validate['name'],
            'description' => $validate['description'],
            'stock' => $validate['stock'],
            'price' => $validate['price'],
            'img' => $validate['img'] ?? null
        ];
        $product = $this->productService->getProductById($inputData['id']);
        if ($request->hasFile('img')) {
            $uploaded = $request->file('img');
            $filename = str_replace(".", Str::random(1), substr(uniqid("", true), 0, -3)) . '.' . File::extension($uploaded->getClientOriginalName());
            if (!Storage::disk('admin_img_upload')->put($filename, $uploaded->get())) {
                return response()->json([
                    'status'  => 400,
                    'message' => 'Failed to upload image',
                    'data'    => null,
                ]);
            }
            if ($product->img && Storage::disk('admin_img_upload')->exists($product->img)) {
                Storage::disk('admin_img_upload')->delete($product->img);
            }

            $inputData['img'] = $filename;
        } else {
            unset($inputData['img']);
        }
        try {
            $this->productService->updateProduct($inputData);
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            if ($inputData['img'] && Storage::disk('admin_img_upload')->exists($inputData['img'])) {
                Storage::disk('admin_img_upload')->delete($inputData['img']);
            }
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 400,
                    'message' => $th->getMessage(),
                    'data' => null
                ]);
            } else {
                return redirect()->back()->with('error', 'Gagal memperbaharui data produk');
            }
        }
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 200,
                'message' => 'Data has been successfully updated',
                'data' => null
            ]);
        } else {
            return redirect()->back()->with('success', 'Berhasil memperbaharui data produk');
        }
    }

    public function create(CreateProductRequest $request)
    {
        $validate = $request->validated();
        $inputData = [
            'name'        => $validate['name'],
            'description' => $validate['description'],
            'stock'       => $validate['stock'],
            'price'       => $validate['price'],
            'img'         => null,
        ];
        if ($request->hasFile('img')) {
            $uploaded = $request->file('img');
            $filename = str_replace(".", Str::random(1), substr(uniqid("", true), 0, -3))
                . '.' . File::extension($uploaded->getClientOriginalName());
            if (!Storage::disk('admin_img_upload')->put($filename, $uploaded->get())) {
                return response()->json([
                    'status'  => 400,
                    'message' => 'Failed to upload image',
                ]);
            }
            $inputData['img'] = $filename;
        }
        try {
            $this->productService->createProduct($inputData);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 400,
                'message' => $th->getMessage(),
                'data'    => null,
            ]);
        }
        return response()->json([
            'status'  => 200,
            'message' => 'Data has been successfully created',
            'data'    => null,
        ]);
    }

    public function deleteData(Request $request)
    {
        $validated = $request->validate([
            'product_id' => "required|integer",
        ]);
        $product = $this->productService->getProductById($validated['product_id']);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }
        // delete file
        if ($product->img && Storage::disk('admin_img_upload')->exists($product->img)) {
            Storage::disk('admin_img_upload')->delete($product->img);
        }
        $result = $product->delete();

        return response()->json(['success' => (bool)$result]);
    }
}
