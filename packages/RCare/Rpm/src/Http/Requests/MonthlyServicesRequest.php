<?php
namespace RCare\Rpm\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class MonthlyServicesRequest extends FormRequest
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
        return [
            "review_data"                            => "required",
            "text_contact_number"                    => "required_if:review_data,1|required_if:contact_via,text", 
            "text_content_title"                     => "required_if:review_data,1|required_if:contact_via,text",
            "text_content_area"                      => "required_if:review_data,1|required_if:contact_via,text",
            // "call_status"                            => "required_if:review_data,1|required_if:contact_via,call",
            "within_guidelines_contact_number"       => "required_if:review_data,2", 
            //"within_guideline_content_title"         => "required_if:review_data,2",
            //"within_guideline_content_area_msg"      => "required_if:review_data,2",
            "answer"                                 => "required_if:call_status,2",
            "office_range_questionnaire.*"           => "required_if:patient_condition,1",
            "emergency_range_questionnaire.*"        => "required_if:patient_condition,2",
            // "record_episode_details"                 => "required_if:patient_condition,2", 
            "out_guidelines_contact_number"          =>"required_if:review_data,3" 
        ]; 

    } 
}