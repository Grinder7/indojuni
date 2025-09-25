<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\ModifyProductRequest;
use App\Modules\Product\ProductService;
use App\Modules\OrderDetail\OrderDetailService;

use App\Modules\User\UserService;
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

    public function adminHome()
    {
        $products = $this->productService->getAllProducts();
        return view('pages.admin.admin')->with(compact("products"));
    }

    // public function adminInvoice()
    // {
    //     $order_details = $this->orderDetail->getAllData();
    //     $users = $this->user->getAllData();
    //     foreach ($order_details as $order_detail) {
    //         foreach ($users as $user) {
    //             if (!strcmp($user->id, $order_detail->user_id)) {
    //                 $order_detail->username = $user->username;
    //             }
    //         }
    //     }
    //     return view('pages.admin.invoices')->with(compact("order_details"));
    // }

    public function modify(ModifyProductRequest $request)
    {
        $validate = $request->validated();
        $inputData = [
            'id' => $validate['product_id'],
            'name' => $validate['name'],
            'description' => $validate['description'],
            'stock' => $validate['stock'],
            'price' => $validate['price'],
            'img' => $validate['img'] ?? null
        ];
        $product = $this->productService->getProductById($inputData['id']);
        if ($product->img && $product->img !== $inputData['img']) {
            $path = $request->file('img');
            $filename = str_replace(".", Str::random(1), substr(uniqid("", true), 0, -3)) . '.' . File::extension($path->getClientOriginalName());
            if (!Storage::disk('admin_img_upload')->put($filename, $path->get())) {
                return redirect()->back()->with('error', $path->getErrorMessage());
            } else {
                $inputData['img'] = $filename;
            }
        } else {
            // TODO: REMOVE PREVIOUS FILE IF EXISTS
        }
        try {
            $this->productService->updateProduct($inputData);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 400,
                'message' => $th->getMessage(),
                'data' => null
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => 'Data has been successfully updated',
            'data' => null
        ]);
    }

    public function create(CreateProductRequest $request)
    {
        $validate = $request->validated();
        $inputData = [
            'name' => $validate['name'],
            'description' => $validate['description'],
            'stock' => $validate['stock'],
            'price' => $validate['price'],
            'img' => null
        ];
        if (isset($validate['img'])) {
            $path = $request->file('file');
            $filename = str_replace(".", Str::random(1), substr(uniqid("", true), 0, -3)) . '.' . File::extension($path->getClientOriginalName());
            if (!Storage::disk('admin_img_upload')->put($filename, $path->get())) {
                return redirect()->back()->with('error', $path->getErrorMessage());
            } else {
                $inputData['img'] = $filename;
            }
        }
        try {
            $this->productService->createProduct($inputData);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 400,
                'message' => $th->getMessage(),
                'data' => null
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => 'Data has been successfully created',
            'data' => null
        ]);
    }

    // public function deleteData(Request $request)
    // {
    //     $validated = $request->validate([
    //         'product_id' => "required|integer",
    //     ]);
    //     $data = $this->productService->getProductById($validated['product_id']);
    //     $terserah = $data->delete();
    //     if ($terserah) return Response::json(['success' => true]);
    //     else return Response::json(['success' => false]);
    // }
}
