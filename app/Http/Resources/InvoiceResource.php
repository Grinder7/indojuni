<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['order_detail']->id,
            'sub_total' => $this['order_detail']->total,
            'tax' => $this['order_detail']->total * 0.11,
            'total' => $this['order_detail']->total * 0.11 + $this['order_detail']->total,
            'items' => OrderItemResource::collection($this['items']),
            'billing_address' => BillingAddressResource::make($this['payment_detail']),
            'created_at' => $this['order_detail']->created_at,
        ];
    }
}
