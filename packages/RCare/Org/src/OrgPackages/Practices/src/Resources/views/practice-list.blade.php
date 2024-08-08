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
                          <th width ="10px">Practice Number</th>
                          <th width ="10px">Address</th>
                          <th width ="10px">Phone Number</th>
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
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Location <span style="color:red">*</span></label>
                                @text("location",["placeholder" => "Enter Location"])
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Phone Number <span style="color:red">*</span></label>
                                @phone("phone",["placeholder" => "Enter Phone Number"])
                            </div>
                            
                            <div class="col-md-12  form-group mb-3 ">
                                <label for="practicename">Address <span style="color:red">*</span></label>
                                <textarea class="form-control forms-element" name="address" placeholder = "Enter Address"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>                   
                        </div>

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