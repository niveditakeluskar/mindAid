<?php

namespace RCare\Org\OrgPackages\DeactivationReasons\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeactivationReasonsAddRequest extends FormRequest 
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
            'reasons' => 'required|min:3|unique:ren_core.deactivation_reasons,reasons|regex:/^[a-zA-Z . , ( )]*$/', 
        ];
    }
}
