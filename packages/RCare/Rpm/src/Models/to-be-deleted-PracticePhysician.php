<?php

namespace  RCare\Rpm\Models;
use Illuminate\Database\Eloquent\Model;

class PracticePhysician extends Model
{
    //
     protected $table ='ren_core.practice_physicians';

    /**
     * The practice this employee belongs to
     *
     * @return App\Models\Practice
     */
    public function practice()
    {
        return $this->belongsTo("RCare\Org\OrgPackages\Practices\src\Models\Practice");
    }
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
	 
    protected $fillable = [
        'id', 
        'practice_id',
        'name' 
    ];

}
  