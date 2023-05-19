<?php
namespace RCare\Ccm\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientsAddRequest extends FormRequest
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
                'first_name'   => 'required', 
                'lirst_name'   => 'required', 
                'dob'          => 'required', 
                'email'        => 'nullable|required', 
                'org_id'       => 'nullable|required', 
                'partner_id'   => 'nullable|required', 
                'uid'          => 'nullable|required', 
                'home_number'  => 'nullable|required', 
                'mob'          => 'nullable|required', 
            ]
        );

    }
}
