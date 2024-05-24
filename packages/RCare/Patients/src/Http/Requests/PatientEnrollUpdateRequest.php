<?php

namespace RCare\Patients\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientEnrollUpdateRequest extends FormRequest
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
            "practices"                     => "required|integer",
            "pcp"                     => "required|integer", //|exists:practice_physicians,id",
            "fname"                           => "required|max:35|regex:/^[\pL\s\- ._]+$/u",
            "mname"                           => "nullable|max:35|regex:/^[\pL\s\- ._]+$/u",
            "lname"                           => "required|max:35|regex:/^[\pL\s\- ._]+$/u",
            "dob"                             => "required|date|after:".date('Y-m-d',strtotime('01/01/1902'))."|before:".date('d-m-Y'), //|before_or_equal:".date('d-m-Y')
            "mob"                             => "required",
            "city"                            => "required|max:35",
            "state"                           => "required|max:35",
            "zipcode"                         => "required|digits_between:5,10|numeric",
            "marital_status"                  => "required|max:45",
            "military_status"                 => "required|int",
            "preferred_contact"               => "nullable|integer",
            "gender"                          => "required|gender", 
            "add_1"                           => "required|max:70",
            "practice_emr"                    => "required|max:15|alpha_num",
        ];
    }


    
}	 