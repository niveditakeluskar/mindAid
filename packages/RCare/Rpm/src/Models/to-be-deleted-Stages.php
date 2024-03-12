<?php

namespace RCare\Rpm\Models;
use Illuminate\Database\Eloquent\Model;

class Stages extends Model
{
    //
    protected $table ='rpm.stage';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'submodule_id', 
        'description'
    ];
}