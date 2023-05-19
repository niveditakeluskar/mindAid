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
		 <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add User</a>  
		</div>
</div>
   <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
           <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="usersList" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="45px">Sr No.</th>
                            <th>User</th>
                            <!-- <th></th> -->
                            <th>Email</th>  
                            <th width="80px">Employee Id</th>
                            
                           <!--  <th>Organization</th> -->
                            <th width="70px">Role</th>		
                            <th width="100px">Report To</th>						
                            <th width="50px">Action</th>
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
<div class="modal fade bd-example-modal-lg" id="user_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
             <div class="modal-header">
                <h4 class="modal-title" id="user_modal_heading"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none"></div>
                <form action="{{ route("create_Users") }}" id="user_form" name="user_form" method="POST" enctype="multipart/form-data"  class="form-horizontal">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 form-group mb-3" >
                               <label for="emp_id"><span class="error">*</span> Employee Id</label>
                               @text("emp_id", ["id" => "emp_id", "class" => "form-control form-control"])
                           </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group mb-3" >
                               <label for="username"><span class="error">*</span> First Name</label>
                               @text("f_name", ["id" => "f_name", "class" => "capital-first form-control"])
                           </div>
                           <div class="col-md-6 form-group mb-3">
                                <label for="username"><span class="error">*</span> Last Name</label>
                                @text("l_name", ["id" => "l_name","class" => "capital-first"])
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="email"><span class="error">*</span> Email Address</label>
                                @email("email", ["id" => "email", "class" => "form-control form-control"])
                            </div>
                          
                              <div class="col-md-6 form-group mb-3">
                                <label for="profile_img"><span class="error">*</span> Select Profile Image</label>
                                @file("profile_img", ["id" => "profile_img", "class" => "form-control form-control"])
                                <!-- <input type="file" name="profile_img" id="profile_img"> -->
                              <span id="profile_img"></span>
                            </div>
                         
                          <!--   <div class="col-md-6 form-group mb-3">
                                <label for="report">Report To</label>
                                <div id="report_to_select">
                                    @selectorguser("report_to", ["id" => "reports"])  
                                </div>
                            </div> -->
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
                        <div class="separator-breadcrumb border-top"></div>
                        <div class="row">
                              <div class="col-md-6 form-group mb-3">
                                <label for="role"><span class="error">*</span> Role</label>
                                @selectorgrole("role", ["id" => "roles"])
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label for="report"><span class="error">*</span> Report To</label>
                                <div id="report_to_select">
                                    @selectorguser("report_to", ["id" => "reports"])  
                                </div>

                           <!--   -->
                          </div>
                       </div>
                       <div class="row">
                            <div class="col-md-6 form-group mb-3" >
                                <label for="Practices"><span class="error">*</span> Practices</label>
                                @selectpractices("practice__id", ["id" => "practice__id"])
                            </div>
                            <!-- <div class="col-md-6 form-group mb-3" >
                                <label for="Organization">Organization</label>
                                @selectrcareorg("org_id", ["id" => "org_id"])
                            </div> -->

                            <div class="col-md-6 form-group mb-3">
                               <label for="Level"><span class="error">*</span> Level</label>
                               @selectlevel("category_id", ["id" => "category_id"])
                           </div>
                       
                    </div>
           <div class="separator-breadcrumb border-top"></div>
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
</div>

<div class="modal fade bd-example-modal-lg " id="edit_user_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
             <div class="modal-header">
                <h4 class="modal-title" id="user_modal_heading">Edit User</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none"></div>
                    <ul class="nav nav-tabs" id="editUserTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="basic-details-tab" data-toggle="tab" href="#basicDetails" role="tab" aria-controls="basicDetails" aria-selected="true">Basic Details</a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link " id="manage-password-tab" data-toggle="tab" href="#managePassword" role="tab" aria-controls="managePassword" aria-selected="false">Change Password</a>
                        </li> 

                        <li class="nav-item">
                            <a class="nav-link" id="manage-practices-tab" data-toggle="tab" href="#managePractices" role="tab" aria-controls="managePractices" aria-selected="false">Manage Practices</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="editUserTabContent">
                        <div class="tab-pane fade show active" id="basicDetails" role="tabpanel" aria-labelledby="basic-details-tab">
                            <div class="row">
                                <div class="col-md-6 form-group mb-3" >
                                    <label for="emp_id">Employee Id</label>
                                    @text("emp_id", ["id" => "edit-emp-id", "class" => "form-control form-control", "readonly"=>"readonly"])
                                </div>
                            </div>
                            
                            <div class="card mb-4">
                                <div class="card-header">
                                    Change User Details
                                </div>
                                <!--begin::form-->
                                <form action="{{ route("ajax.user.name.update") }}" id="name_details" name="name_details" method="POST" class="form-horizontal">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" id="id">
                                    <div class="card-body">
                                        <div class="form-row ">
                                            <div class="col-md-6 form-group mb-2" >
                                                <label for="username"><span class="error">*</span> First Name</label>
                                                @text("f_name", ["id" => "edit-f-name", "class" => "capital-first"])
                                            </div>
                                            <div class="col-md-6 form-group mb-2">
                                                <label for="username"><span class="error">*</span> Last Name</label>
                                                @text("l_name", ["id" => "edit-l-name", "class" => "capital-first"])
                                            </div>
                                        </div>
                                         <p></p>
                                         <div class="form-row ">
                                            <div class="col-md-6 form-group mb-2" >
                                                <label for="email"><span class="error">*</span> Email Address</label>
                                                @email("email", ["id" => "edit-email", "class" => "form-control form-control"])
                                            </div>
                                            <div class="col-md-6 form-group mb-2">
                                               <label for="profile_img"><span class="error">*</span> Select Profile Image</label>
                                                @file("profile_img", ["id" => "edit-profile_img", "class" => "form-control form-control"])
                                            </div>
                                        </div>
                                        <br>

                                        <div class="separator-breadcrumb border-top"></div>


                                         <div class="form-row ">
                                            <div class="col-md-6 form-group mb-2" >
                                                  <label for="role"><span class="error">*</span> Role</label>
                                                @selectorgrole("role", ["id" => "edit-roles"])
                                            </div>
                                            <div class="col-md-6 form-group mb-2">
                                              <label for="report"><span class="error">*</span> Report To</label>
                                                @selectorguser("report_to", ["id" => "edit-reports"])  
                                            </div>
                                        </div>
                                        <p></p>

                                         <div class="form-row ">
                                            <div class="col-md-6 form-group mb-2" >
                                                   <label for="Level"><span class="error">*</span> Level</label>
                                                @selectlevel("category_id", ["id" => "edit-emp-category_id"])
                                            </div>
                                            <div class="col-md-6 form-group mb-2">
                                              <label for="Status">Status</label>
                                                @selectactivestatus("status",["id" => "edit-emp-status", "class" => "form-control form-control"])
                                            </div>
                                        </div>

                                         <br>

                                        <div class="separator-breadcrumb border-top"></div>
                                     

                                        
                                    </div>
                                    <div class="card-footer">
                                        <div class="mc-footer">
                                            <div class="row">
                                                <div class="col-lg-12 text-right">
                                                    <button type="submit" class="btn  btn-primary m-1" id="edit-name-btn">Update Changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!-- end::form -->
                            </div>
                            
                            <!-- <div class="card mb-4">
                                <div class="card-header">
                                    Change Status
                                </div> -->
                                <!--begin::form-->
                               <!--  <form action="{{ route("ajax.user.status.update") }}" id="change_status" name="change_status" method="POST" class="form-horizontal">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" id="id">
                                    <div class="card-body">
                                        <div class="form-row ">
                                            <div class="col-md-12 form-group mb-2">
                                                <label for="Status">Status</label>
                                                @selectactivestatus("status",["id" => "edit-status", "class" => "form-control form-control"])
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="mc-footer">
                                            <div class="row">
                                                <div class="col-lg-12 text-right">
                                                    <button type="submit" class="btn  btn-primary m-1" id="edit-status-btn">Update Changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form> -->
                                <!-- end::form -->
                            <!-- </div> -->
                            
                            <!-- <div class="card mb-4">
                                <div class="card-header">
                                    Change Role
                                </div> -->
                                <!--begin::form-->
                               <!--  <form action="{{ route("ajax.user.role.update") }}" id="change_role" name="change_role" method="POST" class="form-horizontal">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" id="id">
                                    <div class="card-body">
                                        <div class="form-row ">
                                            <div class="col-md-6 form-group mb-2">
                                                <label for="role">Role</label>
                                                @selectorgrole("role", ["id" => "edit-roles"])
                                            </div>
                                            <div class="col-md-6 form-group mb-2">
                                                <label for="report">Report To</label>
                                                @selectorguser("report_to", ["id" => "edit-reports"])  
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="mc-footer">
                                            <div class="row">
                                                <div class="col-lg-12 text-right">
                                                    <button type="submit" class="btn  btn-primary m-1" id="edit-role-btn">Update Changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form> -->
                                <!-- end::form -->
                            <!-- </div> -->
                            
                           <!--  <div class="card mb-4">
                                <div class="card-header">
                                    Change Email
                                </div> -->
                                <!--begin::form-->
                                <!-- <form action="{{ route("ajax.user.email.update") }}" id="change_email" name="change_email" method="POST" class="form-horizontal">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" id="id">
                                    <div class="card-body">
                                        <div class="form-row ">
                                            <div class="col-md-12 form-group mb-3">
                                                <label for="email">Email Address</label>
                                                @email("email", ["id" => "edit-email", "class" => "form-control form-control"])
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="mc-footer">
                                            <div class="row">
                                                <div class="col-lg-12 text-right">
                                                    <button type="submit" class="btn  btn-primary m-1" id="edit-email-btn">Update Changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form> -->
                                <!-- end::form -->
                            <!-- </div> -->
                            
                            <!-- <div class="card mb-4">
                                <div class="card-header">
                                    Change Organization
                                </div> -->
                                <!--begin::form-->
                                <!-- <form action="{{ route("ajax.user.org.update") }}" id="change_org" name="change_org" method="POST" class="form-horizontal">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" id="id">
                                    <div class="card-body">
                                        <div class="form-row ">
                                            <div class="col-md-12 form-group mb-3" >
                                                <label for="Organization">Organization</label>
                                                @selectrcareorg("org_id", ["id" => "edit-org-id"])
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="mc-footer">
                                            <div class="row">
                                                <div class="col-lg-12 text-right">
                                                    <button type="submit" class="btn  btn-primary m-1" id="edit-org-btn">Update Changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form> -->
                                <!-- end::form -->
                            <!-- </div> -->
                            
                            <!-- <div class="card mb-4">
                                <div class="card-header">
                                    Change Category
                                </div> -->
                                <!--begin::form-->
                               <!--  <form action="{{ route("ajax.user.category.update") }}" id="change_category" name="change_category" method="POST" class="form-horizontal">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" id="id">
                                    <div class="card-body">
                                        <div class="form-row ">
                                            <div class="col-md-12 form-group mb-3">
                                                <label for="Level">Level</label>
                                                @selectlevel("category_id", ["id" => "edit-category-id"])
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="mc-footer">
                                            <div class="row">
                                                <div class="col-lg-12 text-right">
                                                    <button type="submit" class="btn  btn-primary m-1" id="edit-category-btn">Update Changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form> -->
                                <!-- end::form -->
                         <!--    </div> -->

                            <!-- <div class="card mb-4">
                                <div class="card-header">
                                    Change Password
                                </div> -->
                                <!--begin::form-->
                               <!--  <form action="{{ route("ajax.user.password.update") }}" id="change_password" name="change_password" method="POST" class="form-horizontal">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" id="id">
                                    <div class="card-body">
                                        <div class="form-row ">
                                            <div class="col-md-6 form-group mb-3">
                                                <label for="password">Password</label>
                                                @password("password", ["id" => "edit-password",  "placeholder" => "Enter Password"])
                                            </div>
                                            <div class="col-md-6 form-group mb-3" >
                                                <label for="repassword">Confirm Password</label>
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
                                </form> -->
                                <!-- end::form -->
                           <!--  </div> -->

                            <!-- <div class="card mb-4" id="change_profile_picture_div">
                                <div class="card-header">
                                    Change Profile Picture
                                </div> -->
                                <!--begin::form-->
                                <!-- <form action="{{ route("ajax.user.picture.update") }}" id="change_profile_image" name="change_profile_image" method="POST" class="form-horizontal" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" id="id">
                                    <div class="card-body">
                                        <div class="form-row ">
                                            <div class="col-md-12 form-group mb-3">
                                                <label for="profile_img">Select Profile Image</label>
                                                @file("profile_img", ["id" => "edit-profile-img", "class" => "form-control form-control"])
                                               
                                                <span id="profile_img"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="mc-footer">
                                            <div class="row">
                                                <div class="col-lg-12 text-right">
                                                    <button type="submit" class="btn  btn-primary m-1" id="edit-profile_image-btn">Update Changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form> -->
                                <!-- end::form -->
                           <!--  </div> -->

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
                        <div class="tab-pane fade" id="managePractices" role="tabpanel" aria-labelledby="manage-practices-tab">
                            <div class="card mb-4">
                                <div class="card-header">
                                    Link Practice
                                </div>
                                <!--begin::form-->
                                <form action="{{ route("ajax.user.link.practices") }}" id="edit_practice" name="edit_practice" method="POST" class="form-horizontal">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" id="id">
                                    <div class="card-body">
                                        <div class="form-row ">
                                            <div class="col-md-12 form-group mb-3" >
                                                <label for="Practices"><span class="error">*</span> Practices</label>
                                                @selectpractices("practice__id", ["id" => "edit-practice-id"])
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="mc-footer">
                                            <div class="row">
                                                <div class="col-lg-12 text-right">
                                                    <button type="submit" class="btn  btn-primary m-1" id="edit-practice-btn">Link</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!-- end::form -->
                            </div>
                            <div class="card mb-4">
                                <div class="card-header">
                                    Linked Practices
                                </div>
                                <div id="practices_div">
                                    <input type="hidden" name="user_id" id="user_id">
                                    <div class="table-responsive">
                                        <table id="practices_table" class="display table table-striped table-bordered practices_table" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th width="45px">Sr No.</th>
                                                    <th width="80px">Practice Number</th>
                                                    <th >Practice</th>
                                                    <th width="50px">Status</th>
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
</div>


@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
<script type="text/javascript">

    var renderUserTable = function() {
        var columns = [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'profile_img',name: 'profile_img',
                mRender: function(data, type, full, meta){
                    if(data!='' && data!='NULL' && data!=undefined){
                        return ["<img src={{ URL::to('/') }}/images/usersRcare/" + data + " width='50px' class='user-image' />"]+' '+full['f_name']+' '+full['l_name'];
                    } else {
                        return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['f_name']+' '+full['l_name'];
                    }
                },
                orderable: false
            },
        
        /* {
                data: 'f_name', name: 'f_name' ,
                "mRender" : function ( data, type, full ) {
                    return full['f_name']+' '+full['l_name'];
                }
            },*/
            {data: 'email', name: 'email'},
            {data: 'emp_id', name: 'emp_id'},
        /* {data: 'rcare_orgs.name', name: 'rcare_orgs.name',
                render: function(data, type, full, meta){
                    if(data!='' && data!='NULL' && data!=undefined){
                        return data;
                    } else {
                        return "-";
                    }
                }
            },*/
            {data: 'role_name.role_name', name: 'role_name',
                render: function(data, type, full, meta){
                    if(data!='' && data!='NULL' && data!=undefined){
                        return data;
                    } else {
                        return "-";
                    }
                }
            },

               /*{
                data: 'reportTo.f_name', name: 'f_name' ,
                "mRender" : function ( data, type, full ) {
                    return full['reportTo']+' '+full['reportTo.l_name'];
                }
            },*/
           {data: 'reportto.f_name', name: 'f_name',   
            render: function(data, type, full, meta){
                if(data!='' && data!='NULL' && data!=undefined){
                return data;
                } else {
                return "-";
                }
                }
                },
           // {data: 'reportto.f_name', name: 'f_name'} ,
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
        var table = util.renderDataTable('usersList', "{{ route('org_users_list') }}", columns, "{{ asset('') }}");
    }

    var renderPracticeTable = function(id) {

        var columns1 = [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'number',name: 'number'},
            {data: 'name',name: 'name'},
            {data: 'is_active',name: 'is_active',
                render: function(data, type, full, meta){
                    if(data==1){
                        return "Active";
                    } else if(data==0){
                        return "Inactive";
                    } else {
                        return "-";
                    }
                }
            },
        ];

        var url = '{{ route("ajax.user.linked.practices", ":id") }}';
        url = url.replace(':id', id);
        var table1 = util.renderDataTable('practices_table', url, columns1, "{{ asset('') }}");
    }


</script>

<script>
    $(document).ready(function() {
        renderUserTable();
        orgusers.init();
        form.ajaxForm(
            "user_form",
            orgusers.onResult,
            orgusers.onSubmit,
            orgusers.onErrors
        );
        form.evaluateRules("OrgUserAddRequest");
    });
</script>

<script type="text/javascript">

    if ($("#user_form").length > 0) {
        $("#user_form").validate({

            rules: {
                emp_id: {
                    required: true,
                },
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
                report_to: {
                    required: true,
                }, 
                report_to: {
                    required: true,
                },
                practice__id: {
                    required: true,
                }, 
                category_id: {
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
                },
                profile_img :{
                    required :true,
                },
            },
            messages: {
                emp_id: {
                    required: "Please enter employee id",
                },
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
                    required: "Please enter Confirm password",
                },
                password: {
                    required: "Please enter password",
                },
                select_file :{
                    required : "Please select Image file",
                } , 
            },
            // submitHandler: function(form) {
            //    $.ajaxSetup({
            //       headers: {
            //           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //       }
            //   });

            //    $('#send_form').html('Sending..');
            //    $.ajax({
            //       url: '{{ url('createUsers')}}' ,
            //       type: "POST",           

            //       data: $('#userform_submit').serialize(),
            //       success: function( response ) {
            //         $('#send_form').html('Submit');
            //         $('#res_message').show();
            //         $('#res_message').html(response.msg);
            //         $('#msg_div').removeClass('d-none');

            //         document.getElementById("userform_submit").reset(); 
            //         setTimeout(function(){
            //             $('#res_message').hide();
            //             $('#msg_div').hide();
            //         },20000);
            //     }
            // });
            // }
        });
    }
</script>

@endsection