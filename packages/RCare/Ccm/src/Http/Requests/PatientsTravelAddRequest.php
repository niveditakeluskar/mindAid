<?php

namespace RCare\Ccm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientsTravelAddRequest extends FormRequest
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
                'travel_status' => 'required|integer',
                'location.*'   => 'nullable|required_if:travel_status,1|min:2|alpha_spaces',
                // 'travel_type.*'   => 'required',
                // 'frequency.*'   => 'required',
                // 'with_whom.*'   => 'required',
                // 'upcoming_tips.*'   => 'required',
                // 'notes'      => 'required', 
            ]
        );
    }
}
