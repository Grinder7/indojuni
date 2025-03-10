<?php

declare(strict_types=1);

namespace App\Modules\PaymentDetail;

use App\Models\PaymentDetail;

class PaymentDetailService
{
    public PaymentDetailRepository $paymentDetailRepository;
    public function __construct(PaymentDetailRepository $paymentDetailRepository)
    {
        $this->paymentDetailRepository = $paymentDetailRepository;
    }
    public function create(array $data): PaymentDetail
    {
        return $this->paymentDetailRepository->create($data);
    }
    public function getById(string $id): PaymentDetail | null
    {
        return $this->paymentDetailRepository->getById($id);
    }
}
