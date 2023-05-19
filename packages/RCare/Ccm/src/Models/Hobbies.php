<?php

namespace RCare\Ccm\Models;
use Illuminate\Database\Eloquent\Model;

class Hobbies extends Model
{
    //
     protected $table ='patients.hobbies';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
        protected $fillable = [
        'id', 'uid', 'description', 'location', 'frequency', 'with_whom', 'notes', 'created_at', 'updated_at', 'created_by', 'updated_by', 'status'

    ];

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
