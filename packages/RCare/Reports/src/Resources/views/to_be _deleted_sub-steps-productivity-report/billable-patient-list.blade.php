@extends('Theme::layouts_2.to-do-master')
@section('page-css')
  
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Billable Patients List</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                <form id="billable_report_form" name="billable_report_form"  action ="">
                @csrf
                <div class="form-row">
                     <div class="col-md-2 form-group mb-3">
                        <label for="practicename">Practice Name</label>
                        @selectpracticeswithAll("practices", ["id" => "practices_id","class" => "select2"])
                    </div>

<!--                     <div class="col-md-2 form-group mb-3">
                        <label for="practicename">Caremanager Name</label>
                        @selectpractices("practices", ["id" => "practicesid","class" => "select2"])
                    </div -->

                    <div class="col-md-1 form-group mb-3">
                       <button type="button" id="search-billable-patient" class="btn btn-primary mt-4">Search</button>
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
                    <table id="Productivity-Billable-Patient-List" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                              <th width="35px">Sr No.</th>
                              <th width="35px">EMR</th>        
                              <th width="25px">Patient</th>
                              <th width="97px">DOB</th>
                              <th width="97px">Practice</th>   
                              <th width="20px">Provider</th>
<!--                               <th width="20px">Time Spent</th> -->
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
<div id="app"></div>


@endsection

@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
<script type="text/javascript">
// Productivity practice billable patient
var getProductivityBillablePatientList = function(practice=null,caremanager=null,fromdate=null,todate=null) {
    var columns =  [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
         
        {data: null,
             mRender: function(data, type, full, meta){ 
                practice_emr = full['practice_emr'];
                if(full['practice_emr'] == null){
                    practice_emr = '';
                }
                if(data!='' && data!='NULL' && data!=undefined){
                    return practice_emr;
                }
            },
            orderable: true
        },
        { data: null, 
            mRender: function(data, type, full, meta){
                m_Name = full['mname'];
                if(full['mname'] == null){
                    m_Name = '';
                }
                if(data!='' && data!='NULL' && data!=undefined){
                   if(full['profile_img']=='' || full['profile_img']==null) {
                        return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                    } else {
                        return ["<img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                    }
                }
            },
            orderable: true
        },
        {data: 'pdob', type: 'date-dd-mmm-yyyy', name: 'pdob', "render":function (value) {
            if (value === null) return "";
                return moment(value).format('MM-DD-YYYY');
         }
       },  

        {data:null,
            mRender: function(data, type, full, meta){
                name = full['practice'];
                if(full['practice'] == null){
                    name = '';
                }
                
                if(data!='' && data!='NULL' && data!=undefined){
                    return name;
                }
            },
            orderable: true
        },

        {data:null,
            mRender: function(data, type, full, meta){
                name = full['provider_name'];
                if(full['provider_name'] == null){
                    name = '';
                }
                if(data!='' && data!='NULL' && data!=undefined){
                    return name;
                }
            },
            orderable: true
        },
        // {data:null,
        //   mRender: function(data, type, full, meta){
        //     totaltime = full['totaltime'];
        //     if(full['totaltime'] == null){
        //       totaltime = '';
        //     }
        //     if(data!='' && data!='NULL' && data!=undefined){
        //       return totaltime;
        //     }
        //   },
        //   orderable: true
        // }
        
    ];
    // debugger;
    if(practice==''){ practice=null; }
    if(fromdate==''){ fromdate=null; }
    if(todate=='')  { todate=null; }
    if(caremanager==''){ caremanager=null; }
    var url ="/reports/productivity-billable-patients/"+practice+'/'+caremanager+'/'+fromdate+'/'+todate;
    // console.log(url);
    util.renderDataTable('Productivity-Billable-Patient-List', url, columns, "{{ asset('') }}");
}

$(document).ready(function() {
    //getProductivitycounts();
    getProductivityBillablePatientList();
    util.getToDoListData(0, {{getPageModuleName()}});
  //  $(".patient-div").hide(); // to hide patient search select
}); 


$('#search-billable-patient').click(function(){
    var practice=$('#practices_id').val();  
    var caremanager =$('#caremanager_id').val();
    var fromdate =$('#fromdate').val();
    var todate =$('#todate').val(); 
    getProductivityBillablePatientList(practice,caremanager,fromdate,todate);
});


</script>
@endsection