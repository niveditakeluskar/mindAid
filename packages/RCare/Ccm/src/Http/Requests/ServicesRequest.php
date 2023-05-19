<?php
namespace RCare\Ccm\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServicesRequest extends FormRequest
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
                'servicetype'  => 'required', 

                'type'          => 'nullable|required_if:servicetype,dme1'

                // 'brand'         => 'nullable|required_if:service_type,dme|required_if:service_type,medical_supplies|required_if:service_type,other_health',

                // 'specify'       => 'required',

                // 'from_whom'     => 'nullable|required_if:service_type,dialysis|required_if:service_type,home_health_services|required_if:service_type,therapy|required_if:service_type,social_services',

                // 'from_where'    => 'nullable|required_if:service_type,dialysis|required_if:service_type,home_health_services|required_if:service_type,therapy|required_if:service_type,social_services',

                // 'purpose'       => 'required',

                // 'frequency'     => 'nullable|required_if:service_type,dialysis|required_if:service_type,home_health_services|required_if:service_type,therapy|required_if:service_type,social_services', 
                
                // 'duration'      => 'nullable|required_if:service_type,dialysis|required_if:service_type,home_health_services|required_if:service_type,therapy|required_if:service_type,social_services' 

               // 'notes'         => 'required',
            ]
        );
    }
} 