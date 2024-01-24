<?php

namespace RCare\Ccm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientsLabRequest extends FormRequest
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
                'lab.*'        => 'required',
                'reading.*.*'  => 'required',
                'high_val.*.*' => 'required_if:reading.*.*,high,normal,low',
                'labdate.*'    => 'required',
                'labdate.*.*'  => 'required|before_or_equal:today',
                'notes.*'      => 'nullable|text_comments_slash' //'required'
            ]
        );
    }
}
