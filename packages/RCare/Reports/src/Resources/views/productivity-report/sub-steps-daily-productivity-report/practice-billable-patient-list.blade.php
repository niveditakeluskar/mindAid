@extends('Theme::layouts_2.to-do-master')
@section('page-css')
  
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Daily Billable Practices List</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                <form id="practice_billable_form" name="practice_billable_form"  action ="">
                @csrf
                <div class="form-row">
                     <div class="col-md-2 form-group mb-3">
                        <label for="practicename">Practice Name</label>
                        @selectpracticeswithAll("practices", ["id" =>"practice_id","class" => "select2"])
                    </div>

                    <!--  <div class="col-md-2 form-group mb-3">
                        <label for="practicename">Caremanager Name</label>
                        @selectpractices("practices", ["id" => "practicesid","class" => "select2"])
                    </div -->

                    <div class="col-md-1 form-group mb-3">
                       <button type="button" id="search-practice-billable-patient" class="btn btn-primary mt-4">Search</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="Productivity-Practice-Billable-Patient-List" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                              <th width="35px">Sr No.</th>        
                              <th width="205px">Practice</th>
                              <th width="20px">Location</th>
                              <th width="20px">Practice Number</th> 
                              <th width="20px">Address</th>  
                              <th width="20px">Phone Number</th>
                              <th width="20px">Billable Patient</th>
                              <th width="20px">Minutes Spent</th>
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
<div id="app">
</div>

@endsection

@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
<script type="text/javascript">
// Productivity practice billable patient
var getProductivityPracticeBillablePatientList = function(practice=null,caremanager=null,fromdate=null,todate=null) {
    var columns =  [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'name', name: 'name'},
        {data: 'location',name: 'location'},
        {data: 'number', name: 'number'},
        {data: 'address', data:'address'},
        {data: 'phone', name: 'phone'},
        {data:null,mRender: function(data, type, full, meta){
            billable_patients = full['billable_patients'];
            if(full['billable_patients'] == null){
              billable_patients = '';
            }
            if(data!='' && data!='NULL' && data!=undefined){
              return billable_patients;
            }
          },
          orderable: true
        },
        {data:null,
          mRender: function(data, type, full, meta){
            totaltime = full['totaltime'];
            if(full['totaltime'] == null){
              totaltime = '';
            }
            if(data!='' && data!='NULL' && data!=undefined){
              return parseFloat(totaltime).toFixed(2);
            }
          },
          orderable: true
        }
        
    ];
    // debugger;
    if(practice==''){ practice=null; }
    if(fromdate==''){ fromdate=null; }
    if(todate=='')  { todate=null; }
    if(caremanager==''){ caremanager=null; }
    var url ="/reports/productivity-daily-practice-billable-patients/"+practice+'/'+caremanager+'/'+fromdate+'/'+todate;
    // console.log(url);
    util.renderDataTable('Productivity-Practice-Billable-Patient-List', url, columns, "{{ asset('') }}");
}

$(document).ready(function() {
    // getProductivitycounts();
    getProductivityPracticeBillablePatientList();
    util.getToDoListData(0, {{getPageModuleName()}});
  //  $(".patient-div").hide(); // to hide patient search select
}); 


$('#search-practice-billable-patient').click(function(){
    var practice = $('#practice_id').val();
    var caremanager =$('#caremanager_id').val();
    var fromdate =$('#fromdate').val();
    var todate =$('#todate').val(); 
    getProductivityPracticeBillablePatientList(practice,caremanager,fromdate,todate);
});

// $('#practices').change(function(){
//     var practice=$(this).val();
//     alert(practice);
// });

</script>
@endsection

