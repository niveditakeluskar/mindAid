<?php

namespace RCare\Org\OrgPackages\Medication\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MedicationUpdateRequest extends FormRequest
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
            // 'description'          => 'required|regex:/^[a-zA-Z0-9-%+().# ]+$/|unique:ren_core.medication,description,'.$this->id, //|unique:ren_core.medication,description,'.$this->id,
            // 'drug_reaction' => 'nullable|regex:/^[a-zA-Z0-9-, ]+$/'
            'name' => 'required',

        ];
    }
}
