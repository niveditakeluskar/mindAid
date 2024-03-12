<?php

namespace RCare\Ccm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientsHealthDataRequest extends FormRequest
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
                'health_data.*'        => 'required|regex:/^[a-zA-Z0-9 -]+$/',
                'health_date.*'       => 'required|date|before:tomorrow'

            ]
        );
    }
}
