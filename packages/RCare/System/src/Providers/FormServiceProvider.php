<?php

/**
 * Form Service Provider
 * Created by David Ludwig
 *
 * Extends the Blade templating engine to include some useful form generation directives.
 * See documentation for more details.
 */

namespace RCare\System\Providers;

//use App\Models\Practice;
use App\Models\Patient;
//use App\Models\Menu;
//use App\Models\Services;
use RCare\System\Support\Form;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use DB;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend("gender", "RCare\System\Rules\Gender");
        Validator::extend("phone", "RCare\System\Rules\PhoneNumber");
        Validator::extend("within_year", "RCare\System\Rules\WithinYear");
        Validator::extend("medication_unique_name", "RCare\System\Rules\MedicationUniqueName"); //added by pranali 09Oct2020
        Validator::extend("provider_unique_name", "RCare\System\Rules\ProviderUniqueName"); //added by pranali 29Oct2020
        Validator::extend("time_textbox", "RCare\System\Rules\TimeTextbox"); //added by pranali 30Oct2020
        Validator::extend("module_unique_name", "RCare\System\Rules\ModuleUniqueName"); //added by pranali 16June2022
        Validator::extend('alpha_spaces', function ($attribute, $value) { //added by pranali 11Feb2021
            // This will only accept alpha and spaces.
            // If you want to accept hyphens use: /^[\pL\s-]+$/u.
            
             return preg_match('/^[\pL\s]+$/u', $value); 
        }); 
        Validator::extend('address', function ($attribute, $value) { //added by Priya 12Feb2021
            // This will only accept alpha, num ,comma, fullstop ,_, dash and spaces. 
            return preg_match('/^[a-zA-Z0-9 . , - ]*$/', $value); 
        });
        
        Validator::extend("check_date_format", "RCare\System\Rules\ValidateDate");
        // Validator::extend("privilege", "RCare\System\Rules\Privilege");
        // Validator::extend("check_id",    "RCare\System\Rules\CheckId");
        Validator::extend('unique_custom', function ($attribute, $value, $parameters, $validator) {
            //print_r($parameters);		
            $validator_ar = $validator->getData();		
            list($table, $field, $field2) = $parameters;
            
            $checkid =  $validator_ar['insurance_primary_idnum_check'];
            
            // Get the parameters passed to the rule				
        
            //echo $field2;
            // Check the table and return true only if there are no entries matching
            // both the first field name and the user input value as well as
            // the second field name and the second field value
            if($checkid == '0'){
                return DB::table('patients')->where($field, $value)->count() == 0;
            } else {
                return true;
            }
        }); 
		
		Validator::extend('text_comments_slash', function ($attribute, $value) { //added by Ashvini 2Feb2023
            // This will only accept alpha, num ,comma, fullstop ,_, dash, braket, apostrophe,slash and spaces. 
			return true;
            //return preg_match('/^[a-zA-Z0-9- . , _ ( )\/ \'&]*$/', $value);      
           
        }); 
            
        // Validator::extend('unique_module', function ($attribute, $value, $parameters, $validator) {
        //     $query = DB::table($parameters[0]);
        //     // $column = $query->getGrammar()->wrap($parameters[1]);
        //     return ! $query->whereRaw("lower({$column}) = lower(?)", [$value])->count();
        // });
		
		/*Validator::extend('unique_secondary_custom', function ($attribute, $value, $parameters, $validator) 
			{
				//print_r($parameters);		
				$validator_ar = $validator->getData();		
				list($table, $field, $field2) = $parameters;
				
				$checkid =  $validator_ar['insurance_secondary_idnum_check'];
				
				// Get the parameters passed to the rule				
			
				//echo $field2;
				// Check the table and return true only if there are no entries matching
				// both the first field name and the user input value as well as
				// the second field name and the second field value
				if($checkid == '0'){
					return DB::table('patients')->where($field, $value)->count() == 0;
				}else{
					return true;
				}
			});*/
		
		
        $this->bootBladeFormDirectives();
    } 

    /**
     * Boot Blade form directives to use within views
     *
     * @return void
     *
	 */
    protected function bootBladeFormDirectives()
    {
        $this->bootInputFields();
        $this->bootSelectFields();
        $this->bootPrintingFields();

        Blade::directive("dynamicarea", function ($expression) {
            $params = parseParameters($expression);
            return "<div data-dynamic-template='$params[1]' data-dynamic-area='$params[0]'></div>"
                 . "<div class='row'><div class='col-md-12 form-group'>"
                 . "<button class='btn btn-outline-primary' data-dynamic-action='add' type='button' data-dynamic-group='$params[0]'>Add $params[2]</button>"
                 . "</div></div>";
        });
    }


    /**
     * Create the Blade directives for fillable input fields, like text boxes, password boxes, etc.
     *
     * @return void
     */
    protected function bootInputFields()
    {
        Blade::directive("input", function ($expression) {
            return "<?php echo \RCare\System\Support\Form::input($expression); ?>";
        });

        Blade::directive("hidden", function ($expression) {
            return "<?php echo \RCare\System\Support\Form::input('hidden', $expression); ?>";
        });

        Blade::directive("date", function ($expression) {
            return "<?php echo \RCare\System\Support\Form::input('date', $expression); ?>";
        });

        Blade::directive("month", function ($expression) {
            return "<?php echo \RCare\System\Support\Form::input('month', $expression); ?>";
        });

        Blade::directive("time", function ($expression) {
            return "<?php echo \RCare\System\Support\Form::input('time', $expression); ?>";
        });

        Blade::directive("email", function ($expression) {
            return "<?php echo \RCare\System\Support\Form::input('email', $expression); ?>";
        });


        Blade::directive("number", function ($expression) {
            return "<?php echo \RCare\System\Support\Form::input('number', $expression); ?>";
        });

        Blade::directive("password", function ($expression) {
            return "<?php echo \RCare\System\Support\Form::input('password', $expression); ?>";
        });

        Blade::directive("file", function ($expression) {
            return "<?php echo \RCare\System\Support\Form::input('file', $expression); ?>";
        });

        Blade::directive("phone", function ($expression) {
            $params = parseParameters($expression);
            $name = $params[0];
            $attributes = defaultParameter($params, 1, []);
            $attributes["data-inputmask"] = "'mask': '(999) 999-9999'";
            $value = defaultParameter($params, 2, "");
            return Form::input("text", $name, $attributes, $value);
        });

        Blade::directive("timetext", function ($expression) {
            $params = parseParameters($expression);
            $name = $params[0];
            $attributes = defaultParameter($params, 1, []);
            $attributes["data-inputmask"] = "'mask': '99:99:99'";
            $value = defaultParameter($params, 2, "");
            return Form::input("text", $name, $attributes, $value);
        });

        Blade::directive("text", function ($expression) {
            return "<?php echo \RCare\System\Support\Form::input('text', $expression); ?>";
        });

        Blade::directive("textarea", function($expression) {
            $params     = [' . $expression . '];
            $name       = $params[0];
            $id         = $params[1];
            return Form::textarea('Pr', null, ['class' => 'field noscrollbars', 'onkeyup' => 'autoAdjustTextArea(this);']);
        });

        Blade::directive("checkbox", function($expression) {
            return '<?php
                $params     = [' . $expression . '];
                $label      = $params[0];
                $name       = $params[1];
                $id         = $params[2];
                $value      = defaultParameter($params, 3, 1);
                $attributes = defaultParameter($params, 4, []);
                $checked    = defaultParameter($params, 5, False);
                echo \RCare\System\Support\Form::checkBox($name, $id, $label, $value, $attributes, $checked);
            ?>';
        });

        Blade::directive("sectioncheckbox", function ($expression) {
            $params      = parseParameters($expression);
            $label       = $params[0];
            $name        = $params[1];
            $id          = $params[2];
            $value       = defaultParameter($params, 3, 1);
            $attributes  = defaultParameter($params, 4, []);
            $checked     = defaultParameter($params, 5, false);
            return Form::sectionCheckBox($name, $id, $label, $value, $attributes, $checked);
        });
    }

    /**
     * Create the Blade directives for various select fields
     *
     * @return void
     */
    protected function bootSelectFields()
    {
        Blade::directive("select", function ($expression) {
            return "<?php echo RCare\System\Support\Form::select($expression); ?>";
        });

        Blade::directive("selectsearch", function ($expression) {
            return "<?php echo RCare\System\Support\Form::selectSearchable($expression); ?>";
        });
        
        Blade::directive("selectactivestatus", function ($expression) {
            // Use this style to force caching
            $params     = parseParameters($expression);
            $name       = $params[0];
            $attributes = defaultParameter($params, 1, []);
            $selected   = defaultParameter($params, 2, "");
            return Form::select("Status", $name, config("form")["activeStatus"], $attributes, $selected);
        });

        Blade::directive("selectanswerformat", function ($expression) {
            // Use this style to force caching
            $params     = parseParameters($expression);
            $name       = $params[0];
            $attributes = defaultParameter($params, 1, []);
            $selected   = defaultParameter($params, 2, "");
            return Form::select("Answer Format", $name, config("form")["answer_format"], $attributes, $selected);
        });

        Blade::directive("selectanswer", function ($expression) {
            // Use this style to force caching
            $params     = parseParameters($expression);
            $name       = $params[0];
            $attributes = defaultParameter($params, 1, []);
            $selected   = defaultParameter($params, 2, "");
            return Form::select("Voice Mail Action", $name, config("form")["answer"], $attributes, $selected);
        });

        Blade::directive("selectactivity", function ($expression) {
            // Use this style to force caching
            $params     = parseParameters($expression);
            $name       = $params[0];
            $attributes = defaultParameter($params, 1, []);
            $selected   = defaultParameter($params, 2, "");
            return Form::select("Activity", $name, config("form")["activity"], $attributes, $selected);
        });

        

        Blade::directive("selectreviewdata", function ($expression) {
            // Use this style to force caching
            $params     = parseParameters($expression);
            $name       = $params[0];
            $attributes = defaultParameter($params, 1, []);
            $selected   = defaultParameter($params, 2, "");
            return Form::select("Review Data", $name, config("form")["review_data"], $attributes, $selected);
        });
        // Blade::directive("selectcategory", function ($expression) {
        //     // Use this style to force caching
        //     $params     = parseParameters($expression);
        //     $name       = $params[0];
        //     $attributes = defaultParameter($params, 1, []);
        //     $selected   = defaultParameter($params, 2, "");
        //     return Form::selectOrOther("Category", $name, config("form")["category"], $attributes, $selected);
        // });

        Blade::directive("selectGroupedPractices", function ($expression) {
            return '<?php
                $params     = [' . $expression . ']; 
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $data       = RCare\Org\OrgPackages\Practices\src\Models\Practices::groupedPractices();
                echo RCare\System\Support\Form::selectWithOptionGroup("Practices", $name, $attributes, $selected, $data);
            ?>'; 
        });

        Blade::directive("selectGroupedPatientActivites", function ($expression) {
            return '<?php
                $params     = [' . $expression . ']; 
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $data       = RCare\TaskManagement\Models\PatientActivity::groupedPatientActivies();
                echo RCare\System\Support\Form::selectWithOptionGroup("Patient Activity", $name, $attributes, $selected, $data);
            ?>'; 
        });

       
          

        Blade::directive("selecteducation", function ($expression) {
            // Use this style to force caching
            $params     = parseParameters($expression);
            $name       = $params[0];
            $attributes = defaultParameter($params, 1, []);
            $selected   = defaultParameter($params, 2, "");
            return Form::selectOrOther("Education", $name, config("form")["education"], $attributes, $selected);
        });

        Blade::directive("selectslicensemodel", function ($expression) {
            // Use this style to force caching
            $params     = parseParameters($expression);
            $name       = $params[0];
            $attributes = defaultParameter($params, 1, []);
            $selected   = defaultParameter($params, 2, "");
            return Form::select("License Model", $name, config("form")["license_model"], $attributes, $selected);
        });

        Blade::directive("selectethnicity", function ($expression) {
            // Use this style to force caching
            $params     = parseParameters($expression);
            $name       = $params[0];
            $attributes = defaultParameter($params, 1, []);
            $selected   = defaultParameter($params, 2, "");
            return Form::selectOrOther("Ethnicity", $name, config("form")["ethnicities"], $attributes, $selected);
        });

        Blade::directive("selectmedabbrev", function ($expression) {
            // Use this style to force caching
            $params     = parseParameters($expression);
            $name       = $params[0];
            $attributes = defaultParameter($params, 1, []);
            $selected   = defaultParameter($params, 2, "");
            return Form::select("Medical Abbreviations", $name, config("form")["medical_abbreviations"], $attributes, $selected);
        });

        Blade::directive("selectstate", function ($expression) {
            // Use this style to force caching
            $params     = parseParameters($expression);
            $name       = $params[0];
            $attributes = defaultParameter($params, 1, []);
            $selected   = defaultParameter($params, 2, "");
            return Form::select("State", $name, config("form")["states"], $attributes, $selected);
        });

        Blade::directive("selectother", function ($expression) {
            return "<?php echo RCare\System\Support\Form::selectOrOther($expression); ?>";
        });
    
        Blade::directive("selectadmissionfacilitytype", function ($expression) {
            // Use this style to force caching
            $params     = parseParameters($expression);
            $name       = $params[0];
            $attributes = defaultParameter($params, 1, []);
            $selected   = defaultParameter($params, 2, "");
            return Form::select("Admission Facility Type", $name, config("form")["admission_facility_type"], $attributes, $selected);
        });

        Blade::directive("selectadmittedform", function ($expression) {
            // Use this style to force caching
            $params     = parseParameters($expression);
            $name       = $params[0];
            $attributes = defaultParameter($params, 1, []);
            $selected   = defaultParameter($params, 2, "");
            return Form::select("Admitted Form", $name, config("form")["admitted_form"], $attributes, $selected);
        });

        Blade::directive("selectdischargedto", function ($expression) {
            // Use this style to force caching
            $params     = parseParameters($expression);
            $name       = $params[0];
            $attributes = defaultParameter($params, 1, []);
            $selected   = defaultParameter($params, 2, "");
            return Form::select("Discharged To", $name, config("form")["discharged_to"], $attributes, $selected);
        });

         Blade::directive("selectrole", function ($expression) {
             return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["" => "None"];
                foreach (RCare\RCareAdmin\AdminPackages\Role\src\Models\Roles::activeRoles() as $roles) {
                    $options[$roles->id] = $roles->role_name;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Role", $name, $options, $attributes, $selected);
            ?>';
        });
         
        Blade::directive("selectUsers", function ($expression) {
            return '<?php
            $params     = [' . $expression . '];
            $name       = $params[0];
            $attributes = defaultParameter($params, 1, []);  
            $selected   = defaultParameter($params, 2, "");
            
            foreach (RCare\Org\OrgPackages\Users\src\Models\Users::activeUsers() as $users) {
                $options[$users->id] = $users->f_name . " " . $users->l_name;      
            }
            $options = array_unique($options);
            echo RCare\System\Support\Form::select("Users", $name, $options, $attributes, $selected); 
        ?>'; 
        });  



        Blade::directive("selectReportName", function ($expression) {
            return '<?php
            $params     = [' . $expression . '];
            $name       = $params[0];
            $attributes = defaultParameter($params, 1, []);  
            $selected   = defaultParameter($params, 2, "");
        
            foreach (RCare\Org\OrgPackages\ReportsMaster\src\Models\ReportsMaster::activeReports() as $reports) {
                $options[$reports->id] = $reports->display_name;      
            }
            $options = array_unique($options);
            echo RCare\System\Support\Form::select("Report Name", $name, $options, $attributes, $selected); 
        ?>'; 
        });  
        
        Blade::directive("selectorgrole", function ($expression) {
             return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["0" => "None"];
                foreach (RCare\Org\OrgPackages\Roles\src\Models\Roles::activeRole() as $roles) {
                    $options[$roles->id] = $roles->role_name;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Role", $name, $options, $attributes, $selected);
            ?>';
        });

        Blade::directive("selectoffices", function ($expression) {
             return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = [];
                foreach (RCare\Org\OrgPackages\Offices\src\Models\Office::activeOffice() as $offices) {
                    $options[$offices->id] = $offices->location;
                }
                $options = array_unique($options); 
                echo RCare\System\Support\Form::select("Office", $name, $options, $attributes, $selected);
            ?>';
        });

        Blade::directive("selectactivitytimertype", function ($expression) {
            return '<?php
               $params     = [' . $expression . '];
               $name       = $params[0];
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");
               $options    = [];
               foreach ( RCare\Org\OrgPackages\Activity\src\Models\Activity::ActivityTimerType() as $a) {
                   $options[$a->id] = $a->activity;
               }
               $options = array_unique($options); 
               echo RCare\System\Support\Form::select("Activity", $name, $options, $attributes, $selected);
           ?>';
       });

        Blade::directive("selectlab", function ($expression) {
            return '<?php
               $params     = [' . $expression . '];
               $name       = $params[0];
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");
               $options    = ["0" => "Other"];
               foreach (RCare\Org\OrgPackages\Labs\src\Models\Labs::activeLabs() as $labs) {
                   $options[$labs->id] = $labs->description;
               }
               $options = array_unique($options);
               echo RCare\System\Support\Form::select("Lab", $name, $options, $attributes, $selected);
           ?>';
       });

        Blade::directive("selectcategory", function ($expression) {
             return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["" => "None"];
                foreach (RCare\Org\OrgPackages\Users\src\Models\UserCategory::all() as $category) {
                    $options[$category->id] = $category->description;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Level", $name, $options, $attributes, $selected);
            ?>';
        });

        Blade::directive("selectdevices", function ($expression) {
            return '<?php
               $params     = [' . $expression . '];
               $name       = $params[0];
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");
        
               foreach (RCare\Org\OrgPackages\Devices\src\Models\Devices::activeDevices() as $dev) {
                   $options[$dev->id] = $dev->device_name;
               }
               $options = array_unique($options);
               echo RCare\System\Support\Form::select("Devices", $name, $options, $attributes, $selected);
           ?>';
       });

        Blade::directive("selectlevel", function ($expression) {
            return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = [];
                foreach (RCare\Org\OrgPackages\Users\src\Models\UserCategory::all() as $category) {
                    $options[$category->id] = $category->description;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Levels", $name, $options, $attributes, $selected);
            ?>';
        });

        //created and modified by ashvini 06thapril2021
        Blade::directive("selectstartdateofscheduler", function ($expression) {
            return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = [];
                foreach (RCare\Org\OrgPackages\Scheduler\src\Models\SchedulerLogHistory::schedulerStartdate() as $s) {  
                    $options[$s->start_date] = $s->start_date;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Start Date", $name, $options, $attributes, $selected);
            ?>';
        });

         //created and modified by ashvini 06thapril2021
        Blade::directive("selectdateofexecution", function ($expression) { 
            return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = [];
                foreach (RCare\Org\OrgPackages\Scheduler\src\Models\SchedulerLogHistory::schedulerDateofExecution() as $s) {
                    $options[$s->schedulerrecord_date] = $s->convertedschedulerrecord_date;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Date of Execution", $name, $options, $attributes, $selected);
            ?>';
        });


        
        Blade::directive("selectpractices", function ($expression) {
             return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = [];
                foreach (RCare\Org\OrgPackages\Practices\src\Models\Practices::activePractices() as $practices) {
                    $loctn = "";
					if($practices->location!=""){
						$loctn = $practices->location;
					}
                     $options[$practices->id] = $practices->name. " " . $loctn;
                }

                $options = array_unique($options);
                echo RCare\System\Support\Form::select("All Practices", $name, $options, $attributes, $selected);
            ?>';
            //$options    = ["0" => "All"];
        });

        Blade::directive("selectpracticespcp", function ($expression) {
             return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = [];
                foreach (RCare\Org\OrgPackages\Practices\src\Models\Practices::activePcpPractices() as $practices) {
                    $loctn = "";
                    if($practices->location!=""){
                        $loctn = $practices->location; 
                    }
                     $options[$practices->id] = $practices->name. " " . $loctn;
                }

                $options = array_unique($options);
                echo RCare\System\Support\Form::select("All Clinic", $name, $options, $attributes, $selected);
            ?>';
            //$options    = ["0" => "All"];
        });

        // created by priya 10dec2020 for provider
        Blade::directive("selectpracticeswithother", function ($expression) {
             return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["0" => "Other"];
                foreach (RCare\Org\OrgPackages\Practices\src\Models\Practices::activePractices() as $practices) {
                    $loctn = "";
                    if($practices->location!=""){
                        $loctn = $practices->location;
                    }
                     $options[$practices->id] = $practices->name. " " . $loctn;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("All Practices", $name, $options, $attributes, $selected);
            ?>';
            //$options    = ["0" => "All"]; 
        });

        

        // created by priya 1dec2020 //modified by ashvini 18jan 2021
        Blade::directive("selectgrppractices", function ($expression) {
             return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, ""); 
                $options    = [];
                foreach (RCare\Org\OrgPackages\Practices\src\Models\PracticesGroup::activeGrpPractices() as $practices) {
                     $options[$practices->id] = $practices->practice_name;
                }
                $options = array_unique($options);
                $dynamicname = config("global.practice_group");
                echo RCare\System\Support\Form::select($dynamicname, $name, $options, $attributes, $selected); 
            ?>'; 
            //$options    = ["0" => "All"];
        }); 

        Blade::directive("selectdocument", function ($expression) {
             return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, ""); 
                $options    = ["0" => "Other"];
                foreach (RCare\Org\OrgPackages\Practices\src\Models\Document::activeDocument() as $document) {
                     $options[$document->doc_type] = $document->doc_type;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Document Type", $name, $options, $attributes, $selected); 
            ?>'; 
        });


        Blade::directive("selectpartner", function ($expression) {
            return '<?php
               $params     = [' . $expression . '];
               $name       = $params[0];
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, ""); 
               $options    = [];
               foreach (RCare\Org\OrgPackages\Partner\src\Models\Partner::activePartner() as $partners) {
                    $options[$partners->id] = $partners->name;
               }
               $options = array_unique($options);
              
               echo RCare\System\Support\Form::select("Partner", $name, $options, $attributes, $selected); 
           ?>'; 
           //$options    = ["0" => "All"];
       });
       
       Blade::directive("selectrpmenrolledpartner", function ($expression) {
        return '<?php
           $params     = [' . $expression . '];
           $name       = $params[0];
           $attributes = defaultParameter($params, 1, []);
           $selected   = defaultParameter($params, 2, ""); 
           $options    = [];
           foreach (RCare\Org\OrgPackages\Partner\src\Models\Partner::activePartner1() as $partners) {
                $options[$partners->id] = $partners->name;
           }
           $options = array_unique($options);
          
           echo RCare\System\Support\Form::select("Partner", $name, $options, $attributes, $selected); 
       ?>'; 
       //$options    = ["0" => "All"];
   });        
        
        Blade::directive("selectpracticeswithAll", function ($expression) {
            return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["0" => "None"];
                foreach (RCare\Org\OrgPackages\Practices\src\Models\Practices::activePractices() as $practices) {
                    $loctn = "";
                    if($practices->location!=""){
                        $loctn = $practices->location;
                    }
                    $c = "";
                    if(isset($practices->count)){
                        $c = $practices->count;
                    }
                    $options[$practices->id] = $practices->name. " " . $loctn . " ". " (".$c.")";
                    
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("All Practices", $name, $options, $attributes, $selected);
            ?>';
            //$options    = ["0" => "All"];
        });
        Blade::directive("selectpracticeswithoutNone", function ($expression) {
            return '<?php
               $params     = [' . $expression . '];
               $name       = $params[0];
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");
               
               foreach (RCare\Org\OrgPackages\Practices\src\Models\Practices::activePractices() as $practices) {
                   $loctn = "";
                   if($practices->location!=""){
                       $loctn = $practices->location;
                   }
                   $options[$practices->id] = $practices->name. " " . $loctn . " ". " (".$practices->count.")";
                  
               }
               $options = array_unique($options);
               echo RCare\System\Support\Form::select("a Practice", $name, $options, $attributes, $selected);
           ?>';
           //$options    = ["0" => "All"];
       });

       //created and modified by ashvini
       Blade::directive("selectrpmpractices", function ($expression) { 
        return '<?php 
           $params     = [' . $expression . '];
           $name       = $params[0];
           $attributes = defaultParameter($params, 1, []);
           $selected   = defaultParameter($params, 2, "");
           $options    = [];
           foreach (RCare\Org\OrgPackages\Practices\src\Models\Practices::RpmPractice() as $practices) {
               $loctn = "";
               if($practices->location!=""){
                   $loctn = $practices->location;  
               }
                $options[$practices->id] = $practices->name. " " . $loctn;
           }

           $options = array_unique($options);
           echo RCare\System\Support\Form::select("All Practices", $name, $options, $attributes, $selected);
       ?>';
       //$options    = ["0" => "All"];
   });

    //created and modified by ashvini 21 sept 2021
    Blade::directive("selectrpmpracticesgrouprolebased", function ($expression) { 
		return '<?php 
		   $params     = [' . $expression . '];
		   $name       = $params[0];
		   $attributes = defaultParameter($params, 1, []);
		   $selected   = defaultParameter($params, 2, "");
		   $options    = [];
		   foreach (RCare\Org\OrgPackages\Practices\src\Models\Practices::RpmPracticeGroupRolesbased() as $practicesgroup) {
			  
			   
				$options[$practicesgroup->id] = $practicesgroup->practice_name;  
		   }

		   $options = array_unique($options);
		   $dynamicname = config("global.practice_group");
		   echo RCare\System\Support\Form::select($dynamicname, $name, $options, $attributes, $selected);
	   ?>';
	   //$options    = ["0" => "All"];
	});

    //created and modified by ashvini
    Blade::directive("selectrpmpracticesgroup", function ($expression) { 
        return '<?php 
           $params     = [' . $expression . '];
           $name       = $params[0];
           $attributes = defaultParameter($params, 1, []);
           $selected   = defaultParameter($params, 2, "");
           $options    = [];
           foreach (RCare\Org\OrgPackages\Practices\src\Models\Practices::RpmPracticeGroup() as $practicesgroup) {
              
               
                $options[$practicesgroup->id] = $practicesgroup->practice_name;  
           }

           $options = array_unique($options);
           $dynamicname = config("global.practice_group");
           echo RCare\System\Support\Form::select($dynamicname, $name, $options, $attributes, $selected);
       ?>';
       //$options    = ["0" => "All"];
   });

   //created and modified by ashvini 21 sept 2021
   Blade::directive("selectrpmpracticesrolebased", function ($expression) { 
    return '<?php 
       $params     = [' . $expression . '];
       $name       = $params[0];
       $attributes = defaultParameter($params, 1, []);
       $selected   = defaultParameter($params, 2, "");
       $options    = [];
       foreach (RCare\Org\OrgPackages\Practices\src\Models\Practices::RpmPracticeRolesbased() as $practices) {
           $loctn = "";
           if($practices->location!=""){
               $loctn = $practices->location;  
           }
            $options[$practices->id] = $practices->name. " " . $loctn;
       }

       $options = array_unique($options);
       echo RCare\System\Support\Form::select("All Practices", $name, $options, $attributes, $selected);
   ?>';
   //$options    = ["0" => "All"];
});

 // created by ashvini 29nov2021 for followuptaskcategory
 Blade::directive("selecttaskcategory", function ($expression) {
    return '<?php
       $params     = [' . $expression . '];
       $name       = $params[0];
       $attributes = defaultParameter($params, 1, []);
       $selected   = defaultParameter($params, 2, "");
       $options    = [];
       foreach (RCare\TaskManagement\Models\ToDoList::activeTask() as $actTask) {
           $taskname = "";
           if($actTask->status!=""){
               $taskname = $actTask->status;
           }
            $options[$actTask->id] = $actTask->status;
       }
       $options = array_unique($options);
       echo RCare\System\Support\Form::select("All Category", $name, $options, $attributes, $selected);
   ?>';
  
});




        Blade::directive("selectworklistpractices", function ($expression) {
            return '<?php
               $params     = [' . $expression . '];
               $name       = $params[0];
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");
               foreach (RCare\Org\OrgPackages\Practices\src\Models\Practices::worklistPractices() as $practices) {
                   $loctn = "";
                   if($practices->location!=""){
                       $loctn = $practices->location;
                   }
                   $options[$practices->id] = $practices->name. " " . $loctn;
               }
               $options = array_unique($options);
            
               echo RCare\System\Support\Form::select("a Practice", $name, $options, $attributes, $selected);
           ?>';
           //$options    = ["0" => "All"];
       });

       //select Active followup task
        Blade::directive("selectfollowuptask", function ($expression) {
            return '<?php 
                $options    = []; 
                $params     = [' . $expression . '];
                $name       = $params[0]; 
                $patient_id  = defaultParameter($params, 1, []); 
                $attributes = defaultParameter($params, 2, []); 
                $selected   = defaultParameter($params, 3, "");
                $data       = RCare\TaskManagement\Models\ToDoList::groupedTasks($patient_id);
                echo RCare\System\Support\Form::selectWithOptionGroupWithoutSelectClass2("Followup Task", $name, $attributes, $selected, $data);
            ?>';
        });
        //select followup task
        Blade::directive("selectfuturefollowuptask", function ($expression) {
            return '<?php
                $params     = [' . $expression . ']; 
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = [];
                $abc        = session()->get("user_type"); 
                $tasks      = $abc == 1 ? RCare\Org\OrgPackages\FollowupTask\src\Models\FollowupTask::activeTask() : RCare\Org\OrgPackages\FollowupTask\src\Models\FollowupTask::activeTask();
                foreach ($tasks as $active_task) {
                    $options[$active_task->id] = $active_task->task;
                } 
                echo RCare\System\Support\Form::select("Task Category", $name, $options, $attributes, $selected);
            ?>';
        });
        //select Dactivation Reasons
        Blade::directive("selectdeactivationReasons", function ($expression) {
            return '<?php
                $params     = [' . $expression . ']; 
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = [];
                $reasons    = RCare\Org\OrgPackages\DeactivationReasons\src\Models\DeactivationReasons::activeReasons();
                foreach ($reasons as $active_reasons) { 
                    $options[$active_reasons->id] = $active_reasons->reasons;
                } 
                echo RCare\System\Support\Form::select("Deactivation Reasons", $name, $options, $attributes, $selected);
            ?>';
        });
        
        Blade::directive("selectdiagnosiscode", function ($expression) {
             return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = [];
                foreach (RCare\Org\OrgPackages\Diagnosis\src\Models\Diagnosis::activeDiagnosis() as $diagnosis) {
                    $options[$diagnosis->id] = $diagnosis->code;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Type", $name, $options, $attributes, $selected);
            ?>';
        });
        
        Blade::directive("selectconditioncode", function ($expression) {
             return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["0" => "Other"];
                foreach (RCare\Org\OrgPackages\Diagnosis\src\Models\DiagnosisCode::activeDiagnosiscode() as $diagnosis) {
                    $options[$diagnosis->code] = $diagnosis->code;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Code", $name, $options, $attributes, $selected);
            ?>';
        });


        Blade::directive("selectcountrycode", function ($expression) {
            return '<?php
               $params     = [' . $expression . ']; 
               $name       = $params[0];
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");
               $options    = [];
               foreach (RCare\Patients\Models\CountryCode::country() as $code) {
                   $options["+".$code->countries_isd_code] = $code->countries_name ." (".$code->countries_iso_code.") +".$code->countries_isd_code;
               }
               $options = array_unique($options);
               echo RCare\System\Support\Form::select("Country Code", $name, $options, $attributes, $selected);
           ?>';
       });


        Blade::directive("selectdiagnosiscondition", function ($expression) {
            return '<?php
               $params     = [' . $expression . '];
               $name       = $params[0];
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");
               $options    = [];
               foreach (RCare\Org\OrgPackages\Diagnosis\src\Models\Diagnosis::activeDiagnosis() as $diagnosis) {
                   $options[$diagnosis->id] = $diagnosis->condition;
               }
               $options = array_unique($options);
               echo RCare\System\Support\Form::select("Condition", $name, $options, $attributes, $selected);
           ?>';
       });

        Blade::directive("selectCarePlancondition", function ($expression) {
            return '<?php
               $params     = [' . $expression . '];
               $name       = $params[0];
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");
               $options    = [];
               foreach (RCare\Org\OrgPackages\CarePlanTemplate\src\Models\CarePlanTemplate::activeCarePlanTemplate() as $careplancondition) {
                   $options[$careplancondition->diagnosis_id] = $careplancondition->condition;
               }
               $options = array_unique($options);
               echo RCare\System\Support\Form::select("Condition", $name, $options, $attributes, $selected);
           ?>';
        });


        Blade::directive("selectspecialpractices", function ($expression) {
             return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, ""); 
                $options    = ["0" => "Other"];
                foreach (RCare\Org\OrgPackages\Providers\src\Models\ProviderSubtype::activeSubPractices() as $specialpractices) {
                    $options[$specialpractices->id] = $specialpractices->sub_provider_type;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Credential", $name, $options, $attributes, $selected);
            ?>';
        });

        
        Blade::directive("selectspeciality", function ($expression) {
             return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];       
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, ""); 
                $options    = [];
                foreach (RCare\Org\OrgPackages\Providers\src\Models\Speciality::activeSpeciality() as $speciality) {
                    $options[$speciality->id] = $speciality->speciality;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Speciality", $name, $options, $attributes, $selected);
            ?>';
        });
		
         Blade::directive("selectpcppractices", function ($expression) {
             return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, ""); 
                $options    = [];
                foreach (RCare\Org\OrgPackages\Providers\src\Models\ProviderSubtype::activeSubPracticesPCP() as $pcpsubpractices) {
                    $options[$pcpsubpractices->id] = $pcpsubpractices->sub_provider_type;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Credential", $name, $options, $attributes, $selected);
            ?>';
        });

        Blade::directive("selectmedications", function ($expression) {
             return '<?php 
                $options    = [];
                $params     = [' . $expression . '];
                $name       = $params[0]; 
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = [];
                foreach (RCare\Org\OrgPackages\Medication\src\Models\Medication::activeMedication() as $medicine) {
                    $options[$medicine->id] = $medicine->description;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Medication", $name, $options, $attributes, $selected);
            ?>';
        });

        Blade::directive("selectsubcategory", function ($expression) {
            return '<?php 
               $options    = [];
               $params     = [' . $expression . '];
               $name       = $params[0]; 
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");
               $options    = [];
               foreach (RCare\Org\OrgPackages\Medication\src\Models\SubCategory::activeSubCategory() as $medicine) {
                   $options[$medicine->id] = $medicine->subcategory;
               }
               $options = array_unique($options);
               echo RCare\System\Support\Form::select("Subcategory", $name, $options, $attributes, $selected);
           ?>';
       });

       
       Blade::directive("selectcategory", function ($expression) {
        return '<?php 
           $options    = [];
           $params     = [' . $expression . '];
           $name       = $params[0]; 
           $attributes = defaultParameter($params, 1, []);
           $selected   = defaultParameter($params, 2, "");
           $options    = [];
           foreach (RCare\Org\OrgPackages\Medication\src\Models\Category::activeCategory() as $medicine) {
               $options[$medicine->id] = $medicine->category;
           }
           $options = array_unique($options);
           echo RCare\System\Support\Form::select("Category", $name, $options, $attributes, $selected);
       ?>';
   });


        Blade::directive("selectsurgery", function ($expression) {
            return '<?php 
               $options    = [];
               $params     = [' . $expression . '];
               $name       = $params[0]; 
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");
               $options    = [];
               foreach (RCare\Org\OrgPackages\Medication\src\Models\Medication::activeMedication() as $medicine) {
                   $options[$medicine->id] = $medicine->name;
               }
               $options = array_unique($options);
               echo RCare\System\Support\Form::select("Surgery", $name, $options, $attributes, $selected);
           ?>';
       });

        Blade::directive("selectemrpractice", function ($expression) {
             return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, ""); 
                $options    = [];
                foreach (RCare\Patients\Models\PatientProvider::where("provider_type_id",1)->where("is_active",1)->get() as $emr) {
                    $options[$emr->practice_emr] = $emr->practice_emr;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("EMR", $name, $options, $attributes, $selected);
            ?>';
        });


        Blade::directive("selectprovider", function ($expression) {
             return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, ""); 
                $options    = ["0" => "Other"];
                foreach (RCare\Org\OrgPackages\Providers\src\Models\Providers::activeProviders() as $provider) {
                    $options[$provider->id] = $provider->name;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Provider", $name, $options, $attributes, $selected);
            ?>';
        });

        Blade::directive("selectFamilyRelationship", function ($expression) {
             return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["0" => "Other"];
                foreach (RCare\Patients\Models\PatientFamily::activeRelationship() as $relationship) {
                    $options[$relationship->relationship] = $relationship->relationship;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Relationship", $name, $options, $attributes, $selected);
            ?>';
        });

        Blade::directive("selectprovidertypes", function ($expression) {
             return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["0" => "None"];
                foreach (RCare\Org\OrgPackages\Providers\src\Models\ProviderType::activeProvidertype() as $providertype) {
                    $options[$providertype->id] = $providertype->provider_type;
                }
                echo RCare\System\Support\Form::select("Type", $name, $options, $attributes, $selected);
            ?>';
        });
         Blade::directive("selectpracticesphysician", function ($expression) {
             return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0]; 
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["0" => "None"];
                foreach (RCare\Org\OrgPackages\Providers\src\Models\Providers::activeProviders() as $providers) {
                    $options[$providers->id] = $providers->name;
                    
                } 
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Primary Care Provider (PCP)", $name, $options, $attributes, $selected);
            ?>';
        });

        Blade::directive("selectpracticesphysicianother", function ($expression) {
            return '<?php
               $params     = [' . $expression . '];
               $name       = $params[0];
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");
               $options    = ["0" => "None"];
               foreach (RCare\Org\OrgPackages\Providers\src\Models\Providers::newactiveProviders() as $providers) {
                   $options[$providers->id] = $providers->name. " ". " (".$providers->count.")";
                   
               }
               $options = array_unique($options);
               echo RCare\System\Support\Form::select("Primary Care Provider (PCP)", $name, $options, $attributes, $selected);
           ?>';
       });


        Blade::directive("selectpcpprovider", function ($expression) {
             return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = [];
                foreach (RCare\Org\OrgPackages\Providers\src\Models\Providers::activePCPProvider() as $providers) {
                    $options[$providers->id] = $providers->name;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Primary Care Provider (PCP)", $name, $options, $attributes, $selected);
            ?>';
        });

         Blade::directive("selectorguser", function ($expression) {
             return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["0" => "None"];
                foreach (RCare\Org\OrgPackages\Users\src\Models\Users::activeUsers() as $user) {
                    $options[$user->id] = $user->f_name. " " . $user->l_name ;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Report To", $name, $options, $attributes, $selected);
            ?>';
        });

        Blade::directive("selectrcareorg", function ($expression) {
            //    foreach (RCare\RCareAdmin\AdminPackages\Organization\src\Models\RcareOrgs::activeRcareOrgs() as $orgs) {
            return '<?php
               $params     = [' . $expression . '];
               $name       = $params[0];
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");
               $options    = ["0" => "None"];
               foreach (RCare\RCareAdmin\AdminPackages\Organization\src\Models\RcareOrgs::all() as $orgs) {
                   $options[$orgs->id] = $orgs->name;
               }
               $options = array_unique($options);
               echo RCare\System\Support\Form::select("Organization", $name, $options, $attributes, $selected);
           ?>';
       });

         Blade::directive("selectorgcategory", function ($expression) {
            //    foreach (RCare\RCareAdmin\AdminPackages\Organization\src\Models\RcareOrgs::activeRcareOrgs() as $orgs) {
            return '<?php
               $params     = [' . $expression . '];
               $name       = $params[0];
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");
               $options    = ["0" => "None"];
               foreach (RCare\RCareAdmin\AdminPackages\Organization\src\Models\OrgCategory::all() as $orgs) {
                   $options[$orgs->id] = $orgs->category;
               }
               $options = array_unique($options);
               echo RCare\System\Support\Form::select("Category", $name, $options, $attributes, $selected);
           ?>';
       });

        Blade::directive("selectpatient", function ($expression) {
            
            return '<?php
               $params     = [' . $expression . '];
               $name       = $params[0];
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");
               $options    = ["0" => "None"];
               foreach (RCare\Patients\Models\Patients::all() as $patient) {
                    $options[$patient->id] = $patient->fname . " " . $patient->mname . " " . $patient->lname ;
               }
               $options = array_unique($options);
               echo RCare\System\Support\Form::select("Patient", $name, $options, $attributes, $selected);
           ?>';
        });

        Blade::directive("selectpatientwithdob", function ($expression) {
            
            return '<?php
               $params     = [' . $expression . '];
               $name       = $params[0];
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");
               $options    = ["0" => "None"];
               foreach (RCare\Patients\Models\Patients::all() as $patient) {
                    $options[$patient->id] = $patient->fname . " " . $patient->mname . " " . $patient->lname . " " .$patient->dob;
               }
               $options = array_unique($options);
               echo RCare\System\Support\Form::select("Patient", $name, $options, $attributes, $selected);
           ?>';
        });
        
         Blade::directive("selectallpatient", function ($expression) {
            
            return '<?php
               $params     = [' . $expression . '];
               $name       = $params[0];
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");              
               foreach (RCare\Patients\Models\Patients::all() as $patient) {
                    $options[$patient->id] = $patient->fname . " " . $patient->mname . " " . $patient->lname;
               }
               $options = array_unique($options);
               echo RCare\System\Support\Form::select("Patient", $name, $options, $attributes, $selected);
           ?>';
        });

          Blade::directive("selectpatientenrolledinrpm", function ($expression) {
            
            return '<?php
               $params     = [' . $expression . '];
               $name       = $params[0];
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");    
               $options    = [];          
               foreach (RCare\Patients\Models\Patients::PatientsEnroledInRPM() as $patient) {
                    $options[$patient->id] = $patient->fname . " " . $patient->mname . " " . $patient->lname ;
               }
               $options = array_unique($options);
               echo RCare\System\Support\Form::select("Patient", $name, $options, $attributes, $selected);
           ?>';
        });

        Blade::directive("selectallworklistpatient", function ($expression) {
            
            
            return '<?php
               $params     = [' . $expression . '];
               $name       = $params[0]; 
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");                
               foreach (RCare\Patients\Models\Patients::Allpatients() as $patient) {
                    $options[$patient->id] = $patient->fname . " " . $patient->mname . " " . $patient->lname;
               }
               $options = array_unique($options);
               echo RCare\System\Support\Form::select("Patient", $name, $options, $attributes, $selected);
           ?>';
        });

        Blade::directive("selectallworklistccmpatient", function ($expression) {
              
            
            return '<?php
               $params     = [' . $expression . '];
               $name       = $params[0]; 
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");               
               foreach (RCare\Patients\Models\Patients::Allpatients() as $patient1) {
                    $options[$patient1->id] = $patient1->fname . " " . $patient1->mname . " " . $patient1->lname;
               }
               $options = array_unique($options);
               echo RCare\System\Support\Form::select("Patient", $name, $options, $attributes, $selected);
           ?>';
        });

        Blade::directive("selectassignpatient", function ($expression) {
            return '<?php
               $loginid = session()->get("userid");
               $options    = [];
               $params     = [' . $expression . '];
               $name       = $params[0]; 
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");       
               foreach (RCare\Patients\Models\Patients::assignpatients($loginid) as $assignpatient) { 
                    $options[$assignpatient->id] = $assignpatient->fname . " " . $assignpatient->mname . " " . $assignpatient->lname;
               }
               $options = array_unique($options);
               echo RCare\System\Support\Form::select("Patient", $name, $options, $attributes, $selected);
            ?>';
        });
        

        Blade::directive("selectrpmpatient", function ($expression) {
            
            
            return '<?php
               $params     = [' . $expression . '];
               $name       = $params[0]; 
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");               
               foreach (RCare\Patients\Models\Patients::RpmPatients() as $patient1) {
                    $options[$patient1->id] = $patient1->fname . " " . $patient1->mname . " " . $patient1->lname;
               }
               $options = array_unique($options);
               echo RCare\System\Support\Form::select("Patient", $name, $options, $attributes, $selected);
           ?>';
        });

        Blade::directive("selectinsurance", function ($expression) {
            
            
            return '<?php 
               $params     = [' . $expression . '];
               $name       = $params[0]; 
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");               
               foreach (RCare\Patients\Models\PatientInsurance::patientsins() as $ins) {
                    $options[$ins->ins_provider] = $ins->ins_provider;
               }
               $options = array_unique($options);
               echo RCare\System\Support\Form::select("Patient Insurance", $name, $options, $attributes, $selected);
           ?>';
        });

        Blade::directive("selectmodules", function ($expression) {
            return '<?php
                $options    = [];
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                foreach (RCare\RCareAdmin\AdminPackages\Organization\src\Models\Rcare_Modules::all() as $module) {
                    $options[$module->id] = $module->modules;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Module", $name, $options, $attributes, $selected);
            ?>';
        });

        //to be deleted in future...
        Blade::directive("selectcallscripttemplates", function ($expression) {
            return '<?php
                $options    = [];
                $params     = [' . $expression . '];
                $name       = $params[0];
                $template_id  = $params[1];
                $attributes = defaultParameter($params, 2, []);
                $selected   = defaultParameter($params, 3, "");
                $getContent = RCare\Org\OrgPackages\QCTemplates\src\Models\ContentTemplate::getContent($template_id);
                    foreach ($getContent as $module) {
                        $options[$module->id] = $module->content_title;
                    }
                echo RCare\System\Support\Form::select("Template", $name, $options, $attributes, $selected);
            ?>';
        });
 
        Blade::directive("selectcontentscript", function ($expression) {
            return '<?php
                $options       = [];
                $params        = [' . $expression . '];
                $name          = $params[0];
                $module_id     = $params[1];
                $submodule_id  = $params[2];
                $stage_id   = $params[3];
                $step_id   = $params[4];
            
            
                $attributes    = defaultParameter($params, 5, []);
                $selected      = defaultParameter($params, 6, "");
                $getContent    = RCare\Org\OrgPackages\QCTemplates\src\Models\ContentTemplate::getContentScripts($module_id, $submodule_id, $stage_id, $step_id, $device_id=null);
                    foreach ($getContent as $module) {
                        $options[$module->id] = $module->content_title;
                    }
                echo RCare\System\Support\Form::select("Template", $name, $options, $attributes, $selected);
            ?>';
        });

        Blade::directive("selectdevicecontentscript", function ($expression) {
            return '<?php
                $options       = [];
                $params        = [' . $expression . '];
                $name          = $params[0];
                $module_id     = $params[1];
                $submodule_id  = $params[2];
                $stage_id   = $params[3];
                $step_id   = $params[4];
                $device_id = $params[5];
            // print_r($device_id);
                $attributes    = defaultParameter($params, 6, []);
                $selected      = defaultParameter($params, 7, "");
                $getContent    = RCare\Org\OrgPackages\QCTemplates\src\Models\ContentTemplate::getContentScripts($module_id, $submodule_id, $stage_id, $step_id,$device_id);
                    foreach ($getContent as $module) {
                        $options[$module->id] = $module->content_title;
                    }
                echo RCare\System\Support\Form::select("Template", $name, $options, $attributes, $selected);
            ?>';
        });

        

        Blade::directive("selectcontactnumber", function ($expression) {
            return '<?php
                $options    = []; 
                $params     = [' . $expression . '];
                $name       = $params[0];
                $patient_id = defaultParameter($params, 1, []);
                $attributes = defaultParameter($params, 2, []);
                $selected   = defaultParameter($params, 3, "");
                
                foreach (RCare\Rpm\Models\Patients::where("id",$patient_id)->select("phone_primary","phone_secondary","other_contact_phone")->get() as $module) {
                    $options[$module->id] = $module->phone_primary;
                    $options[$module->id] = $module->other_contact_phone;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Contact Number", $name, $options, $attributes, $selected);
            ?>';
         });

         Blade::directive("selectscript", function ($expression) {
            return '<?php
                $options    = [];
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");

                foreach (RCare\Rpm\Models\MailTemplate::where("template_type_id",3)->get() as $module) {
                    $options[$module->id] = $module->content_title;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Call Script", $name, $options, $attributes, $selected);
            ?>';
         });

         Blade::directive("selectOrgModule", function ($expression) {
             return '<?php
                $options    = [];
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["0" => "None"];
                foreach (RCare\Org\OrgPackages\Modules\src\Models\Module::activeModule() as $Modules) {
                    $options[$Modules->id] = $Modules->module;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Module", $name, $options, $attributes, $selected);
            ?>';
        });

        Blade::directive("selectOrgSubModule", function ($expression) {
             return '<?php
                $options    = [];
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["0" => "None"];
                foreach (RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents::activeComponents() as $subModules) {
                    $options[$subModules->id] = $subModules->components;
                }
                $options = array_unique($options); 
                echo RCare\System\Support\Form::select("Sub Modules", $name, $options, $attributes, $selected);
            ?>';
        });

          Blade::directive("selectMasterModule", function ($expression) {
             return '<?php
                $options    = [];
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["0" => "None"];
                foreach (RCare\Org\OrgPackages\Modules\src\Models\Module::activeMasterModule() as $Modules) {
                    $options[$Modules->id] = $Modules->module;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Module", $name, $options, $attributes, $selected);
            ?>';
        });

        Blade::directive("selectAllModuleWithoutNone", function ($expression) {
             return '<?php
                $options    = [];
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                foreach (RCare\Org\OrgPackages\Modules\src\Models\Module::activeModule() as $Modules) {
                    $options[$Modules->id] = $Modules->module;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("All Module", $name, $options, $attributes, $selected);
            ?>';
        });
        Blade::directive("selectCCMRPM", function ($expression) {
            return '<?php
               $options    = [];
               $params     = [' . $expression . '];
               $name       = $params[0];
               $attributes = defaultParameter($params, 1, []);
               $selected   = defaultParameter($params, 2, "");
               foreach (RCare\Org\OrgPackages\Modules\src\Models\Module::mainModule() as $Modules) { 
                   $options[$Modules->id] = $Modules->module;
               }
               $options = array_unique($options);
               echo RCare\System\Support\Form::select("Module", $name, $options, $attributes, $selected);
           ?>';
       });



        Blade::directive("selectGQ", function ($expression) {
            return '<?php
               $options    = [];
               $params     = [' . $expression . '];
               $name       = $params[0];
               $module_id     = $params[1];
               $stage_id      = $params[2];
               $attributes = defaultParameter($params, 3, []);
               $selected   = defaultParameter($params, 4, "");
               foreach (RCare\Org\OrgPackages\StageCodes\src\Models\StageCode::generalStageCode($module_id,$stage_id) as $Modules) {
                   $options[$Modules->id] = $Modules->description;
               }
               $options = array_unique($options);
               echo RCare\System\Support\Form::select("General Question", $name, $options, $attributes, $selected);
           ?>';
       });

        Blade::directive("selectContentType", function ($expression) {
            return '<?php
                $options    = [];
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                foreach (RCare\Org\OrgPackages\QCTemplates\src\Models\TemplateTypes::whereIn("id",array(1,2,3,4))->get() as $templateType) {
                    $options[$templateType->id] = $templateType->template_type;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Content Type", $name, $options, $attributes, $selected);
            ?>';
        });

        Blade::directive("selectDevice", function ($expression) {
            return '<?php
                $options    = [];
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                foreach (RCare\Rpm\Models\Devices::all() as $device) {
                    $options[$device->id] = $device->device_name;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Device", $name, $options, $attributes, $selected);
            ?>';
        });
		
		Blade::directive("selectPartnerDevice", function ($expression) {
            return '<?php
                $options    = [];
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                foreach (RCare\Rpm\Models\Partner_Devices::all() as $partnerdevice) {
                    $options[$partnerdevice->id] = $partnerdevice->device_name;
                }
                $options = array_unique($options);
                echo RCare\System\Support\Form::select("Partner Device", $name, $options, $attributes, $selected);
            ?>';
        });

        Blade::directive("selectpatientDevice", function ($expression) {
            return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = [];
                foreach (RCare\Patients\Models\PatientDevices::device() as $patient_device) {
                    $options[$patient_device->id] = $patient_device->device_code;
                }
                $options = array_unique($options); 
                echo RCare\System\Support\Form::select("Pateint Device", $name, $options, $attributes, $selected);
            ?>';
        });

        Blade::directive("selectcaremanager", function ($expression) {
            return '<?php
                $params     = [' . $expression . ']; 
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = [];
                foreach (RCare\Org\OrgPackages\Users\src\Models\Users::activeCareManager() as $caremanager) {
                    $options[$caremanager->id] = $caremanager->f_name. " " . $caremanager->l_name;
                }
                echo RCare\System\Support\Form::select("Care Manager", $name, $options, $attributes, $selected);
            ?>'; 

                    // $options[$caremanager->id] = $caremanager->f_name. " " . $caremanager->l_name. " (".$caremanager->role_name.")";

            //echo RCare\System\Support\Form::selectSearchable("CareManager", $name, $options, $attributes, $selected);
            // foreach (RCare\Org\OrgPackages\Users\src\Models\Users::where("role","17")->where("status",1)->get() as $caremanager) {
            //     $options[$caremanager->id] = $caremanager->f_name. " " . $caremanager->l_name;
            // }
        });

            Blade::directive("selectcaremanagerwithAll", function ($expression) {
            return '<?php
                $params     = [' . $expression . ']; 
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["0" => "None"];
                foreach (RCare\Org\OrgPackages\Users\src\Models\Users::activeCareManager() as $caremanager) {
                    $options[$caremanager->id] = $caremanager->f_name. " " . $caremanager->l_name. " ". " (".$caremanager->count.")"; 
                }
                echo RCare\System\Support\Form::select("Care Manager", $name, $options, $attributes, $selected);
            ?>'; 

                    // $options[$caremanager->id] = $caremanager->f_name. " " . $caremanager->l_name. " (".$caremanager->role_name.")";

            //echo RCare\System\Support\Form::selectSearchable("CareManager", $name, $options, $attributes, $selected);
            // foreach (RCare\Org\OrgPackages\Users\src\Models\Users::where("role","17")->where("status",1)->get() as $caremanager) {
            //     $options[$caremanager->id] = $caremanager->f_name. " " . $caremanager->l_name;
            // }
        });


        Blade::directive("selectAllexceptadmin", function ($expression) {
            return '<?php
                $params     = [' . $expression . ']; 
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["0" => "None"];
                foreach (RCare\Org\OrgPackages\Users\src\Models\Users::activeUsersexceptadmin() as $user) {

                   
                    $options[$user->id] = $user->f_name. " " . $user->l_name. " ". " (".$user->count.")"; 
                }
                echo RCare\System\Support\Form::select("All Users", $name, $options, $attributes, $selected);
            ?>'; 

                    // $options[$caremanager->id] = $caremanager->f_name. " " . $caremanager->l_name. " (".$caremanager->role_name.")";

            //echo RCare\System\Support\Form::selectSearchable("CareManager", $name, $options, $attributes, $selected);
            // foreach (RCare\Org\OrgPackages\Users\src\Models\Users::where("role","17")->where("status",1)->get() as $caremanager) {
            //     $options[$caremanager->id] = $caremanager->f_name. " " . $caremanager->l_name;
            // }
        });


        
            Blade::directive("selectcaremanagerwithoutNone", function ($expression) {
            return '<?php
                $params     = [' . $expression . ']; 
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                
                foreach (RCare\Org\OrgPackages\Users\src\Models\Users::activeCareManager() as $caremanager) {

                   
                    $options[$caremanager->id] = $caremanager->f_name. " " . $caremanager->l_name. " ". " (".$caremanager->count.")"; 
                }
                echo RCare\System\Support\Form::select("Care Manager", $name, $options, $attributes, $selected);
            ?>'; 

                    // $options[$caremanager->id] = $caremanager->f_name. " " . $caremanager->l_name. " (".$caremanager->role_name.")";

            //echo RCare\System\Support\Form::selectSearchable("CareManager", $name, $options, $attributes, $selected);
            // foreach (RCare\Org\OrgPackages\Users\src\Models\Users::where("role","17")->where("status",1)->get() as $caremanager) {
            //     $options[$caremanager->id] = $caremanager->f_name. " " . $caremanager->l_name;
            // }
        });

        Blade::directive("selectcaremanagerNone", function ($expression) {
            return '<?php
                $params     = [' . $expression . ']; 
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["0" => "None"];
                foreach (RCare\Org\OrgPackages\Users\src\Models\Users::activeCareManager() as $caremanager) {

                   
                    $options[$caremanager->id] = $caremanager->f_name. " " . $caremanager->l_name. " ". " (".$caremanager->count.")"; 
                }
                echo RCare\System\Support\Form::select("Care Manager", $name, $options, $attributes, $selected);
            ?>'; 
          
        });  

        Blade::directive("selectRpmcaremanagerNone", function ($expression) {
            return '<?php 
                $params     = [' . $expression . '];  
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["0" => "None"];
                foreach (RCare\Org\OrgPackages\Users\src\Models\Users::RpmActiveCareManager() as $caremanager) {

                   
                    $options[$caremanager->id] = $caremanager->f_name. " " . $caremanager->l_name. " ". " (".$caremanager->count.")"; 
                }
                echo RCare\System\Support\Form::select("Care Manager", $name, $options, $attributes, $selected);
            ?>'; 
          
        });  
      
          Blade::directive("selectExceptionEMR", function ($expression) {
            return '<?php 
                $params     = [' . $expression . '];  
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = [];
                foreach (RCare\API\Models\ApiException::whereNotNull("mrn")->distinct()->get(["mrn"]) as $exceptionmrn) {
                  
                   
                    $options[$exceptionmrn->mrn] = $exceptionmrn->mrn; 
                }
                echo RCare\System\Support\Form::select("EMR", $name, $options, $attributes, $selected);
            ?>'; 
          
        });  
      

        /*
        Blade::directive("selecttitle", function ($expression) {
            return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = [];
                foreach (App\Models\EmployeeTitle::orderBy("title")->get() as $title) {
                    $options[$title->id] = $title;
                }
                echo RCare\System\Support\Form::selectOrOther("Title", $name, $options, $attributes, $selected);
            ?>';
        });

        Blade::directive("selectmanager", function ($expression) {
            return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = [];
                $auths      = App\Models\EmployeeAuth::managers()->get();
                foreach ($auths as $auth) {
                    $employee = $auth->employee;
                    $options[$employee->id] = $employee->esig();
                }
                echo RCare\System\Support\Form::selectSearchable("None", $name, $options, $attributes, $selected);
            ?>';
        });
        
        Blade::directive("selectcaremanager", function ($expression) {
            return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = [];
                foreach (App\Models\Employee::where("title_id","3")->get() as $caremanager) {
                    $options[$caremanager->id] = $caremanager;
                }
                echo RCare\System\Support\Form::selectSearchable("CareManager", $name, $options, $attributes, $selected);
            ?>';
        });
        

        Blade::directive("selectteamleader", function ($expression) {
            return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = [];
                foreach (App\Models\Employee::where("title_id","9")->get() as $teamleader) {
                    $options[$teamleader->id] = $teamleader;
                }
                echo RCare\System\Support\Form::selectSearchable("Team Leader", $name, $options, $attributes, $selected);
            ?>';
        });
        
        //All Practice Select
        Blade::directive("selectpractice", function ($expression) {
            return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["" => "None"];
                $practices  = Auth::user()->is_admin ? App\Models\Practice::all() : Auth::user()->employee->Practices;
                foreach ($practices as $practice) {
                    $options[$practice->id] = $practice->name;
                }
                echo App\Support\Form::selectSearchable("Practice", $name, $options, $attributes, $selected);
            ?>';
        });
        */
        //Active Practice Select
        Blade::directive("selectactivepractice", function ($expression) {
            return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["" => "None"];
                $abc = session()->get("user_type");
                $practices  = $abc == 1 ? RCare\Org\OrgPackages\Practices\src\Models\Practices::activePractices() : RCare\Org\OrgPackages\Practices\src\Models\Practices::activePractices();
                foreach ($practices as $practice) {
                   $loctn = "";
					if($practices->location!=""){
						$loctn = $practices->location;
					}
                    $options[$practices->id] = $practices->name. " " . $loctn;
                }
                echo RCare\System\Support\Form::select("Practice", $name, $options, $attributes, $selected);
            ?>';
        });

        Blade::directive("selectscore", function ($expression) {
             
             return '<?php
             $params     = [' . $expression . '];
             $name       = $params[0];
             $attributes = defaultParameter($params, 1, []);
             $selected   = defaultParameter($params, 2, "");
             $options    = [];
             foreach (RCare\TaskManagement\Models\ToDoList::whereNotNull("score")->distinct()->orderBy("score")->get(["score"]) as $score) {
                     $options[$score->score] = $score->score;
             }
             $options = array_unique($options);
             echo RCare\System\Support\Form::select("Score", $name, $options, $attributes, $selected);
         ?>';
         });
        /*
        Blade::directive("selectpatient", function ($expression) {
            return 
            '<?php
                $params      = [' . $expression . '];
                $name        = $params[0];
                $attributes  = defaultParameter($params, 1, []);
                $selected    = defaultParameter($params, 2, "");
                $options     = [];
                $practiceIds = [];
                $practices   = Auth::user()->is_admin ? App\Models\Practice::all() : Auth::user()->employee->practices;
                foreach ($practices as $practice) {
                    $practiceIds[] = $practice->id;
                }
                foreach (App\Models\Patients::whereIn("practice_id", $practiceIds)->get() as $patient) {
                    $patientString = $patient->fname . " " . $patient->mname . " " . $patient->lname . ", DOB: " . dateValue($patient->dob);
                    $options[$patient->id] = $patientString;
                }
                echo App\Support\Form::selectSearchable("Patient", $name, $options, $attributes, $selected);
            ?>';
        });

        ///////////////////
      

        Blade::directive("selectemployee", function ($expression) {
            return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["" => "None"];
                foreach (App\Models\Employee::all() as $employee) {
                    $options[$employee->id] = $employee->esig();
                }
                echo App\Support\Form::selectSearchable("Employee", $name, $options, $attributes, $selected);
            ?>';
        });
		

        Blade::directive("selectassigntcm", function ($expression) {
            return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["" => "None"];
                foreach (App\Models\Employee::where("title_id","3")->get() as $assigned_to) {
                    $options[$assigned_to->id] = $assigned_to;
                }
                echo App\Support\Form::selectSearchable("CareManager Assign", $name, $options, $attributes, $selected);
            ?>';
        }); 

        Blade::directive("selectreassigntcm", function ($expression) {
            return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["" => "None"];
                foreach (App\Models\Employee::where("title_id","3")->get() as $reassigned_to) {
                    $options[$reassigned_to->id] = $reassigned_to;
                }
                echo App\Support\Form::selectSearchable("CareManager Assign", $name, $options, $attributes, $selected);
            ?>';
        });

        Blade::directive("selecthospital", function ($expression) {
            return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["" => "None"];
                foreach (App\Models\TcmForm::all() as $hospital) {
                    $options[$hospital->hName] = $hospital->hName;
                }
				$options = array_unique($options);
                echo App\Support\Form::selectSearchable("Hospital", $name, $options, $attributes, $selected);
            ?>';
        });

        Blade::directive("selectservices", function ($expression) {
            return '<?php
                $params     = [' . $expression . '];
                $name       = $params[0];
                $attributes = defaultParameter($params, 1, []);
                $selected   = defaultParameter($params, 2, "");
                $options    = ["" => "None"];
                foreach (App\Models\Services::all() as $services) {
                    $options[$services->service_name] = $services->service_name;
                }
				$options = array_unique($options);
                echo App\Support\Form::selectSearchable("Services", $name, $options, $attributes, $selected);
            ?>';
        });

        */
		
		
		
    }
    

    /**
     * Boot up the fields for printing
     */
    protected function bootPrintingFields()
    {
        Blade::directive("printradio", function ($expression) {
            return '
                <?php $params = [' . $expression . '] ?>
                <label>
                    <input type="radio" <?php echo ($params[0] === False || $params[0] === 0) ? "checked" : ""; ?>>
                    No
                </label>
                <label>
                    <input type="radio" <?php echo $params[0] ? "checked" : ""; ?>>
                    Yes
                </label>
                <span class="printing-span"><?php echo $params[1]; ?></span>';
        });

        Blade::directive("printcheckbox", function ($expression) {
            return '
                <?php $params = [' . $expression . '] ?>
                <label>
                    <input type="checkbox" <?php echo $params[0] ? "checked" : ""; ?>>
                    <?php echo $params[1]; ?>
                </label>';
        });        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
