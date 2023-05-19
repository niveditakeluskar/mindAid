<?php
namespace RCare\Patients\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActiveDeactiveAddRequest extends FormRequest
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
            [   'status'                 => 'required',
                'activedeactivefromdate' => 'nullable|required_if:status,0|required_if:status,2',
                'deceasedfromdate'       => 'nullable|required_if:status,3|before_or_equal:'. date('Y-m-d'), 
                'activedeactivetodate'   => 'nullable|required_if:status,0|after:activedeactivefromdate',
                'deactivation_reason'    => 'required'//'required_if:status,0|required_if:status,2|required_if:status,3'
            ]
        );
    }
}