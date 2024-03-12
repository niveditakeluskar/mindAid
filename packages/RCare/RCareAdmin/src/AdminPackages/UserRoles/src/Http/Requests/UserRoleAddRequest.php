<?php
namespace RCare\RCareAdmin\AdminPackages\UserRoles\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRoleAddRequest extends FormRequest
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
                'role_name' => 'required|unique:rcare_roles',
                'status'    => 'required'
            ]
        );
    }
}