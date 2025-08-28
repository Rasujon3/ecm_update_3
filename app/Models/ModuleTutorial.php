<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class ModuleTutorial extends Model
{
    use HasFactory;

    protected $table = 'module_tutorials';

    protected $fillable = [
        'module_id',
        'module_title',
        'video_type',
        'video_url',
        'video_id',
    ];

    public static function rules($id = null)
    {
        return [
            'module_id' => [
                'required',
                'integer',
                'exists:modules,id',
                Rule::unique('module_tutorials', 'module_id')->ignore($id)
            ],
            'video_url' => 'required|url',
        ];
    }
}
