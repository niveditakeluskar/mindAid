<div class="tsf-step-content">
  <div class="row">
    <div class="col-lg-12 mb-3">
      <div class="card">
        <div class="alert alert-success" id="success-alert" style="display: none;">
          <button type="button" class="close" data-dismiss="alert">x</button>
          <strong>Credential  Data saved successfully! </strong><span id="text"></span>
        </div>
        <div id="provider_subtype_success"></div>
        <div class=" card-body">
          <div class="row">
            <div class="col-md-10">
              <!-- <h4 class="card-title mb-3">Physicians</h4> -->
            </div>
            <div class="col-md-2">
              <a class="btn btn-success btn-sm mb-4" href="javascript:void(0)" id="addProvidersubtype"> Add Credential </a>  
            </div>
            <div class="separator-breadcrumb border-top"></div>
            <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
            @include('Theme::layouts.flash-message')
            <div class="table-responsive">
              <table id="providerSubTypeList" class="display table table-striped table-bordered" style="width:100%">
                <thead>
                  <tr>
                    <th>Sr No.</th>
                    <th>Credential </th> 
                    <th>Provider Type</th>
                    <th>Last Modifed By</th>
                    <th>Last Modifed On</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> 


<!-- Modal -->

<div class="modal fade" id="edit_provider_subtype_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modelHeading1">Edit Credential </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="{{ route('update_org_provider_subtype') }}" method="post" id="EditProviderSubtypeForm" name="EditProviderSubtypeForm" class="form-horizontal">
          {{ csrf_field() }}
          <input type="hidden" name="id" id="hidden_providersubtype_id">
          <div class="form-group">
            <div class="row">
              <div class="col-md-12  form-group mb-3 ">
                <label for="physicianame">Provider Type<span style="color:red">*</span></label>
                @selectprovidertypes("provider_type_id",["id"=>"provider_type_id"])
              </div>
              <div class='invalid-feedback'></div>
              <div class="col-md-12  form-group mb-3 ">
                <label for="physicianame">Credential<span style="color:red">*</span></label>
                @text("sub_provider_type", ["placeholder" => "Enter Credential "])
              </div>
              <div class='invalid-feedback'></div>
<!-- <div class="col-md-12  form-group mb-3 ">
<label for="physicianname">physician Email</label>
@text('address')
</div>
<div class="col-md-12  form-group mb-3 ">
<label for="physicianname">physician Contact Number</label>
@text('phone_no')
</div>   -->
</div>  
</div>
</div>
<div class="card-footer">
  <div class="mc-footer">
    <div class="row">
      <div class="col-lg-12 text-right">
        <button type="button"  class="btn  btn-primary m-1" id="updatesubtype">Update</button>
        <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
</form>
</div>
</div>
</div>


<div class="modal fade" id="add_provider_subtype_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modelHeading1">Add Credential </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="{{ route('create_org_provider_subtype') }}" name="AddProviderSubtypeForm" id="AddProviderSubtypeForm" method="post">
          {{ csrf_field() }}
          <div class="form-group">
            <div class="row">
              <div class="col-md-12  form-group mb-3 ">
                <label for="physicianame">Provider Type<span style="color:red">*</span></label>
                @selectprovidertypes("provider_type_id",["id"=>"provider_type_id"])
              </div>
              <div class="invalid-feedback"></div>
              
              <div class="col-md-12  form-group mb-3">
                <label for="physicianame">Credential<span style="color:red">*</span></label>
                @text("sub_provider_type", ["placeholder" => "Enter Credential "])
              </div>
              <div class='invalid-feedback'></div>
<!-- <div class="col-md-12  form-group mb-3 ">
<label for="physicianname">physician Email</label>
@text('address')
</div>
<div class="col-md-12  form-group mb-3 ">
<label for="physicianname">physician Contact Number</label>
@text('phone_no')
</div> -->   
</div>
</div> 
</div>
<div class="card-footer">
  <div class="mc-footer"> 
    <div class="row">
      <div class="col-lg-12 text-right">
        <button type="button"  class="btn  btn-primary m-1" id="insertsubtype">Save</button>
        <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
</form>
</div>
</div> 
</div>
