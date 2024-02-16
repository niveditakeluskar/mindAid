<?php

namespace RCare\Org\OrgPackages\Modules\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ComponentAddRequest extends FormRequest
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
            //'components'  => 'required|unique:ren_core.module_components,components|alpha_spaces',
            'module_id'   => 'required|integer',
            'components' =>  [
                             'required','alpha_spaces', 
                             Rule::unique('ren_core.module_components')
                                    ->where('module_id', $this->module_id)
                            ]
        ];
    }
}
