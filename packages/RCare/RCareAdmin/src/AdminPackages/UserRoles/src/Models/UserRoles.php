<?php

namespace RCare\RCareAdmin\AdminPackages\UserRoles\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;

class UserRoles extends GeneratedFillableModel
{
    use DashboardFetchable, ModelMapper;

    protected $table ='rcare_admin.rcare_roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'role_name',
        'status'
    ];

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