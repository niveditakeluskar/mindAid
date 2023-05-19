<?php
namespace RCare\Org\OrgPackages\Physicians\src\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Http\Request; 

class physiciansAddRequest extends FormRequest
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
            [
                'physician_name'        => 'required|unique:ren_core.physicians,name',
                'contact'               => 'required',
                'email'                 =>'required|email',
                'physicians_uid'        =>'required',
            ]
        );

    }
}
