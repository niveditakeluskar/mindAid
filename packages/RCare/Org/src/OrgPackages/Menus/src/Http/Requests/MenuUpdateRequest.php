<?php
namespace RCare\Org\OrgPackages\Menus\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuUpdateRequest extends FormRequest
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
                'menu'         => 'required|min:3|alpha_spaces',
                'menu_url'     => 'required|regex:/^[a-zA-Z-\/#]+$/',//unique:ren_core.menu_master,menu_url',
                'icon'         => 'required|regex:/^[a-zA-Z0-9-]+$/',
                'component_id' => 'required|integer',
                'module_id'    => 'nullable|integer',
                'parent'       => 'required|integer',
                'status'       => 'required|integer',
                'sequence'     => 'required|integer',
                'operation'	   => 'required|regex:/^[crud]+$/',
                'service_id'   => 'required|integer'
            ]
        );
    }
}
