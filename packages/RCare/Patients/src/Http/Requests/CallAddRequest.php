<?php
namespace RCare\Patients\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CallAddRequest extends FormRequest
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
                'call_status'                 => 'required', 
                //'call_answer_template_id'     => 'nullable|required_if:call_status,1',
                'call_continue_status'        => 'nullable|required_if:call_status,1',
                // 'call_continue_followup_date' => 'nullable|required_if:call_continue_status,0',
                'voice_mail'                  => 'nullable|required_if:call_status,2',
                'phone_no'                    => 'nullable|required_if:call_status,2',
                // 'call_followup_date'          => 'nullable|required_if:call_status,2', 
                'call_not_answer_template_id' => 'nullable|required_if:call_status,2', 
                'text_msg'                    => 'nullable|required_if:call_status,2',
              //  'enroll_status'              =>'nullable|required_if:call_status,2',
                // 'pin_number'                  => 'nullable|max:15|alpha_num'
                'fin_number'                  => "nullable|alpha_num|min:10|max:10",  
            ]
        );
    }
}