<?php
namespace RCare\Ccm\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientsPetAddRequest extends FormRequest
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
            [   'pet_status'   =>  'required|integer',
                'pet_name.*'   => 'nullable|required_if:pet_status,1|min:2|alpha_spaces', 
                // 'pet_type.*'   => 'required', 
                // 'notes.*'      => 'required', 
            ]
        );

    }
}
