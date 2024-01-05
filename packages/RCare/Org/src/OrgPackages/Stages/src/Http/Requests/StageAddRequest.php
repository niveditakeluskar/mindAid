<?php
namespace RCare\Org\OrgPackages\Stages\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StageAddRequest extends FormRequest
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
        return validationRules(True,
            [
                'description'  => 'required|min:3|alpha_spaces',
                'component_id' => 'required|integer',
                'operation'    => 'required|regex:/^[crud]+$/',
                'service_id'   => 'required|integer'
            ]
        );
    }
}
