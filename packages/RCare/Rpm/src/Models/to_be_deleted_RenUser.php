<?php

 namespace RCare\Rpm\Models;
 
 use Illuminate\Notifications\Notifiable;
 use Illuminate\Foundation\Auth\User as Authenticatable;
 use Illuminate\Support\Facades\Hash;
 //use Illuminate\Database\Eloquent\SoftDeletes;

 
 class RenUser extends Authenticatable
 
 {
    protected $guard = 'renCore_users';
	  // use SoftDeletes;  
	   use Notifiable;


	 // protected $table ='rcare_admin.rcare_users';
       protected $table ='ren_core.users';
	   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
        protected $fillable = [
        'id', 'f_name', 'l_name', 'email', 'status', 'remember_me', 'activation_date', 'password'
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
}