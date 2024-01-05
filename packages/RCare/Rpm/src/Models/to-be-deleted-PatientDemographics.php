<?php

namespace RCare\Rpm\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;

class PatientDemographics extends Model
{
    //
    use DashboardFetchable, ModelMapper;
    protected $table ='patients.patient_demographics';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $population_include = [
        "id"
    ];

    protected $fillable = [
        'patient_id','gender','marital_status','education','ethnicity','height','weight','occupation', 'employer', 'occupation_description',
        'other_contact_name', 'other_contact_relationship', 'other_contact_phone_number', 'other_contact_email', 'military_status'
    ];
}