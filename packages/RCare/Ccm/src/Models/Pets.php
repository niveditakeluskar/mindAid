<?php

namespace RCare\Ccm\Models;
use Illuminate\Database\Eloquent\Model;

class Pets extends Model
{
    //
     protected $table ='patients.patient_pets';
 

      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
        protected $fillable = [
        'id', 'uid', 'description', 'notes', 'created_at', 'updated_at', 'created_by', 'updated_by', 'status'

    ];
    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }

    // public function template(){
    //     return $this->belongsTo('RCare\Rpm\Models\Template','template_type_id');
    // }
    // public function service(){
    //     return $this->belongsTo('RCare\Rpm\Models\RcareServices','module_id');
    // }
    // public function subservice(){
    //     return $this->belongsTo('RCare\Rpm\Models\RcareSubServices','component_id');
    // }
}
