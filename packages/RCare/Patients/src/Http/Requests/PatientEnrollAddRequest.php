<?php

namespace RCare\Patients\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientEnrollAddRequest extends FormRequest
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
            "practice_emr"                    => "required|max:15|alpha_num", //|unique:patients removed on 30March2019
           
        ];
    }


    
}	 