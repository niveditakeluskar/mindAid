<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<!-- <meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" /> -->
    <title>Renova HealthCare</title> 
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet"> -->
    <link rel="stylesheet" href="{{ asset('assets/styles/external-css/fonts-googleapis.css')}}">
    <link rel="stylesheet" href="{{asset('assets/styles/css/themes/lite-purple.min.css')}}">
    <style>
     .error{ 
         color:red !important;
     }
 </style>
 <script type="text/javascript"> //<![CDATA[ 
var tlJsHost = ((window.location.protocol == "https:") ? "https://secure.trust-provider.com/" : "http://www.trustlogo.com/");
document.write(unescape("%3Cscript src='" + tlJsHost + "trustlogo/javascript/trustlogo.js' type='text/javascript'%3E%3C/script%3E"));
//]]>
</script>

</head>

<body>
	
    
    <div class="auth-layout-wrap" style="background-image: url({{asset('assets/images/photo-wide-4.jpg')}})">
        <div class="auth-content" id="hd_login"> 
            <div class="card o-hidden">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mt-4">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                            <div class="auth-logo text-center mb-4">
                                <img src="{{asset('assets/images/logo.png')}}" alt="">
                            </div>
                            <div class="text-center text-18">Sign In</div>

                               <!-- method="POST" action="{{ route('rcare-login') }}" -->
                            <form  name="login_form" id="login_form" method="POST">
                                <div class="card-body"> 
                                <p class="alert alert-danger" id="danger" style= "display:none"; >
                                    <span id= "success"></span>.
                                </p>       
                                    @csrf
                                    <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="off" >

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="off">
											<input type="hidden" name="timezone" class = "timezone" id="timezone" value ="<?php echo config('app.timezone_US');?>">
                                            
                                            
                                            
                                            
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row" id="otp-btn-options" style="display: none">
                                        <div class="col-md-6 offset-md-4">
                                             <label for="mfa"><span class="error">*</span> Receive Verification Code : </label> <span class="invalid-feedback" id="mfa-valid"><strong>Please Choose one option</strong></span>
                                             
                                            <div class="forms-element d-inline-flex">
                                                <label class=" checkbox checkbox-primary mr-3">
                                                    <input type="checkbox"  id="mfa_status" name="otp_text" 
                                                    <?php (isset($DomainFeatures->otp_text)&& ($DomainFeatures->otp_text==1) )? print("checked") :'';?> value="1">
                                                    <span>SMS</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                                <label class=" checkbox checkbox-primary mr-3">
                                                    <input type="checkbox"  id="mfa_status" name="otp_email" <?php (isset($DomainFeatures->otp_email)&& ($DomainFeatures->otp_email==1) )? print("checked") :'';?> value="1">
                                                    <span>Email</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6 offset-md-4">
                                            <div class="">
                                                <label class=" checkbox checkbox-primary" for="remember">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <span>{{ __('Remember Me') }}</span>
                                                <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <div class="mc-footer">
                                        <div class="row">
                                            <div class="col-lg-12 text-center">
                                            <button type="button" id="login_btn" class="btn btn-primary">
                                                {{ __('Login') }}
                                            </button>
<!--                                             <button type="button" id="otp_code_btn" class="btn btn-primary" disabled>
                                                {{ __('Send Verification Code') }}
                                            </button> -->
                                            @if (Route::has('password_requestform'))
                                                <a class="btn btn-link" href="{{route('password_requestform')}}">
                                                    {{ __('Forgot Your Password?') }}
                                                </a>
                                            @endif
                                            </div>
                                        </div>    
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

   
<!---2FA otp screen--->
<div class="container" id="hd_otp" style="display: none">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"> <img src="{{asset('assets/images/logo.png')}}" alt=""> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Multifactor authentication<b/></div>
                    <!-- <form method="POST" action="{{ route('2fapost') }}"> -->
                <form id="2faotp">
                @csrf
                <div class="card-body"> 
                	<p class="alert alert-danger" id="otp_danger" style= "display:none"; >
                    <span id= "otp_success"></span>.
                    </p>
                        <p class="text-center">We have sent authentication code to you on  : <b id="otp_num"></b> from RCARE system </p>
  
                        <div class="form-group row">
                            <label for="code" class="col-md-4 col-form-label text-md-right">Security Code : </label>
                              <div class="col-md-6">
                                <input type="text" id="code" name="code" minlength="1" class="form-control " onkeypress="return isNumber(event)"  required> 
                                <!-- autocomplete="code" autofocus -->
                                <span class="invalid-feedback" id="otp_feedback" role="alert">
                                        <strong id="otp_error_msg"></strong>
                                </span>
                                <span class="error" id="mfa_msg_status"></span>
                                <input type="hidden" id="number" name="number" >
  						        <input type="hidden" id="userid" name="userid" >
                                <input type="hidden"  class = "timezone"  id="timezone" name="timezone" value ="<?php echo config('app.timezone_US');?>">
                                <input type="hidden" id="role" name="role" >
                                <input type="hidden" id="page_name" name="page_name" value='login'>
                            </div>
                        </div>
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                            <button type="button" id="opt_save" class="btn btn-primary">Submit</button>
                            <button type="button" id="back_login" class="btn btn-primary">Back</button>
                            <a class="btn btn-link" id="resend_otp" href="">Resend Code?</a>
                            </div>
                        </div>    
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--=============================================================================-->
</div>
<div id="secureCerticate" style="position: absolute;     top: 90% !important;">
	<script language="JavaScript" type="text/javascript">
//TrustLogo("https://rcarestaging.d-insights.global/positivessl_trust_seal_md_167x42.png", "CL1", "none");
TrustLogo("{{asset('/positivessl_trust_seal_md_167x42.png')}}" , "CL1", "none");
//TrustLogo("<?php // echo url('').'/positivessl_trust_seal_md_167x42.png'; ?>", "CL1", "none");


</script>
<a  href="https://www.positivessl.com/" id="comodoTL">Positive SSL Wildcard</a>
</div>
<div id="confirm_url" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">URL Confirm</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <?php 
                    $base_url = url('/');
                    if($base_url == "https://rcareproto2.d-insights.global") {
                        echo "You are in the test system – this is not production – do you acknowledge you would like to proceed in the test system?";
                    } elseif($base_url == "https://rcarestaging.d-insights.global") {
                        echo "You are in the staging – this is not production – do you acknowledge you would like to proceed in the test system?";
                    }
                ?>
            </div>
            <div class="card-footer">
                <div class="mc-footer">
                    <div class="row">
                        <div class="col-lg-12 text-right">
                            <button type="button"  class="btn  btn-primary m-1" id="confirm_url_ok">Ok</button>
                            <button type="button" class="btn btn-outline-secondary m-1" id="confirm_url_cancel">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 <!-- JQUERY  -->
    
<script src="{{asset('assets/js/common-bundle-script.js')}}"></script>
<script src="{{asset('assets/js/script.js')}}"></script>
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script> 
<script src="{{asset('assets/js/moment.min.js')}}"></script> 
    <script type="text/javascript">
        function check_mfa_status(msg_id){
            // alert(msg_id);
            if(msg_id!=''){ 
                const myInterval = setInterval(myTimer, 60000);
                $.ajax({  
                type: "get",
                url: '/system/get-mfa-status/'+msg_id+'/mfa-msg-status',
                    success: function(data){//debugger;
                        // console.log(data[0].status+"DADASDATA");
                        var msg_status = data[0].status;
                        var msg_status_update = data[0].status_update; 
                        if((msg_status_update =='1' && msg_status =='sent')||msg_status =='accepted' ||msg_status =='queued' || msg_status =='sending'){
                            $('#mfa_msg_status').html('it is taking longer to deliver the authentication code, you can wait for sometime or use email authentication Method.');
                            myTimer(); 
                        }else if(msg_status =='failed' ||msg_status =='undelivered'){
                            $('#mfa_msg_status').html('we are currently not able to send the text msg, kindly use email authentication method.');
                            myTimer();
                        }else if(msg_status =='delivered'){
                            $('#mfa_msg_status').html('');
                            clearInterval(myTimer); 
                        }else{
                            $('#mfa_msg_status').html('');
                            myTimer();
                        }    

                    }
                }); 
            }
        }

        // const myInterval = setInterval(myTimer, 30000);
        function myTimer() {
            window.setInterval(function(){
                check_mfa_status(msg_id);    
            }, 30000);
        }
        // var intervalId = window.setInterval(function(){
        // console.log('msg_id');    
        // }, 30000);
        

        $(document).keypress(
          function(event){
            if (event.which == '13') {
                $("#login_btn").click();
              //event.preventDefault();
            }
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function(){

            
        var timezone = moment.tz.guess();
        // alert(timezone);
		$('.timezone').val(timezone);




            if (window.matchMedia("(max-width: 767px)").matches){
              window.location.href = "/login-access-denied";// The viewport is less than 768 pixels wide ->mobile
            }
            //DISABLE AUTO FILL 
            var timezone = moment.tz.guess();
            // alert(timezone);
            $('#timezone').val(timezone);
            $("#password").attr("autocomplete", "off");
            var origin   = window.location.origin;
            if(origin != "https://rcare.d-insights.global"){
                $('#confirm_url').modal('show');
                $('#confirm_url_ok').click(function (){
                    $('#confirm_url').modal('hide');
                }); 
                $('#confirm_url_cancel').click(function (){
                    $('#confirm_url').modal('hide');
                    window.location.href = "/access-denied";
                });
            }
            // $("#email").val("");
            // $("#password").val("");
        });
$(function () {
		
    // var timezone = moment.tz.guess();
    //     alert(timezone);
	// 	// $('#timezone1').val(timezone);
    //     $('#timezone').val(timezone);   


        $("form[name='login_form']").validate({
            rules: {
                    email: {
                        required: true,
                        email: true,
                    },
                    password: {
                        required: true,
                        minlength: 6,
                    },
                    // role: {
                    //     required:true,
                    // }
            },
            messages: {
                email: {
                    required: "Please Enter your E-Mail Id",
                    email: "Your email address must be in the format of name@domain.com"
                },
                  password: {
                    required: "Please Enter your Password",
                    minlength: "Your password must be at least 6 characters long"
                },
                //  role: {
                //     required: "Please choose  Role",
                // },
            },
        });

    $("#back_login").click(function(event){
        var base_url = "<?php echo url('').'/'; ?>";
        event.preventDefault();
        var url = "rcare-login";
        $('#hd_otp').hide();
        $('#hd_login').show();
        window.location.href=base_url+''+url;
    }); 

 
    $("#email").focusout(function(event){
        // alert("This input field has lost its focus.");
        event.preventDefault();
        // if($("form[name='login_form']").valid()){
            $("form[name='login_form']").validate({
            rules: {
                    email: {
                        required: true,
                        email: true,
                    },
            },
            messages: {
                email: {
                    required: "Please Enter your E-Mail Id",
                    email: "Your email address must be in the format of name@domain.com"
                },
            },
        });
            var base_url = "<?php echo url('').'/'; ?>";
            // check required otp for login or not
            $.ajax({
                type: "POST",
                url: "/rcare-login-verification",
                dataType:"json",
                data: $('#login_form').serialize(),
                success: function(response) {
                    if(response.data[0].success=='login_screen'){
                        $("#login_btn").prop( "disabled", false);
                        $('#otp-btn-options').hide();
                        // $("#otp_code_btn").prop( "disabled", true);
                    }else if(response.data[0].success=='otp_screen'){
                        $('#otp-btn-options').show();
                        // $("#otp_code_btn").prop( "disabled", false );
                        $("#login_btn").prop( "disabled", false );

                    }else if(response.data[0].success=='credential empty'){
                        $('#otp-btn-options').hide();
                        $("#login_btn").prop( "disabled", false);
                    }else{
                        $("#danger").html('Invalid User.');
                        $("#danger").show(0).delay(3000).hide(0);
                    }

                },
                error: function (request, status, error) {
                    if(request.responseJSON.status == 419) {
						 location.reload();
					} 
                    if(request.responseJSON.errors !== undefined) {
                        if(request.responseJSON.errors.email) {
                            $("#danger").html(request.responseJSON.errors.email);
                            $("#danger").show(0).delay(3000).hide(0);
                        }
                    }
                }
            });
        // }
    });

    $("#otp_code_btn").click(function(event){
        var options_checked = $('#mfa_status:checked').val(); 
        // alert(options_checked);
        if(options_checked!=undefined){
            $('#mfa-valid').hide();
            if($("form[name='login_form']").valid()){
            var base_url = "<?php echo url('').'/'; ?>"; 
            $('#code').val('');
                $.ajax({
                type: "POST",
                url: "/rcare-login-with-otp",
                dataType:"json",
                data: $('#login_form').serialize(),
                success: function(response) {
                    success = response.data[0].success;
                    url = response.data[0].url;
                    error = response.data[0].error;
                    if(success=='y'){
                        var otp_verify=response.data[0].userid_otp;
                        var role=response.data[0].role;
                       if(url=="login-otp"){
                            var mob=response.data[0].mob;
                            var myArray = mob.split("/");
                            var s1= myArray[1].substr(0, myArray[1].indexOf('@'));
                            if(myArray[0]!=''){
                                var mob_res = myArray[0].substring(0, 10)+"****"+Number(String(myArray[0]).slice(-2));                        
                            }else{
                                mob_res='';
                            }
                            if(myArray[1]!=''){
                                var email_res = s1.substring(0, 2)+"****"+String(myArray[1]).slice(10);
                            }else{
                                email_res='';
                            }

                            var res_send = mob_res +' '+email_res;
                            $('#hd_otp').show();
                            $('#hd_login').hide();
                            $('#number').val(mob);
                            $('#userid').val(otp_verify);
                            $('#timezone').val(timezone);
                            $('#role').val(role);
                            $('#otp_num').html(res_send); 
                       }else{
                            $('#hd_otp').hide();
                            $('#hd_login').show();
                            window.location.href=base_url+''+url;
                       }
                       
                    }
                    else{
                        // alert('eroror');
                            $("#danger").show(0).delay(3000).hide(0);
                            $("#success").html(error);
                    }
                },
                error: function (request, status, error) {
                    if(request.responseJSON.errors !== undefined) {
                        if(request.responseJSON.errors.email) {
                            $("#danger").html(request.responseJSON.errors.email);
                            $("#danger").show(0).delay(150000).hide(0);
                        }
                    }
                }
                });
            }
        }else{
            $('#mfa-valid').show();          
        }
    });
    $("#login_btn").click(function(event){
      event.preventDefault();
      if($("form[name='login_form']").valid()){
        var base_url = "<?php echo url('').'/'; ?>";
        $('#login_btn').prop('disabled', true);
        $('#code').val('');
        $.ajax({
            type: "POST",
            url: "/rcare-login",//"/rcare-login-without-otp",
            dataType:"json",
            data: $('#login_form').serialize(),
            success: function(response) {
                success = response.data[0].success;
                url = response.data[0].url;
                error = response.data[0].error;
                msg_id = response.data[0].message_id;
                // twillio_error = response.data[0].twillio_msg; 

                if(success=='y'){
                    // alert('Y');
                    var otp_verify=response.data[0].userid_otp;
                    //var timezone=response.data[0].timezone;
                    var role=response.data[0].role;
                   if(url=="login-otp"){ 
                        var mob=response.data[0].mob;
                        var myArray = mob.split("/");
                        var s1= myArray[1].substr(0, myArray[1].indexOf('@'));
                        if(myArray[0]!=''){
                            var mob_res = myArray[0].substring(0, 10)+"****"+Number(String(myArray[0]).slice(-2));                        
                        }else{
                            mob_res='';
                        }
                        if(myArray[1]!=''){
                            var email_res = s1.substring(0, 2)+"****"+String(myArray[1]).slice(10);
                        }else{
                            email_res='';
                        }
                        var res_send = mob_res +' '+email_res;
                        check_mfa_status(msg_id);
                        $('#hd_otp').show();
                        $('#hd_login').hide();
                        $('#number').val(mob);
                        $('#userid').val(otp_verify);
                        $('#timezone').val(timezone);
                        $('#role').val(role); 
                        $('#otp_num').html(res_send); 
                        $('#login_btn').prop('disabled', false);

                   }else{
                   		$('#hd_otp').hide();
                   		$('#hd_login').show();
                   	 	window.location.href=base_url+''+url;
                        $('#login_btn').prop('disabled', false);
                   }
                   
                } 
                else{
                    // alert('eroror');
                        // $("#danger").show(0).delay(3000).hide(0);
                        $("#danger").show(0).delay(15000).hide(0); 
                        $("#success").html(error); 
                        $('#login_btn').prop('disabled', false);
                        // $("#success").html(twillio_error);

                // alert(response.data[0].success);

                }
            },
            error: function (request, status, error) {
                // debugger;
                if(request.responseJSON.status == 419) {
                        location.reload(); 
                }else{
                    message_error = request.responseJSON.message;
                    console.log(message_error +"LOGIN ISSUES");
                    var str1 = message_error;
                    var str_mail_error = "Server Error";
                    var str2 = "SSL"; 
                    var str3 = "Unable to create Record:"; 
                    if(str1.indexOf(str2) != -1 ||str1.indexOf(str_mail_error)!= -1){
                        // console.log(str2 + " found");
                        $('#login_btn').prop('disabled', false);  
                        $("#danger").html('We are not able to send the authentication code due to technical issues in email services. Please choose only text message for Multifactor Authentication Code.'); 
                        $("#danger").show(0).delay(30000).hide(0); 
                    }
                    else if(str1.indexOf(str3) != -1){
                        // console.log(str2 + " found"); 
                        $('#login_btn').prop('disabled', false); 
                        $("#danger").html('We are not able to send the authentication code due to technical issues in text services. Please choose only email for Multifactor Authentication Code.'); 
                        $("#danger").show(0).delay(30000).hide(0); 
                    }
                    else{ 
                        $('#login_btn').prop('disabled', false);  
                        $("#danger").html('System Error. Please contact system administrator.'); 
                        $("#danger").show(0).delay(30000).hide(0); 
                    }
                }
                // if(request.responseJSON.errors !== undefined) {
                //     if(request.responseJSON.errors.email) {
                //         $("#danger").html(request.responseJSON.errors.email);
                //         $("#danger").show(0).delay(3000).hide(0);
                //     }
                // }
            }
        });
        }
    });

});


$("#opt_save").click(function(event){
    event.preventDefault();
    if($('#code').val()==""){
        $('#otp_error_msg').html("Please enter Verification code.");
        $('#otp_feedback').show();

    }else if($.isNumeric($('#code').val()) == false){
        $('#otp_error_msg').html("Invalid code.");
        $('#otp_feedback').show();
    }
    else{
    	$('#otp_feedback').hide();
    	var base_url = "<?php echo url('').'/'; ?>";
        $.ajax({
            type: "POST",
            url: "/login-otp/2fapost",
            dataType:"json",
            data: $('#2faotp').serialize(),
            success: function(response) {
           //  alert(JSON.stringify(response));
             if(response.sucsses==1){
                window.location.href=base_url+''+response.message;
             }else if(response.sucsses==2){
                  $('#hd_otp').hide();
                  $('#hd_login').show();
                  $("#danger").show(0).delay(3000).hide(0);
                  $("#success").html(response.message);
             }else{
             	$('#otp_error_msg').html(response.message);
                $('#otp_feedback').show();
             }
             
             
        	}
        })
    }
});

$("#resend_otp").click(function(event){
    event.preventDefault();
    var userid=$('#userid').val();
    $.ajax({
            type: "POST",
            url: "/login-otp/resend",
            data:$('#2faotp').serialize(),
            success: function(response) {
                $("#otp_danger").show(0).delay(3000).hide(0);
                $("#otp_success").html("Multifactor Authentication code has been re-sent");
            }
        });
})

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
</script>
   

   
</body>
</html>
