<?php

namespace RCare\RCareAdmin\AdminPackages\Users\src\Http\Requests;

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
        return  validationRules(True,
            [
            'f_name' => 'required|alpha|max:50',
            'l_name' => 'required|alpha|max:50',
            'email'  => 'required|unique:pgsql.rcare_users,email',
            'role'   =>   'required|max:100',
            'profile_img' => 'required',
            // 'select_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            //'email' => 'required|email|unique:employee_auths',
            /*'password' => 'required|string|min:6|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',*/
              'password' => 'required|string|min:6|confirmed',
        ]

    );

    }
}
