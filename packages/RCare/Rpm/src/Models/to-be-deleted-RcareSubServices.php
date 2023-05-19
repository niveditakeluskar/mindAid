<?php

namespace RCare\Rpm\Models;
use Illuminate\Database\Eloquent\Model;

class RcareSubServices extends Model
{
    //
     protected $table ='ren_core.module_components';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
        protected $fillable = [
            'id', 'module_id', 'components', 'status'

    ];

    public function services(){
        return $this->belongsTo('RCare\Rpm\Models\RcareServices','module_id');
    }
}
