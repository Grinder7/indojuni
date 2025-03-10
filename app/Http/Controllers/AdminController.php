<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditDataRequest;
use App\Models\OrderDetail;
use App\Modules\Product\ProductService;
use App\Modules\ShoppingCart\OrderDetail\OrderDetailService;
use App\Modules\ShoppingCart\OrderItem\OrderItemService;
use App\Modules\User\UserService;
use Illuminate\Auth\Events\Validated;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Str;

class AdminController extends Controller
{

    public ProductService $productService;
    public OrderDetailService $orderDetail;
    public OrderItemService $orderItem;
    public UserService $user;
    public function __construct(ProductService $productService, OrderDetailService $orderDetail, OrderItemService $orderItem, UserService $user)
    {
        $this->productService = $productService;
        $this->orderDetail = $orderDetail;
        $this->orderItem = $orderItem;
        $this->user = $user;
    }


    public function adminHome()
    {
        if (!Gate::allows('isAdmin')) {
            abort(404, "Sorry, You can do this actions");
        }
        $this->authorize('isAdmin');
        $products = $this->productService->getAllData();
        return view('pages.admin.admin')->with(compact("products"));
    }

    public function adminInvoice()
    {
        if (!Gate::allows('isAdmin')) {
            abort(404, "Sorry, You can do this actions");
        }
        $this->authorize('isAdmin');
        $order_details = $this->orderDetail->getAllData();
        $users = $this->user->getAllData();
        foreach ($order_details as $order_detail) {
            foreach ($users as $user) {
                if (!strcmp($user->id, $order_detail->user_id)) {
                    $order_detail->username = $user->username;
                }
            }
        }
        return view('pages.admin.invoices')->with(compact("order_details"));
    }
    public function editData(EditDataRequest $request)
    {
        $validate = $request->validated();
        if ($validate['product_id']) {
            $catalogueData = $this->productService->getById($validate['product_id']);
            //update
            $catalogueData->name = $validate['name'];
            $catalogueData->description = $validate['description'];
            $catalogueData->stock = $validate['stock'];
            $catalogueData->price = $validate['price'];
            if (isset($validate['img'])) {
                $path = $request->file('img');
                $filename = str_replace(".", Str::random(1), substr(uniqid("", true), 0, -3)) . '.' . File::extension($path->getClientOriginalName());
                if (!Storage::disk('admin_img_upload')->put($filename, $path->get())) {
                    return redirect()->back()->with('error', $path->getErrorMessage());
                } else {
                    $catalogueData->img = $filename;
                }
            }
            $catalogueData->save();
            return redirect()->back()->with('success', 'Data has been succesfully updated');
        } else {
            //create

            // dd($validate);
            $this->productService->createData($validate);
            $path = $request->file('file');
            dd($path);
            $upload = $request->file('file')->storeAs('public/images/app/product', $validate['name']);
            // $path->storeAs('public/images/app/product', $validate['name']);
            return redirect()->back()->with('success', 'Data has been succesfully created');
        }
        return redirect()->back()->with('error', 'Error');
    }

    public function deleteData(Request $request)
    {

        $validated = $request->validate([
            'product_id' => "required|integer",
        ]);
        $data = $this->productService->getById($validated['product_id']);
        $terserah = $data->delete();
        if ($terserah) return Response::json(['success' => true]);
        else return Response::json(['success' => false]);
    }
}
