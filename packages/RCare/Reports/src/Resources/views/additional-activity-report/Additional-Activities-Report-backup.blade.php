@extends('Theme::layouts_2.to-do-master')
@section('page-css')
  
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Additional Activities Report</h4>
             <!-- <button type="button" id="exportbutton" class="btn btn-primary">Export to excel</button>    -->
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                <form id="daily_report_form" name="daily_report_form"  action ="">
                @csrf
                <div class="form-row">
                   
                     <div class="col-md-2 form-group mb-2">
                        <label for="practicegrp">{{config('global.practice_group')}}</label>
                         @selectgrppractices("practicesgrp", ["class" => "select2","id" => "practicesgrp"]) 
                    </div>

                   
                    <div class="col-md-2 form-group mb-2">
                        <label for="practicename">Practice Name</label>
                          @selectGroupedPractices("practices",["id" => "practices", "class" => "form-control select2"]) 
                      
                    </div>                  
                   
                    <div class="col-md-2 form-group mb-2">
                        <label for="date">From Date</label>
                        @date('date',["id" => "fromdate"])
                                               
                    </div>
                     <div class="col-md-2 form-group mb-3">
                        <label for="date">To Date</label>
                        @date('date',["id" => "todate"])
                                              
                    </div>
                    <div class="col-md-2 form-group mb-3"> 
                          <label for="activedeactivestatus">Patient Status</label> 
                          <select id="activedeactivestatus" name="activedeactivestatus" class="custom-select show-tick" >
                            <option value="" selected>All (Active,Suspended,Deactivated,Deceased)</option> 
                            <option value="1">Active</option>
                            <option value="0">Suspended</option>
                            <option value="2" >Deactivated</option>                           
                            <option value="3" >Deceased</option>
                          </select>                         
                        </div>
                     
                    <div class="col-md-2 form-group mb-3">
                       <button type="button" id="searchbutton" class="btn btn-primary mt-4">Search</button>                   
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
        <div class="card text-left">

            <div class="card-body">
                @include('Theme::layouts.flash-message')                
           
                <div class="table-responsive">
                    <table id="Activities-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="35px">Sr No.</th>  
                             <th width="35px">View details</th>                          
                            <th width="205px">Patient</th>
                            <th width="97px">DOB</th>
                            <th width="97px">Practice</th>
                            <th width="">Provider</th> 
                            <th >Total Activities</th>
                            <th width="">Total Add'l Minutes</th>
                            <th width="">Status</th>  
                            <th width="">Total Minutes</th> 
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

     <!-- <link href="https://nightly.datatables.net/css/jquery.dataTables.css" rel="stylesheet" type="text/css" /> -->
    <script src="https://nightly.datatables.net/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.js"></script>
     <!-- <link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />  -->
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.colVis.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
   
    <script type="text/javascript">

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

                            // Get the child data - could be any data attached to the row.
                            // console.log(DT_row+"dtrow");
                            // // console.log(JSON.stringify(table.row(DT_row, {
                            //     search: 'none',
                            //     order: 'index'
                            // }).data()) + "dataatest");
                            var childData = table.row(DT_row, {
                                search: 'none',
                                order: 'index'
                            }).data().results;
                            // var rowdata = table.row($(DT_row).parents('tr')).data();
                            //console.log(JSON.stringify(rowdata)+"checkrowdata");        

                            // console.log(JSON.stringify(childData) + "test");
                            if (childData.length > 0) {
                                // Prepare Excel formated row
                                headerRow = '<row r="' + rowCount +
                                    '" s="2"><c t="inlineStr" r="A' + rowCount +
                                    '"><is><t>' +
                                    '</t></is></c><c t="inlineStr" r="B' + rowCount +
                                    '" s="2"><is><t>Sr.No.' +
                                    '</t></is></c><c t="inlineStr" r="C' + rowCount +
                                    '" s="2"><is><t>Activity Type' +
                                    '</t></is></c><c t="inlineStr" r="D' + rowCount +
                                    '" s="2"><is><t>Activity' +
                                    '</t></is></c><c t="inlineStr" r="E' + rowCount +
                                    '" s="2"><is><t>Date' +
                                    '</t></is></c><c t="inlineStr" r="F' + rowCount +
                                    '" s="2"><is><t>Time' +
                                    '</t></is></c><c t="inlineStr" r="G' + rowCount +
                                    '" s="2"><is><t>Comments' +
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
                                    '"><is><t>' + child.activity_type +
                                    '</t></is></c><c t="inlineStr" r="D' + rowCount +
                                    '"><is><t>' + child.activity +
                                    '</t></is></c><c t="inlineStr" r="E' + rowCount +
                                    '"><is><t>' + child.record_date +
                                    '</t></is></c><c t="inlineStr" r="F' + rowCount +
                                    '"><is><t>' + child.net_time +
                                    '</t></is></c><c t="inlineStr" r="G' + rowCount +
                                    '"><is><t>' + child.comment +
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
        //    sScrollX: true,
        // scrollY: 120, 
        // scrollH: true,     
        // scrollCollapse: true,        
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
        }]/*,
        "drawCallback": function( settings ) {
            if(copyflag == 1){
                //alert( copyTo + ' DataTables has redrawn the table' );
                t2content = $('#' + tabid +' tbody').html();
                alert(t2content);
               // console.log(t2content);
                $('#' + copyTo + ' tbody').html(t2content);
                //alert(copyTo);

                

                table2 = util.renderRawDataDataTable(copyTo, assetBaseUrl);
               //table2 = util.renderRawDataDataTableAnand(copyTo, assetBaseUrl,columnData);
                //copydata();


            }
        }*/
    });

    return table;
}

        var table1='';
        var childtable='';
       var getAdditionalActivitiesList = function(practicesgrp=null,practice = null,caremanagerid=null,fromdate1=null,todate1=null,activedeactivestatus=null) {
            var columns =  [   
                                {data: 'DT_RowIndex', name: 'DT_RowIndex'},  
                                 {data: 'action', name: 'action'},                               
                                {data: null, mRender: function(data, type, full, meta){
                                    m_Name = full['mname'];
                                    if(full['mname'] == null){
                                        m_Name = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                        if(full['profile_image']=='' || full['profile_image']==null) {
                                            return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                        } 
                                        else 
                                        {
                                            return ["<img src='"+full['profile_image']+"' width='40px' height='25px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                        }                                       
                                    }
                                },
                                orderable: true
                                },
                                {data: 'dob', type: 'date-dd-mmm-yyyy', name: 'dob', "render":function (value) {
                                    if (value === null) return "";
                                        return util.viewsDateFormat(value);
                                    }
                                },
                             
                               
                               {data:null,
                                    mRender: function(data, type, full, meta){
                                        name = full['practicename'];
                                        if(full['practicename'] == null){
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


                                {data: null,
                                    mRender: function(data, type, full, meta){
                                        activitycount = full['activitycount'];
                                        if(full['activitycount'] == null){
                                            activitycount = '0';
                                        }

                                        if(data!='' && data!='NULL' && data!=undefined){ 
                                            return activitycount;
                                        }
                                    },
                                    orderable: true
                                },  
                                 {data: null,
                                    mRender: function(data, type, full, meta){
                                        activitytotaltime = full['activitytotaltime'];
                                        if(full['activitytotaltime'] == null){
                                            activitytotaltime = '00:00:00';
                                        }

                                        if(data!='' && data!='NULL' && data!=undefined){ 
                                            return activitytotaltime;
                                        }
                                    },
                                    orderable: true
                                },  
                                {data: null, 
                                  mRender: function(data, type, full, meta){
                                    status = full['pstatus'];
                                      if(full['pstatus'] == 1){
                                          status = 'Active';
                                      } 
                                      if(full['pstatus'] == 0){
                                          status = 'Suspended';
                                      }
                                      if(full['pstatus'] == 2){ 
                                          status = 'Deactived';
                                      }
                                      if(full['pstatus'] == 3){ 
                                          status = 'Deceased';
                                      }
                                      if(data!='' && data!='NULL' && data!=undefined){
                                        return status;
                                      }
                                    },
                                    orderable: true, searchable: false
                                },   
                                 {data: null,
                                    mRender: function(data, type, full, meta){
                                        totaltime = full['totaltime'];
                                        if(full['totaltime'] == null){
                                            totaltime = '00:00:00';
                                        }

                                        if(data!='' && data!='NULL' && data!=undefined){ 
                                            return totaltime;
                                        }
                                    },
                                    orderable: true
                                },
                                 {
                                        data: null,
                                        visible: false,
                                        render: function(data, type, row, meta) {
                                            return meta.row;
                                        }
                                    }
                            ];
            
             // debugger;
             if(practicesgrp==''){practicesgrp=null;}
             if(practice==''){practice=null;}                        
             if(fromdate1==''){ fromdate1=null; }
             if(todate1=='')  { todate1=null; }
             if(caremanagerid==''){caremanagerid=null;}
             if(activedeactivestatus==''){activedeactivestatus=null;}

         var url = "/reports/additinal-activities-report/search/"+practicesgrp+"/"+practice+'/'+caremanagerid+'/'+fromdate1+'/'+todate1+'/'+activedeactivestatus;
         table1 = renderDataTableExport('Activities-list', url, columns, "{{ asset('') }}");
              
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
      
            $('#fromdate').val(firstDayWithSlashes);                      
            $('#todate').val(currentdate);     
     
          $("[name='modules']").val(3).attr("selected", "selected").change();          
            util.getToDoListData(0, {{getPageModuleName()}});

            $("[name='practicesgrp']").on("change", function () { 
                var practicegrp_id = $(this).val(); 
                if(practicegrp_id==0){
                   // getPatientData();  
                }
                if(practicegrp_id!=''){
                    util.getPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#practices")); 
                }
                else{
                     util.updatePracticeListWithoutOther(001, $("#practices")) ;
                }   
            });
        
           $("[name='caremanagerid']").on("change", function () {          
            $('#practicesgrp').val('').trigger('change');
          //  util.updatePracticeListWithoutOther(parseInt($(this).val()), $("#practices"))
                if($(this).val() == '' || $(this).val() == '0'){
                    util.updatePracticeListWithoutOther(001, $("#practices"))
                }else{
                    util.updatePracticeListWithoutOther(parseInt($(this).val()), $("#practices"))
                }    
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
                        activitieschild = activitieschild + '<th width="35px">Activity Type</th>';                         
                        activitieschild = activitieschild + '<th width="205px">Activity</th>';
                        activitieschild = activitieschild + '<th width="205px">Date</th>';
                        activitieschild = activitieschild + '<th width="97px">Time</th>';
                        activitieschild = activitieschild + '<th width="97px">Comments</th>';                             
                        activitieschild = activitieschild +  '</tr></thead><tbody></tbody> </table></div>';
                               row.child( activitieschild ).show();
                                 getAdditionalActivitiesChildList(row,row.data().FindField1,patient_id,fromdate,todate);
                     }
                 }); 


             function destroyChild(row) {
              var tabledestroy = $("childtable", row.child());
                tabledestroy.detach();
                tabledestroy.DataTable().destroy();
                // And then hide the row
                row.child.hide();
               }
           
        }); 

   
     var getAdditionalActivitiesChildList = function(row=null,$Find1=null,patient_id=null,fromdate=null,todate=null)
                {
                   var columns =  [   
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                   {data:null,
                                    mRender: function(data, type, full, meta){
                                        activity_type = full['activity_type'];
                                        if(full['activity_type'] == null){
                                            activity_type = '';
                                        }
                                          
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return activity_type;
                                        }
                                    },
                                    orderable: true
                                },
                                 {data:null,
                                    mRender: function(data, type, full, meta){
                                        activity = full['activity'];
                                        if(full['activity'] == null){
                                            activity = '';
                                        }
                                          
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return activity;
                                        }
                                    },
                                    orderable: true
                                },
                                 {data:null,
                                    mRender: function(data, type, full, meta){
                                        record_date = full['record_date'];
                                        if(full['record_date'] == null){
                                            record_date = '';
                                        }
                                          
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return record_date;
                                        }
                                    },
                                    orderable: true
                                },
                                 {data:null,
                                    mRender: function(data, type, full, meta){
                                        net_time = full['net_time'];
                                        if(full['net_time'] == null){
                                            net_time = '';
                                        }
                                          
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return net_time;
                                        }
                                    },
                                    orderable: true
                                },
                                 {data:null,
                                    mRender: function(data, type, full, meta){
                                        comment = full['comment'];
                                        if(full['comment'] == null){
                                            comment = '';
                                        }
                                          
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return comment;
                                        }
                                    },
                                    orderable: true
                                }
                  ]               
                
             var url = "/reports/additinal-activities-details/"+patient_id+'/'+fromdate+'/'+todate;
             var childtableid='Activities-Child-list'+patient_id;
               childtable = util.rendernewDataTable(childtableid, url, columns, "{{ asset('') }}");

                }

      


     $('#resetbutton').click(function(){
            $('#caremanagerid').val('').trigger('change');
            $('#practicesgrp').val('').trigger('change');
            $('#practices').val('').trigger('change');
            $('#activedeactivestatus').val('').trigger('change');
           
            $('#fromdate').val('');
            $('#todate').val('');
             
            
            var practicesgrp =$('#practicesgrp').val();
            var practice=$('#practices').val();
            var provider=$('#physician').val();
           
            var fromdate1=$('#fromdate').val();
            var todate1=$('#todate').val();
       
            var caremanagerid=$('#caremanagerid').val();
        
      
            $('#fromdate').val(firstDayWithSlashes);                      
            $('#todate').val(currentdate); 
            getAdditionalActivitiesList(practicesgrp,practice,caremanagerid,fromdate1,todate1,activedeactivestatus); 

  });

$('#searchbutton').click(function(){
   
   
    var practicesgrp =$('#practicesgrp').val(); 
    var practice=$('#practices').val();     
    var fromdate1=$('#fromdate').val();
    var todate1=$('#todate').val();       
    var caremanagerid=$('#caremanagerid').val();
    var activedeactivestatus = $('#activedeactivestatus').val();
              
           var eDate = new Date(todate1);
           var sDate = new Date(fromdate1);
           if(fromdate1!= '' && todate1!= '' && sDate> eDate)
             {
             alert("Please ensure that the To Date is greater than or equal to the From Date.");
             return false;
             }
             else
             {
            getAdditionalActivitiesList(practicesgrp,practice,caremanagerid,fromdate1,todate1,activedeactivestatus);
            }  
  });

   
         $('#exportbutton').click(function(){
          $.get("/reports/exportdata", function(data, status){
            alert("test");
            //   alert("Data: " + data + "\nStatus: " + status);
             });
              // $.ajax({
              //         url: "/reports/exportdata",
              //        // data: "message="+commentdata,
              //         type: 'post',
              //         success: function (resp) {
              //             alert(resp);
              //         },
              //         error: function(e) {
              //             alert('Error: '+e);
              //         }  
              //    });
         });

    
 
    </script>
@endsection