<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Renova HealthCare</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/styles/css/themes/lite-purple.min.css')}}">
</head>
<body>
    <div class="auth-layout-wrap" style="background-image: url({{asset('assets/images/photo-wide-4.jpg')}})">
        <div class="auth-content" id="hd_login">
            <div class="card o-hidden">
                <div class="row">
                    <div class="col-md-12"> 
                        <div class="mt-4">
                            <div class="auth-logo text-center mb-4">
                                <img src="{{asset('assets/images/logo.png')}}" alt="">
                            </div>
                            <div class="text-center text-18">{{ __('Reset Password') }}</div> 
                            <form name="forget_pass" id="forget_pass" method="POST">
                            <div class="card-body">
                                <p class="alert alert-success" id="success" style= "display:none"; >
                                    <span id= "success"></span>.
                                </p>   
                                <p class="alert alert-danger" id="danger" style= "display:none"; >
                                    <span id= "danger"></span>.
                                </p> 
                                    @csrf
                                    <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right" style="font-size: 15px">{{ __('E-Mail Address') }}</label>
                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span> 
                                            @enderror
                                        </div>
                                    </div>
                                    </div>
                                    <div class="card-footer"> 
                                        <div class="mc-footer">
                                            <div class="row">
                                                <div class="col-lg-12 text-center">
                                                    <button type="btn" class="btn btn-primary" id="password_forget">
                                                     {{ __('Send Password Reset Link') }}
                                                    </button>
                                                    <a class=" btn btn-primary" id="back" href="{{ route('rcare-login') }}">Back</a>
                                               
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
        <!---2FA otp created by anand--->
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
                        <p class="text-center">We have sent authentication code to your Email - <span id="otp_via"></span>from RCARE system </p>
  
                        <div class="form-group row">
                            <label for="code" class="col-md-4 col-form-label text-md-right">Security Code :</label>
  
                            <div class="col-md-6">
                                <input type="text" id="code" name="code"  class="form-control " onkeypress="return isNumber(event)"  required autocomplete="code" autofocus>
                                <span class="invalid-feedback" id="otp_feedback" role="alert">
                                        <strong id="otp_error_msg"></strong>
                                </span>
                              
                                <input type="hidden" id="number" name="number" >
                                <input type="hidden" id="userid" name="userid" >
                                <input type="hidden" id="timezone" name="timezone" value ="<?php echo config('app.timezone_US');?>">
                                <input type="hidden" id="role" name="role" >
                                <input type="hidden" id="page_name" name="page_name" value='forget_pwd'>
                            </div>
                        </div>
  
<!--                         <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <a class="btn btn-link" href="" id="resend_otp">Resend Code?</a>
                            </div>
                        </div>
  
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="button" id="opt_save" class="btn btn-primary">
                                    Submit
                                </button>
  
                            </div>
                        </div> -->
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
    </div>
    <script src="{{asset('assets/js/common-bundle-script.js')}}"></script>
    <script src="{{asset('assets/js/script.js')}}"></script>
    <script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/js/moment.min.js')}}"></script> 
    <script type="text/javascript"> 

        $(document).ready(function(){
            var timezone = moment.tz.guess();
            // alert(timezone);
            $('.timezone').val(timezone);
        });

        $(document).keypress(
          function(event){
            if (event.which == '13') {
              event.preventDefault();
            }
        });
        
        $(function () {
            $("form[name='forget_pass']").validate({
                rules: {
                    email: {
                        required: true,
                        email: true,
                    }
                    // ,
                    // login_as: {
                    //     required:true,
                    // }
                },
                messages: {
                    email: { 
                        required: "please Enter your E-Mail Id",
                        email: "Your email address must be in the format of name@domain.com"
                    }
                    // ,
                    // login_as: {
                    //     required: "please choose  Role",
                    // },
                },
            });
        });

        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }

        $("#back_login").click(function(event){
            var base_url = "<?php echo url('').'/'; ?>";
            event.preventDefault();
            var url = "rcare-login";
            $('#hd_otp').hide();
            $('#hd_login').show();
            window.location.href=base_url+''+url;
        });
        $("#password_forget").click(function(event){
            event.preventDefault();
            $('#code').val('');
            if($("form[name='forget_pass']").valid()){
            var base_url = "<?php echo url('').'/'; ?>";
                $.ajax({
                    type: "POST",
                    url: "resetpassword_otp",//"password_mail",
                    dataType:"json",
                    data: $('#forget_pass').serialize(),
                    success: function(response) {
                        success = response.data[0].success;
                        url = response.data[0].url;
                        error = response.data[0].error; 
                        if(success=='y'){
                            $("#success").show(0).delay(5000).hide(0);
                            $("#success").html(error);
                            $("#forget_pass")[0].reset();
                            if(url=="login-otp"){ 
                            var mob=response.data[0].mob;
                            var otp_verify=response.data[0].userid_otp;
                            var timezone=response.data[0].timezone;
                            var role=response.data[0].role;
                            var mob=response.data[0].mob;
                            var myArray = mob.split("/");
                            var s1= myArray[1].substr(0, myArray[1].indexOf('@'));
                            if(myArray[1]!=''){
                                var email_res = s1.substring(0, 2)+"****"+String(myArray[1]).slice(10);
                            }else{ 
                                email_res='';
                            }

                                var res_send = email_res;
                                // window.location.href=base_url+''+url;
                                $('#hd_otp').show();
                                $('#hd_login').hide(); 
                                $('#number').val(mob);
                                $('#userid').val(otp_verify);
                                $('#timezone').val(timezone);
                                $('#role').val(role);
                                // $('#otp_num').html(mob);
                                $('#otp_via').html(res_send);          
                           }else{
                                $('#hd_otp').hide();
                                $('#hd_login').show();
                                window.location.href=base_url+'password_requestform';
                           }
                        } else{
                            $("#danger").show(0).delay(4000).hide(0);
                            $("#danger").html(error);
                        }
                    }
                });
            }
        });

        $("#opt_save").click(function(event){
            event.preventDefault();
            if($('#code').val()==""){
                $('#otp_error_msg').html("Please enter security code."); 
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
                    url: "/login-otp/2fapost", //password_mail
                    dataType:"json",
                    data: $('#2faotp').serialize(), 
                    success: function(response) {
                   // alert(JSON.stringify(response));
                   // alert(response.message);
                     if(response.sucsses==1){  
                        // window.location.href=base_url+'password_requestform';
                        $('#hd_otp').hide(); 
                        $('#hd_login').show();
                        $('#success').html(response.message);
                        $("#success").show();
                        // $("#success").show(0).delay(3000).hide(0);
                     }else if(response.sucsses==2){ 
                          $('#hd_otp').hide(); 
                          $('#hd_login').show();
                          $("#danger").html(response.message);
                          $("#danger").show();
                          // $("#otp_error_msg").show(0).delay(3000).hide(0);
                     }else{
                        $('#otp_error_msg').html(response.message);
                        $('#otp_feedback').show();
                     }
                    }
                })
            }
        });

    </script>
</body>
</html>