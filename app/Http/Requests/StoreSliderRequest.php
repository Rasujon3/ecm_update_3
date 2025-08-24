<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSliderRequest extends FormRequest
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
            //'domain_id' => 'required|integer|exists:domains,id',
            'title' => 'required|string',
            'sub_title' => 'required|string',
            'image' => 'required',
            'status' => 'required|in:Active,Inactive',
        ];
    }
}
