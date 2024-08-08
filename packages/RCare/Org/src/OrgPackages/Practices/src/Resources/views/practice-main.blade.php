@extends('Theme::layouts_2.master')
@section('main-content')
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
                                
                            </ul>
                            <div class="tab-content" id="myIconTabContent">
                                <div class="tab-pane show active" id="practice" role="tabpanel" aria-labelledby="practice-icon-tab">
                                    @include('Practices::practice-list')
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
var renderPracticeTable =  function() {
    var columns= [
          {data: 'DT_RowIndex', name: 'DT_RowIndex'},
          {data: 'name', name: 'name'},
          {data: 'location',name: 'location'},
          {data: 'number', name: 'number'},
          {data: 'address', data:'address'},
          {data: 'phone', name: 'phone'},
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


$(document).ready(function() {
  practices.init();
  renderPracticeTable(); 
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $("[name='practice_id']").on("change", function () {
        util.updatePhysicianListWithoutOther(parseInt($(this).val()), $("#providers"))
    });
            
});

</script>
@endsection
