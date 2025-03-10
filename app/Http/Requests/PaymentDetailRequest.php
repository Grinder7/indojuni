<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:255',
            'address2' => 'nullable|string|max:255',
            'zip' => 'required|integer|digits:5',
            'payment_method' => 'required|string|max:255',
            'card_name' => 'required|string|max:255',
            'card_number' => 'required|string|max:30',
            'card_expiration' => 'required|date_format:m/y|after_or_equal:now',
            'card_cvv' => 'required|integer|digits_between:3,4',
        ];
    }
}
