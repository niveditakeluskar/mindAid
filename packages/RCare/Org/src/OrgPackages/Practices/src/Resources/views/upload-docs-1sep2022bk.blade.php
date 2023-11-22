
 <!-- Pre Loader Strat  -->
 
 <div class='docsloadscreen' id="docspreloader" style="display:none;">
   <div class="loader "><!-- spinner-bubble spinner-bubble-primary -->
			<img src="{{'/images/loading.gif'}}" width="150" height="150">
    </div>
  </div>
  <!-- Pre Loader end  -->




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
          <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
              <!-- @include('Theme::layouts.flash-message') -->

              <!-- @if(session()->has('successdocsmessage'))
                    {{'hello'}}
              @else
              {{'helhilo'}}
              @endif -->

              <!-- @if($successdocsmessage = Session::get('successdocsmessage'))
              {{'hello'}}
              @else
              {{'helhilo'}}
              @endif -->

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
                <h4 class="modal-title" id="modelHeading1">Document</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- <form action="{{-- route('uploadFile') --}}" method="POST" name="AddDocumentForm" id="AddDocumentForm" enctype="multipart/form-data">  -->
            <form method="POST" enctype="multipart/form-data" name="AddDocumentForm" id="AddDocumentForm" action="javascript:void(0)" >
                {{ csrf_field() }}
                <div class="modal-body"> 
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4 form-group mb-3 ">
                                <label for="practicename">Practice<span style="color:red">*</span></label>
                                 @selectpractices("practice_id",["id" => "practices", "class" => "form-control show-tick"]) 
                                 <span class="error" style="display:none" id="practice_error">practice required</span>
                                 <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4 form-group mb-3 ">
                                <label for="practicename">Provider<span style="color:red">*</span></label>
                                @selectpracticesphysician("provider_id",["id" => "providers","class"=>"custom-select show-tick"])
                                <span class="error" style="display:none" id="provider_error">provider required</span>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                              <label for="practicename">Document Name<span style="color:red">*</span></label>
                                 @text("doc_name",["id" => "doc_name", "class" => "form-control"])
                                 <span class="error" style="display:none" id="docname_error">document name required</span>
                            </div> 
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="file"><span class="error" style="display:none"></span>Upload file<span style="color:red">*</span></label>
                                    @file("file", ["id" => "uploadfile", "class" => "form-control"])
                                    <span class="error" style="display:none" id="file_error">file required</span>
                                </div>
                                <input type="hidden" name ="exist_docs" id="exist_docs">
                                <div id ="doc_filecontent"><div id="viewdocs"></div></div>
                            </div>

                            <div class="col-md-6 form-group mb-3" id="dvtype">
                              <label for="practicename">Document Type</label><span style="color:red">*</span>
                                @selectdocument("doc_type",["id" => "doc_type","class" => "custom-select show-tick documents"])
                                <span class="error" style="display:none" id="doctype_error">document type required</span>
                            </div> 
                            <div class="col-md-3 form-group mb-3">
                              <div id="dvPassport" class="dvPassport" style="display: none">
                                <label for="practicename">Other Document Type</label>
                                  @text("other_doc_type", ["id" =>"other_doc_type" , "class" => "form-control"])
                                  <span class="error" style="display:none" id="otherdoctype_error">document type required</span>
                              </div>
                            </div>
                            
                            <div class="col-md-12 form-group mb-3">
                                <label for="practicename">Document Comment</label>
                                 @text("doc_comments",["id" => "doc_comments", "class" => "form-control"])
                                 <span class="error" style="display:none" id="doccmnt_error">comments required</span> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right"> 
                                <!-- <button type="submit"  class="btn  btn-primary m-1" id ="heeloo" onclick="return uploaddocfile()">Save</button> -->
                                <button type="submit"  class="btn  btn-primary m-1" id ="heeloo">Save</button>
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
    var $id = $("#id").val();
        //alert($('#uploadfile').val());
        //alert($("#exist_docs").val());
        if($("#practices").val()=='' || $("#practices").val()==' '){
            $("#practice_error").show(); 
            return false;
        }else{ 
            $("#practice_error").hide(); 
        }

        if($("#providers").val()=='' || $("#providers").val()==' '){
            $("#provider_error").show(); 
            return false;
        }else{
            $("#provider_error").hide(); 
        }
        if($("#doc_name").val()=='' || $("#doc_name").val()==' '){
            $("#docname_error").show(); 
            return false;
        }else{
            $("#docname_error").hide(); 
        }

        if($("#doc_type").val()== '0' && $("#other_doc_type").val()==' '){
            $("#other_doc_type").show();
            return false;
        }else{
            $("#other_doc_type").hide(); 
        }
        
        if($("#doc_type").val()=='' || $("#doc_type").val()==' '){
            $("#doctype_error").show();
            return false;
        }else{
            $("#doctype_error").hide(); 
        }

        if($("#uploadfile").val()=='' || $("#uploadfile").val()==' '){
          if($("#exist_docs").val()=='' || $("#exist_docs").val()==' '){ 
              $("#file_error").show(); 
              return false;
          }
        }else{
            var ext = $('#uploadfile').val();
            var file_size = $('#uploadfile')[0].files[0].size;
            //alert(ext);
            if(file_size >= 10485760) { //  10485760
              $("#file_error").show().html("File size is greater than 10MB");
               return false;
            }
              
            var allowedExtensions =
                    /(\.doc|\.docx|\.pdf)$/i;
               
            if (!allowedExtensions.exec(ext)) {
            //if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
                $("#file_error").show().html("invalid file extention");
                return false;
            }              
            $("#file_error").hide();

            // var asdf = allowedExtensions.exec(ext);
            // alert(asdf);
            
            // if(allowedExtensions.exec(ext) == ".doc,.doc"  && file_size >= 8192){
            //   //alert("working");
            //   $("#file_error").show().html("Something wrong.");
            //   return false;
            // }

            // if(allowedExtensions.exec(ext) == ".docx,.docx"  && file_size >= 8192){
            //   //alert("working");
            //   $("#file_error").show().html("Something wrong.");
            //   return false;
            // }
        }
        
        // if($("#doc_comments").val()=='' || $("#doc_comments").val()==' '){
        //     $("#doccmnt_error").show(); 
        //     return false;
        // }else{
        //     $("#doccmnt_error").hide(); 
        // }
        // $("#add_docs_modal").hide();
        $("#preloader").css("display","block");
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
          type:'POST',
          url: "{{ url('org/uploadFile')}}",
          data: formData,
          cache:false,
          contentType: false,
          processData: false,
          success: (data) => {
            $("#add_docs_modal").modal('toggle');
            $("#preloader").css("display","none");
            // $("#preloader").css("display","none");
            // $("#preloader").attr('style', 'display: none');
            this.reset();
            // alert('File has been uploaded successfully');
            // console.log(data);
            $("#docs #success-alert").show();
            $("#docs #success-alert strong").text(data);
            setTimeout(function () { $("#docs #success-alert").hide(); }, 5000);
            renderDocsTable(); 
          },
          error: function(data){
            console.log(data);
          }
        });
  });
});
</script>