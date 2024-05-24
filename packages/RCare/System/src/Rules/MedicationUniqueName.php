<?php
// created by Priya on 09oct20
// to check if meication already exist or not

// Modified by Pranali on 13oct20
// updated med_exist_check query and included medication model file

namespace RCare\System\Rules;

use Illuminate\Contracts\Validation\Rule;
use RCare\Org\OrgPackages\Medication\src\Models\Medication;

class MedicationUniqueName implements Rule
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
        $med_exist_check = Medication::where('description','iLIKE',$value)->exists();
        if ($med_exist_check == true) {
            return false;
        } else {
            return true;
        }
           
    }

    public function validate($attribute, $value, $parameters, $validator)
    {
        // return strlen((trim($value))) && $this->passes($attribute, $value);

        return trim($value) && $this->passes($attribute, $value); 
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans("validation.medication_unique_name");
       // return "medication name is already taken"; 
    }
}
