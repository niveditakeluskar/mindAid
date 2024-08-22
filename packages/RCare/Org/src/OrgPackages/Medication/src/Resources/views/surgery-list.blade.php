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
              <strong>Surgery Data saved successfully! </strong><span id="text"></span>
            </div>
            <div id ="provider_success"></div>
          </div>
          <div class="col-md-2">
            <a class="btn btn-success btn-sm mb-4" href="javascript:void(0)" id="addMedication">Add Surgery</a>  
          </div>
          <div class="separator-breadcrumb border-top"></div>
        <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
            @include('Theme::layouts.flash-message')
          <div class="table-responsive">
          <table id="MedicationList" class="display table table-striped table-bordered" style="width:100%">
              <thead>
                <tr>
                  <th width="15px">Sr No.</th>
                  <th width="15px">Name</th>      
                  <th width="15px">Code</th>  
                  <th width="15px">Description</th>      
                  <th width="15px">Category</th>  
                  <th width="15px">Sub Category</th>      
                  <th width="15px">Tentative Duration</th>  
                  <th width="15px">Last Modified By</th>
                  <th width="15px">Last Modified On</th>                 
                  <th width="15px">Action</th>
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


<!-- Modal -->
<!-- Edit medication -->
<div class="modal fade" id="edit_medication_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1">Edit Surgery</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
    
      <div class="modal-body">
      <form action="{{ route('update_org_medication') }}" method="POST" name="editMedicationForm" id="editMedicationForm">
         {{ csrf_field() }}
       <input type="hidden" name="id" id="id">
          <div class="form-group">
          <div class="row">
            <div class="col-md-12  form-group mb-3">            
                <label for="code">Code </label>
                @text("code", ["placeholder" => "Enter Code"])
              </div>
              <div class="col-md-12  form-group mb-3">            
                <label for="name">Name<span class='error'>*</span></label>
                @text("name",["placeholder" => "Enter Surgery Name"])
              </div>
              <div class="col-md-12  form-group mb-3">            
                <label for="description">Description </label>
                @text("description", ["placeholder" => "Enter Description"])
              </div>
              
              <div class="col-md-12  form-group mb-3">            
                <label for="category">Category </label>
                <!-- @text("category", ["placeholder" => "Enter Category"]) -->
                @selectcategory("category",["id"=>"category"])
              </div>
              
              <div class="col-md-12  form-group mb-3">            
                <label for="sub_category">Sub category </label>
                <!-- @text("sub_category", ["placeholder" => "Enter Sub category"]) -->
                @selectsubcategory("sub_category",["id"=>"sub_category"])
              </div>
              <div class="col-md-12  form-group mb-3">            
                <label for="duration">Tentative Duration</label>
                @text("duration",["placeholder" => "Enter Duration"])
              </div>     
            </div>
          </div>
          </div>    
          <div class="card-footer">
              <div class="mc-footer">
                  <div class="row">
                      <div class="col-lg-12 text-right">
                          <button type="submit"  class="btn  btn-primary m-1">Update Medication</button>
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




<!-- Add medication -->
<div class="modal fade" id="add_medication_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1">Add Surgery</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
    
      <div class="modal-body">
      <form action="{{ route('create_org_medication') }}" method="POST" name="AddMedicationForm" id="AddMedicationForm">
         {{ csrf_field() }}

        <div class="form-group">
          <div class="row">
            <div class="col-md-12  form-group mb-3">            
                <label for="code">Code </label>
                @text("code", ["placeholder" => "Enter Code"])
              </div>
              <div class="col-md-12  form-group mb-3">            
                <label for="name">Name <span class='error'>*</span> </label>
                <!-- <span class='error'>*</span> -->
                @text("name",["placeholder" => "Enter Surgery Name"])
              </div>
              <div class="col-md-12  form-group mb-3">            
                <label for="description">Description</label>
                @text("description", ["placeholder" => "Enter Description"])
              </div>
              
              <div class="col-md-12  form-group mb-3">            
                <label for="category">Category</label>
                <!-- @text("category", ["placeholder" => "Enter Category"]) -->
                @selectcategory("category",["id"=>"category"])
              </div>
              
              <div class="col-md-12  form-group mb-3">            
                <label for="sub_category">Sub category</label>
                <!-- @text("sub_category", ["placeholder" => "Enter Sub category"]) -->
                @selectsubcategory("sub_category",["id"=>"sub_category"])
                 
              </div>
              <div class="col-md-12  form-group mb-3">            
                <label for="duration">Tentative Duration</label>
                <!-- <span class='error'>*</span> -->
                @text("duration",["placeholder" => "Enter Duration"])
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
        </div>
      </form>
    </div>
  </div>
</div> 
