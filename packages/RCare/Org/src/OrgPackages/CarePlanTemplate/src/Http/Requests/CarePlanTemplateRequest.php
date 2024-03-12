<?php

namespace RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class CarePlanTemplateRequest extends FormRequest
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
                'allergies.*'      => 'nullable|regex:/^[a-zA-Z0-9()<>\' ]+$/',
                // 'drop_code'        => 'nullable|regex:/^[a-zA-Z :]+[0-9 .]+$/', //regex:/^[a-zA-Z0-9()<>\' ]+$/
                // 'drop_code'        => 'nullable|regex:/^[a-zA-Z0-9 .\']+$/', 
                'drop_code'        => 'nullable|regex:/^[a-zA-Z0-9()<>\' ]+$/',  
                'drop_condition'   => 'required|integer', 
                'health_data.*'    => 'nullable|regex:/^[a-zA-Z0-9()<>\' ]+$/',
                'labs.*'           => 'nullable|integer', 
                'medications.*'    => 'nullable|integer',
                // 'goals.*'          => 'required|regex:/^[a-zA-Z0-9()<> .,\' ]+$/',    //regex:/^[a-zA-Z0-9()<> .,\' ]+$/'
                // 'symptoms.*'       => 'required|regex:/^[a-zA-Z0-9()<> .,\' ]+$/',    //regex:/^[a-zA-Z0-9()<> .,\' ]+$/'
                // 'tasks.*'          => 'required|regex:/^[a-zA-Z0-9()<> .,\'-;& ]+$/', //regex:/^[a-zA-Z0-9()<> .,\' ]+$/'
                'goals.*'          => 'required', //|regex:/^[a-zA-Z0-9()<> .,\'-;& ]+$/
                'symptoms.*'       => 'required', //|regex:/^[a-zA-Z0-9()<> .,\'-;& ]+$/
                'tasks.*'          => 'required', //|regex:/^[a-zA-Z0-9()<> .,\'-;& ]+$/  
                'vitals.*'         => 'nullable|boolean',
                'newmedications.*' => 'nullable|required_if:medications.*,other',
                // // 'id'               => 'nullable||exists:id,id',
                // // 'condition'        => 'required', 
                // // 'code'             => 'required',
                // // 'drop_condition'   => 'nullable|required_without: id', 
                // // 'drop_code'        => 'nullable|required_without: id',
                // 'drop_condition'   => 'required', 
                // // 'drop_code'        => 'required',
                // 'symptoms.*'       => 'required',
                // 'goals.*'          => 'required',
                // 'tasks.*'          => 'required',
                // // 'medications.*'    => 'required',
                // 'newmedications.*' =>'nullable|required_if:medications.*,other',
                // // 'allergies.*'      => 'required',
                // // 'support'          => 'required',
                // // 'comments'         => 'required'
            ]
        );

    }
}
