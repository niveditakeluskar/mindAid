@extends('Theme::layouts_2.to-do-master')
@section('page-css')
@section('page-title')
Care ManagerCare Manager Logged Minute Report
@endsection    
@endsection
@section('main-content')
<style type="text/css">
    table.dataTable tr.group-end td {
    text-align: right;
    font-weight: normal;
    }
</style>
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Care Manager Logged Minute Report</h4> 
        </div>
    </div> 
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                
                <form id="report_form" name="report_form" method="post" action ="">
                @csrf
                <div class="form-row">
                    <div class="col-md-2 form-group mb-3">
                        <label for="practicename">Practice Name</label>
                         @selectGroupedPractices("practices",["id" => "practice_id", "class" => "form-control select2"])  
                    </div> 
                    <div class="col-md-2 form-group mb-2">
                        <label for="date">From Date</label>
                        @date('date',["id" => "fromdate","name" =>"fromdate"])                           
                    </div>
                    <div class="col-md-1 form-group mb-3">
                       <button type="button" class="btn btn-primary mt-4 ml-3" id="month-search">Search</button>
                    </div>
                    <div class="col-md-1 form-group mb-3">
                       <button type="button" class="btn btn-primary mt-4 ml-1" id="reset-btn">Reset</button>
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
                <div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>data saved successfully! </strong><span id="text"></span>
               </div>
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                <table id="logged-minute" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Caremanager Name</th>
                            <th>5 Days Ago</th>
                            <th>4 Days Ago</th>
                            <th>3 Days Ago</th>
                            <th>2 Days Ago</th>
                            <th>Yesterday</th> 
                            <th>Avg</th>
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
    <script type="text/javascript" src="https://cdn.datatables.net/rowgroup/1.1.4/js/dataTables.rowGroup.min.js"></script>
    <script type="text/javascript">
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

    $(document).ready(function() { 
        $.fn.dataTable.ext.errMode = 'throw';
        util.getToDoListData(0, {{getPageModuleName()}});
        util.getAssignPatientListData(0, 0);
        $('#fromdate').val(currentdate); //.val(current_MonthYear);
    });
    var getAdditionalActivitiesList = function(practiceid = null, fromdate1 = null) {
    if(practiceid==''){practiceid=null;} 
    if(fromdate1==''){fromdate1=null;} 
    // var copy_img = "assets/images/copy_icon.png";
    // var excel_img = "assets/images/excel_icon.png";
    // var pdf_img = "assets/images/pdf_icon.png";
    // var csv_img = "assets/images/csv_icon.png";
    var assetBaseUrl ='';
        var columns =  [ 
            {data: null, mRender: function(data, type, full, meta){
                practice_name = full['name'];
                    if(full['name'] == null){
                        practice_name = '';
                    } 
                      
                    if(data!='' && data!='NULL' && data!=undefined){
                        return practice_name;
                    }
                },
                orderable: true
            },
            {data: null,
                mRender: function(data, type, full, meta){
                    caremanager = full['caremanager'];
                    if(full['caremanager'] == null){
                        caremanager = '';
                    }

                    if(data!='' && data!='NULL' && data!=undefined){ 
                        return caremanager;
                    }
                },
                orderable: true
            }, //1
            {data:null,
                mRender: function(data, type, full, meta){
                    five_days = full['five_days'];
                    if(data!='' && data!='NULL' && data!=undefined){
                        return five_days;
                    }
                },
                orderable: true
            },//2
            {data:null,
                mRender: function(data, type, full, meta){
                    four_days = full['four_days'];
                    if(data!='' && data!='NULL' && data!=undefined){
                        return four_days;
                    }
                },
                orderable: true
            },//3
            {data:null,
                mRender: function(data, type, full, meta){
                    three_days = full['three_days'];
                    if(full['three_days'] == null){
                        three_days = 0;
                    }
                      
                    if(data!='' && data!='NULL' && data!=undefined){
                        return three_days;
                    }
                },
                orderable: true
            },//4
            {data: null,
                mRender: function(data, type, full, meta){
                    two_days = full['two_days'];
                    if(full['two_days'] == null){
                        two_days = 0;
                    }

                    if(data!='' && data!='NULL' && data!=undefined){ 
                        return two_days;
                    }
                },
                orderable: true
            },//5
            {data: null,
                mRender: function(data, type, full, meta){
                    yesterday = full['yesterday'];
                    if(full['yesterday'] == null){
                        yesterday = 0;
                    }

                    if(data!='' && data!='NULL' && data!=undefined){ 
                        return yesterday;
                    }
                },
                orderable: true
            },//6
            {data: null,
                mRender: function(data, type, full, meta){
                    var five_days = full['five_days'];
                    var four_days = full['four_days'];
                    var three_days = full['three_days'];
                    var two_days = full['two_days'];
                    var yesterday = full['yesterday'];
                    var avg = full['total_avg'];
                    // var avg = parseInt(five_days) + parseInt(four_days) + parseInt(three_days) + parseInt(two_days) + parseInt(yesterday);//
                    if(avg < 0){ 
                        return 0;
                    }else{
                        return (avg);//parseFloat(avg/5).toFixed(2);
                    } 
                },
                orderable: true
            }           
            ];
            var url = "/reports/care-manager-logged-minute-productivity-report/search/"+practiceid+'/'+fromdate1;
                
                var table = $('#logged-minute').DataTable({
                    "dom": '<"float-right"B><"float-right"f><"float-left"r><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
                    buttons: [
                        // {
                        //     extend: 'copyHtml5',
                        //     text: '<img src="' + assetBaseUrl + copy_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Copy">',
                        // },
                        // {
                        //     extend: 'excelHtml5',
                        //     text: '<img src="' + assetBaseUrl + excel_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Excel">',
                        //     titleAttr: 'Excel' 
                        // },
                        // {
                        //     extend: 'csvHtml5',
                        //     text: '<img src="' + assetBaseUrl + csv_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="CSV">',
                        //     titleAttr: 'CSV',
                        //     fieldSeparator: '\|',
                        // },
                        // {
                        //     extend: 'pdfHtml5',
                        //     text: '<img src="' + assetBaseUrl + pdf_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="PDF">',
                        //     titleAttr: 'PDF'
                        // }
                    ],
                    processing: true,
                    // serverSide : true,
                    destroy: true,
                    // sScrollX: 1500,
                    // scrollY: true, 

                    // scrollH: true,     
                    // scrollCollapse: true,        
                    ajax: url,
                    columns: columns,
                    "destroy": true,
                    "Language": {
                        search: "_INPUT_",
                        // "search":'<a class="btn searchBtn" id="searchBtn"><i class="i-Search-on-Cloud"></i></a>',
                        "searchPlaceholder": "Search records",
                        "EmptyTable": "No Data Found"
                    },
                    order: [[0, 'asc']],
                    rowGroup: { 
                        dataSrc: '',
                        // startRender: null,

                        endRender: function (rows, group) {
                            var fiveDaysAvg = rows
                                        .data()
                                        .pluck('five_days')
                                        .reduce( function (a, b) {
                                            return a + b*1;
                                        }, 0) / rows.count();
                            var fourDaysAvg = rows
                                        .data()
                                        .pluck('four_days')
                                        .reduce( function (a, b) {
                                            return a + b*1;
                                        }, 0) / rows.count();
                            var threeDaysAvg = rows
                                        .data()
                                        .pluck('three_days')
                                        .reduce( function (a, b) {
                                            return a + b*1;
                                        }, 0) / rows.count();
                            var twoDaysAvg = rows
                                        .data()
                                        .pluck('two_days')
                                        .reduce( function (a, b) {
                                            return a + b*1;
                                        }, 0) / rows.count();
                            var yesterdayAvg = rows
                                        .data()
                                        .pluck('yesterday')
                                        .reduce( function (a, b) {	
                                            return a + b*1;
                                        }, 0) / rows.count();
                            var columnAvg = rows
                            			.data()
                                        .pluck('total_avg') 
                                        .reduce( function (a, b) {
                                            return a + b*1;
                                        }, 0) / rows.count();
        				  //return group +' ('+rows.count()+' rows on page, $' + Avg + ' salary total all pages)';    
                            return $('<tr/>')
                                .append( '<td><b>Total Avg</b></td>' )
                                .append( '<td>'+fiveDaysAvg.toFixed(2)+'</td>' )
                                .append( '<td>'+fourDaysAvg.toFixed(2)+'</td>' )
                                .append( '<td>'+threeDaysAvg.toFixed(2)+'</td>' )
                                .append( '<td>'+twoDaysAvg.toFixed(2)+'</td>' )
                                .append( '<td>'+yesterdayAvg.toFixed(2)+'</td>' )
                                .append( '<td>'+ columnAvg.toFixed(2)+'</td>' );  
                            
                            // $('#grandtotal').text(totalAvg);      
                        }, 
                    },
                    "columnDefs": [
                        {
                            "targets": [0],
                            "visible": false,
                            "searchable": false
                        }
                    ]
                  
                });
                return table;
            // var table1 = util.renderDataTable('logged-minute', url, columns, "{{ asset('') }}"); 
        }
    $("#month-search").click(function(){
        var practiceid =$('#practice_id').val();
        var fromdate1 = $('#fromdate').val();
      //  alert(practiceid); 
        getAdditionalActivitiesList(practiceid,fromdate1);
        
    });

    $("#reset-btn").click(function(){
        debugger;
        $('#practice_id').val('').trigger('change');
        $('#fromdate').val(currentdate);
        var practiceid =$('#practice_id').val();
        var fromdate1 = $('#fromdate').val();
        getAdditionalActivitiesList(practiceid,fromdate1);
    }); 
</script>      

@endsection    