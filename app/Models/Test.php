<?php

 namespace App\Models;
 
 use Illuminate\Notifications\Notifiable;
 use Illuminate\Foundation\Auth\User as Authenticatable;
 use Illuminate\Support\Facades\Hash;
 use Illuminate\Database\Eloquent\Model;
 use RCare\System\Support\ModelMapper;

 //use Illuminate\Database\Eloquent\SoftDeletes;

 
 class Test extends Authenticatable
 
 {
	  // use SoftDeletes;
	use Notifiable;


	protected $table ='rcare_admin.rcare_users';

	   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [
        'id', 'f_name', 'l_name', 'email', 'status', 'remember_me', 'password'
    ];

     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password','remember_token' 
   ];


    /**
     * Add a mutator to ensure hashed passwords
     */
    public function setPasswordAttribute(string $password)
    {
        $this->attributes['password'] = Hash::make($password);
    }
 }
