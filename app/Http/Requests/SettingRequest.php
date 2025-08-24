<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
            'courier_api_key' => 'nullable|string|unique:settings,courier_api_key,' . setting()->id,

            'courier_secret' => 'nullable|string|unique:settings,courier_secret,' . setting()->id,

            'facebook_pixel_id' => 'nullable|string|unique:settings,facebook_pixel_id,' . setting()->id,


            'pathao_client_id' => 'nullable|string|unique:settings,pathao_client_id,' . setting()->id,

            'pathao_client_secret' => 'nullable|string|unique:settings,pathao_client_secret,' . setting()->id,

            'pathao_access_token' => 'nullable|string|unique:settings,pathao_access_token,' . setting()->id,

            
            'order_note' => 'nullable|string',

            'delivery_charge' => 'nullable|numeric',
        ];
    }
}
