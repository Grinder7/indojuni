<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvoiceResource;
use App\Http\Resources\TransactionResource;
use App\Modules\Product\ProductService;
use App\Modules\OrderDetail\OrderDetailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public OrderDetailService $orderDetailService;
    public ProductService $productService;
    public function __construct(OrderDetailService $orderDetailService, ProductService $productService)
    {
        $this->orderDetailService = $orderDetailService;
        $this->productService = $productService;
    }
    public function index(Request $request)
    {
        $transactions = $this->orderDetailService->getUserTransactions(Auth::id());
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 200,
                'message' => 'Successfully retrieved transactions',
                'data' => TransactionResource::collection($transactions)
            ]);
        }
        return view('pages.transaction')->with(compact('transactions'));
    }
    public function invoice(Request $request)
    {
        $data['order_id'] = $request->id;
        $validator = Validator::make($data, [
            'order_id' => 'required|string|exists:order_details,id',
        ]);
        if ($validator->fails()) {
            $message = $validator->errors()->messages();
            $message = array_values($message)[0][0];
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 400,
                    'message' => $message,
                    'data' => null
                ], 400);
            }
            return redirect()->back()->with('error', $message);
        } else {
            $orderDetail = $this->orderDetailService->getOrderDetailByID($data['order_id']);
            // Check if user is authorized to view this invoice or the admin wants to view the invoice
            if (Auth::id() == $orderDetail->user_id || Auth::user()->is_admin === 1) {
                if ($orderDetail) {
                    $invoiceData = $this->orderDetailService->prepareInvoice($orderDetail);
                    // dd($invoiceData["items"]);
                    if ($request->expectsJson()) {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Successfully retrieved invoice',
                            'data' => InvoiceResource::make($invoiceData)
                        ]);
                    }
                    return view('pages.invoice')->with(compact('invoiceData'));
                } else {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'status' => 404,
                            'message' => 'Order not found',
                            'data' => null
                        ], 404);
                    }
                    return redirect()->back()->with('error', 'Pesanan tidak ditemukan');
                }
            } else {
                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => 403,
                        'message' => 'You are not authorized to view this invoice',
                        'data' => null
                    ], 403);
                }
                return redirect()->back()->with('error', 'Anda tidak berwenang untuk melihat faktur ini');
            }
        }
    }
}
