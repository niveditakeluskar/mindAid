 @section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
   <div class="row">
    <div class="col-lg-12 mb-3">
      <div class="card">
        <div class=" card-body">
          <div class="row">
            <div class="col-md-10">
             <div class="alert alert-success" id="success-alert" style="display: none;">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>Physician Data saved successfully! </strong><span id="text"></span>
              </div>
              <div id ="provider_success"></div>
            </div>
            <div class="col-md-2">
              <a class="btn btn-success btn-sm mb-4" href="javascript:void(0)" id="addProvider">Add Physician</a>  
            </div>
            <div class="separator-breadcrumb border-top"></div>
          <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
              @include('Theme::layouts.flash-message')
            <div class="table-responsive">
              <table id="providerList" class="display table table-striped table-bordered" style="width:100%">
                <thead>
                  <tr>
                    <th width='10px'>Sr No.</th>
                    <th width='10px'>Physician </th>  
                    <th width='10px'>Clinic </th>
                    <th width='10px'>Speciality</th>
                    <th width='10px'>Phone Number</th>
                    <th width='10px'>Email ID</th>     
                    <th width='10px'>Address</th>   
                    <th width='10px'>Last Modifed By</th>
                    <th width='10px'>Last Modifed On</th>    
                    <th width='10px'>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="edit_provider_modal" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">  
            <div class="modal-header">
              <h4 class="modal-title" id="user_modal_heading">Edit Physician</h4>
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
              </ul>
              <div class="tab-content" id="editUserTabContent">
                  <div class="tab-pane fade show active" id="basicDetails" role="tabpanel" aria-labelledby="basic-details-tab">
                      <form action="{{ route('update_org_provider') }}" method="POST" id="EditProviderForm" name="EditProviderForm" class="form-horizontal">
                      {{ csrf_field() }}
                      
                      <div class="card mb-4">
                          <div class="card-header">Change Physician Details</div>
                          <!--begin::form-->
                          <div id="edit-user-msg"></div>
                          <input type="text" name="id" id="id">
                          <div class="modal-body">
                            <div class="form-group">
                              <div class="row">
                                <div class="col-md-6  form-group mb-3 ">
                                  <label for="physicianame">Select Clinic <span class='error'>*</span></label>
                                  @selectpractices("practice_id", ["placeholder" => "Select Clinic Name" ])
                                </div>
                                <div class="col-md-6  form-group mb-3" >
                                  <label for="speciality">Select Speciality</label>
                                  @selectspeciality("speciality_id",["placeholder" => "Select Speciality" ])
                                </div>
                                <div class="col-md-4 form-group mb-3" >
                                    <label for="username"><span class="error">*</span> First Name</label>
                                    @text("f_name", ["id" => "f_name", "class" => "capital-first "])
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label for="username"><span class="error">*</span> Last Name</label>
                                    @text("m_name", ["id" => "m_name","class" => "capital-first"])
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label for="username"><span class="error">*</span> Last Name</label>
                                    @text("l_name", ["id" => "l_name","class" => "capital-first"])
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label>Date of Birth</label>
                                    @date("dob")
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label>Gender</label>
                                    @select("Gender", "gender", [ 0 => "Male", 1 => "Female"])
                                </div>
                                <div class="col-md-6 form-group mb-3" > 
                                    <label for="extn">Licenese Number</label>
                                    @text("licenese_number", ["id" =>"licenese_number"])
                                </div>
                                <div class="col-md-6 form-group mb-3" > 
                                    <label for="extn">Qualification</label>
                                    @text("qualification", ["id" =>"qualification"])
                                </div>
                                <div class="col-md-6  form-group mb-3 ">
                                <label for="physicianname">Email <span class='error'>*</span></label>
                                @email('email')
                                </div> 
                                <div class="col-6 form-group mb-3">
                                    <label for="city">City</label>
                                    @text("city", ["id" => "city", "class" => "capitalize"])
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                  <label>Country Code</label>
                                  @selectcountrycode("country_code", ["id" => "country_code"]) 
                                </div>
                                <div class="col-md-6  form-group mb-3 ">
                                <label for="name">Phone Number<span class='error'>*</span></label>
                                @phone('phone')
                                </div> 
                                <div class="col-md-12  form-group mb-3 ">
                                <label for="physicianname">Address<span class='error'>*</span></label>
                                @text('address')
                                </div>
                              </div>
                            </div> 
                            <div class="card-footer">
                              <div class="mc-footer">
                                <div class="row">
                                  <div class="col-lg-12 text-right">
                                    <button type="submit"  class="btn  btn-primary m-1">Update</button>
                                    <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- end::form -->    
                      </div>
                      </form>     
                  </div>
                    <div class="tab-pane fade" id="managePassword" role="tabpanel" aria-labelledby="manage-password-tab-tab">
                      <div class="card mb-4">
                          <div class="card-header">
                              Change Password
                          </div>
                          <!--begin::form-->
                          <div id="edit-user-password-msg"></div>
                          <form action="{{ route("ajax.user.password.update") }}" id="change_password" name="change_password" method="POST" class="form-horizontal">
                          {{ csrf_field() }}
                          <input type="text" name="id" id="id">
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
<div class="modal fade" id="add_provider_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modelHeading1">Add Physician</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action="{{ route('create_org_provider') }}" name="AddProviderForm" id="AddProviderForm" method="post">
          {{ csrf_field() }}
        <div class="modal-body">
          <div class="form-group">
            <div class="row"> 
             <input type="hidden" name="role" value="2">
              <div class="col-md-6  form-group mb-3"> 
                <label for="physicianame">Select Clinic <span class='error'>*</span></label>
                @selectpractices("practice_id", ["placeholder" => "Select Clinic Name" ])
              </div>
               <div class="col-md-6  form-group mb-3" >
                <label for="speciality">Select Speciality</label>
                @selectspeciality("speciality_id",["placeholder" => "Select Speciality" ])
              </div>
              <div class="col-md-4 form-group mb-3" >
                  <label for="username"><span class="error">*</span> First Name</label>
                  @text("f_name", ["id" => "f_name", "class" => "capital-first "])
              </div>
              <div class="col-md-4 form-group mb-3">
                  <label for="username"> Middle Name</label>
                  @text("m_name", ["id" => "m_name","class" => "capital-first"])
              </div>
              <div class="col-md-4 form-group mb-3">
                  <label for="username"><span class="error">*</span> Last Name</label>
                  @text("l_name", ["id" => "l_name","class" => "capital-first"])
              </div>
              <div class="col-md-6 form-group mb-3">
                  <label>Date of Birth</label>
									@date("dob")
              </div>
              <div class="col-md-6 form-group mb-3">
                  <label>Gender</label>
                  @select("Gender", "gender", [ 0 => "Male", 1 => "Female"])
              </div>
              <div class="col-md-6 form-group mb-3" > 
                  <label for="extn">Licenese Number</label>
                  @text("licenese_number", ["id" =>"licenese_number"])
              </div>
              <div class="col-md-6 form-group mb-3" > 
                  <label for="extn">Qualification</label>
                  @text("qualification", ["id" =>"qualification"])
              </div>
              <div class="col-md-6  form-group mb-3 ">
               <label for="physicianname">Email <span class='error'>*</span></label>
               @email('email')
              </div> 
              <div class="col-6 form-group mb-3">
                  <label for="city">City</label>
                  @text("city", ["id" => "city", "class" => "capitalize"])
              </div>
              <div class="col-md-6 form-group mb-3">
								<label>Country Code</label>
								@selectcountrycode("country_code", ["id" => "country_code"]) 
							</div>
              <div class="col-md-6  form-group mb-3 ">
               <label for="name">Phone Number<span class='error'>*</span></label>
               @phone('phone')
              </div> 
              <div class="col-md-12  form-group mb-3 ">
               <label for="physicianname">Address<span class='error'>*</span></label>
               @text('address')
              </div>
            </div>
          </div> 
        </div> 
          <div class="card-footer">
            <div class="mc-footer">
              <div class="row">
                <div class="col-lg-12 text-right">
                  <button type="submit"  class="btn  btn-primary m-1">Save</button>
                  <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
          </div>
      </form>
    </div>
  </div> 
</div>
