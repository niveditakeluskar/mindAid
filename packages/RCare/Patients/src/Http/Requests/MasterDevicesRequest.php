<?php
namespace RCare\Patients\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MasterDevicesRequest extends FormRequest
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
                'device_id' => 'required|min:6|regex:/^[a-zA-Z0-9\s]+$/', 
                //'devices'    => 'required',
                'partner_id' => 'required',
                'partner_devices_id' => 'required'
            ]
        );
    }
}