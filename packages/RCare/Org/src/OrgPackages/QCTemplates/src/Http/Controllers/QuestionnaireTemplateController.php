<?php

namespace RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\QCTemplates\src\Models\QuestionnaireTemplate;
use RCare\Org\OrgPackages\QCTemplates\src\Models\TemplateTypes;
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents;
use RCare\Org\OrgPackages\Stages\src\Models\Stage;
use RCare\Org\OrgPackages\StageCodes\src\Models\StageCode;
use RCare\Rpm\Models\Devices;
use RCare\Org\OrgPackages\QCTemplates\src\Http\Requests\SaveQuestionnaireTemplateRequest;
use DataTables;
use PDF;
use DB;
use Session;


class QuestionnaireTemplateController extends Controller
{ 
    public function listQuestionnaireTemplates(Request $request)
    {
        if ($request->ajax()) {
            $module_name = sanitizeVariable(\Request::segment(1));
            $check_module_name = DB::select("SELECT * FROM ren_core.modules WHERE LOWER(module) ='".strtolower($module_name)."'");
            $module_id = $check_module_name[0]->id;
            $data = QuestionnaireTemplate::where('module_id',$module_id)->where('template_type_id',5)->where('status', '!=' , 2)->with('module','components','template','users','stage','step')
            ->orderBy('updated_at','desc')->get();
            // $data = QuestionnaireTemplate::with('module','components')->where('status',1)->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){ 
                $btn = '<a href="update-questionnaire-template/'.$row->id.'" data-toggle="tooltip" data-original-title="Edit"  title="Edit"><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>';
                if($row->status == 2){
                    $btn = '<a href="view-questionnaire-template/'.$row->id.'" data-toggle="tooltip" data-original-title="View"  title="View"><i class="text-15 i-Eye" style="color: green;"></i></a>';
                } 
                // $btn = $btn . '<a href="delete-questionnaire-template/'.$row->id.'"><i class=" i-Close" style="color: #2cb8ea;"></i></a>';
                else if($row->status == 1){
                  $btn = $btn. '<a href="delete-questionnaire-template/'.$row->id.'"data-toggle="tooltip" data-original-title="Active"   data-id="'.$row->id.'" class="change_status_decision_template_active" id="active"><i class="i-Yess i-Yes" title="Active"></i></a>';
                }
                else
                {
                    $btn = $btn.'<a href="delete-questionnaire-template/'.$row->id.'" data-toggle="tooltip" data-original-title="Deactive"  data-id="'.$row->id.'" class="change_status_decision_template_deactive" id="deactive"><i class="i-Closee i-Close"  title="Deactive"></i></a>';
                }
                return $btn;  
            })
            ->rawColumns(['action'])  
            ->make(true);
        }
        return view('QCTemplates::QuestionnaireTemplates.questionnaire-template');
    }

    public function addTemplate()
    {
        $data = TemplateTypes::all();
        $module = Module::all();
        $components = ModuleComponents::all();
        $devices = Devices::all();
        return view('QCTemplates::QuestionnaireTemplates.add-questionnaire-template', compact('data','components','devices', 'module')); 
    }

    public function getTemplate($moduleid, $stepid, $type){

        $template = QuestionnaireTemplate::where('module_id', $moduleid)->where('template_type_id',$type)->where('stage_code', $stepid)->where('status', 1)->get();
        return response()->json($template); 
    }

    public function getTemplateList($module, $subModuleId, $templateId){
        $template = [];
        //$stages = Stage::all()->where("submodule_id", $id)->where("status", 1);
        $template = QuestionnaireTemplate::where('module_id',$module)->where('component_id',$subModuleId)->where('template_type_id',$templateId)->where('status', 1)->orderBy('content_title', 'asc')->get();
        return response()->json($template);
    }

    public function saveTemplate(SaveQuestionnaireTemplateRequest $request)
    {

        $months = array();
        if($request->months != null || $request->months != ''){
            foreach(sanitizeVariable($request->months) as $key => $val){
                $months[] = $key;       
            }
        }
        //check for the stage
        if(sanitizeVariable($request->has('module')) && sanitizeVariable($request->module) != "Select Module") {
            $module = sanitizeVariable($request->module);
        } else {
            $module = 0;
        }
        //check for the stage
        if(sanitizeVariable($request->has('sub_module')) && sanitizeVariable($request->sub_module) != "Select Sub Module") {
            $sub_module = sanitizeVariable($request->sub_module);
        } else {
            $sub_module = 0;
        }

        //check for the stage
        if(sanitizeVariable($request->has('stages')) && sanitizeVariable($request->stages) != "" && sanitizeVariable($request->stages) != "Select Stage" ) {
            $stages = sanitizeVariable($request->stages);
        } else {
            $stages = '0';
        }

        //check for the stage code
        if(sanitizeVariable($request->has('stage_code')) && sanitizeVariable($request->stage_code) != "" && sanitizeVariable($request->stage_code) != "Select Stage Code") {
            $stage_code = sanitizeVariable($request->stage_code);
        } else {
            $stage_code = '0';
        }
        


        //create content details array
        $questionDetails = array('question' => sanitizeMultiDimensionalArray($request->question));
        $data = array(
                        'content_title'    => sanitizeVariable($request->content_title),
                        'template_type_id' => sanitizeVariable($request->template_type),
                        'module_id'        => $module,
                        'component_id'     => $sub_module,
                        'stage_id'         => $stages,
                        'stage_code'       => $stage_code,
                        'question'         => json_encode($questionDetails),
                        // 'status'        => $status,
                        'status'           =>1,
                        'add_to_patient_status' => sanitizeVariable($request->add_to_patient_status),
                        'one_time_entry'   => sanitizeVariable($request->one_time_entry),
                        'score'   => sanitizeVariable($request->add_score),
                        'sequence' => sanitizeVariable($request->sequence),
                        'display_months' => json_encode($months)
                    );

        //check from which form submit requested
        if(!(sanitizeVariable($request->has('add'))) && !(sanitizeVariable($request->has('edit'))))  {
            return('failed');
        } else {
            if(sanitizeVariable($request->has('add'))) {
                $data['created_by']= session()->get('userid');
                $addData = QuestionnaireTemplate::create($data);
                if($addData) {
                    return('success');
                } else {
                    return('failed');
                }
                // return redirect()->route('questionnaire-template')->with('success','Template Modified successfully!');
            }

            if(sanitizeVariable($request->has('edit'))) {
            QuestionnaireTemplate::where('id',sanitizeVariable($request->temp_id))->update(['status' => 2, 'updated_by' =>session()->get('userid')]);
                $data['created_by']= session()->get('userid');
                $data['updated_by']= session()->get('userid'); 
                $editData = QuestionnaireTemplate::create($data);
                // $data['updated_by']= session()->get('userid');
                // //print_r($data);
                // $data['created_by']= session()->get('userid');
                // $editData = QuestionnaireTemplate::create($data);
                if($editData) {
                    return('success');
                }else {
                    return('failed');
                }
                // return redirect()->route('questionnaire-template')->with('success','Template Modified successfully!');
            }
        }
    }

    public function UpdateTemplate($id=0){
        $id = sanitizeVariable($id);
        $data = QuestionnaireTemplate::find($id);
        $type = TemplateTypes::all();
        $module = Module::all();
        $components = ModuleComponents::all();
        $stage = Stage::all();
        return view('QCTemplates::QuestionnaireTemplates.update-questionnaire-template', compact('data','type','module','components','stage'));
    }

    public function DeleteTemplate($id=0)
    {
        // QuestionnaireTemplate::where('id',$id)->delete();
        // return back()->with('success','Template Delete successfully!');
        // QuestionnaireTemplate::where('id',$id)->update(['status' => 0, 'updated_by' =>session()->get('userid')]);
        $id = sanitizeVariable($id);
        $data = QuestionnaireTemplate::where('id',$id)->get();
        $status =$data[0]->status;
        if($status==1){
          $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
          $update_query = QuestionnaireTemplate::where('id',$id)->orderBy('id', 'desc')->update($status);
        }else{
          $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
          $update_query = QuestionnaireTemplate::where('id',$id)->orderBy('id', 'desc')->update($status);
        }
        $module_name = \Request::segment(1);
         //print_r($module_name);
        return redirect()->route($module_name.'-questionnaire-template');
        

    }

    public function viewTemplateDetails($id=0)
    {
        $id = sanitizeVariable($id);
        $data = QuestionnaireTemplate::find($id);
        $type = TemplateTypes::where("id",$data->template_type_id)->get(['template_type']);
        $module = Module::where("id",$data->module_id)->get(['module']);
        $components = ModuleComponents::where("id",$data->component_id)->get(['components']);
        $stage = Stage::where("id",$data->stage_id)->get(['description']);
        $stageCode = StageCode::where("id",$data->stage_code)->get(['description']);
        return view('QCTemplates::QuestionnaireTemplates.view-questionnaire-template', compact('data','type','module','components', 'stage', 'stageCode'));
    }

    public function printQuestionnaireTemplate($id=0)
    {
        $id = sanitizeVariable($id);
        $data = QuestionnaireTemplate::find($id);
        $type = TemplateTypes::where("id",$data->template_type_id)->get(['template_type']);
        $module = Module::where("id",$data->module_id)->get(['module']);
        $components = ModuleComponents::where("id",$data->component_id)->get(['components']);
        $stage = Stage::where("id",$data->stage_id)->get(['description']);
        $stageCode = StageCode::where("id",$data->stage_code)->get(['description']);
        PDF::setOptions(['dpi' => 96, 'defaultFont' => 'serif','fontHeightRatio' => 1.3]);
        $pdf = PDF::loadView('QCTemplates::QuestionnaireTemplates.questionnaire-template-print', compact('data','type','module','components', 'stage', 'stageCode'));
        return $pdf->stream('QuestionnaireTemplate.pdf', array('Attachment'=>0));          
    // return view('QCTemplates::QuestionnaireTemplates.questionnaire-template-print', compact('data','type','module','components', 'stage', 'stageCode'));
    }

    public function printDecisionTreeTemplate($id=0){
        $id = sanitizeVariable($id);
        $data = QuestionnaireTemplate::find($id);
        $type = TemplateTypes::where("id",$data->template_type_id)->get(['template_type']);
        $module = Module::where("id",$data->module_id)->get(['module']);
        $components = ModuleComponents::where("id",$data->component_id)->get(['components']);
        $stage = Stage::where("id",$data->stage_id)->get(['description']);
        $stageCode = StageCode::where("id",$data->stage_code)->get(['description']);
        //return view('QCTemplates::DecisionTreeTemplates.decisiontree-template-print', compact('data','type','module','components', 'stage', 'stageCode'));
        PDF::setOptions(['dpi' => 96, 'defaultFont' => 'serif','fontHeightRatio' => 1.3]);
        $pdf = PDF::loadView('QCTemplates::DecisionTreeTemplates.decisiontree-template-print', compact('data','type','module','components', 'stage', 'stageCode'));
        return $pdf->stream('DecisionTreeTemplate.pdf', array('Attachment'=>0)); 
    }

    public static function getDynamicQuestionnaireTemplate($moduleId, $subModuleId, $stageId, $stepId)
    {
        // $moduleId    = $request->moduleId;
        // $subModuleId = $request->subModuleId;
        // $stageId     = $request->stageId;
        // $stepId      = $request->stepId;
        // $moduleId = sanitizeVariable($moduleId);
        // $subModuleId = sanitizeVariable($subModuleId);
        // $stageId = sanitizeVariable($stageId);
        // $stepId = sanitizeVariable($stepId);

        if($stageId == "" && $stageId == 0 && $stepId == "" && $stepId == 0){
            if (QuestionnaireTemplate::where('module_id', $moduleId)->where('component_id', $subModuleId)->where('status', 1)->orderBy('id', 'DESC')->take(1)->count() > 0) {
                $scripts = QuestionnaireTemplate::where('module_id', $moduleId)->where('component_id', $subModuleId)->where('status', 1)->orderBy('id', 'DESC')->take(1)->get();
            } else {
                $scripts = "";
            }
        } elseif($stepId == "" && $stepId == 0){
            if (QuestionnaireTemplate::where('module_id', $moduleId)->where('component_id', $subModuleId)->where('stage_id', $stageId)->where('status', 1)->orderBy('id', 'DESC')->take(1)->count() > 0) {
                $scripts = QuestionnaireTemplate::where('module_id', $moduleId)->where('component_id', $subModuleId)->where('stage_id', $stageId)->where('status', 1)->orderBy('id', 'DESC')->take(1)->get();
            } else {
                $scripts = "";
            }
        } else {//modified by ashvini 14 jan 2021
            if (QuestionnaireTemplate::where('module_id', $moduleId)->where('component_id', $subModuleId)->where('stage_id', $stageId)->where('stage_code',$stepId)->where('status', 1)->orderBy('id', 'DESC')->take(1)->count() > 0) {
                $scripts = QuestionnaireTemplate::where('module_id', $moduleId)->where('component_id', $subModuleId)->where('stage_id', $stageId)->where('stage_code',$stepId)->where('status', 1)->orderBy('id', 'DESC')->take(1)->get();
                // dd($scripts);
            } else {
                $scripts = ""; 
            }
        }
        return $scripts;
    }

    public function listDecision(Request $request){ 
        if ($request->ajax()) {
            $module_name = sanitizeVariable(\Request::segment(1));
            $check_module_name = DB::select("SELECT * FROM ren_core.modules WHERE LOWER(module) ='".strtolower($module_name)."'");
            $module_id = $check_module_name[0]->id;
            //dd($module_id);
            $configTZ = config('app.timezone');
			$userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
            //$data = QuestionnaireTemplate::where('module_id',$module_id)->where('template_type_id',6)->with('module','components','template','users','stage','step')->where('status',1)->get();
            $data = DB::select(DB::raw("select a.id as \"DT_RowId\", content_title, template_type, a.status, a.sequence, to_char(a.updated_at at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as updated_at, a.id, module, components,d.description as stage, e.description as step, f_name, l_name FROM ren_core.questionnaire_templates as a join ren_core.modules as b on a.module_id=b.id join ren_core.module_components as c on a.component_id=c.id join ren_core.stage as d on a.stage_id=d.id join ren_core.stage_codes as e on a.stage_code=e.id join rcare_admin.template as f on a.template_type_id=f.id left join ren_core.users as u on a.created_by=u.id  WHERE  a.module_id='".$module_id."' AND  template_type_id = 6  and a.status != 2 order by a.updated_at DESC"));
            // $data = QuestionnaireTemplate::with('module','components')->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<a href="update-decisiontree-template/'.$row->id.'" data-toggle="tooltip" data-original-title="Edit"  title="Edit" ><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>';
                if($row->status == 2){
                    $btn = '<a href="view-decisiontree-template/'.$row->id.'" data-toggle="tooltip" data-original-title="View"  title="View"><i class="text-15 i-Eye" style="color: green;"></i></a>';
                } 
                // $btn = $btn . '<a href="delete-questionnaire-template/'.$row->id.'"><i class=" i-Close" style="color: #2cb8ea;"></i></a>';
                else if($row->status == 1){
                  $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" data-toggle="tooltip" data-original-title="Active"  title="Active" class="change_status_decision_template_active" id="active"><i class="i-Yess i-Yes" title="Active"></i></a>';
                }
                else
                {
                    $btn = $btn.'<a href="javascript:void(0)" data-id="'.$row->id.'" data-toggle="tooltip" data-original-title="Deactive"  title="Deactive" class="change_status_decision_template_deactive" id="deactive"><i class="i-Closee i-Close"  title="Deactive"></i></a>';
                }
                return $btn; 
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('QCTemplates::DecisionTreeTemplates.decisiontree-template');
    }

    public function addDecision(){
        $data = TemplateTypes::all();
        $service = Module::all();
        $sub_service = ModuleComponents::all();
        $devices = Devices::all();
        return view('QCTemplates::DecisionTreeTemplates.add-decision-tree', compact('data','service','devices', 'sub_service'));
    }

    public function copyDecision(){
        return view('QCTemplates::DecisionTreeTemplates.copy-decisiontree');
    }

    public function copyQTemplate(){
        return view('QCTemplates::QuestionnaireTemplates.copy-questionnaire-template');
    }

    public function saveDTemplate(Request $request)
    {
        

       // dd($request->months); 
       
        $months = array();
        if($request->months != null || $request->months != ''){
            foreach(sanitizeVariable($request->months) as $key => $val){
                $months[] = $key;       
            }
        }
        //dd($months);
        //check for the stage
        if(sanitizeVariable($request->has('module')) && sanitizeVariable($request->module) != "Select Module") {
            $module = sanitizeVariable($request->module);
        } else {
            $module = 0;
        }
        //check for the stage
        if(sanitizeVariable($request->has('sub_module')) && sanitizeVariable($request->sub_module) != "Select Sub Module") {
            $sub_module = sanitizeVariable($request->sub_module);
        } else {
            $sub_module = 0;
        }

        //check for the stage
        if(sanitizeVariable($request->has('stage_id')) && sanitizeVariable($request->stage_id) != "" && sanitizeVariable($request->stage_id) != "Select Stage" ) {
            $stages = sanitizeVariable($request->stage_id);
        } else {
            $stages = '0';
        }

        //check for the stage code0
        if(sanitizeVariable($request->has('stage_code')) && sanitizeVariable($request->stage_code) != "" && sanitizeVariable($request->stage_code) != "Select Stage Code") {
            $stage_code = sanitizeVariable($request->stage_code);
        } else {
            $stage_code = '0';
        }
        $questionDetails = array('question' => sanitizeMultiDimensionalArray($request->DT));
        $data = array('content_title' => sanitizeVariable($request->content_title),
        'template_type_id' => sanitizeVariable($request->template_type),
        'module_id' => $module,
        'stage_id' => $stages,
        'stage_code' => $stage_code,
        'component_id' => $sub_module,
        'question' => json_encode($questionDetails),
        'status'   => 1,
        'sequence' => sanitizeVariable($request->sequence),
        'display_months' => json_encode($months)
        );
        
        //dd($data);

            $data['created_by'] =session()->get('userid');
            $user = QuestionnaireTemplate::create($data);
            //$user = Template::create(['template_type'=> $request->template_type]);
            //return back()->with('success','Questionnaire Template Created successfully!');
            //Session::put('success','Decision Tree template created successfully!'); 
            if($module==2){
                return redirect()->route('rpm-decisiontree-template');
            }else if($module==8){
                return redirect()->route('patients-decisiontree-template');    
            } else{
                return redirect()->route('ccm-decisiontree-template');  
            }
            
            
           
        
    }

    public function viewdecisiontree($id=0){
        $id = sanitizeVariable($id);
        $data = QuestionnaireTemplate::find($id);
        $type = TemplateTypes::where("id",$data->template_type_id)->get(['template_type']);
        $service = Module::where("id",$data->module_id)->get(['module']);
        $sub_service = ModuleComponents::where("id",$data->component_id)->get(['components']);
        $stage = Stage::where("id",$data->stage_id)->get(['description']);
        $stageCode = StageCode::where("id",$data->stage_code)->get(['description']);
        return view('QCTemplates::DecisionTreeTemplates.update-decisiontree-template', compact('data','type','service','sub_service', 'stage', 'stageCode'));
    }

    public function disableviewdecisiontree($id=0){
        $id = sanitizeVariable($id);
        $data = QuestionnaireTemplate::find($id);
        $type = TemplateTypes::where("id",$data->template_type_id)->get(['template_type']);
        $service = Module::where("id",$data->module_id)->get(['module']);
        $sub_service = ModuleComponents::where("id",$data->component_id)->get(['components']);
        $stage = Stage::where("id",$data->stage_id)->get(['description']);
        $stageCode = StageCode::where("id",$data->stage_code)->get(['description']);
        return view('QCTemplates::DecisionTreeTemplates.view-decisiontree-template', compact('data','type','service','sub_service', 'stage', 'stageCode'));
    }


    public function DecisionTreeimage(Request $request){
        //dd($request->file('picture'));
        $image = $request->file('picture');
        $fname='DT';
        $lname='tree';
        $temp = sanitizeVariable($request->temp);
             $original_name = preg_replace("/\s+/", "_", $image->getClientOriginalName());
             //$file_extension=$image->getClientOriginalExtension();
             $file_extension = 'jpg';
             $new_name = $temp . '_' . $fname."_".$lname.".".$file_extension;                 
             $image = $image->move(public_path('patientProfileImage'), $new_name);
             $img_path = '/patientProfileImage/'.$new_name;                       
             return $img_path;
    }

    public function EditDecision(Request $request){

        $months = array();
        if($request->months != null || $request->months != ''){
            foreach(sanitizeVariable($request->months) as $key => $val){
                $months[] = $key;       
            }
        }

        if(sanitizeVariable($request->has('stages')) && sanitizeVariable($request->stages) != "" && sanitizeVariable($request->stages) != "Select Stage" ) {
            $stages = sanitizeVariable($request->stages);
        } else {
            $stages = '0';
        }
        if(sanitizeVariable($request->has('stage_code')) && sanitizeVariable($request->stage_code) != "" && sanitizeVariable($request->stage_code) != "Select Stage Code") {
            $stage_code = sanitizeVariable($request->stage_code);
        } else {
            $stage_code = '0';
        }
        $questionDetails = array('question' => sanitizeVariable($request->DT));
        $data = array('content_title' => sanitizeVariable($request->content_title),
        'template_type_id' => sanitizeVariable($request->template_type),
        'module_id' => sanitizeVariable($request->module),
        'stage_id' => $stages,
        'stage_code' => $stage_code,
        'component_id' => sanitizeVariable($request->sub_module),
        'question' => json_encode($questionDetails),
        'status'   => 1,
        'sequence' => sanitizeVariable($request->sequence),
        'display_months' => json_encode($months)
        );
        //dd($data);
        QuestionnaireTemplate::where('id',sanitizeVariable($request->question_id))->update(['status' => 2, 'updated_by' =>session()->get('userid')]);
        $data['created_by']= session()->get('userid');
        QuestionnaireTemplate::create($data);
        if($request->module==2){
            return redirect()->route('rpm-decisiontree-template')->with('success','Template Modified successfully!');
        }else{
            return redirect()->route('ccm-decisiontree-template')->with('success','Template Modified successfully!');
        }
        

    }
    public function UpdateDecisionTreeInline(Request $request)
    {
        
        $module_id = 3;
        $all_row = sanitizeVariable($request->all());
        $table_id = array_keys($all_row['data']);
        $row_id = $table_id[0];
        $data = array();
        $sequence = $all_row['data'][$row_id]['sequence'];
        $data['sequence'] = $sequence;
        $data['updated_by']= session()->get('userid');
        $data['id']= $row_id;
        $update_data = QuestionnaireTemplate::where("id", $row_id)->update($data);
        $decidiondata = DB::select(DB::raw("select a.id as \"DT_RowId\", content_title, template_type, a.sequence, a.updated_at, a.id, module, components,d.description as stage, e.description as step, f_name, l_name FROM ren_core.questionnaire_templates as a join ren_core.modules as b on a.module_id=b.id join ren_core.module_components as c on a.component_id=c.id join ren_core.stage as d on a.stage_id=d.id join ren_core.stage_codes as e on a.stage_code=e.id join rcare_admin.template as f on a.template_type_id=f.id left join ren_core.users as u on a.created_by=u.id  WHERE a.id ='".$row_id."' AND a.module_id='".$module_id."' AND  template_type_id = 6"));
        //dd($decidiondata[0]->DT_RowId);
        $data1['id'] = $decidiondata[0]->DT_RowId;
        $data1['sequence'] = $decidiondata[0]->sequence;
        $data1['content_title'] = $decidiondata[0]->content_title;
        $data1['template_type'] = $decidiondata[0]->template_type;
        $data1['module'] = $decidiondata[0]->module;
        $data1['components'] = $decidiondata[0]->components;
        $data1['stage'] = $decidiondata[0]->stage;
        $data1['updated_at'] = $decidiondata[0]->updated_at;
        $data1['DT_RowId'] = $decidiondata[0]->DT_RowId;
        $d['data'][0] = $data1;
        return $d;
        
    }   
    
    public function copyDTemplate(Request $request)
    {
        $module_id = sanitizeVariable($request->module_id);
        $fromstep = sanitizeVariable($request->from);
        $tostep = sanitizeVariable($request->to);
        $questionsToCopy = sanitizeMultiDimensionalArray($request->template);
        $step_name = sanitizeMultiDimensionalArray($request->step_name);
        //print_r($questionsToCopy['copy']);
        foreach($questionsToCopy['copy'] as $key=>$value)
        {
            $template = QuestionnaireTemplate::where('id',$key)->get();
            $data = array(
            'content_title' => $step_name.' '.$template[0]->content_title,
            'template_type_id' => $template[0]->template_type_id,
            'module_id' => $module_id,
            'stage_id' => $template[0]->stage_id,
            'stage_code' => $tostep,
            'component_id' => $template[0]->component_id,
            'question' => $template[0]->question,
            'status'   => 1,
            'add_to_patient_status' => $template[0]->add_to_patient_status,
            'one_time_entry'   => $template[0]->one_time_entry,
            'score'   => $template[0]->score,
            'sequence' => $template[0]->sequence,
            'display_months' => $template[0]->display_months
            );
            $data['created_by'] =session()->get('userid');
            $user = QuestionnaireTemplate::create($data);
            //print_r($data);
        }
       
    }

    public function renderTemplate(Request $request){
        $id = sanitizeVariable($request->id);
        //$template = QuestionnaireTemplate::where('id',$id)->get();
        //dd($id);
        $data = QuestionnaireTemplate::find($id);
        if(sanitizeVariable($request->type) == '6'){
            $html = view('QCTemplates::DecisionTreeTemplates.view-return', compact('data'))->render();
        }else{
            $html = view('QCTemplates::QuestionnaireTemplates.view-return', compact('data'))->render();
        }
        
        return response()->json([
            'status' => true,
            'html' => $html,
            'message' => 'successfully.',
        ]);
       // return $html;
    }


}