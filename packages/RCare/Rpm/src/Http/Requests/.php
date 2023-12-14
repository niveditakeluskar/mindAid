<?php
namespace RCare\Org\OrgPackages\BulkUploadFinNumber\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinBulkURequest extends FormRequest
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
                'practices'           => 'required',
                'file'           	=> 'required'
            ]
        );

    }
}