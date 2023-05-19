<?php
namespace RCare\Ccm\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GeneralQuestionRequest extends FormRequest
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
                // 'call_status'                 => 'required|integer', 
                // 'call_answer_template_id'     => 'nullable|required_if:call_status,1|integer',
                // 'call_continue_status'        => 'nullable|required_if:call_status,1|integer',  
                // // call_continue_followup_date
                // 'answer_followup_date' => 'nullable|required_if:call_continue_status,0|after:today',
                // 'voice_mail'                  => 'nullable|required_if:call_status,2',
                // //'phone_no'                    => 'nullable|required_if:call_status,2',
                // 'call_followup_date'          => 'nullable|required_if:call_status,2|after:today', 
                //'call_not_answer_template_id' => 'nullable|required_if:call_status,2',
                'monthly_notes.*' =>'nullable|text_comments_slash'
            ]
        );
    }
}