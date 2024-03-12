<?php

namespace RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use RCare\Org\OrgPackages\QCTemplates\src\Models\ContentTemplate;
use RCare\Org\OrgPackages\QCTemplates\src\Models\TemplateTypes;
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents;
use RCare\Org\OrgPackages\Stages\src\Models\Stage;
use RCare\Org\OrgPackages\StageCodes\src\Models\StageCode;
use RCare\Rpm\Models\Devices;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\QCTemplates\src\Http\Requests\SaveContentTemplateRequest;
use DataTables;
use PDF;
use DB;

class ContentTemplateController extends Controller
{

   

    public function printContentTemplate($id=0){
        $id = sanitizeVariable($id);
        $data = ContentTemplate::find($id);
        $type = TemplateTypes::where("id",$data->template_type_id)->get(['template_type']);
        $module = Module::where("id",$data->module_id)->get(['module']);
        $components = ModuleComponents::where("id",$data->component_id)->get(['components']);
        $stage = Stage::where("id",$data->stage_id)->get(['description']);
        $stageCode = StageCode::where("id",$data->stage_code)->get(['description']);
        $devices = Devices::where("id",$data->device_id)->get(['device_name']);
        PDF::setOptions(['dpi' => 96, 'defaultFont' => 'serif','fontHeightRatio' => 1.3]);
        $pdf = PDF::loadView('QCTemplates::ContentTemplates.content-template-print', compact('data','type','module','components', 'stage', 'stageCode','devices'));
        return $pdf->stream('ContentTemplate.pdf', array('Attachment'=>0));           
    }

    public function listContentTemplates(Request $request)
    {
        $module_id = 0;
        if ($request->ajax()) {
            $module_name = sanitizeVariable(\Request::segment(1));
            $check_module_name = DB::select("SELECT * FROM ren_core.modules WHERE LOWER(module) ='".strtolower($module_name)."'");
            $module_id = $check_module_name[0]->id;
            $data = ContentTemplate::where('module_id',$module_id)->with('service','subservice', 'template','users','stage','step')->orderBy('updated_at','desc')->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<a href="update-content-template/'.$row->id.'"  data-toggle="tooltip" data-original-title="Edit"  title="Edit"><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>';
                $btn = $btn . '<a href="view-content-template/'.$row->id.'"  data-toggle="tooltip" data-original-title="View"  title="View"><i class="text-15 i-Eye" style="color: green;"></i></a>';
                if($row->status == 1){
                    $btn = $btn . '<a href="delete-content-template/'.$row->id.'"  data-toggle="tooltip" data-original-title="Close"  title="Close"><i class="i-Yess i-Yes" title="Active"></i></a>';
                }else{
                    $btn = $btn . '<a href="delete-content-template/'.$row->id.'"  data-toggle="tooltip" data-original-title="Close"  title="Close"><i class="i-Closee i-Close"  title="Deactive"></i></a>';
                }
                
                return $btn;
            }) 
            ->rawColumns(['action'])
            ->make(true); 
        }
        return view('QCTemplates::ContentTemplates.content-template');
    }

    public function addTemplate()
    {
        $data = TemplateTypes::where('id','!=',5)->where('id','!=',6)->get();
        $module = Module::all();
        $components = ModuleComponents::all();
        $devices = Devices::all();
        return view('QCTemplates::ContentTemplates.add-content-template', compact('data','components','devices', 'module'));
        
    }

    public function saveTemplate(SaveContentTemplateRequest $request)
    {
        if(sanitizeVariable($request->has('editorData'))) {
            $editorData = addslashes(sanitizeVariable($request->editorData));
        } else {
            $editorData = '';
        }
        
        if(sanitizeVariable($request->has('content'))) {
            $editorData = sanitizeVariable($request->content);
        } else {
            $editorData = '';
        }

        //check for the form field
        if(sanitizeVariable($request->has('from'))) {
            $from = sanitizeVariable($request->from);
        } else {
            $from = '';
        }

        //check for the subject
        if(sanitizeVariable($request->has('subject'))) {
            $subject = sanitizeVariable($request->subject);
        } else {
            $subject = '';
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
        $contentDetails = array(
                                    'message' => $editorData,
                                    'from'    => $from, 
                                    'subject' => $subject,                               
                                );
        $data = array(
                        'content_title'    => sanitizeVariable($request->content_title),
                        'template_type_id' => sanitizeVariable($request->template_type),
                        'module_id'        => sanitizeVariable($request->module),
                        'component_id'     => sanitizeVariable($request->sub_module),
                        'stage_id'         => $stages,
                        'stage_code'       => $stage_code,
                        'status'           =>1,
                        'device_id'        => sanitizeVariable($request->devices),
                        'content'          => json_encode($contentDetails)
                    );
      //  dd($data);

        //check from which form submit requested
        if(!(sanitizeVariable($request->has('add'))) && !(sanitizeVariable($request->has('edit'))))  {
            return('failed');
        } else {
            if(sanitizeVariable($request->has('add'))) {
                $data['created_by']= session()->get('userid');
                $addData = ContentTemplate::create($data);
                if($addData) {
                    return('success');
                } else {
                    return('failed');
                }
                // return redirect()->route('content-template')->with('success','Template Modified successfully!');
            }

            if(sanitizeVariable($request->has('edit'))) {
                $data['updated_by']= session()->get('userid');
                $editData = ContentTemplate::where('id',sanitizeVariable($request->temp_id))->update($data);
                if($editData) {
                    return('success');
                } else {
                    return('failed');
                }
                // return redirect()->route('content-template')->with('success','Template Modified successfully!');
            }
        }
    }

    public function UpdateTemplate($id=0){
        $id = sanitizeVariable($id);
        $data = ContentTemplate::find($id);
        $type = TemplateTypes::all();
        $module = Module::all();
        $components = ModuleComponents::all();
        $stage = Stage::all();
        $devices = Devices::all();
        return view('QCTemplates::ContentTemplates.update-content-template', compact('data','type','module','components','stage','devices'));
    }

    public function DeleteTemplate($id=0)
    {
        $id = sanitizeVariable($id);
        $data = ContentTemplate::where('id',$id)->get();
        $status =$data[0]->status;
        if($status==1){
          $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
          $update_query = ContentTemplate::where('id',$id)->orderBy('id', 'desc')->update($status);
        }else{
          $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
          $update_query = ContentTemplate::where('id',$id)->orderBy('id', 'desc')->update($status);
        }
        //ContentTemplate::where('id',$id)->delete();
        return back()->with('success','Template Delete successfully!');
    }

    public function viewTemplateDetails($id=0)
    {
        $id = sanitizeVariable($id);
        $data = ContentTemplate::find($id);
        $type = TemplateTypes::where("id",$data->template_type_id)->get(['template_type']);
        $module = Module::where("id",$data->module_id)->get(['module']);
        $components = ModuleComponents::where("id",$data->component_id)->get(['components']);
        $stage = Stage::where("id",$data->stage_id)->get(['description']);
        $stageCode = StageCode::where("id",$data->stage_code)->get(['description']);
        $devices = Devices::where("id",$data->device_id)->get(['device_name']);
        return view('QCTemplates::ContentTemplates.view-content-template', compact('data','type','module','components', 'stage', 'stageCode','devices'));
    }
}