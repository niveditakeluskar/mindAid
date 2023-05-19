<?php
// created by Pranali on 29oct20
// to check if provider already exist or not

namespace RCare\System\Rules;

use Illuminate\Contracts\Validation\Rule;
use RCare\Rpm\Models\Providers;

class ProviderUniqueName implements Rule
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
        $providers_exist_check = Providers::where('name','iLIKE',$value)->exists();
        if ($providers_exist_check == true) {
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
        return trans("validation.provider_unique_name");
    }
}