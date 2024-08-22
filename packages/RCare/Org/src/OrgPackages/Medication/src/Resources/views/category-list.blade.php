@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
<div class="tsf-step-content">
  <div class="row">
    <div class="col-lg-12 mb-3">
      <div class="card">
        <div class="alert alert-success" id="success-alert" style="display: none;">
          <button type="button" class="close" data-dismiss="alert">x</button>
          <strong>Category Data  Saved Successfully</strong><span id="text"></span>
        </div>
        <div id="provider_speciality_success"></div>
        <div class=" card-body">
          <div class="row">
           <div class="col-md-10">
            <!-- <h4 class="card-title mb-3">Physicians Type</h4> -->
          </div>
          <div class="col-md-2">
            <a class="btn btn-success btn-sm mb-4" href="javascript:void(0)" id="AddCategory"> Add Category</a>  
          </div>
          <div class="separator-breadcrumb border-top"></div>
          <!-- <div id="provider_type_success"></div> -->
          
          @include('Theme::layouts.flash-message')
          <div class="table-responsive">
            <table id="CategoryList" class="display table table-striped table-bordered" style="width:100%">
              <thead>
                <tr>
                  <th width='20px'>Sr No.</th>
                  <th width='20px'>Category</th> 
                  <th width='20px'>Last Modifed By</th>
                  <th width='20px'>Last Modifed On</th>   
                  <th width='20px'>Action</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <div class="mc-footer">
         <div class="row">
          <div class="col-lg-12 text-right" id="call-save-button" >
          </div>
        </div>
      </div>
    </div>
    </div>
    </div>
</div>
</div>


<div class="modal fade" id="edit_category_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
     <div class="modal-header">
      <h4 class="modal-title" id="modelHeading1">Edit Category</h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action="{{ route('update_org_category') }}" method="POST" id="EditCategoryForm" name="EditCategoryForm" class="form-horizontal">
        {{ csrf_field() }}
        <div class="modal-body">  
          <input type="hidden" name="id" id="hidden_category_id">
          <div class="form-group">
            <div class="row">
              <div class="col-md-12  form-group mb-3 ">
               <label for="physicianname">Category<span style="color:red">*</span></label>
                  @text("category")
              </div>
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
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="add_category_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modelHeading1">Add Category</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action="{{ route('create_org_category') }}" name="AddCategoryForm" id="AddCategoryForm" method="post">
        {{ csrf_field() }}
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              <div class="col-md-12  form-group mb-3 ">
               <label for="physicianame">Category<span style="color:red">*</span></label>
               @text("category")
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
