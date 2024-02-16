<?php
namespace RCare\Patients\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RpmDeviceShippingRequest extends FormRequest
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
                // 'courier_service_provider'      => 'required', 
                // 'shipping_date'                 => 'required',
                // 'shipping_status'               => 'required',
                // 'status'                        => 'required',
                'device_code' => 'required|min:6|regex:/^[a-zA-Z0-9\s]+$/|unique:patients.patient_devices,device_code', 
            ], [
                'device_code.unique' => 'This Device Code already added' // Custom error message for the device code
            ]);
    }
}