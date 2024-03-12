<?php

namespace RCare\Org\OrgPackages\Activity\src\Http\Controllers; 
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\Activity\src\Http\Requests\ActivityAddRequest;
use RCare\Org\OrgPackages\Activity\src\Http\Requests\ActivityEditRequest;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\Activity\src\Models\Activity;
use RCare\Org\OrgPackages\Activity\src\Models\PracticeActivity;
// use RCare\Org\OrgPackages\Labs\src\Models\LabsParam;
use DataTables;
use Session; 
use Illuminate\Support\Facades\Log; 

class ActivityController extends Controller {

   //created and modified by ashvini 25 nov 2020 

   public function index()
   {
      $prac = \DB::table('ren_core.practices')->where('is_active',1)->orderBy('name','asc')->get();
    
      foreach($prac as $p){
      
         $titlename = str_replace( array( '\'', '"', ',' , ';', '<', '>', '(', ')', '&', ';' ), '', $p->name);
         $p->name = $titlename; 
         // dd($p);
      }

      return view('Activity::activity',compact('prac'));
   } 


   public function AddActivity(ActivityAddRequest $request) {
      //  dd($request->all()) ;                 
      $activitytype = sanitizeVariable($request->activitytype);  
      $activity     = sanitizeMultiDimensionalArray($request->activity);
      $created_by   = session()->get('userid');
      foreach($activity as $a) {
         $mainactivity = $a['activity'];
         $mainactivitydropdown = $a['activitydropdown'];
         if($mainactivitydropdown == 2 || $mainactivitydropdown == 3) {  
            $mainactivitydefaulttime = $a['defaulttime'];
            $mainpractices = $a['practices'];
            // dd($mainpractices);
            $mainpractime =  $a['newactivitypracticebasedtime']; //activityid
            $mainpractgroup = $a['dynamicdropdown'];

            foreach ($mainpractices as $key => $value){
                 
                  $checkmainpractices = array_filter($value);
                 
            }

            //  $checkmainpractices = array_filter($mainpractices[0]);

             $checkmainpractime = array_filter($mainpractime);
             $checkmainpractgroup = array_filter($mainpractgroup);


              
         } else{
            $mainactivitydefaulttime = '00:00:00'; 
         }
         
         $data = array(
            'activity_type' =>  $activitytype,
            'activity'      =>  $mainactivity,
            'timer_type'    =>  $mainactivitydropdown,
            'default_time'  =>  $mainactivitydefaulttime,
            'created_by'    =>  $created_by,
            'updated_by'    =>  $created_by,
            'status'        =>  1
         );   
         // var_dump($data);
         $insert = Activity::create($data);
         $activityid = $insert->id;
         // $activityid = 0;
         
        
         if($mainactivitydropdown == 2 || $mainactivitydropdown == 3) { 
            
            if(empty($checkmainpractices) && empty($checkmainpractime) && empty($checkmainpractgroup))
            {
                
            }
            else{  

            foreach($mainpractgroup as $practicegrpkey=>$practicegrpvalue) 
            {
               // dd($mainpractgroup);
               if($practicegrpvalue == null)
               {
                  
                        foreach($mainpractices as $p)
                        {
                           $filteredpractices = array_filter($p);
                           foreach($filteredpractices as $key=>$value)
                           {
                              // dd($key);
                              $practicegroup = \DB::table('ren_core.practices')->where('id',$key)->value('practice_group');
                              // dd($practicegroup);  
                              $newpracinsert = array(
                                 'activity_id'    => $activityid,
                                 'practice_id'    => $key,
                                 'time_required'  => $mainpractime[$practicegrpkey],
                                 'practice_group' => $practicegroup,
                                 'created_by'     => $created_by,
                                 'updated_by'     => $created_by,
                                 'status'         => 1
                              );
                             
                            $d = PracticeActivity::create($newpracinsert);
                           } 
                             
                        }
               }
               else{
         
                     // foreach($mainpractices[$practicegrpvalue] as $practices)
                     // {
                        // dd($mainpractices[$practicegrpvalue] ,$practices);
                        // if(count(array_unique($practices)) === 1)
                        if(count(array_unique($mainpractices[$practicegrpvalue])) === 1)  
                           {
                              //  dd($practices,"if");
                              // foreach($practices as $key=>$value)
                              foreach($mainpractices[$practicegrpvalue] as $key)   
                              {
                                 // $checkarray = array_unique($practices);
                                 

                                   
                                       // $practicegroup = \DB::table('ren_core.practices')->where(id,$key)->where('is_active',1)->pluck('id'); 
                                       $newpracinsert = array(
                                                      'activity_id'    => $activityid,
                                                      'practice_id'    => $key,
                                                      'time_required'  => $mainpractime[$practicegrpkey],
                                                      'practice_group' => $practicegrpvalue,
                                                      'created_by'     => $created_by,
                                                      'updated_by'     => $created_by,
                                                      'status'         => 1
                                                   );
                                                
                                                $d = PracticeActivity::create($newpracinsert); 
                                     

                                 
                                 // dd($key);    
                              }
                           }
                           else
                           {
                              // dd($practices,"else");    
                              // foreach($practices as $key=>$value) 
                              foreach($mainpractices[$practicegrpvalue] as $key=>$value) 
                              {
                              //  dd($key,$value);
                                 // $checkarray = array_unique($practices);
                                 

                                    if($value==1)
                                    {
                                       // dd($key,$value);
                                       // $practicegroup = \DB::table('ren_core.practices')->where(id,$key)->where('is_active',1)->pluck('id'); 
                                       $newpracinsert = array(
                                                      'activity_id'    => $activityid,
                                                      'practice_id'    => $key,
                                                      'time_required'  => $mainpractime[$practicegrpkey],
                                                      'practice_group' => $practicegrpvalue,
                                                      'created_by'     => $created_by,
                                                      'updated_by'     => $created_by,
                                                      'status'         => 1
                                                   );
                                                
                                                $d = PracticeActivity::create($newpracinsert); 
                                    }  

                                 
                               
                              }
                              // dd("hi");   
                           }
                       
                     // }   //foreach($mainpractices[$practicegrpvalue] as $practices)
               }
            }


         }

         }
      }
   } 

   public function getActivityListData(Request $request) {
     
      if ($request->ajax()) {
         
         $configTZ = config('app.timezone');
         $userTZ   = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
         // $data     = Activity::with('users')->select('activities.id as DT_RowId, activities.activity_type,activities.activity,activities.timer_type
         // ,activities.default_time,activities.status,activities.updated_by,activities.updated_at,activities.sequence')->get();         
         $data = \DB::table('ren_core.activities as a')
                  ->join('ren_core.users as u','a.updated_by','=','u.id')
                  ->select('a.id as DT_RowId','a.activity_type','a.activity',
                           'a.timer_type','a.default_time','a.status','a.updated_by',
                           'a.updated_at','a.sequence','u.f_name','u.l_name',   
                           \DB::raw("to_char(a.updated_at at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as aupdated_at"))   
                  ->orderby('a.id','asc')   
                  ->get();  

         // dd($data);       
       
         return Datatables::of($data)
         ->addIndexColumn()
         ->addColumn('action', function($row){ 
            // $btn ='<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editroles" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
            $btn ='<a href="javascript:void(0)" data-toggle="modal"  
            data-id="'.$row->DT_RowId.'"  data-target="#edit-activities-modal" id="edit-activity" data-original-title="Edit"
            class="editActivity" title="Edit"><i class=" editform i-Pen-4"></i></a>';
            if($row->status == 1){
               $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->DT_RowId.'" class="change_activity_status_active" id="active"><i class="i-Yess i-Yes" title="Active"></i></a>';
            } else {
               $btn = $btn.'<a href="javascript:void(0)" data-id="'.$row->DT_RowId.'" class="change_activity_status_deactive" id="deactive"><i class="i-Closee i-Close"  title="Deactive"></i></a>';
            }
            return $btn;
         })
         ->rawColumns(['action']) 
         ->make(true); 
      }
     
   }

   public function editActivity($id) {   
      $id = sanitizeVariable($id);
      $data = \DB::table('rencore.activities')->where('id',$id)->get();
      return $data;
   }

   //created and modified by ashvini 25nov 2020
   public function populateActivityData($activityId) {
      $activityId   = sanitizeVariable($activityId);
      $activitydata = (Activity::self($activityId) ? Activity::self($activityId)->population() : "");  
      $paramdata    = PracticeActivity::where('activity_id',$activityId)->where('status',1)->distinct('practice_group')->get()->toArray(); 
      foreach($paramdata as $key=>$p)  
      {
         // $p['1']='abc';
         // dd($p['practice_group']);  
         $parray = PracticeActivity::where('practice_group',$p['practice_group'])->where('activity_id',$activityId)->where('status',1)->distinct('practice_id')->pluck('practice_id')->toArray();
         $paramdata[$key]['practice_id_array'] = $parray;
           
      }
      // dd($paramdata);
      if($paramdata){ 
         $activityparamdata      = array('paramdata'=>$paramdata);
         $activitydata['static'] = array_merge($activitydata['static'], $activityparamdata);
      }
      $result['edit_activity_form'] = $activitydata;
      // dd($result);   
      return $result; 
   
   } 

   //created and modified by ashvini 25nov 2020
    

  

   public function updateActivity(ActivityEditRequest $request){ 
      // dd($request->all());                  
      $activity_id   =  sanitizeVariable($request->activity_id);
      $activity_type =  sanitizeVariable($request->activity_type);
      // $time_required = sanitizeMultiDimensionalArray($request->time_required);         
      $activity      =  sanitizeMultiDimensionalArray($request->activity);
      $timer_type    =  sanitizeMultiDimensionalArray($request->timer_type);
      $default_time  =  sanitizeMultiDimensionalArray($request->default_time);
      $editActivity  =  sanitizeMultiDimensionalArray($request->editActivity);
      // $editpracticesgrp = sanitizeMultiDimensionalArray($request->editpracticesgrp);    
      $created_by    =  session()->get('userid');
      $updated_by    =  session()->get('userid');

      $oldpracvalues = 
      PracticeActivity::where('activity_id',$activity_id)->pluck('practice_id');
      foreach($oldpracvalues as $old) {   
         $data = array('status'=>0,'updated_by'=> $updated_by);
         PracticeActivity::where('activity_id',$activity_id)->where('practice_id',$old)
         ->update($data);   
      }   

      if($timer_type == 2 || $timer_type == 3){
         foreach($editActivity as $key=>$value){
            // dd($)
            $checkpracticegrp = array_key_exists("appendpracticeGroup",$value);
            $checkappendtime_required = array_key_exists("appendtime_required",$value);
            if($checkpracticegrp)
            {
               $practiceGroup = $value['appendpracticeGroup'];
            }
            else{
               $practiceGroup = $value['practiceGroup'];  
            }

            if($checkappendtime_required){
               $time_required    = $value['appendtime_required'];
            }
            else{
               $time_required    = $value['time_required'];  
            }
            
           
            
            $check = array_key_exists("practicesnew_$key",$value);
            if($check)
            {
               $a = "practicesnew_$key";
            }
            else{
               $a = "editappendpracticesnew_$key";
            }
            // dd($check);
            foreach($value[$a] as $prackey=>$pracvalue){
               // dd($prackey,$pracvalue);
               if($prackey=='practices'){
                  //  dd($pracvalue);  
                    foreach($pracvalue as $pvalue)
                    {
                     //   dd($pvalue); 
                       $pfilteredarray = array_filter($pvalue);
                     //   dd($pvalue);
                       if(count($pfilteredarray)==0){
                          //insert all bcz he has selected practicegroup but his practices are unchecked it means he want all practices from dat group
                          foreach($pvalue as $fkey=>$fvalue){
                           //   dd($fkey,$value);
                             $pcheck = PracticeActivity::where('activity_id',$activity_id)->where('practice_id',$fkey)->value('id');
                             $newarrayName = array(
                                'activity_id'    => $activity_id,
                                'practice_id'    => $fkey,
                                'time_required'  => $time_required, 
                                'practice_group' => $practiceGroup,
                                'created_by'     => $created_by,
                                'updated_by'     => $created_by,
                                'status'         => 1
                             );
                              echo "test"; 
                             if(!is_null($pcheck)){
                                PracticeActivity::where('activity_id',$activity_id)
                                ->where('practice_id',$fkey)
                                ->update($newarrayName); 
                             } else  {
                                PracticeActivity::create($newarrayName); 
                             }
                          }
                       } 
                       else{
                        //   dd($pfilteredarray);  
                             foreach($pfilteredarray as $pfkey=>$pfvalue){
                                //insert only whse value 1
                                $pcheck = PracticeActivity::where('activity_id',$activity_id)->where('practice_id',$pfkey)->value('id');
                              //   $practicegrpid1 = \DB::table('ren_core.practices')->where('id',$pfkey)->value('practice_group');
                              //   isset($practicegrpid1) ? $practicegrpid1 : '0';
                                $newarrayName = array(
                                   'activity_id'    => $activity_id,
                                   'practice_id'    => $pfkey,
                                   'time_required'  => $time_required, 
                                   'practice_group' => $practiceGroup,
                                 //   'practice_group'   => $practicegrpid1, 
                                   'created_by'     => $created_by,
                                   'updated_by'     => $created_by,
                                   'status'         => 1
                                );
                                echo "test1"; 
                                if(!is_null($pcheck)){
                                   PracticeActivity::where('activity_id',$activity_id)
                                   ->where('practice_id',$pfkey)
                                   ->update($newarrayName); 
                                } else  {
                                   PracticeActivity::create($newarrayName); 
                                }
                             }
                       }       
                    }
                  }
                  else{
                  $filteredarray = array_filter($value[$a]);
                  if(count($filteredarray)==0){
                     echo "if";
                     if($practiceGroup=='null' || $practiceGroup == ""){
                        //ignore
                     }
                     else{
                        foreach($filteredarray as $f=>$fvalue)
                        {
                           
                             //insert all bcz he has selected practicegroup but his practices are unchecked it means he want all practices from dat group
                           $pcheck = PracticeActivity::where('activity_id',$activity_id)->where('practice_id',$f)->value('id');
                           $practicegrpid = \DB::table('ren_core.practices')->where('id',$f)->value('practice_group');
                           isset($practicegrpid) ? $practicegrpid : '0';
                           $newarrayName = array(
                              'activity_id'    => $activity_id,
                              'practice_id'    => $f,
                              'time_required'  => $time_required, 
                              'practice_group' => $practicegrpid,
                              'created_by'     => $created_by,
                              'updated_by'     => $created_by,
                              'status'         => 1
                           );
                           echo "test2";
                           if(!is_null($pcheck)){
                              PracticeActivity::where('activity_id',$activity_id)
                              ->where('practice_id',$f)
                              ->update($newarrayName); 
                           } else  {
                               PracticeActivity::create($newarrayName); 
                           }
                        }
                     }

                  }else{
                     echo "else" ;
                     // dd($filteredarray); 
                     foreach($filteredarray as $f=>$fvalue) 
                     {
                        // dd($f);
                        //insert only whose value 1;

                        $pcheck = PracticeActivity::where('activity_id',$activity_id)->where('practice_id',$f)->value('id');
                        $practicegrpid = \DB::table('ren_core.practices')->where('id',$f)->value('practice_group');
                           isset($practicegrpid) ? $practicegrpid : '0';
                           $newarrayName = array(
                              'activity_id'    => $activity_id,
                              'practice_id'    => $f,
                              'time_required'  => $time_required, 
                              'practice_group' => $practicegrpid,
                              'created_by'     => $created_by,
                              'updated_by'     => $created_by,
                              'status'         => 1
                           );
                            print_r($newarrayName)."<br>";
                           echo "test3";
                           if(!is_null($pcheck)){
                              PracticeActivity::where('activity_id',$activity_id)
                              ->where('practice_id',$f)
                              ->update($newarrayName); 
                           } else  {
                              PracticeActivity::create($newarrayName); 
                           }   
                     }
                  } 

               }












         } 

      

      }
      // dd("activity"); 
      }
      else{
         $default_time='00:00:00';
      }
     

      $act = array(
         'activity_type' =>  $activity_type, 
         'activity'      =>  $activity,
         'timer_type'    =>  $timer_type ,
         'default_time'  =>  $default_time,
         'updated_by'    =>  $created_by, 
         'status'        =>  1
      );
      Activity::where('id',$activity_id)->update($act); 



      }
   







   public function changeActivityStatus(Request $request) {
      $id     = sanitizeVariable($request->id); 
      $data   = Activity::where('id',$id)->get();
      $status = $data[0]->status;
      if($status==1){
         $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
         $update_query = Activity::where('id',$id)->orderBy('id', 'desc')->update($status);
         return view('Activity::activity');
      }else{
         $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
         $update_query = Activity::where('id',$id)->orderBy('id', 'desc')->update($status);
         return view('Activity::activity');
      } 
   }


   public function UpdateActivitySequenceInline(Request $request)
   {
      // dd($request->all());  
      $data = $request->data; 
      foreach($data as $key=>$value){
         // dd($key);
         $activity_id = $key;
         foreach($value as $v)  
         {
            // dd($v);
            $sequence = $v;
            $c = array('sequence'=> $v);
            // dd($v,$c);
            if(is_numeric($v))
            {
               // echo "numeric";
               $dataupdate = Activity::where('id',$activity_id)->update($c);  
               $d = \DB::table('ren_core.activities as a')
                     ->join('ren_core.users as u','a.updated_by','=','u.id')
                     ->select('a.id as DT_RowId','a.activity_type','a.activity','a.timer_type','a.default_time','a.status','a.updated_by','a.updated_at','a.sequence','u.f_name','u.l_name')
                     ->where('a.id',$activity_id)    
                     ->get();        

                  // $d = \DB::select(\DB::raw("select a.id as \"DT_RowId\",a.activity_type,a.activity,a.timer_type,a.default_time,a.status,a.updated_by,
                  // a.updated_at,a.sequence,u.f_name,u.l_name 
                  // FROM ren_core.activities as a join ren_core.users as u on a.updated_by=u.id where a.id =$activity_id "));  
               
               // $data1['id'] = $d[0]->DT_RowId;
               $data1['sequence'] = $d[0]->sequence;
               $data1['activity'] = $d[0]->activity;
               $data1['activity_type'] = $d[0]->activity_type;
               $data1['default_time'] = $d[0]->default_time;
               $data1['f_name'] = $d[0]->f_name;
               $data1['l_name'] = $d[0]->l_name;
               $data1['status'] = $d[0]->status;
               $data1['timer_type'] = $d[0]->timer_type;
               $data1['updated_at'] = $d[0]->updated_at;
               $data1['DT_RowId'] = $d[0]->DT_RowId;
               $finaldata['data'][0]= $data1;
               // dd($finaldata);   
               return $finaldata;  
            }
            else{
               $finaldata= "not numeric";

               return $finaldata;    
            }   
         }
      }    

   }
} 