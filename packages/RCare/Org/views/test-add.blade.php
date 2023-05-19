@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
    <div class="breadcrusmb">
        <div class="row">
            <div class="col-md-11">
                <h4 class="card-title mb-3">Test Users</h4>
            </div>
        </div>
    </div>

    <div class="tab-error">
    </div>
    <form action="{{ route("ajax.test.save") }}" method="post" name="test_form">
        {{ csrf_field() }}
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <label for="username">First name</label>
                    @text("f_name", ["id" => "fname", "class" => "form-control form-control-rounded"])
                    <span class="text-danger">{{ $errors->first('f_name') }}</span>

                    <label for="username">Last name</label>
                    @text("l_name", ["id" => "lname", "class" => "form-control form-control-rounded"])
                    <span class="text-danger">{{ $errors->first('l_name') }}</span>

                    <label for="email">Email address</label>
                    @email("email", ["id" => "email_add", "class" => "form-control form-control-rounded"])
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <label for="password">Password</label>
                    @password("password", ["id" => "password", "class" => "form-control form-control-rounded"])
                    <span class="text-danger">{{ $errors->first('password') }}</span>

                    <label for="repassword">Confim password</label>
                    @password("password_confirmation", ["id" => "repassword", "class" => "form-control form-control-rounded"])
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary btn-block btn-rounded mt-3" id="test-submit">Add</button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-primary btn-block btn-rounded mt-3">Cancel</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('page-js')
    <script>
        $(document).ready(function() {
            test.init();
            form.ajaxForm(
                "test_form",
                test.onResult,
                test.onSubmit,
                test.onErrors
            );
            form.evaluateRules("TestAddRequest");
        });
    </script>
@endsection