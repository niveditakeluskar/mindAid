@extends('Theme::layouts.master')
@section('page-css')

<!-- <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}"> -->


@endsection

@section('main-content')

<div class="breadcrusmb">
    <div class="row">
		<div class="col-md-11">
		   <h4 class="card-title mb-3">Bulk Upload Devices</h4>
		</div>
    </div>            
</div> 


<div class="separator-breadcrumb border-top"></div>
 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">                    
                @include('Theme::layouts.flash-message')
                <!-- <div class="container mt-5"> -->
                    <form action="javascript:void(0)" method="post" name ="device_upload_docs" id="device_upload_docs" enctype="multipart/form-data">
                      <!-- <h3 class="text-center mb-5"></h3> -->
                        @csrf
                        <div class="alert alert-success" id="success-alert" style="display: none;">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong> </strong><span id="text"></span>
                        </div>
                        <div class="alert alert-danger" id="danger-alert" style="display: none;">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong> </strong><span id="danger-text"></span>
                        </div>
                        <div class="form-row col-md-12">
                            <!-- <div class="col-md-2 form-group mb-3">
                                <label for="practicename">Practice</label><span class="error">*</span>
                                @selectpracticespcp("practices", ["id" => "practices", "class" => "select"])  
                            </div> -->

                            <div class="col-md-4 form-group mb-3" >
                              <label for="file">File</label><span class="error">*</span>
                                @file("file", ["id" => "uploadfile"])
                            </div>
                            <div class="col-md-1">
                                <button type="submit" id="submit" class="btn btn-primary mt-4" >Upload Files</button>
                            </div>
                        </div>
                    </form>
                <!-- </div> -->
                <div id="app">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-js')

<!-- <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script> -->
<!-- <script src="{{asset('assets/js/datatables.script.js')}}"></script> -->
<script type="text/javascript">
$(document).ready(function () {
  $('#device_upload_docs').submit(function(e) {
        e.preventDefault();
        var formName = $(`form[name="device_upload_docs"]`);
        formName.find(".is-invalid").removeClass("is-invalid");
        formName.find(".invalid-feedback").html("");
        var formData = new FormData(this);
        $.ajax({
          type:'POST',
          url: "{{ url('/rpm/bulkupload-rpm-device')}}",
          data: formData,
          cache:false,
          contentType: false,
          processData: false,
          success: (data) => {
            // this.reset();
            if(data == 1){
              $("#device_upload_docs")[0].reset();
              $("#success-alert").show();
              $("#success-alert strong").text("File Uploaded Successfully.!");
              setTimeout(function () { $("#success-alert").hide(); }, 5000);
            } else{
              $("#device_upload_docs")[0].reset();
              $("#danger-alert").show();
              $("#danger-alert strong").text(data);
              setTimeout(function () { $("#danger-alert").hide(); }, 5000);
            }
          }, error: function(response){  
              var fields = form.getFields('device_upload_docs');
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
          },
          complete: function(){          }
        });
    });
});

</script>
@endsection
















