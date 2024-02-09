@extends('Theme::layouts_2.to-do-master')
@section('page-css')
@section('page-title')
Consolidate Billing Report
@endsection   
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Consolidate Billing Report</h4>
        </div>
    </div> 
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

<!--  
<div _ngcontent-rei-c8="" class="row">
   <div _ngcontent-rei-c8="" style="width: 17%;margin-left: 1%; margin-right: 1%;" >
      <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Myspace"></i>
            <div _ngcontent-rei-c8="" class="content">
               <p _ngcontent-rei-c8="" class="text-muted" style="width: 86px;height: 30px;">Total Patients</p>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" >
                 <a href="/reports/total-patients-details" id="totalpatient" target="_blank"></a> 
               </p>
            </div>
         </div>
      </div>
   </div>
   -->
   <!--
   <div _ngcontent-rei-c8=""  style="width: 17%;margin-right: 1%;" >
      <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Add-UserStar"></i>
            <div _ngcontent-rei-c8="" class="content">
               <p _ngcontent-rei-c8="" class="text-muted" style="width: 130px;height: 30px;margin-left: -20px;">CCM <br>Billable /Enrolled </p>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" > <i type="button" class="editform click_id" data-toggle="modal" data-target="#DetailsModal" target="Enrolled" id="totalenrolledpatient"></i> 
                    <a href="/reports/billablepatients-details" id="billablepatient" target="_blank"></a>
               </p>
            </div>
         </div>
      </div>
   </div>
   -->
   <!--
   <div _ngcontent-rei-c8="" style="width: 17%;margin-right: 1%;">
      <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Checked-User"></i>
            <div _ngcontent-rei-c8="" class="content">
               <p _ngcontent-rei-c8="" class="text-muted" style="width: 172px;height: 30px;margin-left: -50px;">CCM <br>Non Billable /Enrolled</p>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2"><i type="button" class="editform click_id" data-toggle="modal" data-target="#DetailsModal" target="EnrolledInCCM" id="totalenrolledpatientCCM"></i> 
                  <a href="/reports/nonbillablepatients-details" id="nonbillablepatient" target="_blank"></a>
               </p>
            </div>
         </div> 
      </div>
   </div>
    -->
<!--
   <div _ngcontent-rei-c8="" style="width: 17%;margin-right: 1%;">
      <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Checked-User"></i>
            <div _ngcontent-rei-c8="" class="content">
               <p _ngcontent-rei-c8="" class="text-muted" style="width: 172px;height: 30px;margin-left: -50px;">RPM <br>Billable/Enrolled</p>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2"><i type="button" class="editform click_id" data-toggle="modal" data-target="#DetailsModal" target="EnrolledInCCM" id="totalenrolledpatientCCM"></i> 
                  <a href="/reports/billablepatientsrpm-details" id="billablepatientrpm" target="_blank"></a>
               </p>
            </div>
         </div> 
      </div>
   </div>
   -->
   <!--
   <div _ngcontent-rei-c8="" style="width: 17%;margin-right: 1%;">
      <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Checked-User"></i>
            <div _ngcontent-rei-c8="" class="content">
               <p _ngcontent-rei-c8="" class="text-muted" style="width: 172px;height: 30px;margin-left: -50px;">RPM <br>NonBillable/Enrolled</p>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2"><i type="button" class="editform click_id" data-toggle="modal" data-target="#DetailsModal" target="EnrolledInCCM" id="totalenrolledpatientCCM"></i> 
                  <a href="/reports/nonbillablepatientsrpm-details" id="nonbillablepatientrpm" target="_blank"></a>
               </p>
            </div>
         </div> 
      </div>
   </div>
</div>-->


@include('Reports::monthly-biling-report.consolidated-billing-filter')
 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-rightleft">
            <div class="card-body">
                <div style="display: block;margin-left:50%" id="load-monthly-billing-tbl">
                    <div>Loading</div>
                     <span class="loader-bubble loader-bubble-info m-2"></span>
                 </div>
                <div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>data saved successfully! </strong><span id="text"></span> 
               </div>
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                   <!--  <table id="patient-list" class="display table table-striped table-bordered">
                    <thead>
                        <tr>
                        <th width="35px">Sr No.</th>
                        <th width="100px">Provider</th>
                        <th width="10px">EMR</th>
                        <th width="225px">Patient First Name</th>
                        <th width="225px">Patient Last Name</th>
                        <th width="97px">DOB</th>
                        <th width="97px">DOS</th>
                            th width="220px">Conditions</th>                           
                            <th width="150px" >Practice</th>
                            <th width="100px">Provider</th>
                            <th width="75px">Minutes Spent</th
                            <th width="100px">CPT Code</th>
                            <th width="50px">Units</th>
                            <th width="100px">Diagnosis</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody> 
                </table>-->
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

var getMonthlyBillingPatientList = function(practicesgrp=null,practice = null,provider=null,modules=null,monthly=null,monthlyto=null,activedeactivestatus=null,callstatus=null,onlycode) {
       
       if(practicesgrp=='')    
       {
           practicesgrp=null;
       } 
       if(practice=='')
       {
           practice=null;
        }
       if(provider=='')
        {
            provider=null;
        }
        if(monthly=='')
        {
            monthly=null;
        }
        if(monthlyto=='')
        { 
            monthlyto=null;
       }

    if(activedeactivestatus==''){activedeactivestatus=null;}
    if(callstatus==''){callstatus=null;}
    
var copy_img = "assets/images/copy_icon.png";
var excel_img = "assets/images/excel_icon.png";
var pdf_img = "assets/images/pdf_icon.png";
var csv_img = "assets/images/csv_icon.png";
var assetBaseUrl = "{{ asset('') }}";
var randomval = "{{ rand() }}";
$('.table-responsive').html("");
$('.table-responsive').html('<table id="patient-list-'+randomval+'" class="display table table-striped table-bordered"></table>');
           
$.ajax({
type: 'GET',
url: "/reports/consolidate-monthly-billing-report/search/"+practicesgrp+'/'+practice+"/"+provider+"/"+modules+"/"+monthly+"/"+monthlyto+"/"+activedeactivestatus+"/"+callstatus+"/"+onlycode,
//data: data,
success: function (datatest) {

 var dataObject = eval('[' +datatest+']');
        var columns = [];
 var tableHeaders;
           $.each(dataObject[0].columns, function(i, val){
               tableHeaders += "<th>" + val.title + "</th>";
           });
 
$('#patient-list-'+randomval).dataTable({
"dom": '<"float-right"B><"float-right"f><"float-left"r><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
buttons: [
   {
       extend: 'copyHtml5',
       text: '<img src="' + assetBaseUrl + copy_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Copy">',
   },
   {
       extend: 'excelHtml5',
       text: '<img src="' + assetBaseUrl + excel_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Excel">',
       titleAttr: 'Excel'
   },
   {
       extend: 'csvHtml5',
       text: '<img src="' + assetBaseUrl + csv_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="CSV">',
       titleAttr: 'CSV',
       fieldSeparator: '\|',
   },
   {
       extend: 'pdfHtml5',
       text: '<img src="' + assetBaseUrl + pdf_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="PDF">',
       titleAttr: 'PDF' 
   }
],
"processing": true,
"Language": { 
   'loadingRecords': '&nbsp;',
   'processing': 'Consolidated Billing Report is a processing-intensive Report and might take some time to load. Please wait...',
   search: "_INPUT_",
   // "search":'<a class="btn searchBtn" id="searchBtn"><i class="i-Search-on-Cloud"></i></a>',
   "searchPlaceholder": "Search records",
   "EmptyTable": "No Data Found",
},
"destroy": true,
"data": dataObject[0].DATA,
"columns": dataObject[0].COLUMNS,


});
$('#load-monthly-billing-tbl').hide();

}
});
}
        function formatDate() {
        var d = new Date(),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) 
            month = '0' + month;
        if (day.length < 2) 
            day = '0' + day;

        return [year,month, day].join('-');
        }

           var currentdate = formatDate();   
           var date = new Date(); 
           var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);         
           var getmnth=("0" +(date.getMonth() + 1)).slice(-2);
           var firstDayWithSlashes = date.getFullYear()+ '-' + getmnth + '-' +('0' +(firstDay.getDate())).slice(-2);
           

        $(document).ready(function() {  
            var practice = $('#practices').val();

            $("#monthly").val(firstDayWithSlashes);
            $("#monthlyto").val(currentdate);

            var monthly = $('#monthly').val();
            var monthlyto = $('#monthlyto').val();

            if(monthlyto < monthly)
            {
                $('#monthlyto').addClass("is-invalid");
                $('#monthlyto').next(".invalid-feedback").html("Please select to-month properly .");
                $('#monthly').addClass("is-invalid");
                $('#monthly').next(".invalid-feedback").html("Please select from-month properly .");   
            } 
            
            else{ 
                $('#load-monthly-billing-tbl').show();
                $('#monthlyto').removeClass("is-invalid");
                $('#monthlyto').removeClass("invalid-feedback");
                $('#monthly').removeClass("is-invalid");
                $('#monthly').removeClass("invalid-feedback");
                getMonthlyBillingPatientList(null,null,null,null,monthly,monthlyto,null,null); 
            }
            
            util.getToDoListData(0, {{getPageModuleName()}});
            
            $("[name='practicesgrp']").on("change", function () { 
                var practicegrp_id = $(this).val(); 
                if(practicegrp_id==0){
                   // getMonthlyBillingPatientList();  
                }
                if(practicegrp_id!=''){
                    util.getPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#practices")); 
                }
                else{
                   // getMonthlyBillingPatientList(); 
                    util.updatePracticeListWithoutOther(001, $("#practices"));   
                }   
            });

            $("[name='practices']").on("change", function () {
                 util.updatePhysicianListWithoutOther(parseInt($(this).val()), $("#physician"))
            });

            $("[name='physician']").on("change", function () {
                 // util.updatePhysicianList(parseInt($(this).val()), $("#physician"))
            });

            $("[name='modules']").val('').attr("selected", "selected").change();
            
            $("[name='modules']").on("change", function () {
                // util.updatePatientList(parseInt($(this).val()), {{ getPageModuleName() }}, $("#patient"));
            });

            $("[name='monthly']").on("change", function (){    
            });

            $("[name='monthlyto']").on("change", function (){    
            });
            var modules = $('#modules').val();

            var activedeactivestatus= $("#activedeactivestatus").val();

            var callstatus = $("#callstatus").val();
             
        }); 

        $("#month-search").click(function(){
            //modified by -ashvini 26th oct 2020
            var practicesgrp =$('#practicesgrp').val(); 
            var practice = $('#practices').val();
            var provider = $('#physician').val();
            var modules = $('#modules').val();
            var monthly = $('#monthly').val();
            var monthlyto = $('#monthlyto').val();
            var activedeactivestatus= $("#activedeactivestatus").val();
            var callstatus = $("#callstatus").val();
            var only_code = 0;
            if($("#only_code").is(':checked')){
                only_code = 1;
            }
    
            if(monthlyto < monthly)
            {
                $('#monthlyto').addClass("is-invalid");
                $('#monthlyto').next(".invalid-feedback").html("Please select to-month properly .");
                $('#monthly').addClass("is-invalid");
                $('#monthly').next(".invalid-feedback").html("Please select from-month properly .");   
            } 
            
            else{ 
                $('#load-monthly-billing-tbl').show();
                $('#monthlyto').removeClass("is-invalid");
                $('#monthlyto').removeClass("invalid-feedback");
                $('#monthly').removeClass("is-invalid");
                $('#monthly').removeClass("invalid-feedback");
                getMonthlyBillingPatientList(practicesgrp,practice,provider,modules,monthly,monthlyto,activedeactivestatus,callstatus,only_code); 
            }
    
        });

        $("#month-reset").click(function(){
                $('#monthlyto').removeClass("is-invalid");
                $('#monthlyto').removeClass("invalid-feedback");
                $('#monthly').removeClass("is-invalid");
                $('#monthly').removeClass("invalid-feedback"); 
            // function getMonth(date) {
            // var month = date.getMonth() + 1;
            // return month < 10 ? '0' + month : '' + month; // ('' + month) for string result
            // }
            // var c_month = (new Date().getMonth() + 1).toString().padStart(2, "0");
            // var c_year = new Date().getFullYear();
            // var current_MonthYear = c_year+'-'+c_month;
            $("#monthly").val(firstDayWithSlashes);
            $("#monthlyto").val(currentdate);
                var practice = null
                var modules = null;
                var provider = null;
                var monthly =  $("#monthly").val();
                var monthlyto = $("#monthlyto").val(); 
                var only_code
               
        $('#practicesgrp').val('').trigger('change');
        $('#practices').val('').trigger('change'); 
        $('#physician').val('').trigger('change'); 
        $('#modules').val('').trigger('change'); 
        $('#activedeactivestatus').val('').trigger('change');
        $('#callstatus').val('').trigger('change');
        getMonthlyBillingPatientList(practicesgrp,practice,provider,modules,monthly,monthlyto,activedeactivestatus,callstatus,only_code); 
        });

        
        </script>
@endsection         