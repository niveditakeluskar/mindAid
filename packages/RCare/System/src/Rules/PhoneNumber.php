<?php

namespace RCare\System\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneNumber implements Rule
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
        $matches = [];
        $regex = "/\([0-9]{3}\)\s[0-9]{3}\-[0-9]{4}/";
        if (preg_match($regex, $value, $matches)) { 
            return $matches[0] === $value;
        }
        return False;
    }

    public function validate($attribute, $value, $parameters, $validator)
    {
        return strlen((trim($value))) && $this->passes($attribute, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans("validation.phone");
    }
}
