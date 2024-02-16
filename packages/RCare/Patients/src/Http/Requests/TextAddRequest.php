<?php
namespace RCare\Patients\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TextAddRequest extends FormRequest
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
                'contact_no'  => 'required', 
                'template'    => 'required', 
                'message'     => 'required', 
            ]
        );

    }
}
