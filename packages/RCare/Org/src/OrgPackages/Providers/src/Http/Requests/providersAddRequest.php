<?php
namespace RCare\Org\OrgPackages\Providers\src\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Http\Request; 

class providersAddRequest extends FormRequest
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
        return validationRules(True,
            [   'practice_id'          =>'required|integer', 
                // 'name'                 =>'required|unique:ren_core.providers,name',
                'provider_type_id'     =>'required|integer',
                'provider_subtype_id'  =>'nullable|integer',
                'speciality_id'        =>'nullable|integer',
                'name'                 =>'required|min:2|max:50|regex:/^[a-zA-Z- . , ( )]*$/',//[a-zA-Z0-9- ]
                'phone'                =>'required|phone|max:14',
                'address'              =>'required|address|min:3', 
                'email'                =>'required|email:rfc,dns|regex:/^[a-zA-Z0-9_.-]+@[a-zA-Z.]+.[a-zA-Z]$/',//email:rfc,dns|
                // 'physicians_uid'    =>'required',
            ]
        );

    }
}
