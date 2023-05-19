<?php
namespace RCare\Ccm\src\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class ServicesAddRequest extends FormRequest
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
                'service_type'       => 'required', //regex:/^[a-zA-Z0-9 -]+$/
                'type'               => 'required|min:3|alpha_spaces',//'nullable|required_if:service_type,dme|required_if:service_type,medical_supplies|required_if:service_type,other_health',
                'brand'              => 'required|min:3|alpha_spaces',//'nullable|required_if:service_type,dme|required_if:service_type,medical_supplies|required_if:service_type,other_health',
                'specify'            => 'required|min:3|alpha_spaces',
                'purpose'            => 'required|min:3|alpha_spaces',
                'service_start_date' => 'nullable|date|required_if:service_type,dialysis,home_health_services,therapy,social_services',// required_if:service_type,dialysis|required_if:service_type,home_health_services|required_if:service_type,therapy|required_if:service_type,social_services', //before:service_end_date|
                'service_end_date'   => 'nullable|date|after:service_start_date',  //|after:'.date("Y-m-d",strtotime("01/01/2000 ")).'|required_if:service_type,dialysis|required_if:service_type,home_health_services|required_if:service_type,therapy|required_if:service_type,social_services',
                'frequency'          => 'nullable|min:3|alpha_spaces|required_if:service_type,dialysis,home_health_services,therapy,social_services', 
                // 'duration'           => 'nullable|required_if:service_type,dialysis|required_if:service_type,home_health_services|required_if:service_type,therapy|required_if:service_type,social_services'
                'notes'              => 'nullable|text_comments_slash', 
            ]
        );
    }
} 