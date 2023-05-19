<?php

namespace RCare\Patients\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientProfileImage extends FormRequest
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
         "fname"  => "required|max:35|alpha",           
             "lname"  => "required|max:35|alpha",
              'file' => 'max:1500|mimes:jpg,jpeg,png,bmp,gif',	
        ];
    }


    
}	