<?php
namespace RCare\Patients\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CallSatusFinalAddRequest extends FormRequest
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
                'enroll_status'              => 'required',
                'call_back_date'             => 'nullable|after:today|required_if:enroll_status,2',
               // 'call_back_time'             => 'nullable|required_if:enroll_status,2',
                'enrl_refuse_reason'         => 'nullable|required_if:enroll_status,3',
            ]
        );
    }
}