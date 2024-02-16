<?php
namespace RCare\Org\OrgPackages\DomainFeatures\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DomainFeaturesAddRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    /*public function authorize()
    {
        return true;
    }*/

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'url' => 'required',
            'features' =>'required',
            'digit_in_otp'=>'required',
            'otp_max_attempts'=>'required',
            'password_attempts'=>'required', 
            'no_of_days' =>'required|integer|min:1',
            'otp_text'=>'nullable|required_if:otp_email,0|',
            'otp_email'=>'nullable|required_if:otp_text,0|'
            // 'username'    => 'nullable|required_if:config_type,sms|min:4|max:60|regex:/^\S[a-z-A-Z_0-9-@.]*$/',
            // 'password'    => 'nullable|required_if:config_type,sms|min:4|max:60|regex:/^\S[a-z-A-Z_0-9-@.#!$]*$/',
            // 'user_name'   => 'nullable|required_if:config_type,email|min:4|max:40|regex:/^\S[a-z-A-Z_0-9-@.]*$/',
            // 'pass'        => 'nullable|required_if:config_type,email|min:4|max:20|regex:/^\S[a-z-A-Z_0-9-@.#!$]*$/',
            // 'api_url'     => 'nullable|required_if:config_type,sms|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            // 'from_name'   => 'nullable|required_if:config_type,email|min:4|max:20|regex:/^[a-zA-Z ]*$/',
            // 'from_email' =>  'nullable|required_if:config_type,email|email:rfc,dns',
            // // 'from_email'  => 'nullable|required_if:config_type,email|email',
            // 'host'        => 'nullable|required_if:config_type,email|regex:/^\S[a-z-A-Z_0-9-@.]*$/',
            // 'port'        => 'nullable|required_if:config_type,email|digits_between:2,4|numeric',
            // 'cc_email'    => 'nullable|required_if:config_type,email|email:rfc,dns|different:from_email',
        ];

    }
}