<?php

namespace RCare\Ccm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientsDataAddRequest extends FormRequest
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
        return validationRules(
            True,
            [
                'add_1'        => 'required|min:3|address',
                'home_number'  => 'nullable|max:14|phone',
                'email'        => 'nullable|email|email:rfc,dns|regex:/^[a-zA-Z0-9_.-]+@[a-zA-Z.]+.[a-zA-Z]$/',
                'mob'          => 'required|max:14|phone',
            ]
        );
    }
}
