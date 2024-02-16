<?php

namespace RCare\RCareAdmin\AdminPackages\Menu\src\Models;
use Illuminate\Database\Eloquent\Model;


class Menus extends Model
{
    protected $table ='rcare_admin.rcare_menu_master';

    protected $fillable = [
        'menu',
        'menu_url',
        'service_id',
        'icon',
        'parent',
        'status',
        'sequence',
        'operation'
    ];

    public function services()
    {
        return $this->belongsTo('RCare\RCareAdmin\AdminPackages\Services\src\Models\Services', 'service_id');
    }
    public function mnu()
    {
        return $this->belongsTo('RCare\RCareAdmin\AdminPackages\Menu\src\Models\Menus', 'parent')->withDefault(['menu'=>'none']);
    }
    
}
