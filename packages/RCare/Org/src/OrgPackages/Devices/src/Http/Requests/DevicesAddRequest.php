<?php

namespace RCare\Org\OrgPackages\Devices\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DevicesAddRequest extends FormRequest
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
            'device_name' => 'required'
        ];
    }
}
