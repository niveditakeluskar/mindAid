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
                            <div class="text-center text-18"></div>
                            <form  name="login_form" id="login_form" method="POST">
                                <div class="card-body">     
                                    <div class="form-group row">
                                        <label for="email" class="col-md-12 col-form-label text-center" style="color: red">Access Denied!!!</label>
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
    <script type="text/javascript"> 
    $(document).ready(function(){ 
        if (window.matchMedia("(min-width: 768px)").matches){
          window.location.href = "/rcare-login";// The viewport is less than 768 pixels wide ->mobile
        }
    });
</script>
</body> 
</html>
