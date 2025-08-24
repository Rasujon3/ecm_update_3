<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePackageRequest extends FormRequest
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
            'package_name' => 'required|string|max:50|unique:packages',
            'short_description' => 'required',
            'price' => 'required|numeric',
            'max_product' => 'required|integer',
            'sub_title' => 'nullable|string|max:191',
            'demo_url' => 'nullable|string|url|max:191',
            'img' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg',
            'status' => 'required|in:Active,Inactive',
            'services' => 'required|array|min:1',
        ];
    }
}
