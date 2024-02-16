<?php

namespace RCare\RCareAdmin\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

//use Illuminate\Database\Eloquent\SoftDeletes;


class AuthUser extends Authenticatable
{
    // use SoftDeletes;
    use Notifiable;


	protected $table ='rcare_admin.rcare_users';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $population_include = [
        "id"
    ];

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
	 
    protected $fillable = [
        'id',
        'f_name',
        'l_name',
        'email',
        'status',
        'remember_me',
        'password',
        'profile_img'
    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
        'password',
        'remember_token'
    ];

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
    * Fetch all active Users
    */
    public static function activeUsers()
    {
        return User::all()->where("status", 1);
    }
}