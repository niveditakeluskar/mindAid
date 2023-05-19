<?php

namespace RCare\Org\OrgPackages\Activity\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityAddRequest extends FormRequest 
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
            'activitytype' => 'required|min:2|regex:/^[a-zA-Z-0-9 ]*$/',   
            'activity.*.activity'=>'required|min:2|regex:/^[a-zA-Z-0-9 ]*$/',
            'activity.*.defaulttime'=>'nullable|date_format:H:i:s|required_if:activity.*.activitydropdown,2,3' 
		    // 'activity.*.newactivitypracticebasedtime.*' => 'nullable|date_format:H:i:s|required_unless:activity.*.practices.*,null',
            // 'activity.*.newactivitypracticebasedtime.*' => 'nullable|date_format:H:i:s'      
            //  'activity.*.newactivitypracticebasedtime.*' => 'required' 
            
              
            
        ];
    }
}
