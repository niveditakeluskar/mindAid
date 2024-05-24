<?php

namespace RCare\System\Http\Controllers\Auth;
use RCare\RCareAdmin\AdminPackages\Users\src\Models\User;
use RCare\Org\OrgPackages\Users\src\Models\Users;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use RCare\System\src\Http\Requests\PasswordUpdateRequest;
use Illuminate\Http\Request;
use Hash;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    public function updatePassword(Request $request) {   
        $email    = sanitizeVariable($request->input('email'));
        $password = sanitizeVariable($request->input('password'));
        $login_as = sanitizeVariable($request->input('login_as'));

        if($login_as=='1') {
            $Auth_check = User::where("email", $email)->first();
            if(!empty($Auth_check)) {
                $Auth = User::where("email", $email)->where("status", 1)->first(); 
                if($Auth){
                    $update_password = User::where('email', $email)->update(['password' =>Hash::make($password)]);
                    if($update_password) {
                        $response['success']='y';
                        $response['url']='rcare-login';
                        $response['error']='Your Password successfully update';
                    } else {
                        $response['success']='n';
                        $response['url']='';
                        $response['error']='Sorry Your password is not reset!!!';
                    }
                } else {
                    $response['success']='n';
                    $response['url']='';
                    $response['error']='You are temporary blocked, please contact to admin';
                }
            } else {
                if ($email=='') {
                    $response['success']='n';
                    $response['url']='';
                    $response['error']="All feild are Required";
                }else{
                    $response['success']='n';
                    $response['url']='';
                    $response['error']="Email Id doesn't exist";
                }
            }     
        } elseif ($login_as=='2') {
            $Auth_check = Users::where("email", $email)->first();    
            if($Auth_check) {
                $Auth = Users::where("email", $email)->where("status", 1)->first(); 
                if($Auth) {
                    $update_password = Users::where('email', $email)->update(['password' =>Hash::make($password)]);
                    if($update_password) {
                        $response['success']='y';
                        $response['url']='rcare-login';
                        $response['error']='Your Password successfully update';
                    } else {
                        $response['success']='n';
                        $response['url']='';
                        $response['error']='Sorry Your password is not reset!!!';
                    }
                } else {
                    $response['success']='n';
                    $response['url']='';
                    $response['error']='You are temporary blocked, please contact to admin';
                }
            } else {
                if ($email=='') {
                    $response['success']='n';
                    $response['url']='';
                    $response['error']="All feild are Required";
                } else {
                    $response['success']='n';
                    $response['url']='';
                    $response['error']="Email Id doesn't exist";
                }
            }     
        }
        echo '{ "data":[ '. json_encode( $response) .']}';
    }
}