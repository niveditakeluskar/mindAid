
<?php

/*
   |--------------------------------------------------------------------------
   | Custom Helper Functions
   |--------------------------------------------------------------------------
   |
   | This file contains all of our custom helper functions. These functions
   | can be accessed from anywhere throughout the framework, so be sure
   | to make use of them because they will speed up your workflow.
   |
 */

use Carbon\Carbon;
use Illuminate\Http\Request;
use RCare\Rpm\Models\MailTemplate;
// Blade Directive Utilities ---------------------------------------------------

/**
 * Parse the given expression in a Blade directive. It will return the expression
 * as if each the expression were a list of arguments. (e.g. mydirective("arg1", 17))
 * would give you the array: ["arg1", 17]
 *
 * @param string $expression
 * @return array
 */
function parseParameters(string $expression)
{
    // Yes, eval is very dangerous, but it is completely safe here since
    // the user will not be able to input anything. Go ahead, try to break it.
    return eval("return [$expression];");
}

/**
 * Get a value from the given params array at the given index. If it doesn't exist, return the default vaule
 *
 * @param array  $params
 * @param mixed  $index
 * @param mixed  $defaultValue
 * @return mixed
 */
function defaultParameter(array $params, $index, $defaultValue)
{
    return isset($params[$index]) ? $params[$index] : $defaultValue;
}

// Model Utilities -------------------------------------------------------------

/**
 * Generate authentication credentials array for Laravel's Auth features.
 *
 * @param string $userId    the login user ID
 * @param string $password  the unhashed login password
 * @return array
 */
function credentials($userId, $password)
{
    return [
        "is_active" => true,
        "email"  => $userId,  // "email" is table column
        "password"  => $password // "password" is table column
    ];
}

// Navbar Utilities ------------------------------------------------------------

/**
 * Determine if the current URL is the route or sub-route of the given route name
 * (e.g. /care-plan/summary is a sub-route of /care-plan)
 *
 * @param string    $linkId The unlocalized ID of the link (located in the navbar config)
 * @return boolean
 */
function isNavLinkActive($linkId)
{
    $link = config("navbar")[$linkId];
    if (isset($link["route"])) {
        return starts_with(url()->current(), route($link["route"]));
    } elseif ($link["dropdown"]) {
        foreach ($link["dropdown"] as $key => $options) {
            if (starts_with(url()->current(), route($options["route"]))) {
                return true;
            }
        }
    }
    return false;
}

// String Utilities ------------------------------------------------------------

/**
 * Convert a boolean to a string indicating "Active/Inactive"
 *
 * @param  mixed   $value
 * @return string
 */
function activeInactive($value)
{
    return $value ? "Active" : "Inactive";
}

/**
 * Convert the first character of each word to uppercase
 *
 * @param  mixed   $value
 * @return string
 */
/* function convertFirstCharUpper($value)
{
    return ucwords($value);
} */

/**
 * Convert a phone number from `(999) 999-999` to `9999999999`
 *
 * @param  string $phoneNumber
 * @return string
 */
function flattenPhoneNumber($phoneNumber = null)
{
    if (!$phoneNumber) {
        return null;
    }
    $matches = [];
    preg_match_all("/[0-9]+/", $phoneNumber, $matches);
    if (count($matches) != 1) {
        return null;
    }
    if (count($matches[0]) != 3) {
        return null;
    }
    return implode([$matches[0][0], $matches[0][1], $matches[0][2]]);
}

/**
 * Convert a boolean to a string indicating "Y/N" or "Yes/No"
 *
 * @param  mixed    $value
 * @param  boolean  $short  If true, return Y/N instead of Yes/No
 * @return string
 */
function yesNo($value, $short = true)
{
    if ($short) {
        return $value ? 'Y' : 'N';
    } else {
        return $value ? "Yes" : "No";
    }
}

/**
 * Convert the given element name in snake-case format to a proper ID format
 *
 * @param  string $value
 * @return string
 */
function toId(string $value)
{
    return str_replace('_', '-', $value);
}

/**
 * Generate the validation rules for a request
 *
 * @param string|array<string> $rules
 * @return array<string>
 */
function validationRules($save = False, ...$validation)
{
    $result = [];
    foreach ($validation as $validator) {
        if (is_string($validator)) {
            $rules  = config("validation.$validator");
            if (in_array("save", $rules) && in_array("submit", $rules)) {
                $rules = $rules[$save ? "save" : "submit"];
            }
            if ($fields = config("form.section.$validator")) {
                foreach ($fields as $field) {
                    foreach ($rules as $key => $rule) {
                        $result[sprintf($key, $field)] = $rule;
                    }
                }
            } else {
                $result = array_merge($result, $validator);
            }
        } else {
            $result = array_merge($result, $validator);
        }
    }
    return $result;
}

// Miscellaneous ---------------------------------------------------------------

/**
 * Generate a hash consisting of characters a-zA-z0-9
 *
 * @param integer $length
 * @return string
 */
function createHash(int $length = 10)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $string = "";
    for ($i = 0; $i < $length; $i++) {
        $string .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $string;
}

/**
 * Generate a carbon date from string value in the form of `Y-m-d`.
 * Return Null otherwise
 *
 * @param  mixed              $value
 * @return Carbon\Carbon|Null
 */
function carbonDate($value)
{
    if ($value) {
        $parts = explode('-', $value);
        return Carbon::createFromDate((int)$parts[0], (int)$parts[1], (int)$parts[2]);
    }
    return null;
}

/**
 * Convert a carbon date to the format to use in a `date` field
 *
 * @param Carbon\Carbon|Null $date
 * @return String|Null
 */
function dateValue($date)
{
    if ($date) {
        return $date->format("Y-m-d");
    }
    return null;
}

function yearsSince($date)
{
    if (is_string($date)) {
        $date = carbonDate($date);
    }
    return $date->diffInYears(Carbon::now());
}

function calAge($date)
{
    if (is_string($date)) {
        $date = carbonDate($date);
    }
    return $date->diff(Carbon::now())
                ->format('%y years, %m months and %d days');
}

function monthsSince($date)
{
    if (is_string($date)) {
        $date = carbonDate($date);
    }
    return $date->diffInMonths(Carbon::now());
}

function daysSince($date)
{
    if (is_string($date)) {
        $date = carbonDate($date);
    }
    return $date->diffInDays(Carbon::now());
}

function addDays($date, $days){
	if (is_string($date)) {
        $date = carbonDate($date);
    }
	return $date->addDays($days);
}

/* function validateDateFormat($date)
{
	$startDate = carbonDate('1902-01-01');
	$endDate = Carbon::now();
	if (is_string($date)) {
		$date = carbonDate($date);
	}
	// return ($date < $startDate && $date >= $endDate);
    return ($date < $startDate);
}
 */
 
/**
 * Populate form fields
 *
 * @param  array $static  Static form fields.
 * @param  array $dynamic Dynamic form fields
 * @return array
 */
function populate(array $static, array $dynamic)
{
    return [
        "static"  => $static,
        "dynamic" => $dynamic
    ];
}

/**
 * Get the name of a class without the namespace
 */
function get_class_name($class)
{
    if (!is_string($class)) {
        $class = get_class($class);
    }
    return substr(strrchr($class, '\\'), 1);
}

function checkboxChecked($value)
{
    return $value ? "checked" : "";
}

function radioYes($value)
{
    return checkboxChecked($value);
}

function radioNo($value)
{
    return $value ? "" : "checked";
}

function threeScale($value) // I'm so sorry
{
    switch ($value) {
        case('0'):
            return "Low";
            break;
        case('1'):
            return "Medium";
            break;
        case('2'):
            return "High";
            break;
        default:
            return "N/A";
            break;
    }
}

function militaryValDisplay($value) // I'm so sorry
{
    switch ($value) {
        case('0'):
            return "Yes";
            break;
        case('1'):
            return "No";
            break;
        case('2'):
            return "Unknown";
            break;
        default:
            return "N/A";
            break;
    }
}

function selectYesNoOptionDisplay($value) // I'm so sorry
{
    switch ($value) {
        case('0'):
            return "No";
            break;
        case('1'):
            return "Yes";
            break;
        default:
            return "N/A";
            break;
    }
}

function selectPreferredContact($value) // I'm so sorry
{
    switch ($value) {
        case('0'):
            return "Primary phone number";
            break;
        case('1'):
            return "Secondary phone number";
            break;
        case('2'):
            return "Email";
            break;
        case('3'):
            return "Other contact";
            break;
        default:
            return "N/A";
            break;
    }
}

function returnCarePlanInformation($value)
{
    return $value->template;
}


// Linking System ----------------------------------------------------------------------------------

/**
 * Any of the given IDs can match
 *
 * @param  Array<string> $args
 * @return Array<string>
 */
function either(...$args)
{
    return $args;
}

function either_requirement(...$args)
{
    $result = [];
    foreach ($args as $arg) {
        $result[] = [$arg];
    }
    return $result;
}

function checked($count)
{
    return [true, $count];
}

/**
 * Greater than
 *
 * @param mixed $value
 */
function greater($value)
{
    return [$value, '>'];
}

/**
 * Greater than or equal to
 *
 * @param mixed $value
 */
function greater_or_equal($value)
{
    return [$value, '>='];
}

/**
 * Less than
 *
 * @param mixed $value
 */
function less($value)
{
    return [$value, '<'];
}

/**
 * Less than or equal to
 *
 * @param mixed $value
 */
function less_or_equal($value)
{
    return [$value, '<='];
}

/**
 * Negate a value
 *
 * @param mixed $value
 */
function not($value)
{
    return [$value, '!='];
}

// Healthcare Functions ----------------------------------------------------------------------------

/**
 * Calculate BMI
 *
 * @param  int   $height Height in inches
 * @param  int   $weight Weight in lbs
 * @return float
 */
function bmi($height, $weight)
{
    try {
        return ((int) $weight) / pow(((int) $height), 2) * 703;
    } catch (ErrorException $e) { // Catch division by zero
        return null;
    }
}

function dateValuePrint($date)
{
    if ($date) {
        return $date->format("m-d-Y");
    }
    return null;
}

// function getCallScriptsById(Request $request){
//     $id = $request->id;
//     $scripts = MailTemplate::where('id',$id)->get(); //

//     return $scripts;
// }
