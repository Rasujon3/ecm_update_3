<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'discount_price' => 'required|numeric',
            'unit_id' => 'required|integer|exists:units,id',
            'stock_qty' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'description' => 'required',
            'gallery_images' => 'required|array|min:1',
            'status' => 'required|in:Active,Inactive',
            'fake_stocks' => 'nullable|numeric',
        ];
    }
}
