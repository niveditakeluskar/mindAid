<?php

 namespace RCare\RCareAdmin\AdminPackages\Role\src\Models;
 
 use Illuminate\Notifications\Notifiable;
 use Illuminate\Foundation\Auth\User as Authenticatable;
 use Illuminate\Support\Facades\Hash;
 //use Illuminate\Database\Eloquent\SoftDeletes;

 
 class Roles extends Authenticatable
 {
    //
    protected $table ='rcare_admin.rcare_roles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'id', 
        'role_name', 
        'status'
    ];

    public function user()
    {
        return $this->belongsToMany("RCare\RCareAdmin\AdminPackages\Users\src\Models\User");
    }

    /**
     * Fetch all active Roles
     */
    public static function activeRoles()
    {
        return Roles::all()->where("status", 1);
    }

    
}