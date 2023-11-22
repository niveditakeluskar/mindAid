<?php
namespace RCare\Org\OrgPackages\Calender\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class calendarAddRequest extends FormRequest
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
                'practice_id'           => 'required',
                'patient'           	=> 'required',
                'modules'               => 'required',
                'submodule_id'          => 'required',
                'event_start_date'      => 'required', 
                'event_end_date'		=> 'required',
                'event_name'            => 'required|min:6'
            ]
        );

    }
}