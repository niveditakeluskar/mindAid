<?php

namespace RCare\Org\OrgPackages\Activity\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityEditRequest extends FormRequest 
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
            //
            'activity_type' =>'required|min:2|regex:/^[a-zA-Z-0-9 ]*$/',
            'activity'=>'required|min:2|regex:/^[a-zA-Z-0-9 ]*$/',  
            'default_time'=>'nullable|date_format:H:i:s|required_if:timer_type,2,3', 
            // 'time_required.*' => 'nullable|required_unless:practicesnew.*,null|date_format:H:i:s' 
            'time_required.*' => 'nullable|date_format:H:i:s'     
        ];
    }
}
