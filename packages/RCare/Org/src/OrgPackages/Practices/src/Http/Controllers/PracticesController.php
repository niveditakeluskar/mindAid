<?php
namespace RCare\Org\OrgPackages\Practices\src\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\Practices\src\Http\Requests\practicesAddRequest;
use RCare\Org\OrgPackages\Practices\src\Http\Requests\practicesGrpAddRequest;
use RCare\Org\OrgPackages\Practices\src\Http\Requests\practicesDocsAddRequest;
//use RCare\Org\OrgPackages\Practices\src\Http\Requests\practicesThresholdRequest;
use Illuminate\Http\Request; 
use RCare\Org\OrgPackages\Practices\src\Models\Practices;
use RCare\Org\OrgPackages\Practices\src\Models\PracticesGroup;
use RCare\Org\OrgPackages\Practices\src\Models\PracticeThreshold;
use RCare\Org\OrgPackages\Practices\src\Models\OrgThreshold;
use RCare\Org\OrgPackages\Providers\src\Models\Providers;
use RCare\Org\OrgPackages\Providers\src\Models\ProviderType;
use RCare\Org\OrgPackages\Providers\src\Models\ProviderSubtype;
use RCare\Org\OrgPackages\Physicians\src\Models\Physicians;
use RCare\Org\OrgPackages\Practices\src\Models\Document;
use RCare\Org\OrgPackages\Users\src\Models\Users;
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon;
use DataTables;
use Hash;
use Redirect,Response;
use Session;
use File;
use Illuminate\Validation\Rule;
use Validator;


class PracticesController extends Controller {

    
    public function AddPracticeLogo(Request $request){
     if(isset($_FILES) && !empty($_FILES)) {          
        if(sanitizeVariable($request->hasFile('file'))){                  
            $image = sanitizeVariable($request->file('file'));                
            $original_name = preg_replace("/\s+/", "_", $image->getClientOriginalName());
            $file_extension=$image->getClientOriginalExtension();
            $new_name = $original_name;  
            $image = $image->move(public_path('PracticeLogo'), $new_name);
         // $img_path = '/orgLogoRcare/PracticeLogo'.$new_name;                       
            return $new_name;
        }
      }
   }

    public function uploadDocsFile(practicesDocsAddRequest $request){
        $destinationPath = base_path('practice-provider-documents');
        $id              = sanitizeVariable($request->id);
        $type            = sanitizeVariable($request->doc_type);
        $formatted_now   = preg_replace("/\s+/", "_", now());
        if($type == '0'){
            $doc_type = sanitizeVariable($request->other_doc_type);
        } else {
            $doc_type = $type; 
        }
        $practice_id           = sanitizeVariable($request->practice_id);      
        $provider_id           = sanitizeVariable($request->provider_id);
        $doc_name              = sanitizeVariable($request->doc_name);
        $formattd_doc_name     = preg_replace("/\s+/", "_", $doc_name);
        $doc_comments          = sanitizeVariable($request->doc_comments);
        $created_by            = session()->get('userid'); 

        if(sanitizeVariable($request->hasFile('file'))){     
            $uploadedFile    = sanitizeVariable($request->file('file'));  
            $filename        = $uploadedFile->getClientOriginalName();
            $original_name   = preg_replace("/\s+/", "_", $uploadedFile->getClientOriginalName());
            $file_extension  = $uploadedFile->getClientOriginalExtension();
            $docs_new_name = $formattd_doc_name.'_'.$formatted_now.'.'.$file_extension;
            $move_file_in_folder = $uploadedFile->move(public_path('practice-provider-documents'), $docs_new_name);
            $documents_array = array(
                'doc_type'              => $doc_type,
                'practice_id'           => $practice_id,
                'provider_id'           => $provider_id,
                'doc_name'              => $doc_name,
                'doc_content'           => $docs_new_name,
                'doc_comments'          => $doc_comments,
            );
        } else {
            $docs_new_name = sanitizeVariable($request->exist_docs);  
            $documents_array = array(
                'doc_type'              => $doc_type,
                'practice_id'           => $practice_id,
                'provider_id'           => $provider_id,
                'doc_name'              => $doc_name,
                'doc_content'           => $docs_new_name,
                'doc_comments'          => $doc_comments,
            );
        }
        if(isset($id) && ($id != "")){
            $documents_array['updated_by'] = $created_by; 
            $update_doc = Document::where('id',$id)->update($documents_array);
            if($update_doc) {
                return 'The Document Updated Succesfully!'; 
            } else {
                return 'Something went wrong! Please try again.'; 
            }
        } else {
            $documents_array['created_by'] = $created_by;
            $documents_array['updated_by'] = $created_by;
            $insert_doc = Document::create($documents_array);
            if($insert_doc) {
                return 'The Document Uploaded Succesfully!';
            } else {
                return 'Something went wrong! Please try again.'; 
            }
        }
    }

     public function getActiveOtherDocumentList() {
        $otherdocumentList = [];
        $otherdocumentList = Document::activeDocument();
        return response()->json($otherdocumentList);
    }
    // public function uploadDocsFileZZZZ(Request $request){
    //  if(isset($_FILES) && !empty($_FILES)) {          
    //     if(sanitizeVariable($request->hasFile('file'))){                  
    //         $image = sanitizeVariable($request->file('file'));                
    //         $original_name = preg_replace("/\s+/", "_", $image->getClientOriginalName());
    //         $file_extension=$image->getClientOriginalExtension();
    //         $new_name = $original_name;  
    //         $image = $image->move(public_path('PracticeLogo'), $new_name);
    //      // $img_path = '/orgLogoRcare/PracticeLogo'.$new_name;                       
    //         return $new_name;
    //     }
    //   }
    // }


    // public function uploadDocsFile(practicesDocsAddRequest $request){ //commentted on 29Aug2022
    //     $destinationPath = base_path('practice-provider-documents');
    //     $id              = sanitizeVariable($request->id);
    //     // dd($id);
    //     $type            = sanitizeVariable($request->doc_type);
    //     if($type == '0'){
    //         $doc_type = sanitizeVariable($request->other_doc_type);
    //     }else{
    //         $doc_type = $type; 
    //     }
    //     //$doc_subtype           = sanitizeVariable($request->doc_subtype);
    //     $practice_id           = sanitizeVariable($request->practice_id);      
    //     $provider_id           = sanitizeVariable($request->provider_id);
    //     $doc_name              = sanitizeVariable($request->doc_name);
    //     $doc_comments          = sanitizeVariable($request->doc_comments);
    //     $created_by            = session()->get('userid'); 

    //     if(sanitizeVariable($request->hasFile('file'))){                  
    //         $image = sanitizeVariable($request->file('file'));                
    //         $original_name = preg_replace("/\s+/", "_", $image->getClientOriginalName());
    //         $file_extension=$image->getClientOriginalExtension();
    //         $expolde_name = explode(".", $original_name);
    //         $expolde_name1 = ("." . $expolde_name[1]);
    //             $without_ext_doc_name = 'upload'.($request->id);
    //             $Document = Document::max('id');
    //             $addid = $Document + '1';
    //             //$size = $request->file('file')->getSize();
    //             //dd($size);
    //             if(($request->id) != NULL){
    //                 $random_name = 'upload'.($request->id).$expolde_name1;
    //             } else{
    //                 $random_name = 'upload'.$addid.$expolde_name1;
    //             }
    //             //dd($random_name);
    //             $docs_new_name =  $random_name;
    //             $image = $image->move(public_path('practice-provider-documents'), $docs_new_name);
    //             $documents_array = array(
    //                 'doc_type'              => $doc_type,
    //                 'practice_id'           => $practice_id,
    //                 'provider_id'           => $provider_id,
    //                 'doc_name'              => $doc_name,
    //                 'doc_content'           => $docs_new_name,
    //                 'doc_comments'          => $doc_comments,
    //                 );
    //     }else{
    //         $docs_new_name = sanitizeVariable($request->exist_docs);  
    //         $documents_array = array(
    //             'doc_type'              => $doc_type,
    //             'practice_id'           => $practice_id,
    //             'provider_id'           => $provider_id,
    //             'doc_name'              => $doc_name,
    //             'doc_content'           => $docs_new_name,
    //             'doc_comments'          => $doc_comments,
    //             );
            
    //     }
    //     $Documents = Document::where('id',$id)->exists();
    //     if($Documents==true)
    //     {
    //         $documents_array['updated_by']=$created_by; 
    //         $update_doc= Document::where('id',$id)->update($documents_array);
    //         return 'The Document Updated Succesfully!';  
    //         //return "edit";
    //         // return redirect()->back()->with('successdocsmessage', 'The Document Updated Succesfully!');
    //         // $request->session()->put('successdocsmessage', 'The Document Updated Succesfully!');
    //         // Session::set('successdocsmessage','The Document Updated Succesfully!');
    //         // session()->put(['successdocsmessage'=>'The Document Updated Succesfully!']);
    //         // return redirect('/org/org-practice')->with('successdocsmessage', 'The Document Updated Succesfully!');
    //         // return redirect()->route('uploadFile')->with('success','Successfully added Product!');
    //         // return view('practice-main',['successdocsmessage'=>'The Document Updated Succesfully!']);
    //         // return View::make('practice-main', compact(['successdocsmessage'=>'The Document Updated Succesfully!']));
    //         //$suceesmsg = "The Document Updated Succesfully!";
    //         //return view('Org::Practices.practice-main',compact(['successdocsmessage'=>$suceesmsg]));    
    //     }else{  
    //         $documents_array['created_by']=$created_by;
    //         $documents_array['updated_by']=$created_by;
    //         $insert_doc= Document::create($documents_array);
    //         return 'The Document Inserted Succesfully!';
            
    //         //return "add";
    //         // return redirect()->back()->with('widoutdocssuccess', 'Your message has been sent successfully!');
    //         // $request->session()->put('key', 'value');
    //         //return redirect('/org/org-practice')->with('widoutdocssuccess', 'The success message!');
    //     }
            

    // }

    public function uploadDocsFileBK(Request $request){ //practicesDocsAddRequest
        //dd($request->id);
       //dd($request->all());
        $this->validate($request,[
         'practice_id'=>'required',[
            'practice_id.required' => "field is required."
         ]
        ]);
       // dd($request->all());
        $destinationPath = base_path('practice-provider-documents');
        if(sanitizeVariable($request->hasFile('file'))){                
            $image = sanitizeVariable($request->file('file'));                
            $original_name = preg_replace("/\s+/", "_", $image->getClientOriginalName());
            $file_extension=$image->getClientOriginalExtension();
            $expolde_name = explode(".", $original_name);
            $expolde_name1 = ("." . $expolde_name[1]);
                $without_ext_doc_name = 'upload'.($request->id);
                $Document = Document::max('id');
                $addid = $Document + '1';
               // dd($addid);
                if(($request->id) != NULL){
                    $random_name = 'upload'.($request->id).$expolde_name1;
                } else{
                    $random_name = 'upload'.$addid.$expolde_name1;
                }
                //dd($random_name);
                $docs_new_name =  $random_name;
                $image = $image->move(public_path('practice-provider-documents'), $docs_new_name);
        }
            $id                    = sanitizeVariable($request->id); 
            $doc_type              = sanitizeVariable($request->doc_type);
            //$doc_subtype           = sanitizeVariable($request->doc_subtype);
            $practice_id           = sanitizeVariable($request->practice_id);      
            $provider_id           = sanitizeVariable($request->provider_id);
            $doc_name              = sanitizeVariable($request->doc_name);
            $doc_comments          = sanitizeVariable($request->doc_comments);
            $created_by            = session()->get('userid'); 

        if(sanitizeVariable($request->hasFile('file'))){  
            $documents_array = array(
                                'doc_type'              => $doc_type,
                                'practice_id'           => $practice_id,
                                //'doc_subtype'           => $doc_subtype, 
                                'provider_id'           => $provider_id,
                                'doc_name'              => $doc_name,
                                'doc_content'           => $docs_new_name,
                                'doc_comments'          => $doc_comments
                                );
        } else {
            $documents_array = array(
                                'doc_type'              => $doc_type,
                                'practice_id'           => $practice_id,
                                //'doc_subtype'           => $doc_subtype, 
                                'provider_id'           => $provider_id,
                                'doc_name'              => $doc_name,
                                //'doc_content'           => $docs_new_name,
                                'doc_comments'          => $doc_comments
                                );
        }
        // dd($documents_array);
        //dd($docs_new_name);
        if(sanitizeVariable($request->hasFile('file'))){    
            $Document = Document::where('id',$id)->where('doc_content',$docs_new_name)->exists();
            //dd($Document);
            if($Document==true)
            { 
                File::delete($destinationPath.'/'.$docs_new_name);
                $documents_array['updated_by']=$created_by; 
                $update_document = Document::where('id',$id)->update($documents_array);
                // return view('Practices::practice-main');
                return back();
                return "upload docs add";
            }else{  
                $documents_array['created_by']=$created_by;
                $documents_array['updated_by']=$created_by;
                $insert_practice = Document::create($documents_array);
                // return view('Practices::practice-main');
                return back();
                return "upload docs edit";
            }
        } else {
           $Document = Document::where('id',$id)->exists();
            //dd($Document);
            if($Document==true)
            { 
            
                $documents_array['updated_by']=$created_by; 
                $update_document = Document::where('id',$id)->update($documents_array);
                // return view('Practices::practice-main');
                return back();
                return "upload docs add";
            }else{  
                $documents_array['created_by']=$created_by;
                $documents_array['updated_by']=$created_by;
                $insert_practice = Document::create($documents_array);
                // return view('Practices::practice-main');
                return back();
                return "upload docs edit";
            } 
        }
    }

   public function DocumentList(Request $request) {
        if ($request->ajax()) {
            $data = Document::with('users','practices','providers')->latest()->get();//Document::with('users')->latest()->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn ='<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editDocs" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
                if($row->status == 1){
                $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_docstatus_active" data-toggle="tooltip" data-original-title="Deactive" title="Deactive"><i class="i-Yess i-Yes" style="color: green;"></i></a>';
                }
                else
                {
                    $btn = $btn.'<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_docstatus_deactive" data-toggle="tooltip" data-original-title="Active" title="Active"><i class="i-Closee i-Close"  style="color: red;"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        //return view('Devices::devices-list');
        return view('Practices::practice-main');
    }

  // public function addpracticethreshold(practicesThresholdRequest $request) {
    public function addpracticethreshold(Request $request) {
            $practice_id = sanitizeVariable($request->practice_id);
            $bpmHigh = sanitizeVariable($request->bpmhigh);
            $bpmLow = sanitizeVariable($request->bpmlow);
            $diastolicHigh = sanitizeVariable($request->diastolichigh);
            $diastolicLow = sanitizeVariable($request->diastoliclow);
            $glucoseHigh = sanitizeVariable($request->glucosehigh);
            $glucoseLow = sanitizeVariable($request->glucoselow);
            $oxSatHigh = sanitizeVariable($request->oxsathigh);
            $oxSatLow = sanitizeVariable($request->oxsatlow);
            $systolicHigh = sanitizeVariable($request->systolichigh);
            $systolicLow = sanitizeVariable($request->systoliclow);
            $temperatureHigh= sanitizeVariable($request->temperaturehigh);
            $temperatureLow = sanitizeVariable($request->temperaturelow);
            $eff_date = Carbon::now();
            $created_by = session()->get('userid');

            $threshold_data = array(
                'practice_id' => $practice_id,
                'bpmhigh' => $bpmHigh,
                'bpmlow' => $bpmLow,
                'diastolichigh' => $diastolicHigh,
                'diastoliclow' => $diastolicLow,
                'glucosehigh' => $glucoseHigh,
                'glucoselow' => $glucoseLow,
                'oxsathigh' => $oxSatHigh,
                'oxsatlow' => $oxSatLow,
                'systolichigh' => $systolicHigh,
                'systoliclow' => $systolicLow,
                'temperaturehigh' => $temperatureHigh,
                'temperaturelow' => $temperatureLow,
                'eff_date' => $eff_date
                
            );
            $PracticeThreshold = PracticeThreshold::where('practice_id',$practice_id)->exists();
        if($PracticeThreshold==true)
        {
            $threshold_data['updated_by']=$created_by; 
            $update_practice = PracticeThreshold::where('practice_id',$practice_id)->update($threshold_data);
            //return "edit";
        }else{  
            $threshold_data['created_by']=$created_by;
            $threshold_data['updated_by']=$created_by;
            $insert_practice = PracticeThreshold::create($threshold_data);
            //return "add";
        }
    
   }

       public function addorgthreshold(Request $request) {
    
            $org_id = sanitizeVariable($request->org_id);
            $bpmHigh = sanitizeVariable($request->bpmhigh);
            $bpmLow = sanitizeVariable($request->bpmlow);
            $diastolicHigh = sanitizeVariable($request->diastolichigh);
            $diastolicLow = sanitizeVariable($request->diastoliclow);
            $glucoseHigh = sanitizeVariable($request->glucosehigh);
            $glucoseLow = sanitizeVariable($request->glucoselow);
            $oxSatHigh = sanitizeVariable($request->oxsathigh);
            $oxSatLow = sanitizeVariable($request->oxsatlow);
            $systolicHigh = sanitizeVariable($request->systolichigh);
            $systolicLow = sanitizeVariable($request->systoliclow);
            $temperatureHigh= sanitizeVariable($request->temperaturehigh);
            $temperatureLow = sanitizeVariable($request->temperaturelow);
            $eff_date = Carbon::now();
            $created_by = session()->get('userid');

            $threshold_data = array(
                'org_id' => $org_id,
                'bpmhigh' => $bpmHigh,
                'bpmlow' => $bpmLow,
                'diastolichigh' => $diastolicHigh,
                'diastoliclow' => $diastolicLow,
                'glucosehigh' => $glucoseHigh,
                'glucoselow' => $glucoseLow,
                'oxsathigh' => $oxSatHigh,
                'oxsatlow' => $oxSatLow,
                'systolichigh' => $systolicHigh,
                'systoliclow' => $systolicLow,
                'temperaturehigh' => $temperatureHigh,
                'temperaturelow' => $temperatureLow,
                'eff_date' => $eff_date
                
            );
            $OrgThreshold = OrgThreshold::where('org_id',$org_id)->exists();
        if($OrgThreshold==true)
        {
            $threshold_data['updated_by']=$created_by; 
            $update_org = OrgThreshold::where('org_id',$org_id)->update($threshold_data);
            //return "edit";
        }else{  
            $threshold_data['created_by']=$created_by;
            $threshold_data['updated_by']=$created_by;
            $insert_org = OrgThreshold::create($threshold_data);
            //return "add";
        }
    
   }



    public function addpractice(practicesAddRequest $request) {
        $name                  = sanitizeVariable($request->name);
        $practice_group        = sanitizeVariable($request->practice_group);
        $number                = sanitizeVariable($request->number);
        $location              = sanitizeVariable($request->location);
        $address               = sanitizeVariable($request->address);
        $phone                 = sanitizeVariable($request->phone);
        $key_contact           = sanitizeVariable($request->key_contact);
        $outgoing_phone_number = sanitizeVariable($request->outgoing_phone_number);
        $id                    = sanitizeVariable($request->id);       
        $provider_name         = sanitizeVariable($request->providers);
        $provider_type_id      = sanitizeVariable($request->provider_type_id);
        $provider_subtype_id   = sanitizeVariable($request->provider_subtype_id);
        $billable              = sanitizeVariable($request->billable);
        $created_by            = session()->get('userid');
        $is_active             = 1;
        $logo                  = sanitizeVariable($request->image_path);
        $partners              = sanitizeVariable($request->partner_id);
        $practice_type         = sanitizeVariable($request->practice_type)!=''?$request->practice_type:'pcp';
        if($billable == null){
            $billable = 1;
        }
      //  dd($billable);
        $practice_array = array(
                            'name'                  => $name,
                            'location'              => $location,
                            'number'                => $number,
                            'address'               => $address,
                            'phone'                 => $phone,
                            'key_contact'           => $key_contact,
                            'outgoing_phone_number' => $outgoing_phone_number,
                            'is_active'             => $is_active,
                            'logo'                  => $logo,
                            'practice_group'        => $practice_group,
                            'billable'              => $billable,
                            'partner_id'            => $partners,
                            'practice_type'         => $practice_type
                            
                        );

       

        $Practices = Practices::where('id',$id)->exists();
        if($Practices==true)
        {
            $practice_array['updated_by']=$created_by; 
            $update_practice = Practices::where('id',$id)->update($practice_array);
            return "edit";
        }else{  
            $practice_array['created_by']=$created_by;
            $practice_array['updated_by']=$created_by;
            $insert_practice = Practices::create($practice_array);
            return "add";
        }
    }

    public function addPracticeGroup(practicesGrpAddRequest $request)  
    {
        $practice_name = sanitizeVariable($request->practice_name);
        $assign_message = sanitizeVariable($request->assign_message);
        $id = sanitizeVariable($request->id);
        $created_by  = session()->get('userid');
        $data = array(
                    'practice_name' => $practice_name,
                    'assign_message' => $assign_message
                );
        $existpractice_name=PracticesGroup::where('id',$id)->exists();
        if($existpractice_name==true){
            $data['updated_by']= $created_by;
            $update_practice_name = PracticesGroup::where('id',$id)->orderBy('id', 'asc')->update($data);
        }else{
            $data['created_by']= $created_by;
            $data['updated_by']= $created_by;
            $insert_practice_name = PracticesGroup::create($data);
        }
    }


    public function getActivePracticeList(Request $request) {
        $practiceid=sanitizeVariable($request->practice_id);
        if($practiceid!=0){
            $providerList = [];
            $providerList = Practices::activePractices($practiceid);
            return response()->json($providerList);
        }else{
            $providerList = [];
            return response()->json($providerList);            
        }

    }  
    ///showing list of roles
    public function practice($caremanager){
        $caremanager = sanitizeVariable($caremanager);
        $practice = [];
        if($caremanager==null)
        {
            $caremanager = 001;   
        }
        if($caremanager == 001){
            $practice =  DB::table('ren_core.practices')->get();
            foreach($practice as $p)
            {
                
                $id = $p->id ; 
                
                $pat = \DB::select(("select count(distinct p.id) from patients.patient p 
                left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
                from patients.patient_providers pp1  
                inner join (select patient_id, max(id) as max_pat_practice 
                from patients.patient_providers  where provider_type_id = 1  and is_active =1  
                group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice) pp                 
                on p.id = pp.patient_id
                left join ren_core.practices rp on rp.id = pp.practice_id where rp.id = '".$id."' "));    
                
    
                $patientcount =$pat[0]->count;
                     
                $p->count  = $patientcount;
                // dd($p);
                                 
            }
            
        }else{
            $practice = DB::table('ren_core.practices')
        ->whereIn('id', DB::table('ren_core.user_practices')->where('user_id', $caremanager)->pluck('practice_id'))
        ->get();
        foreach($practice as $p)
        {
            
            $id = $p->id ; 
            
            $pat = \DB::select(("select count(distinct p.id) from patients.patient p 
            left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
            from patients.patient_providers pp1  
            inner join (select patient_id, max(id) as max_pat_practice 
            from patients.patient_providers  where provider_type_id = 1  and is_active =1  
            group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice) pp                 
            on p.id = pp.patient_id
            left join ren_core.practices rp on rp.id = pp.practice_id where rp.id = '".$id."' "));    
            

            $patientcount =$pat[0]->count;
                 
            $p->count  = $patientcount;
            // dd($p);
                             
        }
        }
        return response()->json($practice);
    }


    public function PracticeList(Request $request) {
        if ($request->ajax()) {
            $data = Practices::with('users')->with('practice_group')->with('partners')->latest()->get();
            return Datatables::of($data) 
            ->addIndexColumn()
            ->addColumn('threshold', function($row){ 
                $btns =    '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="practicethreshold" title="Edit">Edit</a>'; 
                return $btns;
            })
            ->addColumn('action', function($row){
            
                $btn =    '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editPractices" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
                           if($row->is_active == 1){
                               $btn = $btn. '<a href="javascript:void(0)" data-id ="'.$row->id.'" class="change_practicestatus_active"><i class="i-Yess i-Yes" title="Active"></i></a>';
                         }
                       else
                      {
                          $btn = $btn.'<a href="javascript:void(0)" data-id ="'.$row->id.'" class="change_practicestatus_deactive"><i class="i-Closee i-Close"  title="Inactive"></i></a>';
                     }
                    return $btn;
            })
            ->rawColumns(['action','threshold'])
            ->make(true);
        }
        // return view('Practices::practice-list');

        return view('Practices::practice-main');
    }

    public function PracticeGroupList(Request $request) {
        if ($request->ajax()) {
            $data = PracticesGroup::with('users')->latest()->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('threshold', function($row){ 
                $btns =    '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="orgthreshold" title="Edit">Edit</a>'; 
                return $btns;
            })

            ->addColumn('action', function($row){
                $btn =    '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editPracticesGroup" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
                           if($row->status == 1){
                               $btn = $btn. '<a href="javascript:void(0)" data-id ="'.$row->id.'" class="change_practicegrpstatus_active"><i class="i-Yess i-Yes" title="Active"></i></a>';
                         }
                       else
                      {
                          $btn = $btn.'<a href="javascript:void(0)" data-id ="'.$row->id.'" class="change_practicegrpstatus_deactive"><i class="i-Closee i-Close"  title="Inactive"></i></a>';
                     }
                    return $btn;
            })
            ->rawColumns(['action','threshold'])
            ->make(true);
        }
        // return view('Practices::practice-list');

        return view('Practices::practice-main');
    }

    public function deleteRole(Request $request) {
        $id= sanitizeVariable($request->id);
        $update = ['status' => '0'];
        Practices::where('id',$id)->update($update);
        return response()->json(['success'=>'Practice deleted successfully.']);
    }

    public function deleteProviderName(Request $request) {
         $id= sanitizeVariable($request->id);
         // dd($id);
    }

     //document active or Inactive
    //  public function changeDocumentStatus(Request $request) {
    //     $id = sanitizeVariable($request->id);
    //     $data = Document::where('id',$id)->get();
    //     dd($data);
    //     $status_val =$data[0]->status;
       
    //     if($status_val==1){
    //       $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
    //       $update_query = Document::where('id',$id)->orderBy('id', 'desc')->update($status);
    //     //   return "Deactive";
    //     }else{
    //       $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
    //       $update_query = Document::where('id',$id)->orderBy('id', 'desc')->update($status);
    //     //   return "Active";
    //     }
    // }
    public function changeDocumentStatus($id){
        $id = sanitizeVariable($id);
        $row = Document::find($id);
        // dd($row);
        $row->status=!$row->status;
        if($row->save()) {
            return redirect()->route('org_practice');
        } else {
            return redirect()->route('statuschange');
        }
    }

    //Practice active or notactive
    public function changePracticeGrpStatus($id) {
        $id = sanitizeVariable($id);
        $row = PracticesGroup::find($id);
        $row->status=!$row->status;
        if($row->save()) {
            return redirect()->route('org_practice');
        } else {
            return redirect()->route('statuschange');
        }
    }
    public function changePracticeStatus($id) {
        $id = sanitizeVariable($id);
        $row = Practices::find($id);
        $row->is_active=!$row->is_active;
        if($row->save()) {
            return redirect()->route('org_practice');
        } else {
            return redirect()->route('statuschange');
        }
    }

    public function populategrppractice(Request $request){
        $id = sanitizeVariable($request->id);
        $org_practice = (PracticesGroup::self($id) ? PracticesGroup::self($id)->population() : "");
        $result['AddPracticeGrpForm'] = $org_practice;
        return $result;
    }
    
    public function populatedocument(Request $request){
        $id = sanitizeVariable($request->id);
        $org_document_populate = (Document::self($id) ? Document::self($id)->population() : "");
        $result['AddDocumentForm'] = $org_document_populate;
        return $result;
    }

    public function populatepracticethreshold(Request $request){
        $id = sanitizeVariable($request->id);
        $threshold_practice = (PracticeThreshold::self($id) ? PracticeThreshold::self($id)->population() : "");
        $result['practice_threshold_form'] = $threshold_practice;
        return $result;
    }
    public function populateorgthreshold(Request $request){
        $id = sanitizeVariable($request->id);
        $threshold_org = (OrgThreshold::self($id) ? OrgThreshold::self($id)->population() : "");
        $result['org_threshold_form'] = $threshold_org;
        return $result; 
    }

    public function populate(Request $request)
    {   $id = sanitizeVariable($request->id);
        $org_practice = (Practices::self($id) ? Practices::self($id)->population() : "");

        $org_provider = Providers::where('practice_id',$id)->get()->toArray();
  
    if($org_provider){ 
            $prdata=array('providerdata'=>$org_provider);
            $org_practice['static']=array_merge($org_practice['static'], $prdata);
        }
       
       $result['AddPracticeForm'] = $org_practice;
      
        return $result;
    }

    public function getsubProviders(Request $request){
        $provider_type_id = sanitizeVariable($request->provider_type_id);
        // dd($request->provider_type_id);
        if(!empty($provider_type_id)){
            $param = providerSubtype::where('provider_type_id',sanitizeVariable($request->provider_type_id))->get();
            // dd($param);
            echo "<option value=''>Select Provider Subtype</option>";
            foreach($param as $value){
                echo "<option value='".$value->id."'>".$value->sub_provider_type."</option>";
                
            }
        }else{
            echo "<option value=''>Select Provider Subtype</option>";
        }
        }

        public function practicegrpRelatedPractice($practicegrpid)
        {   $practicegrpid = sanitizeVariable($practicegrpid);
            $userid = session()->get('userid');
            $usersdetails = Users::where('id',$userid)->get(); 
            $roleid = $usersdetails[0]->role;
            // dd($roleid);
            $practice = [];
            if($roleid == 2){

                    if($practicegrpid=="null")
                    {   //changes by priya 8th june 22 show pcp practices add practice_type
                        $practice = Practices::where("is_active", 1)->where("name","!=","null")->where('practice_type','pcp')->orderBy('name','asc')->get();
                        foreach($practice as $p) 
                        {
                            $id = $p->id;
                            $pro= \DB::select(("select  count(distinct p.id) from patients.patient p 
                            left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
                            from patients.patient_providers pp1  
                            inner join (select patient_id, max(id) as max_pat_practice 
                            from patients.patient_providers  where provider_type_id = 1  and is_active =1  
                            group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice) pp                 
                            on p.id = pp.patient_id
                            left join ren_core.practices rp on rp.id = pp.practice_id where rp.id = '".$id."' ")); 
                
                            $practicecount = $pro[0]->count; 
                            
                            $p->count = $practicecount;
                        }    
                    }
                    else{
                            $practice = Practices::where("is_active", 1)->where("practice_group", $practicegrpid)->where("name","!=","null")->orderBy('name','asc')->get();
                            foreach($practice as $p) 
                            {
                                $id = $p->id;
                                $pro= \DB::select(("select  count(distinct p.id) from patients.patient p 
                                left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
                                from patients.patient_providers pp1  
                                inner join (select patient_id, max(id) as max_pat_practice 
                                from patients.patient_providers  where provider_type_id = 1  and is_active =1  
                                group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice) pp                 
                                on p.id = pp.patient_id
                                left join ren_core.practices rp on rp.id = pp.practice_id where rp.id = '".$id."' ")); 
                    
                                $practicecount = $pro[0]->count; 
                                
                                $p->count = $practicecount;
                            }  
                        }

            }
            else{

                if($practicegrpid=="null")
                    {
                        $practice = Practices::where("is_active", 1)->where("name","!=","null")->orderBy('name','asc')->get();
                        foreach($practice as $p) 
                        {
                            $id = $p->id;
                            $pro= \DB::select(("select  count(distinct p.id) from patients.patient p 
                            left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
                            from patients.patient_providers pp1  
                            inner join (select patient_id, max(id) as max_pat_practice 
                            from patients.patient_providers  where provider_type_id = 1  and is_active =1  
                            group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice) pp                 
                            on p.id = pp.patient_id
                            left join ren_core.practices rp on rp.id = pp.practice_id where rp.id = '".$id."' ")); 
                
                            $practicecount = $pro[0]->count; 
                            
                            $p->count = $practicecount;
                        }    
                    }
                    else{
                        // dd("else");
                            // $practice = Practices::where("is_active", 1)->where("practice_group", $practicegrpid)->where("name","!=","null")->orderBy('name','asc')->get();
                            $practice = \DB::table('ren_core.practices as rp')
                            ->select('rp.name','rp.id','rp.location')
                            ->join('ren_core.user_practices as up','up.practice_id','=','rp.id')
                            ->where("up.user_id",$userid)
                            ->where("rp.is_active",1)
                            ->where("rp.practice_group", $practicegrpid)
                            ->where("rp.name","!=","null")
                            ->orderBy("rp.name","asc")
                            ->get();
                            // dd($practice);
                           
                            foreach($practice as $p) 
                            {
                                $id = $p->id;
                                $pro= \DB::select(("select  count(distinct p.id) from patients.patient p 
                                left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
                                from patients.patient_providers pp1  
                                inner join (select patient_id, max(id) as max_pat_practice 
                                from patients.patient_providers  where provider_type_id = 1  and is_active =1  
                                group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice) pp                 
                                on p.id = pp.patient_id
                                inner join ren_core.practices rp on rp.id = pp.practice_id and  rp.id = '".$id."'
                                inner join ren_core.user_practices up on up.practice_id = rp.id and up.user_id = '".$userid."'
                                 ")); 
                    
                                $practicecount = $pro[0]->count; 
                                
                                $p->count = $practicecount;
                            }  
                        }  
            }
            return response()->json($practice); 
        }
} 