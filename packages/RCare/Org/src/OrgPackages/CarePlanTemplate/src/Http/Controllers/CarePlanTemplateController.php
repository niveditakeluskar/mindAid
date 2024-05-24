<?php

namespace RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\CarePlanTemplate\src\Models\CarePlanTemplate;
use RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Requests\CarePlanTemplateRequest;
use RCare\Org\OrgPackages\Diagnosis\src\Models\Diagnosis;
use RCare\Org\OrgPackages\Diagnosis\src\Models\DiagnosisCode;
use RCare\Org\OrgPackages\Diagnosis\src\Http\Requests\DiagnosisAddRequest;

use RCare\Org\OrgPackages\Medication\src\Models\Medication;
use DataTables;
use Session; 
use File,DB;
use PDF;

class CarePlanTemplateController extends Controller {
	
    public function addCarePlanTemplate()  
    {
       $condition = 
       // Diagnosis::with('CareplanTemplate')->whereNotIn('condition','CareplanTemplate.condition')->get();  
       DB::select(("select * from ren_core.diagnosis where condition not in
                                  (select distinct condition from ren_core.care_plan_templates) order by condition asc")); 
         $code  = DiagnosisCode::where('diagnosis_id', $condition[0]->id)->get();
      return view('CarePlanTemplate::care-plan-template-add',['condition'=>$condition],['code'=>$code]);
    }

    public function populateCarePlanTemplateData($patientId){
    $patientId = sanitizeVariable($patientId);
    $diagnosis_data = (CarePlanTemplate::self($patientId) ? CarePlanTemplate::self($patientId)->population() : "");
   
    $newtasks = '';
    $newgoals = '';
    $newsymptoms = '';

    foreach($diagnosis_data as $d){
      
      if(array_key_exists("tasks",$d)){
        $newtasks = htmlspecialchars_decode($d['tasks']);
      }
      if(array_key_exists("goals",$d)){
        $newgoals = htmlspecialchars_decode($d['goals']);
      }
      if(array_key_exists("symptoms",$d)){
        $newsymptoms = htmlspecialchars_decode($d['symptoms']);
      }
     
    }
    $diagnosis_data['static']['tasks'] = $newtasks;
    $diagnosis_data['static']['goals'] = $newgoals;
    $diagnosis_data['static']['symptoms'] = $newsymptoms;
    
    $result['main_edit_care_plan_template_form'] = $diagnosis_data;  
    return $result;
    }

    public function editCarePlanTemplate($id)  
    {  $id =sanitizeVariable($id);
      $patient = CarePlanTemplate::where('id',$id)->get();
        $condition= 
        // CarePlanTemplate::with('Diagnosis')
        //           ->whereNotIn('Diagnosis.condition',CarePlanTemplate::pluck('condition')->unique()
        //           )->get(); 
     DB::select(("select * from ren_core.diagnosis where condition not in
     (select distinct condition from ren_core.care_plan_templates) order by condition asc")); 

        $code  = DiagnosisCode::where('diagnosis_id', $condition[0]->id)->get();
       return view('CarePlanTemplate::care-plan-template-edit',['patient'=>$patient],['condition'=>$condition],['code'=>$code]);
      
    }

     public function getcode($id)  
    {    $id =sanitizeVariable($id);
         //$code  = DiagnosisCode::where('diagnosis_id', $id)->whereNotNull('code')->get();  //changes for upper case 13 Feb 2023 ashwini Mali
         $code  = DiagnosisCode::select(DiagnosisCode::raw('upper(code) as code' , 'diagnosis_id','status','qualified','valid_invalid'))->where('diagnosis_id', $id)->where('status',1)->whereNotNull('code')->get();            
          $result= json_encode($code);
          return $result;
             
    }

    public function PopulateDiagnosis($id)
    {
      $id =sanitizeVariable($id);
     $diagnosis_data = (CarePlanTemplate::self($id) ? CarePlanTemplate::self($id)->population() : "");
    $result['edit_diagnosis_careplan_form'] = $diagnosis_data;
    return $result;

    }
  
  public function UpdateDiagnosis(DiagnosisAddRequest $request)
  {
      // dd($request->all());   
      
      $id        = sanitizeVariable($request->id);
      $code      = sanitizeVariable($request->code);     
      $oldcode   = sanitizeVariable($request->codename);     
      $condition = sanitizeVariable($request->condition);
      $oldcondition=sanitizeVariable($request->conditionname);
      $diagnosis_id=sanitizeVariable($request->diagnosis_id);
     
      $data      = array(      
        'code'      => $code,  
        'condition' => $condition           
        ); 

      $condition=array(
          'condition' => $condition  
        );

     
  
        $diagnosisExists=Diagnosis::where('id','!=',$diagnosis_id)->where('condition',$condition)->exists();
        if($diagnosisExists==true)
        {
            return "yes";
       }
       else
       {
           $updatecondition=Diagnosis::where('id',$diagnosis_id)->update($condition);
       
        // $deletecode=DiagnosisCode::where('diagnosis_id',$diagnosis_id)->delete();
           if(!empty($code))
             {
          $code=array(
          'diagnosis_id'=>$diagnosis_id,
          'code'      => $code
           );
           $updatecode=DiagnosisCode::where('diagnosis_id',$diagnosis_id)->where('code',$oldcode)->update($code);
          }
          $updateCarePlanDiagnosis=CarePlanTemplate::where('id',$id)->update($data);
       }
  }

  public function saveCarePlanTemplate(CarePlanTemplateRequest $request){ 
         
      $id            = sanitizeVariable($request->id);   
      $goals         = sanitizeVariable($request->goals);    
      $symptoms      = sanitizeVariable($request->symptoms);
      $tasks         = sanitizeVariable($request->tasks);
      $medications   = sanitizeVariable($request->medications);
      //$support       = sanitizeVariable($request->support);
      $allergies     = sanitizeVariable($request->allergies);
      $newmedication  = sanitizeVariable($request->newmedications);
      $health_data    = sanitizeVariable($request->health_data);
      $labs           = sanitizeVariable($request->labs);
      $vitals         = sanitizeVariable($request->vitals);
      //  dd($medications);  

     if(!empty($newmedication))
     {     
          
            foreach($newmedication as $key => $values){                 
                    
                        $meddata=array(
                             'description'=>$values,
                             'drug_reaction'=>'',
                             'created_by'=>session()->get('userid')
                        );                       
                         $newmedication  = Medication::create($meddata);
                         $medications[]= $newmedication->id;                   
        }
     }
    // array_push($medicationfilter,$newmedid);
    
     
     if($medications == null){         
      $medicationfilter = null;
     }else{
      $newmed = \array_diff($medications, ["other"]);     
      $medicationfilter = array_values(array_filter($newmed));
      $medicationfilter = json_encode($medicationfilter);
     }
     
//dd($medicationfilter);
      $data    = array(
            'goals'         => json_encode($goals),
            'symptoms'      => json_encode($symptoms),
            'tasks'         => json_encode($tasks),
            'medications'   => $medicationfilter,
            //'support'     => $support,
            'allergies'     => json_encode($allergies),
            'health_data'  => json_encode($health_data),
            'labs'         => json_encode($labs),
            'vitals'       => json_encode($vitals),
        ); 
      $dataExist  = CarePlanTemplate::where('id', $id)->exists();

        if ($dataExist == true) {
            $data['updated_by']= session()->get('userid');

            $update_query = CarePlanTemplate::where('id',$id)->orderBy('id', 'desc')->first()->update($data);
           
              
        } else {
              $code      = sanitizeVariable($request->drop_code);
              $diagnosisid = sanitizeVariable($request->drop_condition);
              
              $codition  = Diagnosis::where('id', $diagnosisid)->get();
            $conditionname="";
            if(!empty($codition)){
                $conditionname =$codition[0]->condition;   
            }    
            else{
             $conditionname ='';   

            }     
            $data['status']=1;
            $data['code']     = $code;
            $data['diagnosis_id'] = $diagnosisid;
            $data['condition']=$conditionname;
            $data['created_by']= session()->get('userid');
            $data['updated_by']= session()->get('userid');
            $insert_query = CarePlanTemplate::create($data);
            $datacode = array('diagnosis_id' => sanitizeVariable($request->drop_condition),
                              'code' => sanitizeVariable($request->drop_code), 
                              'status' => 1
                              );
          $existcondition=DiagnosisCode::where('diagnosis_id',sanitizeVariable($request->drop_condition))->exists();
          if($existcondition==true){
              $existcondition = DiagnosisCode::where('diagnosis_id',sanitizeVariable($request->drop_condition))->where('code',sanitizeVariable($request->drop_code))->exists();
                if($existcondition==true){
                    $datacode['updated_by'] = session()->get('userid'); 
                    DiagnosisCode::where('diagnosis_id',sanitizeVariable($request->drop_condition))->where('code',sanitizeVariable($request->drop_code))->update($datacode);
                }else{
                  $datacode['created_by'] = session()->get('userid'); 
                  $datacode['updated_by'] = session()->get('userid'); 
                   DiagnosisCode::create($datacode);
                }
          }else{
                 $datacode['created_by']=session()->get('userid');
                 $datacode['updated_by'] = session()->get('userid');
              $insert_code = DiagnosisCode::create($datacode);
          }
        }
        echo "save"; 
    }   
    
    public function CarePlanTemplateList(Request $request) {
        if ($request->ajax()) { //changes for upper case ashwini mali 13 Feb
            $data = CarePlanTemplate::select(CarePlanTemplate::raw('id,upper(code) as code,condition,symptoms,goals,tasks,status,diagnosis_id,medications,support,allergies,numbers_tracking,
          health_data,labs,vitals,updated_by,updated_at'))->with('users')->latest()->get();
         // $data = CarePlanTemplate::with('users')->latest()->get();
			//dd($data);
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                // $btn ='<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editroles" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
                $btn ='<a href="care-plan-template-edit/'.$row->id.'" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                if($row->status == 1){
                  $btn = $btn. '<a href="javascript:void(0)"  data-id="'.$row->id.'" class="change_status_care_active" id="active"><i class="i-Yess i-Yes" title="Active"></i></a>';
                  }
                  else
                  {
                    $btn = $btn.'<a href="javascript:void(0)"  data-id="'.$row->id.'" class="change_status_care_deactive" id="deactive"><i class="i-Closee i-Close"  title="Deactive"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('CarePlanTemplate::care-plan-template-list');
    }

    public function deleteCareplan(Request $request)
    {
        $id = sanitizeVariable($request->id);
        $data = CarePlanTemplate::where('id',$id)->get();
        $status =$data[0]->status;
        if($status==1){
          $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
          $update_query = CarePlanTemplate::where('id',$id)->orderBy('id', 'desc')->update($status);
        }else{
          $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
          $update_query = CarePlanTemplate::where('id',$id)->orderBy('id', 'desc')->update($status);
        }
        
    }
  
    

    public function CarePlanTemplatePdf($id)
    { 
      $id =sanitizeVariable($id);
       $diagnosis_data = CarePlanTemplate::self($id);
        $medications=array();
      $med_id=json_decode($diagnosis_data->medications);
      if(!empty($med_id))
      {
         $med = implode(', ', $med_id);         
      
       $medications=DB::select(("select description from ren_core.medication where id in (".$med.")")); 
     }
       PDF::setOptions(['dpi' => 96, 'defaultFont' => 'serif','fontHeightRatio' => 1.3]);
        $pdf = PDF::loadView('CarePlanTemplate::care-plan-template-pdf',compact('diagnosis_data','medications'));
       // return view('CarePlanTemplate::care-plan-template-pdf',compact('diagnosis_data','medications'));
        return $pdf->stream('CarePlanTemplate.pdf', array('Attachment'=>0));
    }

    public function checkCodeAvailabel(Request $request)
    {
        $codeval=sanitizeVariable($request->code);
        $coditionid=sanitizeVariable($request->diagnosis_id);

       $code=DiagnosisCode::where('diagnosis_id',$coditionid)->where('code',$codeval)->exists();
       if($code==true)
       {
         return '1';
       }
       else
       {
         return '0';
       }

      

    }
}