<?php

namespace RCare\Ccm\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Ccm\Models\MailTemplate;
use RCare\Ccm\Models\Template;
use RCare\Ccm\Models\RcareServices;
use RCare\Ccm\Models\RcareSubServices;
// use RCare\Org\OrgPackages\Stages\src\Models\Stages;
use RCare\Org\OrgPackages\Stages\src\Models\Stage;
use RCare\Rpm\Models\Devices;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Log;
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents;
use RCare\Ccm\Http\Requests\CcmSaveTemplateRequest;

class MailTemplateController extends Controller
{
    public function ListMail(Request $request)
    {
        if ($request->ajax()) {
            // $data = MailTemplate::select(array('id', 'content_title', 'ren_core.service.service_name as service'))->with('service:id,service_name','subservice:id,sub_services')->get();
            $data = MailTemplate::with('service','subservice')->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<a href="updatemailtemplate/'.$row->id.'" ><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>';
                $btn = $btn . '<a href="viewTemplateDetails/'.$row->id.'"><i class="text-15 i-Eye" style="color: green;"></i></a>';
                $btn = $btn . '<a href="deletemailtemplate/'.$row->id.'"><i class=" i-Close" style="color: #2cb8ea;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
            dd($data);
        }
        return view('Ccm::mail.listmailtemplate');
    }

    public function addTemplate()
    {
        $data = Template::all();
        $module = Module::all();
        $components = ModuleComponents::where('module_id',3)->get();
        $devices = Devices::all();
        return view('Ccm::mail.mail', compact('data','components','devices', 'module'));
        
    }

    public function CcmSaveTemplate(CcmSaveTemplateRequest $request)
    {
        // //check for the content
        // if($request->has('content')) {
        //     $content = $request->content;
        // } else {
        //     $content = '';
        // }

        //check for ckeditor data
        if($request->has('editorData')) {
            $editorData = $request->editorData;
        } else {
            $editorData = '';
        }

        //check for the form field
        if($request->has('from')) {
            $from = $request->from;
        } else {
            $from = '';
        }

        //check for the subject
        if($request->has('subject')) {
            $subject = $request->subject;
        } else {
            $subject = '';
        }

        //check for the stage code
        if($request->has('stage_code') && $request->stage_code != "Select Stage Code") {
            $stage_code = $request->stage_code;
        } else {
            $stage_code = 0;
        }

        //create content details array
        $contentDetails = array(
                                    'message' => $editorData,
                                    'from'    => $from, 
                                    'subject' => $subject,                               
                                );
        $data = array(
                        'content_title'    => $request->content_title,
                        'template_type_id' => $request->template_type,
                        'module_id'        => $request->module,
                        'component_id'     => $request->sub_module,
                        'stage_id'         => $request->stages,
                        'stage_code'       => $stage_code,
                        'device_id'        => $request->devices,
                        'content'          => json_encode($contentDetails)
                    );

        //check from which form submit requested
        if(!($request->has('add')) && !($request->has('edit')))  {
            return('failed');
        } else {
            if($request->has('add')) {
                $addData = MailTemplate::create($data);
                if($addData) {
                    return('success');
                } else {
                    return('failed');
                }
                // return redirect()->route('listmailtemplate')->with('success','Template Modified successfully!');
            }

            if($request->has('edit')) {
                $editData = MailTemplate::where('id',$request->temp_id)->update($data);
                if($editData) {
                    return('success');
                } else {
                    return('failed');
                }
                // return redirect()->route('listmailtemplate')->with('success','Template Modified successfully!');
            }
        }
    }

    public function UpdateTemplate($id=0){
        $data = MailTemplate::find($id);
        $type = Template::all();
        // $service = RcareServices::all();
        // $sub_service = RcareSubServices::where('services_id',$data->module_id)->get();
        $service = Module::all();
        $sub_service = ModuleComponents::where('module_id',$data->module_id)->get();
        $stage = Stage::all();
        $devices = Devices::all();
        return view('Ccm::mail.updatemailtemplate', compact('data','type','service','sub_service','stage','devices'));
    }
    
    // public function CcmSaveTemplate(Request $request)
    // {
    //     if($request->has('content')) {
    //         $content = $request->content;
    //     } else {
    //         $content = '';
    //     }
    //     if($request->has('editorData')) {
    //         $editorData = $request->editorData;
    //     } else {
    //         $editorData = '';
    //     }
    //     if($request->has('from')) {
    //         $from = $request->from;
    //     } else {
    //         $from = '';
    //     }
    //     if($request->has('subject')) {
    //         $subject = $request->subject;
    //     } else {
    //         $subject = '';
    //     }
    //     if($request->has('stage_code')) {
    //         $stage_code = $request->stage_code;
    //     } else {
    //         $stage_code = 0;
    //     }
    //     $contentDetails = array(
    //                             'message' => $editorData,
    //                             'from'    => $from, 
    //                             'subject' => $subject,                               
    //                             );
                      

    //     $data = array('content_title' => $request->content_title,
    //     'template_type_id' => $request->template_type,
    //     'module_id' => $request->module,
    //     'component_id' => $request->sub_module,
    //     'stage_id' => $request->stages,
    //     'stage_code' => $stage_code,
    //     'device_id' => $request->devices,
    //     // 'description' => $request->description,
    //     'content' => json_encode($contentDetails)
    //     );

    //     if ($request->has('edit')){
    //         $editData = MailTemplate::where('id',$request->temp_id)->update($data);
    //         if($editData) {
    //             return('success');
    //         } else {
    //             return('failed');
    //         }
    //         // return redirect()->route('listmailtemplate')->with('success','Template Modified successfully!');
    //     }
    //     else if($request->has('service')){
    //         $value = RcareSubServices::where('services_id',3)->get(); // 3 for Ccm
    //         foreach($value as $key){
    //             echo "<option value='".$key->id."'>".$key->sub_services."</option>";
    //         }
    //     }
    //     else if($request->has('stages_id')){
           
    //          $value = Stages::where('submodule_id',$request->stages_id)->get();
    //         // echo "<option value='1'>ghhg</option>";
    //          foreach($value as $key){
    //              echo "<option value='".$key->id."'>".$key->description."</option>";
    //          }
    //     }
    //     else{
    //         $user = MailTemplate::create($data);
    //        //$user = Template::create(['template_type'=> $request->template_type]);
    //         return back()->with('success','Template Created successfully!');
    //     }
    // }

    // public function ListMail()
    // {
    //     $data = MailTemplate::with('template','service','subservice')->get();
    //    // $user->status = $request->status;
    // //dd($data);
    //     return view('Ccm::mail.listmailtemplate', compact('data'));
    // }

    

    public function test_modal(){
        return view('Ccm::Modal.callingwrapup');
    }
    public function DeleteTemplate($id=0)
    {
        MailTemplate::where('id',$id)->delete();
        return back()->with('success','Template Delete successfully!');

    }
    
    public function CreateServices(){
        $service = RcareServices::all();
        $sub_service = RcareSubServices::with('services')->get();
        return view('Ccm::mail.rcareservices', compact('service','sub_service'));
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
        $sub_service = RcareSubServices::where('services_id',$data->module_id)->get();
        return view('Ccm::mail.viewTemplateDetails', compact('data','type','service','sub_service'));
        
    }

    

}
