<?php

namespace App\Http\Requests;

use App\Models\ModuleTutorial;
use Illuminate\Foundation\Http\FormRequest;

class ModuleTutorialRequest extends FormRequest
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
        $route = $this->route('module_tutorial');
        $id = $route?->id ?? null;

        return ModuleTutorial::rules($id);
    }

    public function messages(): array
    {
        return [
            'module_id.unique' => 'The selected module already has a video URL.',
        ];
    }
}
