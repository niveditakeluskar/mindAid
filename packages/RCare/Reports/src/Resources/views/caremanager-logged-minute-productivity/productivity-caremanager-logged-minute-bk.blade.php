@extends('Theme::layouts_2.to-do-master')
@section('page-css')
@section('page-title')
Care ManagerCare Manager Logged Minute Report
@endsection    
@endsection
@section('main-content')
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
                        <label for="practicegrp">{{config('global.practice_group')}}</label>
                         @selectgrppractices("practicesgrp", ["class" => "select2","id" => "practicesgrp","name"=>"practicesgrp"]) 
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
                    <table id="Activities-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="35px">Sr No.</th>
                            <th>View Details</th> 
                            <th>Practice grp</th>
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

    var renderDataTableExport = function (tabid, url, columnData, assetBaseUrl, copyflag = "0", copyTo = '') {

    var copy_img = "assets/images/copy_icon.png";
    var excel_img = "assets/images/excel_icon.png";
    var pdf_img = "assets/images/pdf_icon.png";
    var csv_img = "assets/images/csv_icon.png";
    var table = $('#' + tabid).DataTable({
        "dom": '<"float-right"B><"float-right"f><"float-left"r><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
        // dom     : '<"float-right"B><"clearfix"><"navbar-text"><"float-left"lr><"float-right"f><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
        // dom     : '<"navbar-text"><"float-left"lr><"float-right"f><"float-right"B><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
        buttons: [
            {
                extend: 'copyHtml5',
                text: '<img src="' + assetBaseUrl + copy_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Copy">',
            },
            {
                extend: 'excelHtml5',
                text: '<img src="' + assetBaseUrl + excel_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Excel">',
                titleAttr: 'Excel',

                customize: function(xlsx) {
                    // var table = $('#example').DataTable();

                    // Get number of columns to remove last hidden index column.
                    var numColumns = table.columns().header().count();

                    // Get sheet.
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];

                    var col = $('col', sheet);
                    // Set the column width.
                    $(col[1]).attr('width', 20);

                    // Get a clone of the sheet data.        
                    var sheetData = $('sheetData', sheet).clone();

                    // Clear the current sheet data for appending rows.
                    $('sheetData', sheet).empty();

                    // Row index from last column.
                    var DT_row; // Row count in Excel sheet.

                    var rowCount = 1;

                    // Itereate each row in the sheet data.
                    $(sheetData).children().each(function(index) {

                        // Used for DT row() API to get child data.
                        var rowIndex = index - 1;

                        // Don't process row if its the header row.
                        if (index > 0) {
                            //alert(index);
                            // Get row
                            var row = $(this.outerHTML);
                            // console.log(row+"row");
                            // Set the Excel row attr to the current Excel row count.
                            row.attr('r', rowCount);

                            var colCount = 1;
                           // console.log(row.children().length + "rowchildr");

                            // Iterate each cell in the row to change the rwo number.
                            row.children().each(function(index) {
                                //  console.log(index+"index");
                                var cell = $(this);

                                // Set each cell's row value.
                                var rc = cell.attr('r');
                                //  console.log(rc+" rc");
                                rc = rc.replace(/\d+$/, "") + rowCount;
                                cell.attr('r', rc);
                               // console.log(colCount + " === ====" + numColumns);
                                if (colCount === numColumns) {
                                    DT_row = cell.text();
                                    cell.html('');
                                }

                                colCount++;
                            });

                            // Get the row HTML and append to sheetData.
                            row = row[0].outerHTML;
                            $('sheetData', sheet).append(row);
                            rowCount++;
                            var childData = table.row(DT_row, {
                                search: 'none',
                                order: 'index'
                            }).data().results;
                            // console.log(JSON.stringify(childData) + "test");
                            if (childData.length > 0) {
                                // Prepare Excel formated row
                                headerRow = '<row r="' + rowCount +
                                    '" s="2"><c t="inlineStr" r="A' + rowCount +
                                    '"><is><t>' +
                                    '</t></is></c><c t="inlineStr" r="B' + rowCount +
                                    '" s="2"><is><t>Sr.No.' +
                                    '</t></is></c><c t="inlineStr" r="C' + rowCount +
                                    '" s="2"><is><t>caremanager' +
                                    '</t></is></c><c t="inlineStr" r="D' + rowCount +
                                    '" s="2"><is><t>5 days' +
                                    '</t></is></c><c t="inlineStr" r="E' + rowCount +
                                    '" s="2"><is><t>4 days' +
                                    '</t></is></c><c t="inlineStr" r="F' + rowCount +
                                    '" s="2"><is><t>3 days' +
                                    '</t></is></c><c t="inlineStr" r="G' + rowCount +
                                    '" s="2"><is><t>2 days' +
                                    '</t></is></c><c t="inlineStr" r="H' + rowCount +
                                    '" s="2"><is><t>Yesterday' +
                                    '</t></is></c><c t="inlineStr" r="I' + rowCount +
                                    '" s="2"><is><t>Avg' +
                                    '</t></is></c></row>';

                                // Append header row to sheetData.
                                $('sheetData', sheet).append(headerRow);
                                rowCount++; // Inc excelt row counter.

                            }

                            // The child data is an array of rows
                            for (c = 0; c < childData.length; c++) {

                                // Get row data.
                                child = childData[c];
                                 var srno=c+1;
                                // Prepare Excel formated row
                                childRow = '<row r="' + rowCount +
                                    '"><c t="inlineStr" r="A' + rowCount +
                                    '"><is><t>' +
                                    '</t></is></c><c t="inlineStr" r="B' + rowCount +
                                    '"><is><t>' + srno +
                                    '</t></is></c><c t="inlineStr" r="C' + rowCount +
                                    '"><is><t>' + child.caremanagername +
                                    '</t></is></c><c t="inlineStr" r="D' + rowCount +
                                    '"><is><t>' + child.five_days +
                                    '</t></is></c><c t="inlineStr" r="E' + rowCount +
                                    '"><is><t>' + child.four_days +
                                    '</t></is></c><c t="inlineStr" r="F' + rowCount +
                                    '"><is><t>' + child.three_days +
                                    '</t></is></c><c t="inlineStr" r="G' + rowCount +
                                    '"><is><t>' + child.two_days +
                                    '</t></is></c><c t="inlineStr" r="H' + rowCount +
                                    '"><is><t>' + child.yesterdays +
                                    '</t></is></c><c t="inlineStr" r="I' + rowCount +
                                    '"><is><t>' + child.total_avg +
                                    '</t></is></c></row>';

                                // Append row to sheetData.
                                $('sheetData', sheet).append(childRow);
                                rowCount++; // Inc excelt row counter.

                            }
                            // Just append the header row and increment the excel row counter.
                        } else {
                            var row = $(this.outerHTML);

                            var colCount = 1;

                            // Remove the last header cell.
                            row.children().each(function(index) {
                                var cell = $(this);

                                if (colCount === numColumns) {
                                    cell.html('');
                                }

                                colCount++;
                            });
                            row = row[0].outerHTML;
                            $('sheetData', sheet).append(row);
                            rowCount++;
                        }
                    });
                },               
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
        processing: true,
        // serverSide : true,
        destroy: true,    
        ajax: url,
        columns: columnData,
        "Language": {
            search: "_INPUT_",
            // "search":'<a class="btn searchBtn" id="searchBtn"><i class="i-Search-on-Cloud"></i></a>',
            "searchPlaceholder": "Search records",
            "EmptyTable": "No Data Found"
        },
        "columnDefs": [{
            "targets": '_all',
            "defaultContent": ""
        }]
    });

    return table;
}

        var table1='';
        var childtable='';
        var getAdditionalActivitiesList = function(practicesgrp = null, fromdate1 = null) {
        var columns =  [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'}, 
            {data: 'action', name: 'action'},
            {data: null, mRender: function(data, type, full, meta){
                practice_name = full['practice_name'];
                    if(full['practice_name'] == null){
                        practice_name = '';
                    }
                      
                    if(data!='' && data!='NULL' && data!=undefined){
                        return practice_name;
                    }
                },
                orderable: true
            }, //1
            {data:null,
                mRender: function(data, type, full, meta){
                    five_days = full['five_days'];
                    if(full['five_days'] == null){
                        five_days = '';
                    }
                      
                    if(data!='' && data!='NULL' && data!=undefined){
                        return five_days;
                    }
                },
                orderable: true
            },//2
            {data:null,
                mRender: function(data, type, full, meta){
                    four_days = full['four_days'];
                    if(full['four_days'] == null){
                        four_days = '';
                    }
                      
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
                        three_days = '';
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
                        two_days = '';
                    }

                    if(data!='' && data!='NULL' && data!=undefined){ 
                        return two_days;
                    }
                },
                orderable: true
            },//5
            {data: null,
                mRender: function(data, type, full, meta){
                    Yesterday = full['Yesterday'];
                    if(full['Yesterday'] == null){
                        Yesterday = '';
                    }

                    if(data!='' && data!='NULL' && data!=undefined){ 
                        return Yesterday;
                    }
                },
                orderable: true
            },//6
            {data: null,
                mRender: function(data, type, full, meta){
                    total_avg = full['total_avg'];
                    if(full['total_avg'] == null){
                        total_avg = '';
                    }

                    if(data!='' && data!='NULL' && data!=undefined){ 
                        return total_avg;
                    }
                },
                orderable: true
            }              
            ];
            if(practicesgrp==''){practicesgrp=null;} 
            if(fromdate1==''){fromdate1=null;}
             var url = "/reports/care-manager-logged-minute-productivity-report/search/"+practicesgrp+'/'+fromdate1;             
            var table1 = util.renderDataTable('Activities-list', url, columns, "{{ asset('') }}"); 
        } 
    $(document).ready(function() { 
     
        util.getToDoListData(0, {{getPageModuleName()}});
         $('#fromdate').val(currentdate); //.val(current_MonthYear);
        // $(".patient-div").hide(); // to hide patient search select

        $("[name='modules']").val(3).attr("selected", "selected").change();
        $("[name='modules']").on("change", function () {
        });
        $("[name='monthlyto']").on("change", function (){    
        });
        $("[name='monthly']").on("change", function (){    
        });

        $("[name='practicesgrp']").on("change", function () { 
            var practicegrp_id = $(this).val(); 

            if(practicegrp_id==0){
                //getAdditionalActivitiesList();  
            }
            if(practicegrp_id!=''){
                util.getPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#practices")); 
            }
            else{
                  util.updatePracticeListWithoutOther(001, $("#practices")) ;
            }   
        });  
    });




    $("#month-search").click(function(){
        var practicesgrp =$('#practicesgrp').val();
        var fromdate1 = $('#fromdate').val();
      //  alert(practicesgrp);
        getAdditionalActivitiesList(practicesgrp,fromdate1);
        
    });

    $("#reset-btn").click(function(){ 
        $('#caremanagerid').val('').trigger('change');
        $('#practices').val('').trigger('change');
        $('#practicesgrp').val('').trigger('change');
        var c_month = (new Date().getMonth() + 1).toString().padStart(2, "0");
        var c_year = new Date().getFullYear();
        var current_MonthYear = c_year+'-'+c_month;
        $("#monthlyto").val(current_MonthYear);
        $("#fromdate").val(current_MonthYear);
        $("[name='modules']").val(3).attr("selected", "selected").change(); 
        $('#activedeactivestatus').val('').attr("selected","selected").change();
            }); 
        $("[name='caremanagerid']").on("change", function () { 
    });
          var tablecount=1;
                $('#Activities-list tbody').on('click', 'td a', function () {     
                  var data_id=  $(this).attr('id');      
                   var res = data_id.split("/");
                   var patient_id=res[0];
                    var fromdate=res[1];
                     var todate=res[2];

                  var tr  = $(this).closest('tr'),
                    row = $('#Activities-list').DataTable().row(tr);
                     if (row.child.isShown()) {                 
                              destroyChild(row);
                            tr.removeClass('shown');
                          $(this).find('i').removeClass('i-Remove');
                            $(this).find('i').addClass('i-Add');
                          
                          // $(this).attr("class","http://i.imgur.com/SD7Dz.png");
                     }
                     else
                     {                      
                          tr.addClass('shown');
                        //$(this).attr("src","https://i.imgur.com/d4ICC.png");
                          $(this).find('i').removeClass('i-Add');
                            $(this).find('i').addClass('i-Remove');
                          
                         var activitieschild=' <div class="table-responsive">';
                        activitieschild = activitieschild + '<table id="Activities-Child-list'+patient_id+'" class="display table table-striped table-bordered activitieschildtable" style="width:100%">';
                        activitieschild = activitieschild + '<thead>';
                        activitieschild = activitieschild + '<tr>';
                        activitieschild = activitieschild + '<th width="35px">Sr No.</th>';  
                        activitieschild = activitieschild + '<th width="35px">CM</th>';                         
                        activitieschild = activitieschild + '<th width="205px">5days</th>';
                        activitieschild = activitieschild + '<th width="205px">4days</th>';
                        activitieschild = activitieschild + '<th width="97px">3days</th>';
                        activitieschild = activitieschild + '<th width="97px">2days</th>'; 
                        activitieschild = activitieschild + '<th width="97px">yesterdays</th>';                             
                        activitieschild = activitieschild + '<th width="97px">totalAvg</th>'; 
                        activitieschild = activitieschild +  '</tr></thead><tbody></tbody> </table></div>';
                               row.child( activitieschild ).show();
                                 getAdditionalActivitiesChildList(row,row.data().FindField1,patient_id,fromdate);
                     }
                 }); 


             function destroyChild(row) {
              var tabledestroy = $("childtable", row.child());
                tabledestroy.detach();
                tabledestroy.DataTable().destroy();
                // And then hide the row
                row.child.hide();
            }
             var getAdditionalActivitiesChildList = function(row=null,$Find1=null,patient_id=null,fromdate=null)
                {
                   var columns =  [  
                   {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                   {data: null, mRender: function(data, type, full, meta){
                        caremanagername = full['caremanager'];
                            if(full['caremanager'] == null){
                                caremanagername = '';
                            }
                              
                            if(data!='' && data!='NULL' && data!=undefined){
                                return caremanagername;
                            }
                        },
                        orderable: true
                    }, //1
                    {data:null,
                        mRender: function(data, type, full, meta){
                            five_days = full['five_days'];
                            if(full['five_days'] == null){
                                five_days = '';
                            }
                              
                            if(data!='' && data!='NULL' && data!=undefined){
                                return five_days;
                            }
                        },
                        orderable: true
                    },//2
                    {data:null,
                        mRender: function(data, type, full, meta){
                            four_days = full['four_days'];
                            if(full['four_days'] == null){
                                four_days = '';
                            }
                              
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
                                three_days = '';
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
                                two_days = '';
                            }

                            if(data!='' && data!='NULL' && data!=undefined){ 
                                return two_days;
                            }
                        },
                        orderable: true
                    },//5
                    {data: null,
                        mRender: function(data, type, full, meta){
                            Yesterday = full['Yesterday'];
                            if(full['Yesterday'] == null){
                                Yesterday = '';
                            }

                            if(data!='' && data!='NULL' && data!=undefined){ 
                                return Yesterday;
                            }
                        },
                        orderable: true
                    },//6
                    {data: null,
                        mRender: function(data, type, full, meta){
                            total_avg = full['total_avg'];
                            if(full['total_avg'] == null){
                                total_avg = '';
                            }

                            if(data!='' && data!='NULL' && data!=undefined){ 
                                return total_avg;
                            }
                        },
                        orderable: true
                    },
                   
                  ]               
                
             var url = "/reports/care-manager-logged-minute-productivity-details/"+patient_id+'/'+fromdate;
             var childtableid='Activities-Child-list'+patient_id;
               childtable = util.rendernewDataTable(childtableid, url, columns, "{{ asset('') }}");

                }

      



           
</script>      

@endsection    