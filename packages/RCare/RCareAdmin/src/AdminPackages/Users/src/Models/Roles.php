<?php

namespace RCare\RCareAdmin\AdminPackages\Users\src\Models;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    //
     protected $table ='rcare_admin.rcare_roles';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [
        'id', 'role_name', 'status'

    ];

    public function user()
    {
        return $this->belongsToMany("App\Models\User");
    }


}
