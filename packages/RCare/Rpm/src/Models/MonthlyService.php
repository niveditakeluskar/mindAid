<?php

namespace RCare\Rpm\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class MonthlyService extends Model
{
    //
    use DatesTimezoneConversion;
     protected $table ='rpm.monthly_services';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $population_include = [
      "id"
  ];
  
  protected $dates = [
      'created_at',
      'updated_at'
  ];
        protected $fillable = [
       
        'id',
        'practice_id', 
        'uid', 
        'patient_id', 
        'dob', 
        'phone_no',
        'phone_primary',
        'last_encounter',
        'patient_data_status',
        'not_recorded_action',
        'not_recorded_action_template',
        'voice_mail',
        'out_of_guideline_patient_condition',
        'out_of_guideline_patient_condition_template',
        'withinguideline_msg_template',
        'withinguideline_left_msg_in_emr',
        'created_by', 
        'updated_by', 
        'created_at', 
        'updated_at'   

    ];


  }





 