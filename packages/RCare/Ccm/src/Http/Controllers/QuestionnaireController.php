<?php

namespace RCare\Ccm\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Ccm\Models\RcareServices;
use RCare\Ccm\Models\Questionnaire;
use RCare\Ccm\Models\Template;
use RCare\Ccm\Models\RcareSubServices;
use RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents;
use RCare\Rpm\Models\Stages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class QuestionnaireController extends Controller
{
    
    public function index(){

        $service = RcareServices::all();
        $subModule =  RcareSubServices::where('services_id',2)->get();
        return view('Ccm::questionnaire.questionnaire', compact( 'service', 'subModule'));
        
    }

    public function inshow()
    {
        $service = RcareServices::all();
        return view('Ccm::questionnaire.treeView', compact('service'));
        
    }

    public function listQuestionnaire()
    {
        
        $data = Questionnaire::with('template','service','subservice')->get();
        //dd($data);
        return view('Ccm::questionnaire.listQuestionnaire', compact('data'));
    }
    public function listDecision()
    {
        
        $data = Questionnaire::with('template','service','subservice')->where('template_type_id',6)->get();
        //dd($data);
        return view('Ccm::questionnaire.listDecision', compact('data'));
    }


    public function viewQuestionnaireDetails($id=0)
    {
        $data = Questionnaire::find($id);
       // Log::info($data);
        $type = Template::all();
        $service = RcareServices::all();
        $sub_service = RcareSubServices::where('services_id',$data->module_id)->get();
        return view('Ccm::questionnaire.viewQuestionnaireDetails', compact('data','type','service','sub_service'));
        
    }

    public function testJsonArr(){
        $DT['qs']['q']="Are you feelings depressed or sad?";
        $DT['qs']['AF']="radio";
        $DT['qs']['opt1']['val']="yes";
        $DT['qs']['opt2']['val']="no";

       // $DT['qs']['opt'][1]= "How long have you felt this way? Are you being treated for depression and are you compliant with your medications";
       
        $DT['qs']['opt1']['qs1']['q']="How long have you felt this way? Are you being treated for depression and are you compliant with your medications.n";
        $DT['qs']['opt1']['qs1']['AF1']="radio";
        $DT['qs']['opt1']['qs1']['opt1']['val']="Yes, I hurt myself";
        $DT['qs']['opt1']['qs1']['opt2']['val']="No I am not on medication";

       
        
        $DT['qs']['opt1']['qs2']['q']="Are you having difficulty sleeping? How much sleep are you getting? It is very important to have the right amount of sleep";
        $DT['qs']['opt1']['qs2']['AF']="radio";
        $DT['qs']['opt1']['qs2']['opt1']['val']="yes";
        $DT['qs']['opt1']['qs2']['opt2']['val']="no";

        $DT['qs']['opt1']['qs1']['opt1']['qs1']['q']="How long have you felt this way? Are you being treated for depression and are you compliant with your medications.n";
        $DT['qs']['opt1']['qs1']['opt1']['qs1']['AF1']="radio";
        $DT['qs']['opt1']['qs1']['opt1']['qs1']['opt1']['val']="Yes, I hurt myself";
        $DT['qs']['opt1']['qs1']['opt1']['qs1']['opt2']['val']="No I am not on medication";

        
        echo "<code>";
        echo json_encode( $DT);
    }

    public function deleteQuestionnaire($id=0)
    {
        Questionnaire::where('id',$id)->delete();
        return back()->with('success','Template Delete successfully!');

    }

    public function SaveQuestionnaire(Request $request)
    {
        
        //dd($request);
        // Log::info($request);
       
        // Log::info($request);
        // Log::info(json_encode($request->question));
        // die;
        if ($request->has('tree')){
            $questionDetails = array('question' => $request->DT);
        }else{
            $questionDetails = array('question' => $request->question);
        }

        $data = array('content_title' => $request->content_title,
        'template_type_id' => $request->template_type,
        'module_id' => 2,
        'component_id' => $request->sub_module,
        'stage_id' => $request->stage_id,
        'stage_code' => $request->stage_code,
        'question' => json_encode($questionDetails)
        );

        if ($request->has('edit')){
            Questionnaire::where('id',$request->question_id)->update($data);
            return redirect()->route('listQuestionnaire')->with('success','Template Modified successfully!');
        }
        else if($request->has('service')){
            echo ' <option  value="0" selected>Choose One...</option>';
            $value = RcareSubServices::where('services_id',2)->get();
            foreach($value as $key){
                echo "<option value='".$key->id."'>".$key->sub_services."</option>";
            }
        }
        else{
            
            $user = Questionnaire::create($data);
           //$user = Template::create(['template_type'=> $request->template_type]);
            return back()->with('success','Questionnaire Template Created successfully!');

        }
    }

    public function EditDecision(Request $request){
        $questionDetails = array('question' => $request->DT);
        $data = array('content_title' => $request->content_title,
        'template_type_id' => $request->template_type,
        'module_id' => 2,
        'stage_id' => $request->stages,
        'stage_code' => 0,
        'component_id' => $request->sub_module,
        'question' => json_encode($questionDetails)
        );
        Questionnaire::where('id',$request->question_id)->update($data);
        return back()->with('success','Template Modified successfully!');

    }
    public function updateQuestionnaire($id=0)
    {
        
        $data = Questionnaire::find($id);
        Log::info($data);
        $type = Template::all();
        $service = RcareServices::all();
        $sub_service = RcareSubServices::where('module_id',$data->module_id)->get();
        $stage = Stages::all();
        if($data->template_type_id == 6){
            return view('Ccm::questionnaire.updateResponseTreeQuestionnaire', compact('data','type','service','sub_service','stage'));
        }else{
            return view('Ccm::questionnaire.updateQuestionnaire', compact('data','type','service','sub_service'));
        }
        
    }

    public function get($id=0)
    {
        
        $data1 = Questionnaire::all();
      
         return view('Ccm::questionnaire.updateResponseTreeQuestionnaire', compact('data','type','service','sub_service'));
    }



}
