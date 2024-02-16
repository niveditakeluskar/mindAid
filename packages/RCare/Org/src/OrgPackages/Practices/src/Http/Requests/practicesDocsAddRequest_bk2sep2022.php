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
                'practice_id'           => 'required'
                // //'provider_id'           => 'required',
                // 'doc_type'              => 'nullable',
                // 'doc_name'              => 'required|max:6|regex:/^[a-zA-Z0-9,.-]*$/',
                // // 'doc_comments'          => 'required|min:20|regex:/^[a-zA-Z0-9,.-]*$/'
                // 'file'                  => ['required','mimes:pdf','max:10000'],  
                // [
                //     'file.required' => "Please Select a File to Upload",
                //     'file.file' => "Invalid file type please upload only PDF file",
                //     'file.max' => "Allowed file size is :max"
                // ]);
            ]
        );

    }
}
?>
