<?php

declare(strict_types=1);

namespace App\Modules\PaymentDetail;

use App\Models\PaymentDetail;

class PaymentDetailRepository
{
    public function create(array $data): PaymentDetail
    {
        return PaymentDetail::create($data);
    }
    public function getById(string $id): PaymentDetail | null
    {
        return PaymentDetail::find($id);
    }
}
