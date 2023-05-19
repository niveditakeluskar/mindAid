<?php

namespace RCare\Patients\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
class PatientActiveDeactiveHistory extends Model
{
use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='patients.patient_active_deactive_history';

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
	protected $primaryKey = 'id';
	
	/*public $keyType = 'string';
	
	protected $casts = [
        'id' => 'string',
    ];
	*/
   

    protected $fillable = [
		'id',
        'patient_id',
        'from_date',
        'to_date',
        // 'permanent',
        'module_id',
        'comments',
        'activation_status',
        'created_by',
        'updated_by'      

    ];
	

	

}