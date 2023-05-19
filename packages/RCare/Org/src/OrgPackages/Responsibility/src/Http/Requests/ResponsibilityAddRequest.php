<?php

namespace RCare\Org\OrgPackages\Responsibility\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResponsibilityAddRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'responsibility' => 'required|alpha_spaces', 
            'module_id' =>'required',
            'component_id' =>'nullable|required_unless:module_id,0'
        ];
    }
}
