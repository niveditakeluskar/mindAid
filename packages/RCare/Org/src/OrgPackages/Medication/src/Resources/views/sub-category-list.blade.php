<div class="tsf-step-content">
  <div class="row">
    <div class="col-lg-12 mb-3">
      <div class="card">
        <div class="alert alert-success" id="success-alert" style="display: none;">
          <button type="button" class="close" data-dismiss="alert">x</button>
          <strong>Sub Category  Data saved successfully! </strong><span id="text"></span>
        </div>
        <div id="subcategory_success"></div>
        <div class=" card-body">
          <div class="row">
            <div class="col-md-10">
              <!-- <h4 class="card-title mb-3">Physicians</h4> -->
            </div>
            <div class="col-md-2">
              <a class="btn btn-success btn-sm mb-4" href="javascript:void(0)" id="addSubcategory"> Add Sub Category </a>  
            </div>
            <div class="separator-breadcrumb border-top"></div>
            <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
            @include('Theme::layouts.flash-message')
            <div class="table-responsive">
              <table id="SubCategoryList" class="display table table-striped table-bordered" style="width:100%">
                <thead>
                  <tr>
                    <th width='20px'>Sr No.</th>
                    <th width='20px'>Category </th> 
                    <th width='20px'>Sub Category</th>
                    <th width='20px'>Last Modifed By</th>
                    <th width='20px'>Last Modifed On</th>
                    <th width='20px'>Action</th>
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

<div class="modal fade" id="edit_subcategory_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modelHeading1">Edit Sub category </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
          <form action="{{ route('update_org_subcategory') }}" method="post" id="EditSubcategoryForm" name="EditSubcategoryForm" class="form-horizontal">
          {{ csrf_field() }}
          <input type="hidden" name="id" id="hidden_subcategory_id">
          <div class="form-group">
            <div class="row">
              <div class="col-md-12  form-group mb-3 ">
                <label for="physicianame">Category<span style="color:red">*</span></label>
                @selectcategory("category",["id"=>"category"])
              </div>
              <div class='invalid-feedback'></div>
              <div class="col-md-12  form-group mb-3 ">
                <label for="physicianame">Sub Category<span style="color:red">*</span></label>
                @text("subcategory", ["placeholder" => "Enter Sub category "])
              </div>
              <div class='invalid-feedback'></div>
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


<div class="modal fade" id="add_subcategory_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modelHeading1">Add Sub Category </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="{{ route('create_org_subcategory') }}" name="AddSubcategoryForm" id="AddSubcategoryForm" method="post">
          {{ csrf_field() }}
          <div class="form-group">
            <div class="row">
              <div class="col-md-12  form-group mb-3 ">
                <label for="physicianame">Category<span style="color:red">*</span></label>
                @selectcategory("category",["id"=>"category"])
              </div>
              <div class='invalid-feedback'></div>
              <div class="col-md-12  form-group mb-3 ">
                <label for="physicianame">Sub Category<span style="color:red">*</span></label>
                @text("subcategory", ["placeholder" => "Enter Sub category "])
              </div>
              <div class='invalid-feedback'></div>
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
