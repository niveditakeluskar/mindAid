@extends('Theme::layouts_2.to-do-master')
@section('page-title')
  Daily Productivity Report
@endsection
@section('page-css')
@endsection
@section('main-content')
<div class="breadcrusmb">
  <div class="row">
    <div class="col-md-11">
      <h4 class="card-title mb-3">Daily Productivity Report</h4>
    </div>
  </div>
  <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<div _ngcontent-rei-c8="" class="row">

<div _ngcontent-rei-c8="" style="width:19%;margin-left: 1%; margin-right: 2%;" >
 <a href="{{ route('productivity.total.patients') }}" target="blank">
    <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
      <div _ngcontent-rei-c8="" class="card-body text-center">
        <i _ngcontent-rei-c8="" class="i-Myspace"></i>
        <div _ngcontent-rei-c8="" class="content">
           <!-- <p _ngcontent-rei-c8="" class="text-muted" data-toggle="modal"  data-target="#mypatientModal" target="allpatient" style="width: 86px;height: 30px;">Patients</p> -->
           <p _ngcontent-rei-c8="" class="text-muted" style="width: 86px;height: 30px;">Patients</p>
           <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" id="totalpatient"></p>
        </div>
      </div>
    </div>
  </a>
</div>



<div _ngcontent-rei-c8=""  style="width: 18%;margin-right: 1%;" >
  <a href="/reports/productivity-practice" target="blank">
  <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-3">
   <div _ngcontent-rei-c8="" class="card-body text-center">
    <i _ngcontent-rei-c8="" class="i-Clinic"></i>
    <div _ngcontent-rei-c8="" class="content">
     <p _ngcontent-rei-c8="" class="text-muted" style="width:60px;height:27px;">Practices</p>
     <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" id="totalpractices"></p>
   </div>
 </div>
</div>
</a>
</div> 

<div _ngcontent-rei-c8="" style="width: 18%;margin-right: 1%;" >
  <a href="/reports/productivity-caremanager" target="blank">
  <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-3">
   <div _ngcontent-rei-c8="" class="card-body text-center">
    <i _ngcontent-rei-c8="" class="i-Add-UserStar"></i>
    <div _ngcontent-rei-c8="" class="content">
     <p _ngcontent-rei-c8="" class="text-muted" style="width: 65px;height:28px;">Care Managers</p>
     <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" id="totalcaremaneger"></p>
   </div>
 </div>
</div>
</a>
</div> 


<div _ngcontent-rei-c8="" data-toggle="modal" style="cursor:pointer;width: 18%;margin-right: 1%;">
<a target="_blank" href="{{route('productivity.daily.patientsworkedon.view')}}">
  <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-3">
   <div _ngcontent-rei-c8="" class="card-body text-center">
    <i _ngcontent-rei-c8="" class="i-Conference"></i>
    <div _ngcontent-rei-c8="" class="content">
     <p _ngcontent-rei-c8="" class="text-muted" style="width: 99px;height: 30px;"> Patients Worked On</p>
     <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" id="totalpatientworkedon"></p>
   </div>
 </div>
</div>
</a> 
</div>
 
<div _ngcontent-rei-c8="" style="width: 18%;margin-right: 1%;">
  <a href="/reports/productivity-daily-billable-patients" target="_blank">
  <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-3">
   <div _ngcontent-rei-c8="" class="card-body text-center">
    <i _ngcontent-rei-c8="" class="i-Checked-User"></i>
    <div _ngcontent-rei-c8="" class="content">
     <p _ngcontent-rei-c8="" class="text-muted" style="width: 55px;height: 30px;">Billable Patients</p>
     <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" id="totalbillablepatient"></p>
   </div>
 </div> 
</div>
</a> 
</div>
<!-- 
<div _ngcontent-rei-c8="" style="width: 15%;margin-right: 1%;"> 
  <a href="/reports/productivity-daily-billable-practices" target="_blank">
  <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-3">
   <div _ngcontent-rei-c8="" class="card-body text-center">
    <i _ngcontent-rei-c8="" class="i-Find-User"></i>
    <div _ngcontent-rei-c8="" class="content">
     <p _ngcontent-rei-c8="" class="text-muted" style="width: 99px;height: 30px;">Billable Practices</p>
     <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" id="totalpracticebillablepatient"></p>
   </div>
 </div>
</div>
</a> 
</div>  -->

</div>
@include('Reports::productivity-report.productivity-daily-filter')
<div class="row mb-4">
  <div class="col-md-12 mb-4">
    <div class="card text-rightleft">
      <div class="card-body">
        <div class="table-responsive">
          <table id="patient-list" class="display datatable table-striped table-bordered" style="width:100%">
            <thead>
              <tr>
                <th width="10px">Sr No.</th>
                <th width="20px">From Date</th>
                <th width="20px">To Date</th>
                <th width="97px">Care Manager</th>
                <th width="97px">Practice</th>
                <th width="75px">Minutes Spent</th>
                <th width="40px">Billable Patients</th>
                <th width="40px">Total Patients</th>
                <!-- <th width="40px">Module</th>
                <th width="40px">Status</th> -->
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
<div id="app"></div>
@endsection

@section('page-js')
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script src="{{asset('assets/js/tooltip.script.js')}}"></script>
<script type="text/javascript">

var getDailyProductivityList = function(practicesgrp = null,practice = null,care_manager_id=null,fromdate=null,todate=null,activedeactivestatus=00123) { //modules=null
  var columns =  [
  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
  // {data: 'fdate', name: 'fdate'},
  {data: 'fdate',type: 'date-dd-mm-yyyy', name: 'fdate',"render":function (value) {
      if (value === null) return "";
          return moment(value).format('MM-DD-YYYY');
      }
  },
  //{data: 'tdate', name: 'tdate'},
  {data: 'tdate', type: 'date-dd-mmm-yyyy', name: 'tdate',"render":function (value) {
          if (value === null) return "";
          return moment(value).format('MM-DD-YYYY');
      }
  },
  {data: null, mRender: function(data, type, full, meta){                                    

    if(data!='' && data!='NULL' && data!=undefined){
     if(full['caremanager'] == null) {
      return '';
    }
    else
    {
     return  full['caremanager']; 
   }
 }

},
orderable: true
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
    totaltime = full['totaltime'];
    if(full['totaltime'] == null){
      totaltime = '';
    }
    if(data!='' && data!='NULL' && data!=undefined){
      return parseFloat(totaltime).toFixed(2);
    }
  },
  orderable: true
},
{data:null,
  mRender: function(data, type, full, meta){
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
    total_patients = full['totalpatients'];
    if(full['totalpatients'] == null){
      total_patients = '';
    }
    if(data!='' && data!='NULL' && data!=undefined){
      return total_patients;
    }
  },
  orderable: true
},
// {data: null, 
//   mRender: function(data, type, full, meta){
//     status = full['psmodule'];
//       if(full['psmodule'] == 3){
//           status = 'CCM';
//       }
//       if(full['psmodule'] == 0){
//           status = ''; 
//       }
//       if(full['psmodule'] == 2){ 
//           status = 'RPM';
//       }
//       if(full['psmodule'] == 8){ 
//           status = 'Register'; 
//       }
//       if(data!='' && data!='NULL' && data!=undefined){
//         return status;
//       }
//     },
//     orderable: true, searchable: false
// },

// {data: null, 
//   mRender: function(data, type, full, meta){
//     status = full['pstatus'];
//       if(full['pstatus'] == 1){
//           status = 'Active';
//       }
//       if(full['pstatus'] == 0){
//           status = 'Suspended'; 
//       }
//       if(full['pstatus'] == 2){ 
//           status = 'Deactived';
//       }
//       if(full['pstatus'] == 3){ 
//           status = 'Deceased'; 
//       }
//       if(data!='' && data!='NULL' && data!=undefined){
//         return status;
//       }
//     },
//     orderable: true, searchable: false
// },

{data: 'action', name: 'action', orderable: false, searchable: false}

];
// debugger;
if(practicesgrp==''){practicesgrp=null;} 
if(practice==''){practice=null;} 
if(fromdate==''){ fromdate=null; }
if(todate=='')  { todate=null; }
if(care_manager_id==''){ care_manager_id=null; }
// if(modules==''){modules==null;}
if(activedeactivestatus==''){activedeactivestatus=00123;}
var url = "/reports/productivity-daily-report/search/"+practicesgrp+'/'+practice+'/'+care_manager_id+'/'+fromdate+'/'+todate+'/'+activedeactivestatus;
console.log(url);
table1 = util.renderDataTable('patient-list', url, columns, "{{ asset('') }}");

}





$(document).ready(function() {
getDailyProductivitycounts();
// getDailyProductivityList();
function convert(str) {
  var date = new Date(str),
    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
    day = ("0" + date.getDate()).slice(-2);
  return [date.getFullYear(), mnth, day].join("-");
}

var date = new Date(),
 y = date.getFullYear(), m = date.getMonth(), d=date.getDate();

var firstDay = new Date(y, m, d);
var lastDay = new Date(y, m, d);


$("#fromdate").attr("value", (convert(firstDay)));
$("#todate").attr("value", (convert(firstDay)));
var fromdate = $("#fromdate").val();
var todate = $("#todate").val();    
getDailyProductivityList(null,null,null,fromdate,todate,null);

$("[name='practicesgrp']").on("change", function () { 
                var practicegrp_id = $(this).val(); 
                /*if(practicegrp_id==0){
                  getDailyProductivityList();  
                }*/
                if(practicegrp_id!=''){
                    util.getPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#prac_id")); 
                }
                /*else{
                  getDailyProductivityList();    
                }  */ 
            });

$("#care_id").on("change", function () {
     $('#practicesgrp').val('').trigger('change');
     util.updatePracticeListWithoutOther(parseInt($(this).val()), $("#prac_id")); 
});



// $("[name='modules']").val(0).attr("selected", "selected").change();           
util.getToDoListData(0, {{getPageModuleName()}});
util.getAssignPatientListData(0, 0);
//  $(".patient-div").hide(); // to hide patient search select

$('#daily-prod-search').click(function(){
  var fromdate=$('#fromdate').val();
  var todate = $("#todate").val();
  if(Date.parse(fromdate) > Date.parse(todate)){
      alert("Invalid Date Range");
  }else{
    var practice_id = $('#prac_id').val();
    if (practice_id=='') {
      var practice='null';
    }else{
      var practice= $('#prac_id').val()
    }
    var care_manager_id=$('#care_id').val();
    var fromdate=$('#fromdate').val(); 
    var todate = $("#todate").val();
    // var module_id =$('#modules').val();
    // if(module_id==''){
    //   var modules ='null';
    // }else{
    //   var modules=$('#modules').val();
    // }
    var practicesgrp = $('#practicesgrp').val();
    if (practicesgrp=='') {
      var practicesgrp='null';
    }else{
      var practicesgrp= $('#practicesgrp').val()
    } 
    var activedeactivestatus= $('#activedeactivestatus').val();
    // alert(activedeactivestatus);
    if(activedeactivestatus==''){
      var activedeactivestatus ='0123';
    }else{
      var activedeactivestatus = $('#activedeactivestatus').val();
    }
    getDailyProductivityList(practicesgrp,practice,care_manager_id,fromdate,todate,activedeactivestatus); 
    
  }

  
});

$('#resetbutton').click(function(){
  function convert(str) {
  var date = new Date(str), 
    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
    day = ("0" + date.getDate()).slice(-2);
    return [date.getFullYear(), mnth, day].join("-"); 
  }

 var date = new Date(),
 y = date.getFullYear(), m = date.getMonth(), d=date.getDate();

var firstDay = new Date(y, m, d);
var lastDay = new Date(y, m, d); 

  $("#fromdate").val(convert(firstDay));
  $("#todate").val(convert(lastDay));
  $('#care_id').val('').trigger('change');
  $('#prac_id').val('').trigger('change');
  // $('#modules').val(3).trigger('change');
  $('#practicesgrp').val('').trigger('change');
  $('#activedeactivestatus').val('').trigger('change');  
}); 

}); 


var getDailyProductivitycounts=function()
{
  $.ajaxSetup({ 
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    } 
  });
  $.ajax({
    type: 'POST',
    //patientproductivity-summary 
    url: '/reports/patientproductivity-daily-summary',
            //data: data, 
            success: function (data) { 
             var Totalpatient=data.Totalpatient[0].count;
             var TotalCareManager=data.TotalCareManager[0].count;
             var TotalPatientWorkedon=data.TotalPatientWorkedon[0].count;
             var TotalBillablepatient=data.TotalBillablepatient[0].count;
             var TotalPractices=data.Totalpractices[0].count; 
             // var TotalPracticesBillablePatient=data.TotalPracticesBillablePatient[0]['productivity_billable_practice'];

             $('#totalpatient').html(Totalpatient);
             $('#totalcaremaneger').html(TotalCareManager);
             $('#totalpatientworkedon').html(TotalPatientWorkedon); 
             $('#totalbillablepatient').html(TotalBillablepatient);
             $('#totalpractices').html(TotalPractices); 
             // $('#totalpracticebillablepatient').html(TotalPracticesBillablePatient);
              //console.log("save cuccess"+data.TotalEnreolledPatient[0]['count']);
             }
           });
}

</script>
@endsection