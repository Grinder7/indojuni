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
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'zip' => 'required|integer|digits:5',
            'card_name' => 'required|string|max:255',
            'card_number' => 'required|string|max:30',
            'card_type' => 'required|integer|digits:1',
            'card_expiration' => 'required|date_format:m/y|after_or_equal:now',
            'card_cvv' => 'required|string|min:3|max:4',
        ];
    }

    protected function prepareForValidation(){
        $this->merge([
            'card_number' => preg_replace('/[^\d]/', '', $this->card_number),
            // or whatever field you need to clean
        ]);
    }

}
