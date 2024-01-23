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
use Tymon\JWTAuth\Contracts\JWTSubject;

class Partner extends Authenticatable implements JWTSubject
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion, HasFactory, Notifiable;

    protected $table = 'ren_core.partners';

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
        'updated_at',
        'email_verified_at'
    ];

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'add1 ',
        'email',
        'password',
        'phone',
        'status'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function tokenExists($token)
    {
        // Check if at least one token exists for this partner
<<<<<<< HEAD
        return self::where('token', $token)->exists();
=======
        return self::where('token', md5($token))->exists();
>>>>>>> 82f4c8ec4b3d441abee86b8b032d6165cd3a92ee
    }
}
