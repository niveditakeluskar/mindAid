<?php
namespace RCare\Org\OrgPackages\Users\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class OrgUserOrganizationUpdateRequest extends FormRequest
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
                'org_id'          => 'required|integer'
            ]
        );

    }
}
