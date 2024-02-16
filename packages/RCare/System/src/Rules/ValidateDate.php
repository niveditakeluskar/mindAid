<?php
// created by Pranali on 25Apr2022
// to check if Date enter in textbox is correct or not

namespace RCare\System\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class ValidateDate implements Rule
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
        if (preg_match("^(?:(1[0-2]|0[1-9])/(3[01]|[12][0-9]|0[1-9])|(3[01]|[12][0-9]|0[1-9])/(1[0-2]|0[1-9]))/[0-9]{4}$",$value)) {
            return true;
        }
        return false;
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
        return trans("validation.check_date_format");
    }
}