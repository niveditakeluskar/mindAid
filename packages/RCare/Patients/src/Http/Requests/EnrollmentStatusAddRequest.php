<?php
namespace RCare\Patients\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentStatusAddRequest extends FormRequest
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
                'enrollment_status_template_id' => 'required',
                'enrol_status'                  => 'required',
                'call_back_date'                => 'nullable|required_if:enrol_status,2|after:today'
               // 'call_back_time'                => 'nullable|required_if:enrol_status,2|after:today'
            ]
        );
    }
}