<?php
namespace RCare\Rpm\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarenoteAddRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return validationRules(True,
            [
                'CareManagerNotes'  => 'required|text_comments_slash'
               
            ]
        );

    }
}
