<?php

namespace RCare\RCareAdmin\AdminPackages\Organization\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RcareOrgsAddRequest extends FormRequest
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
        return [
            //

            "name"                         => "required|max:40",                    
            // "logo_img"                     => "required|image|mimes:jpeg,png,jpg,gif|max:2048",
            "add1"                         => "required|max:40",
            "add2"                         => "nullable|max:40",
            "city"                         => "required|max:40",
            "state"                        => "required|max:40",
            "zip"                          => "required|max:6",
            "phone"                        => "required|max:20",
            "email"                        => "required|unique:pgsql.rcare_orgs,email",
            "contact_person"               => "required|max:40",
            "contact_person_phone"         => "max:30",
            "contact_person_email"         => "unique:pgsql.rcare_orgs,contact_person_email",



        ];
    }
}
