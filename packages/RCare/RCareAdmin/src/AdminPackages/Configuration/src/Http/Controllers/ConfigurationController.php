<?php

namespace RCare\RCareAdmin\AdminPackages\Configuration\src\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RCare\RCareAdmin\AdminPackages\Configuration\src\Http\Requests\ConfigurationAddRequest;
use RCare\RCareAdmin\AdminPackages\Configuration\src\Models\Configurations;
use RCare\RCareAdmin\AdminPackages\Services\src\Models\Services;
use DataTables;
use Session;
class ConfigurationController extends Controller{

     public function index()
    { dd('dasssssssss');
        return view('Configuration::configuration-list'); 
    }

    public function fetchConfiguration(Request $request){
        if ($request->ajax()){
            $data = Configurations::with('users')->orderBy('status','desc')->get(['configurations->username as username', 'configurations->password as password','configurations->phone as phone', 'configurations->api_url as api_url','configurations->from_name as from_name', 'configurations->from_email as from_email',
            'configurations->port as port', 'configurations->host as host', 'configurations->cc_email as cc_email', 'id as id', 'status as status', 'config_type as config_type','updated_by as updated_by','updated_at as updated_at']);
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){

              if($row->config_type == 'sms'){ 

                 $btn = '<a data-toggle="modal" data-target="#myModal1" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" id="edit" class="edit_conf editconfigsms" title="Edit"><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>';

               }else{

                  $btn = '<a data-toggle="modal" data-target="#myModal" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" id="edit" class="edit_conf editconfigmail" title="Edit"><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>'; 
               }
                
               
              if($row->status == 1){ 
                  $btn =  $btn. '<a data-id="'.$row->id.'" id="update" class="update"><i class="i-Yess i-Yes" style="color: green;" title="Active"></i></a>

                  ';
              } else {
                  $btn =  $btn. '<a data-id="'.$row->id.'" id="update" class="update"><i class="i-Closee i-Close" style="color: red;" title="Inactive"></i></a>

                  ';
              } 
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        return view('Configuration::configuration-list'); 
    }
  }

    public function createConfiguration(ConfigurationAddRequest $request)
    {   
        $hidden_id = sanitizeVariable($request->input('hidden_id'));
        $data = Configurations::where('id',$hidden_id)->count();
        // dd($data);
        $config_type= sanitizeVariable($request->input('config_type'));

        if($config_type=='sms') {
        $username = sanitizeVariable($request->input('username'));
        $phone    = sanitizeVariable($request->input('phone'));
        } elseif($config_type=='email') {
        $username = sanitizeVariable($request->input('user_name'));
        $phone = "";
        }

        if($config_type=='sms') {
        $password = sanitizeVariable($request->input('password'));
        } elseif($config_type=='email') {
        $password = sanitizeVariable($request->input('pass'));
        }

        // $username   = $request->input('username');
        // $password   = $request->input('password');
        $org_id     = session()->get('userid');
        $login_user = session()->get('userid');
       
        $api_url    = sanitizeVariable($request->input('api_url'));
        $from_name  = sanitizeVariable($request->input('from_name'));
        $from_email = sanitizeVariable($request->input('from_email'));
        $host       = sanitizeVariable($request->input('host'));
        $port       = sanitizeVariable($request->input('port'));
        $cc_email   = sanitizeVariable($request->input('cc_email'));

        $createConfiguration_json = array(  
              'username'  => $username,
              'password'  => $password,
              'api_url'   => $api_url,
              'from_name' => $from_name,
              'from_email'=> $from_email,
              'host'      => $host,
              'port'      => $port,
              'cc_email'  => $cc_email,
              'phone'     => $phone
        );

          $data_use = array(
            'config_type'    => sanitizeVariable($request->config_type),
            'org_id'         => sanitizeVariable($request->org_id),
            'configurations' =>json_encode($createConfiguration_json),
            // 'status'        => '1',
            'updated_by'=> session()->get('userid'),
            'created_by'=> session()->get('userid'),
          ); 
          //echo $data;
          if($data > 0){ 
             // $data_use=['updated_by'=> session()->get('userid')];
              $Configurations_update = Configurations::where('id',$hidden_id)->update($data_use);
              //print_r($data_use);
                  if($Configurations_update) { 
                    //Session::put('success','Data Update successfully!'); 
                    //echo $hidden_id;
                    return response()->json(['success'=>"Data Saved Successfully."]);
                  } else {
                    //Session::put('failed','Data Update failed!'); 
                   return response()->json(['failed'=>'Data Update failed!']);
                  }
              }else{
                $config_type= $request->input('config_type');
                $status_deactive = ['status' => '0','updated_by'=> session()->get('userid')];
                Configurations::where('config_type',$config_type,)->update($status_deactive);
                $Configurations_Add = Configurations::create($data_use,'status','1');
                  if($Configurations_Add){
                  // Session::put('success','Data created successfully!');
                    return response()->json(['success'=>"Data Saved Successfully."]); 
                    }else{
                  // Session::put('failed','Data Update failed!');
                    return response()->json(['failed'=>'Data Update failed!']);
                  }
              }    
        }

        public function editConfiguration($id)
        {
          // $Configuration = Configurations::find($id);
          $Configuration = Configurations::where("id", $id)->get(['configurations->username as username', 'configurations->password as password','configurations->phone as phone', 'configurations->api_url as api_url','configurations->from_name as from_name', 'configurations->from_email as from_email',
            'configurations->port as port', 'configurations->host as host', 'configurations->cc_email as cc_email', 'id as id', 'status as status', 'config_type as config_type']);
          // dd($Configuration);
          return response()->json($Configuration);

        }


      
    // edit users
    // public function updateConfiguration(Request $request) {
    //     $id= $request->id;
    //     // $update = ['role_name' => $request->role_name];

    //     $config_type =$request->input('config_type');
    //     $username   = $request->input('username');
    //     $password   = $request->input('password');
    //     $org_id     = session()->get('userid');
    //     $login_user = session()->get('userid');
       
    //     $api_url    = $request->input('api_url');
    //     $from_name  = $request->input('from_name');
    //     $from_email = $request->input('from_email');
    //     $host       = $request->input('host');
    //     $port       = $request->input('port');
    //     $cc_email   = $request->input('cc_email');

      
    //       $createConfiguration_sms = array(
    //           'username' => $request->username,
    //           'api_url' =>  $request->api_url,
    //           'password' => $request->password
    //         );

    //       $createConfiguration_mail = array(  
    //           'username'  => $request->username,
    //           'password'  => $request->password,
    //           'from_name' => $request->from_name,
    //           'from_email'=> $request->from_email,
    //           'host'      => $request->host,
    //           'port'      => $request->port,
    //           'cc_email'  => $request->cc_email
    //       );

    //       $data_sms = array(
    //         'config_type'   => $request->config_type,
    //         'org_id'        => $request->org_id,
    //         'configurations' =>json_encode($createConfiguration_sms),
    //         'status'        => '1',
    //         'created_by'    => $login_user,
    //       );

    //       $data_email = array(
    //         'config_type'   => $request->config_type,
    //         'org_id' => $request->org_id,
    //         'configurations' =>json_encode($createConfiguration_mail),
    //         'status' => '1',
    //         'created_by' => $login_user,
    //       );


    //        if($config_type=='sms'){
    //          $Configurations_Add = Configurations::where('id',$id)->update($data_sms);
    //           Session::put('success','Menu created successfully!'); 
    //           return back()->with('success','Menu created successfully!');
    //       }else{
    //          $Configurations_Add = Configurations::where('id',$id)->update($data_email);
    //            Session::put('success','Menu created successfully!'); 
    //           return back()->with('success','Menu created successfully!');
    //       }
    // }

        public function changeConfigurationStatus(Request $request)
        {
        $id= sanitizeVariable($request->id);
        $row = Configurations::find($id); 
       // dd($row);
        $row->status=!$row->status;
        $row->save();
        /*$config_type = Configurations::where('id',$id)->pluck('config_type');
        $status_deactive = ['status' => '0','updated_by'=> session()->get('userid')];
        $status_active = ['status' => '1','updated_by'=> session()->get('userid')];
        $all = Configurations::where('config_type',$config_type[0])->where('status', 1)->update($status_deactive);
        // dd($all);
        Configurations::where('id',$id)->update($status_active);
        return response()->json(['success'=>'Configuration status update successfully.']);*/
        return view('Configuration::configuration-list'); 
        }
    
}