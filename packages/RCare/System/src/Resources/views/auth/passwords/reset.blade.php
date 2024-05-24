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
                            <div class="text-center text-18">{{ __('Reset Password') }}</div> 
                                <p class="alert alert-success" id="success" style= "display:none"; >
                                    <span id= "success"></span>.
                                </p>   
                                <p class="alert alert-danger" id="danger" style= "display:none"; >
                                    <span id= "danger"></span>.
                                </p>   
                
                                <div class="card-body">
                                    <form id="password-update" name="password_update" method="POST">
                                    @csrf
                                    <input type="hidden" name="token" value="<?php echo (isset($_GET['token']))?$_GET['token']:'' ?>">
                                    <input type="hidden" name="login_as" value="<?php echo $_GET['login_as']; ?>">

                                    <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
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
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                        <div class="col-md-6">
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 offset-md-4">
                                            <button type="btn" class="btn btn-primary" id="update-password">
                                                {{ __('Reset Password') }}
                                            </button>
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
        <script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
        <script type="text/javascript">
            $(function () {
                $("form[name='password_update']").validate({
                    rules: {
                        email: {
                            required: true,
                            email: true,
                        },
                        password: {
                            required: true,
                            minlength: 6,
                        },
                        password_confirmation : {
                            minlength : 6,
                            equalTo : "#password"
                        }
                    },
                    messages: {
                        email: {
                            required: "please Enter your E-Mail Id",
                            email: "Your email address must be in the format of name@domain.com"
                        },
                        password: {
                            required: "please Enter your Password",
                            minlength: "Your password must be at least 6 characters long"
                        },
                        password_confirmation: {
                            required: "please Enter your Confirm Password",
                            minlength: "Your password must be at Equal to Confirm Password"
                        },
                    },
                });
            });

            $("#update-password").click(function(event){
                event.preventDefault();
                    if($("form[name='password_update']").valid()){
                    var base_url = "<?php echo url('').'/'; ?>";
                        $.ajax({
                            type: "POST",
                            url: "/password_update",
                            dataType:"json",
                            data: $('#password-update').serialize(),
                            success: function(response) {
                                success = response.data[0].success;
                                url = response.data[0].url;
                                error = response.data[0].error;
                                if(success=='y'){
                                    window.location.href=base_url+''+url;
                                    $("#success").show(0).delay(5000).hide(0);
                                    $("#success").html(error);
                                    $("#forget_pass")[0].reset();
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