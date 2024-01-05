<?php
namespace RCare\Org\OrgPackages\Providers\src\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Http\Request; 

class providersTypeAddRequest extends FormRequest
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
            [   'provider_type'  =>'required|min:2|max:50|alpha_spaces|unique:ren_core.provider_types,provider_type'//regex:/^[a-zA-Z- ]*$/',
                // 'provider_type'        => 'required|unique:ren_core.providers,provider_type',
                // 'phone_no'             => 'required',
                // 'address'              =>'required', 
                // 'email_id'             =>'required|email',
                // 'physicians_uid'    =>'required',
            ]
        );

    }
}
