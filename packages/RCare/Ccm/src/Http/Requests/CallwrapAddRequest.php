<?php
namespace RCare\Ccm\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CallwrapAddRequest extends FormRequest
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
               
                'emr_monthly_summary.*'          =>'nullable|min:3|regex:/^[a-zA-Z0-9- . , ]*$/',                
                'notes'                          =>'nullable|min:3|text_comments_slash',
                'emr_monthly_summary_date.*'     => 'required|before_or_equal:today',  
                'emr_monthly_summary.*'          => 'required_with:emr_monthly_summary_date|text_comments_slash'                   
                // 'emr_entry_completed'            => 'required_without_all:schedule_office_appointment,resources_for_medication,medical_renewal,called_office_patientbehalf,referral_support,no_other_services',    
                // 'schedule_office_appointment'   => 'required_without_all:emr_entry_completed,resources_for_medication,medical_renewal,called_office_patientbehalf,referral_support,no_other_services', 
                // 'resources_for_medication'      => 'required_without_all:emr_entry_completed,schedule_office_appointment,medical_renewal,called_office_patientbehalf,referral_support,no_other_services', 
                // 'medical_renewal'               => 'required_without_all:emr_entry_completed,schedule_office_appointment,resources_for_medication,called_office_patientbehalf,referral_support,no_other_services',    
                // 'called_office_patientbehalf'   => 'required_without_all:emr_entry_completed,schedule_office_appointment,resources_for_medication,medical_renewal,referral_support,no_other_services', 
                // 'referral_support'              => 'required_without_all:emr_entry_completed,schedule_office_appointment,resources_for_medication,medical_renewal,called_office_patientbehalf,no_other_services',
                // 'no_other_services'             => 'required_without_all:emr_entry_completed,schedule_office_appointment,resources_for_medication,medical_renewal,called_office_patientbehalf,referral_support',   
                  
            ]    
        );

    }
}
