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
                <strong>Practice Data saved successfully! </strong><span id="text"></span>
              </div>
              <div id ="success"></div>
            </div>
            <div class="col-md-2">
              <a class="btn btn-success btn-sm mb-4 ml-5" href="javascript:void(0)" id="addPractice">Add Practice</a>  
            </div>
            <div class="separator-breadcrumb border-top"></div>
          <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
              @include('Theme::layouts.flash-message')
            <div class="table-responsive">
              <table id="practiceList" class="display table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
                          <th width ="10px">Sr No.</th>
                          <th width ="10px">Practice</th>        
                          <th width ="10px">Location</th>
                          <th width ="10px">Partner</th> 
                          <th width ="10px">Practice Number</th>
                          <th width ="10px">Address</th>
                          <th width ="10px">Phone Number</th>
                          <th width ="10px">Key Contact</th> 
                          <th width ="10px">{{config('global.practice_group')}}</th>
                          <th width ="10px">Outgoing Phone Number</th> 
                          <th width ="10px">CPD billable with MM</th>
                          <th width ="10px">Practice Type</th>
                          <th width ="10px">Threshold</th>
                          <th width ="10px">Last Modified By</th>
                          <th width ="10px">Last Modified On</th>                
                          <th width ="10px">Action</th>
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
<!-- Modal -->
<div class="modal fade" id="add_practice_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 635px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1">Add Practice</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('create_org_practice') }}" method="POST" name="AddPracticeForm" id="AddPracticeForm">
                {{ csrf_field() }}
                <input type="hidden" id="checkcounter" value="">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Practice  <span style="color:red">*</span></label>
                                @text("name", ["placeholder" => "Enter Practice Name"])
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Practice Number <span style="color:red">*</span></label>
                                @text("number",["placeholder" => "Enter Practice Number"])
                            </div>
                            <div class="col-md-4 form-group mb-3 ">
                                <label for="practicename">Location <span style="color:red">*</span></label>
                                @text("location",["placeholder" => "Enter Location"])
                            </div>
                            <div class="col-md-4 form-group mb-3 ">
                                <label for="practicename">Phone Number <span style="color:red">*</span></label>
                                @phone("phone",["placeholder" => "Enter Phone Number"])
                            </div>
                            <div class="col-md-4 form-group mb-3 "> 
                                <label for="practicename">Key Contact <span style="color:red">*</span></label>
                                @text("key_contact",["placeholder" => "Enter Key Contacts"])
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Select {{config('global.practice_group')}}</label>
                                @selectgrppractices("practice_group")
                            </div>
                            
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Outgoing Phone Number <span style="color:red">*</span></label>
                                @phone("outgoing_phone_number",["placeholder" => "Enter Outgoing Phone Number"])
                            </div>
                            <!-- <div class="col-md-3 form-group mb-3"> 
              					      <label>
              						      <yes-no name="billable" label-no="No" label-yes="Yes">CPD billable with MM</yes-no>
              					      </label>
              				</div> -->
                              <div class="col-md-3 form-group mb-3"> 
                                <label for="billable" class="control-label">CPD billable with MM</label> <span></span> 
                                <div class="mr-3 d-inline-flex">    
                                    <label for="billable-yes" class="radio radio-primary mr-3">
                                        <input type="radio" formcontrolname="radio" name="billable" id="billable-yes" value="1">
                                        <span>Yes</span>
                                        <span class="checkmark"></span>
                                    </label>
                                    <label for="billable-no" class="radio radio-primary mr-3">
                                        <input type="radio" formcontrolname="radio" name="billable" id="billable-no" value="0">
                                        <span>No</span>
                                        <span class="checkmark"></span>
                                    </label>
                                <!-- <label>
                                    <yes-no name="billable" label-no="No" label-yes="Yes">CPD billable with MM</yes-no>
                                </label> -->
                                </div>
              				</div>
                            <div class="col-md-3 form-group mb-3">
                            <label for="practicename">Practice Type<span style="color:red">*</span></label>
                              <select class="custom-select" name="practice_type" id="practice_type">
                                <option value="">Practice Type</option>
                                <option value="pcp">PCP</option>
                                <option value="specialist">Specialist</option>
                                <option value="dentist">Dentist</option>
                                <option value="vision">Vision</option>
                              </select> 
                              <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 form-group mb-3 ">  
                                <label for="partners">Select Partner</label>
                                @selectpartner("partner_id",["id" => "partner_id"])
                            </div>
                            <div class="col-md-12  form-group mb-3 ">
                                <label for="practicename">Address <span style="color:red">*</span></label>
                                <textarea class="form-control forms-element" name="address" placeholder = "Enter Address"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>                   
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="file"><span class="error"></span>Upload logo</label>
                                    @file("file", ["id" => "logo", "class" => "form-control",'onchange'=>"uploadfile()"])
                                    <input type="hidden" name="image_path" id="image_path">
                                    <br/>
                                    <div id="viewlogo"> </div>
                                    <!-- <input type="hidden" name="profile_img" id="profile_img"> -->
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="uploading_img_loader" style="display:none;" class="loader-bubble loader-bubble-primary m-5"></div>
                            </div>
                        </div>
                        <div style="display: none">
                            @selectprovidertypes("provider_type_id[]",["id"=>"providertype", "onchange" => "providerSubtype(this)"])
                            @selectspecialpractices("provider_subtype_id[]",["id"=>"provider_subtype"])
                        </div>                
                        <div class="form-group" id="appendprovider"></div> 
                    </div>
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit"  class="btn  btn-primary m-1 save_practice" id="buttonHeading1">Save</button>
                                <!-- <button type="button" class="btn btn-info float-left additionalProvider" id="additionalProvider">Add Provider</button> -->
                                <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('Practices::practice-threshold')