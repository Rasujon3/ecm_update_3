<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAreaRequest extends FormRequest
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
            'division' => 'nullable|string|max:191',
            'area_name' => 'required|string|max:50',
            'area_type' => 'nullable|in:Inside,Outside',
            'status' => 'required|in:Active,Inactive',
            'inside_delivery_charges' => 'required|string|max:191',
            'outside_delivery_charges' => 'required|string|max:191',
        ];
    }
}
