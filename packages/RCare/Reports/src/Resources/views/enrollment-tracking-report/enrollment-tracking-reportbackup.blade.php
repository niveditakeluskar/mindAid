@extends('Theme::layouts_2.to-do-master')
@section('page-css')
@section('page-title')
 Enrollment Tracking Report
@endsection   
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Enrollment Tracking Report</h4>
        </div>
    </div> 
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>


<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                <form id="enrollment_tracking_report_form" name="enrollment_tracking_report_form"  action ="">
                @csrf
                <div class="form-row">   
                    
                    <div class="col-md-2 form-group mb-3">
                        <label for="month">Month & Year</label>
                        @month('monthly',["id" => "monthly"]) 
                    </div>

                    <div class="col-md-1 form-group mb-3">
                       <button type="button" id="searchbutton" class="btn btn-primary mt-4">Search</button>
                    </div>

                    <div class="col-md-1 form-group mb-3">
                       <button type="button" id="resetbutton" class="btn btn-primary mt-4">Reset</button>
                    </div>

                </div>
                </form>
            </div>
        </div>
    </div>
</div>

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



var getParentEnrollmentTrackingList = function(monthly=null) {
       
       // if(practicesgrp=='')    
       // {
       //     practicesgrp=null;
       // } 
       // if(practice=='')
       // {
       //     practice=null;
       //  }
       // if(provider=='')
       //  {
       //      provider=null;
       //  }
        if(monthly=='')
        {
            monthly=null;
        }
   //      if(monthlyto=='')
   //      { 
   //          monthlyto=null;
   //     }

   //  if(activedeactivestatus==''){activedeactivestatus=null;}
   //  if(callstatus==''){callstatus=null;}
    
var copy_img = "assets/images/copy_icon.png";
var excel_img = "assets/images/excel_icon.png";
var pdf_img = "assets/images/pdf_icon.png";
var csv_img = "assets/images/csv_icon.png";
var assetBaseUrl = "{{ asset('') }}";
// var randomval = "{{ rand() }}";
var randomval = "123";
$('.table-responsive').html("");
$('.table-responsive').html('<table id="patient-list-'+randomval+'" class="display table table-striped table-bordered"></table>');
           
$.ajax({
type: 'GET',
url: "/reports/parent-enrollment-tracking-report/search/"+monthly,
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
$('#load-monthly-billing-tbl').hide();

}  
});
}



 var getEnrollmentTrackingList = function(monthly=null) {
       
                // if(practicesgrp=='')    
                // {
                //     practicesgrp=null;
                // } 
                // if(practice=='')
                // {
                //     practice=null;
                //  }
                // if(provider=='')
                //  {
                //      provider=null;
                //  }
                 if(monthly=='')
                 {
                     monthly=null;
                 }
            //      if(monthlyto=='')
            //      { 
            //          monthlyto=null;
            //     }

            //  if(activedeactivestatus==''){activedeactivestatus=null;}
            //  if(callstatus==''){callstatus=null;}
             
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
        url: "/reports/enrollment-tracking-report/search/"+monthly,
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
     $('#load-monthly-billing-tbl').hide();

        } 
        
        

       
    });

    
}


        $(document).ready(function() {
            // debugger;
            getParentEnrollmentTrackingList();
            // getEnrollmentTrackingList();
            function getMonth(date) {
            var month = date.getMonth() + 1;
            return month < 10 ? '0' + month : '' + month; // ('' + month) for string result
            }

            var c_month = (new Date().getMonth() + 1).toString().padStart(2, "0");
            var c_year = new Date().getFullYear();
            var current_MonthYear = c_year+'-'+c_month;
            alert(current_MonthYear); 
            $("#monthly").val(current_MonthYear);
          
      
            util.getToDoListData(0, {{getPageModuleName()}});

            // var randomval = "{{ rand() }}";
            
           
               
        }); 

        $("#searchbutton").click(function(){
         
            var monthly = $('#monthly').val();
            getParentEnrollmentTrackingList(monthly);
            // getEnrollmentTrackingList(monthly); 

           
    
        });

        $("#resetbutton").click(function(){
               
            $('#monthly').removeClass("is-invalid");
            $('#monthly').removeClass("invalid-feedback"); 

            function getMonth(date) {
            var month = date.getMonth() + 1;
            return month < 10 ? '0' + month : '' + month; // ('' + month) for string result
            }

            var c_month = (new Date().getMonth() + 1).toString().padStart(2, "0");
            var c_year = new Date().getFullYear();
            var current_MonthYear = c_year+'-'+c_month;
            $("#monthly").val(current_MonthYear);         
            var monthly =  $("#monthly").val();
           
            getParentEnrollmentTrackingList(monthly);
            // getEnrollmentTrackingList(monthly);  
        });

        // var tablecount=1;
       
                // $('#patient-list-123 tbody').on('click', 'td a', function () {   
                //     alert("hello");         
                //   var data_id=  $(this).attr('id');      
                //    var res = data_id.split("/");
                //    var patient_id=res[0];
                //     var fromdate=res[1];
                //      var todate=res[2];

                //   var tr  = $(this).closest('tr'),
                //     row = $('#Activities-list').DataTable().row(tr);
                //      if (row.child.isShown()) {                 
                //               destroyChild(row);
                //             tr.removeClass('shown');
                //           $(this).find('i').removeClass('i-Remove');
                //             $(this).find('i').addClass('i-Add');
                          
                //           // $(this).attr("class","http://i.imgur.com/SD7Dz.png");
                //      }
                //      else
                //      {                      
                //           tr.addClass('shown');
                //         //$(this).attr("src","https://i.imgur.com/d4ICC.png");
                //           $(this).find('i').removeClass('i-Add');
                //             $(this).find('i').addClass('i-Remove');
                          
                //          var activitieschild=' <div class="table-responsive">';
                //         activitieschild = activitieschild + '<table id="Activities-Child-list'+patient_id+'" class="display table table-striped table-bordered activitieschildtable" style="width:100%">';
                //         activitieschild = activitieschild + '<thead>';
                //         activitieschild = activitieschild + '<tr>';
                //         activitieschild = activitieschild + '<th width="35px">Sr No.</th>';  
                //         activitieschild = activitieschild + '<th width="35px">CM</th>';                         
                //         activitieschild = activitieschild + '<th width="205px">5days</th>';
                //         activitieschild = activitieschild + '<th width="205px">4days</th>';
                //         activitieschild = activitieschild + '<th width="97px">3days</th>';
                //         activitieschild = activitieschild + '<th width="97px">2days</th>'; 
                //         activitieschild = activitieschild + '<th width="97px">yesterdays</th>';                             
                //         activitieschild = activitieschild + '<th width="97px">totalAvg</th>'; 
                //         activitieschild = activitieschild +  '</tr></thead><tbody></tbody> </table></div>';
                //                row.child( activitieschild ).show();
                //                  getAdditionalActivitiesChildList(row,row.data().FindField1,patient_id,fromdate);
                //      }
                //  }); 


            //  function destroyChild(row) {
            //   var tabledestroy = $("childtable", row.child());
            //     tabledestroy.detach();
            //     tabledestroy.DataTable().destroy();
            //     // And then hide the row
            //     row.child.hide();
            // }


    
        
        </script>
@endsection         