<?php

 namespace RCare\RCareAdmin\AdminPackages\Users\src\Models;
 
 use Illuminate\Notifications\Notifiable;
 use Illuminate\Foundation\Auth\User as Authenticatable;
 use Illuminate\Support\Facades\Hash;
 //use Illuminate\Database\Eloquent\SoftDeletes;

 
 class User extends Authenticatable
 
 {
      // use SoftDeletes;
       use Notifiable;

     protected $guard = 'rcare_users';

     protected $table ='rcare_admin.rcare_users';

       /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     
        protected $fillable = [
        'id', 'f_name', 'l_name', 'email', 'status', 'remember_me', 'password', 'role', 'profile_img','token'
    ];

     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

   protected $hidden = ['password', 'remember_token'];

    /**
     * Boot the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->remember_token = str_random(30);
        });
    }

    /**
     * Set the password attribute.
     *
     * @param string $password
     */
    // public function setPasswordAttribute($password)
    // {
    //     $this->attributes['password'] = Hash::make($password);
    // }

    /**
     * Confirm the user.
     *
     * @return void
     */
    // public function confirmEmail()
    // {
    //     $this->verified = true;
    //     $this->token = null;
    //     $this->save();
    // }


     public function roles()
    {
        return $this->belongsTo('RCare\RCareAdmin\AdminPackages\Users\src\Models\Roles','role');
    }
}