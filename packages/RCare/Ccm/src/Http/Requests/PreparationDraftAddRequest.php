<?php
namespace RCare\Ccm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreparationDraftAddRequest extends FormRequest
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
            'patient_relationship_building'       =>'nullable',
            'condition_requirnment1'              => 'nullable', 
            'condition_requirnment2'              => 'nullable', 
            'condition_requirnment3'              => 'nullable',  
            'condition_requirnment4'              => 'nullable', 
            'condition_requirnment_notes'         => 'nullable|text_comments_slash',
            'newofficevisit'                      => 'nullable',
            'nov_notes'                           => 'nullable', 
            'newdiagnosis'                        => 'nullable',
            'nd_notes'                            => 'nullable|text_comments_slash',
            'med_added_or_discon'                 => 'nullable',
            'med_added_or_discon_notes'           => 'nullable|text_comments_slash',
            'report_requirnment1'                 => 'nullable',
            'report_requirnment2'                 => 'nullable',
            'report_requirnment3'                 => 'nullable',
            'anything_else'                       => 'nullable|text_comments_slash'
            ]
            );

}
}
