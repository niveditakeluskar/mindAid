<?php
namespace RCare\RCareAdmin\AdminPackages\Menu\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuAddRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    /*public function authorize()
    {
        return true;
    }*/

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'menu' => 'required|min:3',
            'menu_url' => 'required|min:3',
            'icon'  => 'nullable'
            
        ];

    }
}
