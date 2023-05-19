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
        <div class="row">
        <div class="col-md-6">
            <div class="form-group capitalize">
                <label for="user">User</label>
                @selectuser("user")
                <!-- <select class="" name="user" data-live-search="1" title="Select User" data-style="custom-select" data-feedback="T7tP3xEmcX" tabindex="-98">
                    <option class="bs-title-option" value="">Select User</option>
                    <option value="">None</option>
                    <option value="157">Demo Demo</option>
                    <option value="110">mama mama</option>
                    <option value="136">manuuu1 manuuu</option>
                    <option value="133">Pranali Swapnil</option>
                    <option value="112">rani arun</option>
                    <option value="45">RCare Admin</option>
                    <option value="145">rajuuu kaluuuuu</option>
                    <option value="154">crazy girl</option>
                    <option value="162">rajurajan rajan</option>
                    <option value="24">ff ff</option>
                    <option value="27">test test</option>
                    <option value="29">ccc cc</option>
                    <option value="63">pritush shinde</option>
                    <option value="149">dadar dadar</option>
                    <option value="130">Smita Rahate</option>
                    <option value="131">ABC DEF</option>
                    <option value="129">rahul kam</option>
                    <option value="111">priya singh</option>
                    <option value="122">hhfff ffff</option>
                    <option value="134">mamu mamu</option>
                    <option value="121">egresdfdas qDEWQEW</option>
                    <option value="126">egreaaaa add</option>
                    <option value="120">fffdddddddd wefqwf</option>
                    <option value="113">egreweee safewfw</option>
                    <option value="101">ramu rajan</option>
                    <option value="137">manuuu manuuu</option>
                    <option value="115">dummy dummy</option>
                    <option value="116">fffeee qweqwre2</option>
                    <option value="28">w w</option>
                    <option value="102">kamal raja</option>
                    <option value="118">wfefwf dsffeger</option>
                    <option value="119">qewqrqewrewq qe33</option>
                    <option value="123">egredccde weqe</option>
                    <option value="127">wqerqr werwerewq</option>
                    <option value="128">weewf </option>
                    <option value="124">gsert ertertert</option>
                    <option value="117">egrewerwq werqtrrewq</option>
                    <option value="114">egreqwee wqre</option>
                    <option value="140">ambani ambani</option>
                    <option value="143">mohini kale</option>
                    <option value="146">priaaa kale</option>
                    <option value="51">pran p</option>
                    <option value="26">ss sss</option>
                    <option value="95"> </option>
                    <option value="25">ss ss</option>
                    <option value="43">aditya borkar</option>
                    <option value="103">ashok raja</option>
                    <option value="91">egre sds</option>
                    <option value="92">egreddd dsafdf</option>
                    <option value="37">test test</option>
                    <option value="104">kalu wer</option>
                    <option value="52">jogi jogi</option>
                    <option value="105">test test</option>
                    <option value="93">egre ereewtr</option>
                    <option value="100">raju kumar</option>
                    <option value="106">vikram kalu</option>
                    <option value="109">sddd wwww</option>
                    <option value="108">smita smita</option>
                    <option value="135">mamu mamu</option>
                    <option value="138">resu resu</option>
                    <option value="141">ambani ambani</option>
                    <option value="144">rajanee sharama</option>
                    <option value="147">dadar dadar</option>
                    <option value="150">Smita Hanchate</option>
                    <option value="153">hey hey</option>
                    <option value="156">Demo Demo</option>
                </select> -->
            </div>
        </div>
    </div>
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
            form.addAutofillRules(
                "test_form",
                {!! json_encode(config("test")["autofill"]) !!}
            );
            form.evaluateRules("TestAddRequest");
        });
    </script>
@endsection