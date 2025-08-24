<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentInfoRequest extends FormRequest
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
            'payment_method'  => 'required|in:bKash,rocket,nogod',
            'account_number'  => 'required|string|max:191',
            'payment_type'    => 'required|string|max:191',
            'instructions'    => 'required|string',
        ];
    }
}
