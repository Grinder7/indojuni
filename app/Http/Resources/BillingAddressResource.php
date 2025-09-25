<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillingAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "firstname" => $this->firstname,
            "lastname" => $this->lastname,
            "address" => $this->address,
            "address2" => $this->address2,
            "email" => $this->email,
            "zip" => $this->zip,
            "payment_method" => $this->payment_method,
            "card_name" => $this->card_name,
            "card_number" => $this->card_number,
            "card_expiration" => $this->card_expiration,
            "card_cvv" => $this->card_cvv
        ];
    }
}
