@extends('Theme::layouts_2.to-do-master')       
@section('page-css')
 @section('page-title')

@endsection 
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Clinical-Insight</h4>
        </div>
    </div>
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">     
            <div class="card-body">
                <form id="daily_report_form" name="daily_report_form"  action ="">
                @csrf
                    <div class="form-row">
                      
                        <div class="col-md-3 form-group mb-3">  
                            <label for="practicegrp">{{config('global.practice_group')}}</label>
                             @selectgrppractices("practicesgrp", ["class" => "select2","id" => "practicesgrp"]) 
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label for="practicename">Practice</label>                  
                             @selectGroupedPractices("practices",["id" => "practices", "class" => "form-control show-tick select2"])   
                        </div>
                        <div class="col-md-2 form-group mb-3">
                            <label for="provider">Provider</label>
                            @selectpracticesphysician("provider",["id" => "physician","class"=>"custom-select show-tick select2"])
                        </div>                     
                        <div class="col-md-2 form-group mb-2">
                            <label for="date">From Date</label>
                            @date('date',["id" => "fromdate"])          
                        </div>
                        <div class="col-md-2 form-group mb-3">
                            <label for="date">To Date</label>
                            @date('date',["id" => "todate"])                     
                        </div>

                        <div class="col-md-1">
                            <button type="button" id="searchbutton" class="btn btn-primary mt-4">Search</button>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-primary mt-4" id="month-reset">Reset</button>
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
                    <!-- <table id="initial-list" class="display datatable table-striped table-bordered" style="width:100%"> -->
                    <!-- <thead>
                        <tr> 
                            <th width="15px">Sr No.</th>
                            <th>Month Year</th>                     
                            <th>CCM Enrolled</th>
                            <th>CCM Active Patients</th> 
                            <th>HTN</th>
                            <th>Diabetes</th>
                            <th>CHF</th>
                            <th>CKD</th> 
                            <th>Hyperlipidemia</th>
                            <th>COPD</th>
                            <th>Asthma</th>
                            <th>Depression</th>
                            <th>Anxiety</th>
                            <th>Dementia</th>
                            <th>Arthritis</th>
                            <th>Other Diagnosis</th>
                            <th>Female</th>
                            <th>Male</th>
                            <th>39 & Younger</th>
                            <th>40 to 49</th>
                            <th>50 to 59</th>
                            <th>60 to 69</th>
                            <th>70 to 79</th>
                            <th>80 to 89</th>
                            <th>90 to 99</th>
                            <th>100 & Above</th>
                            <th>Hospitalization</th>
                            <th>ED Visit</th>
                            <th>Urgent Care</th>
                            <th>Social Needs</th>
                            <th>Not Taking Medications AS Prescribed</th>
                            <th>Fell</th>
                            <th>Office Appointment</th>
                            <th>Resource Medication</th>
                            <th>Medication Renewal</th>
                            <th>Called Office Patientbehalf</th>
                            <th>Referral Support</th>
                            <th>NO Other Services</th>
                            <th colspan="20" style="text-align:center !important">Routine Response</th>
                         
                         
                             
                        </tr>
                        <tr>
                            <th width="15px"></th>
                            <th></th>                     
                            <th></th>
                            <th></th> 
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th> 
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Interaction with Office Staff</th>
                            <th>Interaction with PCP</th>
                            <th>PCP appointment scheduled</th>
                            <th>Specialist appointment scheduled</th>
                            <th>AWV appointment scheduled</th>
                            <th>Vision or Dental appointment scheduled</th>
                            <th>Prior Authorization for Labs or Diagnostics</th>
                            <th>Medical Records Request</th>

                           
   
                        </tr>
                    </thead>
                    <tbody>
                    </tbody> -->
                <!-- </table> -->
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
        var table1="";

       
        var getinitialList = function(practicesgrp=null,practices=null,provider=null,fromdate1=null,todate1=null)
        {
       
        if(practicesgrp=='')    
        {
            practicesgrp=null;
        } 
        if(practices=='')
        {
            practices=null;
        }
        if(provider=='')
        {
            provider=null;
        }
        if(fromdate1=='')
        {
            fromdate1=null;
        }
        if(todate1=='')
        { 
            todate1=null;
        }

   
            var copy_img = "assets/images/copy_icon.png";
            var excel_img = "assets/images/excel_icon.png";
            var pdf_img = "assets/images/pdf_icon.png";
            var csv_img = "assets/images/csv_icon.png";
            var assetBaseUrl = "{{ asset('') }}";
            var randomval = "{{ rand() }}";
            $('.table-responsive').html("");
            $('.table-responsive').html('<table id="initial-list-'+randomval+'" class="display table table-striped table-bordered"></table>');
                    
            $.ajax({
            type: 'GET',
            url: "/reports/clinicalreportsearch/"+practicesgrp+"/"+practices+"/"+provider+"/"+fromdate1+"/"+todate1,
            //data: data,
            success: function (datatest) {
            // console.log(datatest);  
            var dataObject = eval('[' +datatest+']');
            var columns = [];
            var tableHeaders;

                    $.each(dataObject[0].columns, function(i, val){
                        tableHeaders += "<th>" + val.title + "</th>";
                    });
                   
                    
            
            $('#initial-list-'+randomval).dataTable({
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
            // "columns": dataObject[1].COLUMNS

            });
            // $('#load-monthly-billing-tbl').hide();

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


$(document).ready(function(){

    $('#fromdate').val(firstDayWithSlashes);                         
    $('#todate').val(currentdate);
    var fromdate1=$('#fromdate').val();
    var todate1=$('#todate').val();
    //getinitialList(null,null,null,fromdate1,todate1);       
    
    $("[name='practicesgrp']").on("change", function () { 
        var practicegrp_id = $(this).val(); 
        if(practicegrp_id==0){
            // getDailyPatientList();  
        }
        if(practicegrp_id!=''){
            util.getPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#practices")); 
        }
        else{
            util.updatePracticeListWithoutOther(001, $("#practices"));    
        }   
    });

    $("[name='practices']").on("change", function () {
      util.updatePhysicianListWithoutOther(parseInt($(this).val()), $("#physician"))
    });

}); 

$('#searchbutton').click(function(){

    var practice=$('#practices').val();
    var provider=$('#physician').val();
    var practicesgrp = $('#practicesgrp').val();
    var fromdate1=$('#fromdate').val();
    var todate1=$('#todate').val();   
     getinitialList(practicesgrp,practice,provider,fromdate1,todate1);   
    
});

$("#month-reset").click(function(){       
    $('#practicesgrp').val('').trigger('change');
    $('#practices').val('').trigger('change'); 
    $('#physician').val('').trigger('change');
    $('#fromdate').val(firstDayWithSlashes);                         
    $('#todate').val(currentdate);
    var fromdate1=$('#fromdate').val();
    var todate1=$('#todate').val();
    getinitialList(null,null,null,fromdate1,todate1);       
});

</script>
@endsection