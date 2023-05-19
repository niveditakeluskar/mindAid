@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">

@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
    <div class="col-md-11">
     <h4 class="card-title mb-3">Reports Master</h4>
   </div>
   <div class="col-md-1">
     <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addReports"> Add Reports</a>  
   </div>
 </div>
 <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<div id="msg"></div>
<div id="success"></div>
<div class="row mb-4">
  <div class="col-md-12 mb-4">
    <div class="card text-left">
      <div class="card-body">
       <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
       @include('Theme::layouts.flash-message')
       <div class="table-responsive">
        <table id="ReportList" class="display table table-striped table-bordered" style="width:100%">
          <thead>
            <tr>
              <th width="45px">Sr No.</th>
              <th width="60px">Reports Name</th>      
              <th width="50px">Management Status</th>  
              <th width="50px">Last Modified By</th>
              <th width="50px">Last Modified On</th>                 
              <th width="65px">Action</th>
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

<!-- Add Reports Model -->
<div class="modal fade" id="add_reportsmaster_modal" aria-hidden="true">
   <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="modelHeading1"></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
            <form action="{{ route("create-reports")}}" method="post" name ="ReportNameForm"  id="ReportNameForm">
            <div class="modal-body">
                @csrf
                <input type="hidden" name="id" id="id">
                <div class="col-md-12 form-group mb-3"> 
                    <label for="report_name">Report Name</label> 
                    @text('display_name',['id'=>'display_name'])
                </div>
                <div class="col-md-12 form-group mb-3"> 
                    <label for="report_name">Report File Path</label> 
                    @text('report_file_path',['id'=>'report_file_path'])
                </div>
                <div class="col-md-12  form-group mb-3">              
                  <label for="env">Management Status</label>
                  <select id="management_status" name="management_status" class="custom-select show-tick" >
                    <option value="1" selected>Yes</option>
                     <option value="0">No</option>                            
                  </select> 
                  <div class="invalid-feedback"></div>
                  <!-- @if ($errors->any())
                    <div class="alert alert-danger">
                      <ul>
                        @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                      </ul>
                    </div>
                  @endif -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary float-right" id="task_submit">Submit</button>
                <button type="button" class="btn btn-default float-left" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div> 

@endsection

@section('page-js')
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script type="text/javascript">
 var renderReportsMasterTable =  function() {
  var columns= [
    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
    // {data: 'report_name', name: 'report_name'},
    {data: 'display_name', name: 'display_name'},
    {data: 'management_status', mRender: function(data, type, full, meta){
        if(data!='' && data!='NULL' && data!=undefined){
            return 'Yes';
        } else { 
            return 'No'; 
        }    
      },orderable: false
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
    ]; 
  var table = util.renderDataTable('ReportList', "{{ route('reports-list') }}", columns, "{{ asset('') }}");   
 };


$(document).ready(function() {
  reportsMaster.init();
  renderReportsMasterTable();

});    
</script>

  

@endsection

