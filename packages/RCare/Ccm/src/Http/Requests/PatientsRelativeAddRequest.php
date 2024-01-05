<?php
namespace RCare\Ccm\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientsRelativeAddRequest extends FormRequest
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
                'tab_name'          => 'required',
                'relational_status' => 'nullable|integer|required_if:tab_name,live-with|required_if:tab_name,sibling|required_if:tab_name,children|required_if:tab_name,grandchildren',
                // 'fname.*'           => 'nullable|alpha_spaces|min:2|required_if:relational_status,1',  
                // 'lname.*'           => 'nullable|alpha_spaces|min:2|required_if:relational_status,1',  
                'fname.*'           => 'nullable|required_if:relational_status,1|min:2',  
                'lname.*'           => 'nullable|required_if:relational_status,1|min:2',  
               
                // 'address.*'         => 'nullable',//required_if:relational_status,1|required_if:tab_name,sibling|required_if:tab_name,children|required_if:tab_name,grandchildren',
                //'relationship.*'    => 'nullable|required_if:relationship,Select',//|required_if:relational_status,1|required_if:tab_name,live-with', 
                //'relationship_txt.*' =>'nullable|string|required_if:relationship,0|',
                'age.*'             => 'nullable|numeric',//|required_if:relational_status,1',//|required_if:tab_name,children|required_if:tab_name,sibling|required_if:tab_name,grandchildren',
            ] 
       );  

    }
}
