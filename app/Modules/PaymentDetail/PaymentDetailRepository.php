<?php

declare(strict_types=1);

namespace App\Modules\PaymentDetail;

use App\Models\OrderDetail;
use App\Models\PaymentDetail;

class PaymentDetailRepository
{
    public function createPaymentDetail(array $data): PaymentDetail
    {
        return PaymentDetail::create($data);
    }
    public function getPaymentDetailByOrderDetailID(string $orderDetailID): PaymentDetail | null
    {
        $orderDetail = OrderDetail::find($orderDetailID);
        if (!$orderDetail) {
            return null;
        }
        return $orderDetail->payment();
    }
}
