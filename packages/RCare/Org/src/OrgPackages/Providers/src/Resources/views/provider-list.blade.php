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
                <strong>Provider Data saved successfully! </strong><span id="text"></span>
              </div>
              <div id ="provider_success"></div>
            </div>
            <div class="col-md-2">
              <a class="btn btn-success btn-sm mb-4" href="javascript:void(0)" id="addProvider">Add Provider</a>  
            </div>
            <div class="separator-breadcrumb border-top"></div>
          <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
              @include('Theme::layouts.flash-message')
            <div class="table-responsive">
              <table id="providerList" class="display table table-striped table-bordered" style="width:100%">
                <thead>
                  <tr>
                    <th width='10px'>Sr No.</th>
                    <th width='10px'>Provider </th>  
                    <th width='10px'>Practice </th>
                    <th width='10px'>Provider Type</th>
                    <th width='10px'>Credential</th>  
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
        <h4 class="modal-title" id="modelHeading1">Edit Provider</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button> 
      </div>
      <form action="{{ route('update_org_provider') }}" method="POST" id="EditProviderForm" name="EditProviderForm" class="form-horizontal">
          {{ csrf_field() }}
        <input type="hidden" name="id" id="id">
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              <div class="col-md-12  form-group mb-3 ">
               <label for="physicianame">Select Practice <span class='error'>*</span></label>
               @selectpractices("practice_id", ["placeholder" => "Select Practice" ])
              </div>
              <div class="col-md-12  form-group mb-3 ">
                 <label for="physicianame">Select Provider Type <span class='error'>*</span></label>
                @selectprovidertypes("provider_type_id",["id"=>"providertype"])
               
              </div>
              <div class="col-md-12  form-group mb-3">
                 <label for="physicianame">Select Credential</label>
                @selectspecialpractices("provider_subtype_id",["id"=>"provider_subtype_id"])
              </div>
              <div class="col-md-12  form-group mb-3" >
                 <label for="speciality">Select Speciality</label>
                @selectspeciality("speciality_id",["placeholder" => "select speciality" ])
              </div>
              
              <div class="col-md-12  form-group mb-3 ">
               <label for="physicianame">Provider <span class='error'>*</span></label>
               @text("name", ["placeholder" => "Enter Provider" ])
              </div>
              <div class="col-md-12  form-group mb-3 ">
               <label for="physicianname">Email<span class='error'>*</span></label>
               @email('email')
              </div>
              <div class="col-md-12  form-group mb-3 ">
               <label for="physicianname">Address<span class='error'>*</span></label>
               @text('address')
              </div> 
              <div class="col-md-12  form-group mb-3 ">
               <label for="name">Phone Number<span class='error'>*</span></label>
               @phone('phone')
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
      </form>
    </div>
  </div> 
</div>


<div class="modal fade" id="add_provider_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modelHeading1">Add Provider</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action="{{ route('create_org_provider') }}" name="AddProviderForm" id="AddProviderForm" method="post">
          {{ csrf_field() }}
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              <div class="col-md-12  form-group mb-3 ">
               <label for="physicianame">Select Practice <span class='error'>*</span></label>
               @selectpractices("practice_id", ["placeholder" => "Select Practice Name" ])
              </div>
              <div class="col-md-12  form-group mb-3 ">
                 <label for="physicianame">Select Provider Type <span class='error'>*</span></label>
                @selectprovidertypes("provider_type_id",["id"=>"providertype"])
              
              </div>
              <div class="col-md-12  form-group mb-3" >
                 <label for="physicianame">Select Credential</label>
                @selectspecialpractices("provider_subtype_id",["id"=>"provider_subtype_id"])
              </div>
               <div class="col-md-12  form-group mb-3" >
                 <label for="speciality">Select Speciality</label>
                @selectspeciality("speciality_id",["placeholder" => "Select Speciality" ])
              </div>
              <div class="col-md-12  form-group mb-3 ">
               <label for="physicianame">Provider <span class='error'>*</span></label>
               @text("name", ["placeholder" => "Enter Provider Name" ])
              </div>
              <div class="col-md-12  form-group mb-3 ">
               <label for="physicianname">Email <span class='error'>*</span></label>
               @email('email')
              </div>
              <div class="col-md-12  form-group mb-3 ">
               <label for="physicianname">Address<span class='error'>*</span></label>
               @text('address')
              </div> 
              <div class="col-md-12  form-group mb-3 ">
               <label for="name">Phone Number<span class='error'>*</span></label>
               @phone('phone')
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