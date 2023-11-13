<?php

namespace RCare\RCareAdmin\AdminPackages\Organization\src\Models;

use Illuminate\Database\Eloquent\Model;

class OrgCategory extends Model
{
    //

    protected $table ='rcare_admin.rcare_org_category';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
        protected $fillable = [
        'id', 'category'

    ];



}
