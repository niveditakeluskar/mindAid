<?php

namespace RCare\API\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Partner extends Authenticatable 
{
use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='rcare_api.partner';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
 protected $population_include = [
        "user_id"
    ];

  protected $dates = [
        'created_at',
        'updated_at'
    ];

protected $primaryKey = 'user_id';
  protected $keyType = 'string';
public $incrementing = false;

    protected $fillable = [
	'partner_name',
    'location ',
    'email',
    'phone',
    'user_id',
    'User_key'
    ];
	
	
}
