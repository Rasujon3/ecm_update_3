<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'product_name' => 'required|string|max:50',
            'product_price' => 'required|numeric',
            'unit_id' => 'required|integer|exists:units,id',
            'stock_qty' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'description' => 'required',
            'fake_stocks' => 'nullable|numeric',
            'status' => 'required|in:Active,Inactive',
        ];
    }
}
