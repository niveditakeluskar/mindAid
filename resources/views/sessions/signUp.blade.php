<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rcare</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/styles/css/themes/lite-purple.min.css')}}">
</head>

<body>
    <div class="auth-layout-wrap" style="background-image: url({{asset('assets/images/photo-wide-4.jpg')}})">
        <div class="auth-content">
            <div class="card o-hidden">
                <div class="row">
                    <div class="col-md-12">
                        <div class="p-4">
                            <div class="auth-logo text-center mb-4">
                                <img src="{{asset('assets/images/logo.png')}}" alt="">
                            </div>
                            <h1 class="mb-3 text-18">Sign Up</h1>
                            <form action="" method="post">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                          <label for="username">First name</label>
                                          <input id="fname" name ="f_name" class="form-control form-control-rounded" type="text">  
                                        </div>
                                        <div class="col-md-6">
                                            <label for="username">Last name</label>
                                            <input id="lname" name ="l_name" class="form-control form-control-rounded" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <!-- <div class="col-md-6">
                                          <label for="username">UID</label>
                                          <input id="fname" name ="f_name" class="form-control form-control-rounded" type="text">  
                                        </div> -->
                                        <div class="col-md-6">
                                           <label for="email">Email address</label>
                                           <input id="email" name="email" class="form-control form-control-rounded" type="email">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                          <label for="password">Password</label>
                                          <input id="password" name="password" class="form-control form-control-rounded" type="password">  
                                        </div>
                                        <div class="col-md-6">
                                            <label for="repassword">Retype password</label>
                                            <input id="repassword" class="form-control form-control-rounded" type="password">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                   
                                </div>
                                <button class="btn btn-primary btn-block btn-rounded mt-3">Sign Up</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{asset('assets/js/common-bundle-script.js')}}"></script>

    <script src="{{asset('assets/js/script.js')}}"></script>
</body>

</html>
