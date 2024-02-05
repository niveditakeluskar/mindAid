	<?php
    // namespace RCare\System\Http\Helpers;
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

    use RCare\System\Http\Controllers\CommonFunctionController;
    use Carbon\Carbon;
    use Twilio\Rest\Client;
    use Twilio\Jwt\ClientToken;
    use Twilio\Exceptions\TwilioException;
    use Twilio\TwiML\MessagingResponse;
    #use Exception;


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
            // "is_active" => true,
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
        return $date;
        /*if ($date) {
        return $date->format("Y-m-d");
    }
    return null;*/
    }

    /**
     * Convert a carbon date to the format to use To display Date
     *
     * @param Carbon\Carbon|Null $date
     * @return String|Null
     */
    function displayDateValue($date)
    {
        if ($date) {
            return $date->format("m-d-Y");
        }
        return null;
    }

    function monthValue($date)
    {
        // if ($date) {
        //     return $date->format("m/Y");
        // }
        // return null;
        if ($date) {
            $parts = explode('-', $date);
            return $parts[1] . "-" . $parts[0];
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

    function age($date)
    {
        if (is_string($date)) {
            $date = carbonDate($date);
        }
        return $date->diff(Carbon::now())
            ->format('%y years');
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

    function addDays($date, $days)
    {
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

    // function checkboxChecked($value)
    // {
    //     return $value ? "checked" : "";
    // }

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
            case ('0'):
                return "Low";
                break;
            case ('1'):
                return "Medium";
                break;
            case ('2'):
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
            case ('0'):
                return "Yes";
                break;
            case ('1'):
                return "No";
                break;
            case ('2'):
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
            case ('0'):
                return "No";
                break;
            case ('1'):
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
            case ('0'):
                return "Primary phone number";
                break;
            case ('1'):
                return "Secondary phone number";
                break;
            case ('2'):
                return "Email";
                break;
            case ('3'):
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

    /**
     * Equal a value
     *
     * @param mixed $value
     */
    function equal($value)
    {
        return [$value, '=='];
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

    /******
     * 
     * function to convert date formate to insert date
     * 
     * ****/
    function dateInsertFormateChange($date)
    {
        if ($date) {
            if (strpos($date, '-') !== false) {
                $dateMonthArray = explode('-', $date);
                $year = $dateMonthArray[0];
                $month = $dateMonthArray[1];
                $timestamp = $year . '-' . $month . '-01';
                $ndate = date_format(date_create($timestamp), "Y-m-d");
                // $ndate = Carbon::parse($timestamp)->format('Y-m-d');
            } elseif (strpos($date, '/') !== false) {
                $dateMonthArray = explode('/', $date);
                // var_dump($dateMonthArray);
                $year = $dateMonthArray[1];
                $month = $dateMonthArray[0];
                $timestamp = $year . '-' . $month . '-01';
                $ndate = date_format(date_create($timestamp), "Y-m-d");
            }
            return $ndate;
        }
        return null;
    }

    // $included_files = get_included_files();

    // foreach ($included_files as $filename) {
    //     echo "$filename\n";
    // }

    function printHello($nm)
    {
        return "hello " . $nm;
    }

    function idleTimeRedirect()
    {
        //$redirectTime = RCare\Org\OrgPackages\DomainFeatures\src\Models\DomainFeatures::where('status',1)->latest()->first();
        $redirectTime =  DB::select("select idle_time_redirect from ren_core.domain_features dmf where status = 1 order by created_at desc");
        if (isset($redirectTime[0]->idle_time_redirect)) {
            echo  $redirectTime[0]->idle_time_redirect;
        }
        //return $redirectTime;
    }

    function mainMenus()
    {

        $role = Session::get('role');
        if ($role == '') {
            $role = 0;
        }
        $menu = DB::select("select * from rcare_menu_master m where parent ='0' and status ='1' and service_id in (select service_id from rcare_role_services where role_id = '" . $role . "' and lower(crud) like  '%' || m.operation || '%' ) order by sequence");
        $menu_arr = [];
        $i = 0;
        foreach ($menu as $value) {
            $menu_arr[$i]['id']         = $value->id;
            $menu_arr[$i]['menu']       = $value->menu;
            $menu_arr[$i]['menu_url']   = $value->menu_url;
            $menu_arr[$i]['service_id'] = $value->service_id;
            $menu_arr[$i]['icon']       = $value->icon;
            $menu_arr[$i]['parent']     = $value->parent;
            $menu_arr[$i]['sequence']   = $value->sequence;
            // $menu_arr[$i]['parent']     = $value->parent;
            $i++;
        }
        return $menu_arr;
    }

    function subMenus()
    {
        $role = Session::get('role');
        if ($role == '') {
            $role = 0;
        }
        $menu = DB::select("select * from rcare_menu_master m where parent !='0' and status ='1' and service_id in (select service_id from rcare_role_services where role_id = '" . $role . "' and lower(crud) like  '%' || m.operation || '%' order by sequence)");
        $menu_arr = [];
        $i = 0;
        foreach ($menu as $value) {
            $menu_arr[$i]['id']         = $value->id;
            $menu_arr[$i]['menu']       = $value->menu;
            $menu_arr[$i]['menu_url']   = $value->menu_url;
            $menu_arr[$i]['service_id'] = $value->service_id;
            $menu_arr[$i]['icon']       = $value->icon;
            $menu_arr[$i]['parent']     = $value->parent;
            $menu_arr[$i]['sequence']   = $value->sequence;
            $menu_arr[$i]['parent']     = $value->parent;
            $i++;
        }
        return $menu_arr;
    }

    function orgmainMenus()
    {
        $role = Session::get('role');
        if ($role == '') {
            $role = 0;
        }
        $menu = DB::connection('ren_core')->select("select * from menu_master m where parent ='0' and status ='1' and module_id in (select module_id from role_modules where role_id = '" . $role . "' and lower(crud) like  '%' || m.operation || '%' ) and (component_id = 0 or component_id in (select components_id from role_modules where role_id = '" . $role . "' and lower(crud) like  '%' || m.operation || '%' ))  order by sequence");
        $menu_arr = [];
        $i = 0;
        foreach ($menu as $value) {
            $menu_arr[$i]['id']         = $value->id;
            $menu_arr[$i]['menu']       = $value->menu;
            $menu_arr[$i]['menu_url']   = $value->menu_url;
            //$menu_arr[$i]['service_id'] = $value->service_id;
            $menu_arr[$i]['icon']       = $value->icon;
            $menu_arr[$i]['parent']     = $value->parent;
            $menu_arr[$i]['sequence']   = $value->sequence;
            // $menu_arr[$i]['parent']     = $value->parent;
            $i++;
        }
        return $menu_arr;
    }
    function orgsubMenus()
    {
        $role = Session::get('role');
        if ($role == '') {
            $role = 0;
        }
        $menu = DB::connection('ren_core')->select("select * from menu_master m where parent !='0' and status ='1' and module_id in (select module_id from role_modules where role_id = '" . $role . "' and lower(crud) like  '%' || m.operation || '%' ) and component_id in (select components_id from role_modules where role_id = '" . $role . "' and lower(crud) like  '%' || m.operation || '%' )  order by sequence");
        $menu_arr = [];
        $i = 0;
        foreach ($menu as $value) {
            $menu_arr[$i]['id']         = $value->id;
            $menu_arr[$i]['menu']       = $value->menu;
            $menu_arr[$i]['menu_url']   = $value->menu_url;
            // $menu_arr[$i]['service_id'] = $value->service_id;
            $menu_arr[$i]['icon']       = $value->icon;
            $menu_arr[$i]['parent']     = $value->parent;
            $menu_arr[$i]['sequence']   = $value->sequence;
            // $menu_arr[$i]['parent']     = $value->parent;
            $i++;
        }
        return $menu_arr;
    }

    function orgsubsubMenus()
    {
        $role = Session::get('role');
        if ($role == '') {
            $role = 0;
        }
        $menu = DB::connection('ren_core')->select("select * from menu_master m where parent in (select id from menu_master where parent != '0') and status ='1' and module_id in (select module_id from role_modules where role_id = '" . $role . "' and lower(crud) like  '%' || m.operation || '%' ) and component_id in (select components_id from role_modules where role_id = '" . $role . "' and lower(crud) like  '%' || m.operation || '%' )  order by sequence");
        $menu_arr = [];
        $i = 0;
        foreach ($menu as $value) {
            $menu_arr[$i]['id']         = $value->id;
            $menu_arr[$i]['menu']       = $value->menu;
            $menu_arr[$i]['menu_url']   = $value->menu_url;
            // $menu_arr[$i]['service_id'] = $value->service_id;
            $menu_arr[$i]['icon']       = $value->icon;
            $menu_arr[$i]['parent']     = $value->parent;
            $menu_arr[$i]['sequence']   = $value->sequence;
            // $menu_arr[$i]['parent']     = $value->parent;
            $i++;
        }
        return $menu_arr;
    }

    // function anothersubMenus() {
    //     $role =Session::get('role');
    //     if($role==''){
    //         $role=0;
    //     }
    //     $menu = DB::connection('ren_core')->select("select * from menu_master m where parent !='0' and status ='1' and module_id in (select module_id from role_modules where role_id = '".$role."' and lower(crud) like  '%' || m.operation || '%' ) and component_id in (select components_id from role_modules where role_id = '".$role."' and lower(crud) like  '%' || m.operation || '%' )  order by sequence");
    //         $menu_arr =[];
    //         $i = 0;
    //         foreach ($menu as $value) {
    //             $menu_arr[$i]['id']         = $value->id;
    //             $menu_arr[$i]['menu']       = $value->menu;
    //             $menu_arr[$i]['menu_url']   = $value->menu_url;
    //            // $menu_arr[$i]['service_id'] = $value->service_id;
    //             $menu_arr[$i]['icon']       = $value->icon;
    //             $menu_arr[$i]['parent']     = $value->parent;
    //             $menu_arr[$i]['sequence']   = $value->sequence;
    //             // $menu_arr[$i]['parent']     = $value->parent;
    //             $i++;
    //         }
    //     return $menu_arr;
    // }


    function getNetTime($start_time, $end_time, $flag)
    {
        /*$start          = strtotime($start_time); 
    $end            = strtotime($end_time); 
    $totaltime      = ($end - $start)  ; 
    $hours          = intval($totaltime / 3600);   
    $seconds_remain = ($totaltime - ($hours * 3600)); 
    $minutes        = intval($seconds_remain / 60);   
    $seconds        = ($seconds_remain - ($minutes * 60)); 
    $net_time       =  abs($hours) .':'. abs($minutes) .':'. abs($seconds);*/
        if ($flag == 1) {
            $st = explode(" ", $start_time);
            $et = explode(" ", $end_time);
            $startdate = explode('-', $st[0]);
            // dd($startdate);
            $month = $startdate[0];
            $day   = $startdate[1];
            $year  = $startdate[2];
            $sd = $year . '-' . $month . '-' . $day . ' ' . $st[1];

            $enddate = explode('-', $et[0]);
            $month1 = $enddate[0];
            $day1  = $enddate[1];
            $year1  = $enddate[2];
            $ed = $year1 . '-' . $month1 . '-' . $day1 . ' ' . $et[1];
            $net_time = gmdate('H:i:s', Carbon::parse($ed)->diffInSeconds(Carbon::parse($sd)));
            return $net_time;
        } else {
            $net_time = gmdate('H:i:s', Carbon::parse($end_time)->diffInSeconds(Carbon::parse($start_time)));
            return $net_time;
        }
    }


    // function getToDoList() {
    //     $module_id = getPageModuleName();
    //     $submodule_id = getPageSubModuleName();
    //     $patient_id = request()->segment(3);
    //     $login_user =Session::get('userid');
    //     // $to_do_list = RCare\TaskManagement\Models\ToDoList::where('module_id', $module_id)->where('component_id', $submodule_id)->with(['Patient'])->get();
    //     if(isset($patient_id) && $patient_id != ""){
    //         $to_do_list = DB::connection('ren_core')->select("select patient.fname, patient.lname, todo.id, todo.task_date, todo.module_id, todo.component_id, todo.task_notes, todo.patient_id from task_management.to_do_list as todo left join patients.patient as patient on patient.id = todo.patient_id  where todo.status_flag ='0' and todo.assigned_to = '".$login_user."' and todo.patient_id = '".$patient_id."'");
    //         // $to_do_list = DB::connection('ren_core')->select("select patient.fname, patient.lname, todo.id, todo.task_notes, todo.patient_id from task_management.to_do_list as todo left join patients.patient as patient on patient.id = CAST(todo.patient_id AS INTEGER) where todo.module_id = '".$module_id."' and todo.component_id = '".$submodule_id."' and todo.patient_id = '".$patient_id."'");
    //     } else {
    //         // $to_do_list = DB::connection('ren_core')->select("select patient.fname, patient.lname, todo.id, todo.task_notes, todo.patient_id from task_management.to_do_list as todo left join patients.patient as patient on patient.id = CAST(todo.patient_id AS INTEGER) where todo.module_id = '".$module_id."' and todo.component_id = '".$submodule_id."'");
    //         $to_do_list = DB::connection('ren_core')->select("select patient.fname, patient.lname, todo.id, todo.task_date, todo.module_id, todo.component_id, todo.task_notes, todo.patient_id from task_management.to_do_list as todo left join patients.patient as patient on patient.id = todo.patient_id where todo.status_flag ='0' and todo.assigned_to = '".$login_user."'");
    //     }
    //     $to_do_arr =[];
    //     $i = 0;
    //     foreach ($to_do_list as $value) {
    //         $to_do_arr[$i]['fname']            = $value->fname;
    //         $to_do_arr[$i]['lname']            = $value->lname;
    //         $to_do_arr[$i]['id']               = $value->id;
    //         $to_do_arr[$i]['task_date']        = $value->task_date;
    //         $to_do_arr[$i]['task_notes']       = $value->task_notes;
    //         $to_do_arr[$i]['module_id']        = $value->module_id;
    //         $to_do_arr[$i]['component_id']     = $value->component_id;
    //         $to_do_arr[$i]['patient_id']              = $value->patient_id;
    //         $i++;
    //     }
    //     return $to_do_arr;
    // }

    function getPartnerdevice($id)
    {
        $partnerdevice = RCare\Rpm\Models\Partner_Devices::where('id', $id)->with('device')->get();
        return $partnerdevice;
    }

    function ApiECGCredeintials()
    {
        return  $data = RCare\API\Models\PartnerCredentials::where('status', '1')->get();
    }




    function assingSessionUser($patientid)
    {
        $module_name = \Request::segment(1);
        // $check_module_name = DB::connection('ren_core')->select("SELECT id FROM ren_core.modules WHERE LOWER(module) ='".strtolower($module_name)."'");
        //dd($module_name);
        if ($module_name == 'patients') {

            $check_patient = RCare\TaskManagement\Models\UserPatients::where('patient_id', $patientid)->get();
            // dd( count($check_patient));                

            if (count($check_patient) == 0) {
                //dd($check_patient);
                $check_patient_practice = DB::connection('patients')->select("SELECT * FROM patients.patient_providers
                                  WHERE  patient_id = '" . $patientid . "'");


                if (count($check_patient_practice) > 0) {
                    $practiceid = $check_patient_practice[0]->practice_id;
                } else {
                    $practiceid = 0;
                }

                $login_user = Session::get('userid');
                $data = array(

                    'practice_id'  => $practiceid,
                    'user_id'      => $login_user,
                    'patient_id'   => $patientid,
                    'created_by'   => $login_user,
                    'updated_by'   => $login_user,
                    'status'       => 1,
                    'assigned_to'  => $login_user
                );

                // dd( $data);  

                $insert_query  = RCare\TaskManagement\Models\UserPatients::create($data);
            }
        }
    }


    function getPageModuleName()
    {
        $module_name = \Request::segment(1);
        $sub_module_name = \Request::segment(2);
        $patient_id = \Request::segment(3);
        if (($sub_module_name == 'care-plan-development' or $sub_module_name == 'monthly-monitoring') and is_int($patient_id)) {
            $PatientServices = RCare\Patients\Models\PatientServices::select("*")
                ->with('module:id,module')
                ->whereHas('module', function ($query) {
                    $query->where('module', '=', 'RPM'); // '=' is optional
                })
                ->select('module_id')
                ->where('patient_id', $patient_id)
                ->where('status', 1)
                ->get();
            // dd($PatientServices);
            if (is_null($PatientServices) == false) {
                return $PatientServices[0]->module_id;
            } else {
                $check_module_name = DB::connection('ren_core')->select("SELECT * FROM ren_core.modules WHERE LOWER(module) ='" . strtolower($module_name) . "'");
                return (count($check_module_name) > 0 ? $check_module_name[0]->id : 0);
            }
        } else {
            $check_module_name = DB::connection('ren_core')->select("SELECT * FROM ren_core.modules WHERE LOWER(module) ='" . strtolower($module_name) . "'");
            return (count($check_module_name) > 0 ? $check_module_name[0]->id : 0);
        }
    }

    function getPageModuleNameWithUrl($url)
    {
        // dd($url);
        $path = explode('/', $url);
        // dd($path);
        // dd(count($path));
        $module_name = $path[3]; //\Request::segment(1);
        $sub_module_name = $path[4]; //\Request::segment(2);
        if (count($path) == 6) {
            $patient_id = $path[5]; //\Request::segment(3);
        }
        if (($sub_module_name == 'care-plan-development' or $sub_module_name == 'monthly-monitoring') and is_int($patient_id)) {
            $PatientServices = RCare\Patients\Models\PatientServices::select("*")
                ->with('module:id,module')
                ->whereHas('module', function ($query) {
                    $query->where('module', '=', 'RPM'); // '=' is optional
                })
                ->select('module_id')
                ->where('patient_id', $patient_id)
                ->where('status', 1)
                ->get();
            // dd($PatientServices);
            if (is_null($PatientServices) == false) {
                return $PatientServices[0]->module_id;
            } else {
                $check_module_name = DB::connection('ren_core')->select("SELECT * FROM ren_core.modules WHERE LOWER(module) ='" . strtolower($module_name) . "'");
                return (count($check_module_name) > 0 ? $check_module_name[0]->id : 0);
            }
        } else {
            $check_module_name = DB::connection('ren_core')->select("SELECT * FROM ren_core.modules WHERE LOWER(module) ='" . strtolower($module_name) . "'");
            return (count($check_module_name) > 0 ? $check_module_name[0]->id : 0);
        }
    }

    // function getPageModuleName() {
    //     $module_name = \Request::segment(1);
    //     $check_module_name = DB::connection('ren_core')->select("SELECT * FROM ren_core.modules WHERE LOWER(module) ='".strtolower($module_name)."'");
    //             return (count($check_module_name) > 0 ? $check_module_name[0]->id : 0 );
    // }

    //created by pranali on 10Nov2020
    //function to get module_id, sub_module_id(component_id) from menu master table
    function getPageModuleIdSubModuleIdFromMenuMasterTable()
    {
        $url = \Request::path();
        $check_url = DB::connection('ren_core')->select("SELECT * FROM ren_core.menu_master WHERE LOWER(menu_url) ='" . strtolower($url) . "'");
        if (empty($check_url)) return false;
        $result['module_id'] = $check_url[0]->module_id;
        $result['sub_module_id'] = $check_url[0]->component_id;
        return $result;
    }

    function getPageSubModuleName()
    {
        $module_id = getPageModuleName();
        // dd($module_id);
        $component_name = \Request::segment(2);
        $comp_name = str_replace('-', ' ', $component_name);
        $check_component_name = DB::connection('ren_core')->select("SELECT * FROM ren_core.module_components WHERE module_id = '" . $module_id . "' AND LOWER(components) ='" . strtolower($comp_name) . "'");
        // echo "SELECT * FROM ren_core.module_components WHERE module_id = '".$module_id."' AND LOWER(components) ='".strtolower($comp_name)."'"; die;
        // dd($check_component_name[0]->id);
        return (count($check_component_name) > 0 ? $check_component_name[0]->id : 0);
    }

    function getPageDeviceid()
    {

        $component_name = \Request::segment(4);
        // dd($component_name); 
        // $comp_name = str_replace('-', ' ', $component_name);
        // $check_component_name = DB::connection('ren_core')->select("SELECT * FROM ren_core.module_components WHERE module_id = '".$module_id."' AND LOWER(components) ='".strtolower($comp_name)."'");
        // // echo "SELECT * FROM ren_core.module_components WHERE module_id = '".$module_id."' AND LOWER(components) ='".strtolower($comp_name)."'"; die;
        return ($component_name > 0 ? $component_name : 0);
    }

    function getCpdPageSubModuleName($component_name)
    {
        $module_id = getPageModuleName();
        // $component_name = \Request::segment(2);
        $comp_name = str_replace('-', ' ', $component_name);
        $check_component_name = DB::connection('ren_core')->select("SELECT * FROM ren_core.module_components WHERE module_id = '" . $module_id . "' AND LOWER(components) ='" . strtolower($comp_name) . "'");
        // echo "SELECT * FROM ren_core.module_components WHERE module_id = '".$module_id."' AND LOWER(components) ='".strtolower($comp_name)."'"; die;
        return (count($check_component_name) > 0 ? $check_component_name[0]->id : 0);
    }

    function getFormStageName()
    {
        // function getFormStageName($module_id, $component_id, $stage_name = 'Call') {
        $module_id = getPageModuleName();
        $component_id = getPageSubModuleName();
        $stage_name = 'Call';
        $check_stage_name = DB::connection('ren_core')->select("SELECT * FROM ren_core.stage WHERE module_id = '" . $module_id . "' AND submodule_id = '" . $component_id . "' AND LOWER(TRIM(description)) ='" . strtolower($stage_name) . "'");
        return (count($check_stage_name) > 0 ? $check_stage_name[0]->id : 0);
    }

    function getFormStageId($module_id, $component_id, $stage_name = null)
    {
        $check_stage_name = DB::connection('ren_core')->select("SELECT * FROM ren_core.stage WHERE module_id = '" . $module_id . "' AND submodule_id = '" . $component_id . "' AND LOWER(TRIM(description))='" . strtolower($stage_name) . "'");
        // echo "SELECT * FROM ren_core.stage WHERE module_id = '".$module_id."' AND submodule_id = '".$component_id."' AND LOWER(TRIM(description)) ='".strtolower($stage_name)."'";
        //    dd( $check_stage_name[0]->id );
        return (count($check_stage_name) > 0 ? $check_stage_name[0]->id : 0);
    }

    function getFormStagesId($module_id, $stage_name = null)
    {
        $check_component_name = DB::connection('ren_core')->select("SELECT * FROM ren_core.module_components WHERE module_id = '" . $module_id . "' AND LOWER(components) ='monthly monitoring'");
        $check_stage_name = DB::connection('ren_core')->select("SELECT * FROM ren_core.stage WHERE module_id = '" . $module_id . "' AND submodule_id = '" . $check_component_name[0]->id . "' AND LOWER(TRIM(description))='" . strtolower($stage_name) . "'");
        // echo "SELECT * FROM ren_core.stage WHERE module_id = '".$module_id."' AND submodule_id = '".$component_id."' AND LOWER(TRIM(description)) ='".strtolower($stage_name)."'";
        //    dd( $check_stage_name[0]->id );
        return (count($check_stage_name) > 0 ? $check_stage_name[0]->id : 0);
    }


    function getFormStepId($module_id, $submodule_id, $stage_id, $step_name = null)
    {
        $check_step_name = DB::connection('ren_core')->select("SELECT * FROM ren_core.stage_codes WHERE module_id = '" . $module_id . "' AND submodule_id = '" . $submodule_id . "' AND stage_id = '" . $stage_id . "' AND LOWER(description) ='" . strtolower($step_name) . "'");
        // $check_step_name = RCare\Org\OrgPackages\StageCodes\src\Models\StageCode::where("module_id", $module_id)->where("submodule_id", $submodule_id)->where("stage_id", $stage_id)->where("description", strtolower($step_name))->orderBy('id','DESC')->get();
        return (count($check_step_name) > 0 ? $check_step_name[0]->id : 0);
    }

    function getHippaScriptContent($module_id, $submodule_id, $stage_id)
    {
        // echo 'here';
        $hippscript = RCare\Org\OrgPackages\QCTemplates\src\Models\ContentTemplate::where('module_id', $module_id)->where('component_id', $submodule_id)->where('stage_id', $stage_id)->where('status', 1)->latest()->first();


        if (isset($hippscript->content)) {
            $content = json_decode($hippscript->content);
            echo  $content->message;
        }
    }

    function assingCareManager($id)
    {
        $ap = RCare\TaskManagement\Models\UserPatients::where('patient_id', $id)->where('status', 1)->get();
        if (isset($ap[0]->user_id)) {
            //return $ap[0]->user_id;
            $cm = RCare\Org\OrgPackages\Users\src\Models\Users::where('id', $ap[0]->user_id)->get();
            return $cm[0]->f_name . ' ' . $cm[0]->l_name;
        } else {
            return 'CM Not Assigned';
        }
    }

    function activeThemeMode($id)
    {
        if (isset($id)) {
            $am = RCare\Org\OrgPackages\Users\src\Models\Users::where('id', $id)->get();
            return $am[0]->theme;
        } else {
            return '0';
        }
    }

    function getVTreeData($id)
    {
        $module_id    = getPageModuleName();
        $SID = getFormStageId(8, 9, 'Veteran');
        $patient_demographics = RCare\Patients\Models\PatientDemographics::where('patient_id', $id)->get();
        // dd($patient_demographics);
        if (isset($patient_demographics[0]->template)) {
            $patient_questionnaire1 = json_decode($patient_demographics[0]->template, true);
            if (isset($patient_questionnaire1["template_id"])) {
                $veteranQuestion = RCare\Org\OrgPackages\QCTemplates\src\Models\QuestionnaireTemplate::where('id', $patient_questionnaire1["template_id"])->where('stage_id', $SID)->where('template_type_id', 5)->latest()->first();
            } else {
                $veteranQuestion = RCare\Org\OrgPackages\QCTemplates\src\Models\QuestionnaireTemplate::where('status', 1)->where('stage_id', $SID)->where('template_type_id', 5)->latest()->first();
            }
        } else {
            $veteranQuestion = RCare\Org\OrgPackages\QCTemplates\src\Models\QuestionnaireTemplate::where('status', 1)->where('stage_id', $SID)->where('template_type_id', 5)->latest()->first();
        }
        // dd($veteranQuestion);

        if ($veteranQuestion != null) {
            $queData = json_decode($veteranQuestion['question']);
            if (isset($queData->question->q)) {
                $questionnaire = $queData->question->q;

                if (isset($patient_demographics[0]->template)) {
                    $patient_questionnaire = json_decode($patient_demographics[0]->template, true);
                    foreach ($questionnaire as $value) {
                        $questionTitle = trim($value->questionTitle);
                        $questionExist = 0;
                        if ($patient_questionnaire != '' || $patient_questionnaire != null) {
                            if (array_key_exists($questionTitle, $patient_questionnaire)) {
                                $questionExist = 1;
                            }
                        }
                        $que_val = trim(preg_replace('/\s+/', ' ', $value->questionTitle));
                        echo '<label>' . $value->questionTitle . '</label> : ';
                        echo '<b>';
                        if ($questionExist == 1) {
                            if (property_exists($value, 'label') && $value->answerFormat == '4') {
                                foreach ($value->label as $labels) {
                                    if (isset($patient_questionnaire[$questionTitle][str_replace(' ', '_', $labels)])) {
                                        if ($patient_questionnaire[$questionTitle][str_replace(' ', '_', $labels)] == 1) {
                                            echo $labels;
                                        }
                                    }
                                }
                            } else {
                                echo $patient_questionnaire[$questionTitle];
                            }
                        }

                        echo '</b>';
                        echo '<br>';
                    }
                } else {
                    return '';
                }
            }
        }
    }

    function getOrganization($id)
    {
        $org =  RCare\Org\OrgPackages\Practices\src\Models\PracticesGroup::where('id', $id)->get();
        return $org;
    }

    function getSMSConfigue()
    {
        $sms = RCare\RCareAdmin\AdminPackages\Configuration\src\Models\Configurations::where('config_type', 'sms')->where('status', '1')->orderBy('created_at', 'desc')->first();
        return $sms;
    }

    function getMessage($id)
    {
        $sms = RCare\RCareAdmin\AdminPackages\Configuration\src\Models\Configurations::where('config_type', 'sms')->where('status', '1')->orderBy('created_at', 'desc')->first();
        if (isset($sms->configurations)) {
            $sms_detail = json_decode($sms->configurations);

            $sid = $sms_detail->username;
            $token = $sms_detail->password;
            $client = new Client($sid, $token);
            $message = $client->messages($id)->fetch();
            return $message->body;
        }
    }

    function reSendMessage($id)
    {
        $sms = RCare\RCareAdmin\AdminPackages\Configuration\src\Models\Configurations::where('config_type', 'sms')->orderBy('created_at', 'desc')->first();
        if (isset($sms->configurations)) {
            $sms_detail = json_decode($sms->configurations);

            $sid = $sms_detail->username;
            $token = $sms_detail->password;
            $client = new Client($sid, $token);
            $message_re = $client->messages($id)->fetch();
            //return $message->body;
            try {
                $message = $client->messages->create(
                    // the number you'd like to send the message to
                    $message_re->to,
                    [
                        // A Twilio phone number you purchased at twilio.com/console
                        'from' => $sms_detail->phone,
                        // the body of the text message you'd like to send
                        'body' => $message_re->body
                        // "statusCallback" => "https://demo.twilio.com/welcome/sms/reply"
                    ]
                );
                $sid = $message->sid;
                $message1 = $client->messages($sid)->fetch();
                $resend = CommonFunctionController::resendMessages($id, $sid, $message1->status);
                $messagestatus['sending'] = 'Message status is ';
                $messagestatus['queued'] = 'Message has been ';
                $messagestatus['sent'] = 'Message has been ';
                $messagestatus['accepted'] = 'Message has been ';
                $messagestatus['failed'] = 'Message has been ';
                $messagestatus['delivered'] = 'Message has been ';
                $messagestatus['undelivered'] = 'Message is ';
                $messagestatus['receiving'] = 'Message status is ';
                $messagestatus['received'] = 'Message has been ';
                print_r($messagestatus[strtolower($message1->status)] . "" . $message1->status);
            } catch (TwilioException $e) {
                return ($e->getCode() . ' : ' . $e->getMessage());
            }
        }
    }

    function MFAmessageResponse()
    {
        $sms = RCare\RCareAdmin\AdminPackages\Configuration\src\Models\Configurations::where('config_type', 'sms')
            ->orderBy('created_at', 'desc')->first();
        if (isset($sms->configurations)) {
            $sms_detail = json_decode($sms->configurations);
            $sid = $sms_detail->username;
            $token = $sms_detail->password;
            $client = new Client($sid, $token);
            $record = RCare\System\Models\MfaTextingLog::Where('status', 'sent')->get();
            foreach ($record as $msaagelog) {
                //print_r($msaagelog['message_id']);
                $message = $client->messages($msaagelog['message_id'])->fetch();
                CommonFunctionController::MFAupdateStatus($msaagelog['message_id'], $message->status);
            }
        }
    }


    function messageResponse()
    {
        $sms = RCare\RCareAdmin\AdminPackages\Configuration\src\Models\Configurations::where('config_type', 'sms')->orderBy('created_at', 'desc')->first();
        if (isset($sms->configurations)) {
            $sms_detail = json_decode($sms->configurations);

            $sid = $sms_detail->username;
            $token = $sms_detail->password;
            $client = new Client($sid, $token);
            $record =  RCare\Messaging\Models\MessageLog::where('status_update', 0)->orWhere('status', 'sent')->get();
            foreach ($record as $msaagelog) {
                //print_r($msaagelog['message_id']);
                $message = $client->messages($msaagelog['message_id'])->fetch();
                CommonFunctionController::updateStatus($msaagelog['message_id'], $message->status);
            }
            /*$messages = $client->messages->read([]);,
    foreach ($messages as $record) {
    print($record->body);
    echo " ";
    //print($record->status);
    echo "<br>";*/

            // }

        }
    }

    function sendTextMessage($phoneNumber, $text, $patient_id, $module_id, $stage_id)
    {
        $sms = RCare\RCareAdmin\AdminPackages\Configuration\src\Models\Configurations::where('config_type', 'sms')->orderBy('created_at', 'desc')->first();
        if (isset($sms->configurations)) {
            $sms_detail = json_decode($sms->configurations);

            $sid = $sms_detail->username;
            $token = $sms_detail->password;
            $client = new Client($sid, $token);

            // Use the client to do fun stuff like send text messages!
            try {
                $message = $client->messages->create(
                    // the number you'd like to send the message to
                    $phoneNumber,
                    [
                        // A Twilio phone number you purchased at twilio.com/console
                        'from' => $sms_detail->phone,
                        // the body of the text message you'd like to send
                        'body' => $text
                        //"statusCallback" => "https://demo.twilio.com/welcome/sms/reply"
                    ]
                );
                $sid = $message->sid;

                $message1 = $client->messages($sid)->fetch();
                $record_messages  = CommonFunctionController::recordMessages($patient_id, $module_id, $stage_id, $sid, $sms_detail->phone, $phoneNumber, $message1->status, $text);
                $messagestatus['sending'] = 'Message status is ';
                $messagestatus['queued'] = 'Message has been ';
                $messagestatus['sent'] = 'Message has been ';
                $messagestatus['accepted'] = 'Message has been ';
                $messagestatus['failed'] = 'Message has been ';
                $messagestatus['delivered'] = 'Message has been ';
                $messagestatus['undelivered'] = 'Message is ';
                $messagestatus['receiving'] = 'Message status is ';
                $messagestatus['received'] = 'Message has been ';
                //print_r($messagestatus[strtolower($message1->status)]."".$message1->status);
                return $messagestatus[strtolower($message1->status)] . "" . $message1->status;
            } catch (TwilioException $e) {
                return ($e->getCode() . ' : ' . $e->getMessage());
            }
        } else {
        }
    }

    /*function getMessageHistory($patient_id){
    $call_history =RCare\Messaging\Models\MessageLog::select('patient_id','status','created_at','module_id','message_date','status','message_date','id','message')
    ->where('patient_id', $patient_id)
    ->whereMonth('created_at','>=', date('m')-1)->whereYear('created_at', date('Y'))->orderBy('created_at', 'desc')->get();
    return $call_history;
}*/

    function getCallHistory($patient_id)
    {
        // $module_id = getPageModuleName();
        // $submodule_id = getPageSubModuleName();
        $dateS = Carbon::now()->startOfMonth()->subMonth(1);
        $dateE = Carbon::now();
        $a = RCare\Ccm\Models\CallStatus::select('call_status', 'phone_no', 'created_at', 'call_continue_status', 'ccm_answer_followup_date', 'ccm_answer_followup_time', 'ccm_call_followup_date', 'voice_mail', 'text_msg')->where('patient_id', $patient_id)
            //->where('module_id',$module_id)->where('component_id',$submodule_id)
            ->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
        $b = RCare\Messaging\Models\MessageLog::select('patient_id', 'status', 'created_at', 'module_id', 'message_date', 'status', 'message_date', 'id', 'message')->where('patient_id', $patient_id)->where('status', 'received')
            ->whereBetween('created_at', [$dateS, $dateE]);
        $c = RCare\Ccm\Models\CallStatus::select('call_status', 'phone_no', 'created_at', 'call_continue_status', 'ccm_answer_followup_date', 'ccm_answer_followup_time', 'ccm_call_followup_date', 'voice_mail', 'text_msg')->where('patient_id', $patient_id)
            ->where('voice_mail', 3)
            ->whereBetween('created_at', [$dateS, $dateE]);
        $call_history = $a->union($c)->union($b)->orderBy('created_at', 'desc')->get();
        return $call_history;
    }

    function getSendTextMessage($module_id, $patient_id, $submodule_id)
    {
        $conf = getSMSConfigue();
        $stage_id = getFormStageId($module_id, $submodule_id, 'Call');
        $call_not_answered_step_id = getFormStepId($module_id, $submodule_id, $stage_id, 'Call Not Answered');

        $patient = RCare\Patients\Models\Patients::where('id', $patient_id)->get();
        $patient_providers = RCare\Patients\Models\PatientProvider::where('patient_id', $patient_id)->with('practice')->with('provider')->with('users')->where('provider_type_id', 1)
            ->where('is_active', 1)->orderby('id', 'desc')->first();
        if (isset($patient_providers->practice['practice_group'])) {
            $org = getOrganization($patient_providers->practice['practice_group']);
        }
        $assign_message = isset($org[0]->assign_message) ? $org[0]->assign_message : '';
        $consent_to_text = isset($patient[0]->consent_to_text) ? $patient[0]->consent_to_text : '';
        $valid = 0;
        if ($consent_to_text == '1' && isset($conf->configurations) && $assign_message == '1') {
            $valid = 1;
        } else if (!isset($conf->configurations)) {
            $valid = 2;
        } else if ($assign_message != '1') {
            $valid = 3;
        }
        $mob_number = '';
        $mobval = '';
        $home_number = '';
        $home_number_value =  '';
        $mob = 0;
        $home = 0;
        if (isset($patient[0]->mob) && ($patient[0]->mob != "") && ($patient[0]->mob != null) && ($patient[0]->primary_cell_phone == "1")) {
            $mob_number = $patient[0]->mob;
            $mobval = $patient[0]->country_code . '' . $patient[0]->mob;
            $mob = 1;
        }
        if (isset($patient[0]->home_number) && ($patient[0]->home_number != "") && ($patient[0]->home_number != null) && ($patient[0]->secondary_cell_phone == "1")) {
            $home_number = $patient[0]->home_number;
            $home_number_value =  $patient[0]->secondary_country_code . '' . $patient[0]->home_number;
            $home = 1;
        }
        $data = array(
            "valid" => $valid,
            "mob_number" => $mob_number,
            "mobval" => $mobval,
            "home_number" => $home_number,
            "home_number_value" => $home_number_value,
            "mob" => $mob,
            "home" => $home,
        );
        return $data;
    }

    function renderTree($treeObj, $lab, $val, $tree_key, $answarFormet, $seq, $tempid)
    {
        $optCount = count((array) $treeObj);
        $javaObj = json_encode($treeObj);
        $i = 1;
        $content = "";
        for ($i = 1; $i <= 25; $i++) {
            if (property_exists($treeObj, $i)) {
                $id = $val . '' . $i;
                $label_str = str_replace("[q]", "", $lab);
                $label = $label_str . "[opt][" . $i . "][val]";
                $jobj =  str_replace("''", "&apos;", $javaObj);
                $jobj =  str_replace("'", "&apos;", $jobj);
                $treeobjval =  str_replace("'", "&#39;", $treeObj->$i->val);
                if ($answarFormet == '1') {
                    $content = $content . '<label class="checkbox  checkbox-primary mr-3">';
                    $onchage = "onchange=ajaxRenderTree($jobj,'$label','$id','$i','$tree_key',$seq,$tempid)";
                    $content = $content . "<input type='checkbox' name='" . $label . "' id='" . $id . "_" . $tree_key . "_" . $i . "' value='" . ($treeobjval ? $treeobjval : "") . "' onchange='ajaxRenderTree($jobj,this,$id,$i,$tree_key,$seq,$tempid)'>";
                    //$content = $content . '<input type="checkbox" name="'.$label.'" id="'.$id . '_' . $tree_key.'_'.$i.'" value="'.($treeobjval ? $treeobjval : '' ).'"  onchange=ajaxRenderTree('.$jobj.','.$label.','.$id.','.$i.','.$tree_key.',$seq,$tempid)>';
                    $content = $content . '<span>' . ($treeObj->$i->val ? $treeObj->$i->val : '') . '</span>';
                    $content = $content . '<span class="checkmark"></span>';
                    $content = $content . '</label>';
                } else if ($answarFormet == '2' || $answarFormet == '5') {
                    $content = $content . "<input type='text' class='form-control col-md-5' name='" . $label . "' id='" . $id . "_" . $tree_key . "_" . $i . "' value='' onkeyup='ajaxRenderTree($jobj,this,$id,$i,$tree_key,$seq,$tempid)'>";
                    $content = $content . "<input type='hidden' class='form-control name='" . $label . "' col-md-5 firsttbox'  style='display:none' onclick='ajaxRenderTree($jobj,this,$id,$i,$tree_key,$seq,$tempid)'>";
                } else {
                    $content = $content . '<label class="radio radio-primary mr-3">';
                    $content = $content . "<input type='radio' name='" . $label . "' id='" . $id . "_" . $tree_key . "_" . $i . "' value='" . ($treeobjval ? $treeobjval : "") . "' onchange='ajaxRenderTree($jobj,this,$id,$i,$tree_key,$seq,$tempid)'>";
                    $content = $content . '<span>' . ($treeObj->$i->val ? $treeObj->$i->val : '') . '</span>';
                    $content = $content . '<span class="checkmark"></span>';

                    $content = $content . '</label>';
                }
            }
        }
        return $content;
    }

    function getDecisionTree($module_id, $patientId, $step_id, $componentId)
    {
        $enrollinRPM = 1;
        if (RCare\Patients\Models\PatientServices::where('patient_id', $patientId)->where('module_id', 3)->where('status', 1)->exists() && RCare\Patients\Models\PatientServices::where('patient_id', $patientId)->where('module_id', 2)->where('status', 1)->exists()) {
            $enrollinRPM = 2;
        }
        $module_id = getPageModuleName();
        $submodule_id = $componentId;
        $stage_id = getFormStageId($module_id, $submodule_id, "General Question");
        $content = "";
        $last_key = 0;
        $skey = $step_id;
        $ccmModule = RCare\Org\OrgPackages\Modules\src\Models\Module::where('module', 'CCM')->where('status', 1)->get('id');
        $ccmModule = (isset($ccmModule) && ($ccmModule->isNotEmpty())) ? $ccmModule[0]->id : 0;
        $ccmSubModule = RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents::where('components', "Monthly Monitoring")->where('module_id', $ccmModule)->where('status', 1)->get('id');
        $ccmSubModule = (isset($ccmSubModule) && ($ccmSubModule->isNotEmpty())) ? $ccmSubModule[0]->id : 0;
        $ccmSID = getFormStageId($ccmModule, $ccmSubModule, 'General Question');
        if ($enrollinRPM > 1) {
            $stage_id = $ccmSID;
            $dmodule_id = $ccmModule;
            $dsubmodule_id = $ccmSubModule;
            $decisionTree = RCare\Org\OrgPackages\QCTemplates\src\Models\QuestionnaireTemplate::where('module_id', $ccmModule)->where('status', 1)->where('stage_id', $ccmSID)->where('stage_code', $step_id)->orderBy('sequence', 'ASC')->get();
            $genQuestion = RCare\Ccm\Models\QuestionnaireTemplatesUsageHistory::where('patient_id', $patientId)->where('contact_via', 'decisiontree')->where('step_id', 0)->where('stage_code', $step_id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->get();
        } else {
            $stage_id = $stage_id;
            $dmodule_id = $module_id;
            $dsubmodule_id = $submodule_id;
            $decisionTree = RCare\Org\OrgPackages\QCTemplates\src\Models\QuestionnaireTemplate::where('module_id', $module_id)->where('status', 1)->where('stage_id', $stage_id)->where('stage_code', $step_id)->orderBy('sequence', 'ASC')->get();
            $genQuestion = RCare\Ccm\Models\QuestionnaireTemplatesUsageHistory::where('patient_id', $patientId)->where('module_id', $module_id)->where('contact_via', 'decisiontree')->where('step_id', 0)->where('stage_code', $step_id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->get();
        }

        $off = 1;
        foreach ($genQuestion as $key => $value) {
            $editGq = json_decode($value['template']);
            $javaObj = str_replace("'", "&#39;", json_encode($editGq));
            $sc = $value["stage_code"];
            // $content = $content . "<button type='button' id='RenderGeneralQuestion".$key."' onclick='checkQuestion($javaObj,$off,$sc)' style='display: none'></button>";
            $off++;
        }
        //dd($decisionTree);
        $content = $content . '<form name="general_question_form_' . $step_id . '" id="general_question_form_' . $step_id . '" >';
        $content = $content . '<input type="hidden" name="uid" value="' . $patientId . '">';
        $content = $content . '<input type="hidden" name="start_time" value="00:00:00">';
        $content = $content . '<input type="hidden" name="end_time" value="00:00:00">';
        $content = $content . '<div class="row">';
        $content = $content . '<div class="col-lg-12 mb-3">';
        $content = $content . '<div class="card">';
        $content = $content . '<div class="card-body">';
        $content = $content . '<input type="hidden" name="patient_id" value="' . $patientId . '">';
        $content = $content . '<input type="hidden" name="m_id" value="' . $dmodule_id . '">';
        $content = $content . '<input type="hidden" name="c_id" value="' . $dsubmodule_id . '">';
        foreach ($decisionTree as $value) {
            $months = json_decode($value['display_months']);
            $queData = json_decode($value['question']);
            if (empty($months)) {
                $months = array("All");
            }
            if (in_array(date('F'), $months) || in_array("All", $months)) {
                $content = $content . '<input type="hidden" name="module_id[' . $last_key . ']" value="' . $module_id . '">';
                $content = $content . '<input type="hidden" name="component_id[' . $last_key . ']" value="' . $submodule_id . '">';

                $content = $content . '<input type="hidden"  id ="stage_id" name="stage_id[' . $last_key . ']" value="' . $value['stage_id'] . '">';
                $content = $content . '<input type="hidden" name="stage_code[' . $last_key . ']" value="' . $value['stage_code'] . '">';
                $content = $content . '<input type="hidden" name="step_id" value="' . $value['stage_code'] . '">';
                $content = $content . '<input type="hidden" name="form_name" value="general_question_form">';
                if ($value['template_type_id'] == 6) {
                    $content = $content . '<input type="hidden" name="template_id[' . $last_key . ']" value="' . $value['id'] . '">';
                    $content = $content . '<div class="mb-4 radioVal" id="' . $last_key . 'general_question11">';
                    $que_val = trim(preg_replace('/\s+/', ' ', $queData->question->qs->q));
                    $content = $content . '<label for="are-you-in-pain" class="col-md-12"><input type="hidden" name="DT' . $last_key . '[qs][q]" value="' . $que_val . '">' . $queData->question->qs->q . '</label>';
                    $content = $content . '<input type="hidden" name="sq[' . $value['id'] . '][0]" value="0">';
                    $content = $content . '<div class="d-inline-flex mb-2 col-md-12">';
                    if (property_exists($queData->question->qs, 'opt')) {
                        $rendOption = renderTree($queData->question->qs->opt, 'DT' . $last_key . '[qs][q]', '1', $last_key, $queData->question->qs->AF, 0, $value['id']);
                        $content = $content . $rendOption;
                    }
                    $content = $content . '</div>';
                    $content = $content . '<p class="message" style="color:red"></p>';
                    $content = $content . '</div>';
                    $content = $content . '<div id="question' . $last_key . '"></div>';
                    $content = $content . '<div id="in-pain">';
                    $content = $content . '<label for="" class="mr-3">Current Monthly Notes:</label>';
                    $content = $content . '<input type="hidden" name="monthly_topic[' . $last_key . ']" value="' . $value['content_title'] . ' Related Monthly Notes">';
                    $content = $content . '<textarea class="form-control" placeholder="Monthly Notes" name="monthly_notes[' . $last_key . ']">';
                    foreach ($genQuestion as $key => $val) {
                        if ($val->template_id == $value['id']) {
                            $content = $content . $val->monthly_notes;
                        }
                    }
                    $content = $content . '</textarea>';
                    $content = $content . '<p class="txtmsg" style="color:red"></p>';
                    $content = $content . '</div>';
                    $content = $content . '<hr>';
                    $last_key++;
                }
            }
        }
        $last_key = $last_key;
        $rendQ = getRelationshipQ($patientId, $skey, $dmodule_id, $dsubmodule_id, $stage_id);
        $content = $content . $rendQ;
        $content = $content . '</div>';
        $content = $content . '<div class="card-footer">';
        $content = $content . '<div class="mc-footer"> ';
        $content = $content . '<div class="row">';
        $content = $content . '<div class="col-lg-12 text-right">';
        $content = $content . '<input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time"  :value="start_time">';
        $content = $content . '<button type="button" class="btn  btn-primary m-1 office-visit-save" id="generalQue' . $skey . '" onclick="saveGeneralQuestions(' . $skey . ')">Save</button>';
        $content = $content . '</div>';
        $content = $content . '</div>';
        $content = $content . '</div>';
        $content = $content . '</div>';
        $content = $content . '</div>';
        $content = $content . '</div>';
        $content = $content . '</div>';
        $content = $content . '</form>';
        return $content;
    }


    function getRelationshipQ($patient_id, $stepid, $m, $sm, $s)
    {
        $module_id = getPageModuleName();
        $submodule_id = getPageSubModuleName();
        $stage_id = getFormStageId($module_id, $submodule_id, "General Question");
        $i = 0;
        $content = "";
        //$patientqut =  RCare\Patients\Models\PatientQuestionnaire::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->orderBy('created_at', 'desc')->first();
        $content = $content . '<input type="hidden" name="mid" value="' . $module_id . '">';
        $content = $content . '<input type="hidden" name="cid" value="' . $submodule_id . '">';
        // return " module_id==" . $module_id . "  submodule_id=="  . $submodule_id . "  stage_id==" .$stage_id;
        // $steps = DB::connection('ren_core')->select("SELECT * FROM ren_core.stage_codes WHERE module_id = '".$module_id."' AND submodule_id = '".$submodule_id."' AND stage_id ='".$stage_id."'");
        $module_id = $m;
        $submodule_id = $sm;
        $stage_id = $s;
        $steps = RCare\Org\OrgPackages\StageCodes\src\Models\StageCode::where('module_id', $module_id)->where('submodule_id', $submodule_id)->where('stage_id', $stage_id)->orderBy('sequence', 'ASC')->get();

        $callp = RCare\Ccm\Models\CallPreparation::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->orderBy('created_at', 'desc')->first();
        foreach ($steps as $step) {
            $new_stage_id = $step->stage_id;
            $step_id   = $step->id;
            if ($stepid == $step_id) {
                $step_name = $step->description;
                //$step_name_trimmed = str_replace(' ','_', trim($step_name));

                $one_time = RCare\Org\OrgPackages\QCTemplates\src\Models\QuestionnaireTemplate::where('one_time_entry', 1)->where('stage_code', $step_id)->get();
                //print_r($one_time[0]->one_time_entry);
                if (isset($one_time[0]->one_time_entry)) {
                    $patientsRelationQuestionnaire_arr = RCare\Ccm\Models\QuestionnaireTemplatesUsageHistory::where('patient_id', $patient_id)->where('stage_id', $stage_id)->where('stage_code', $step_id)->orderBy('created_at', 'desc')->get();
                } else {
                    $patientsRelationQuestionnaire_arr = RCare\Ccm\Models\QuestionnaireTemplatesUsageHistory::where('patient_id', $patient_id)->where('stage_id', $stage_id)->where('stage_code', $step_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->orderBy('created_at', 'desc')->get();
                }



                // $questionnaire = RCare\Ccm\Models\QuestionnaireTemplatesUsageHistory::where('patient_id', $patient_id)->where('stage_id', $stage_id)->where('stage_code', $step_id)->latest()->get();
                $questionnaire_arr = RCare\Org\OrgPackages\QCTemplates\src\Models\QuestionnaireTemplate::where('module_id', $module_id)->where('status', 1)->where('template_type_id', 5)->where('stage_id', $new_stage_id)->where('stage_code', $step_id)->orderBy('sequence', 'ASC')->get();
                foreach ($questionnaire_arr as $questionnaire) {
                    $step_name = preg_replace('/[^A-Za-z0-9\-]/', '', trim($step_name));
                    $step_name_trimmed = str_replace(' ', '_', trim($step_name)) . '' . $i;
                    $step_name_trimmed = str_replace('/', '_', trim($step_name_trimmed));
                    if (isset($questionnaire) && ($questionnaire != null) && ($questionnaire != "")) {
                        $months = json_decode($questionnaire['display_months']);
                        if (empty($months)) {
                            $months = array("All");
                        }
                        if (in_array(date('F'), $months) || in_array("All", $months)) {
                            $question_data = json_decode($questionnaire->question);
                            $template_id = json_decode($questionnaire->id);

                            $patient_template_exists = 0;
                            $monthly_notes = '';
                            $patient_questionnaire = array();
                            foreach ($patientsRelationQuestionnaire_arr as $patientsRelationQuestionnaire) {
                                if (isset($patientsRelationQuestionnaire->template_id) && $patientsRelationQuestionnaire->template_id != "") {
                                    $patient_rel_template_id = $patientsRelationQuestionnaire->template_id;
                                    if ($patient_rel_template_id == $template_id) { //  condition needs to be analysed basedon if we are updating or making new entry for same template.
                                        $patient_template_exists = 1;
                                        $patient_questionnaire = json_decode($patientsRelationQuestionnaire->template, true);
                                        $monthly_notes = $patientsRelationQuestionnaire->monthly_notes;
                                        break;
                                    }
                                }
                            }
                            $q_arr         = $question_data->question->q;
                            // $content = $content . '<div class="card-title">' . $step_name . '</div>';
                            //$content = $content . '<input type="hidden" name="'.$step_name_trimmed.'[\'template_id\']" value="'.json_decode($questionnaire[0]->id).'">';

                            $content = $content . "<input type='hidden' name=" . $step_name_trimmed . "[template_id] value=" . json_decode($questionnaire->id) . ">";
                            $content = $content . '<input type="hidden" name="' . $step_name_trimmed . '[content_title]" value="' . $questionnaire->content_title . '">';
                            $content = $content . '<input type="hidden" name="' . $step_name_trimmed . '[step_id]" value="' . $step_id . '">';
                            $content = $content . '<input type="hidden" name="que_step_id[]" value="' . $step_id . '">';
                            $content = $content . '<input type="hidden" name="' . $step_name_trimmed . '[template_type_id]" value="' . json_decode($questionnaire->template_type_id) . '">';
                            $j = 1;
                            $radioinc = 0;
                            $txtinc = 0;
                            $dropinc = 0;
                            $areainc = 0;
                            $checkinc = 0;
                            $questionExist = 0;
                            foreach ($q_arr as $q) {
                                $questionTitle = str_replace(' ', '_', trim($q->questionTitle));
                                if (array_key_exists($questionTitle, $patient_questionnaire)) {
                                    $questionExist = 1;
                                }

                                $exist = 0;
                                $scoreid = '';
                                $score_value = 0;
                                $onclick = '';
                                $placeholder = '';
                                if (property_exists($q, 'placeholder')) {
                                    $placeholder = $q->placeholder[0];
                                }
                                if (property_exists($q, 'score') && $questionnaire->score == 1) {
                                    $exist = 1;
                                    $score = $q->score;
                                }

                                $content = $content . '<div class="form-row"> <div class="col-md-12 form-group"> <div class="mb-1"><strong class="mr-1">' . $j . ': <span>' . $q->questionTitle . '</span> <span class="error">*</span></strong><input type="hidden" name="qseq[' . json_decode($questionnaire->id) . '][' . $j . ']" value="' . $j . '"></div>';
                                if (property_exists($q, 'label') && $q->answerFormat == '1') {

                                    $qid = $step_name_trimmed . '' . $dropinc;
                                    if ($exist == 1) {
                                        $onclick = "onchange=add_score(this,'$step_name_trimmed','$dropinc','drop')";
                                    }

                                    $content = $content . '<select name="' . $step_name_trimmed . '[question][' . $questionTitle . ']" class="col-md-3 custom-select" ' . $onclick . '>';
                                    $content = $content . '<option value="">Select Option</option>';
                                    $k = 0;
                                    foreach ($q->label as $labels) {
                                        if ($exist == 1 && $score[$k] != '') {
                                            $scoreid = $score[$k];
                                            $onclick = "onclick=add_score($scoreid,'$step_name_trimmed','$dropinc','drop')";
                                            if ($questionExist && $patient_questionnaire[$questionTitle] == $labels) {
                                                $score_value = $scoreid;
                                            }
                                        }

                                        $content = $content . '<option id="' . $scoreid . '" value="' . $labels . '" ' . ($questionExist && $patient_questionnaire[$questionTitle] == $labels ? 'selected' : '') . '>' . $labels . '</option>';
                                        $k++;
                                    }
                                    $content = $content . '</select><div class="invalid-feedback"></div><input  type="hidden" class="drop-' . $step_name_trimmed . '" value="' . $score_value . '" id="drop-score-' . $qid . '">';
                                    $dropinc++;
                                } elseif ($q->answerFormat == '2') {
                                    $qid = $step_name_trimmed . '' . $txtinc;
                                    if ($exist == 1) {
                                        $scoreid = $score[0];
                                        $onclick = "onclick=add_score($scoreid,'$step_name_trimmed','$txtinc','text')";
                                        if ($questionExist) {
                                            $score_value = $scoreid;
                                        }
                                    }
                                    $content = $content . '<input type="text" ' . $onclick . ' name="' . $step_name_trimmed . '[question][' . $questionTitle . ']" class="form-control col-md-8"  placeholder="' . $placeholder . '" value="' . ($questionExist ? $patient_questionnaire[$questionTitle] : '') . '"><div class="invalid-feedback"></div><input  type="hidden" class="text-' . $step_name_trimmed . '" value="' . $score_value . '" id="text-score-' . $qid . '">';
                                    $txtinc++;
                                } elseif (property_exists($q, 'label') && $q->answerFormat == '3') {
                                    $content = $content . '<div class="checkRadio forms-element">';
                                    $k = 0;
                                    $qid = $step_name_trimmed . '' . $radioinc;
                                    $radioex = 0;
                                    foreach ($q->label as $labels) {
                                        if ($exist == 1) {
                                            if ($score[$k] == '') {
                                                $score[$k] = 0;
                                            }
                                            $scoreid = $score[$k];
                                            $onclick = "onclick=add_score($scoreid,'$step_name_trimmed','$radioinc','radio')";
                                            if (array_key_exists($questionTitle, $patient_questionnaire)) {
                                                if ($questionExist && $patient_questionnaire[$questionTitle] == $labels) {
                                                    $score_value = $scoreid;
                                                }
                                                $radioex = 1;
                                            }
                                        }
                                        $rdid = $questionTitle . '_' . $labels . '_' . json_decode($questionnaire->id);
                                        $content = $content . '<label class="radio radio-primary col-md-4 float-left" for="' . $rdid . '">
                                                    <input type="radio" ' . $onclick . ' name="' . $step_name_trimmed . '[question][' . $questionTitle . ']" value="' . $labels . '" formControlName="radio" id="' . $rdid . '"  ' . ($radioex ? ($questionExist && $patient_questionnaire[$questionTitle] == $labels ? 'checked' : '') : '') . '>
                                                    <span>' . $labels . '</span>
                                                    <span class="checkmark"></span>
                                                </label>';
                                        $k++;
                                    }
                                    $content = $content . '</div><div class="invalid-feedback"></div><input  type="hidden" class="radio-' . $step_name_trimmed . '" value="' . $score_value . '" id="radio-score-' . $qid . '">';
                                    $radioinc++;
                                } elseif (property_exists($q, 'label') && $q->answerFormat == '4') {
                                    $content = $content . '<div class="checkRadio forms-element">';
                                    $k = 0;
                                    $qid = $step_name_trimmed . '' . $checkinc;
                                    foreach ($q->label as $labels) {

                                        $labelArray = str_replace(' ', '_', trim($labels));
                                        $labelArray1 = str_replace('_', ' ', trim($labels));
                                        //echo "--".str_replace(' ','_',$labels).'<br/>';

                                        $questionExist = 0;
                                        if (!empty($patient_questionnaire)) {
                                            if (array_key_exists($questionTitle, $patient_questionnaire)) {
                                                if (array_key_exists($labelArray, $patient_questionnaire[$questionTitle])) {
                                                    $questionExist = 1;
                                                    //print_r(str_replace('_',' ', trim($labels)));
                                                    //print_r($patient_questionnaire[$questionTitle][str_replace(' ','_',$labels)]);
                                                    //echo "<br>";
                                                    //print_r($labelArray1);
                                                    //echo "<br>";
                                                }
                                            }
                                        }


                                        if ($exist == 1) {
                                            if ($score[$k] == '') {
                                                $score[$k] = 0;
                                            }
                                            $scoreid = $score[$k];
                                            $onclick = "onclick=add_score($scoreid,'$step_name_trimmed','$checkinc','check')";
                                            if (array_key_exists($questionTitle, $patient_questionnaire)) {
                                                if (array_key_exists(str_replace(' ', '_', $labels), $patient_questionnaire[$questionTitle])) {
                                                    if ($questionExist && $patient_questionnaire[$questionTitle][str_replace(' ', '_', $labels)] == $labelArray1) {
                                                        $score_value = $score_value + $scoreid;
                                                    }
                                                }
                                            }
                                        }
                                        $cbid = $questionTitle . '_' . $labelArray . '_' . json_decode($questionnaire->id);
                                        $content = $content . '<label class="checkbox checkbox-primary col-md-4 float-left" for="' . $cbid . '">
                                                    <input ' . $onclick . ' class="form-check-input" value="' . $labels . '" type="checkbox" name="' . $step_name_trimmed . '[question][' . $questionTitle . '][' . $labelArray . ']" id="' . $cbid . '"  ' . ($questionExist && $patient_questionnaire[$questionTitle][str_replace(' ', '_', $labels)] == $labelArray1 ? 'checked' : '') . '>
                                                    <span>' . $labels . '</span>
                                                    <span class="checkmark"></span>
                                                </label>';
                                        $k++;
                                    }
                                    $content = $content . '</div><div class="invalid-feedback"></div><input type="hidden" class="check-' . $step_name_trimmed . '" value="' . $score_value . '"  id="check-score-' . $qid . '">';
                                    $checkinc++;
                                } elseif ($q->answerFormat == '5') {
                                    $qid = $step_name_trimmed . '' . $areainc;
                                    if ($exist == 1) {
                                        $scoreid = $score[0];
                                        $onclick = "onclick=add_score($scoreid,'$step_name_trimmed','$areainc','textarea')";
                                        if ($questionExist) {
                                            $score_value = $scoreid;
                                        }
                                    }
                                    $content = $content . '<textarea ' . $onclick . ' class="form-control col-md-8" placeholder="' . $placeholder . '" name="' . $step_name_trimmed . '[question][' . $questionTitle . ']" >' . ($questionExist ? $patient_questionnaire[$questionTitle] : '') . '</textarea><div class="invalid-feedback"></div><input  type="hidden" class="textarea-' . $step_name_trimmed . '" value="' . $score_value . '" id="textarea-score-' . $qid . '">';
                                    $areainc++;
                                }
                                $content = $content . '</div></div>';
                                // $content = $content . '<div class="invalid-feedback"></div>';  
                                $content = $content . '';
                                $j++;

                                $patientsRelationQuestionnaire = "";
                            }
                            $sc = '';
                            $scoreExit = 0;
                            if (isset($patient_questionnaire['score'])) {
                                $scoreExit = 1;
                            }
                            if ($exist == 1) {
                                $sc = '<strong>Score :</strong> <span id="total-' . $step_name_trimmed . '">' . ($scoreExit && $exist ? $patient_questionnaire['score'] : '0') . '</span>';
                            }
                            $content = $content . '<div><label class="mr-3">Current Monthly Notes:</label><textarea class="form-control col-md-8" placeholder="Current Monthly Notes" name="' . $step_name_trimmed . '[current_monthly_notes]">' . $monthly_notes . '</textarea><div class="invalid-feedback"></div></div> <div class="mt-2">' . $sc . '</div>';
                            if ($exist == 1) {
                                $content = $content . '<input type="hidden" name="qseq[' . json_decode($questionnaire->id) . '][' . $j . ']" value="' . $j . '"><input type="hidden" name="' . $step_name_trimmed . '[question][score]"  value="' . ($scoreExit && $exist ? $patient_questionnaire['score'] : '0') . '" id="score-' . $step_name_trimmed . '"><hr>';
                            }
                            $i++;
                        }
                    }
                }
            }
        }

        echo '<div id="patient_build"></div>';


        return $content;
    }


    function getRelationshipQuestionnaire($patient_id, $m, $sm)
    {
        $module_id = $m;
        $submodule_id = $sm;
        $stage_id = getFormStageId($module_id, $submodule_id, "Relationship");
        $i = 0;

        $content = "";
        $content = $content . '<input type="hidden" name="stage_id" value="' . $stage_id . '">';
        //$patientqut =  RCare\Patients\Models\PatientQuestionnaire::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->orderBy('created_at', 'desc')->first();
        // return " module_id==" . $module_id . "  submodule_id=="  . $submodule_id . "  stage_id==" .$stage_id;
        // $steps = DB::connection('ren_core')->select("SELECT * FROM ren_core.stage_codes WHERE module_id = '".$module_id."' AND submodule_id = '".$submodule_id."' AND stage_id ='".$stage_id."'");
        $steps = RCare\Org\OrgPackages\StageCodes\src\Models\StageCode::where('module_id', $module_id)->where('submodule_id', $submodule_id)->where('stage_id', $stage_id)->orderBy('sequence', 'ASC')->get();

        $callp = RCare\Ccm\Models\CallPreparation::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->orderBy('created_at', 'desc')->first();
        foreach ($steps as $step) {
            $new_stage_id = $step->stage_id;
            $step_id   = $step->id;
            $step_name = $step->description;
            $step_name = preg_replace('/[^A-Za-z0-9\-]/', '', trim($step_name));
            $step_name_trimmed = str_replace(' ', '_', trim($step_name));

            $one_time = RCare\Org\OrgPackages\QCTemplates\src\Models\QuestionnaireTemplate::where('one_time_entry', 1)->where('stage_code', $step_id)->get();
            //print_r($one_time[0]->one_time_entry);
            if (isset($one_time[0]->one_time_entry)) {
                $patientsRelationQuestionnaire = RCare\Ccm\Models\QuestionnaireTemplatesUsageHistory::where('patient_id', $patient_id)->where('stage_id', $stage_id)->where('stage_code', $step_id)->orderBy('created_at', 'desc')->first();
            } else {
                $patientsRelationQuestionnaire = RCare\Ccm\Models\QuestionnaireTemplatesUsageHistory::where('patient_id', $patient_id)->where('stage_id', $stage_id)->where('stage_code', $step_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->orderBy('created_at', 'desc')->first();
            }



            // $questionnaire = RCare\Ccm\Models\QuestionnaireTemplatesUsageHistory::where('patient_id', $patient_id)->where('stage_id', $stage_id)->where('stage_code', $step_id)->latest()->get();
            $questionnaire = RCare\Org\OrgPackages\QCTemplates\src\Models\QuestionnaireTemplate::where('module_id', $module_id)->where('status', 1)->where('template_type_id', 5)->where('stage_id', $new_stage_id)->where('stage_code', $step_id)->orderBy('id', 'DESC')->take(1)->get();
            if (isset($questionnaire) && ($questionnaire != null) && ($questionnaire != "") && count($questionnaire) > 0) {

                $months = json_decode($questionnaire[0]->display_months);
                if (empty($months)) {
                    $months = array("All");
                }
                if (in_array(date('F'), $months) || in_array("All", $months)) {
                    $question_data = json_decode($questionnaire[0]->question);
                    $template_id = json_decode($questionnaire[0]->id);

                    $patient_template_exists = 0;
                    $monthly_notes = '';
                    $patient_questionnaire = array();
                    if (isset($patientsRelationQuestionnaire->template_id) && $patientsRelationQuestionnaire->template_id != "") {
                        $patient_rel_template_id = $patientsRelationQuestionnaire->template_id;
                        if ($patient_rel_template_id == $template_id) { //  condition needs to be analysed basedon if we are updating or making new entry for same template.
                            $patient_template_exists = 1;
                            $patient_questionnaire = json_decode($patientsRelationQuestionnaire->template, true);
                        }
                        $monthly_notes = $patientsRelationQuestionnaire->monthly_notes;
                    }
                    $q_arr         = $question_data->question->q;
                    $content = $content . '<div class="card-title">' . $step_name . '</div>';
                    //$content = $content . '<input type="hidden" name="'.$step_name_trimmed.'[\'template_id\']" value="'.json_decode($questionnaire[0]->id).'">';

                    $content = $content . "<input type='hidden' name=" . $step_name_trimmed . "[template_id] value=" . json_decode($questionnaire[0]->id) . ">";
                    // $content = $content . '<input type="hidden" name="'.$step_name_trimmed.'[stage_id]" value="'.$stage_id.'">';
                    $content = $content . '<input type="hidden" name="' . $step_name_trimmed . '[step_id]" value="' . $step_id . '">';
                    $content = $content . '<input type="hidden" name="step_id" value="' . $step_id . '">';
                    $content = $content . '<input type="hidden" name="' . $step_name_trimmed . '[template_type_id]" value="' . json_decode($questionnaire[0]->template_type_id) . '">';
                    $j = 1;
                    $radioinc = 0;
                    $txtinc = 0;
                    $dropinc = 0;
                    $areainc = 0;
                    $checkinc = 0;
                    foreach ($q_arr as $q) {
                        $questionTitle = str_replace(' ', '_', trim($q->questionTitle));
                        $questionExist = 0;
                        if (array_key_exists($questionTitle, $patient_questionnaire)) {
                            $questionExist = 1;
                        }

                        $exist = 0;
                        $scoreid = '';
                        $score_value = 0;
                        $onclick = '';
                        if (property_exists($q, 'score') && $questionnaire[0]->score) {
                            $exist = 1;
                            $score = $q->score;
                        }

                        $content = $content . '<div class="form-row"> <div class="col-md-12 form-group"> <div class="mb-1"><strong class="mr-1">' . $j . ': <span>' . $q->questionTitle . '</span> </strong></div>';
                        if (property_exists($q, 'label') && $q->answerFormat == '1') {
                            $qid = $step_name_trimmed . '' . $dropinc;
                            if ($exist == 1) {
                                $onclick = "onchange=add_score(this,'$qid','drop')";
                            }
                            //print_r($patient_questionnaire);
                            $content = $content . '<select name="' . $step_name_trimmed . '[question][' . $questionTitle . ']" class="col-md-3 custom-select" ' . $onclick . '>';
                            $content = $content . '<option value="">Select Option</option>';
                            $k = 0;
                            foreach ($q->label as $labels) {
                                if ($exist == 1) {
                                    $scoreid = $score[$k];
                                    $onclick = "onclick=add_score($scoreid,'$step_name_trimmed','drop')";
                                    if ($questionExist && $patient_questionnaire[$questionTitle] == $labels) {
                                        $score_value = $scoreid;
                                    }
                                }
                                $content = $content . '<option  id="' . $scoreid . '" value="' . $labels . '" ' . ($questionExist && $patient_questionnaire[$questionTitle] == $labels ? 'selected' : '') . '>' . $labels . '</option>';
                                $k++;
                            }
                            $content = $content . '</select><div class="invalid-feedback"></div><input class="drop-' . $step_name_trimmed . '" type="hidden" value="' . $score_value . '" id="drop-score-' . $qid . '">';
                            $dropinc++;
                        } elseif ($q->answerFormat == '2') {
                            $qid = $step_name_trimmed . '' . $txtinc;
                            if ($exist == 1) {
                                $scoreid = $score[0];
                                $onclick = "onclick=add_score($scoreid,'$qid','text')";
                                if ($questionExist) {
                                    $score_value = $scoreid;
                                }
                            }
                            $content = $content . '<input type="text" ' . $onclick . ' name="' . $step_name_trimmed . '[question][' . $questionTitle . ']" class="form-control col-md-8"  value="' . ($questionExist ? $patient_questionnaire[$questionTitle] : '') . '"><div class="invalid-feedback"></div><input  type="hidden" class="text-' . $step_name_trimmed . '" value="' . $score_value . '" id="text-score-' . $qid . '">';
                            $txtinc++;
                        } elseif (property_exists($q, 'label') && $q->answerFormat == '3') {
                            $content = $content . '<div class="checkRadio forms-element">';
                            $k = 0;
                            $qid = $step_name_trimmed . '' . $radioinc;
                            foreach ($q->label as $labels) {
                                if ($exist == 1) {
                                    $scoreid = $score[$k];
                                    $onclick = "onclick=add_score($scoreid,'$qid','radio')";
                                    if ($questionExist && $patient_questionnaire[$questionTitle] == $labels) {
                                        $score_value = $scoreid;
                                    }
                                }
                                $content = $content . '<label class="radio radio-primary col-md-4 float-left" for="' . $questionTitle . '_' . $labels . '">
                                                    <input ' . $onclick . ' type="radio" name="' . $step_name_trimmed . '[question][' . $questionTitle . ']" value="' . $labels . '" formControlName="radio" id="' . $questionTitle . '_' . $labels . '"  ' . ($questionExist && $patient_questionnaire[$questionTitle] == $labels ? 'checked' : '') . '>
                                                    <span>' . $labels . '</span>
                                                    <span class="checkmark"></span>
                                                </label>';
                                $k++;
                            }
                            $content = $content . '</div><div class="invalid-feedback"></div><input  type="hidden" class="radio-' . $step_name_trimmed . '" value="' . $score_value . '" id="radio-score-' . $qid . '">';
                            $radioinc++;
                        } elseif (property_exists($q, 'label') && $q->answerFormat == '4') {
                            $content = $content . '<div class="checkRadio forms-element">';
                            $k = 0;
                            $qid = $step_name_trimmed . '' . $checkinc;
                            foreach ($q->label as $labels) {
                                $checked = '';
                                if (array_key_exists($questionTitle, $patient_questionnaire)) {
                                    if (array_key_exists(str_replace(' ', '_', $labels), $patient_questionnaire[$questionTitle])) {
                                        $checked = 'checked';
                                    }
                                }
                                if ($exist == 1) {
                                    $scoreid = $score[$k];
                                    $onclick = "onclick=add_score($scoreid,'$qid','check')";
                                    if ($questionExist && $patient_questionnaire[$questionTitle][str_replace(' ', '_', $labels)] == 1) {
                                        $score_value = $score_value + $scoreid;
                                    }
                                }
                                //print_r($patient_questionnaire[$questionTitle][str_replace(' ','_',$labels)]);
                                //echo "--".str_replace(' ','_',$labels).'<br/>';

                                $labelArray = str_replace(' ', '_', trim($labels));
                                $content = $content . '<label class="checkbox checkbox-primary col-md-4 float-left" for="' . $questionTitle . '_' . $labelArray . '">
                                                    <input ' . $onclick . ' class="form-check-input" value="' . $labels . '" type="checkbox" name="' . $step_name_trimmed . '[question][' . $questionTitle . '][' . $labelArray . ']" id="' . $questionTitle . '_' . $labelArray . '"  ' . $checked . '>
                                                    <span>' . $labels . '</span>
                                                    <span class="checkmark"></span>
                                                </label>';
                                $k++;
                            }
                            $content = $content . '</div><div class="invalid-feedback"></div><input type="hidden" class="check-' . $step_name_trimmed . '" value="' . $score_value . '"  id="check-score-' . $qid . '">';
                            $checkinc++;
                        } elseif ($q->answerFormat == '5') {
                            $qid = $step_name_trimmed . '' . $areainc;
                            if ($exist == 1) {
                                $scoreid = $score[0];
                                $onclick = "onclick=add_score($scoreid,'$qid','textarea')";
                                if ($questionExist) {
                                    $score_value = $scoreid;
                                }
                            }
                            $content = $content . '<textarea ' . $onclick . ' class="form-control col-md-8" name="' . $step_name_trimmed . '[question][' . $questionTitle . ']" >' . ($questionExist ? $patient_questionnaire[$questionTitle] : '') . '</textarea><div class="invalid-feedback"></div><input  type="hidden" class="textarea-' . $step_name_trimmed . '" value="' . $score_value . '" id="textarea-score-' . $qid . '">';
                            $areainc++;
                        }
                        $content = $content . '</div></div>';
                        // $content = $content . '<div class="invalid-feedback"></div>';  
                        $content = $content . '';
                        $j++;

                        $patientsRelationQuestionnaire = "";
                    }
                    $sc = '';
                    if ($exist == 1) {
                        $sc = '<strong>Score :</strong> <span id="total-' . $step_name_trimmed . '">' . ($questionExist && $exist ? $patient_questionnaire['score'] : '0') . '</span>';
                    }
                    $content = $content . '<div><span class="mr-3">Current Monthly Notes:</span><textarea class="form-control col-md-8" placeholder="Current Monthly Notes" name="' . $step_name_trimmed . '[current_monthly_notes]">' . $monthly_notes . '</textarea><div class="invalid-feedback"></div></div> <div class="mt-2">' . $sc . '</div>';
                    if ($exist == 1) {
                        $content = $content . '<input type="hidden" name="' . $step_name_trimmed . '[question][score]"  value="' . ($questionExist && $exist ? $patient_questionnaire['score'] : '0') . '" id="score-' . $step_name_trimmed . '"><hr>';
                    }
                    $i++;
                }
            }
        }
        echo '<div class="form-row"><div class="col-md-12 form-group card-title">Patient Relationship Building</div></div>';
        echo '<div id="patient_build"></div>';


        echo $content;
        // }else{
        // echo $content;
        // }

    }

    // function getContentTemplateBasedOnStage($module_id, $component_id, $stage_id) {
    //     // $module_id = getPageModuleName();
    //     // $component_id = getPageSubModuleName();
    //     $content_template = DB::connection('ren_core')->select("select * from content_templates where module_id = '".$module_id."' AND component_id = '".$component_id."' AND stage_id ='".$stage_id."'")->latest();
    //     $content_template_arr =[];
    //     $i = 0;
    //     foreach ($content_template as $template) {
    //         $content_template_arr[$i]['id']            = $template->id;
    //         $content_template_arr[$i]['content_title'] = $template->content_title;
    //         $content_template_arr[$i]['content']       = $template->content;
    //         $i++;
    //     }
    //     return $content_template_arr;

    //     // return (count($content_template) > 0 ? $content_template[0]->id : 0 );
    // }
    /*
function getDevice($id){
    $patient_device = RCare\Patients\Models\PatientDevices::where('patient_id', $id)->get(['vital_devices']);
    $nin = array();
    foreach($patient_device as $dv){
       $js = json_decode($dv->vital_devices);
       foreach($js as $val){
           if(isset($val->vid)){
               $nin[] = array_push($nin,$val->vid);
           }
          
       }
    }
    
    $List = implode(', ', $nin);
    $flist = ltrim($List,',');
    $l = '1,2';
    $device = RCare\Org\OrgPackages\Devices\src\Models\Devices::whereNotIn('id',$nin)->where('status','1')->get();
    return $device;
  }*/

    function getQuestionnaireTemplate($moduleId, $subModuleId, $stageId, $stepId)
    {
        // echo $moduleId;
        //echo "testing";
        $questionnaire = RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController::getDynamicQuestionnaireTemplate($moduleId, $subModuleId, $stageId, $stepId);
        $content = "";
        // print_r($questionnaire);  

        // die;
        if (!empty($questionnaire[0]->question)) {
            // if (count($questionnaire)>0) {
            // echo "testing123"; die;

            $questions = json_decode($questionnaire[0]->question);

            $question_data    = json_decode($questionnaire[0]->question);
            $content_title    = $questionnaire[0]->content_title;
            $template_id      = json_decode($questionnaire[0]->id);
            $template_type_id = json_decode($questionnaire[0]->template_type_id);
            $q_arr            = $question_data->question->q;

            $content = $content . "<input type='hidden' name='content_title' value='" . $content_title . "'>";
            $content = $content . "<input type='hidden' name='template_id' value='" . $template_id . "'>";
            $content = $content . "<input type='hidden' name='template_type_id' value='" . $template_type_id . "'>";
            $j = 1;
            if ($stepId == 37) {
                $qname = 'fquestion';
            } else {
                $qname = 'question';
            }
            foreach ($q_arr as $q) {
                $questionTitle = str_replace(' ', '_', trim($q->questionTitle));
                $questionExist = 0;
                //  if (array_key_exists($questionTitle, $patient_questionnaire)) {
                // 	$questionExist = 1;
                //  }

                $content = $content . '<div class="col-md-12 form-row"> <div class="form-group col-md-12"> <div class="mb-1"><strong class="mr-1">' . $j . ': <span>' . $q->questionTitle . '</span> <span class="error">*</span></strong></div>';
                if (property_exists($q, 'label') && $q->answerFormat == '1') {

                    $content = $content . '<select name="' . $qname . '[' . $questionTitle . ']" class="col-md-3 custom-select forms-element" >';
                    $content = $content . '<option value="">Select Option</option>';
                    foreach ($q->label as $labels) {
                        $content = $content . '<option value="' . $labels . '" ' . ($questionExist && $patient_questionnaire[$questionTitle] == $labels ? 'selected' : '') . '>' . $labels . '</option>';
                    }
                    $content = $content . '</select><div class="invalid-feedback"></div>';
                } elseif ($q->answerFormat == '2') {
                    $content = $content . '<input type="text" name="' . $qname . '[' . $questionTitle . ']" class="form-control col-md-8"  value="' . ($questionExist ? $patient_questionnaire[$questionTitle] : '') . '"><div class="invalid-feedback"></div>';
                } elseif (property_exists($q, 'label') && $q->answerFormat == '3') {
                    $content = $content . '<div class="checkRadio forms-element">';
                    foreach ($q->label as $labels) {
                        $content = $content . '<label class="radio radio-primary ml-2 float-left">
                                                <input type="radio" name="' . $qname . '[' . $questionTitle . ']" value="' . $labels . '" formControlName="radio"  ' . ($questionExist && $patient_questionnaire[$questionTitle] == $labels ? 'checked' : '') . '>
                                                <span>' . $labels . '</span>
                                                <span class="checkmark"></span>
                                            </label>';
                    }
                    $content = $content . '</div><div class="invalid-feedback"></div>';
                } elseif (property_exists($q, 'label') && $q->answerFormat == '4') {
                    $content = $content . '<div class="checkRadio forms-element">';
                    foreach ($q->label as $labels) {
                        $content = $content . '<label class="checkbox checkbox-primary col-md-4 float-left">
                                                <input class="form-check-input" type="checkbox" value="' . $labels . '" name="' . $qname . '[' . $questionTitle . ']"  ' . ($questionExist && $patient_questionnaire[$questionTitle] == $labels ? 'checked' : '') . '>
                                                <span>' . $labels . '</span>
                                                <span class="checkmark"></span>
                                            </label>';
                    }
                    $content = $content . '</div><div class="invalid-feedback"></div>';
                } elseif ($q->answerFormat == '5') {
                    $content = $content . '<textarea class="form-control col-md-8 forms-element" name="' . $qname . '[' . $questionTitle . ']" >' . ($questionExist ? $patient_questionnaire[$questionTitle] : '') . '</textarea><div class="invalid-feedback"></div>';
                }
                $content = $content . '</div></div>';
                $content = $content . '';
                $j++;

                // 	$patientsRelationQuestionnaire = "";
            }
        } else {
            // echo "testing";
            // die;
        }

        echo $content;
    }

    //created by pranali on 29Oct2020
    function queryEscape($rawQuery)
    {
        $pdo = DB::connection()->getPdo();  // get pdo from laravel database
        $query = $pdo->prepare($rawQuery); // prepare raw sql
        $succ = $query->execute(); // execute raw sql
        if ($succ) {
            if ($query->fetch()) { // check result for select query
                $query->execute();
                return  $query->fetch();
            } else {
                return $query->rowCount();
            }
        } else {
            return 0;
        }
    }

    //created by pranali on 03Nov2020
    function convertTimeToSeceonds($time)
    {
        $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $time);
        sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
        $seconds = ($hours * 3600) + ($minutes * 60) + $seconds;
        return $seconds;
    }

    //created by pranali on 01Dec2020
    function sanitizeVariable($var)
    {
        if (is_string($var) == 1) {
            $trimmed_var    = trim($var);
            $strip_tags_var = Purifier::clean($trimmed_var);
            $sanitized_var  = str_replace("''", "'", pg_escape_string($strip_tags_var));
            return $sanitized_var;
        } else if (is_numeric($var) == 1) { //|| is_bool($var)
            $trimmed_var    = trim($var);
            $strip_tags_var = Purifier::clean($trimmed_var);
            $sanitized_var  = pg_escape_string($strip_tags_var);
            return $sanitized_var;
        } else if (is_array($var) == 1) {
            foreach ($var as $key => $value) {
                if (is_array($value))
                    $var[$key] = sanitizeVariable($value);
                else
                    $var[$key] = str_replace("''", "'", Purifier::clean(pg_escape_string(trim($value))));
            }
            return $var;
        }
        //  else if(is_bool($var)){
        //     $string = strtolower($var);
        //     $string_check = (in_array($string, array("true", "false", "1", "0", "yes", "no"), true));
        //     if($string_check == true) {
        //         return $var;
        //     } else {
        //         return "";
        //     }
        // }

        else {
            return $var;
        }
    }

    //created by pranali on 15Jan2021
    function sanitizeMultiDimensionalArray($array)
    {
        return sanitizeVariable($array);
    }
