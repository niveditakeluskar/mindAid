<?php

namespace RCare\Rpm\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;

class Patients extends Model
{
    //
    use DashboardFetchable, ModelMapper;
    protected $table ='patients.patient';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $population_include = [
        "uid"
    ];

    protected $fillable = [
       'fname','mname','lname','email','org_id','partner_id','home_number', 'mob', 'dob', 'review', 'created_by','updated_by',
       'contact_preference_calling','contact_preference_sms', 'contact_preference_email', 'contact_preference_letter', 'age'
    ];
}