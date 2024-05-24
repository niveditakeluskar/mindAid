<?php
namespace RCare\Org\OrgPackages\Practices\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class practicesGrpAddRequest extends FormRequest
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
                'practice_name'    => 'required|min:2|alpha_spaces',//|unique:ren_core.practicegroup,practice_name',
            ]
        );

    }
}
    