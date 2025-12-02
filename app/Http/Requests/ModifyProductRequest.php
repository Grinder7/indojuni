<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModifyProductRequest extends FormRequest
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
            'category' => 'required|string|max:255',
            'subcategory' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'variant' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'size' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'stock' => 'required|integer',
            'price' => 'required|integer',
            'img' => 'nullable|file|image',
            'id' => 'required|integer|exists:products,id',
        ];
    }
}
