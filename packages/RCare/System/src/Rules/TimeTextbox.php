<?php
// created by Pranali on 30oct20
// to check if time enter in textbox is correct or not

namespace RCare\System\Rules;

use Illuminate\Contracts\Validation\Rule;

class TimeTextbox implements Rule
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
        $regex = "/^(?:[01]\d|2[0123]):(?:[012345]\d):(?:[012345]\d)$/";
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
        return trans("validation.time_textbox");
    }
}
