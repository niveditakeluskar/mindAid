<?php
namespace RCare\Ccm\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use RCare\System\Rules\MedicationUniqueName;

class PatientsMedicationAddRequest extends FormRequest
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
            [   'patient_id'           =>'required|integer',
                'med_id'               =>'required', //|unique:ren_core.medication,description
                'med_description'      =>'nullable|min:2|required_if:med_id,other|medication_unique_name', //new MedicationUniqueName,
                // 'description'          =>'required',
                'purpose'              =>'required|min:2|alpha_spaces', 
                'dosage'               =>'required|min:2',    
                'strength'             =>'required|min:2',    
                'frequency'            =>'required|min:2', 
                'route'                =>'required|min:2|alpha_spaces',
                'duration'             =>'required|min:2',
                'pharmacy_name'        =>'nullable|min:2',
                'pharmacy_phone_no'    =>'nullable|max:14|phone',
                // 'drug_reaction'        =>'required',
                // 'pharmacogenetic_test' =>'required', 
                // 'uid'                  =>'required',
            ]
        );

    }
}
