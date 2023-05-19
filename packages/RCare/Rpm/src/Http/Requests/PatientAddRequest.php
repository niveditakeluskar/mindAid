<?php

namespace RCare\Rpm\Http\Requests;

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
            "practice_id"                     => "required|integer",
            "physician_id"                    => "required|integer", //|exists:practice_physicians,id",
            "fname"                           => "required|max:35",
            "mname"                           => "nullable|max:35",
            "lname"                           => "required|max:35",
            "gender"                          => "required|gender",
            "dob"                             => "required|date|after:".date('Y-m-d',strtotime('01/01/1902'))."|before:".date('d-m-Y'), //|before_or_equal:".date('d-m-Y')
            "addr1"                           => "required|max:70",
            "addr2"                           => "nullable|max:70",
            "city"                            => "required|max:35",
            "state"                           => "required|max:35",
            "zip"                             => "required|max:10",
            "ethnicity1"                      => "required|max:45",
            "ethnicity2"                      => "nullable|max:45",
            "education"                       => "required|max:45",
            "marital_status"                  => "required|max:45",
            "insurance_type.*"                => "required|max:45",
            "insurance_id.*"                    => "required",
            // "insurance_primary"               => "required|max:45",
			// "insurance_primary_idnum_check"   => "nullable|integer",
			// "insurance_primary_idnum"         => "required",//|unique_custom:patients,insurance_primary_idnum, insurance_primary_idnum_check",           
            // "insurance_secondary"             => "nullable|max:45",
			// "insurance_secondary_idnum_check" => "nullable|integer",
            // "insurance_secondary_idnum"       => "nullable|max:45",//|unique_secondary_custom:patients,insurance_primary_idnum, insurance_secondary_idnum_check",
            "education"                       => "required",
            "occupation_description"          => "nullable|max:255",
            "occupation_status"               => "nullable|int",
            "military"                        => "required|int",
            "phone_primary"                   => "required|phone",
            "phone_secondary"                 => "nullable|phone|different:phone_primary",
            "email"                           => "nullable|required_if:no_email,0|max:255",//|unique:patients,email,noemail@renovahealth.care,email",
            "no_email"                        => "nullable",
            "preferred_contact"               => "nullable|integer",
           // "preferred_contact"               => "required|integer", // "nullable|required_with:other_contact_name,other_contact_relationship,other_contact_phone,other_contact_email",
            "other_contact_name"              => "nullable|max:70",
            "other_contact_relationship"      => "nullable|max:70",
            "other_contact_phone"             => "nullable|phone",
            "other_contact_email"             => "nullable|max:255",
            "contact_time_*"                  => "nullable|integer", // "boolean",
            "emr"                             => "required|max:15", //|unique:patients removed on 30March2019
            "poa"                             => "boolean",
            "poa_name"                        => "nullable|required_if:poa,true|max:70",
            "poa_relationship"                => "nullable|required_if:poa,true|max:70",
            "poa_phone"                       => "nullable|required_if:poa,true|phone",
            "poa_email"                       => "nullable|required_if:poa,true|email"			
        ];
    }
}	