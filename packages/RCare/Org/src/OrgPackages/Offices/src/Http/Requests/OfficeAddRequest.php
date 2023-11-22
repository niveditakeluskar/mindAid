<?php

namespace RCare\Org\OrgPackages\Offices\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfficeAddRequest extends FormRequest
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
            'location' => 'required|regex:/^[a-z\d\-_().,\s]+$/i',
            'address' => 'nullable|min:3|regex:/^[a-z\d\-_().,\s]+$/i',
            'phone' =>'nullable|phone|max:14' 
        ];
    }
}
