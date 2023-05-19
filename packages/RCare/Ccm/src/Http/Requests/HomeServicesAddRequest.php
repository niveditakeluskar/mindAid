<?php
namespace RCare\Ccm\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HomeServicesAddRequest  extends FormRequest
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
                'therapist_come_home_care' => 'required|integer',
                // 'wound_care'               => 'required_without_all:Injections_IV,catheter,tubefeeding,physio,oc_therapy,speech_therapy',
                // 'Injections_IV'            => 'required_without_all:wound_care,catheter,tubefeeding,physio,oc_therapy,speech_therapy',
                // 'catheter'                 => 'required_without_all:wound_care,Injections_IV,tubefeeding,physio,oc_therapy,speech_therapy',
                // 'tubefeeding'              => 'required_without_all:wound_care,Injections_IV,catheter,physio,oc_therapy,speech_therapy',
                // 'physio'                   => 'required_without_all:wound_care,Injections_IV,catheter,tubefeeding,oc_therapy,speech_therapy',
                // 'oc_therapy'               => 'required_without_all:wound_care,Injections_IV,catheter,tubefeeding,physio,speech_therapy',
                // 'speech_therapy'           => 'required_without_all:wound_care,Injections_IV,catheter,tubefeeding,physio,oc_therapy',
               'reason_for_visit'          => 'required_if:therapist_come_home_care,1|regex:/^[a-zA-Z0-9- . , ]*$/',
               'home_service_ends'         => 'required_if:therapist_come_home_care,1', 
               'service_end_date'          => 'nullable|required_if:home_service_ends,1|after:today', //therapist_come_home_care,1,
               'follow_up_date'            => 'nullable|required_if:home_service_ends,0|after:today', //therapist_come_home_care,1,
               'verification'              => 'required|integer', 
            ]   
        );

    }
}
