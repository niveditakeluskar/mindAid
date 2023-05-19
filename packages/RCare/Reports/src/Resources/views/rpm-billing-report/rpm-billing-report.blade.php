@extends('Theme::layouts_2.to-do-master')
@section('page-css')
@section('page-title')

@endsection
@endsection 
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">RPM Billing Report</h4>
        </div>
    </div>
    <!-- ss -->             
</div> 
<div class="separator-breadcrumb border-top"></div>

<div class="row">
@include('Reports::rpm-billing-report.filter')
</div>
 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
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
    var getRpmBillingReport = function(patient=null,practice=null,fromdate=null,todate=null,moduleid=null,provider=null,practicesgrp=null,activedeactivestatus=null,callstatus=null) {                
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
        url: "/reports/rpm-billing-report/"+patient+'/'+practice+'/'+fromdate+'/'+todate+'/'+moduleid+'/'+provider+'/'+practicesgrp+'/'+activedeactivestatus+'/'+callstatus,
        //url: "/reports/rpm-billing-report/"+patient+'/'+practice+'/'+fromdate+'/'+moduleid,
        success: function (datatest) {
        //  console.log(datatest);
          var dataObject = eval('[' +datatest+']');
                 var columns = [];
                //  console.log(dataObject[0].DATA[0]);
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
                extend: 'csvHtml5',
                text: '<img src="' + assetBaseUrl + excel_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Excel">',
                titleAttr: 'Excel',
                messageTop:'RenovaHealthcare.xlxs'
                
            },
            {
                extend: 'csvHtml5',
                text: '<img src="' + assetBaseUrl + csv_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="CSV">',
                titleAttr: 'CSV'
                // fieldSeparator: '\|',
            },
            {
                extend: 'pdfHtml5',
                text: '<img src="' + assetBaseUrl + pdf_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="PDF">',
                orientation : 'landscape',
                pageSize : 'A0',
                titleAttr: 'PDF'    
            }
        ],
        "processing": true,
        "Language": { 
            'loadingRecords': '&nbsp;',
            'processing': 'Loading...',
            search: "_INPUT_",
            // "search":'<a class="btn searchBtn" id="searchBtn"><i class="i-Search-on-Cloud"></i></a>',
            "searchPlaceholder": "Search records",
            "EmptyTable": "No Data Found",
        },
         "destroy": true,
        "data": dataObject[0].DATA,
        "columns": dataObject[0].COLUMNS,
       
        
     });
     // $('#load-monthly-billing-tbl').hide();

        }
    });
}

        $(document).ready(function() {
           $("[name='practicesgrp']").on("change", function () { 
                var practicegrp_id = $(this).val(); 
                if(practicegrp_id==0){
                   // getMonthlyBillingPatientList();  
                }
                if(practicegrp_id!=''){
                    util.getPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#practice")); 
                }
                else{
                   // getMonthlyBillingPatientList(); 
                    util.updatePracticeListWithoutOther(001, $("#practice"));   
                }   
            });

            $("[name='practice']").on("change", function () {
              var module_id = $('#modules').val(); 
              if($(this).val()==''){
                  var practiceId = null;
                  util.updatePatientList(parseInt(practiceId),parseInt(module_id), $("#patient"));
              }
              else 
              {   util.updatePhysicianListWithoutOther(parseInt($(this).val()), $("#physician"))
                  util.updatePatientList(parseInt($(this).val()),parseInt(module_id), $("#patient"));
              }  
            });

            $("[name='provider']").on("change", function () {
              if($(this).val()!=''){
                  util.getRpmProviderPatientList(parseInt($(this).val()), $("#patient"));
              }
            });

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
           // alert(currentdate);   
            var date = new Date(); 
            var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);         
            var getmnth=("0" +(date.getMonth() + 1)).slice(-2);
            var firstDayWithSlashes = date.getFullYear()+ '-' + getmnth + '-' +('0' +(firstDay.getDate())).slice(-2);

            // function getMonth(date) {
            // var month = date.getMonth() + 1;
            // return month < 10 ? '0' + month : '' + month; // ('' + month) for string result
            // }
            // var c_month = (new Date().getMonth() + 1).toString().padStart(2, "0");
            // var c_year = new Date().getFullYear();
            // var current_MonthYear = c_year+'-'+c_month;
            var fromdate = $("#monthly").val(firstDayWithSlashes);
            var todate = $("#monthlyto").val(currentdate);
            //alert(todate);
            var patient = $('#patient').val();
            var practice = $('#practice').val();

           //getRpmBillingReport(patient,practice,fromdate);  
        }); 

        $('#rpmbillingresetbutton').click(function(){ 
            // function getMonth(date) {
            // var month = date.getMonth() + 1;
            // return month < 10 ? '0' + month : '' + month; // ('' + month) for string result
            // }

            // var c_month = (new Date().getMonth() + 1).toString().padStart(2, "0");
            // var c_year = new Date().getFullYear();
            // var current_MonthYear = c_year+'-'+c_month;
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

            var current_MonthYear = firstDayWithSlashes;
            var current_MonthYear1 = currentdate;

            var practicesgrp =$('#practicesgrp').val('').trigger('change');
            var fromdate = $("#monthly").val(current_MonthYear);
            var todate = $("#monthlyto").val(current_MonthYear1)
            var patient  = $('#patient').val('').trigger('change').html('Select Patient'); 
            var practice = $('#practice').val('').trigger('change'); 
            var provider = $('#physician').val('').trigger('change');
            var callstatus = $("#callstatus").val('').trigger('change');
            var activedeactivestatus =  $('#activedeactivestatus').val('').trigger('change'); 

    var practicesgrp =$('#practicesgrp').val(); 
    var practice = $('#practice').val();
    var provider = $('#physician').val();
    var moduleid = $('#modules').val();
    var patient =$('#patient').val();
    var fromdate = $('#monthly').val();
    var todate = $("#monthlyto").val();
    var activedeactivestatus= $("#activedeactivestatus").val();
    var callstatus = $("#callstatus").val();
    if(patient == ''){ 
        patient = null;
    }
    if (practice==''){
        practice = null;
    }

    if(provider == ''){
        provider = null;
    }
    if (practice==''){
        practice = null;
    }
    
    if(practicesgrp == ''){
        practicesgrp = null;
    }
    if (activedeactivestatus==''){
        activedeactivestatus = null;
    }
    if (callstatus==''){
        callstatus = null;
    }
    getRpmBillingReport(patient,practice,fromdate,todate,moduleid,provider,practicesgrp,activedeactivestatus,callstatus);          
        });


   
$('#rpmbillingsearchbutton').click(function(){ 
    var practicesgrp =$('#practicesgrp').val(); 
    var practice = $('#practice').val();
    var provider = $('#physician').val();
    var moduleid = $('#modules').val();
    var patient =$('#patient').val();
    var fromdate = $('#monthly').val();
    var todate = $("#monthlyto").val();
    var activedeactivestatus= $("#activedeactivestatus").val();
    var callstatus = $("#callstatus").val();
    if(patient == ''){ 
        patient = null;
    }
    if (practice==''){
        practice = null;
    }

    if(provider == ''){
        provider = null;
    }
    if (practice==''){
        practice = null;
    }
    
    if(practicesgrp == ''){
        practicesgrp = null;
    }
    if (activedeactivestatus==''){
        activedeactivestatus = null;
    }
    if (callstatus==''){
        callstatus = null;
    }
    getRpmBillingReport(patient,practice,fromdate,todate,moduleid,provider,practicesgrp,activedeactivestatus,callstatus);          
});


 
    </script>
@endsection