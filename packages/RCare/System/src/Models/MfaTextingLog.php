<?php

namespace RCare\System\Models; 
use RCare\RCareAdmin\AdminPackages\Users\src\Models\User;
use RCare\Org\OrgPackages\Users\src\Models\Users;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;

class MfaTextingLog extends Model 
{
    protected $table = 'ren_core.mfa_texting_log';   
    protected $dates = [
        'created_at',  
        'updated_at'
    ];

    public $timestamps = true; 

    protected $fillable = [
        'type',
        'user_id',
        'sent_type',
        'content',
        'sent_to',
        'created_by',
        'updated_by',
        'message_id',
        'status',
        'status_update'
    ]; 
    
    public function users()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users', 'updated_by', 'id');
    }
    
    public static function self($id)
    {   
        return self::where('id', $id)->first();
    }
}
