<?php

namespace RCare\Org\OrgPackages\Users\src\Models;
use Illuminate\Database\Eloquent\Model;

class UserPractices extends Model
{
    //
    protected $table ='ren_core.user_practices';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'id', 
        'user_id', 
        'practice_id' 
    ];

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