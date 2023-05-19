<?php

namespace RCare\Org\OrgPackages\FollowupTask\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FollowupTaskAddRequest extends FormRequest 
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
        return [
            'task' => 'required|min:3|unique:ren_core.followup_tasks,task|regex:/^[a-zA-Z . , ( )]*$/', 
        ];
    }
}
