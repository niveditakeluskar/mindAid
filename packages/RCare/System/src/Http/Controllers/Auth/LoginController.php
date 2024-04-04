<?php
namespace RCare\System\Http\Controllers\Auth;
use Auth;

use Mail;
use Hash;
use DB; 
use Validator;
use Session;

use Twilio\Rest\Client;
use Twilio\Jwt\ClientToken;
use Twilio\Exceptions\TwilioException;
use Twilio\TwiML\MessagingResponse;

use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect; 
use Illuminate\Foundation\Auth\AuthenticatesUsers;
// use RCare\RCareAdmin\AdminPackages\Login\src\Http\Requests\Request;
use Illuminate\Http\Request;
// use App\UserLoginHistory; 
use App\Http\Requests\PasswordSetRequest;
use Carbon\Carbon;
// use Illuminate\Database\Eloquent\Model;
use RCare\RCareAdmin\AdminPackages\Users\src\Models\User;

use RCare\RCareAdmin\AdminPackages\Users\src\Models\UserLoginHistory;
use RCare\Org\OrgPackages\Users\src\Models\Users;
use RCare\Org\OrgPackages\Roles\src\Models\RolesTypes;
use RCare\Org\OrgPackages\Users\src\Models\RenUserLoginHistory;
use RCare\RCareAdmin\AdminPackages\Configuration\src\Models\Configurations;
// use RCare\Org\OrgPackages\Users\src\Models\DomainFeatures;
use RCare\Org\OrgPackages\DomainFeatures\src\Models\DomainFeatures;
use Illuminate\Support\Str; 
use URL;
//use Illuminate\Support\Facades\Auth;
use RCare\System\Models\MfaTextingLog;
use App\Mail\DemoMail;

 $a=0;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */  
    
    
    use AuthenticatesUsers;
    // $password_attempts=0;
    protected $maxAttempts;
    
    public function index() {
       
    	$role  = session()->get('role');
    	if($role == '2')
        {
            $this->setLogOutLog_renCore();
            Auth::logout();
        }      
        $base_url = strtolower(URL::to('/').'/rcare-login'); 
        $DomainFeatures=DomainFeatures::where('features','2FA')
        ->where(DB::raw('lower(url)'), $base_url)
        ->first(); 
    //    dd($DomainFeatures); 
        $maxAttempts     = isset($DomainFeatures)?$DomainFeatures->password_attempts:''; //3
        //$otp_max_attempts =$DomainFeatures->otp_max_attempts;
        return view('System::login',compact('DomainFeatures'));
    }

    /**
     * Where to redirect users after login. 
     *
     * @var string
     */
    // protected $redirectTo = '/dashboard';
    // protected $redirectTo = 'task-management/work-list'; 
    protected $redirectTo = 'patients/worklist'; 

    /**
     * Create a new controller instance.
     * 
     * @return void
     */

    // protected $_config;
    public function __construct()
    {
         // $this->_config = request('_config');
        //$this->middleware('guest', ['except' => 'logout']);
        
        $this->middleware('guest')->except('logout');
        //  $this->middleware('guest:rcare_users')->except('logout');
        //    $this->middleware('guest:renCore_users')->except('logout');
            
    }

    /**
    * Authenticate the given login request
    *
    * @param Illuminate\Http\Request $request
    * @return mixed
    */
    // public function authenticate(Request $request)
    // {
    //     $email    = sanitizeVariable($request->input("email"));
    //     $password = sanitizeVariable($request->input("password"));
    //     if (Auth::attempt(credentials($email, $password))) {
    //          return redirect()->route("dashboard");
    //     }
    //     return back()->with("failed", true);
    // }

    /**
     * Determine if the user has too many failed login attempts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */

   
    protected $decayMinutes = 5; 

    //CODE BY ANAND========================
    public function otpVerify(Request $request)
    {  
        $chk_attempts = Users::where('id',$request->userid)->first();   
        $roleId = $chk_attempts->role;
        $role_details = RolesTypes::userRoleType($roleId);   
        $role_type = $role_details[0]->role_type;
        $timezone    =   !empty(sanitizeVariable($request->input('timezone')))? sanitizeVariable($request->input('timezone')) : config('app.timezone');
        $base_url = strtolower(URL::to('/').'/rcare-login');  
        if(sanitizeVariable($request->input('page_name')=='login')){
            $request->validate([
                'code'=>'required', 
            ]);
            $DomainFeatures = DomainFeatures::where('features','2FA')
            ->where(DB::raw('lower(url)'), $base_url)
            ->first();
            // dd($DomainFeatures);
            // print_r($chk_attempts->max_attempts .'='. $DomainFeatures->otp_max_attempts);
            if($chk_attempts->max_attempts > $DomainFeatures->otp_max_attempts){//2
                Users::where('id',sanitizeVariable($request->userid))->update(['temp_lock_time' => Carbon::now()]);  //'block_unblock_status' =>1,'blocked_via'=>'otp_attempt' 
                $block_time = $DomainFeatures->block_time;
                return response(['sucsses'=>2,'message'=>"You are temporary lock. Please contact to admin OR Try after $block_time minutes."]);
            }else{ 
                ($chk_attempts->max_attempts =="" && $chk_attempts->max_attempts ==null && $chk_attempts->max_attempts == 0) ? $count_attempts=1 : $count_attempts=$chk_attempts->max_attempts+1;
                Users::where('id',$request->userid)->update(['max_attempts' =>$count_attempts]); 
                $find = Users::where('id',$request->userid)
                          ->where('otp_code', $request->code)
                          ->where('status', 1)
                          ->first();

                if (!is_null($find)) {
                   // Session::put('userid', $request->userid);
                      session()->put(['email'=>$chk_attempts->email,'userid'=>sanitizeVariable($request->userid),
                       'status'=>$chk_attempts->status,'role'=>$chk_attempts->role,
                       'f_name'=>$chk_attempts->f_name,'l_name'=>$chk_attempts->l_name,
                       'profile_img'=>$chk_attempts->profile_img,'user_type'=>sanitizeVariable($request->role),
                       'org_id'=>$chk_attempts->org_id, 'timezone'=>$timezone, 'role_type'=>$role_type]);
                        $userStatus = Auth::guard('renCore_user')->user()->status;
                        Users::where('id',sanitizeVariable($request->userid))->update(['max_attempts' =>0,'otp_date'=>Carbon::now()]);
                        $this->setAfterLogInLog_renCore($request->userid,$chk_attempts->email);
                        return response(['sucsses'=>1,'message'=>'patients/worklist']);

                }else{
                    return response(['sucsses'=>0,'message'=>'You entered wrong authentication code.']);
                }
            } 
        }else if(sanitizeVariable($request->input('page_name')=='forget_pwd')){
           $request->validate([
                'code'=>'required',
            ]);
            Users::where('id',sanitizeVariable($request->userid))->update(['token' =>Str::random(60)]); 
            $chk_attempts = Users::where('id',$request->userid)->first();
            
            $DomainFeatures= DomainFeatures::where('features','2FA')
            ->where(DB::raw('lower(url)'), $base_url)    
            ->first();
             // dd($DomainFeatures); 
            // ($chk_attempts->max_attempts =="" && $chk_attempts->max_attempts ==null && $chk_attempts->max_attempts == 0) ? $count_attempts=1 : $count_attempts=$chk_attempts->max_attempts+1; 
            if($chk_attempts->max_attempts > $DomainFeatures->otp_max_attempts){//2
                Users::where('id',sanitizeVariable($request->userid))->update(['block_unblock_status' =>1]);  
               return response(['sucsses'=>2,'message'=>'You are temporary lock. Please contact to admin.']);
            }else{ 
                ($chk_attempts->max_attempts =="" && $chk_attempts->max_attempts ==null && $chk_attempts->max_attempts ==0) ? $count_attempts=1 : $count_attempts=$chk_attempts->max_attempts+1;
            // dd($count_attempts);
                Users::where('id',$request->userid)->update(['max_attempts' =>$count_attempts]);       
                $find = Users::where('id',$request->userid)
                          ->where('otp_code', $request->code)
                          ->where('status',1)
                          ->first();
                if(!is_null($find)) { 
                   // Session::put('userid', $request->userid);
                      session()->put(['email'=>$chk_attempts->email,'userid'=>sanitizeVariable($request->userid), 'status'=>$chk_attempts->status,
                      'role'=>$chk_attempts->role,'f_name'=>$chk_attempts->f_name,'l_name'=>$chk_attempts->l_name,'profile_img'=>$chk_attempts->profile_img,
                      'user_type'=>sanitizeVariable($request->role),
                      'org_id'=>$chk_attempts->org_id, 'timezone'=>$timezone]); 
                         // $userStatus = Auth::guard('renCore_user')->user()->status;
                         // // dd($userStatus);
                        //  dd(Session::get('timezone'));

                        $this->setAfterLogInLog_renCore($request->userid,$chk_attempts->email);
                        // Email code
                        $data = array( 
                           'email' => $chk_attempts->email, 
                           'name'  => $chk_attempts->f_name, 
                           'url'   =>  $base_url.'/password/reset?token='.$chk_attempts->token.'&login_as=2',
                           'link'  => $base_url.'/rcare-login'
                    );
                    // try{

                        $data['message'] = 'Hi '. $data["name"];
                       
                        $mailData = [
                            'title' => 'RCARE Password Reset',
                            'body' => $data['message'],
                            'message' => 'A password request has been requested for this account. If you did not request a password reset, it is encouraged that you change your password in order to prevent any malicious attacks on your account. Otherwise, proceed by clicking the link below. ',
                            'button_text' => 'Reset Password',
                            'button_url' => $data["url"],
                            'link' => 'Team Renova'
                        ];
                
                        Mail::to($data['email'])->send(new DemoMail($mailData));
                        
                        $response['success']='y';
                        $response['url']='';
                        $response['message']='Your Password Reset Request is Accepted, Please check your email.';
                        
                    // }catch(\Exception $e){
                        // dd($e); 
                        $response['success']='n';
                        $response['message_id'] ='';
                        $response['message']='Sorry we are unable to sent an email, try again';
                    // }
                    Users::where('id',sanitizeVariable($request->userid))->update(['max_attempts' =>0,'otp_date'=>Carbon::now()]);
                // Users::where('id',sanitizeVariable($request->userid))->update(['max_attempts' =>0,]);
                return response(['sucsses'=>1,'message'=>$response['message']]);
                }else{
                    return response(['sucsses'=>0,'message'=>'You entered wrong authentication code.']);
                } 
            } 
        }else{
            return response(['sucsses'=>0,'message'=>'Contact Admin']);
        }
        echo '{ "data":[ '. json_encode( $response) .']}';
    }   

    public function generateCode($id,$user_level_sms,$user_level_email){   
        try {
            $base_url = URL::to('/').'/rcare-login'; 
            // dd($base_url);
            //strtolower(URL::to('/').'/rcare-login');
            $userlevelmfa = Users::where('id',$id)->first();
            $user_level_sms = isset($user_level_sms)?$user_level_sms:0;
            $user_level_email =isset($user_level_email)?$user_level_email:0;
            $server_domain = DomainFeatures::where('features','2FA')
            ->where(DB::raw('lower(url)'), strtolower($base_url))
            ->where('status',1)->first();
            // dd($server_domain);
            $otp_digit = $server_domain->digit_in_otp; 
            $otp_by_sms = $user_level_sms;//$server_domain->otp_text ;
            $otp_by_email = $user_level_email;//$server_domain->otp_email;
            // dd($otp_by_email);
            $code = rand(pow(10, $otp_digit-1), pow(10, $otp_digit)-1); 
            $code_data =array('otp_code'=>$code,'created_by'=>$id, 'updated_by'=>$id);
            Users::where('id',$id)->update($code_data);
            $MobileNo = Users::select('number','country_code')->where('id',$id)->first();
            $country_code=$MobileNo->country_code;
            $mob=$MobileNo->number;
            $emailID = Users::select('email','f_name','l_name','token','otp_code')->where('id',$id)->first();
            $email = $emailID->email; 

            if($otp_by_sms==1 && ($otp_by_email== ''||$otp_by_email== 0 ||$otp_by_email==null ||$otp_by_email=='null')){//otp sent in phn 
                if(($country_code=="" || $country_code==null) || ($mob=="" || $mob==null)){
                    return array(['sucsses'=>"n",'msg'=>"Please ask administrator to add your contact number with country code for Multifactor authentication."]);
                }else{
                    $receiverNumber =$MobileNo->country_code.$MobileNo->number;
                    $text_message = "Multifactor Authentication login code is ". $code ." from RCARE ";
                    $sms = Configurations::where('config_type','sms')->orderBy('created_at', 'desc')->first();
                    // dd($sms);
                    if(isset($sms->configurations)){
                        $sms_detail = json_decode($sms->configurations);
                        $account_sid = $sms_detail->username;
                        $auth_token = $sms_detail->password;
                        $twilio_number = $sms_detail->phone;
                        try{
                            $client = new Client($account_sid, $auth_token);
                            $message = $client->messages->create($receiverNumber, [
                            'from' => $twilio_number, 
                            'body' => $text_message]);
                            $message_id = $message->sid;
                            $message1 = $client->messages($message_id)->fetch();
                            $msg_status = strtolower($message1->status);
                            $type = 'MFA';
                            $content = strip_tags($text_message); 
                            $sent_type = 'sms'; 
                            $sent_to = $receiverNumber;
                            $this->setTextingLog($id,$type,$sent_type,$content,$sent_to,$message_id,$msg_status);
                            $response['success']='y';
                            $response['message']="OTP has been sent to your contact number.";
                            $response['message_id'] = $message_id;
                            return array(['mob'=>$receiverNumber.'/','userid_otp'=>$id,'sucsses'=>$response['success'],
                            'msg'=>$response['message'],'message_id'=>$response['message_id']]);
                        } catch (TwilioException $e) {
                            // dd($e);  
                            // dd($e->getCode() . ' : ' .$e->getMessage());
                            $response['message'] = "We are currently not able to deliver the text, kindly use email for authentication method.";
                            return array(['mob'=>$receiverNumber.'/','userid_otp'=>$id,'sucsses'=>"n",'msg'=>$response['message']]);
                        }
                    }    
                }
                // return array(['mob'=>$receiverNumber.'/','userid_otp'=>$id,'sucsses'=>$response['success'],'msg'=>$response['message']]);
            }
            else if($otp_by_email==1 && ($otp_by_sms== 0 ||$otp_by_sms==null ||$otp_by_sms=='null')){//otp sent in email
                if($email=="" || $email==null){
                    return array(['sucsses'=>"n",'msg'=>"Please ask administrator to add your email-id for Multifactor authentication."]);
                }
                else{
                    $data = array(
                        'email'=>$emailID->email, 
                        'name'=>$emailID->f_name, 
                        'url'=> $base_url.'/password/reset?token='.$emailID->token.'&login_as=1',
                        'otp' =>$emailID->otp_code, 
                        'link'=> $base_url.'/rcare-login'
                    );
                    // try{
                        $data['message'] = 'Hi '. $data["name"];
                       
                        $mailData = [
                            'title' => 'RCARE Multifactor Authentication Code',
                            'body' => $data['message'],
                            'message' => 'Multifactor authentication login code is '.$data["otp"].' from RCARE.',
                            'button_text' => '',
                            'button_url' => '',
                            'link' => 'Team Renova'
                        ];
                
                        Mail::to($data['email'])->send(new DemoMail($mailData));

                        $type = 'MFA';
                        $email_msg  = '<h5>Hi  ' . $data["name"].', </h5> 
                        <p>Multifactor authentication forget password code is '.$data["otp"].' from RCARE "</p> 
                        <a>Team Renova</a>';
                        $content = strip_tags($email_msg);
                        $sent_type = 'email'; 
                        $sent_to = $email;
                        $message_id ='';
                        $msg_status='';
                        $this->setTextingLog($id,$type,$sent_type,$content,$sent_to,$message_id,$msg_status);
                        $response['success']='y';
                        $response['url']='';
                        $response['message']='OTP has been sent on your email, Please check your email';
                        $response['message_id'] = ''; 
                    
                    // }catch(\Exception $e){
                    //     // dd($e); 
                    //     $response['message_id'] = ''; 
                    //     $response['success']='n';
                    //     $response['url']='';//password_reset
                    //     $response['message']='We are currently not able to deliver the email, kindly use only text for authentication method';
                    // }
                    // Users::where('id',$id)->update(['otp_date'=>Carbon::now()]); 
                 return array(['mob'=>'/'.$emailID->email,'userid_otp'=>$id,'sucsses'=>$response['success'],
                 'msg'=>$response['message'],'message_id'=>$response['message_id']
                 ]);
                }
            }
            else if($otp_by_email==1 && $otp_by_sms== 1){ //otp sent in email & phn
                if(($country_code=="" || $country_code==null) || ($mob=="" || $mob==null)){
                 return array(['sucsses'=>"n",'msg'=>"Please ask administrator to add your contact number for Multifactor authentication."]);
                }else if($email=="" || $email==null){
                return array(['sucsses'=>"n",'msg'=>"Please ask administrator to add your Email Id for Multifactor authentication."]);
                }else{
                    $receiverNumber =$MobileNo->country_code.$MobileNo->number;
                    $text_message = "Multifactor Authentication login code is ". $code ." from RCARE ";
                    $sms = Configurations::where('config_type','sms')->orderBy('created_at', 'desc')->first();
                    if(isset($sms->configurations)){
                        $sms_detail = json_decode($sms->configurations);
                        $account_sid = $sms_detail->username;
                        $auth_token = $sms_detail->password;
                        $twilio_number = $sms_detail->phone;
                        $sid='';
                        try{
                            $client = new Client($account_sid, $auth_token);
                            $message = $client->messages->create($receiverNumber, [
                            'from' => $twilio_number, 
                            'body' => $text_message]);
                            $message_id = $message->sid;
                            $sid = $message->sid;
                            $message1 = $client->messages($message_id)->fetch();    
                        $msg_status = strtolower($message1->status);
                        $type = 'MFA';
                        $content = strip_tags($text_message);
                        $sent_type = 'sms'; 
                        $sent_to = $receiverNumber;
                        $this->setTextingLog($id,$type,$sent_type,$content,$sent_to,$message_id,$msg_status);
                            $response['mob_otp']='y';
                            $response['message_id'] = $sid;
                        } catch (TwilioException $e) {
                            // ($e->getCode() . ' : ' .$e->getMessage());
                            $response['mob_otp']='n';
                            $response['message_id'] ='';
                        }
                    } 
                    // return array(['sucsses'=>'N','msg'=>$response['message']]);
                    $data = array(
                        'email'=>$emailID->email, 
                        'name'=>$emailID->f_name, 
                        'url'=> $base_url.'/password/reset?token='.$emailID->token.'&login_as=1',
                        'otp' =>$emailID->otp_code, 
                        'link'=> $base_url.'/rcare-login'
                    );
                        
                    try{
                        $data['message'] = 'Hi '. $data["name"];
                       
                        $mailData = [
                            'title' => 'RCARE Multifactor Authentication Code',
                            'body' => $data['message'],
                            'message' => 'Multifactor authentication login code is '.$data["otp"].' from RCARE.',
                            'button_text' => '',
                            'button_url' => '',
                            'link' => 'Team Renova'
                        ];
                
                        Mail::to($data['email'])->send(new DemoMail($mailData));

                            $type = 'MFA';
                            $email_msg  = '<h5>Hi  ' . $data["name"].', </h5> 
                            <p>Multifactor authentication forget password code is '.$data["otp"].' from RCARE "</p> 
                            <a>Team Renova</a>';
                            $content = strip_tags($email_msg);
                            $sent_type = 'email'; 
                            $sent_to = $email;
                            $message_id ='';
                            $msg_status='';
                            $this->setTextingLog($id,$type,$sent_type,$content,$sent_to,$message_id,$msg_status);
                            $response['email_otp']='y';
                            $response['message_id'] = $sid;
                       
                    }catch(\Exception $e){ 
                        // dd($e); 
                        $response['email_otp']='n';
                        $response['message_id'] = '';

                    }
                        if($response['mob_otp']=='n' && $response['email_otp']=='n'){
                            return array(['sucsses'=>'n','message_id'=>$response['message_id'],'msg'=>'We are not able to send the authentication code due to technical issues in text and email services. Please contact Admin to disable the Multifactor Authentication temporarily.']);
                        }else if($response['mob_otp']=='n' && $response['email_otp']=='y'){
                            return array(['mob'=>'/'.$emailID->email,
                            'userid_otp'=>$id,'sucsses'=>'y','message_id'=>$response['message_id'],'msg'=>'We are not able to send code on your phone but code has been sent on your email.']);
                        }else if($response['mob_otp']=='y' && $response['email_otp']=='n'){
                            return array(['mob'=>$receiverNumber.'/',
                            'userid_otp'=>$id,'sucsses'=>'y','message_id'=>$response['message_id'],'msg'=>'We are not able to send code on your email but code has been sent on phone']);
                        }
                        else if($response['mob_otp']=='y' && $response['email_otp']=='y'){ 
                            return array(['mob'=>$receiverNumber.'/'.$emailID->email, 
                            'userid_otp'=>$id,'sucsses'=>'y','message_id'=>$response['message_id'],'msg'=>'OTP has been sent on your phone and on your email']);
                        }else{

                        }
                        // else{ 
                        //     return array(['mob'=>$receiverNumber.'/'.$emailID->email, 
                        //     'userid_otp'=>$id,'sucsses'=>'y','message_id'=>$response['message_id'],'msg'=>'OTP has been sent on your phone and on your email']);
                        // }
                }
            }
            //else{
            //      return array(['sucsses'=>"n",'msg'=>"Please ask administrator to add your Contact Number 
            //      and  Email Id for Multifactor authentication."]);
            // }
        }//return redirect()->route('2fa.index');
        catch (Exception $e) { 
           // info("Error: ". $e->getMessage()); 
           return array(['sucsses'=>"n",'msg'=>"Invalid number. Please ask administrator to add your contact details for multifactor authentication."]);
           
        } 
    }
    public function checkMfaTextstatus($msg_id){   
        return MfaTextingLog::where('message_id',sanitizeVariable($msg_id))->get();
    }
    public function setTextingLog($id,$type,$sent_type,$content,$sent_to,$message_id,$msg_status){
        // $created_by = Auth::guard('users')->user()->id;
        MfaTextingLog::create( 
            [
            'user_id' => $id,
            'sent_to' =>$sent_to,
            'type'=>$type,
            'sent_type' =>$sent_type,
            'content' => strip_tags($content),
            'created_by' =>$id,
            'updated_by' =>$id,
            'message_id' =>$message_id,
            'status' =>$msg_status
            ]);
    }

    public function resend(Request $request)
    {
      //dd($request->userid);
      //$generateOtp= $this->generateCode($request->userid);
        $id = sanitizeVariable($request->userid); 
        $mfa_method =  sanitizeVariable($request->input('mfa_method'));
		//dd($mfa_method);
        if($mfa_method==1){
            $user_level_email = 1;
            $user_level_sms =''; 
        }else if($mfa_method==2){
            $user_level_sms = 1; 
            $user_level_email ='';
        }
        else{
            $user_level_sms =1;
            $user_level_email = 1;
        }
		
		
        $generateOtp= $this->generateCode($id,$user_level_sms,$user_level_email);
        if($generateOtp[0]['sucsses']=='n'){
            // echo "string5.2";
            $response['error']=$generateOtp[0]['msg'];
        }else{ 
            // echo "string5.3"; 
            $response['success']='y';
            $response['url'] = 'login-otp';
            $response['mob'] = $generateOtp[0]['mob']; 
        }
        return $generateOtp; 
        // echo '{ "data":[ '. json_encode( $response) .']}'; 
      
    }


    public function resendAnotherMethod(Request $request)
    {
        $id = sanitizeVariable($request->userid); 
        $mfa_method =  sanitizeVariable($request->input('mfa_method'));
        if($mfa_method==1){
            $user_level_sms = 1;
            $user_level_email ='';
        }else if($mfa_method==2){
            $user_level_email = 1;
            $user_level_sms ='';
        }
        else{
            $user_level_sms ='';
            $user_level_email = '';
        }

        $generateOtp = $this->generateCode($id,$user_level_sms,$user_level_email);
        if($generateOtp[0]['sucsses']=='n'){
            // echo "string5.2";
            $response['error']=$generateOtp[0]['msg'];
        }else{ 
            // echo "string5.3"; 
            $response['success']='y'; 
            $response['url'] = 'login-otp';
            $response['mob'] = $generateOtp[0]['mob']; 
        }

        return $generateOtp; 
        // echo '{ "data":[ '. json_encode( $response) .']}'; 
      
    }

    public function login(Request $request)
    {  //dd($password_attempts);  
        $base_url = strtolower(URL::to('/').'/rcare-login'); 
        // dd($base_url); 
        $remember    =    sanitizeVariable($request->input('remember'));
        $credentials =    sanitizeVariable($request->only('email', 'password')); 
        $email       =    sanitizeVariable($request->input('email'));
        $password    =    sanitizeVariable($request->input('password')); 
        $role = 2; 
        // $configTZ    = config('app.timezone');
		// $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
        $timezone    =    !empty(sanitizeVariable($request->input('timezone')))? sanitizeVariable($request->input('timezone')) : config('app.timezone');
        $response = array();
        $DomainFeatures= DomainFeatures::where('features','2FA')
        ->where(DB::raw('lower(url)'), $base_url)
        // ->where('url',$base_url) 
        // ->where('status',1) 
        ->first(); 
        // dd($DomainFeatures);
        // dd($DomainFeatures->password_attempts); 
        $no_of_days  =  !empty($DomainFeatures) ? $DomainFeatures->no_of_days:''; 
        $pwd_attempts = !empty($DomainFeatures) ? $DomainFeatures->password_attempts:'3';
        $user_level_sms = sanitizeVariable($request->input('otp_text'));
        $user_level_email = sanitizeVariable($request->input('otp_email'));
            $eid_exists = Users::where('email',$email)->exists(); 
            // dd($eid_exists); 
            if($eid_exists == true){ 
                $eid = Users::where('email',$email)->first();
                $id = $eid['id'];
                $chk_attempts = Users::where('id',$id)->first();
                $userlockStatus = Users::where('email',$email)->where('id', $id )->first();
                $today = carbon::now();//date("m-d-Y");
                if($userlockStatus->otp_date=='' || $userlockStatus->otp_date==null|| $userlockStatus->otp_date=='null'){
                        $current_date='';
                    }else{
                       $current_date = Carbon::parse($userlockStatus->otp_date)->addDay($no_of_days); 
                    }
                ($chk_attempts->max_attempts =="" && $chk_attempts->max_attempts ==null && $chk_attempts->max_attempts ==0) ? $count_attempts=1 : $count_attempts=$chk_attempts->max_attempts+1;
                // dd(Auth::guard('renCore_user')->attempt($credentials,$remember));
                if(Auth::guard('renCore_user')->attempt($credentials,$remember)==true){
                    if($DomainFeatures!=null){
                        // dd('saaa');  
                        $domainFeatures_status = DomainFeatures::where('features','2FA')
                        ->whereRaw('LOWER(url) like ?', [$base_url])
                        //DomainFeatures::where('features','2FA')->where('url',$base_url)
                        ->where('status',1)->first();
                        if(isset($domainFeatures_status)){
                            if($userlockStatus->status==0){
                                $this->setLogInLog_renCore($id,$email);
                                 echo "string1";
                                $response['success']='n';
                                $response['url']=''; 
                                $response['error']='You are temparary deactivated. Please contact to admin.';
                            }
                            if($userlockStatus->block_unblock_status==1){ // block by otp
                                $this->setLogInLog_renCore($id,$email);
                                // echo "string1";
                                $response['success']='n'; 
                                $response['url']=''; 
                                $response['error']='You are temparary block. Please contact to admin.';
                            }
                            else if($userlockStatus->status==2){ //to chk user block or not
                                // echo "string2";
                                $userlockStatus = Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>0]);
                                $this->setLogInLog_renCore($id,$email);
                                $response['success']='n';
                                $response['url']='';
                                $response['error']='You are deactivated user. Please contact to admin.';
                            }else if($userlockStatus->temp_lock_time!="" && $userlockStatus->temp_lock_time!=null){ //if temp_lock_time
                                ($chk_attempts->max_attempts =="" && $chk_attempts->max_attempts ==null && $chk_attempts->max_attempts ==0) ?
                                $count_attempts=1 : $count_attempts=$chk_attempts->max_attempts+1;
                                // $userExist = Users::where('email',$email)->where('password', $password)->exists();
                                // dd($userExist);  
                                $h1=strtotime($userlockStatus->temp_lock_time);
                                $h2=strtotime(Carbon::now());
                                $h=$h2-$h1; 
                                $m=$h/60;
                                // $remaining_time= round(3-$m, 2);

                                $block_time = $DomainFeatures->block_time;
                                $remaining_time= round($block_time-$m, 2);

                                $this->setLogInLog_renCore($id,$email);
                                if($m>$remaining_time){  
                                Users::where('email',$email)->where('id', $id )->update(['temp_lock_time' => null,'max_attempts' =>0,'blocked_via'=>'']);
                                // if($userExist!=true){
                                //     dd('adasdasdsa'); 
                                //     Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>$count_attempts]);
                                //     $response['error']='Incorrect Username and Password';
                                // }  
                                }else{
                                    $response['error']="Too many login attempts. Please try again in $remaining_time minutes.";
                                }
                            // }
                            // else if($userlockStatus->max_attempts > $DomainFeatures->password_attempts && ($userlockStatus->temp_lock_time=="" && $userlockStatus->temp_lock_time==null ||$userlockStatus->temp_lock_time=='00:00:00') ){ 
                            //     echo "string4".$userlockStatus->max_attempts .'==='. $DomainFeatures->password_attempts;
                            //     Users::where('email',$email)->where('id',$id )->update(['max_attempts' =>0,'temp_lock_time' => Carbon::now()]);
                            //     $this->setLogInLog_renCore($id,$email);
                            //     $response['error']="Too many login attempts. Please try again after 3 minutes.";
                            }else if(($current_date=='')&& $pwd_attempts > $userlockStatus->max_attempts 
                            && ($userlockStatus->block_unblock_status==0 ||$userlockStatus->block_unblock_status=='') 
                            && ($userlockStatus->temp_lock_time==null ||$userlockStatus->temp_lock_time=='00:00:00')){
                                //otp date is empty....
                                $this->setLogInLog_renCore($id,$email);
                                        // $this->clearLoginAttempts($request);
                                    Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>0]);
                                    // $generateOtp= $this->generateCode($id);
                                    $generateOtp= $this->generateCode($id,$user_level_sms,$user_level_email);
                                    // dd($generateOtp);
                                        if($generateOtp[0]['sucsses']=='n'){
                                            // echo "string5.2";
                                            $response['error']=$generateOtp[0]['msg'];
                                        }else{ 
                                            // echo "string5.3";
                                            $response['success']='y';
                                            $response['url'] = 'login-otp';
                                            $response['mob'] = $generateOtp[0]['mob'];
                                            $response['userid_otp'] = $generateOtp[0]['userid_otp'];
                                            $response['timezone'] = $timezone;
                                            $response['role'] = $role;
                                            $response['error']='';
                                            $response['message_id']=$generateOtp[0]['message_id'];
                                        } 
                            }else if(($current_date->gt($today)==true) && $pwd_attempts > $userlockStatus->max_attempts 
                            && ($userlockStatus->block_unblock_status==0 ||$userlockStatus->block_unblock_status=='')
                            && ($userlockStatus->temp_lock_time==null ||$userlockStatus->temp_lock_time=='00:00:00')){
                                //once login between 24 hrs not required pwd....
                                // dd('haaa88888');
                                $roleId = Auth::guard('renCore_user')->user()->role;
                                $role_details = RolesTypes::userRoleType($roleId);   
                                $role_type = $role_details[0]->role_type;
                                    session()->put(['email'=>Auth::guard('renCore_user')->user()->email,'userid'=>Auth::guard('renCore_user')->user()->id, 'status'=>Auth::guard('renCore_user')->user()->status,'role'=>Auth::guard('renCore_user')->user()->role,'f_name'=>Auth::guard('renCore_user')->user()->f_name,'l_name'=>Auth::guard('renCore_user')->user()->l_name,'profile_img'=>Auth::guard('renCore_user')->user()->profile_img,
                                    'user_type'=>$role,'org_id'=>Auth::guard('renCore_user')->user()->org_id, 'timezone'=>$timezone, 'role_type'=>$role_type]);
                                    $userStatus = Auth::guard('renCore_user')->user()->status;
                                    $id=Auth::guard('renCore_user')->user()->id;
                                    if($userStatus=='1'){
                                        $this->setAfterLogInLog_renCore($id,$email);
                                        // $this->clearLoginAttempts($request);
                                        $response['success']='y';
                                        $response['url'] = 'patients/worklist';
                                        $response['error']='';
                                        // return redirect()->route("org_users_list");
                                    }else{
                                        ($chk_attempts->max_attempts =="" && $chk_attempts->max_attempts ==null && $chk_attempts->max_attempts ==0) ? $count_attempts=1 : $count_attempts=$chk_attempts->max_attempts+1;
                                        $this->setLogInLog_renCore($id,$email); 
                                        if($userlockStatus->max_attempts >= $DomainFeatures->password_attempts && ($userlockStatus->temp_lock_time=="" && $userlockStatus->temp_lock_time==null ||$userlockStatus->temp_lock_time=='00:00:00') ){ 
                                        //echo "string".$userlockStatus->max_attempts.'=='.$DomainFeatures->password_attempts;
                                        $this->setLogInLog_renCore($id,$email);
                                        Users::where('email',$email)->where('id',$id )->update(['temp_lock_time' => Carbon::now()]);
                                        $h1=strtotime($userlockStatus->temp_lock_time);
                                        $h2=strtotime(Carbon::now());
                                        $h=$h2-$h1; 
                                        $m=$h/60;
                                        $block_time = $DomainFeatures->block_time;
                                        $remaining_time= round($block_time-$m, 2);
                                        $response['error']="Too many login attempts. Please try again after $remaining_time minutes.";
                                        } 
                                        Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>$count_attempts]);
                                        $response['error']='Incorrect username or password. Please try again.';
                                    }
                
                            }else{ 
                                $userStatus = Auth::guard('renCore_user')->user()->status;
                                $id=Auth::guard('renCore_user')->user()->id;
                                    if($userStatus=='1'){
                                        $this->setLogInLog_renCore($id,$email);
                                        // $this->clearLoginAttempts($request);
                                    Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>0]);
                                    // $generateOtp= $this->generateCode($id);
                                    $generateOtp= $this->generateCode($id,$user_level_sms,$user_level_email);
                                    // dd($generateOtp);
                                        if($generateOtp[0]['sucsses']=='n'){
                                            // echo "string5.2";
                                            $response['error']=$generateOtp[0]['msg'];
                                        }else{ 
                                            // echo "string5.3";
                                            $response['success']='y'; 
                                            $response['url'] = 'login-otp';
                                            $response['mob'] = $generateOtp[0]['mob'];
                                            $response['userid_otp'] = $generateOtp[0]['userid_otp'];
                                            $response['timezone'] = $timezone;
                                            $response['role'] = $role;
                                            $response['error']='';
                                            $response['message_id']=$generateOtp[0]['message_id']; 
                                        }
                                    }else{
                                        ($chk_attempts->max_attempts =="" && $chk_attempts->max_attempts ==null && $chk_attempts->max_attempts ==0) ? $count_attempts=1 : $count_attempts=$chk_attempts->max_attempts+1;
                                        Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>$count_attempts]);
                                        $this->setLogInLog_renCore($id,$email); 
                                        if($userlockStatus->max_attempts >= $DomainFeatures->password_attempts && ($userlockStatus->temp_lock_time=="" && $userlockStatus->temp_lock_time==null ||$userlockStatus->temp_lock_time=='00:00:00') ){ 
                                        // echo "ssss".$userlockStatus->max_attempts.'='.$DomainFeatures->password_attempts;
                                            Users::where('email',$email)->where('id',$id )->update(['temp_lock_time' => Carbon::now()]);
                                            $h1=strtotime($userlockStatus->temp_lock_time);
                                            $h2=strtotime(Carbon::now());
                                            $h=$h2-$h1; 
                                            $m=$h/60;
                                            $block_time = $DomainFeatures->block_time;
                                            $remaining_time= round($block_time-$m, 2);
                                            $response['error']="Too many login attempts. Please try again after $remaining_time minutes.";
                                        } 
                                            $response['success']='n';
                                            $response['url']='';
                                            $response['error']='Incorrect username or password. Please try again.';
                                        // return redirect()->route('rcare-login')->with('message','Incorrect username or password. Please try again.');
                                    }
                            }
                        }else{//without 2FA
                                //  dd('saaa-else');
                            $userlockStatus = Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>0]);
                                session()->put(['email'=>Auth::guard('renCore_user')->user()->email,'userid'=>Auth::guard('renCore_user')->user()->id, 'status'=>Auth::guard('renCore_user')->user()->status,'role'=>Auth::guard('renCore_user')->user()->role,'f_name'=>Auth::guard('renCore_user')->user()->f_name,'l_name'=>Auth::guard('renCore_user')->user()->l_name,'profile_img'=>Auth::guard('renCore_user')->user()->profile_img,
                                'user_type'=>$role,'org_id'=>Auth::guard('renCore_user')->user()->org_id, 'timezone'=>$timezone]);
                                $userStatus = Auth::guard('renCore_user')->user()->status;
                                // dd($userStatus)
                                $id=Auth::guard('renCore_user')->user()->id;
                                if($userStatus=='1'){
                                    // dd('in userstatus');
                                    $this->setAfterLogInLog_renCore($id,$email);
                                    // $this->clearLoginAttempts($request);
                                        $response['success']='y';
                                        $response['url'] = 'patients/worklist';
                                        $response['error']='';
                                }else{
                                    // dd('ELSE-else');
                                    $this->setLogInLog_renCore($id,$email);
                                    Auth::logout();
                                    $response['success']='n';
                                    $response['url']='';
                                    $response['error']='You are temporary blocked. please contact to admin';
                                } 
                        }
                    }else{
                        $this->setLogInLog_renCore($id,$email);
                        // Auth::logout();
                       /* $response['success']='n';
                        $response['url']='';
                        $response['error']='Your URL Domain Not Valid';*/
                        $response['success']='y';
                        $response['url'] = 'patients/worklist';
                        $response['error']='';
                    }
                }else{
                    ($chk_attempts->max_attempts =="" && $chk_attempts->max_attempts ==null && $chk_attempts->max_attempts ==0) ? $count_attempts=1 : $count_attempts=$chk_attempts->max_attempts+1;
                        $this->setLogInLog_renCore($id,$email);
                        $this->setLogInLog($id,$email);
                        // $this->incrementLoginAttempts($request);
                        if($userlockStatus->max_attempts >= $pwd_attempts && ($userlockStatus->temp_lock_time=="" && $userlockStatus->temp_lock_time==null ||$userlockStatus->temp_lock_time=='00:00:00') ){ 
                            Users::where('email',$email)->where('id',$id )->update(['temp_lock_time' => Carbon::now()]);
                            $h1=strtotime($userlockStatus->temp_lock_time);
                            $h2=strtotime(Carbon::now());
                            $h=$h2-$h1; 
                            $m=$h/60;
                            $block_time = $DomainFeatures->block_time;
                            // dd($block_time);
                            // $remaining_time= round($block_time-$m, 2);
                            // dd($block_time-$m, 2);
                            $response['error']="Too many login attempts. Please try again after $block_time minutes.";
                        }else if($userlockStatus->max_attempts >= $pwd_attempts && ($userlockStatus->temp_lock_time!="" && $userlockStatus->temp_lock_time!=null ||$userlockStatus->temp_lock_time!='00:00:00') ){
                            $h1=strtotime($userlockStatus->temp_lock_time);
                            $h2=strtotime(Carbon::now());
                            $h=$h2-$h1;
                            $m=$h/60;
                            // $remaining_time= round(3-$m, 2);
                            // echo $m.'<=>'.$remaining_time;die;
                            $block_time = $DomainFeatures->block_time;
                            $remaining_time= round($block_time-$m, 2);

                            if($m>$remaining_time){
                             Users::where('email',$email)->where('id', $id )->update(['temp_lock_time' => null,'max_attempts' =>0]);
                            }else{
                              $response['error']="Too many login attempts. Please try again in $remaining_time minutes.";
                            }
                        }else{ 
                            Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>$count_attempts]);
                            $response['error']='Incorrect username or password. Please try again.';
                        }
                }
            }else{
                $response['success']='n';
                $response['url']='';
                $response['error']='Incorrect Username.';
            }

        echo '{ "data":[ '. json_encode( $response) .']}';  
    }





    public function loginVerify(Request $request){ //not in use
        $remember    =    sanitizeVariable($request->input('remember'));
        $credentials =    sanitizeVariable($request->only('email', 'password')); 
        $email       =    sanitizeVariable($request->input('email'));
        $password    =    sanitizeVariable($request->input('password')); 
        $role = 2; 
        $timezone    =    sanitizeVariable($request->input('timezone'));
        // dd($timezone);
        $response = array();
        $DomainFeatures= DomainFeatures::where('features','2FA')->first();
        $domainFeatures_status = $DomainFeatures->status;
        $no_of_days  =  $DomainFeatures->no_of_days;
        $pwd_attempts = $DomainFeatures->password_attempts;
        $eid_exists = Users::where('email',$email)->exists();
        // dd($eid_exists);
        if($email!='' && $eid_exists==true){

                // session()->put(['timezone'=>$timezone]);

                $eid = Users::where('email',$email)->first();
                $id = $eid['id'];
                $chk_attempts = Users::where('id',$id)->first();
                $userlockStatus = Users::where('email',$email)->where('id', $id )->first();
                $today = carbon::now();//date("m-d-Y");
                ($chk_attempts->max_attempts =="" && $chk_attempts->max_attempts ==null && $chk_attempts->max_attempts ==0) ? $count_attempts=1 : $count_attempts=$chk_attempts->max_attempts+1;
                if($userlockStatus->otp_date=='' || $userlockStatus->otp_date==null|| $userlockStatus->otp_date=='null'){
                        $current_date='';
                }else{
                       $current_date = Carbon::parse($userlockStatus->otp_date)->addDay($no_of_days); 
                }
                if($domainFeatures_status==1 && $current_date==''){
                        $response['success']='otp_screen';
                }elseif($domainFeatures_status==1 && $current_date->gt($today)==true){
                        $response['success']='login_screen';
                }else{
                    $response['success']='otp_screen';
                }
        }elseif($email=='' || $password==''){
            $response['success']='credential empty';
            $response['url']='';
            $response['error']='Please Enter Login Credential.';
        }else{
            $response['success']='Incorrect Username.';
            // $response['url']='';
            // $response['error']='Incorrect Username.';
        }
        echo '{ "data":[ '. json_encode( $response) .']}';  
    }
    // public function loginVerify(Request $request){ --cmnted on 3rd march 22
    //     $remember    =    sanitizeVariable($request->input('remember'));
    //     $credentials =    sanitizeVariable($request->only('email', 'password')); 
    //     $email       =    sanitizeVariable($request->input('email'));
    //     $password    =    sanitizeVariable($request->input('password')); 
    //     $role = 2; 
    //     $timezone    =    sanitizeVariable($request->input('timezone'));
    //     $response = array();
    //     $DomainFeatures= DomainFeatures::where('features','2FA')->first();
    //     $domainFeatures_status = $DomainFeatures->status;
    //     $no_of_days  =  $DomainFeatures->no_of_days;
    //     $pwd_attempts = $DomainFeatures->password_attempts;
    //     $eid_exists = Users::where('email',$email)->exists();
    //         // dd($eid_exists);
    //         if($eid_exists === true ){
    //             $eid = Users::where('email',$email)->first();
    //             $id = $eid['id'];
    //             $chk_attempts = Users::where('id',$id)->first();
    //             $userlockStatus = Users::where('email',$email)->where('id', $id )->first();
    //             $today = carbon::now();//date("m-d-Y");
    //             ($chk_attempts->max_attempts =="" && $chk_attempts->max_attempts ==null && $chk_attempts->max_attempts ==0) ? $count_attempts=1 : $count_attempts=$chk_attempts->max_attempts+1;
    //             if($userlockStatus->otp_date=='' || $userlockStatus->otp_date==null|| $userlockStatus->otp_date=='null'){
    //                     $current_date='';
    //             }else{
    //                    $current_date = Carbon::parse($userlockStatus->otp_date)->addDay($no_of_days); 
    //             }
    //             if($domainFeatures_status==1 && $current_date==''){
    //                     $response['success']='otp_screen';
    //             }elseif($domainFeatures_status==1 && $current_date->gt($today)==true){
    //                     $response['success']='login_screen';
    //             }else{
    //                 $response['success']='otp_screen';
    //             }                
    //         }else{
    //             $response['success']='n';
    //             $response['url']='';
    //             $response['error']='Incorrect Username.';
    //         }

    //     echo '{ "data":[ '. json_encode( $response) .']}';  
    // }
    // public function loginWithOtp(Request $request){  //not in use
    //     $remember    =    sanitizeVariable($request->input('remember'));
    //     $credentials =    sanitizeVariable($request->only('email', 'password')); 
    //     $email       =    sanitizeVariable($request->input('email'));
    //     $password    =    sanitizeVariable($request->input('password')); 
    //     $role = 2; 
    //     $timezone    =    sanitizeVariable($request->input('timezone'));
    //     $response = array();
    //     $user_level_sms = sanitizeVariable($request->input('otp_text'));
    //     $user_level_email = sanitizeVariable($request->input('otp_email'));
    //     $DomainFeatures= DomainFeatures::where('features','2FA')->first();
    //     $no_of_days  =  $DomainFeatures->no_of_days;
    //     $pwd_attempts = $DomainFeatures->password_attempts;
    //     $eid_exists = Users::where('email',$email)->exists();
    //         if($eid_exists === true){
    //             // echo "string";
    //             $eid = Users::where('email',$email)->first();
    //             $id = $eid['id'];
    //             $chk_attempts = Users::where('id',$id)->first();
    //             $userlockStatus = Users::where('email',$email)->where('id', $id )->first();
    //             $today = carbon::now();//date("m-d-Y");
    //             ($chk_attempts->max_attempts =="" && $chk_attempts->max_attempts ==null && $chk_attempts->max_attempts ==0) ? $count_attempts=1 : $count_attempts=$chk_attempts->max_attempts+1;
    //             if(Auth::guard('renCore_user')->attempt($credentials,$remember)==true){
    //                 if($userlockStatus->status==0){ //chck user Inctive
    //                     // echo "string1";
    //                     $this->setLogInLog_renCore($id,$email);
    //                     $response['success']='n';
    //                     $response['url']=''; 
    //                     $response['error']='You are temparary deactivated. Please contact to admin.';
    //                 }else if($userlockStatus->block_unblock_status==1){ // block by otp
    //                     // echo "string2";
    //                     $this->setLogInLog_renCore($id,$email);
    //                     $response['success']='n';
    //                     $response['url']=''; 
    //                     $response['error']='You are temparary block. Please contact to admin.';
    //                 }else if($userlockStatus->status==2){ //to chk user permanent block or not
    //                     // echo "string3";
    //                     $userlockStatus = Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>0]);
    //                     $this->setLogInLog_renCore($id,$email);
    //                     $response['success']='n';
    //                     $response['url']='';
    //                     $response['error']='You are deactivated user. Please contact to admin.';
    //                 }else if($userlockStatus->temp_lock_time!="" && $userlockStatus->temp_lock_time!=null){ //if temp_lock_time
    //                 // echo "string4";
    //                     ($chk_attempts->max_attempts =="" && $chk_attempts->max_attempts ==null && $chk_attempts->max_attempts ==0) ? $count_attempts=1 : $count_attempts=$chk_attempts->max_attempts+1;
    //                     $userExist = Users::where('email',$email)->where('password', $password)->exists();
    //                     $h1=strtotime($userlockStatus->temp_lock_time);
    //                     $h2=strtotime(Carbon::now());
    //                     $h=$h2-$h1;
    //                     $m=$h/60;
    //                     $remaining_time= round(3-$m, 2);
    //                     $this->setLogInLog_renCore($id,$email);
    //                     if($m>3){
    //                       Users::where('email',$email)->where('id', $id )->update(['temp_lock_time' => null,'max_attempts' =>0]);
    //                       if($userExist!=true){
    //                         Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>$count_attempts]);
    //                          $response['error']='Incorrect Username and Password';
    //                       }
    //                     }else{
    //                       $response['error']="Too many login attempts. Please try again in $remaining_time minutes.";
    //                     }
    //                 }else if($userlockStatus->max_attempts >= $DomainFeatures->password_attempts && ($userlockStatus->temp_lock_time=="" && $userlockStatus->temp_lock_time==null ||$userlockStatus->temp_lock_time=='00:00:00') ){ 
    //                     $this->setLogInLog_renCore($id,$email);
    //                     Users::where('email',$email)->where('id',$id )->update(['temp_lock_time' => Carbon::now()]);
    //                     $response['error']="Too many login attempts. Please try again after 3 minutes."; 
    //                 }
    //                 // else if($pwd_attempts > $userlockStatus->max_attempts && ($userlockStatus->block_unblock_status==0 ||$userlockStatus->block_unblock_status=='') && ($userlockStatus->temp_lock_time==null ||$userlockStatus->temp_lock_time=='00:00:00')){//otp date is empty....
    //                 //     // echo "string5";
    //                 //     $this->setLogInLog_renCore($id,$email);
    //                 //     Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>0]);
    //                 //     $generateOtp= $this->generateCode($id,$mfa_status);
    //                 //     if($generateOtp[0]['sucsses']=='n'){
    //                 //         $response['error']=$generateOtp[0]['msg'];
    //                 //     }else{
    //                 //         $response['success']='y';
    //                 //         $response['url'] = 'login-otp';
    //                 //         $response['mob'] = $generateOtp[0]['mob'];
    //                 //         $response['userid_otp'] = $generateOtp[0]['userid_otp'];
    //                 //         $response['timezone'] = $timezone;
    //                 //         $response['role'] = $role;
    //                 //         $response['error']='';
    //                 //     } 
    //                 // }
    //                 else{
    //                     // echo "do something"; 
    //                     $this->setLogInLog_renCore($id,$email);
    //                     Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>0]);
    //                     $generateOtp= $this->generateCode($id,$user_level_sms,$user_level_email);
    //                     if($generateOtp[0]['sucsses']=='n'){
    //                         $response['error']=$generateOtp[0]['msg'];
    //                     }else{
    //                         $response['success']='y';
    //                         $response['url'] = 'login-otp';
    //                         $response['mob'] = $generateOtp[0]['mob'];
    //                         $response['userid_otp'] = $generateOtp[0]['userid_otp'];
    //                         $response['timezone'] = $timezone;
    //                         $response['role'] = $role;
    //                         $response['error']='';
    //                         $response['message_id']=$generateOtp[0]['message_id'];
    //                     }
    //                 }
    //             }else{
    //                 ($chk_attempts->max_attempts =="" && $chk_attempts->max_attempts ==null && $chk_attempts->max_attempts ==0) ? $count_attempts=1 : $count_attempts=$chk_attempts->max_attempts+1;
    //                 $this->setLogInLog_renCore($id,$email);
    //                 $this->setLogInLog($id,$email);
    //                 // $this->incrementLoginAttempts($request);
    //                 if($userlockStatus->max_attempts >= $DomainFeatures->password_attempts && ($userlockStatus->temp_lock_time=="" && $userlockStatus->temp_lock_time==null ||$userlockStatus->temp_lock_time=='00:00:00') ){ 
    //                     Users::where('email',$email)->where('id',$id )->update(['temp_lock_time' => Carbon::now()]);
    //                     $response['error']="Too many login attempts. Please try again after 3 minutes.";
    //                 }else if($userlockStatus->max_attempts >= $DomainFeatures->password_attempts && ($userlockStatus->temp_lock_time!="" && $userlockStatus->temp_lock_time!=null ||$userlockStatus->temp_lock_time!='00:00:00') ){
    //                     $h1=strtotime($userlockStatus->temp_lock_time);
    //                     $h2=strtotime(Carbon::now());
    //                     $h=$h2-$h1;
    //                     $m=$h/60;
    //                     $remaining_time= round(3-$m, 2);
    //                     // echo $m.'<=>'.$remaining_time;die;
    //                     if($m>3){
    //                      Users::where('email',$email)->where('id', $id )->update(['temp_lock_time' => null,'max_attempts' =>0]);
    //                     }else{
    //                       $response['error']="Too many login attempts. Please try again in $remaining_time minutes.";
    //                     }
    //                 }else{ 
    //                     Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>$count_attempts]);
    //                     $response['error']='Incorrect username or password. Please try again.';
    //                 }
    //             }   
    //         }else{
    //             $response['success']='n';
    //             $response['url']='';
    //             $response['error']='Incorrect Username.';
    //         } 
    //     echo '{ "data":[ '. json_encode( $response) .']}';  
    // }
    // public function loginWithoutOtp(Request $request){ //not in use

    //     $remember    =    sanitizeVariable($request->input('remember'));
    //     $credentials =    sanitizeVariable($request->only('email', 'password')); 
    //     $email       =    sanitizeVariable($request->input('email'));
    //     $password    =    sanitizeVariable($request->input('password')); 
    //     $role = 2; 
    //     $timezone    =    sanitizeVariable($request->input('timezone'));
    //     $response = array();
    //     $DomainFeatures= DomainFeatures::where('features','2FA')->first();
    //     $no_of_days  =  $DomainFeatures->no_of_days;
    //     $pwd_attempts = $DomainFeatures->password_attempts;
    //     $eid_exists = Users::where('email',$email)->exists();

    //         // dd($eid_exists);
    //         if($eid_exists === true){
    //             // echo "string";
    //             $eid = Users::where('email',$email)->first();
    //             $id = $eid['id'];
    //             $chk_attempts = Users::where('id',$id)->first();
    //             $userlockStatus = Users::where('email',$email)->where('id', $id )->first();
    //             $today = carbon::now();//date("m-d-Y");
    //             ($chk_attempts->max_attempts =="" && $chk_attempts->max_attempts ==null && $chk_attempts->max_attempts ==0) ? $count_attempts=1 : $count_attempts=$chk_attempts->max_attempts+1;
    //             if(Auth::guard('renCore_user')->attempt($credentials,$remember)==true){
    //                 if($userlockStatus->status==0){ //chck user Inctive
    //                     // echo "string1";
    //                     $this->setLogInLog_renCore($id,$email);
    //                     $response['success']='n';
    //                     $response['url']=''; 
    //                     $response['error']='You are temparary deactivated. Please contact to admin.';
    //                 }else if($userlockStatus->block_unblock_status==1){ // block by otp
    //                     // echo "string2";
    //                     $h1=strtotime($userlockStatus->temp_lock_time);
    //                     $h2=strtotime(Carbon::now());
    //                     $h=$h2-$h1;
    //                     $m=$h/60; 
    //                     $remaining_time= round(30-$m, 2);
    //                     $this->setLogInLog_renCore($id,$email);
    //                     $response['success']='n';
    //                     $response['url']=''; 
    //                     if($m>30){
    //                         Users::where('email',$email)->where('id', $id )->update(['temp_lock_time' => null,'max_attempts' =>0]);
    //                         if($userExist!=true){
    //                           Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>$count_attempts]);
    //                            $response['error']='Incorrect Username and Password';
    //                         }
    //                       }else{
    //                         $response['error']="Too many otp attempts. Please try again in $remaining_time minutes.";
    //                       }
    //                     //$response['error']="You are temparary block. Please contact to admin. OR Please try again in $remaining_time minutes.";
    //                 }else if($userlockStatus->status==2){ //to chk user permanent block or not
    //                     // echo "string3";
    //                     $userlockStatus = Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>0]);
    //                     $this->setLogInLog_renCore($id,$email);
    //                     $response['success']='n';
    //                     $response['url']='';
    //                     $response['error']='You are deactivated user. Please contact to admin.';
    //                 }else if($userlockStatus->temp_lock_time!="" && $userlockStatus->temp_lock_time!=null){ //if temp_lock_time
    //                 // echo "string4";
    //                     ($chk_attempts->max_attempts =="" && $chk_attempts->max_attempts ==null && $chk_attempts->max_attempts ==0) ? $count_attempts=1 : $count_attempts=$chk_attempts->max_attempts+1;
    //                     $userExist = Users::where('email',$email)->where('password', $password)->exists();
    //                     $h1=strtotime($userlockStatus->temp_lock_time);
    //                     $h2=strtotime(Carbon::now());
    //                     $h=$h2-$h1;
    //                     $m=$h/60;
    //                     $remaining_time= round(3-$m, 2);
    //                     $this->setLogInLog_renCore($id,$email);
    //                     if($m>3){
    //                       Users::where('email',$email)->where('id', $id )->update(['temp_lock_time' => null,'max_attempts' =>0]);
    //                       if($userExist!=true){
    //                         Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>$count_attempts]);
    //                          $response['error']='Incorrect Username and Password';
    //                       }
    //                     }else{
    //                       $response['error']="Too many login attempts. Please try again in $remaining_time minutes.";
    //                     }
    //                 }else if($userlockStatus->max_attempts >= $DomainFeatures->password_attempts && ($userlockStatus->temp_lock_time=="" && $userlockStatus->temp_lock_time==null ||$userlockStatus->temp_lock_time=='00:00:00') ){ 
    //                     $this->setLogInLog_renCore($id,$email);
    //                     Users::where('email',$email)->where('id',$id )->update(['temp_lock_time' => Carbon::now()]); 
    //                     $response['error']="Too many login attempts. Please try again after 3 minutes."; 
    //                 }else{
    //                     $userlockStatus = Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>0]);
    //                         session()->put(['email'=>Auth::guard('renCore_user')->user()->email,'userid'=>Auth::guard('renCore_user')->user()->id, 'status'=>Auth::guard('renCore_user')->user()->status,'role'=>Auth::guard('renCore_user')->user()->role,'f_name'=>Auth::guard('renCore_user')->user()->f_name,'l_name'=>Auth::guard('renCore_user')->user()->l_name,'profile_img'=>Auth::guard('renCore_user')->user()->profile_img,'user_type'=>$role,'org_id'=>Auth::guard('renCore_user')->user()->org_id, 'timezone'=>$timezone]);
    //                         $userStatus = Auth::guard('renCore_user')->user()->status;
    //                         // dd($userStatus)
    //                         $id=Auth::guard('renCore_user')->user()->id;
    //                         if($userStatus=='1'){
    //                             // dd('in userstatus');
    //                             $this->setAfterLogInLog_renCore($id,$email); 
    //                             // $this->clearLoginAttempts($request);
    //                              $response['success']='y';
    //                              $response['url'] = 'patients/worklist';
    //                              $response['error']='';
    //                         }else{
    //                             $this->setLogInLog_renCore($id,$email);
    //                             Auth::logout();
    //                             $response['success']='n';
    //                             $response['url']='';
    //                             $response['error']='You are temporary blocked. please contact to admin';
    //                         } 
    //                 }
    //             }else{
    //                 ($chk_attempts->max_attempts =="" && $chk_attempts->max_attempts ==null && $chk_attempts->max_attempts ==0) ? $count_attempts=1 : $count_attempts=$chk_attempts->max_attempts+1;
    //                 $this->setLogInLog_renCore($id,$email);
    //                 $this->setLogInLog($id,$email);
    //                 // $this->incrementLoginAttempts($request);
    //                 if($userlockStatus->max_attempts >= $DomainFeatures->password_attempts && ($userlockStatus->temp_lock_time=="" && $userlockStatus->temp_lock_time==null ||$userlockStatus->temp_lock_time=='00:00:00') ){ 
    //                     Users::where('email',$email)->where('id',$id )->update(['temp_lock_time' => Carbon::now()]);
    //                     $response['error']="Too many login attempts. Please try again after 3 minutes.";
    //                 }else if($userlockStatus->max_attempts >= $DomainFeatures->password_attempts && ($userlockStatus->temp_lock_time!="" && $userlockStatus->temp_lock_time!=null ||$userlockStatus->temp_lock_time!='00:00:00') ){
    //                     $h1=strtotime($userlockStatus->temp_lock_time);
    //                     $h2=strtotime(Carbon::now());
    //                     $h=$h2-$h1;
    //                     $m=$h/60;
    //                     $remaining_time= round(3-$m, 2);
    //                     // echo $m.'<=>'.$remaining_time;die;
    //                     if($m>3){
    //                      Users::where('email',$email)->where('id', $id )->update(['temp_lock_time' => null,'max_attempts' =>0]);
    //                     }else{
    //                       $response['error']="Too many login attempts. Please try again in $remaining_time minutes.";
    //                     }
    //                 }else{ 
    //                     Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>$count_attempts]);
    //                     $response['error']='Incorrect username or password. Please try again.';
    //                 }
    //             }   
    //         }else{
    //             $response['success']='n';
    //             $response['url']='';
    //             $response['error']='Incorrect Username.';
    //         }
    //     echo '{ "data":[ '. json_encode( $response) .']}';  
    // } 
    
    // public function login25thnov22(Request $request)
    // {  // dd($password_attempts); 
    //     $remember    =    sanitizeVariable($request->input('remember'));
    //     $credentials =    sanitizeVariable($request->only('email', 'password')); 
    //     $email       =    sanitizeVariable($request->input('email'));
    //     $password    =    sanitizeVariable($request->input('password')); 
    //     $role = 2; 
    //     // $configTZ    = config('app.timezone');
	// 	// $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
    //     $timezone    =    !empty(sanitizeVariable($request->input('timezone')))? sanitizeVariable($request->input('timezone')) : config('app.timezone');
    //     $response = array();
    //     $DomainFeatures= DomainFeatures::where('features','2FA')->first();
    //     $no_of_days  =  $DomainFeatures->no_of_days;
    //     $pwd_attempts = $DomainFeatures->password_attempts;
    //     $user_level_sms = sanitizeVariable($request->input('otp_text'));
    //     $user_level_email = sanitizeVariable($request->input('otp_email'));
    //         $eid_exists = Users::where('email',$email)->exists();
    //         // dd($eid_exists);
    //         if($eid_exists == true){
    //             $eid = Users::where('email',$email)->first();
    //             $id = $eid['id'];
    //             $chk_attempts = Users::where('id',$id)->first();
    //             $userlockStatus = Users::where('email',$email)->where('id', $id )->first();
    //             $today = carbon::now();//date("m-d-Y");
    //             if($userlockStatus->otp_date=='' || $userlockStatus->otp_date==null|| $userlockStatus->otp_date=='null'){
    //                     $current_date='';
    //                 }else{
    //                    $current_date = Carbon::parse($userlockStatus->otp_date)->addDay($no_of_days); 
    //                 }
    //             ($chk_attempts->max_attempts =="" && $chk_attempts->max_attempts ==null && $chk_attempts->max_attempts ==0) ? $count_attempts=1 : $count_attempts=$chk_attempts->max_attempts+1;
    //             // dd(Auth::guard('renCore_user')->attempt($credentials,$remember));
    //             if(Auth::guard('renCore_user')->attempt($credentials,$remember)==true){
    //                 if($DomainFeatures->status==1){ 
    //                     // dd('saaa');
    //                     if($userlockStatus->status==0){
    //                         $this->setLogInLog_renCore($id,$email);
    //                         // echo "string1";
    //                         $response['success']='n';
    //                         $response['url']=''; 
    //                         $response['error']='You are tempararily deactivated. Please contact to admin.';
    //                     }if($userlockStatus->block_unblock_status==1){ // block by otp
    //                         $this->setLogInLog_renCore($id,$email);
    //                         // echo "string1";
    //                         $response['success']='n';
    //                         $response['url']=''; 
    //                         $response['error']='You are tempararily blocked. Please contact to admin.';
    //                     }else if($userlockStatus->status==2){ //to chk user block or not
    //                         // echo "string2";
    //                         $userlockStatus = Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>0]);
    //                         $this->setLogInLog_renCore($id,$email);
    //                         $response['success']='n';
    //                         $response['url']='';
    //                         $response['error']='Your account is deactivated. Please contact to admin.';
    //                     }else if($userlockStatus->temp_lock_time!="" && $userlockStatus->temp_lock_time!=null){ //if temp_lock_time
    //                         ($chk_attempts->max_attempts =="" && $chk_attempts->max_attempts ==null && $chk_attempts->max_attempts ==0) ? $count_attempts=1 : $count_attempts=$chk_attempts->max_attempts+1;
    //                         $userExist = Users::where('email',$email)->where('password', $password)->exists();
    //                         $h1=strtotime($userlockStatus->temp_lock_time);
    //                         $h2=strtotime(Carbon::now());
    //                         $h=$h2-$h1;
    //                         $m=$h/60;
    //                         $remaining_time= round(3-$m, 2);
    //                         $this->setLogInLog_renCore($id,$email);
    //                         if($m>3){
    //                           Users::where('email',$email)->where('id', $id )->update(['temp_lock_time' => null,'max_attempts' =>0]);
    //                           if($userExist!=true){
    //                             Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>$count_attempts]);
    //                              $response['error']='Incorrect Username and Password';
    //                           }
    //                         }else{
    //                           $response['error']="Too many login attempts. Please try again in $remaining_time minutes.";
    //                         }
    //                     // }
    //                     // else if($userlockStatus->max_attempts > $DomainFeatures->password_attempts && ($userlockStatus->temp_lock_time=="" && $userlockStatus->temp_lock_time==null ||$userlockStatus->temp_lock_time=='00:00:00') ){ 
    //                     //     echo "string4".$userlockStatus->max_attempts .'==='. $DomainFeatures->password_attempts;
    //                     //     Users::where('email',$email)->where('id',$id )->update(['max_attempts' =>0,'temp_lock_time' => Carbon::now()]);
    //                     //     $this->setLogInLog_renCore($id,$email);
    //                     //     $response['error']="Too many login attempts. Please try again after 3 minutes.";
    //                     }else if(($current_date=='')&& $pwd_attempts > $userlockStatus->max_attempts && ($userlockStatus->block_unblock_status==0 ||$userlockStatus->block_unblock_status=='') && ($userlockStatus->temp_lock_time==null ||$userlockStatus->temp_lock_time=='00:00:00')){
    //                         //otp date is empty....
    //                           $this->setLogInLog_renCore($id,$email);
    //                                 // $this->clearLoginAttempts($request);
    //                                Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>0]);
    //                                // $generateOtp= $this->generateCode($id);
    //                                $generateOtp= $this->generateCode($id,$user_level_sms,$user_level_email);
    //                                // dd($generateOtp);
    //                                 if($generateOtp[0]['sucsses']=='n'){
    //                                     // echo "string5.2";
    //                                     $response['error']=$generateOtp[0]['msg'];
    //                                 }else{ 
    //                                     // echo "string5.3";
    //                                     $response['success']='y';
    //                                     $response['url'] = 'login-otp';
    //                                     $response['mob'] = $generateOtp[0]['mob'];
    //                                     $response['userid_otp'] = $generateOtp[0]['userid_otp'];
    //                                     $response['timezone'] = $timezone;
    //                                     $response['role'] = $role;
    //                                     $response['error']='';
    //                                 } 
    //                     }else if(($current_date->gt($today)==true)&& $pwd_attempts > $userlockStatus->max_attempts && ($userlockStatus->block_unblock_status==0 ||$userlockStatus->block_unblock_status=='') && ($userlockStatus->temp_lock_time==null ||$userlockStatus->temp_lock_time=='00:00:00')){
    //                         //once login between 24 hrs not required pwd....
    //                         // dd('haaa88888');
	// 						$roleId = Auth::guard('renCore_user')->user()->role;
	// 						$role_details = RolesTypes::userRoleType($roleId);   
	// 		                $role_type = $role_details[0]->role_type;
			
    //                             session()->put(['email'=>Auth::guard('renCore_user')->user()->email,'userid'=>Auth::guard('renCore_user')->user()->id, 'status'=>Auth::guard('renCore_user')->user()->status,'role'=>Auth::guard('renCore_user')->user()->role,'f_name'=>Auth::guard('renCore_user')->user()->f_name,'l_name'=>Auth::guard('renCore_user')->user()->l_name,'profile_img'=>Auth::guard('renCore_user')->user()->profile_img,
    //                             'user_type'=>$role,'org_id'=>Auth::guard('renCore_user')->user()->org_id, 'timezone'=>$timezone, 'role_type'=>$role_type]);
    //                             $userStatus = Auth::guard('renCore_user')->user()->status;
    //                             // dd($userStatus)

    //                             // $userTZ       = Session::get('timezone');
    //                             // dd($userTZ);   


    //                             $id=Auth::guard('renCore_user')->user()->id;
    //                             if($userStatus=='1'){
    //                                 $this->setAfterLogInLog_renCore($id,$email);
    //                                 // $this->clearLoginAttempts($request);
    //                                  $response['success']='y';
    //                                  $response['url'] = 'patients/worklist';
    //                                  $response['error']='';
    //                                  // return redirect()->route("org_users_list");
    //                             }else{
    //                                 ($chk_attempts->max_attempts =="" && $chk_attempts->max_attempts ==null && $chk_attempts->max_attempts ==0) ? $count_attempts=1 : $count_attempts=$chk_attempts->max_attempts+1;
    //                                 $this->setLogInLog_renCore($id,$email); 
    //                                 if($userlockStatus->max_attempts >= $DomainFeatures->password_attempts && ($userlockStatus->temp_lock_time=="" && $userlockStatus->temp_lock_time==null ||$userlockStatus->temp_lock_time=='00:00:00') ){ 
    //                                 //echo "string".$userlockStatus->max_attempts.'=='.$DomainFeatures->password_attempts;
    //                                 $this->setLogInLog_renCore($id,$email);
    //                                 Users::where('email',$email)->where('id',$id )->update(['temp_lock_time' => Carbon::now()]);
    //                                 $response['error']="Too many login attempts. Please try again after 3 minutes.";
    //                                 } 
    //                                 Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>$count_attempts]);
    //                                 $response['error']='Incorrect username or password. Please try again.';
    //                             }
              
    //                     }else{ 
    //                         $userStatus = Auth::guard('renCore_user')->user()->status;
    //                         $id=Auth::guard('renCore_user')->user()->id;
    //                             if($userStatus=='1'){
    //                                 $this->setLogInLog_renCore($id,$email);
    //                                 // $this->clearLoginAttempts($request);
    //                                Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>0]);
    //                                // $generateOtp= $this->generateCode($id);
    //                                $generateOtp= $this->generateCode($id,$user_level_sms,$user_level_email);
    //                                // dd($generateOtp);
    //                                 if($generateOtp[0]['sucsses']=='n'){
    //                                     // echo "string5.2";
    //                                     $response['error']=$generateOtp[0]['msg'];
    //                                 }else{ 
    //                                     // echo "string5.3";
    //                                     $response['success']='y';
    //                                     $response['url'] = 'login-otp';
    //                                     $response['mob'] = $generateOtp[0]['mob'];
    //                                     $response['userid_otp'] = $generateOtp[0]['userid_otp'];
    //                                     $response['timezone'] = $timezone;
    //                                     $response['role'] = $role;
    //                                     $response['error']='';
    //                                 }
    //                             }else{
    //                                 ($chk_attempts->max_attempts =="" && $chk_attempts->max_attempts ==null && $chk_attempts->max_attempts ==0) ? $count_attempts=1 : $count_attempts=$chk_attempts->max_attempts+1;
    //                                 Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>$count_attempts]);
    //                                 $this->setLogInLog_renCore($id,$email); 
    //                                 if($userlockStatus->max_attempts >= $DomainFeatures->password_attempts && ($userlockStatus->temp_lock_time=="" && $userlockStatus->temp_lock_time==null ||$userlockStatus->temp_lock_time=='00:00:00') ){ 
    //                                    // echo "ssss".$userlockStatus->max_attempts.'='.$DomainFeatures->password_attempts;
    //                                     Users::where('email',$email)->where('id',$id )->update(['temp_lock_time' => Carbon::now()]);
    //                                     $response['error']="Too many login attempts. Please try again after 3 minutes.";
    //                                 } 
    //                                     $response['success']='n';
    //                                     $response['url']='';
    //                                     $response['error']='Incorrect username or password. Please try again.';
    //                                 // return redirect()->route('rcare-login')->with('message','Incorrect username or password. Please try again.');
    //                             }
    //                     }
    //                 }else{//without 2FA
    //                     $userlockStatus = Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>0]);
    //                         session()->put(['email'=>Auth::guard('renCore_user')->user()->email,'userid'=>Auth::guard('renCore_user')->user()->id, 'status'=>Auth::guard('renCore_user')->user()->status,'role'=>Auth::guard('renCore_user')->user()->role,'f_name'=>Auth::guard('renCore_user')->user()->f_name,'l_name'=>Auth::guard('renCore_user')->user()->l_name,'profile_img'=>Auth::guard('renCore_user')->user()->profile_img,
    //                         'user_type'=>$role,'org_id'=>Auth::guard('renCore_user')->user()->org_id, 'timezone'=>$timezone]);
    //                         $userStatus = Auth::guard('renCore_user')->user()->status;
    //                         // dd($userStatus)
    //                         $id=Auth::guard('renCore_user')->user()->id;
    //                         if($userStatus=='1'){
    //                             // dd('in userstatus');
    //                             $this->setAfterLogInLog_renCore($id,$email);
    //                             // $this->clearLoginAttempts($request);
    //                              $response['success']='y';
    //                              $response['url'] = 'patients/worklist';
    //                              $response['error']='';
    //                         }else{
    //                             $this->setLogInLog_renCore($id,$email);
    //                             Auth::logout();
    //                             $response['success']='n';
    //                             $response['url']='';
    //                             $response['error']='You are temporary blocked. please contact to admin';
    //                         } 
    //                 }
    //             }else{
    //                 ($chk_attempts->max_attempts =="" && $chk_attempts->max_attempts ==null && $chk_attempts->max_attempts ==0) ? $count_attempts=1 : $count_attempts=$chk_attempts->max_attempts+1;
    //                     $this->setLogInLog_renCore($id,$email);
    //                     $this->setLogInLog($id,$email);
    //                     // $this->incrementLoginAttempts($request);
    //                     if($userlockStatus->max_attempts >= $DomainFeatures->password_attempts && ($userlockStatus->temp_lock_time=="" && $userlockStatus->temp_lock_time==null ||$userlockStatus->temp_lock_time=='00:00:00') ){ 
    //                         Users::where('email',$email)->where('id',$id )->update(['temp_lock_time' => Carbon::now()]);
    //                         $response['error']="Too many login attempts. Please try again after 3 minutes.";
    //                     }else if($userlockStatus->max_attempts >= $DomainFeatures->password_attempts && ($userlockStatus->temp_lock_time!="" && $userlockStatus->temp_lock_time!=null ||$userlockStatus->temp_lock_time!='00:00:00') ){
    //                         $h1=strtotime($userlockStatus->temp_lock_time);
    //                         $h2=strtotime(Carbon::now());
    //                         $h=$h2-$h1;
    //                         $m=$h/60;
    //                         $remaining_time= round(3-$m, 2);
    //                         // echo $m.'<=>'.$remaining_time;die;
    //                         if($m>3){
    //                          Users::where('email',$email)->where('id', $id )->update(['temp_lock_time' => null,'max_attempts' =>0]);
    //                         }else{
    //                           $response['error']="Too many login attempts. Please try again in $remaining_time minutes.";
    //                         }
    //                     }else{ 
    //                         Users::where('email',$email)->where('id', $id )->update(['max_attempts' =>$count_attempts]);
    //                         $response['error']='Incorrect username or password. Please try again.';
    //                     }
    //             }
                
    //         }else{
    //             $response['success']='n';
    //             $response['url']='';
    //             $response['error']='Incorrect Username.';
    //         }

    //     echo '{ "data":[ '. json_encode( $response) .']}';  
    // }
    public function logout()// modified by ashvini 14 jan 2023
    {   
        $role  = session()->get('role'); 
        // if($role == '1')  
        // {
        //     $this->setLogOutLog();
        // }
        // elseif($role == '2')
        // {
        //     $this->setLogOutLog_renCore();
        // }      
        
        $this->setLogOutLog_renCore();
        Auth::logout();
        return redirect()->route("rcare-login");
    }

     // REN_CORE USERS//created by ashvini 27 january 2021
     public function setLogOutLog_renCore(){
        $id = session()->get('userid'); 
        $email = session()->get('email');
        $ipaddress = request()->getClientIp();
        $latestuserdetails = RenUserLoginHistory::where('userid',$id)
        ->where('user_email',$email)->where('ip_address',$ipaddress)->orderBy('id','desc')->first();   
        if($latestuserdetails!=''){ //modify by priya 4thApril2021
            RenUserLoginHistory::where('id',$latestuserdetails->id)
            ->update([ 'logout_time' =>Carbon::now() ]);
            Session::flush();
        } 
    }

    //modified by ashvini 27 jan 2021
    public function setLogOutLog(){
        $id = session()->get('userid');
        $email = session()->get('email');
        $ipaddr = request()->getClientIp();
        $userdetails =UserLoginHistory::where('user_email',$email)->where('ip_address',$ipaddr)->latest('id')->first();
        UserLoginHistory::where('user_email',$userdetails->user_email)
        ->update([
            'logout_time' =>Carbon::now(),
        ]);        
        Session::flush();     
    }

    

    public function setLogInLog($id,$email){

    UserLoginHistory::create(
        [
        'userid' => $id,
        'user_email' =>$email,
        'login_time'=>Carbon::now(),
        'ip_address'=>request()->getClientIp(),
        // 'mac_address'=>'',
        'login_attempt_status'=>0,
        ]);  
    }

    public function setAfterLogInLog(){

        UserLoginHistory::create(
        [
        'userid' => Auth::guard('rcare_user')->user()->id, 
        'user_email' =>Auth::guard('rcare_user')->user()->email,
        'login_time'=>Carbon::now(),
        'ip_address'=>request()->getClientIp(),
        // 'mac_address'=>'',
        'login_attempt_status'=>1, 
        ]);
    }
    public function setLogInLog_user_not_exist($email){
         UserLoginHistory::create(
        [
        // 'userid' => '',
        'user_email' =>$email,
        'login_time'=>Carbon::now(),
        'ip_address'=>request()->getClientIp(),
        // 'mac_address'=>'',
        'login_attempt_status'=>0,
        ]);  
    }

   

    public function setLogInLog_renCore($id,$email){
    RenUserLoginHistory::create(
        [
        'userid' => $id,
        'user_email' =>$email,
        'login_time'=>Carbon::now(),
        'ip_address'=>request()->getClientIp(),
        // 'mac_address'=>'',
        'login_attempt_status'=>0,
        ]);  
    }

    public function setAfterLogInLog_renCore($id,$email){
        RenUserLoginHistory::create(
            [
            //'userid' => Auth::guard('renCore_user')->user()->id,
    //'user_email' =>Auth::guard('rcare_user')->user()->email,
            'userid' => $id,
            'user_email' =>$email,
            'login_time'=>Carbon::now(),
            'ip_address'=>request()->getClientIp(),  
            // 'mac_address'=>'',
            'login_attempt_status'=>1, 
            ]);
        }
    public function setLogInLog_user_not_exist_ren_Core($email){
        RenUserLoginHistory::create(
        [
        // 'userid' => '',
        'user_email' =>$email,
        'login_time'=>Carbon::now(),
        'ip_address'=>request()->getClientIp(),
        // 'mac_address'=>'',
        'login_attempt_status'=>0,
        ]); 
    }

}
