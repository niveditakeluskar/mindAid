<?php

namespace RCare\Org\OrgPackages\RPMBillingConfiguration\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RPMBillingAddRequest extends FormRequest
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
            'vital_review_time' => 'required|time_textbox|max:8',
            'billing_phone'=> 'required',
            'billing_fname'=> 'required',
            'billing_lname'=> 'required',
            'billing_address'=> 'required',
            'billing_city'=> 'required',
            'billing_state'=> 'required',
            'billing_zip'=> 'required|min:5|max:5',
            //'billing_email' => 'required',
            'headquaters_phone' => 'required_if:headqadd,false',
            'headquaters_fname' => 'required_if:headqadd,false',
            'headquaters_lname' => 'required_if:headqadd,false',
            'headquaters_address' => 'required_if:headqadd,false',
            'headquaters_city' => 'required_if:headqadd,false',
            'headquaters_state' => 'required_if:headqadd,false',
            'headquaters_zip' => 'required_if:headqadd,false|min:5|max:5'
            //  'headquaters_email'  => 'required_if:headqadd,false'
        ];
    }
}
