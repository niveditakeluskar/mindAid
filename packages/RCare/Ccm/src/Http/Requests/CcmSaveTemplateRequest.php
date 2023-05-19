<?php
namespace RCare\Ccm\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class CcmSaveTemplateRequest extends FormRequest
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
                'content_title'   => 'required', 
                'template_type'   => 'required', 
                'module'          => 'required', 
                'sub_module'      => 'required', 
                'stages'          => 'required', 
                'from'            => 'nullable|email|required_if:template_type,1', 
                'subject'         => 'nullable|required_if:template_type,1', 
                'editorData'      => 'required',
            ]
        );

    }
}
