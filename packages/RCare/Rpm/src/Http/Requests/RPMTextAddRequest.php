<?php
namespace RCare\Rpm\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RPMTextAddRequest extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

   
    public function rules()
    {
        return validationRules(True,
            [
                'contact_no'  => 'required', 
                'template'    => 'required|integer', 
                'message'     => 'required|min:2|address'
              
            ]
        );

    }
}
