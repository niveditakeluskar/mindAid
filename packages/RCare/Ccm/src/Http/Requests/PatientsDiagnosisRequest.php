<?php
namespace RCare\Ccm\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientsDiagnosisRequest extends FormRequest
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
                'diagnosis'  => 'required|integer', 
                'code'       => 'required|regex:/^[a-zA-Z0-9().]+$/',  //^[a-zA-Z0-9-!$%^&*()_+|~=`{}\[\]:";'<>?,.\/]+$
                'new_code'   => 'required_if:code,0', 
                'symptoms.*' => 'required',
                'goals.*'    => 'required',
                'tasks.*'    => 'required', 
                'comments'   => 'nullable' 
                // 'support'    => 'required',
                // 'comments'   => 'required'
            ]
        );

    }
}
