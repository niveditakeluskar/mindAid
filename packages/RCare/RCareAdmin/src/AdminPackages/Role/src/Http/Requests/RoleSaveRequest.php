<?php

namespace RCare\RCareAdmin\AdminPackages\Role\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleSaveRequest extends FormRequest
{
    /**
     * Determine if the Test is authorized to make this request.
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
            'role_name' => 'required|min:3'
        ];
    }
}
