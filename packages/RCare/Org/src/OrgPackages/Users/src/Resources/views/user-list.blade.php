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
                            <th>Extension</th> 
                            <th>Employee Id</th>
                            <th>Office</th>
                            <th>Created At</th>
                            <th>Role</th>		
                            <th>Report To</th> 
                            <th>Responsibility</th>
                            <th>MFA Config</th>
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
                                <div class="col-md-6 form-group mb-3" >
                                   <label for="emp_id"><span class="error">*</span> Employee Id</label>
                                   @text("emp_id", ["id" => "emp_id", "class" => " "])
                               </div>
                               <div class="col-md-6 form-group mb-3 pro_image">
                                    <label for="profile_img">Select Profile Image</label>
                                    @file("file", ["id" => "file", "class" => "form-control",'onchange'=>"uploadfile('add')"])
                                     <input type="hidden" name="image_path" id="image_path">
                                    <span id="profile_img"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mb-3" >
                                   <label for="username"><span class="error">*</span> First Name</label>
                                   @text("f_name", ["id" => "txtName", "class" => "capital-first "])
                               </div>
                               <div class="col-md-6 form-group mb-3">
                                    <label for="username"><span class="error">*</span> Last Name</label>
                                    @text("l_name", ["id" => "l_name","class" => "capital-first"])
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="email"><span class="error">*</span> Email Address</label>
                                    @email("email", ["id" => "email", "class" => " "])
                                </div>
                              
                                <div class="col-md-6 form-group mb-3" > 
                                   <label for="extn">Extension</label>
                                    @text("extension", ["id" =>"exten"])
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
								<label>Country Code</label>
								@selectcountrycode("country_code", ["id" => "country_code"]) 
							</div> 
                            <div class="col-md-6 form-group mb-3">
                                <label for="password"><span class="error">*</span> Contact Number (MFA)</label>
                                    @phone("number", ["id"=> "number"])
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
                            </div>
                        </div>
                       <div class="row">
                            <div class="col-md-6 form-group mb-3" >
                                <label for="Practices"><span class="error">*</span> Practices</label>
                                @selectpractices("practice__id", ["id" => "practice__id"])
                            </div>

                            <div class="col-md-6 form-group mb-3" >
                                <label for="Office">Office</label>
                                @selectoffices("office_id", ["id" => "office_id"])
                            </div>
                            <!-- <div class="col-md-6 form-group mb-3" >
                                <label for="Organization">Organization</label>
                                selectrcareorg("org_id", ["id" => "org_id"])
                            </div> -->

                           <!--  <div class="col-md-6 form-group mb-3">
                               <label for="Level"><span class="error">*</span> Level</label>
                               selectlevel("category_id", ["id" => "category_id"])
                           </div> --> 
                        </div>
                        <div class="row"> 
                            <div class="col-md-12 form-group mb-3">
                                <label for="name" class="control-label">Responsibilities</label>
                                <div class="wrapMulDrop">
                                    <button type="button" id="multiDrop" class="multiDrop form-control col-md-12">Select Responsibilities<i style="float:right;" class="icon ion-android-arrow-dropdown"></i></button>
                                    <!-- <?php //print_r($responsibility); die(); ?> -->
                                    <?php if (isset($responsibility[0]->id) && $responsibility[0]!=''){?>
                                    <ul>
                                        <?php foreach ($responsibility as $key => $value): ?> 
                                        <li id="list_<?php echo $value->id;?>">
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="responsibility_id[<?php echo $value->id;?>]"  id ="responsibility_<?php echo $value->id;?>" type="checkbox"><span class=""><?php echo $value->responsibility?></span><span class="checkmark"></span>             
                                            </label>
                                        </li>
                                        <?php endforeach ?> 
                                    </ul>
                                    <?php }else{?> 
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                        <div class="separator-breadcrumb border-top"></div>
                        <!-- <div class="form-row mb-4">
                            <label for="Resposibility"><span class="error">*</span>Resposibility</label>
                            <div class="col-md-12 forms-element">
                                <div class="mr-3 d-inline-flex align-self-center">
                                    <label for="scheduler" class="checkbox  checkbox-primary mr-3">
                                        <input type="checkbox" name="schedular" id="scheduler" value="1" class="Rclass" formControlName="checkbox">
                                        <span>Scheduler</span>
                                        <span class="checkmark"></span>
                                    </label>
                                    <label for="enroller" class="checkbox  checkbox-primary mr-3">
                                        <input type="checkbox" name="enroller" id="enroller" value="1" class="Rclass" formControlName="checkbox">
                                        <span>Enroller</span>
                                        <span class="checkmark"></span>
                                    </label>
                                    <label for="ccm" class="checkbox  checkbox-primary mr-3">
                                        <input type="checkbox" name="ccm" id="ccm" value="1" class="Rclass" formControlName="checkbox">
                                        <span>CCM</span>
                                        <span class="checkmark"></span>
                                    </label> 
                                    <label for="rpm" class="checkbox  checkbox-primary mr-3">
                                        <input type="checkbox" name="rpm" id="rpm" value="1" class="Rclass" formControlName="checkbox"> 
                                        <span>RPM</span>
                                        <span class="checkmark"></span>
                                    </label>
                                    <label for="awv" class="checkbox  checkbox-primary mr-3">
                                        <input type="checkbox" name="awv" id="awv" value="1" class="Rclass" formControlName="checkbox"> 
                                        <span>AWV</span>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>  
                            <div id="edit-CPmsg" class="invalid-feedback" style="font-size: 13px;"></div>
                        </div> -->  
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
                            <a class="nav-link" id="manage-practices-tab" data-toggle="tab" href="#managePractices" role="tab" aria-controls="managePractices" aria-selected="false">Manage Practices</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="mfa-tab" data-toggle="tab" href="#mfa" role="tab" aria-controls="mfa" aria-selected="false">2Fa Config</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="editUserTabContent">
                        <div class="tab-pane fade show active" id="basicDetails" role="tabpanel" aria-labelledby="basic-details-tab">
                            <form action="{{ route("ajax.user.details.update") }}" id="user_details"  enctype="multipart/form-data" name="user_details" method="POST" class="form-horizontal">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6 form-group mb-3" >
                                    <label for="emp_id">Employee Id</label>
                                    @text("emp_id", ["id" => "edit-emp-id", "class" => " "])
                                </div>
                                <div class="col-md-6 form-group mb-3" id="insertedImages"></div>
                            </div>
                            
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
                                                <div class="col-md-4 form-group mb-3" > 
                                                   <label for="extension">Extension</label>
                                                    @text("extension", ["id" =>"edit-exten"])
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
                                                <div class="col-md-4 form-group mb-3 pro_image">
                                                    <label for="profile_img">Select Profile Image</label>
                                                    @file("file", ["id" => "fileedit",'onchange'=>"uploadfile('edit')"])
                                                    
                                                    <input type="hidden" name="image_path_edit" id="image_path_edit">
                                                @hidden("hidden_profile_image", ["id" => "hidden-profile-img", "class" => " "])
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="separator-breadcrumb border-top"></div>
                                        <div class="row">
                                            <div class="col-md-6 form-group mb-3">
                                                <label for="role"><span class="error">*</span> Role</label>
                                                 @selectorgrole("role", ["id" => "edit-roles"])
                                            </div>

                                            <div class="col-md-6 form-group mb-3">
                                                <label for="report"><span class="error">*</span> Report To</label>
                                                <div id="report_to_select">
                                                    @selectorguser("report_to", ["id" => "edit-reports"])
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 form-group mb-3" >
                                                <label for="Status"><span class="error">*</span>Status</label>
                                                @selectactivestatus("status",["id" => "edit-emp-status", "class" => " "])
                                            </div>
                                            <div class="col-md-4 form-group mb-3" >
                                                <label for="Office">Office</label>
                                                @selectoffices("office_id", ["id" => "edit-office_id"])
                                            </div>   
                                            <div class="col-md-4 form-group mb-3">
                                                <label for="name" class="control-label">Responsibilities</label>
                                                <div class="wrapMulDrop">
                                                    <button type="button" class="multiDrop form-control col-md-12">Select Responsibilities<i style="float:right;" class="icon ion-android-arrow-dropdown"></i></button>
                                                    <!-- <?php //print_r($responsibility); die(); ?> -->
                                                    <?php if (isset($responsibility[0]->id) && $responsibility[0]!=''){?>
                                                    <ul>
                                                        <?php foreach ($responsibility as $key => $value): ?> 
                                                        <li> 
                                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                                <input class="" name ="responsibility_id[<?php echo $value->id;?>]"  id ="edit_responsibility_<?php echo $value->id;?>" type="checkbox"><span class=""><?php echo $value->responsibility?></span><span class="checkmark"></span>             
                                                            </label>
                                                        </li>
                                                        <?php endforeach ?> 
                                                    </ul>
                                                    <?php }else{?> 
                                                    <?php }?>
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-6 form-group mb-3" >
                                                <label for="Organization">Organization</label>
                                               {{-- @selectrcareorg("org_id", ["id" => "org_id"]) --}}
                                            </div> -->

                                           <!--  <div class="col-md-6 form-group mb-3">
                                               <label for="Level"><span class="error">*</span> Level</label> 
                                               {{-- @selectlevel("category_id", ["id" => "category_id"]) --}}
                                           </div> -->
                                        </div>
                                        <!-- <div class="form-row mb-4">
                                            <label for="Responsibility"><span class="error">*</span>Responsibility</label>
                                            <div class="col-md-12 forms-element">
                                                <div class="mr-3 d-inline-flex align-self-center">
                                                    <label class="checkbox  checkbox-primary mr-3">
                                                        <input type="checkbox" name="scheduler" id="edit-scheduler" value="1" class="ERclass" formControlName="checkbox">
                                                        <span>Scheduler</span>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <label  class="checkbox  checkbox-primary mr-3">
                                                        <input type="checkbox" name="enroller" id="edit-enroller" value="1" class="ERclass" formControlName="checkbox">
                                                        <span>Enroller</span>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <label class="checkbox  checkbox-primary mr-3">
                                                        <input type="checkbox" name="ccm" id="edit-ccm" value="1" class="ERclass" formControlName="checkbox">
                                                        <span>CCM</span>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <label class="checkbox  checkbox-primary mr-3">
                                                        <input type="checkbox" name="rpm" id="edit-rpm" value="1" class="ERclass" formControlName="checkbox"> 
                                                        <span>RPM</span> 
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <label class="checkbox  checkbox-primary mr-3">
                                                        <input type="checkbox" name="awv" id="edit-awv" value="1" class="ERclass" formControlName="checkbox"> 
                                                        <span>AWV</span>
                                                        <span class="checkmark"></span>
                                                    </label>                                               
                                                </div>
                                            </div>      
                                            <div id="CPmsg" class="invalid-feedback" style="font-size: 13px;"></div> 
                                        </div> -->
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
                        <div class="tab-pane fade" id="managePractices" role="tabpanel" aria-labelledby="manage-practices-tab">
                            <div class="card mb-4">
                                <div class="card-header">
                                    Link Practice
                                </div>
                                <!--begin::form-->
                                <div id="edit-user-practice-msg"></div>
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
                                                    <th width="10px">Sr No.</th>
                                                    <th width="40px">Practice Number</th>
                                                    <th width="40px">Practice</th>
                                                    <th width="10px">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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
            {data: 'profile_img',name: 'profile_img',
                mRender: function(data, type, full, meta){
                //console.log(full['f_name']);
                if(data!='' && data!='NULL' && data!=undefined){
                    return ["<img src={{ URL::to('/') }}/images/usersRcare/" + data + " width='40px' class='user-image' />"]+' '+full['f_name']+' '+full['l_name'];
                } else { 
                        return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['f_name']+' '+full['l_name'];
                    }
                },
                orderable: true
            },
            {data: 'email', name: 'email'},
            {data: 'number', name: 'number'},
            {data: 'extension', name: 'extension'},
            {data: 'emp_id', name: 'emp_id'},
           
            {data: 'office.location', name: 'office_id',
                render: function(data, type, full, meta){
                    if(data!='' && data!='NULL' && data!=undefined){
                        return data;
                    } else {
                        return "-";
                    }
                } 
            },
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
        //    {data: 'reportto.f_name', name: 'f_name',   
        //         render: function(data, type, full, meta){
        //             if(data!='' && data!='NULL' && data!=undefined){
        //                 return data;
        //             } else {
        //                 return "-";
        //             }
        //         }
        //     },
            {data: 'reportto', mRender: function(data, type, full, meta){
                if(data!='' && data!='NULL' && data!=undefined){
                    if(data['l_name'] == null && data['f_name'] == null){
                        return '';
                    }else{
                        return data['f_name'] + ' ' + data['l_name'];
                    } 
                }else { return '';}    
            },orderable: false}, 

            // {data: 'responsibility', name: 'responsibility'},
            {data: "users_responsibility", render: "[, ].responsibility.responsibility", orderable: false },
            {data: 'mfa_status', name: 'mfa_status',
                render: function(data, type, full, meta){
                    if(data!='' && data!='NULL' && data!=undefined){
                        if(data==1){
                            return "SMS";
                        }else{
                            return "2FA";
                        }
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
        // console.log(url);
        var table1 = util.renderDataTable('practices_table', url, columns1, "{{ asset('') }}");
    }


</script>

<script>
    $(document).ready(function() {
        // $(".pro_image").hide(); 
        renderUserTable();
        orgusers.init();
        util.getToDoListData(0, {{getPageModuleName()}});
        $('#country_code option:contains(United States (US) +1)').attr('selected', 'selected');

       

        // $('.loginas').click(function(){ 
        //    alert("The paragraph was clicked.");
        // });
    });

    function loginAction(id){
        
        var base_url = "<?php echo url('').'/'; ?>";
        $.ajax({
            type: "GET",
            url: "/org/ajax/login-as-developer/"+id,    
            // dataType:"json",
            // data: $('#login_form').serialize(),
            success: function(response) {
                // alert('success');
                // console.log(response); 
                // console.log(response.success);
                 var url =  response.url; 
                if(response.success=='y'){
                    // alert('if');  
                    // window.location.href=base_url+''+url;
                    var mainurl = base_url+''+url;
                    window.open(mainurl, '_blank').focus();
                }else{  
                    // alert('else');  
                    // $("#danger").show(0).delay(3000).hide(0);
                    // $("#success").html(error);

                }


            }
        });
        
    }

    // $('#loginas'+id).on('click', function () {
    //       alert('button');
    // });


    

    //Multiple Dropdown Select
    $('.multiDrop').on('click', function (event) { 
        event.stopPropagation();
        $(this).next('ul').slideToggle();
    });

    $(document).on('click', function () {
        if (!$(event.target).closest('.wrapMulDrop').length) {
            $('.wrapMulDrop ul').slideUp();
            // $('#drpdwn_task_notes').hide(); //hide notes 
        }
    }); 
    
    $('.wrapMulDrop ul li input[type="checkbox"]').on('change', function () {
        var x = $('.wrapMulDrop ul li input[type="checkbox"]:checked').length;
        // alert(x);
        if (x != "") {
            $('.multiDrop').html(x + " " + "selected");
        } else if (x < 1) {
            $('.multiDrop').html('Select Responsibility<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
        }
    });




    $('table').on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
    }); 
    
    function uploadfile(action){
        var token=$('input[name=_token]').val();
        var fname;
        var lname;
        var file_data;
         if(action=='add') 
         {
             fname=$('#txtName').val();
             lname=$('#l_name').val();
            file_data = $("#file").prop("files")[0];
         }else
         {
             fname=$('#edit-f-name').val();
             lname=$('#edit-l-name').val();
             file_data = $("#fileedit").prop("files")[0];  
         }
            //console.log(fname+" test "+lname);
        var form_data = new FormData();
        form_data.append("f_name", fname);
        form_data.append("l_name", lname);
        form_data.append("file", file_data);
        
        $.ajax({
            url: '/org/ajax/UseruploadImage',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN':token
            },
            data:form_data,
            enctype: 'multipart/form-data',
            success: function(data) {
                    console.log(data+'user imgage path'); 
            if(action=='add'){   
                $('#image_path').val(data);
            }else{
                $('#image_path_edit').val(data);
            }
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }
</script>
@endsection