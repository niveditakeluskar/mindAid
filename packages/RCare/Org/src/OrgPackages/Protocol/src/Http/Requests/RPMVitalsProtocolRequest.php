<?php

namespace RCare\Org\OrgPackages\Protocol\src\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class RPMVitalsProtocolRequest extends FormRequest
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
        return validationRules(True,[
         "device"  => "required",   
         'file' => 'required|max:10000|mimes:pdf'	
        ]
    );
    }


    
}	