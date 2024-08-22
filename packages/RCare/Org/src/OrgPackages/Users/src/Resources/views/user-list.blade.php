@extends('Theme::layouts_2.master')
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
			@feature(9,7,'c')
			<a class="" href="javascript:void(0)" id="addUser"><i class="add-icons i-Administrator" data-toggle="tooltip" data-placement="top" title="Add User"></i><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add User"></i></a>  
			@endfeature		 
		</div>
    </div>
   <!-- ss -->             
</div> 


<div class="separator-breadcrumb border-top"></div>
 <div id="success"></div> 
 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">                    
           <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="usersList" class="display table table-striped table-bordered capital" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sr No.</th>
                            <th>User</th>
                            <th>Email</th> 
                            <th>Number</th> 
                            <th>Created At</th>
                            <th>Role</th>		
                            <th>Last Modified By</th>
                            <th>Last Modified On</th>						
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-md" id="user_modal" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
             <div class="modal-header">
                <h4 class="modal-title" id="user_modal_heading"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('create_Users') }}" id="user_form" name="user_form" method="POST" enctype="multipart/form-data"  class="form-horizontal">
                <div class="modal-body">
                        {{ csrf_field() }} 
                        <input type="hidden" name="id" id="id">
                        
                        <div class="form-group">
                            
                            <div class="row">
                                <div class="col-md-4 form-group mb-3" >
                                   <label for="username"><span class="error">*</span> First Name</label>
                                   @text("f_name", ["id" => "txtName", "class" => "capital-first "])
                               </div>
                               <div class="col-md-4 form-group mb-3">
                                    <label for="username">Middle Name</label>
                                    @text("m_name", ["id" => "m_name","class" => "capital-first"])
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label for="username"><span class="error">*</span> Last Name</label>
                                    @text("l_name", ["id" => "l_name","class" => "capital-first"])
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mb-3" >
                                   <label for="dob"><span class="error">*</span> Date of Birth</label>
									@date("dob")
                               </div>
                               <div class="col-md-6 form-group mb-3">
                                    <label for="gender"><span class="error">*</span> Gender</label>
                                    @select("Gender", "gender", [ 0 => "Male", 1 => "Female"])
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 form-group mb-3">
                                    <label for="city">City</label>
                                    @text("city", ["id" => "city", "class" => "capitalize"])
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="email"><span class="error">*</span> Email Address</label>
                                    @email("email", ["id" => "email", "class" => "email"])
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="password"><span class="error">*</span> Password</label>
                                @password("password", ["id" => "password",  "placeholder" => "Enter Password"])
                            </div>
                            <div class="col-md-6 form-group mb-3" >
                                <label for="repassword"><span class="error">*</span> Confirm Password</label>
                                @password("password_confirmation", ["id" => "password_confirmation",  "placeholder" => "Confirm password"])
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
								<label for="country_code"><span class="error">*</span> Country Code</label>
								@selectcountrycode("country_code", ["id" => "country_code"]) 
							</div> 
                            <div class="col-md-6 form-group mb-3">
                                <label for="number"><span class="error">*</span> Contact Number (MFA)</label>
                                 @phone("number", ["id"=> "number"])
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="addr1">Address</label>
                                    @text("address", ["id" => "address"])
                                </div>
                            </div>
                            
                        </div>
                       
                        <div class="separator-breadcrumb border-top"></div>
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="role"><span class="error">*</span> Role</label>
                                @selectorgrole("role", ["id" => "roles"])
                            </div>
                        </div>
                       <div class="row" id ="providers_details">
                            <div class="col-md-6 form-group mb-3" > 
                                <label for="extn">Licenese Number</label>
                                @text("licenese_number", ["id" =>"licenese_number"])
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label for="speciality_id"><span class="error">*</span> Speciality</label>
                                @selectspeciality("speciality_id", ["id" => "speciality_id"])
                            </div>
                            <div class="col-md-6 form-group mb-3" > 
                                <label for="extn">Qualification</label>
                                @text("qualification", ["id" =>"qualification"])
                            </div>
                        </div>
                        <div class="row"> 
                            
                        </div>
                        <div class="separator-breadcrumb border-top"></div>
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <span id="button_div"></span>
                                <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal" onClick="refresh">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> 

<div class="modal fade bd-example-modal-lg " id="edit_user_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">  
             <div class="modal-header">
                <h4 class="modal-title" id="user_modal_heading">Edit User</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
            <div class="modal-body">
                    <ul class="nav nav-tabs" id="editUserTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="basic-details-tab" data-toggle="tab" href="#basicDetails" role="tab" aria-controls="basicDetails" aria-selected="true">Basic Details</a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link " id="manage-password-tab" data-toggle="tab" href="#managePassword" role="tab" aria-controls="managePassword" aria-selected="false">Change Password</a>
                        </li> 

                        <li class="nav-item">
                            <a class="nav-link" id="mfa-tab" data-toggle="tab" href="#mfa" role="tab" aria-controls="mfa" aria-selected="false">2Fa Config</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="editUserTabContent">
                        <div class="tab-pane fade show active" id="basicDetails" role="tabpanel" aria-labelledby="basic-details-tab">
                            <form action="{{ route("ajax.user.details.update") }}" id="user_details"  enctype="multipart/form-data" name="user_details" method="POST" class="form-horizontal">
                            {{ csrf_field() }}
                            
                            <div class="card mb-4">
                                <div class="card-header">Change User Details</div>
                                <!--begin::form-->
                                <div id="edit-user-msg"></div>
                                    <input type="hidden" name="id" id="id">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4 form-group mb-3" >
                                                   <label for="username"><span class="error">*</span> First Name</label>
                                                    @text("f_name", ["id" =>"edit-f-name", "class" => "capital-first"])

                                               </div>
                                               <div class="col-md-4 form-group mb-3">
                                                    <label for="username"><span class="error">*</span> Last Name</label>
                                                    @text("l_name", ["id" => "edit-l-name", "class" => "capital-first"])
                                                </div>
                                                <div class="col-md-4 form-group mb-3">
                                                    <label for="username"> Middle Name</label>
                                                    @text("m_name", ["id" => "edit-m-name", "class" => "capital-first"])
                                                </div> 
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 form-group mb-3">
                                                    <label for="email"><span class="error">*</span> Email Address</label>
                                                     @email("email", ["id" => "edit-email", "class" => " "])
                                                </div>
                                                <div class="col-md-2 form-group mb-3">
                                                    <label>Country Code</label>
                                                    @selectcountrycode("country_code", ["id" => "edit_country_code"]) 
                                                </div> 
                                                <div class="col-md-2 form-group mb-3">
                                                    <label for="password"><span class="error">*</span> Contact Number (MFA)</label>
                                                        @phone("number", ["id"=> "edit-number"])
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="separator-breadcrumb border-top"></div>
                                        <div class="row">
                                            <div class="col-md-6 form-group mb-3">
                                                <label for="role"><span class="error">*</span> Role</label>
                                                 @selectorgrole("role", ["id" => "edit-roles"])
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 form-group mb-3" >
                                                <label for="Status"><span class="error">*</span>Status</label>
                                                @selectactivestatus("status",["id" => "edit-emp-status", "class" => " "])
                                            </div>
                                        </div>
                                        <div class="separator-breadcrumb border-top"></div>        
                                        <div class="card-footer">
                                            <div class="mc-footer">
                                                <div class="row">
                                                    <div class="col-lg-12 text-right">
                                                        <button type="button" class="btn  btn-primary m-1" id="edit-name-btn">Update Changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                 <!-- end::form -->    
                            </div>
                            </form>     
                        </div>
                       <!--  <li class="nav-item">
                            <a class="nav-link " id="manage-password-tab" data-toggle="tab" href="#managePassword" role="tab" aria-controls="managePassword" aria-selected="false">Change Password</a>
                        </li> 
 -->
                         <div class="tab-pane fade" id="managePassword" role="tabpanel" aria-labelledby="manage-password-tab-tab">
                            <div class="card mb-4">
                                <div class="card-header">
                                    Change Password
                                </div>
                                <!--begin::form-->
                                <div id="edit-user-password-msg"></div>
                                <form action="{{ route("ajax.user.password.update") }}" id="change_password" name="change_password" method="POST" class="form-horizontal">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" id="id">
                                    <div class="card-body">
                                        <div class="form-row ">
                                            <div class="col-md-6 form-group mb-3">
                                                <label for="password"><span class="error">*</span> Password</label>
                                                @password("password", ["id" => "edit-password",  "placeholder" => "Enter Password"])
                                            </div>
                                            <div class="col-md-6 form-group mb-3" >
                                                <label for="repassword"><span class="error">*</span> Confirm Password</label>
                                                @password("password_confirmation", ["id" => "edit-password_confirmation",  "placeholder" => "Confirm password"])
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="mc-footer">
                                            <div class="row">
                                                <div class="col-lg-12 text-right">
                                                    <button type="submit" class="btn  btn-primary m-1" id="edit-password-btn">Update Changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!-- end::form -->
                            </div>
                          
                        </div>

                        <div class="tab-pane fade" id="mfa" role="tabpanel" aria-labelledby="mfa-tab">
                            <div class="card mb-4">
                                <div class="card-header">
                                    Multifactor Authentication Factor
                                </div>
                                <!--begin::form-->
                                <div id="edit-user-mfa"></div>
                                <form action="{{ route("user.mfa.update") }}" id="change_mfa" name="change_mfa" method="POST" class="form-horizontal">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" id="id">
                                    <div class="card-body">
                                        <div class="form-row ">
                                            <div class="col-md-6 form-group mb-3">
                                                <label for="mfa"><span class="error">*</span> Verification Code Sent on : </label>
                                                <div class="forms-element d-inline-flex ml-3">
                                                    <label class="radio radio-primary mr-3">
                                                        <input type="radio"  id="mfa_status_sms" name="mfa_status" value="1">
                                                        <span>SMS</span>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <label class="radio radio-primary mr-3">
                                                        <input type="radio"  id="mfa_status_config" name="mfa_status" value="0">
                                                        <span>Configuration</span>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="mc-footer">
                                            <div class="row">
                                                <div class="col-lg-12 text-right">
                                                    <button type="submit" class="btn  btn-primary m-1" id="edit-password-btn">Update Changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!-- end::form -->
                            </div>
                        </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="mc-footer">
                    <div class="row">
                        <div class="col-lg-12 text-right">
                            <span id="button_div"></span>
                            <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

   

@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script type="text/javascript">

    var renderUserTable = function() {
        //alert("test");
        var columns = [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'f_name',name: 'f_name',
                mRender: function(data, type, full, meta){ 
                if(data!='' && data!='NULL' && data!=undefined){
                    return full['f_name']+' '+full['l_name'];
                 } else {
                    return '';
                }
            },
                orderable: true
            },
            {data: 'email', name: 'email'},
            {data: 'number', name: 'number'},
            {data: 'created_at', name: 'created_at'},
            {data: 'role_name.role_name', name: 'role_name',
                render: function(data, type, full, meta){
                    if(data!='' && data!='NULL' && data!=undefined){
                        return data;
                    } else {
                        return "-";
                    }
                }
            },
            
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
       // console.log(columns);
        var table = util.renderDataTable('usersList', "{{ route('org_users_list') }}", columns, "{{ asset('') }}");
    }

</script>

<script>
    $(document).ready(function() {
        // $(".pro_image").hide(); 
        renderUserTable();
        orgusers.init();
        // util.getToDoListData(0, {{getPageModuleName()}});
        
        $('#providers_details').hide();
        $('#country_code option:contains(United States (US) +1)').attr('selected', 'selected');

        $('#roles').change(function() { 
            // alert(this.value);
            if(this.value == '2'){
                $('#providers_details').show();
            } else {
                $('#providers_details').hide();
            }
        });
    });

   
    
    $('table').on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
    }); 
    
   
</script>
@endsection