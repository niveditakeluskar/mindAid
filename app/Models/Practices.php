<?php

namespace RCare\Org\OrgPackages\Practices\src\Models;
use Illuminate\Database\Eloquent\Model;

class Practices extends Model
{
    //
     protected $table ='ren_core.practices';


    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
	 
    protected $fillable = [
        'id', 
        'name', 
        'number', 
        'is_active'

    ];

    public static function activePractices()
    {
        return Practices::all()->where("is_active", 1);
    }

    /**
     * Get the users that are assigned to this practice
     *
     * @return array
     */
    public function users()
    {
        return $this->belongsToMany("RCare\Org\OrgPackages\Users\src\Models\Users");
    }
}
  