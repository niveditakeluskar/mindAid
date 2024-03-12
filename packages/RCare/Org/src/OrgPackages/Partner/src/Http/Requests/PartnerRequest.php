<?php

namespace RCare\Org\OrgPackages\Partner\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends FormRequest
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
            'name'  => 'required|regex:/^[a-zA-Z0-9-%+().# ]+$/', //|unique:ren_core.Partner,description,'.$this->id,
            'email' => 'required',
            'phone' => 'required',
            'add1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required|digits_between:5,10|numeric',
            'contact_person'=> 'required',
           // 'devices.*' =>'required',
            //'partner_device_name.*' =>'required',
            //'url.*' => 'nullable|required',
            //'username.*' => 'nullable|required',
            //'password.*' => 'nullable|required',
            //'status.*' => 'nullable|required',
            //'env.*'    => 'nullable|required'  

        ];
    }
}
