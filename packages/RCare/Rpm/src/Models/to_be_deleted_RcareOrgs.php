<?php

namespace RCare\Rpm\Models;
use Illuminate\Database\Eloquent\Model;

class RcareOrgs extends Model
{
    //
    protected $table ='rcare_admin.rcare_orgs';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
     protected $fillable = [
        'id',
        'name',
        'logo_img',
        'uid', 
        'add1', 
        'add2', 
        'city', 
        'state',
        'zip', 
        'phone',
        'email', 
        'category', 
        'contact_person', 
        'contact_person_phone', 
        'contact_person_email',
        'category', 
        'schema_prefix'
    ];

    /*   public function licensefetch()

    {

        return $this->belongsTo('App\Models\License');
    }*/

   



}
