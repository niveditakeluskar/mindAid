@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">

@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
    <div class="col-md-11">
     <h4 class="card-title mb-3">RPM Vitals Protocol</h4>
   </div>
    <div class="col-md-1">
     <a class="" href="javascript:void(0)" id="UploadDocument"><i class="add-icons i-Administrator" data-toggle="tooltip" data-placement="top" title="Upload Document"></i><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Upload Document"></i></a>  
    </div>
 </div>
 <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<div id="msg"></div>
 <div class="alert alert-success alert-block" style="display: none">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>You have successfully upload file.</strong>
                </div>
<div class="row mb-4">
  <div class="col-md-12 mb-4">
    <div class="card text-left">
      <div class="card-body">
       <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
       @include('Theme::layouts.flash-message')
       <div class="table-responsive">
        <table id="alert-document-list" class="display table table-striped table-bordered" style="width:100%">
          <thead>
            <tr>
                            <th width="30px">Sr No.</th>
                            <th width="50px">Device</th>
                            <th width="80px">Document Name</th>
                            <th width="80px">Last Modified By</th>
                            <th width="80px">Last Modified On</th>   
                            <th width="80px">Action</th>                         
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

     
<div class="modal fade" id="rpm_protocol_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modelHeading1">Upload RPM Vitals Protocol Document</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
      <form method="POST" id="rpmprotocol_form" form="rpmprotocol_form" enctype="multipart/form-data">
         {{ csrf_field() }}

         <div class="form-group">
            <div class="row">
                      <div class="col-md-7 ">
                        <div class="form-group">
                          <label for="device">Select Device<span class="error">*</span></label>
                        @selectDevice("device",["id"=>"device"])
                        </div>
                       </div>
                     </div>
                     <br>
                    <div class="row">
                        <div class="col-md-7 form-group">
                           <!-- <label for="practice_id">Upload File<span class="error">*</label> -->
                            <!-- <input type="file" name="file" class="form-control"> -->
                            <div _ngcontent-fcp-c8="" class="custom-file"><input _ngcontent-fcp-c8="" class="custom-file-input form-control" id="file"  name="file" type="file"><div class="invalid-feedback"></div><label _ngcontent-fcp-c8="" aria-describedby="inputGroupFileAddon02" class="custom-file-label" for="inputGroupFile02">Choose file</label></div>
                            (.pdf )
                        </div>
                        <div class="col-md-5 progress" id="progress-bar2" style="display: none">
                        <div class="progress-bar" id="progress-bar1"></div>
                         </div>
                  </div>
       </div>
       <div class="card-footer">
        <div class="mc-footer">
          <div class="row">
            <div class="col-lg-12 text-right">
            <button type="button" class="btn btn-success" onclick="uploadfile()">Upload</button>
            <button type="button" id="resetbutton" class="btn btn-primary" onclick="resetform()">Reset</button>
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


@endsection

@section('page-js')
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script type="text/javascript">
 var renderRPMProtocolTable =  function() {
  var columns= [
    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
    {data: 'devices',  mRender: function(data, type, full, meta){
     if(data!='' && data!='NULL' && data!=undefined){
         return data['device_name'];
     }
    }},   
      {data: 'devices',  mRender: function(data, type, full, meta){
     if(data!='' && data!='NULL' && data!=undefined){
       var foldername= data['device_name'].replace(' ', '-');
         return '<a href="/Vitals-Alert-Protocol-Documentation/'+foldername+'/'+full['file_name']+'" target="_blank">'+
         full['file_name']+'</a>';
     }
    }},
    {data: 'users',
        mRender: function(data, type, full, meta){
        if(data!='' && data!='NULL' && data!=undefined){
          l_name = data['l_name'];
        if(data['l_name'] == null && data['f_name'] == null){
          l_name = '';
          return '';
        }
        else
        {

          return data['f_name'] + ' ' + l_name;
        }
        } else { 
            return ''; 
        }    
        },orderable: true
        }, 
    {data: 'updated_at',name:'updated_at'},
     {data: 'action',name:'action'}
   
    ];
  var table = util.renderDataTable('alert-document-list', "{{ route('rpm.vital.document.list') }}", columns, "{{ asset('') }}");   
 };

$( document ).ready(function() {
    renderRPMProtocolTable();

   $('body').on('click', '.change_rpmprotocolstatus_active', function () {
    var splitid = $(this).data('id');
      var res = splitid.split("/");
      var id=res[0];
      var deviceid=res[1];
    if (confirm("Are you sure you want to Deactivate this Vitals Protocol")) {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({        
        type: 'post',
        url: '/admin/vitalsprotocolStatus/' + id+'/'+deviceid,
        // data: {"_token": "{{ csrf_token() }}","id": id},
        data: { "id": id,"deviceid":deviceid },
        success: function (response) {
          renderRPMProtocolTable();
          $("#success").show();
          var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Vitals Protocol Deactivated Successfully!</strong></div>';
          $("#success").html(txt);
          var scrollPos = $(".main-content").offset().top;
          $(window).scrollTop(scrollPos);
          //goToNextStep("call_step_1_id");
          setTimeout(function () {
            $("#success").hide();
          }, 3000);
        }
      });
    } else { return false; }
  });

  $('body').on('click', '.change_rpmprotocolstatus_deactive', function () {
    var splitid = $(this).data('id');
      var res = splitid.split("/");
      var id=res[0];
      var deviceid=res[1];
    if (confirm("Are you sure you want to Activate this Vitals Protocol")) {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type: 'post',
        url: '/admin/vitalsprotocolStatus/' + id+'/'+deviceid,        
        data: { "id": id ,"deviceid":deviceid },
        success: function (response) {
        renderRPMProtocolTable();
          $("#msg").show();
          var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Vitals Protocol  Activated Successfully!</strong></div>';
          $("#msg").html(txt);
          var scrollPos = $(".main-content").offset().top;
          $(window).scrollTop(scrollPos);
          //goToNextStep("call_step_1_id");
          setTimeout(function () {
            $("#msg").hide();
          }, 3000);
        }
      });
    } else { return false; }
  });
});

$('#UploadDocument').click(function(){
    $('#rpm_protocol_modal').modal('show');
    $('#device').val(''); 
            $(".custom-file-input").siblings(".custom-file-label").html('Choose file');
            $("#file").val(''); 
            $("#device").removeClass("is-invalid");
            $("#device").next(".invalid-feedback").html('');
            $("#file").removeClass("is-invalid");
            $("#file").next(".invalid-feedback").html(''); 
});

$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});


            function uploadfile() {             
              var device=$('#device').val();   
              var file_data = $("#file").prop("files")[0];                
              var form_data = new FormData();
              form_data.append("device", device);           
              form_data.append("file", file_data);

              $.ajax({
                xhr: function() {
                  var xhr = new window.XMLHttpRequest();
                  if(file_data==undefined)
                  {
                    $('.progress').hide();
                  }else {
                     $('.progress').show();
                  xhr.upload.addEventListener("progress", function(evt) {
                      if (evt.lengthComputable) {
                          var percentComplete = ((evt.loaded / evt.total) * 100);
                          $(".progress-bar").width(percentComplete + '%');
                          $(".progress-bar").html(percentComplete+'%');
                      }
                  }, false);
                }
                  return xhr;

                },
                url: '/admin/save-file-upload',
                type: 'POST',
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: form_data,
                 beforeSend: function(){
                $(".progress-bar").width('0%');
              //  $('#uploadStatus').html('<img src="images/loader.gif"/>');
                 },
                enctype: 'multipart/form-data',
                error: function (xhr, status, error) {
                  var dataerror=JSON.parse(xhr.responseText);
                  //  console.log(xhr.responseText);    
                      console.log(dataerror.errors.device+"testlength");  
                  if(dataerror.errors.device==undefined) 
                 {
                   $("#device").removeClass("is-invalid");
                 $("#device").next(".invalid-feedback").html('');
                 } 
                else
                {
                  $("#device").addClass("is-invalid");
                  $("#device").next(".invalid-feedback").html(dataerror.errors.device[0]);
                   
                 }          
                if(dataerror.errors.file==undefined) 
                 {
                 $("#file").removeClass("is-invalid");
                 $("#file").next(".invalid-feedback").html('');
                 }
                 else
                 {
                   $("#file").addClass("is-invalid");
                   $("#file").next(".invalid-feedback").html(dataerror.errors.file[0]);
                 }
                },
                success: function(data) {
                    $('.progress').hide();
                   $('#rpm_protocol_modal').modal('hide');
                  renderRPMProtocolTable();
                   $("#device").removeClass("is-invalid");
                 $("#device").next(".invalid-feedback").html('');
                   $("#file").removeClass("is-invalid");
                 $("#file").next(".invalid-feedback").html('');
                  $('.alert-success').show();
                  setTimeout(function () {
                  $(".alert-success").hide();     
                  $('#device').val(''); 
                  $(".custom-file-input").siblings(".custom-file-label").html('Choose file');
                  $("#file").val('');                            
                      }, 3000);        
                  
                },
                cache: false,
                contentType: false,
                processData: false
              });
          }

          function resetform()
          {
            $('#device').val(''); 
            $(".custom-file-input").siblings(".custom-file-label").html('Choose file');
            $("#file").val(''); 
            $("#device").removeClass("is-invalid");
            $("#device").next(".invalid-feedback").html('');
            $("#file").removeClass("is-invalid");
            $("#file").next(".invalid-feedback").html('');  
            document.getElementById("progress-bar1").style.display="none";
            document.getElementById("progress-bar2").style.display="none";
          }
</script>

  

@endsection

