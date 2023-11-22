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
        <div class="auth-content">
            <div class="card o-hidden">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mt-4">
                            <div class="auth-logo text-center mb-4">
                                <img src="{{asset('assets/images/logo.png')}}" alt="">
                            </div>
                            <div class="text-center text-18"><b>Multifactor authentication<b/></div> 
                           
                            <form name="forget_pass" id="2faotp" method="POST">
                            <div class="card-body">
                                <p class="alert alert-success" id="success" style= "display:none"; >
                                    <span id= "success"></span>.
                                </p>   
                                <p class="alert alert-danger" id="danger" style= "display:none"; >
                                    <span id= "danger"></span>.
                                </p>
                                    @csrf
                                    <div class="form-group row">
                                        <p class="text-center">We have sent authentication code to your contact number :  <b id="otp_num"></b> from RCARE system </p>
                                        <label for="otp" class="col-md-4 col-form-label text-md-right" style="font-size: 15px">{{ __('OTP') }}</label>
                                        <div class="col-md-6">
                                            <input type="text" id="number" name="number" >
                                            <input type="text" id="userid" name="userid" >
                                            <input type="text" id="timezone" name="timezone" value ="<?php echo config('app.timezone');?>">
                                            <input type="text" id="role" name="role" >
                                            <input id="code"   type="code" maxlength="6" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code') }}" 
                                            onkeypress="return isNumber(event)"
                                            required autocomplete="code" autofocus>
                                            @error('code')
                                            <span class="invalid-feedback" id="otp_feedback" role="alert">
                                                <strong id="otp_error_msg">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
<!--                                     <div class="form-group row">
                                        <label for="loginuser" class="col-md-4 col-form-label text-md-right" style="font-size: 15px">Login As</label>
                                        <div class="col-md-6">
                                            <select class="form-control dropdown-list" name="login_as" id="login_as">
                                                <option value="">Choose Role</option>
                                                <option value="1">Rcare</option>
                                                <option value="2">Organization(Renova Healthcare)</option>
                                                <option value="3">Partner</option>
                                            </select>
                                        </div>
                                    </div> -->
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
    </div>
    <script src="{{asset('assets/js/common-bundle-script.js')}}"></script>
    <script src="{{asset('assets/js/script.js')}}"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script type="text/javascript"> 
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

        $("#password_forget").click(function(event){
            event.preventDefault();
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
                                window.location.href=base_url+''+url;
                                $('#hd_otp').show();
                                $('#hd_login').hide();
                                $('#number').val(mob);
                                $('#otp_num').html(mob.substring(0, 10)+"****"+Number(String(mob).slice(-2))); 
                                
                           }else{
                                $('#hd_otp').hide();
                                $('#hd_login').show();
                                window.location.href=base_url+''+url;
                           }
                        } else{
                            $("#danger").show(0).delay(4000).hide(0);
                            $("#danger").html(error);
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>