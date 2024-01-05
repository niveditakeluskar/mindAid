<?php
namespace RCare\Ccm\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientsImagingRequest extends FormRequest
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
        return validationRules(True,
            [
                'imaging.*'        => 'required|regex:/^[a-zA-Z0-9 -]+$/',
                'imaging_date.*'        => 'required|date|before:tomorrow'
             
            ]
        );

    }
}
