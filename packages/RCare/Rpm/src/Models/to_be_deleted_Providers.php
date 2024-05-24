<?php

namespace RCare\Rpm\Models;

use Illuminate\Database\Eloquent\Model;

class Providers extends Model
{
    //
    protected $table ='ren_core.providers';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
     protected $fillable = [
       'id','provider_type_id','name','practice_id','phone_no','address','email_id', 'created_by','updated_by'
    ];

    public function practice()
    {
        return $this->belongsTo("RCare\Org\OrgPackages\Practices\src\Models\Practice");
    }
}