<?php
namespace RCare\Org\OrgPackages\Providers\src\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Http\Request; 

class providersSubtypeAddRequest extends FormRequest
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
            [   'provider_type_id'         =>'required|integer',
                'sub_provider_type'        => 'required|min:2|max:50|alpha_spaces'//regex:/^[a-zA-Z- ]*$/',
                // 'phone_no'             => 'required'|unique:ren_core.providers,sub_provider_types,
                // 'address'              =>'required',
                // // 'email_id'             =>'required|email',
                // 'physicians_uid'    =>'required',
            ]

            
        );

    }
}
