@extends('Theme::layouts.master')
@section('main-content') 
@section('page-css')
<style type="text/css">
  #docsloader {
    position: absolute;
    left: 50%;
    top: 35%;
    background: transparent;
    z-index: 1000;
}
</style>
@endsection
<!-- Pre Loader Strat  -->
<div class='docsloadscreen' id="docspreloader" style="display:none;">
   <div class="loader" id="docsloader"><!-- spinner-bubble spinner-bubble-primary -->
			<img src="{{'/images/loading.gif'}}" width="150" height="150">
    </div>
</div>
<!-- Pre Loader end  -->
<div class="row mb-4" id="call-sub-steps">
    <div class="col-md-12">
        <div class="card12"> 
            <div class="row ">
                <div class="col-md-12 "> 
                    <div class="card text-left">
                        <div class="card-body" >
                            <h4 class="card-title mb-3">Practices</h4>
                            @include('Theme::layouts.flash-message')
                            @if(isset($message))
                                <div class="col-sm-12">
                                    <div class="alert  alert-danger alert-dismissible fade show" role="alert">
                                    $message
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                    </div>
                                </div>
                            @endif
                            <ul class="nav nav-tabs" id="myIconTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="practice-icon-tab" data-toggle="tab" href="#practice" role="tab" aria-controls="practice" aria-selected="true"><i class="nav-icon color-icon i-Telephone mr-1"></i>Practice</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="practice-group-icon-tab" data-toggle="tab" href="#practice-group" role="tab" aria-controls="practice-group" aria-selected="false"><i class="nav-icon color-icon i-Gears mr-1"></i>{{config('global.practice_group')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="docs-icon-tab" data-toggle="tab" href="#docs" role="tab" aria-controls="docs" aria-selected="true"><i class="nav-icon color-icon i-File-Pie mr-1"></i>Docs</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myIconTabContent">
                                <div class="tab-pane show active" id="practice" role="tabpanel" aria-labelledby="practice-icon-tab">
                                    @include('Practices::practice-list')
                                </div>
                                <div class="tab-pane" id="practice-group" role="tabpanel" aria-labelledby="practice-group-icon-tab">
                                     @include('Practices::practice-group-list')
                                </div>
                                <div class="tab-pane" id="docs" role="tabpanel" aria-labelledby="docs-group-icon-tab">
                                     @include('Practices::upload-docs')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('page-js')
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script type="text/javascript">
    $(function () {
        $(".documents").change(function () {
            if ($(this).val() == "0") {
                $(".dvPassport").show();
                $("form[name='AddDocumentForm'] #dvtype").removeClass("col-md-6").addClass("col-md-3");
            } else {
                $(".dvPassport").hide();
                $("form[name='AddDocumentForm'] #dvtype").removeClass("col-md-3").addClass("col-md-6");
            }
        });
    });
</script>
<script type="text/javascript">
var renderPracticeTable =  function() {
    var columns= [
          {data: 'DT_RowIndex', name: 'DT_RowIndex'},
          {data: 'name', name: 'name'},
          {data: 'location',name: 'location'},
          {data: 'partners.name',name: 'partners.name'},
          {data: 'number', name: 'number'},
          {data: 'address', data:'address'},
          {data: 'phone', name: 'phone'},
          {data: 'key_contact', name: 'key_contact'},
          {data: 'practice_group', name: 'practice_group',
              mRender: function(data, type, full, meta){
                  if(data!='' && data!='NULL' && data!=undefined){
                        return data['practice_name'];
                  } else { 
                      return ''; 
                  }    
              },orderable: false
          },
          {data: 'outgoing_phone_number', name: 'outgoing_phone_number'},
          {data: null,mRender: function(data, type, full, meta){
              if (data != '' && data != 'NULL' && data != undefined) {
                if (full['billable'] == '1') {
                  return "Yes";
                } else {
                  return "No";
                }
                if (full['billable'] == null) {
                  return "No";
                }
              }
            },
            orderable: true
          },
          {data: 'practice_type', name: 'practice_type'},
          {data: 'threshold', name: 'threshold'},
          {data: 'users',
              mRender: function(data, type, full, meta){
                  if(data!='' && data!='NULL' && data!=undefined){
                      l_name = data['l_name'];
                      if(data['l_name'] == null && data['f_name'] == null){
                        l_name = '';
                        return '';
                      } else {
                        return data['f_name'] + ' ' + l_name;
                      }
                  } else { 
                      return ''; 
                  }    
              },orderable: false
          }, 
          {data: 'updated_at',name:'updated_at'},
          {data: 'action', name: 'action', orderable: false, searchable: false},
    ];
    var table = util.renderDataTable('practiceList', "{{ route('org_practices_list') }}", columns, "{{ asset('') }}");   
};

var renderPracticeGrpTable =  function() {
    var columns= [
      {data: 'DT_RowIndex', name: 'DT_RowIndex'},
      {data: 'practice_name', name: 'practice_name' },  
      {data: null,mRender: function(data, type, full, meta){
              if(full['assign_message'] == '1'){
                  return 'Yes';
              }else if(full['assign_message'] == '0'){
                 return 'No';
              } else { 
                  return ''; 
              }    
          },orderable: false
      }, 
      {data: null,mRender: function(data, type, full, meta){
              if(full['quality_metrics'] == '1'){
                  return 'Yes';
              }else if(full['quality_metrics'] == '0'){
                 return 'No';
              } else { 
                  return ''; 
              }    
          },orderable: false
      }, 
      {data: 'threshold', name: 'threshold'},
      {data: 'users',
          mRender: function(data, type, full, meta){
              if(data!='' && data!='NULL' && data!=undefined){
                  l_name = data['l_name'];
                  if(data['l_name'] == null && data['f_name'] == null){
                    l_name = '';
                    return '';
                  } else {
                    return data['f_name'] + ' ' + l_name;
                  }
              } else { 
                  return ''; 
              }    
          },orderable: false
      },           
      {data: 'updated_at',name:'updated_at'},
      {data: 'action', name: 'action', orderable: false, searchable: false},
    ];
    var table = util.renderDataTable('practiceGrpList', "{{ route('org_practices_group_list') }}", columns, "{{ asset('') }}");   
};

var renderDocsTable = function() {
  var columns= [
    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
    {data: 'practices', mRender: function(data, type, full, meta){
            if(data!='' && data!='NULL' && data!=undefined){
                return data['name']; 
            }else{
                return '';
            } 
        },orderable: false 
    },
    {data: 'providers', mRender: function(data, type, full, meta){
            if(data!='' && data!='NULL' && data!=undefined){
                return data['name']; 
            }else{
                return 'None';
            } 
        },orderable: false 
    },
    {data: 'doc_type',name: 'doc_type'},
    {data: 'doc_name',name: 'doc_name'},
    {data: 'doc_comments',name: 'doc_comments'}, 
    {data: 'doc_content',name: 'doc_content'},
    {data: 'users',
        mRender: function(data, type, full, meta){
            if(data!='' && data!='NULL' && data!=undefined){
                l_name = data['l_name'];
                if(data['l_name'] == null && data['f_name'] == null){
                l_name = '';
                return '';
                } else {
                return data['f_name'] + ' ' + l_name;
                }
            } else { 
                return ''; 
            }    
        },orderable: false}, 
    {data: 'updated_at',name:'updated_at'},
    {data: 'action', name: 'action', orderable: false, searchable: false}
  ];
  var table = util.renderDataTable('docsList', "{{ route('org_docs_list') }}", columns, "{{ asset('') }}");  
};

$(document).ready(function() {
  practices.init();
  renderPracticeTable(); 
  renderPracticeGrpTable();
  renderDocsTable();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("[name='practice_id']").on("change", function () {
        util.updatePhysicianListWithoutOther(parseInt($(this).val()), $("#provider_id"))
    });
            
});

function uploadfile() {   
    var file_data = $("#logo").prop("files")[0];   
    var form_data = new FormData();    
    form_data.append("file", file_data);
    $("#uploading_img_loader").show();
    $(".save_practice").prop('disabled', true);
    $.ajax({
        url: '/org/ajax/uploadImage',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: form_data,
        enctype: 'multipart/form-data',
        success: function(data) {          
            $('#image_path').val($.trim(data));
            $("#uploading_img_loader").hide();
            $(".save_practice").prop('disabled', false);
        },
        cache: false,
        contentType: false,
        processData: false
    });
}
</script>
@endsection