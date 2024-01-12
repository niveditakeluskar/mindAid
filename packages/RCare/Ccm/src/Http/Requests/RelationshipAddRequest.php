<?php
namespace RCare\Ccm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RelationshipAddRequest extends FormRequest
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
                //'questionnaire' => 'required', 
                //'Introduction_Questionnaire.question.*'=>'required',
                // 'Introduction_Questionnaire.current_monthly_notes' => 'nullable|regex:/^[a-zA-Z0-9- . , ( )]*$/',
                //'Seasonal_Questionnaire.current_monthly_notes' => 'nullable|regex:/^[a-zA-Z0-9- . , ( )]*$/',  
                //'Personal_Questionnaire.question.*'=>'nullable|regex:/^[a-zA-Z0-9- . , ( )]*$/',
                //'Personal_Questionnaire.question.*'=> 'required',
                //'Seasonal_Questionnaire.question.*'=>'required'        
            ]
            
        );

    }
}
