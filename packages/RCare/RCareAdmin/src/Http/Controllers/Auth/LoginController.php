<?php

namespace RCare\RCareAdmin\Http\Controllers\Auth;
// use Auth;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\UserLoginHistory;
use Carbon\Carbon;

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
    /**
     * Login page has been requested
     *
     * @return mixed
     */

    public function index() {
        return view("RCareAdmin::auth.login");
    }

    // use AuthenticatesUsers;
    
    /**
    * Authenticate the given login request
    *
    * @param Illuminate\Http\Request $request
    * @return mixed
    */
    public function authenticate(Request $request)
    {
        $email = $request->input("email");
        $password = $request->input("password");
        if (Auth::attempt(credentials($email, $password))) {
            return redirect()->route("dashboard");
        }
        return back()->with("failed", true);
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }


    /**
     * Determine if the user has too many failed login attempts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected $maxAttempts = 3;
    protected $decayMinutes = 5; 

    public function login(Request $request) { 
        $remember    =    $request->input('remember');
        $credentials =    $request->only('email', 'password');
        $email       =    $request->input('email'); 
        $id = \DB::table('rcare_users')->select('id')->where('email',$email)->first();
        dd($id);
        if($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }       
        else{
            if(Auth::attempt($credentials,$remember)){
            session()->put(['email'=>Auth::user()->email,'userid'=>Auth::user()->id, 'status'=>Auth::user()->status]);
                $userStatus = Auth::user()->status;
                $id=Auth::user()->id;
                if($userStatus=='1'){
                    $this->setAfterLogInLog($id);
                    $this->clearLoginAttempts($request);
                    return redirect()->route("compact");
                }else{
                    $this->setLogInLog($id);
                    $this->incrementLoginAttempts($request);
                    Auth::logout();
                    return redirect(url('login'))->withInput()->with('errorMsg','You are temporary blocked. please contact to admin');
                }
            }else{
                $this->setLogInLog($id);
                $this->incrementLoginAttempts($request);
                return redirect(url('login'))->withInput()->with('errorMsg','Incorrect username or password. Please try again.');
            }
        }  
    }

    public function logout() {   
        Auth::logout();
        $this->setLogOutLog();
        return redirect()->route("home");
    }

     public function setLogOutLog() {
        $id = session()->get('userid');
        DB::table('rcare_users_login_history') 
        ->where('userid',$id)
        ->update([
            'logout_time' =>Carbon::now(),
        ]);

    }

    public function setLogInLog($id) {
    
        DB::table('rcare_users_login_history')->insert(
        [
        'userid' => $id,
        'login_time'=>Carbon::now(),
        'ip_address'=>request()->getClientIp(),
        'mac_address'=>'',
        // 'role' =>Auth::user()->role,
        'login_attempt_status'=>0,
        // 'session_id'=>request()->session()->getId()
        ]);  
    }

    public function setAfterLogInLog() {
        DB::table('rcare_users_login_history')->insert(
        [
        'userid' => Auth::user()->id,
        'login_time'=>Carbon::now(),
        'ip_address'=>request()->getClientIp(),
        'mac_address'=>'',
        // 'role' =>Auth::user()->role,
        'login_attempt_status'=>1, 
        // 'session_id'=>request()->session()->getId()
        ]);  
    }
}