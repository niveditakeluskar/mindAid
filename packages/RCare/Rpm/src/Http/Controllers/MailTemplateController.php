<?php

namespace RCare\Rpm\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Rpm\Models\MailTemplate;
use RCare\Rpm\Models\Template;
use RCare\Rpm\Models\RcareServices;
use RCare\Rpm\Models\RcareSubServices;
use RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents;
use RCare\Org\OrgPackages\Modules\src\Models\Models;
use RCare\Rpm\Models\Stages;
use RCare\Rpm\Models\Devices;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Log;

class MailTemplateController extends Controller
{
    
    public function test_modal(){
        return view('Rpm::Modal.callingwrapup');
    }

    public function index()
    {
        $data = Template::all();
        $service = RcareServices::all();
        $subModule =  RcareSubServices::where('module_id')->get();
        $devices = Devices::all();
        return view('Rpm::mail.mail', compact('data','subModule','devices', 'service'));
        
    }
/*    public function SaveTemplate(Request $request)
{
                if($request->has('content')) {
                $content = $request->content;
                } else {
                $content = '';
                }
                if($request->has('from')) {
                $from = $request->from;
                } else {
                $from = '';
                }
                if($request->has('subject')) {
                $subject = $request->subject;
                } else {
                $subject = '';
                }
                $contentDetails = array(
                'message' => $content,
                'from' => $from,
                'subject' => $subject,
                );

                $data = array('content_title' => $request->content_title,
                'template_type_id' => $request->template_type,
                'module_id' => 2,
                'component_id' => $request->sub_module,
                'stage_id' => $request->stages,
                'device_id' => $request->devices,
                // 'description' => $request->description,
                'content' => json_encode($contentDetails)
                );

                if ($request->has('edit')){
                MailTemplate::where('id',$request->temp_id)->update($data);
                return redirect()->route('listmailtemplate')->with('success','Template Modified successfully!');
                } else {
                $user = MailTemplate::create($data);
                return back()->with('success','Template Created successfully!');
                }*/
// else if($request->has('service')){
// $value = RcareSubServices::where('services_id',2)->get(); // 2 for RPM
// foreach($value as $key){
// echo "<option value='".$key->id."'>".$key->sub_services."</option>";
// }
// }
// else if($request->has('stages_id')){
// $value = Stages::where('submodule_id',$request->stages_id)->get();
// // echo "<option value='1'>ghhg</option>";
// foreach($value as $key){
// echo "<option value='".$key->id."'>".$key->description."</option>";
// }
// } else{
// $user = MailTemplate::create($data);
// //$user = Template::create(['template_type'=> $request->template_type]);
// return back()->with('success','Template Created successfully!');

// }
//}
   public function SaveTemplate(Request $request)
    {
    	//Log::info("--new--");
        if($request->has('content')) {
            $content = $request->content;
        } else {
            $content = '';
        }
        if($request->has('editorData')) {
            $editorData = $request->editorData;
        } else {
            $editorData = '';
        }
        if($request->has('from')) {
            $from = $request->from;
        } else {
            $from = '';
        }
        if($request->has('subject')) {
            $subject = $request->subject;
        } else {
            $subject = '';
        }
        $contentDetails = array(
                                'message' => $editorData,
                                'from'    => $from, 
                                'subject' => $subject,                               
                                );
                      
        
        $data = array('content_title' => $request->content_title,
        'template_type_id' => $request->template_type,
        'module_id' => $request->module,
        'component_id' => $request->sub_module,
        'stage_id' => $request->stages,
        'stage_code' =>  $request->stage_code,
        'device_id' => $request->devices,
        // 'description' => $request->description,
        'content' => json_encode($contentDetails)
        );
        // return $request;
        if ($request->has('edit')){
            MailTemplate::where('id',$request->temp_id)->update($data);
            // return $request;
            // return redirect()->route('listmailtemplate')->with('success','Template Modified successfully!');
            // return back()->with('success','Template Modified successfully!');
            return back()->with('success','Template Modified successfully!');
        }
        else if($request->has('service')){
            $value = ModuleComponents::where('module_id',$request->service)->get(); // 2 for RPM
            foreach($value as $key){
                echo "<option value='".$key->id."'>".$key->components."</option>";
            }
        }
        else if($request->has('sub_module') && ! $request->has('content')){ 
                       
             $value = Stages::where('submodule_id',$request->sub_module)->get();
             foreach($value as $key){
                 echo "<option value='".$key->id."'>".$key->description."</option>";
             }
        }
        else {
       // 	Log::info('save11');
            $user = MailTemplate::create($data);
           //$user = Template::create(['template_type'=> $request->template_type]);
            return back()->with('success','Template Created successfully!');
            // return $request;
        }
    }
    public function ListMail()
    {
        
        $data = MailTemplate::with('template','service','subservice')->get();
        //dd($data);
        return view('Rpm::mail.listmailtemplate', compact('data'));
    }
    public function DeleteTemplate($id=0)
    {
        MailTemplate::where('id',$id)->delete();
        return back()->with('success','Template Delete successfully!');

    }
    public function UpdateTemplate($id=0){
        $data = MailTemplate::find($id);
        $type = Template::all();
        $service = RcareServices::all();
        // $sub_service = RcareSubServices::where('services_id',$data->module_id)->get();
        $sub_service = RcareSubServices::all();
        $stage = Stages::all();
        $devices = Devices::all();
        return view('Rpm::mail.updatemailtemplate', compact('data','type','service','sub_service','stage','devices'));
    }
    public function CreateServices(){
        $service = RcareServices::all();
        $sub_service = RcareSubServices::with('services')->get();
        return view('Rpm::mail.rcareservices', compact('service','sub_service'));
    }
    public function AddServices(Request $request){
        if ($request->has('service')){
           $data = array(
            'service_name' => $request->service_name,
            'status' => $request->status 
           );
           RcareServices::create($data);
        }else{
            $data =array(
                'services_id' => $request->services_id,
                'sub_services' => $request->sub_services
            );
            RcareSubServices::create($data);
        }
        return back()->with('success','Created successfully!');
    }

    public function viewTemplateDetails($id=0)
    {
        $data = MailTemplate::find($id);
        $type = Template::all();
        $service = RcareServices::all();
        $sub_service = RcareSubServices::where('module_id',$data->module_id)->get();
        return view('Rpm::mail.viewTemplateDetails', compact('data','type','service','sub_service'));
        
    }

}
