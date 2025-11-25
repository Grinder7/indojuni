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
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'address' => $this->address,
            'city' => $this->city,
            'province' => $this->province,
            'postcode' => $this->postcode,
            'card_type' => $this->card_type === 1 ? 'Kartu Kredit' : 'Kartu Debit',
            'card_name' => $this->card_name,
            'card_number' => $this->card_number,
            'card_expiration' => $this->card_expiration,
            'card_cvv' => $this->card_cvv,
        ];
    }
}
