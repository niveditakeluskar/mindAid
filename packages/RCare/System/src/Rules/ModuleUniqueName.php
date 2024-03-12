<?php
// created by Pranali on 16June2022
namespace RCare\System\Rules;

use Illuminate\Contracts\Validation\Rule;
use RCare\Org\OrgPackages\Modules\src\Models\Module;

class ModuleUniqueName implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $module_exist_check = Module::where('module','iLIKE',$value)->exists();
        if ($module_exist_check == true) {
            // $module_update_exist_check = Module::where('module','iLIKE',$value)->ignore($this->id)->exists();
            // if ($module_update_exist_check == true) {
            //     return false;
            // } else {
            //     return true;
            // }
            return false;
        } else {
            return true;
        }
    }

    public function validate($attribute, $value, $parameters, $validator)
    {
        return trim($value) && $this->passes($attribute, $value); 
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans("validation.module_unique_name");
       // return "module name is already taken"; 
    }
}
