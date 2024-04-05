<?php
namespace RCare\System\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Mail;
use Redirect;
use Carbon\Carbon;
use Auth;
use Hash;
use DB;
use Validator;
use Session;
use Twilio\Rest\Client;
use Twilio\Jwt\ClientToken;
use Twilio\Exceptions\TwilioException;
use Twilio\TwiML\MessagingResponse;

use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use RCare\System\src\Http\Requests\PasswordResetRequest;
use Illuminate\Http\Request;
use RCare\System\Models\PasswordReset;
use RCare\RCareAdmin\AdminPackages\Configuration\src\Models\Configurations;
use RCare\Org\OrgPackages\DomainFeatures\src\Models\DomainFeatures;
use RCare\Org\OrgPackages\Users\src\Models\RenUserLoginHistory;
use RCare\RCareAdmin\AdminPackages\Users\src\Models\User;
use RCare\Org\OrgPackages\Users\src\Models\Users;
use URL;
use RCare\System\Models\MfaTextingLog;
use App\Mail\MailPHP;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }
 
    public function showLinkRequestForm() {
      return view('System::passwords.email');
    }
    public function AuthenticatUser(Request $request){
            $role = 2;
            $email= sanitizeVariable($request->input('email'));
            $eid = Users::where('email',$email)->first();
            if(!empty($eid)){
                $id =$eid['id']; 
                $userStatus = $eid['status'];
                $block_unblock_status = $eid['block_unblock_status'];
                // dd($userStatus);
                        if($userStatus=='1' &&  $block_unblock_status==0){
                            $generateOtp= $this->generateCode($id); 
                            if($generateOtp[0]['sucsses']=="N"){
                                $response['error']=$generateOtp[0]['msg'];
                            }else{ 
                                $response['success']='y';
                                $response['url'] = 'login-otp';
                                $response['mob'] = $generateOtp[0]['mob'];
                                $response['userid_otp'] = $generateOtp[0]['userid_otp'];
                                // $response['timezone'] = $timezone;
                                $response['role'] = $role;
                                $response['error']='';
                            } 
                        }else{
                             $response['success']='n';
                             $response['url']='';
                             $response['error']='You are temporary blocked. please contact to admin';
                        }
            }else{
                $this->setLogInLog_user_not_exist_ren_Core($email);
                             $response['success']='n';
                             $response['url']='';
                             $response['error']='User does not exist.';
                // return redirect()->route('rcare-login')->with('message','User does not exist.');
            }    
    echo '{ "data":[ '. json_encode( $response) .']}'; 
    }

    public function generateCode($id){    
        try {
            $base_url = URL::to('/');
            $server_domain = DomainFeatures::where('status',1)->first();
            $otp_digit = $server_domain->digit_in_otp; 
            $digit_in_otp=1;
            for($i=1;$i<=$otp_digit;$i++){
                $digit_in_otp .= 0;
            } 
            // echo $mfa_status.'-'.$otp_by_sms. ''.$otp_by_email;die;
            $code = rand(1,$digit_in_otp);
            //$code = rand(100000,999999);
            $code_data =array('otp_code'=>$code,'created_by'=>$id, 'updated_by'=>$id);  
            Users::where('id',$id)->update($code_data);
            $emailID = Users::select('email','f_name','l_name','token','otp_code')->where('id',$id)->first();
            $email = $emailID->email;
            if($email=="" || $email==null){
                return array(['sucsses'=>"N",'msg'=>"Please ask administrator to add your email-id for Multifactor authentication."]);
            }
            else{
                
                $data = array(
                    'email'=>$emailID->email, 
                    'name'=>$emailID->f_name, 
                    'url'=> $base_url.'/password/reset?token='.$emailID->token.'&login_as=1',
                    'otp' =>$emailID->otp_code, 
                    'link'=> $base_url.'/rcare-login'
                );
                
                $data['message'] = 'Hi '. $data["name"];
                       
                $mailData = [
                    'title' => 'RCARE Multifactor Authentication Code',
                    'body' => $data['message'],
                    'message' => 'Multifactor authentication forget password code is '.$data["otp"].' from RCARE.',
                    'button_text' => '',
                    'button_url' => '',
                    'link' => 'Team Renova'
                ];
            
                Mail::to($data['email'])->send(new MailPHP($mailData));

                $response['success']='y';
                $response['url']='';
                $response['message']='OTP has sent on your email, Please check your email';
                
                $type = 'MFA';
                $email_msg  = '<h5>Hi  ' . $data["name"].', </h5> 
                <p>Multifactor authentication forget password code is '.$data["otp"].' from RCARE "</p> 
                <a>Team Renova</a>';
                $content = strip_tags($email_msg);
                $sent_type = 'email'; 
                $sent_to = $email;
                $this->setTextingLog($id,$type,$sent_type,$content,$sent_to);
                // Users::where('id',$id)->update(['otp_date'=>Carbon::now()]); 
             return array(['mob'=>'/'.$emailID->email,'userid_otp'=>$id,'sucsses'=>'Y','msg'=>$response['message']]);
            }

        } catch (Exception $e) {
           // info("Error: ". $e->getMessage());
           return array(['sucsses'=>"N",'msg'=>"Invalid number. Please ask administrator to add your contact number for multifactor authentication."]);
           
        }
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

    public function setTextingLog($id,$type,$sent_type,$content,$sent_to){
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
            'message_id' =>'',
            'status' =>''
            ]);
    }

    public function sendResetLinkEmail(Request $request){
            $request->validate([
                'code'=>'required',
            ]); 
            $chk_attempts = Users::where('id',$request->userid)->first();
            ($chk_attempts->max_attempts =="" && $chk_attempts->max_attempts ==null && $chk_attempts->max_attempts ==0) ? $count_attempts=1 : $count_attempts=$chk_attempts->max_attempts+1;

            Users::where('id',$request->userid)->update(['max_attempts' =>$count_attempts]);      
            $DomainFeatures= DomainFeatures::where('features','2FA')->first();
             
            if($count_attempts>=$DomainFeatures->otp_max_attempts){//2
                Users::where('id',sanitizeVariable($request->userid))->update(['block_unblock_status' =>1]);  
               return response(['sucsses'=>2,'message'=>'You are temporary lock. Please contact to admin.']);
            }else{
                $find = Users::where('id',$request->userid)
                          ->where('otp_code', $request->code)
                          ->first();
                if (!is_null($find)){
                   // Session::put('userid', $request->userid);
                      session()->put(['email'=>$chk_attempts->email,
                      'userid'=>sanitizeVariable($request->userid),
                       'status'=>$chk_attempts->status,'role'=>$chk_attempts->role,
                       'f_name'=>$chk_attempts->f_name,'l_name'=>$chk_attempts->l_name,
                       'profile_img'=>$chk_attempts->profile_img,'user_type'=>sanitizeVariable($request->role),
                       'org_id'=>$chk_attempts->org_id, 'timezone'=>sanitizeVariable($request->timezone)]);

                      $userStatus = Auth::guard('renCore_user')->user()->status;
                        $this->setAfterLogInLog_renCore($request->userid,$chk_attempts->email);
                        //return response(['sucsses'=>1,'message'=>'password_requestform']);
                        return response(['sucsses'=>1,'message'=>'password_requestform']);
                }else{
                    return response(['sucsses'=>0,'message'=>'You entered wrong authentication code.']);
                } 
            } 
    }    

    // public function sendResetLinkEmail(Request $request){
    //     $login_as      = sanitizeVariable($request->input('login_as'));
    //     $email_address = sanitizeVariable($request->input('email'));
    //     $response = array();
    //     $Auth_check = User::where("email", $email_address)->first();
    //     if($login_as=='1'){ 
    //         $Auth_check = User::where("email", $email_address)->first();
    //         if(!empty($Auth_check)){
    //             $Auth = User::where("email", $email_address)->where("status", 1)->first(); 
    //             if($Auth){
    //                $resetRequest = PasswordReset::createRequest($Auth,$login_as);
    //                $data = array(
    //                     'email'=>$Auth->email, 
    //                     'name'=>$Auth->f_name, 
    //                     'url'=>'http://rcareproto2.d-insights.global/password/reset?token='.$Auth->token.'&login_as=1',
    //                     'link'=>'http://rcareproto2.d-insights.global/rcare-login'
    //                 );
    //                 Mail::send([], $data, function ($message) use($data) {
    //                     $message->to($data['email'], $data['name'] )
    //                     ->subject('Password Reset') 
    //                     ->setBody('<h5>Hi  ' . $data["name"].', </h5>
    //                         <p>A password request has been requested for this account. If you did not request a password reset, it is encouraged that you change your password in order to prevent any malicious attacks on your account. Otherwise, proceed by clicking the link below. The link will be valid for 24 hours. </p> 
    //                         <p> <a class="button" href ='.$data["url"].'>Reset Password</a></p>
    //                         <a href='.$data["link"].' Team Renova</a>',  
    //                     'text/html'); // for HTML rich messages
    //                 });
    //                 if (Mail::failures()) {
    //                     $response['success']='n';
    //                     $response['url']='password_reset';
    //                     $response['error']='Sorry we are unable to sent an email, try again';
    //                 } else{
    //                     $response['success']='y';
    //                     $response['url']='';
    //                     $response['error']='Your Request is Accepted, Please check your email.';
    //                 }
    //             } else {
    //                 $response['success']='n';
    //                 $response['url']='password_reset';
    //                 $response['error']='You are temporary blocked, please contact to admin';
    //             }
    //         } else {
    //             $response['success']='n';
    //             $response['url']='password_reset';
    //             $response['error']="Email Id doesn't exist";
    //         }     
    //     } elseif ($login_as=='2') {
    //         $Auth_check = Users::where("email", $email_address)->first();    
    //             if($Auth_check){
    //                 $Auth = Users::where("email", $email_address)->where("status", 1)->first(); 
    //                 if($Auth){
    //                 $resetRequest = PasswordReset::createRequest($Auth,$login_as);
    //                 $data = array(
    //                        'email' => $Auth->email, 
    //                        'name'  => $Auth->f_name, 
    //                        'url'   => 'http://rcareproto2.d-insights.global/password/reset?token='.$Auth->token.'&login_as=2',
    //                        'link'  => 'http://rcareproto2.d-insights.global/rcare-login'
    //                 );
    //                 Mail::send([], $data, function ($message) use($data) {
    //                     $message->to($data['email'], $data['name'])
    //                     ->subject('Password Reset')
    //                     ->setBody('<h5>Hi  ' . $data["name"].', </h5>
    //                     <p>A password request has been requested for this account. If you did not request a password reset, it is encouraged that you change your password in order to prevent any malicious attacks on your account. Otherwise, proceed by clicking the link below. The link will be valid for 24 hours. </p> 
    //                     <p> <a class="button" href ='.$data["url"].'>Reset Password</a></p>
    //                     <!-- Callout Panel -->
    //                     <a href='.$data["link"].' Team Renova</a>',  
    //                     'text/html'); // for HTML rich messages
    //                 });
    //                 if (Mail::failures()) {
    //                     $response['success']='n';
    //                     $response['url']='password_reset';
    //                     $response['error']='Sorry we are unable to sent an email, try again';
    //                 } else{
    //                     $response['success']='y';
    //                     $response['url']='';
    //                     $response['error']='Your Request is Accepted, Please check your email.';
    //                 }
    //             } else {
    //                 $response['success']='n';
    //                 $response['url']='password_reset';
    //                 $response['error']='You are temporary blocked, please contact to admin';
    //             }
    //         } else {
    //             $response['success']='n';
    //             $response['url']='password_reset';
    //             $response['error']="Email Id doesn't exist";
    //         }     
    //     } elseif ($login_as=='3') {
    //         echo "string3333";
    //     } else{
    //         $response['success']='n';
    //         $response['url']='password_reset';
    //         $response['error']="All Feild are Required";
    //     }
    //     echo '{ "data":[ '. json_encode( $response) .']}';
    // }
}