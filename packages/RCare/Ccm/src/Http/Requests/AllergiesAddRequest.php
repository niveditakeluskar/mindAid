<?php
namespace RCare\Ccm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AllergiesAddRequest extends FormRequest
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
                'specify'                     => 'required_if:allergy_status,false', 
                'type_of_reactions'           => 'required_if:allergy_status,false', 
                'severity'                    => 'required_if:allergy_status,false',
                'course_of_treatment'         => 'required_if:allergy_status,false',
                'notes'                       => 'nullable|text_comments_slash', 
                     
            ]
        );
    }
}