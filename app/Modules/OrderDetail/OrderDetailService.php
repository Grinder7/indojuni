<?php

declare(strict_types=1);

namespace App\Modules\OrderDetail;

use App\Models\OrderDetail;
use App\Modules\OrderItem\OrderItemRepository;
use App\Modules\PaymentDetail\PaymentDetailRepository;
use Illuminate\Database\Eloquent\Collection;

class OrderDetailService
{
    private OrderDetailRepository $orderDetailRepository;
    private OrderItemRepository $orderItemRepository;
    private PaymentDetailRepository $paymentDetailRepository;

    public function __construct(OrderDetailRepository $orderDetailRepository, OrderItemRepository $orderItemRepository, PaymentDetailRepository $paymentDetailRepository)
    {
        $this->orderDetailRepository = $orderDetailRepository;
        $this->orderItemRepository = $orderItemRepository;
        $this->paymentDetailRepository = $paymentDetailRepository;
    }
    public function createInvoice(array $data): OrderDetail
    {
        $paymentDetail = $this->paymentDetailRepository->createPaymentDetail($data["billing_address"]);
        $orderDetail = $this->orderDetailRepository->createOrderDetail([
            'user_id' => $data['user_id'],
            'total' => $data['total'],
            'payment_id' => $paymentDetail->id,
        ]);
        foreach ($data["items"] as $item) {
            $success = $this->orderItemRepository->createOrderItem([
                "order_id" => $orderDetail->id,
                "product_id" => $item['product_id'],
                "quantity" => $item['quantity'],
            ]);
            if (!$success) {
                throw new \Exception("Gagal menambahkan item pesanan");
            }
        }
        return $orderDetail;
    }
    public function getOrderDetailByID(string $orderDetailID): ?OrderDetail
    {
        return $this->orderDetailRepository->getOrderDetailByID($orderDetailID);
    }
    public function prepareInvoice(OrderDetail $orderDetail): array
    {
        $orderItems = $this->orderItemRepository->getOrderItemsByOrderID($orderDetail->id);
        return [
            'order_detail' => $orderDetail,
            'items' => $orderItems,
            'payment_detail' => $orderDetail->payment,
        ];
    }
    public function getUserTransactions(string $userId): Collection
    {
        return $this->orderDetailRepository->getUserTransactions($userId);
    }
    public function getAllTransactions(): Collection
    {
        return $this->orderDetailRepository->getAllTransactions();
    }
}
