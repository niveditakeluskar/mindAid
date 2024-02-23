<?php

namespace RCare\Ccm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientsHobbiesAddRequest extends FormRequest
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
                'hobbies_status' => 'required|integer',
                'hobbies_name.*' => 'nullable|required_if:hobbies_status,1|min:2|alpha_spaces',
                // 'location.*'   => 'required',
                // 'frequency.*'  => 'required', 
                // 'with_whom.*'  => 'required', 
                //     'notes.*'      => 'required', 
            ]
        );
    }
}
