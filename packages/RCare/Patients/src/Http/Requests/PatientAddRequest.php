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
            "provider_id"                     => "required|integer", //|exists:practice_physicians,id",
            'provider_name'                   => 'nullable|required_if:provider_id,0|provider_unique_name', //added by pranali on 29Oct2020
            "fname"                           => "required|max:35|regex:/^[\pL\s\- ._]+$/u",
            "mname"                           => "nullable|max:35|regex:/^[\pL\s\- ._]+$/u",
            "lname"                           => "required|max:35|regex:/^[\pL\s\- ._]+$/u",
            "gender"                          => "required|gender",
            "dob"                             => "required|date|after:".date('Y-m-d',strtotime('01/01/1902'))."|before:".date('d-m-Y'), //|before_or_equal:".date('d-m-Y')
            "add_1"                           => "required|max:70",
            "add_2"                           => "nullable|max:70",
            "city"                            => "required|max:35",
            "state"                           => "required|max:35",
            "fin_number"                      => "nullable|required_if:organization,Memorial Hospital Gulfport|alpha_num|min:10|max:10",
            "zipcode"                         => "required|digits_between:5,10|numeric",
           // "ethnicity"                       => "required|max:45",
           // "ethnicity_2"                     => "nullable|max:45",
           // "education"                       => "required|max:45",
           "marital_status"                   => "required|max:45",
            // "insurance_type.*"                => "required|max:45",
            // "insurance_id.*"                  => "required",
            // "insurance_primary"               => "required|max:45",
            // "insurance_primary_idnum_check"   => "nullable|integer",
            // "insurance_primary_idnum"         => "required|unique_custom:patients,insurance_primary_idnum, insurance_primary_idnum_check",           
            // "insurance_secondary"             => "nullable|max:45",
            // "insurance_secondary_idnum_check" => "nullable|integer",
            // "insurance_secondary_idnum"       => "nullable|max:45|unique_secondary_custom:patients,insurance_primary_idnum, insurance_secondary_idnum_check",
            "occupation_description"          => "nullable|max:255",
            "occupation"                      => "nullable|int",
            "military_status"                 => "required|int",
            "mob"                             => "required|phone",
            "home_number"                     => "nullable|phone|different:mob",
            "email"                           => "nullable|required_if:no_email,0|max:255",//|unique:patients,email,noemail@renovahealth.care,email",
            "no_email"                        => "nullable|boolean",
            "preferred_contact"               => "nullable|integer",
           // "preferred_contact"               => "required|integer", // "nullable|required_with:other_contact_name,other_contact_relationship,other_contact_phone,other_contact_email",
            "primary_cell_phone"              => "nullable|boolean",
            "secondary_cell_phone"            => "nullable|boolean",
            "other_contact_name"              => "nullable|max:70",
            "other_contact_relationship"      => "nullable|max:70",
            "other_contact_phone_number"      => "nullable|phone",
            "other_contact_email"             => "nullable|max:255",
            "contact_time_*"                  => "nullable|integer", // "boolean",
            "practice_emr"                    => "required|max:15|alpha_num", //|unique:patients removed on 30March2019
            "poa"                             => "nullable|boolean",
            "poa_first_name"                  => "nullable|required_if:poa,true|max:70",
            "poa_last_name"                   => "nullable|required_if:poa,true|max:70",
            "poa_relationship"                => "nullable|required_if:poa,true|max:70",
            // "poa_mobile"                      => "nullable|required_if:poa,true|phone",
            "poa_phone_2"                     => "nullable|phone",
            "poa_email"                       => "nullable|required_if:poa,true|email",
            // "poa_age"                         => "nullable|required_if:poa,true|integer"	
			 "entrollment_from"                => "required",
            "contact_preference_calling"      => "nullable|boolean",
            "contact_preference_sms"          => "nullable|boolean",
            "contact_preference_email"        => "nullable|boolean",
            "contact_preference_letter"       => "nullable|boolean",		
        ];
    }


    
}	