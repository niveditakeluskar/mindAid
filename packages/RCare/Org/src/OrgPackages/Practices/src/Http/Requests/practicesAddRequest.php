<?php
namespace RCare\Org\OrgPackages\Practices\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class practicesAddRequest extends FormRequest
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
                'name'                  => 'required|min:2|regex:/^[a-zA-Z\d\-_().,\s]+$/i',
                'number'                => 'required|min:6|alpha_num',
                'location'              => 'required|min:2|regex:/^[a-zA-Z0-9.-]*$/',
                'phone'                 => 'required|max:14|phone', 
                // 'key_contact'           => 'required|min:2|regex:/^[a-zA-Z0-9,.-]*$/',
                'key_contact'           => 'required|min:2|regex:/^[a-zA-Z0-9,.\s-]*$/', //for space
                // 'address'               => 'required|min:3|address',
                'address'               => 'required|min:3|regex:/^[a-zA-Z0-9,.\s-]*$/',
                'outgoing_phone_number' => 'required|max:14|phone',
                'billable'              => 'nullable|boolean',
                'practice_group'        => 'nullable|integer',
                'practice_type'         => 'required'
            ]
        );

    }
}
