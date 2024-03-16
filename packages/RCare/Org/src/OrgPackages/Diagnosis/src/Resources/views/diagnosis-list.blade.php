@extends('Theme::layouts.master')
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
    <style>
       
        table {
            /* border-spacing: 0px; */
            table-layout: fixed;
            /* margin-left: auto; */
            /* margin-right: auto; */
        }
        
        td {
            word-wrap: break-word;
        }
        .color-red{
            background-color: red;
            color: white;
        }
        .color-green{
            background-color: green;
            color: white;
        }
    </style>
@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
    <div class="col-md-11">
       <h4 class="card-title mb-3">Diagnosis Codes</h4>
    </div>
     <div class="col-md-1">
     <a class="" href="javascript:void(0)" id="addDiagnosis"><i class="add-icons i-Administrator" data-toggle="tooltip" data-placement="top" title="Add Diagnosis"></i><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Diagnosis"></i></a>  
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
                <div id="msg"></div>
           <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="diagnosis-list" class="display datatable table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="30px">Sr No.</th>
                            <th>Code</th>
                            <th>Condition</th>
                            <th>Qualified for billing</th>
                            <th>Last Modifed By</th>
                            <th>Last Modifed On</th> 
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

<!-- Add medication -->
<div class="modal fade" id="add_diagnosis_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> 
                <h4 class="modal-title" id="modelHeading1">Add Diagnosis</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
    
      <div class="modal-body">
      <form action="{{ route("ajax.save.diagnosis")}}" method="post" name ="main_diagnosis_form"  id="main_diagnosis_form">
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

       
    function valid_numbers(e){
        alert("hii");
    }


        var renderDiagnnosisTable =  function() {
            var columns = [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'code',name: 'code'},
                {data: 'condition', name: 'condition'},
                {data: null,mRender: function(data, type, full, meta){
                    if(full['qualified'] == '1'){
                        return 'Yes';
                    }else if(full['qualified'] == '0'){
                        return 'No';
                    } else { 
                        return ''; 
                    }    
                    },orderable: false
                }, 
                {data: 'f_name', name: 'f_name',render: 
                    function(data, type, full, meta){
                        if(data!='' && data!='NULL' && data!=undefined){
                            return data + ' ' + full.l_name;
                        } else { 
                            return '';
                        }
                    }
                },
                // {data:'users',
                //     mRender: function(data, type, full, meta){
                //         if(data!='' && data!='NULL' && data!=undefined){
                //             l_name = data['l_name'];
                //             if(data['l_name'] == null && data['f_name'] == null){
                //                 l_name = '';
                //                 return '';
                //             } else { 
                //                 return data['f_name'] + ' ' + l_name;
                //             }
                //         } else { 
                //             return ''; 
                //         }    
                //     },orderable: false
                // },            
                {data: 'updated_at',type: 'date-dd-mm-yyyy h:i:s', name: 'updated_at',"render":function (value) {
                   if (value === null) return "";
                        return util.viewsDateFormatWithTime(value);
                    }
                },
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ];
            var table = util.renderDataTable('diagnosis-list', "{{ route('diagnosis_code_list') }}", columns, "{{ asset('') }}"); 
        } 
        $(document).ready(function() {
            diagnosisCode.init();
            renderDiagnnosisTable();
            util.getToDoListData(0, {{getPageModuleName()}});
        });
    </script>
@endsection