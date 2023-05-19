<?php
namespace RCare\Org\OrgPackages\Physicians\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class Physicians extends Model
{
    //
    use  DatesTimezoneConversion;
     protected $table ='ren_core.physicians';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $population_include = [
        "id"
    ];
    
    protected $dates = [
        'created_at',
        'updated_at'
    ];
	 
        protected $fillable = [
        'id', 'name','physicians_uid','email', 'phone', 'is_active'

    ];

public static function activeUsers()
    {
        return physicians::all()->where("is_active", 1);
    }
}
  