<?php

namespace RCare\TaskManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientActivityAddRequest extends FormRequest
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
            'activity_id' => 'required',
            'net_time' => 'required|TimeTextbox', 
            'timer_type' =>'nullable',
            'notes' =>'nullable|required_if:timer_type,1',
        ];

    }
}
