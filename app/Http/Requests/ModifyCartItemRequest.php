<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModifyCartItemRequest extends FormRequest
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
            'product' => 'required|array',
            'product.*.product_id' => 'required|numeric',
            'product.*.quantity' => 'required|numeric|min:0'
        ];
    }

    public function messages()
    {
        return [
            'product.required' => 'Product is required',
            'product.array' => 'Product must be an array',
            'product.*.product_id.required' => 'Product ID is required',
            'product.*.product_id.numeric' => 'Product ID must be a number',
            'product.*.quantity.required' => 'Quantity is required',
            'product.*.quantity.numeric' => 'Quantity must be a number',
            'product.*.quantity.min' => 'Quantity must be at least 0',
        ];
    }
}
