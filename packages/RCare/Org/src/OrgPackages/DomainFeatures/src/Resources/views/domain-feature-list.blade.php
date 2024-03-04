@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">

@endsection

@section('main-content')

<div class="breadcrusmb"> 

  <div class="row">
                <div class="col-md-11">
                   <h4 class="card-title mb-3">Domain Features</h4>
                </div>
                 <div class="col-md-12" style="text-align: right;">
                 <button type="button" class="btn btn-info btn-lg add-btn" data-toggle="modal"  id="add-btn" data-target="#myModal1">Add</button>
                </div>
              </div>
   <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">

            <div class="alert alert-success" style="display:none"><span id='suc_msg'></span></div>
            <div class="alert alert-danger" style="display:none"><span id='dan_msg'></span></div>
           <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="domain_features_list" class="display table table-striped table-bordered capital" style="width:100%">
                    <thead>
                        <tr>
                        <th>No</th>
                        <th>URL</th>
                        <th>Feature</th>
                        <th>Password Attempts</th>
                        <th>OTP Digit</th>
                        <th>OTP Attempts</th>
                        <th>No. Of Days</th>
                        <th>Session Timeout</th>
                        <th>Logout Popup Time</th>
                        <th>Redirect Idle time</th>
                        <th>Block time</th>
                        <th>Last Modified By</th>
                        <th>Last Modified On</th>
                        <th width="100px">Action</th>
                        </tr>
                    </thead> 
                    <tbody>

                    </tbody>
                </table>
                </div>

            </div>
        </div>
    </div>
</div>

   
<div class="modal fade" id="myModal1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1">Add Domain Features</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id ="domain_features_form" method="POST" name="domain_features_form" action="{{ route("createDomainFeatures") }}">
            <div class="modal-body">
            {{ csrf_field() }}
                 <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <input type="hidden" name="id" id="id" value="">
                            <label for="service_name">URL<span class="error">*</span> </label>
                            @text("url", ["id" => "url", "class" => "form-control", "placeholder" => "Enter API URL", 'url' => 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'])
                        </div>
                        <div class="col-md-6 form-group mb-3">
                          <label for="service_name">Features<span class="error">*</span> </label>
                            <select class="form-select form-control" name="features" id="features">
                              <option selected>Select Features</option>
                              <option value="2FA">Multifactor Authentication</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="service_name">Password Attempt<span class="error">*</span> </label>
                            @text("password_attempts", ["id" => "password_attempts", "class" => "form-control  ", "placeholder" => "Enter Password Attempt"])
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="service_name">Digit in OTP <span class="error">*</span> </label>
                            @text("digit_in_otp", ["id" => "digit_in_otp", "class" => "form-control  ", "placeholder" => "Enter OTP Digit"])
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="service_name">OTP Attempt<span class="error">*</span> </label>
                            @text("otp_max_attempts", ["id" => "otp_max_attempts", "class" => "form-control  ", "placeholder" => "Enter OTP Attempt"])
                        </div>
                        
                        <div class="col-md-6 form-group mb-3">
                            <label for="service_name">No. Of Days<span class="error">*</span> </label>
                            @text("no_of_days", ["id" => "no_of_days", "class" => "form-control  ", "placeholder" => "Enter No. Of Days"])
                        </div>
                        <div class="col-md-6  form-group mb-3"> 
                             <label for="icon">OTP Sent Mode : <span class="error">*</span> </label>
                             <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="otp_text" id="otp_text">
                                <label class="form-check-label" for="text">Text</label> 
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="otp_email" id="otp_email">
                                <label class="form-check-label" for="email">Mail</label>
                              </div>
                        </div>  
                        
                        <div class="col-md-6 form-group mb-3">
                                <label for="service_name">Session Timeout(In Minutes)<span class="error">*</span> </label>
                                @text("session_timeout", ["id" => "session_timeout", "class" => "form-control  ", "placeholder" => "Enter Session timeout "])
                        </div>

                        <div class="col-md-6 form-group mb-3">
                                <label for="service_name">Logout Popup Time(In Seconds)<span class="error">*</span> </label>
                                @text("logoutpoptime", ["id" => "logoutpoptime", "class" => "form-control  " ])  
                        </div>

                        <div class="col-md-6 form-group mb-3">
                                <label for="service_name">Redirect Idle Time(In Minute)<span class="error">*</span> </label>
                                @text("idle_time_redirect", ["id" => "idle_time_redirect", "class" => "form-control" ])  
                        </div>

                        <div class="col-md-4 form-group mb-3">
                                <label for="service_name">login Block Time(In Minute)<span class="error">*</span> </label>
                                @text("block_time", ["id" => "block_time", "class" => "form-control" ])  
                        </div>
                        <div class="col-md-4 form-group mb-3">
                                <label for="server_name">Instance Name</label>
                                @text("instance", ["id" => "instance", "class" => "form-control" ])
                        </div>
                        

                    </div>
                </div>    
            </div>
            <div class="card-footer">
                <div class="mc-footer">
                    <div class="row">
                        <div class="col-lg-12 text-right"> 
                            <button type="submit" id="save" class="btn  btn-primary m-1 add">Save</button>
                            <button type="button" id="sms" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form> 
        </div>
    </div>
</div>

@endsection 
@section('page-js')<!--Andy22Nov21-->
<!-- <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script> -->
<script src="{{asset(mix('assets/js/jquery.validate.min.js'))}}"></script>
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script src="{{asset('assets/js/tooltip.script.js')}}"></script>

<script type="text/javascript">
$("document").ready(function(){
  domainfeatureslistData();
  domainfeatures.init();
}); 
</script>
  <script type="text/javascript">
  // $(".add-btn").click(function(){
  //         $("input").removeClass("is-invalid");
  //         $("select").removeClass("is-invalid");
  //         $('.invalid-feedback').html('');
  //       $('#configuration-form-sms')[0].reset();  
  // });
    // $(".add").click(function(){
    //     var target = $(this).attr("id");
    //     $.ajax({
    //                 type: 'post',
    //                 url: '/configuration/createConfiguration',
    //                 data: $('#configuration-form-sms').serialize(), // getting filed value in serialize form
    //                 success: function(request){
    //                   // console.log(JSON.parse(JSON.stringify(request.success)) +'iii');
    //                       $("input").removeClass("is-invalid");
    //                       $("select").removeClass("is-invalid");
    //                       $('.invalid-feedback').html('');
    //                     $('#configuration-form-sms')[0].reset();
    //                     $('#myModal1').modal('hide'); 
    //                     $('.alert-success').show();
    //                     $('.alert-success').html(JSON.parse(JSON.stringify(request.success))); 
    //                     configlistData();
    //                       setTimeout(function(){ 
    //                          $("div.alert").fadeOut('fast');
    //                       },3000 ); // 5 secs
    //                 },
    //                 error: function (request, status, error) {
    //                     if(request.responseJSON.errors.username) {
    //                       $('[name="username"]').addClass("is-invalid");
    //                       $('[name="username"]').next(".invalid-feedback").html(request.responseJSON.errors.username);
    //                     } else {
    //                         $('[name="username"]').removeClass("is-invalid");
    //                         $('[name="username"]').next(".invalid-feedback").html('');
    //                     }
                        
    //                     if(request.responseJSON.errors.password){
    //                       $('[name="password"]').addClass("is-invalid");
    //                       $('[name="password"]').next(".invalid-feedback").html(request.responseJSON.errors.password);
    //                     } else {
    //                         $('[name="password"]').removeClass("is-invalid");
    //                         $('[name="password"]').next(".invalid-feedback").html('');
    //                     }
                        
    //                     if(request.responseJSON.errors.api_url){
    //                       $('[name="api_url"]').addClass("is-invalid");
    //                       $('[name="api_url"]').next(".invalid-feedback").html(request.responseJSON.errors.api_url);
    //                     }else{
    //                       $('[name="api_url"]').removeClass("is-invalid");
    //                       $('[name="api_url"]').next(".invalid-feedback").html('');
    //                     }
    //                     console.log('Error:', request);
    //                 }
    //             });
    // });

   //   $('body').on('click', '.edit_conf', function (){
   //     var id = $(this).data('id');
   //      $("input").removeClass("is-invalid");
   //      $("select").removeClass("is-invalid");
   //      $('.invalid-feedback').html('');
   //  //    alert(id);
   //     $.get("ajax/rCare/editConfiguration" +'/' + id +'/edit', function (data) {
   //         var username   =data[0].username;
   //         var password   =data[0].password;
   //         var api_url    =data[0].api_url;
   //         var from_name  =data[0].from_name;
   //         var from_email =data[0].from_email;
   //         var host       =data[0].host;
   //         var port       =data[0].port;
   //         var cc_email   =data[0].cc_email;
   //         var id         =data[0].id;
   //         var phone      =data[0].phone;
   //             $('#hidden_id').val(id);
   //             $('#sms_hidden_id').val(id);
   //             $('#username').val(username);
   //             $('#user_name').val(username);
   //             $('#pass').val(password);
   //             $('#password').val(password);
   //             $('#api_url').val(api_url);
   //             $('#from_name').val(from_name);
   //             $('#from_email').val(from_email);
   //             $('#host').val(host);
   //             $('#port').val(port);
   //             $('#cc_email').val(cc_email);
   //          $("#phone").val(phone);
   //        })
   // });

// update status

// $('body').on('click', '.update', function () {
//     var id = $(this).data("id");
//     var checkstr = confirm("Are You sure want to update !");
//     if(checkstr == true){
//         $.ajax({
//             type: "post",
//             url: "ajax/rCare/changeConfigurationStatus"+'/'+ id +'/update',
//             data: {
//             "_token": "{{ csrf_token() }}"
//             },
//             success: function (data){
//               //alert(data);
//                 configlistData();
//                 setTimeout(function(){
//                    $("div.alert").fadeOut('fast');
//                 }, 5000 ); // 5 secs
//             },
//             error: function (data) {
//                 console.log('Error:', data);
//             }
//         });
//     }else{
//         return false;
//     }
// });

var domainfeatureslistData = function(){
  var columns= [
      {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'url', name: 'url'},
                  {data: 'features', name: 'features'},
                  {data: 'password_attempts', name: 'password_attempts'},
                  {data: 'digit_in_otp', name: 'digit_in_otp'},
                  {data: 'otp_max_attempts', name: 'otp_max_attempts'},
                  {data: 'no_of_days', name: 'no_of_days'},
                  {data: 'session_timeout', name: 'session_timeout'},  
                  {data: 'logoutpoptime', name: 'logoutpoptime'},
                  {data: 'idle_time_redirect', name: 'idle_time_redirect'},  
                  {data: 'block_time', name: 'block_time'},  
                  {data: 'users', mRender: function(data, type, full, meta){
                    if(data!='' && data!='NULL' && data!=undefined){
                        if(data['l_name'] == null && data['f_name'] == null){
                            return '';
                        }else{
                            return data['f_name'] + ' ' + data['l_name'];
                        }
                    }else { return '';}
                  },orderable: false},
                  {data: 'updated_at', name: 'updated_at'},
                  {data: 'action', name: 'action', orderable: false, searchable: false},
          ];
        var table = util.renderDataTable('domain_features_list', "{{ route('fetchDomainFeatures') }}", columns, "{{ asset('') }}");   
};
</script>

     <!-- <script>
        $(document).ready(function() {
            configurations.init();
            form.ajaxForm(
                "configuration",
                configurations.onResult,
                configurations.onSubmit,
                configurations.onErrors
            );
            form.evaluateRules("ConfigurationAddRequest");
        });
    </script> -->
@endsection
