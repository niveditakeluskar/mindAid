<?php
namespace RCare\Org\OrgPackages\Users\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class OrgUserUpdateRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return validationRules(True, 
            [
                'f_name'                => 'required|alpha_spaces|max:50',
                'l_name'                => 'required|alpha_spaces|max:50',
                'email'                 => 'required|email:rfc,dns|unique:ren_core.users,email,'.$request->id,
                'role'                  => 'required|integer',
                'report_to'             => 'required|integer',
                'status'                => 'required|integer', 
               /* 'practice__id'          => 'required',*/
                // 'profile_img'           => 'required',
                // 'category_id'           => 'required',
               'extension'              =>'nullable|min:2|regex:/^[0-9]*$/',
                'office_id'             => 'nullable|integer',
                "emp_id"                => 'required|alpha_num|max:10|unique:ren_core.users,emp_id,'.$request->id
            ]
        );

    }
}
