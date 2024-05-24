<?php

namespace RCare\Org\OrgPackages\ReportsMaster\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportsMasterRequest extends FormRequest
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
                'report_name'             => 'required',
                'report_file_path'        => 'required',
                'management_status'       => 'required'
            ]
        );
    }
}
