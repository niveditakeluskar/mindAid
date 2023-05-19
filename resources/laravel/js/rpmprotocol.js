const URL_POPULATE = "";
var baseURL = window.location.origin+'/';

 var renderRPMProtocolTable =  function() {
  var columns= [
    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
    {data: 'devices',  mRender: function(data, type, full, meta){
     if(data!='' && data!='NULL' && data!=undefined){
         return data['device_name'];
     }
    }},   
        {data: 'protocol',name:'protocol'},
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
    var url="/org/rpm-vital-document-list";
  var table = util.renderDataTable('vitals-document-list',url, columns, baseURL);   
 };

 var testfun =  function() {
  console.log("checkfunction call");
 };

var init = function () {
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
        url: '/org/vitalsprotocolStatus/' + id+'/'+deviceid,
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
        url: '/org/vitalsprotocolStatus/' + id+'/'+deviceid,        
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

    
    $(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

   $('#resetbutton').click(function(){
           $('#device').val(''); 
            $(".custom-file-input").siblings(".custom-file-label").html('Choose file');
            $("#file").val(''); 
            $("#device").removeClass("is-invalid");
            $("#device").next(".invalid-feedback").html('');
            $("#file").removeClass("is-invalid");
            $("#file").next(".invalid-feedback").html('');  
     });

   $('#uploadfile').click(function(){
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
                          var percentComplete = Math.round(((evt.loaded / evt.total) * 100));
                          $(".progress-bar").width(percentComplete + '%');
                          $(".progress-bar").html(percentComplete+'%');
                      }
                  }, false);
                }
                  return xhr;

                },
                url: '/org/save-file-upload',
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
                     // console.log(dataerror.errors.device+"testlength");  
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
   });
 
};

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



  

window.rpmprotocol={  
    init: init
}