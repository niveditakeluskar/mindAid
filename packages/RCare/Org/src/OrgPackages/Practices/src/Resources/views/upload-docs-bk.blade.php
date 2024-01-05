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
                <strong>Data saved successfully! </strong><span id="text"></span>
              </div> 
              <div id ="success"></div>
            </div>
            <div class="col-md-2">
              <a class="btn btn-success btn-sm mb-4 ml-5" href="javascript:void(0)" id="add_docs">Add docs</a>  
            </div>
            <div class="separator-breadcrumb border-top"></div>
          <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
              @include('Theme::layouts.flash-message')
            <div class="table-responsive">
              <table id="docsList" class="display table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
                          <th width ="10px">Sr No.</th>
                          <!-- <th width ="10px">id</th> -->
                          <th width ="10px">Practice</th>        
                          <th width ="10px">Provider</th>
                          <th width ="10px">Document Type</th>
                          <th width ="10px">Document Name</th>
                          <th width ="10px">Document Comment</th>
                          <!-- <th width ="10px">Document Content</th> -->
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
<div class="modal fade" id="add_docs_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 635px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1">Document</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('uploadFile') }}" method="POST" name="AddDocumentForm" id="AddDocumentForm" enctype="multipart/form-data"> 
                {{ csrf_field() }}
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4 form-group mb-3 ">
                                <label for="practicename">Practice<span style="color:red">*</span></label>
                                 @selectpractices("practice_id",["id" => "practices", "class" => "form-control show-tick"]) 
                                 <span class="error"><p id="practice_error"></p></span>
                                 <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4 form-group mb-3 ">
                                <label for="practicename">Provider</label>
                                @selectpracticesphysician("provider_id",["id" => "physician","class"=>"custom-select show-tick"])
                            </div>
                            <div class="col-md-4 form-group mb-3">
                              <label for="practicename">Document Name<span style="color:red">*</span></label>
                                 @text("doc_name",["id" => "doc_name", "class" => "form-control"])
                                 <span class="error"><p id="docname_error"></p></span>
                            </div> 
                            <div class="col-md-4 form-group mb-3">
                              <label for="practicename">Document Comment</label>
                                 @text("doc_comments",["id" => "doc_comments", "class" => "form-control"]) 
                            </div>
                            <div class="col-md-4 form-group mb-3">
                              <label for="practicename">Document Type</label><span style="color:red">*</span>
                                @selectdocument("doc_type",["id" => "doc_id","class" => "custom-select show-tick documents"])
                                <span class="error"><p id="doctype_error"></p></span>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                              <div id="dvPassport" class="dvPassport" style="display: none">
                                <label for="practicename">Other Document Type</label>
                                  @text("other_doc_type", ["id" =>"doc_id" , "class" => "form-control"])
                                  <div class="invalid-feedback"></div>
                              </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="file"><span class="error"></span>Upload file<span style="color:red">*</span></label>
                                    @file("file", ["id" => "uploadfile", "class" => "form-control"]) <!-- 'onchange'=>"uploadfile()" -->
                                    <div class="invalid-feedback"></div>
                                    <input type="hidden" name="doc_content" id="doc_content">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="uploading_img_loader" style="display:none;" class="loader-bubble loader-bubble-primary m-5"></div>
                            </div>
                            <div class="col-md-6">
                                <div id="uploading_img_loader" style="display:none;" class="loader-bubble loader-bubble-primary m-5"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit"  class="btn  btn-primary m-1 save_uploaddocfile" onclick="return uploaddocfile()">Save</button>
                                <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>