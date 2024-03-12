@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">

@endsection

@section('main-content')
<div class="breadcrusmb">

    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Users</h4>
        </div>
        <div class="col-md-1">
            <a class="" href="javascript:void(0)" id="addUser"><i class="add-icons i-Administrator" data-toggle="tooltip" data-placement="top" title="Add User"></i><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add User"></i></a>  
        </div>
    </div>
   <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
      
 <div class="row mb-4">
  <div class="col-md-12 mb-4">
    <div class="card text-left">      
      <div class="card-body"> 
                @include('Theme::layouts.flash-message')      
        <div class="table-responsive">
          <table id="user-list" class="display table table-striped table-bordered capital" style="width:100%">
                        <thead>
                            <tr>
                                <th width="50px">Sr No.</th>
                                <th>User</th>
                                <!-- <th></th>
                                <th></th> -->
                                <th>Email</th>
                                <th width="100px">Role</th>               
                                <th width="60px">Action</th>
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
     

<div class="modal fade bd-example-modal-lg" id="edit_user_model" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
             <div class="modal-header">
                <h4 class="modal-title" id="edit_user_model">Edit User</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none"></div>
                <form action="{{route('admin_update_User')}}"  id="edituser-validation" enctype="multipart/form-data" method="POST" class="form-horizontal">
               
                
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 form-group mb-3" >
                               <label for="username">First Name</label>
                               @text("f_name", ["id" => "f_name", "class" => "capital-first"])
                               
                           </div>
                           <div class="col-md-6 form-group mb-3">

                            <label for="username">Last Name</label>
                            @text("l_name", ["id" => "l_name", "class" => "capital-first"])
                          
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                           <label for="email">Email Address</label>
                           @email("email", ["id" => "email", "class" => "form-control "])
                          
                       </div>
                       <div class="col-md-6 form-group mb-3">

                           <label for="Status">Status</label>
                           @selectactivestatus("status",["id" => "status", "class" => "form-control"])

                       </div>
                       <div class="col-md-6 form-group mb-3">
                           <label for="role">Role</label>
                           @selectrole("role", ["id" => "role"])
                           
                       </div>

                   <div class="col-md-6 form-group mb-3">
                    <br>
                      <div _ngcontent-fcp-c8="" class="custom-file"><input _ngcontent-fcp-c8="" class="custom-file-input" id="select_file"  name="select_file" type="file"><label _ngcontent-fcp-c8="" aria-describedby="inputGroupFileAddon02" class="custom-file-label" for="inputGroupFile02">Choose file</label></div>
                          <!--  <label for="role">Select Profile Image</label>
                            <input type="file" name="select_file" id="select_file">
                            <span id="profile_img"></span> -->
                           
                       </div> 
                   </div>
               </div>
          

           <div class="card-footer">
            <div class="mc-footer">
                <div class="row">
                    <div class="col-lg-12 text-right">
                        <button type="submit" class="btn  btn-primary m-1">Save changes</button>
                        <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

      
    
            </div>
        </div>
    </div>
</div>

  

    <div class="modal fade" id="add_user_model" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title" id="add_user_model">Add User</h4>
                <button type="button" class="close" data-dismiss="modal" onClick="form_clear()">&times;</button>
            </div>
           
                <div class="modal-body">
                   <form action="{{route('create_user')}}"  id="adduser-validation" enctype="multipart/form-data" method="POST" class="form-horizontal">
                    {{ csrf_field() }}
                     
                   
                    <div class="form-group">
                       <div class="row">
                            <div class="col-md-6  form-group mb-3">
                                <label for="f_name">First Name</label>
                                @text("f_name", ["id" => "txtName","placeholder" => "Enter First Name", "class" => "capital-first"])
                                <span id="lblError" style="color: red"></span>

                  
                               
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="username">Last Name</label>
                                @text("l_name", ["id" => "txtName1", "placeholder" => "Enter Last Name", "class" => "capital-first"])
                                <span id="lblError1" style="color: red"></span>
                                
                            </div>
                       </div>

                       <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="email">Email Address</label>
                                @email("email", ["id" => "email",  "placeholder" => "Enter Email Id"])
                                
                            </div>
                            <div class="col-md-6 form-group mb-3">
                               <label for="role">Role</label>
                               @selectrole("role", ["id" => "role"])
                              
                           </div>
                       </div>

                       <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="password">Password</label>
                                @password("password", ["id" => "password",  "placeholder" => "Enter Password"])
                                
                            </div>

                            <div class="col-md-6 form-group mb-3" >
                                <label for="repassword">Confirm Password</label>
                                @password("password_confirmation", ["id" => "repassword",  "placeholder" => "Confirm password"])
                               
                            </div>
                       </div>
                       
                       <div class="row">
                          <div class="col-md-12 form-group mb-3">
                        <div _ngcontent-fcp-c8="" class="custom-file"><input _ngcontent-fcp-c8="" class="custom-file-input" id="select_file"  name="select_file" type="file"><label _ngcontent-fcp-c8="" aria-describedby="inputGroupFileAddon02" class="custom-file-label" for="inputGroupFile02">Choose file</label></div>
                           <!--  <div class="col-md-6 form-group mb-3">
                                <label for="password">Select Profile Image</label>
                                <input type="file" name="select_file" id="select_file"> -->
                                
                            </div>
                       </div>
                    </div>
                   
               
                 <div class="card-footer">
                        <div class="mc-footer">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit"  class="btn  btn-primary m-1">Add User</button>
                                    <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal" >Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
              </div>
            </div>
        </div>
    </div>

 
@endsection

@section('page-js')
  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script type="text/javascript">

 var columns = [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'profile_img',name: 'profile_img',
                mRender: function(data, type, full, meta){
                if(data!='' && data!='NULL' && data!=undefined){
                    return ["<img src={{ URL::to('/') }}/images/usersRcare/" + data + " width='40px' class='user-image' />"]+' '+full['f_name']+' '+full['l_name'];
                } else {  
                    return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['f_name']+' '+full['l_name'];
                }
            },
            orderable: false
        },
                   /* {data: 'f_name', name: 'f_name'},
                    {data: 'l_name', name: 'l_name'},*/
                    {data: 'email', name: 'email'},
                    {data: 'roles.role_name', name: 'roles.role_name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
    var table = util.renderDataTable('user-list', "{{ route('user_list') }}", columns, "{{ asset('') }}");  
          
            $('body').on('click', '.edit', function () {
                var id = $(this).data('id');
                $.get("ajax/rCare/editUser" +'/' + id +'/edit', function (data) {
                    $('#modelHeading').html("Edit Product");
                    $('#saveBtn').val("edit-user");
                    $('#edit_user_model').modal('show');
                    $('#id').val(data.id);
                    $('#l_name').val(data.l_name);
                    $('#f_name').val(data.f_name);
                    $('#email').val(data.email);
                    $('#status').val(data.status);
                    $('#role').val(data.role);
                })
            });

            $('#addUser, .addusers').click(function () {
                $('#saveBtn').val("create-product");
                // $('#product_id').val('');
                // $('#productForm').trigger("reset");
                // $('#modelHeading1').html("Add User");
                $('#add_user_model').modal('show');
            });


           

      

    </script>
    <script type="text/javascript">

 if ($("#adduser-validation").length > 0) {
    $("#adduser-validation").validate({

        rules: {
          f_name: {
            required: true,
            
        },

        l_name: {
            required: true,
            
        },

        email: {
            required: true,
            
        },


        role: {
            required: true,

        }, 

        password: {
            required: true,
            
        },  

        password_confirmation: {
            required: true,

        },

        select_file :{
            required :true,
        } , 
    },
    messages: {

      f_name: {
        required: "Please enter first name ",
        maxlength: "Your last name maxlength should be 50 characters long."

    },
    l_name: {
        required: "Please enter last name",
        
    },

    role: {
      required: "Please select role",

  },

  email: {
      required: "Please enter email id",

  },

  password_confirmation: {
      required: "Please enate Confirm password",

  },
  password: {
      required: "Please enter password",

  },
  select_file :{
            required : "Please select Image file",
        } , 
},

});
}

if ($("#edituser-validation").length > 0) {
    $("#edituser-validation").validate({

        rules: {
          f_name: {
            required: true,
            
        },

        l_name: {
            required: true,
            
        },

        email: {
            required: true,
            
        },


        role: {
            required: true,

        }, 

        password: {
            required: true,
            
        },  

        password_confirmation: {
            required: true,

        },

        select_file :{
            required :true,
        } , 
    },
    messages: {

      f_name: {
        required: "Please enter first name",
        maxlength: "Your last name maxlength should be 50 characters long."

    },
    l_name: {
        required: "Please enter last name",
        
    },

    role: {
      required: "Please select role",

  },

  email: {
      required: "Please enter email id",

  },

  password_confirmation: {
      required: "Please enate Confirm password",

  },
  password: {
      required: "Please enter password",

  },
  select_file :{
            required : "Please select Image file",
        } , 
},

});
}

 
 $(document).ready(function() { 
            if(window.location.href == 'http://rcareprototype.d-insights.global/admin/users#add_user_model'){
                $('#saveBtn').val("create-product");
              /*  $('#product_id').val('');
                $('#productForm').trigger("reset");
                /*$('#modelHeading1').html("Add User");*/
                $('#add_user_model').modal('show');
             }
         });


           function form_clear() {
            document.getElementById("user-validation").reset();
            }

    $(function () {
        $("#txtName").keypress(function (e) {
            var keyCode = e.keyCode || e.which;
 
            $("#lblError").html("");
 
           
            var regex = /^[a-zA-Z]+$/;
 

            var isValid = regex.test(String.fromCharCode(keyCode));
            if (!isValid) {
                $("#lblError").html("Only Alphabets allowed.");
            }
 
            return isValid;
        });
    });   

        $(function () {
        $("#txtName1").keypress(function (e) {
            var keyCode = e.keyCode || e.which;
 
            $("#lblError1").html("");
 
           
            var regex = /^[a-zA-Z]+$/;
 

            var isValid = regex.test(String.fromCharCode(keyCode));
            if (!isValid) {
                $("#lblError1").html("Only Alphabets allowed.");
            }
 
            return isValid;
        });
    });   
       $("document").ready(function(){
    setTimeout(function(){
       $("div.alert").remove();
    }, 5000 ); // 5 secs

});
       
</script>
<script>
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>

@endsection

 
