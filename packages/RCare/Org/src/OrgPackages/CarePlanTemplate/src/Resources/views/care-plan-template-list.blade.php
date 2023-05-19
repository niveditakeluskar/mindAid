@extends('Theme::layouts.master')
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
    <div class="col-md-10">
       <h4 class="card-title mb-3">Care Plan Templates</h4>
    </div>
    <div class="col-md-1">
        <a href="javascript:void(0)" id="addCarePlanDiagnosis" class="btn btn-success btn-sm "><b>Add Diagnosis</b></a>
      
    </div>
     <div class="col-md-1">
     <a class="" href="{{route("care_plan_template_add")}}" id="addCarePLanTemplate"><i class="add-icons i-Administrator" data-toggle="tooltip" data-placement="top" title="Add Care Plan Template"></i><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Care Plan Template"></i></a>  
    </div>
</div>
   <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<div id="success"></div>

 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
              <div id="msgsccess"></div>
                <div class="alert alert-success" id="success-alert" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong> Care Plan Template Updated successfully! </strong><span id="text"></span>
                </div>
           <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="careplan-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="30px">Sr No.</th>
                            <th width="50px">Code</th>
                            <th width="80px">Condition</th>
                            <th width="50px">Last Modified By</th>
                            <th width="50px">Last Modified On</th>
                            <th width="40px">Action</th>
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



<div class="modal fade" id="edit_diagnosis_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1">Edit Diagnosis</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
    
      <div class="modal-body">
      <form action="{{ route("ajax.save.diagnosis.careplan")}}" method="post" name ="edit_diagnosis_careplan_form"  id="edit_diagnosis_careplan_form">
                                @csrf
                                <input type="hidden" name="id" id="id" value="">
                                <input type="hidden" name="codenm" id="codenm">
                                 <input type="hidden" name="conditionname" id="conditionname">
                                   <input type="hidden" name="diagnosis_id" id="diagnosis_id">
                                @include('Diagnosis::diagnosis')
                            </form>
            </div>
</div>
</div>
</div> 


<!-- Add medication -->
<div class="modal fade" id="add_diagnosis_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1">Add Diagnosis</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
    
      <div class="modal-body">
      <form action="{{ route("ajax.save.diagnosis")}}" method="post" name ="main_careplan_diagnosis_form"  id="main_careplan_diagnosis_form">
                                @csrf
                                <input type="hidden" name="id" id="id">
                                @include('Diagnosis::diagnosisnew')
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
     var renderCarePlanTable =  function() {
        var columns = [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'code',name: 'code'},
                        {data: 'condition', name: 'condition',render: function(data, type, full, meta){
                        if(data!='' && data!='NULL' && data!=undefined){
                            return full['condition'] + '    <a href="javascript:void(0)" data-toggle="tooltip" data-id="'+full['id']+'" data-original-title="Edit" class="editdiagnosis" title="Edit"><i class=" editform i-Pen-4"></i></a> ';
                        }else{ return '';}
                        }
                    },
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
                            },orderable: false
                            }, 
                        {data: 'updated_at',name:'updated_at'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
            var table = util.renderDataTable('careplan-list', "{{ route('care_plan_template_list') }}", columns, "{{ asset('') }}"); 
    }; 
        $(document).ready(function() {
            renderCarePlanTable();
            carePlanTemplate.init();
             diagnosisCode.init();
            util.getToDoListData(0, {{getPageModuleName()}});
        });




       var codevailable= function() {
        // alert("test");
         if(confirm("Are you sure you want to change the code?")){       
           var data={
             condition: $('#condition').val(),
             code: $('#code').val()
            }
       
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
     $.ajax({
     type:'POST',
     url:'/org/code-availabel',
     data:data,   
     success:function (data) {      
     
       
     }
   });

     }
    else{
        return false;
    }
       };
    </script>
@endsection