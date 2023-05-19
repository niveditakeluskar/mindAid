<?php

namespace RCare\System\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class WithinYear implements Rule
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
		// return yearsSince($value) < 1; // 1year before from now()
		return monthsSince($value) < 18; // 18 months beforefrom now()//new requirement
    }

    public function validate($attribute, $value, $parameters, $validator)
    {
        return $this->passes($attribute, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans("validation.within_year");
    }
}
