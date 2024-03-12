<?php

namespace RCare\Rpm\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    //
    protected $table ='patients.patients';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [
        'employee_id',
        'practice_id',
        'practice_physician_id',
        'fname',
        'mname',
        'lname',
        'gender',
        'dob',
        'addr1',
        'addr2',
        'city',
        'state',
        'zip',
        'ethnicity1',
        'ethnicity2',
        'phone_primary',
        'phone_secondary',
        'email',
        'other_contact_name',
        'other_contact_relationship',
        'other_contact_phone',
        'other_contact_email',
        'preferred_contact',
        'emr',
        'date',
        "insurance_primary",
        "insurance_primary_idnum",
        "insurance_secondary",
        "insurance_secondary_idnum",
        "marital_status",
        "education",
        "occupation_status",
        "occupation_description",
        "military",
        'poa',
        'poa_name',
        'poa_relationship',
        'poa_phone',
        'poa_email',
        'enroll_awv',
        'enroll_iccm',
        'enroll_tcm',
        'enroll_other'
    ];

    /**
     * Fetch the latest form from a given patient
     */
    public static function latest($patientId)
    {
        return self::where("id", $patientId)->orderBy("id", "desc")->first();
    }
}