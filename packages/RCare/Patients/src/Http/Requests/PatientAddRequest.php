<?php

namespace RCare\Patients\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientAddRequest extends FormRequest
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
		//$insurance_primary_idnum_check = $request->post('insurance_primary_idnum_check');
        return [
			//"uid"							  => "unique:patients.patient,id",
            "practice_id"                     => "required|integer",
            // "provider_id"                     => "required|integer", //|exists:practice_physicians,id",
            // 'provider_name'                   => 'nullable|required_if:provider_id,0|provider_unique_name', //added by pranali on 29Oct2020
            "fname"                           => "required|max:35|regex:/^[\pL\s\- ._]+$/u",
           "mname"                            => "nullable|max:35|regex:/^[\pL\s\- ._]+$/u",
            "lname"                           => "required|max:35|regex:/^[\pL\s\- ._]+$/u",
            "gender"                          => "required|gender",
            "dob"                             => "required|date|after:".date('Y-m-d',strtotime('01/01/1902'))."|before:".date('d-m-Y'), //|before_or_equal:".date('d-m-Y')
            "address"                         => "nullable|max:70",
            "city"                            => "nullable|max:35",
            "state"                           => "nullable|max:35",
            "zipcode"                         => "nullable|digits_between:5,10|numeric",
            "country_code"                    => "required",
            "mob"                             => "required|phone",
            "email"                           => "nullable|max:255",//|unique:patients,email,noemail@renovahealth.care,email",
        ];
    }


    
}	