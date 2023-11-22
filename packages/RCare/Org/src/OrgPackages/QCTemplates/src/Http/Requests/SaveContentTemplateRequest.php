<?php
namespace RCare\Org\OrgPackages\QCTemplates\src\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class SaveContentTemplateRequest extends FormRequest
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
                'content_title'   => 'required|min:3|regex:/^[a-zA-Z- ]*$/', 
                'template_type'   => 'required|integer',
                'module'          => 'required|integer', 
                'sub_module'      => 'required|integer',
                // 'stages'          => 'required',
                'from'            => 'nullable|email:rfc,dns|regex:/^[a-zA-Z0-9_.-]+@[a-zA-Z.]+.[a-zA-Z]$/|required_if:template_type,1', 
                'subject'         => 'nullable|required_if:template_type,1|min:3|regex:/^[a-zA-Z- . , ( )]*$/', 
                //'editorData'      => 'required|min:3]*$/|regex:/^[a-z0-9_\ \-\.\,\[\]]+$/i', 
            ]
        );

    }
}
