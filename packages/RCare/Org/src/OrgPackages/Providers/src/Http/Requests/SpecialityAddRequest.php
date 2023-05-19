<?php
namespace RCare\Org\OrgPackages\Providers\src\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Http\Request; 

class SpecialityAddRequest extends FormRequest
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
                'speciality' =>'required|min:2|max:50|alpha_spaces|unique:ren_core.speciality,speciality'//regex:/^[a-zA-Z- ]*$/',                
            ]
        );

    }
}
