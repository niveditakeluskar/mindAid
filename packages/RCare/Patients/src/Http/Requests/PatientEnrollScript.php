<?php

namespace RCare\Patients\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientEnrollScript extends FormRequest
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
           // "script"                     => "required",
           // "text_msg"                     => "required", 
        ];
    }


    
}	  