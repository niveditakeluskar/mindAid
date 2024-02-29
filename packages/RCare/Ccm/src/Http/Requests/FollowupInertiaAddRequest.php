<?php

namespace RCare\Ccm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FollowupInertiaAddRequest extends FormRequest
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
                // "folllowUpTaskData.*.task_name" => 'nullable|min:3|regex:/^[a-zA-Z0-9 - . , ()]*$/|required_with:folllowUpTaskData.*.selectedFollowupMasterTask',
                // 'folllowUpTaskData.*.selectedFollowupMasterTask' => 'nullable|integer|required_with:folllowUpTaskData.*.task_name',
                // 'folllowUpTaskData.*.task_date' => 'nullable|date|after_or_equal:today',
                // 'folllowUpTaskData.*.notes' => 'nullable|min:2|text_comments_slash',
                // 'emr_complete'  => 'required'  //'nullable|required_without:folllowUpTaskData.*.task_name'
            ]
        );
    }
}
