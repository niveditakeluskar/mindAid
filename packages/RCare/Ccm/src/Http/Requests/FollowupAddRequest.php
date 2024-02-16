<?php

namespace RCare\Ccm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FollowupAddRequest extends FormRequest
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
        return validationRules(
            True,
            [
                'task_name.*' => 'nullable|min:3|regex:/^[a-zA-Z0-9 - . , ()]*$/|required_with:followupmaster_task.*',
                'followupmaster_task.*' => 'nullable|integer|required_with:task_name.*',
                // // 'status_flag.*.*' =>'nullable|required',
                'task_date.*' => 'nullable|date|after_or_equal:today',
                'notes.*' => 'nullable|min:2|text_comments_slash', //required_with:task_name.*,followupmaster_task.*',  
                // 'emr_select'    => 'required_without:followup_task|min:1', 
                // 'followup_task' => 'required_without:emr_select', 
                // 'notes'         => 'required',
                'emr_complete'  => 'nullable|required_without:task_name.*'
                // 'notes'  =>'required',
                // 'emr_complete' =>'accepted'
                // // 'emr_select'          => 'required|min:1',  
                // // 'reseach_complete'    => 'required|accepted',
                // // 'care_coord_complete' => 'required|accepted'
            ]
        );
    }
}
