<?php

namespace RCare\System\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManuallyAdjustTimeRequest extends FormRequest
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
            "care_manager_id" => "required",
            'time'            => "required|time_textbox|max:8", // updated by pranali on 30Oct2020 added max and time_textbox condition
            'time_to'         => "required",
            'billable'        => "required", // updated by pranali on 18Jann2022 added required
            'module'          => "required",
            'submodule_id'    => "required",
            'comment'         => "required",
        ];

    }
}
