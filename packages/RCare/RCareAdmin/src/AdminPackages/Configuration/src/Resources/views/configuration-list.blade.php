@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">


@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
                <div class="col-md-11">
                   <h4 class="card-title mb-3">Server Configuration</h4>
                </div>
                 <div class="col-md-12" style="text-align: right;">
                 <button type="button" class="btn btn-info btn-lg add-btn" data-toggle="modal" data-target="#myModal1" >SMS</button>
                 <button type="button" class="btn btn-info btn-lg add-btn" data-toggle="modal" data-target="#myModal">Email</button>
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
                    <table id="config_list" class="display table table-striped table-bordered capital" style="width:100%">
                    <thead>
                        <tr>
                        <th>No</th>
                        <th>Account SID</th>
                        <th>Auth Token</th>
                        <th>Phone Number</th>
                        <th>API URL</th>
                        <th>Form Email</th>
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


<div class="modal fade" id="myModal" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id=" myModal modelHeading">Email Configuration</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id ="configuration-form" method="POST" name="configuration" action="{{ route("createConfiguration") }}">
            <div class="modal-body">
            {{ csrf_field() }}
                 <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <?php ?>
                            <input type="hidden" name="config_type" id="config_type" value="email">
                            <input type="hidden" name="hidden_id" id="hidden_id">
                            <label for="service_name">From Name <span class='error'>*</span></label>
                            @text("from_name", ["id" => "from_name", "class" => "form-control", "placeholder" => "Enter From Name"])
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="service_name">From Email <span class='error'>*</span></label>
                            @text("from_email", ["id" => "from_email", "class" => "form-control  ", "placeholder" => "Enter From Email"])
                        </div>
                        <div class="col-md-6  form-group mb-3">
                             <label for="icon">Host <span class='error'>*</span></label>
                             @text("host", ["id" => "host", "class" => "form-control  ", "placeholder" => "Enter host"])
                        </div>    

                        <div class="col-md-6 form-group mb-3">
                            <label for="loginuser" class="">User Name <span class='error'>*</span></label>
                             @text("user_name", ["id" => "user_name", "class" => "form-control  ", "placeholder" => "Enter User Name"])
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="loginuser" class="">Password <span class='error'>*</span></label>
                             @text("pass", ["id" => "pass", "class" => "form-control  ", "placeholder" => "Enter Password"])
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="loginuser" class="">Port <span class='error'>*</span></label>
                             @text("port", ["id" => "port", "class" => "form-control  ", "placeholder" => "Enter Port"])
                        </div> 

                         <div class="col-md-6 form-group mb-3">
                            <label for="loginuser" class="">Cc Email <span class='error'>*</span></label>
                             @text("cc_email", ["id" => "cc_email", "class" => "form-control  ", "placeholder" => "Enter Cc Email"])
                        </div>      
                        
                        <!--div class="col-md-12">
                            <div class="card-header">
                                <button type="button" id="add_menu" class="btn btn-primary">Add Menu</button>
                                <button type="button" class="btn btn-danger" style="float:right" data-dismiss="modal">Close</button>
                            </div>    
                        </div-->
                        
                    </div>
                </div>    
            </div>
            <div class="card-footer">
                <div class="mc-footer">
                    <div class="row">
                        <div class="col-lg-12 text-right">
                            <button type="button" id="email" class="btn btn-primary m-1 add">Save</button>
                            <button type="button" id="email" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form> 
        </div>
    </div>
</div>

   
<div class="modal fade" id="myModal1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id=" myModal1 modelHeading">SMS Configuration</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id ="configuration-form-sms" method="POST" name="sms_configuration" action="">
            <div class="modal-body">
            {{ csrf_field() }}
                 <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <input type="hidden" name="hidden_id" id="sms_hidden_id" value="">
                            <input type="hidden" name="config_type" id="sms_config_type" value="sms">
                            <label for="service_name">Account SID<span class="error">*</span> </label>
                            @text("username", ["id" => "username", "class" => "form-control  ", "placeholder" => "Enter User Name"])
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="service_name">Auth Token<span class="error">*</span> </label>
                            @text("password", ["id" => "password", "class" => "form-control  ", "placeholder" => "Enter Password"])
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="service_name">Phone Number<span class="error">*</span> </label>
                            @text("phone", ["id" => "phone", "class" => "form-control  ", "placeholder" => "Enter phone Number"])
                        </div>
                        <div class="col-md-6  form-group mb-3">
                             <label for="icon">Enter API URL<span class="error">*</span> </label>
                             @text("api_url", ["id" => "api_url", "class" => "form-control   ", "placeholder" => "Enter API URL", 'url' => 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'])
                        </div>    
                    </div>
                </div>    
            </div>
            <div class="card-footer">
                <div class="mc-footer">
                    <div class="row">
                        <div class="col-lg-12 text-right"> 
                            <button type="button" id="sms" class="btn  btn-primary m-1 add">Save</button>
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
  configlistData();
  // configurations.init();
    util.getToDoListData(0, {{getPageModuleName()}});
});
</script>
  <script type="text/javascript">
  $(".add-btn").click(function(){
          $("input").removeClass("is-invalid");
          $("select").removeClass("is-invalid");
          $('.invalid-feedback').html('');
        $('#configuration-form')[0].reset();  
        $('#configuration-form-sms')[0].reset();  
  });
    $(".add").click(function(){
        var target = $(this).attr("id");
        if(target == 'email'){
            $.ajax({
                type: 'post',
                url: '/configuration/createConfiguration',
                data: $('#configuration-form').serialize(), // getting filed value in serialize form
                success: function(request){
                        $("input").removeClass("is-invalid");
                        $("select").removeClass("is-invalid");
                        $('.invalid-feedback').html('');
                    $('#configuration-form')[0].reset();  
                    $('#myModal').modal('hide');
                    $(".alert-success").show();
                    $(".alert-success").html(JSON.parse(JSON.stringify(request.success)));
                    configlistData();
                      setTimeout(function(){
                         $("div.alert").remove();
                      },3000 ); // 5 secs
                },
                error: function (request, status, error) {
                     if(request.responseJSON.errors.user_name){
                      $('[name="user_name"]').addClass("is-invalid");
                      $('[name="user_name"]').next(".invalid-feedback").html(request.responseJSON.errors.user_name);
                    }else{
                      $('[name="user_name"]').removeClass("is-invalid");
                      $('[name="user_name"]').next(".invalid-feedback").html('');
                    }
                    
                    if(request.responseJSON.errors.pass){
                      $('[name="pass"]').addClass("is-invalid");
                      $('[name="pass"]').next(".invalid-feedback").html(request.responseJSON.errors.pass);
                    }else{
                      $('[name="pass"]').removeClass("is-invalid");
                      $('[name="pass"]').next(".invalid-feedback").html('');
                    }
                    
                    if(request.responseJSON.errors.from_name){
                      $('[name="from_name"]').addClass("is-invalid");
                      $('[name="from_name"]').next(".invalid-feedback").html(request.responseJSON.errors.from_name);
                    }else{
                      $('[name="from_name"]').removeClass("is-invalid");
                      $('[name="from_name"]').next(".invalid-feedback").html('');
                    }
                    
                    if(request.responseJSON.errors.from_email){
                      $('[name="from_email"]').addClass("is-invalid");
                      $('[name="from_email"]').next(".invalid-feedback").html(request.responseJSON.errors.from_email);
                    }else{
                      $('[name="from_email"]').removeClass("is-invalid");
                      $('[name="from_email"]').next(".invalid-feedback").html('');
                    }
                    
                    if(request.responseJSON.errors.host){
                      $('[name="host"]').addClass("is-invalid");
                      $('[name="host"]').next(".invalid-feedback").html(request.responseJSON.errors.host);
                    }else{
                      $('[name="host"]').removeClass("is-invalid");
                      $('[name="host"]').next(".invalid-feedback").html('');
                    }
                    
                    if(request.responseJSON.errors.port){
                      $('[name="port"]').addClass("is-invalid");
                      $('[name="port"]').next(".invalid-feedback").html(request.responseJSON.errors.port);
                    }else{
                      $('[name="port"]').removeClass("is-invalid");
                      $('[name="port"]').next(".invalid-feedback").html('');
                    }
                    
                    if(request.responseJSON.errors.cc_email){
                      $('[name="cc_email"]').addClass("is-invalid");
                      $('[name="cc_email"]').next(".invalid-feedback").html(request.responseJSON.errors.cc_email);
                    }else {
                        $('[name="cc_email"]').removeClass("is-invalid");
                        $('[name="cc_email"]').next(".invalid-feedback").html('');
                    }
                    console.log('Error:', data);
                }
            });
        } else if(target == 'sms') {
            $.ajax({
                type: 'post',
                url: '/configuration/createConfiguration',
                data: $('#configuration-form-sms').serialize(), // getting filed value in serialize form
                success: function(request){
                  // console.log(JSON.parse(JSON.stringify(request.success)) +'iii');
                      $("input").removeClass("is-invalid");
                      $("select").removeClass("is-invalid");
                      $('.invalid-feedback').html('');
                    $('#configuration-form-sms')[0].reset();
                    $('#myModal1').modal('hide'); 
                    $('.alert-success').show();
                    $('.alert-success').html(JSON.parse(JSON.stringify(request.success))); 
                    configlistData();
                      setTimeout(function(){ 
                         $("div.alert").fadeOut('fast');
                      },3000 ); // 5 secs
                },
                error: function (request, status, error) {
                    if(request.responseJSON.errors.username) {
                      $('[name="username"]').addClass("is-invalid");
                      $('[name="username"]').next(".invalid-feedback").html(request.responseJSON.errors.username);
                    } else {
                        $('[name="username"]').removeClass("is-invalid");
                        $('[name="username"]').next(".invalid-feedback").html('');
                    }
                    
                    if(request.responseJSON.errors.password){
                      $('[name="password"]').addClass("is-invalid");
                      $('[name="password"]').next(".invalid-feedback").html(request.responseJSON.errors.password);
                    } else {
                        $('[name="password"]').removeClass("is-invalid");
                        $('[name="password"]').next(".invalid-feedback").html('');
                    }
                    
                    if(request.responseJSON.errors.api_url){
                      $('[name="api_url"]').addClass("is-invalid");
                      $('[name="api_url"]').next(".invalid-feedback").html(request.responseJSON.errors.api_url);
                    }else{
                      $('[name="api_url"]').removeClass("is-invalid");
                      $('[name="api_url"]').next(".invalid-feedback").html('');
                    }
                    console.log('Error:', request);
                }
            });
        }
    });

     $('body').on('click', '.edit_conf', function (){
       var id = $(this).data('id');
        $("input").removeClass("is-invalid");
        $("select").removeClass("is-invalid");
        $('.invalid-feedback').html('');
    //    alert(id);
       $.get("ajax/rCare/editConfiguration" +'/' + id +'/edit', function (data) {
           var username   =data[0].username;
           var password   =data[0].password;
           var api_url    =data[0].api_url;
           var from_name  =data[0].from_name;
           var from_email =data[0].from_email;
           var host       =data[0].host;
           var port       =data[0].port;
           var cc_email   =data[0].cc_email;
           var id         =data[0].id;
           var phone      =data[0].phone;
               $('#myModal1 modelHeading').text("Edit Configuration");
               $('#myModal modelHeading').text("Edit Configuration");
               $('#hidden_id').val(id);
               $('#sms_hidden_id').val(id);
               $('#username').val(username);
               $('#user_name').val(username);
               $('#pass').val(password);
               $('#password').val(password);
               $('#api_url').val(api_url);
               $('#from_name').val(from_name);
               $('#from_email').val(from_email);
               $('#host').val(host);
               $('#port').val(port);
               $('#cc_email').val(cc_email);
            $("#phone").val(phone);
           })
   });

// update status

$('body').on('click', '.update', function () {
    var id = $(this).data("id");
    var checkstr = confirm("Are You sure want to update !");
    if(checkstr == true){
        $.ajax({
            type: "post",
            url: "ajax/rCare/changeConfigurationStatus"+'/'+ id +'/update',
            data: {
            "_token": "{{ csrf_token() }}"
            },
            success: function (data){
              //alert(data);
                configlistData();
                setTimeout(function(){
                   $("div.alert").fadeOut('fast');
                }, 5000 ); // 5 secs
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }else{
        return false;
    }
});

var configlistData = function() {
  var columns= [
      {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'username', name: 'username'},
                  {data: 'password', name: 'password'},
                  {data: 'phone', name: 'phone'},
                  {data: 'api_url', name: 'api_url'},
                  {data: 'from_email', name: 'from_email'},
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
  var table = util.renderDataTable('config_list', "{{ route('fetchConfiguration') }}", columns, "{{ asset('') }}");   
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
