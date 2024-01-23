<?php
namespace RCare\Ccm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreparationAddRequest extends FormRequest
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
            //'patient_relationship_building'      =>'nullable|required_with:call_preparation',
            // 'condition_requirnment1'              => 'required_without_all:condition_requirnment2,condition_requirnment3,condition_requirnment4', 
            // 'condition_requirnment2'              => 'required_without_all:condition_requirnment1,condition_requirnment3,condition_requirnment4', 
            // 'condition_requirnment3'              => 'required_without_all:condition_requirnment1,condition_requirnment2,condition_requirnment4',  
            // 'condition_requirnment4'              => 'required_without_all:condition_requirnment1,condition_requirnment2,condition_requirnment3', 
            // 'condition_requirnment_notes'         => 'nullable|text_comments_slash',   
            // 'newofficevisit'                      => 'required|integer',
            // 'nov_notes'                           => 'nullable|required_if:newofficevisit,1|min:2|text_comments_slash',
            // 'newdiagnosis'                        => 'required|integer',
            // 'nd_notes'                            => 'nullable|required_if:newdiagnosis,1|min:2|text_comments_slash',
            // 'med_added_or_discon'                 => 'required|integer',
            // 'med_added_or_discon_notes'           => 'nullable|required_if:med_added_or_discon,1|min:2|text_comments_slash',
            // 'report_requirnment1'                 => 'required_without_all:report_requirnment2,report_requirnment3',
            // 'report_requirnment2'                 => 'required_without_all:report_requirnment1,report_requirnment3',
            // 'report_requirnment3'                 => 'required_without_all:report_requirnment1,report_requirnment2',
            // 'anything_else'                      => 'nullable|min:2|text_comments_slash',
            // 'pcp_reviwewd'                        => 'nullable|required_if:this_month,1',
            ]
            );

}
}
