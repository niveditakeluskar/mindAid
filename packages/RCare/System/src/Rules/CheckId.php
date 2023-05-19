<?php

namespace RCare\System\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckId implements Rule
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
			/*
			$table = $parameters[0];
            $insurance_primary_idnum_check = $parameters[1];
            $tenant_id = $parameters[2];
            $excluded_id = $parameters[3];
            
			if($insurance_primary_idnum_check == 0 ) {
				// query the table with all the conditions
				//$result = DB::table( $table )->select( \DB::raw( 1 ) )
				//->where($attribute , $value);
            
				$result = DB::table($table)->where($field, $value)->count() == 0;
				//$result = $result->get();

				// return empty( $result ); // edited here
				//return false;
				
			} else {
				return true;
			}*/
			return false;
	
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
        return trans("validation.check_id");
    }
}
