<?php
namespace RCare\Ccm\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientsVitalsDataAddRequest extends FormRequest
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
            // [ 
            // 'height'    => 'nullable|between:0,99.99|required_without_all:weight,bmi,bp,diastolic,o2,pulse_rate', 
            // 'weight'    => 'nullable|between:0,99.99|required_without_all:height,bmi,bp,diastolic,o2,pulse_rate', 
            // 'bmi'       => 'nullable|between:0,99.99|required_without_all:height,weight,bp,diastolic,o2,pulse_rate', 
            // 'bp'        => 'nullable|between:0,99.99|required_without_all:height,weight,bmi,diastolic,o2,pulse_rate',  
            // 'diastolic' => 'nullable|between:0,99.99|required_without_all:height,weight,bp,diastolic,o2,pulse_rate', 
            // 'o2'   => 'nullable|between:0,99.99|required_without_all:height,weight,bp,diastolic,diastolic,pulse_rate', 
            // 'pulse_rate'=> 'nullable|between:0,99.99|required_without_all:height,weight,bp,diastolic,diastolic,o2',  
            // ]
            [ 
                'height'    => 'nullable|numeric', 
                'weight'    => 'nullable|numeric',  
                // 'bmi'       => 'nullable|numeric', 
                'bp'        => 'nullable|numeric',   
                'diastolic' => 'nullable|numeric',  
                'o2'   => 'nullable|numeric',  
                'pulse_rate'=> 'nullable|numeric',  
                'oxygen'   => 'nullable|integer',
                'notes'    =>  'nullable|required_if:oxygen,0|text_comments_slash'   
                ]
        );

    }
}
