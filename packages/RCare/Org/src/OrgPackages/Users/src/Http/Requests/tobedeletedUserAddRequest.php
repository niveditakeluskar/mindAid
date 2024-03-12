<?php

namespace RCare\Org\OrgPackages\Users\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAddRequest extends FormRequest
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
            'f_name' => 'required',
            'l_name' => 'required',
            'email'  => 'required|unique:pgsql.ren_core.users,email',
            'password' => 'required|min:6|confirmed'
        ];

    }
}
