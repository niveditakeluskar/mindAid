<?php
namespace RCare\Patients\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use Notifiable;

class PatientBilling extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='patients.patient_billing';
    protected $population_include = [
        "id"
    ];
        protected $dates = [
        'billing_date',
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'id',
        'patient_id',
        'module_id',
        'component_id',
        'billing_date',
        'status',        
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
}

