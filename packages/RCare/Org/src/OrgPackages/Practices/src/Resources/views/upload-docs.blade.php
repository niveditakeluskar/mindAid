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
            @if(isset($successdocsmessage))
              {{ $successdocsmessage }}
            @endif
            <div class="col-md-2">
              <a class="btn btn-success btn-sm mb-4 ml-5" href="javascript:void(0)" id="add_docs">Add docs</a>  
            </div>
            <div class="separator-breadcrumb border-top"></div>
            <div class="table-responsive">
              <table id="docsList" class="display table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
                          <th width ="10px">Sr No.</th>
                          <th width ="10px">Practice</th>        
                          <th width ="10px">Provider</th>
                          <th width ="10px">Document Type</th>
                          <th width ="10px">Document Name</th>
                          <th width ="10px">Document Comment</th>
                          <th width ="10px">Document Content</th>
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
                <h4 class="modal-title" id="modelHeading1"><span class='form_type'></span>Document</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" enctype="multipart/form-data" name="AddDocumentForm" id="AddDocumentForm" action="javascript:void(0)" >
                <div class="modal-body"> 
                    <input type="hidden" name="id" id="document_id">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4 form-group mb-3 ">
                                <label for="practice_id">Practice<span style="color:red">*</span></label>
                                 @selectpractices("practice_id",["id" => "practice_id"])
                            </div>
                            <div class="col-md-4 form-group mb-3 ">
                                <label for="provider_id">Provider<span style="color:red">*</span></label>
                                @selectpracticesphysician("provider_id",["id" => "provider_id"])
                            </div>
                            <div class="col-md-4 form-group mb-3">
                              <label for="doc_name">Document Name<span style="color:red">*</span></label>
                                 @text("doc_name",["id" => "doc_name"])
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="file"><span class="error" style="display:none"></span>Upload file<span style="color:red">*</span></label>
                                    @file("file", ["id" => "uploadfile"])
                                </div>
                                <input type="hidden" name ="exist_docs" id="exist_docs">
                                <div id ="doc_filecontent"><div id="viewdocs"></div></div>
                            </div>
                            <div class="col-md-6 form-group mb-3" id="dvtype">
                              <label for="practicename">Document Type</label><span style="color:red">*</span>
                                @selectdocument("doc_type",["id" => "doc_type","class" => "documents"])
                            </div> 
                            <div class="col-md-3 form-group mb-3">
                              <div id="dvPassport" class="dvPassport" style="display: none">
                                <label for="practicename">Other Document Type</label>
                                  @text("other_doc_type", ["id" =>"other_doc_type"])
                              </div>
                            </div>
                            <div class="col-md-12 form-group mb-3">
                                <label for="practicename">Document Comment</label>
                                 @text("doc_comments",["id" => "doc_comments"])
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right"> 
                                <button type="submit"  class="btn  btn-primary m-1" id ="upload-doc-btn">Save</button>
                                <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
  $('#AddDocumentForm').submit(function(e) {
        // $("#add_docs_modal").hide();
        $("#docspreloader").css("display","block");
        e.preventDefault();
        var formName = $(`form[name="AddDocumentForm"]`);
        formName.find(".is-invalid").removeClass("is-invalid");
        formName.find(".invalid-feedback").html("");
        var formData = new FormData(this);
        $.ajax({
          type:'POST',
          url: "{{ url('org/uploadFile')}}",
          data: formData,
          cache:false,
          contentType: false,
          processData: false,
          success: (data) => {
            // this.reset();
            $("#AddDocumentForm")[0].reset();
            $("#document_id").val("");
            $("#add_docs_modal").modal('toggle');
            $("#docspreloader").css("display","none");
            util.updateDocsOtherList($("form[name='AddDocumentForm'] #doc_type"));
            $("#docs #success-alert").show();
            $("#docs #success-alert strong").text(data);
            setTimeout(function () { $("#docs #success-alert").hide(); }, 5000);
            renderDocsTable(); 
          }, error: function(response){
              var fields = form.getFields('AddDocumentForm');
              var errors = response.responseJSON.errors;
              var fieldNames = Object.keys(errors);
              for (let i = 0; i < fieldNames.length; i++) {
                  try {
                      let field = fields.fields[fieldNames[i]];
                      if (!field)
                          return;
                      if (field.attr("data-feedback")) {
                          $(`[data-feedback-area="${field.attr("data-feedback")}"]`).html(errors[fieldNames[i]]);
                      } else {
                          if (field.next(".invalid-feedback").length > 0) {
                              field.next(".invalid-feedback").html(errors[fieldNames[i]]);
                          } else {
                              field.closest(".forms-element").next().html(errors[fieldNames[i]]);
                              field.closest(".forms-element").next().css("display", "block");
                          }
                      }
                      field.addClass("is-invalid");
                  } catch (e) {
                      console.error(`Ajax error reporting: for field ${fieldNames[i]}`, e);
                  }
              }
              $("#docspreloader").css("display","none");
          },
          complete: function(){
              $("#preloader").css("display","none");
              $("#docspreloader").css("display","none");
          }
        });
  });

    $('body').on('click', '.editDocs', function () {
  var formName = $(`form[name="AddDocumentForm"]`);
    formName.find(".is-invalid").removeClass("is-invalid");
    formName.find(".invalid-feedback").html("");

    $(".dvPassport").hide();
    $("form[name='AddDocumentForm'] #dvtype").removeClass("col-md-3").addClass("col-md-6");
  });

  $('#add_docs').click(function () {
    var formName = $(`form[name="AddDocumentForm"]`);
    formName.find(".is-invalid").removeClass("is-invalid");
    formName.find(".invalid-feedback").html("");

    $(".dvPassport").hide();
    $("form[name='AddDocumentForm'] #dvtype").removeClass("col-md-3").addClass("col-md-6");
    $("#AddDocumentForm")[0].reset();
    $("#document_id").val("");
    $(".form_type").text("Add ");
  });
});
</script>