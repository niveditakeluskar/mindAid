<?php

namespace RCare\Ccm\Models;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    //
     protected $table ='rcare_admin.template';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
        protected $fillable = [
        'id', 'template_type',

    ];
   /* public function mailtemplate(){s
        return $this->hasMany('App\Models\MailTemplate','template_type_id');

    }*/
}
