<?php
namespace RCare\Org\OrgPackages\QCTemplates\src\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class SaveQuestionnaireTemplateRequest extends FormRequest
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
                'content_title'              => 'required',//|min:3|regex:/^[a-zA-Z-]*$/', 
                'template_type'              => 'required|integer', 
                'module'                     => 'required|integer', 
                'sub_module'                 => 'required|integer', 
                // 'stages'                     => 'required',
                'question.q.*.questionTitle' => 'required',
                //|min:3|regex:/^[a-zA-Z- ? . ,]*$/',
                'question.q.*.answerFormat'  => 'required|integer'
            ]
        );

    }
}
