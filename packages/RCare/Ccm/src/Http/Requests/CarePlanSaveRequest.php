<?php
namespace RCare\Ccm\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarePlanSaveRequest extends FormRequest
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
                //diagnosis details 
                'diagnosis_id'              => 'required', 
                'code'                      => 'required',
                'new_code'                  => 'nullable|required_if:code,0|regex:/^[a-zA-Z :]+[0-9 .]+$/',
                'symptoms.*'                => 'required|text_comments_slash',
                'goals.*'                   => 'required|text_comments_slash',
                'tasks.*'                   => 'required|text_comments_slash',
                // 'support'                   => 'required',
                'comments'                  => 'nullable|text_comments_slash',

                //medications details
                //  'medication_id.*' =>'required',
                //'medications.med_id'        => 'required', 
                //'medications.*.*'           => 'required',
                // 'medications.*.purpose'     => 'required', 
                // 'medications.*.strength'    => 'required',
                // 'medications.*.dosage'      => 'required',
                // 'medications.*.route'       => 'required',
                // 'medications.*.frequency'   => 'required',
                // 'medications.*.duration'    => 'required',

                //allergies details
                // 'allergies.*'               => 'required',

                // //vital details
                // 'height'                    => 'required|numeric', 
                // 'weight'                    => 'required|int', 
                // 'bmi'                       => 'required|numeric', 
                // 'bp'                        => 'required|int',  
                // 'diastolic'                 => 'required|int', 
                // 'o2'                        => 'required|int', 
                // 'pulse_rate'                => 'required|int',  

                // //lab details
                // 'lab.*'                     => 'required', 
                // 'reading.*.*'               => 'required',
                // 'high_val.*.*'              => 'required|numeric',
                // 'notes.*'                   => 'required',

                // 'comments'                  => 'required'
            ]
        );

    }
}
