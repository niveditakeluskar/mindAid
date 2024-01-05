<?php
namespace RCare\Ccm\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientsProvidersAddRequest extends FormRequest
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
                'practice_id'           => 'required|integer', 
                'provider_id'           => 'required|integer',
                'provider_name'         => 'nullable|required_if:provider_id,0|provider_unique_name|min:2|max:50|regex:/^[a-zA-Z- . , ( )]*$/', //added by pranali on 29Oct2020 regex added by priya on 2feb 2021
                'address'               => 'required|min:3|address',
                'phone_no'              => 'required|phone|max:14',  
                'last_visit_date'       => 'required|before:tomorrow',
                'provider_subtype_id'   => 'required|integer',//nullable|required_if:provider_type_id,2,1
                'provider_type_id'      => 'required|integer',
                'specialist_id'         => 'required|integer', 
                
            ]
        );

    }
}
