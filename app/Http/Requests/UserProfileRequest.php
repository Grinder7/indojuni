<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
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
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postcode' => 'required|integer|digits:5',
            'card_name' => 'required|string|max:255',
            'card_number' => 'required|string|max:30',
            'card_type' => 'required|integer|in:1,2',
            'card_expiration' => 'required|date_format:m/y|after_or_equal:now',
            'card_cvv' => 'required|string|min:3|max:4',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'card_number' => preg_replace('/[^\d]/', '', $this->card_number),
            // or whatever field you need to clean
        ]);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan pada data yang Anda masukkan.')
        );
    }
}
