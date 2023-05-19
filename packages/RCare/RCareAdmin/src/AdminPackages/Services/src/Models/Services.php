<?php

namespace RCare\RCareAdmin\AdminPackages\Services\src\Models;

use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    protected $table ='rcare_admin.rcare_service_master';

    protected $fillable = [
        'service_name',
        'created_by',
        'updated_by',
    ];
    // public function creatercareUsers()
    // {
        
    //     $services = Creatercareuser::create(request(['service_name']));
        
    // }
    public function service_name()
    {
        // $query=\DB::table('rcare_admin.rcare_service_master')->get();
        // dd($query);

         return "{$this->service_name}";
 
    }
}
