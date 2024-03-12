<?php

namespace RCare\Ccm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientsFamilyAddRequest extends FormRequest
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
                'fname'        => 'required|min:2|alpha_spaces',
                'lname'        => 'required|min:2|alpha_spaces',
                'address'      => 'required|min:3|address',
                'email'        => 'nullable|email|email:rfc,dns|regex:/^[a-zA-Z0-9_.-]+@[a-zA-Z.]+.[a-zA-Z]$/',
                'mobile'       => 'nullable|phone|max:14',
                'phone_2'      => 'nullable|phone|max:14',
                // 'phone_2'      => 'nullable|phone|required_if:tab_name,spouse|required_if:tab_name,care-giver|required_if:tab_name,emergency-contact',
            ]
        );
    }
}
