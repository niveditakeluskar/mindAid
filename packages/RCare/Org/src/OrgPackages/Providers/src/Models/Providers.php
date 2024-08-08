<?php

namespace RCare\Org\OrgPackages\Providers\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use Carbon\Carbon;
use RCare\System\Traits\DatesTimezoneConversion;


class Providers extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    // DatesTimezoneConversion;

    protected $table ='ren_core.providers';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $population_include = [
        "id"
    ];
    
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    // public $timestamps = true;
    // protected $dates = [
    //     'created_at',
    //     'updated_at'
    // ];
    protected $fillable = [
        'id',
        'licenese_number',
        'qualification',
        'physician_id',
        'created_by',
        'updated_by',
        'is_active',
        'created_at',
        'updated_at',
        'speciality_id'
    ];
    public static function self($id)
    {   $id = sanitizeVariable($id);
        return self::where('id', $id)->orderBy('created_at', 'desc')->first();
    }

    public static function practices($type)
    {   $type = sanitizeVariable($type);
        return Providers::where('provider_type_id',$type)->where("status",1);
    }

     public static function activeProviders()
    { 
      
        // $providers = \DB::table('ren_core.providers')->where("is_active", 1)->where("name","!=","null")->orderBy('name','asc')->get();
         
        // foreach($providers as $p)
        // {
        //     $id = $p->id;
        //    /* $pro= \DB::select(\DB::raw("select  count(distinct p.id) from patients.patient p 
        //     left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
        //     from patients.patient_providers pp1  
        //     inner join (select patient_id, max(id) as max_pat_practice 
        //     from patients.patient_providers  where provider_type_id = 1  and is_active =1  
        //     group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice) pp                 
        //     on p.id = pp.patient_id
        //     left join ren_core.providers rp on rp.id = pp.provider_id where rp.id = '".$id."' ")); */

        //     $d = \DB::table('patients.patient as p')
		// 	 ->join('patients.patient_providers as pp', 'p.id', '=', 'pp.patient_id')
        //      ->where('p.status',1)             
        //      ->where('provider_type_id',1)
		// 	  ->where('provider_id',$p->id)
        //      ->where('pp.is_active',1)
        //      ->distinct('pp.patient_id')
        //      ->count('patient_id');
			 
        //     //$providerscount = $pro[0]->count; 
        //     $providerscount = $d; 
            
        //     $p->count = $providerscount;
        // }
        // return $providers;
    }

    // public static function newactiveProviders()
    // { 
      
    //     $providers = \DB::table('ren_core.providers')->where("is_active", 1)->where("practice_id","!=",0)
    //     ->where("name","!=","null")->orderBy('name','asc')->get();
    //       // return Providers::where("is_active", 1)->where("name","!=","null")->orderBy('name','asc')->get();
    //     foreach($providers as $p)
    //     {
    //         $id = $p->id;
            
			/*$pro= \DB::select(\DB::raw("select  count(distinct p.id) from patients.patient p 
            left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
            from patients.patient_providers pp1  
            inner join (select patient_id, max(id) as max_pat_practice 
            from patients.patient_providers  where provider_type_id = 1  and is_active =1  
            group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice) pp                 
            on p.id = pp.patient_id
            left join ren_core.providers rp on rp.id = pp.provider_id where rp.id = '".$id."' "));  
			*/
    //          $d = \DB::table('patients.patient as p')
	// 		 ->join('patients.patient_providers as pp', 'p.id', '=', 'pp.patient_id')
    //          ->where('p.status',1)
    //          ->where('provider_id',$p->id)
    //          ->where('provider_type_id',1)
    //          ->where('pp.is_active',1)
    //          ->distinct('pp.patient_id')
    //          ->count('patient_id');
	// 		//dd($d);
    //       //  $providerscount = $pro[0]->count;  
    //         $providerscount = $d;  
            
    //         $p->count = $providerscount;
    //     }
    //     return $providers; 
    // }


    // public static function groupedProvider() { 
    //     $query = \DB::select(\DB::raw("select p.id, p.name as name,
    //             case
    //                 when p.is_active = 1 then 'Active'
    //                 when p.is_active = 0 then 'Inactive'
    //                 else ''
    //             end AS group_by
    //             from ren_core.providers p order by group_by, p.name"));
    //     return $query;
    // }


      public static function activePracticeProvider($practiceid)
    {   $practiceid = sanitizeVariable($practiceid);
        return Providers::orderBy('name','asc')->where('practice_id',$practiceid)->where("is_active", 1)->where("name","!=","null")->get();
    }

    public static function activePCPProvider()
    {
        return Providers::where("is_active", 1)->where('provider_type_id',1)->where("name","!=","null")->orderBy('name','asc')->get();
    }

    public function  practice()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Practices\src\Models\Practices', 'practice_id');
    }
    public function provider_type()
    {
        return $this->hasMany('RCare\Org\OrgPackages\Providers\src\Models\ProviderType', 'provider_type_id');
    }

    public function subprovider_type()
    {
        return $this->hasMany('RCare\Org\OrgPackages\Providers\src\Models\ProviderSubtype', 'provider_subtype_id');
    }
    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }

    // /**
    //  * Generate some fillable attributes for the model
    //  */
    // public function generateFillables()
    // {
    //     return $this->fillable;
    // }
    
    // public function populationRelations()
    // {
    //     return [];
    // }
}