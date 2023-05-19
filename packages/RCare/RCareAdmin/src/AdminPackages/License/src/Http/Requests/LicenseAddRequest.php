<?php

namespace RCare\RCareAdmin\AdminPackages\License\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LicenseAddRequest extends FormRequest
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
                "license_model"                => "required|max:40",
                "subscription_in_months"       => "required|max:40",
                "start_date"                   => "required|max:40",
                "end_date"                     => "required|max:40",
                "status"                       => "required|max:40"
            ]
        );
    }
}
