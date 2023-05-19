<?php

namespace RCare\Org\OrgPackages\Diagnosis\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiagnosisAddRequest extends FormRequest
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
            //
            'code.*'    => 'nullable|regex:/^[a-zA-Z :]+[0-9 .]+$/',
            //'code.*'    => 'required|regex:/^[a-zA-Z :]+[0-9 .]+$/|unique:ren_core.diagnosis_codes, code, {$this->diagnosis_codes.code->id}', // required|email|unique:users, email, {$this->user->id}
            'condition' => 'required|regex:/^[a-zA-Z0-9()\' ]+$/',
            // 'qualified' => 'required'   
           
        ];
    }
}
