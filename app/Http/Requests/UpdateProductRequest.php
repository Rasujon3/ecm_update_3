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
            'product_price' => 'required|numeric|min:1',
            'unit_id' => 'required|integer|exists:units,id',
            'stock_qty' => 'nullable|numeric|min:1',
            'discount' => 'nullable|numeric|min:1',
            'description' => 'required',
            'fake_stocks' => 'nullable|numeric|min:1',
            'status' => 'required|in:Active,Inactive',
        ];
    }
}
