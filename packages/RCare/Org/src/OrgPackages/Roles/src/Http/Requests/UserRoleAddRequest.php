<?php

namespace RCare\Org\OrgPackages\Roles\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRoleAddRequest extends FormRequest
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
        return [
            //
            'role_name' => 'required|alpha_spaces|max:50',
            'level'     => 'required|integer'
        ];
    }
}
