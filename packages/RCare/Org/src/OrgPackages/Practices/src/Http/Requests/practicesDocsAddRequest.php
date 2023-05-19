<?php
namespace RCare\Org\OrgPackages\Practices\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class practicesDocsAddRequest extends FormRequest
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
                'practice_id'           => 'required',
                'provider_id'           => 'required',
                'doc_type'              => 'required',
                'other_doc_type'        => 'nullable|required_if:doc_type,0',
                'doc_name'              => 'required|min:6|alpha_spaces', //regex:/^[a-zA-Z0-9,.-]*$/',
                'doc_comments'          => 'nullable|min:6|regex:/^[a-zA-Z0-9\s]+$/',
                'file'                  => 'nullable|required_if:id,null|mimes:pdf,doc,docx|max:10000'
            ]
        );

    }
}