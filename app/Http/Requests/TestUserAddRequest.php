<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestUserAddRequest extends FormRequest
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
                'f_name'   => 'required',
                'l_name'   => 'required',
                'email'    => 'required|email',
                'password' => 'required|string|min:6|confirmed'
            ]
        );
    }
}
