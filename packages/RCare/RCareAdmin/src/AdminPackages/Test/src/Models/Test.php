<?php

 namespace RCare\RCareAdmin\AdminPackages\Test\src\Models;
 
//  use Illuminate\Notifications\Notifiable;
//  use Illuminate\Foundation\Auth\User as Authenticatable;
//  use Illuminate\Support\Facades\Hash;
 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
 use Illuminate\Database\Eloquent\Model;
 //use Illuminate\Database\Eloquent\SoftDeletes;

 
 class Test extends GeneratedFillableModel
 
 {
	  // use SoftDeletes;
	 use Notifiable;
     use DashboardFetchable, ModelMapper;

	   protected $table ='rcare_admin.rcare_users';

	   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
      protected $population_include = [
          "id"
      ];
      
      protected $fillable = [
         'id',
         'f_name', 
         'l_name', 
         'email', 
         'status', 
         'remember_me', 
         'password'
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
     * Generate some fillable attributes for the model
     */
    public function generateFillables()
    {
        return $this->fillable;
    }
    
    public function populationRelations()
    {
        return [];
    }
}