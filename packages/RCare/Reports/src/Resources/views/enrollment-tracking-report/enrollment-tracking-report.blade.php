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

<div id="txtArea1"></div>  

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

   

    <!-- //cdn.datatables.net/plug-ins/1.11.5/api/sum().js -->
    <script type="text/javascript">



function rowGroupingExportData(dt, inOpts) {    


var config = $.extend(true, {}, {
    rows: null,
    columns: '',
    grouped_array_index: [],
    modifier: {
        search: 'applied',
        order: 'applied'
    },
    orthogonal: 'display',
    stripHtml: true,
    stripNewlines: true,
    decodeEntities: true,
    trim: true,
    format: {
        header: function (d) {
            return strip(d);
        },
        footer: function (d) {
            return strip(d);
        },
        body: function (d) {
            return strip(d);
        }
    }

}, inOpts);

var strip = function (str) {
    if (typeof str !== 'string') {
        return str;
    }

    // Always remove script tags
    str = str.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');

    if (config.stripHtml) {
        str = str.replace(/<[^>]*>/g, '');
    }

    if (config.trim) {
        str = str.replace(/^\s+|\s+$/g, '');
    }

    if (config.stripNewlines) {
        str = str.replace(/\n/g, ' ');
    }

    console.log(str); 
    // console.log(config.decodeEntities) ;
    // console.log(_exportTextarea);  
    // var _exportTextarea = 0 ;
    // console.log(_exportTextarea); 

    // if (config.decodeEntities) {
        // _exportTextarea.innerHTML = str;
        // str = _exportTextarea.value;  

        // txtArea1.document.open("txt/html","replace");
        // txtArea1.document.write(str);
        // txtArea1.document.close();
        // txtArea1.focus(); 
        // str=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
    // }  

    return str;
};


var header = dt.columns(config.columns).indexes().map(function (idx) {
    var el = dt.column(idx).header();
    return config.format.header(el.innerHTML, idx, el);
}).toArray();

var footer = dt.table().footer() ?
    dt.columns(config.columns).indexes().map(function (idx) {
        var el = dt.column(idx).footer();
        return config.format.footer(el ? el.innerHTML : '', idx, el);
    }).toArray() :
    null;

var rowIndexes = dt.rows(config.rows, config.modifier).indexes().toArray();
var selectedCells = dt.cells(rowIndexes, config.columns);
var cells = selectedCells
    .render(config.orthogonal)
    .toArray();
var cellNodes = selectedCells
    .nodes()
    .toArray();

var grouped_array_index = config.grouped_array_index;                     //customised
var no_of_columns = header.length;
var no_of_rows = no_of_columns > 0 ? cells.length / no_of_columns : 0;
var body = new Array(no_of_rows);
var body_data = new Array(no_of_rows);                                //customised
var body_with_nodes = new Array(no_of_rows);                          //customised
var body_with_nodes_static = new Array(no_of_rows);                          //customised
var cellCounter = 0;

for (var i = 0, ien = no_of_rows; i < ien; i++) {
    var rows = new Array(no_of_columns);
    var rows_with_nodes = new Array(no_of_columns);

    for (var j = 0; j < no_of_columns; j++) {
        rows[j] = config.format.body(cells[cellCounter], i, j, cellNodes[cellCounter]);
        rows_with_nodes[j] = config.format.body(cellNodes[cellCounter], i, j, cells[cellCounter]).outerHTML;
        cellCounter++;
    }

    body[i] = rows;
    body_data[i] = rows;
    body_with_nodes[i] = $.parseHTML('<tr class="even">' + rows_with_nodes.join("") + '</tr>');
    body_with_nodes_static[i] = $.parseHTML('<tr class="even">' + rows_with_nodes.join("") + '</tr>');
}

// only attempt group if even defined grouped_array_index
if (grouped_array_index.length > 0) {

    // also check to see if the column(s) defined in grouped_array_index are present in the data.  we can grab the first row from row_data_array and check
    var testRow = dt.rows().data()[0];
    var testCheck = true;
    for (var k = 0; k < grouped_array_index.length; k++) {
        if (testRow[grouped_array_index[k]] == null) {
            console.log('Column ' + grouped_array_index[k] + ' does not exist in data, aborting grouping...');
            testCheck = false;
            break;
        }
    }
    if (testCheck == true) {

        /******************************************** GROUP DATA *****************************************************/
        var row_array = dt.rows().nodes();
        var row_data_array = dt.rows().data();
        var iColspan = no_of_columns;
        var sLastGroup = "";
        var inner_html = '',
            grouped_index;
        var individual_group_array = [],
            sub_group_array = [],
            total_group_array = [];
        var no_of_splices = 0;  //to keep track of no of element insertion into the array as index changes after splicing one element

        for (var i = 0, row_length = body_with_nodes.length; i < row_length; i++) {
            sub_group_array[i] = [];
            individual_group_array[i] = [];

            var sGroup = '';

            for (var k = 0; k < grouped_array_index.length; k++) {
                sGroup = row_data_array[i][grouped_array_index[k]];
                inner_html = row_data_array[i][grouped_array_index[k]];
                grouped_index = k;
                individual_group_array[i][k] = row_data_array[i][grouped_array_index[k]];
                sub_group_array[i][k] = sGroup;
            }

            total_group_array[i] = sGroup;

            if (sGroup !== sLastGroup) {
                var table_data = [];
                var table_data_with_node = '';

                for (var $column_index = 0; $column_index < iColspan; $column_index++) {
                    if ($column_index === 0) {
                        table_data_with_node += '<td style="border-left:none;border-right:none">' + inner_html + '</td>';
                        table_data[$column_index] = inner_html + " ";
                    }
                    else {
                        table_data_with_node += '<td style="border-left:none;border-right:none"></td>';
                        table_data[$column_index] = '';
                    }
                }

                body_with_nodes.splice(i + no_of_splices, 0, $.parseHTML('<tr class="group group_' + grouped_index + ' grouped-array-index_' + grouped_array_index[grouped_index] + '">' + table_data_with_node + '</tr>'));
                body_data.splice(i + no_of_splices, 0, table_data);
                no_of_splices++;
                sLastGroup = sGroup;
            }
        }
    }
}
console.log( header);
console.log( footer);
console.log( body_data); 
if ( config.customizeData ) {
        config.customizeData( data );
    }

return {
    header: header,
    footer: footer,
    body: body_data
};


 
};

// var exportData = function ( dt, inOpts )
// {
//     var config = $.extend( true, {}, {
//         rows:           null,
//         columns:        '',
//         modifier:       {
//             search: 'applied',
//             order:  'applied'
//         },
//         orthogonal:     'display',
//         stripHtml:      true,
//         stripNewlines:  true,
//         decodeEntities: true,
//         trim:           true,
//         format:         {
//             header: function ( d ) {
//                 return strip( d );
//             },
//             footer: function ( d ) {
//                 return strip( d );
//             },
//             body: function ( d ) {
//                 return strip( d );
//             }
//         },
//         customizeData: null
//     }, inOpts );
 
//     var strip = function ( str ) {
//         if ( typeof str !== 'string' ) {
//             return str;
//         }
 
//         // Always remove script tags
//         str = str.replace( /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '' );
 
//         // Always remove comments
//         str = str.replace( /<!\-\-.*?\-\->/g, '' );
 
//         if ( config.stripHtml ) {
//             str = str.replace( /<[^>]*>/g, '' );
//         }
 
//         if ( config.trim ) {
//             str = str.replace( /^\s+|\s+$/g, '' );
//         }
 
//         if ( config.stripNewlines ) {
//             str = str.replace( /\n/g, ' ' );
//         }
 
//         // if ( config.decodeEntities ) {
//         //     _exportTextarea.innerHTML = str;
//         //     str = _exportTextarea.value;
//         // }
    
//         return str;
//     };
 
 
//     var header = dt.columns( config.columns ).indexes().map( function (idx) {
//         var el = dt.column( idx ).header();
//         return config.format.header( el.innerHTML, idx, el );
//     } ).toArray();
 
//     var footer = dt.table().footer() ?
//         dt.columns( config.columns ).indexes().map( function (idx) {
//             var el = dt.column( idx ).footer();
//             return config.format.footer( el ? el.innerHTML : '', idx, el );
//         } ).toArray() :
//         null;
 
//     // If Select is available on this table, and any rows are selected, limit the export
//     // to the selected rows. If no rows are selected, all rows will be exported. Specify
//     // a `selected` modifier to control directly.
//     var modifier = $.extend( {}, config.modifier );
//     if ( dt.select && typeof dt.select.info === 'function' && modifier.selected === undefined ) {
//         if ( dt.rows( config.rows, $.extend( { selected: true }, modifier ) ).any() ) {
//             $.extend( modifier, { selected: true } )
//         }
//     }
 
//     var rowIndexes = dt.rows( config.rows, modifier ).indexes().toArray();
//     var selectedCells = dt.cells( rowIndexes, config.columns );
//     var cells = selectedCells
//         .render( config.orthogonal )
//         .toArray();
//     var cellNodes = selectedCells
//         .nodes()
//         .toArray();
 
//     var columns = header.length;
//     var rows = columns > 0 ? cells.length / columns : 0;
//     var body = [];
//     var cellCounter = 0;
 
//     for ( var i=0, ien=rows ; i<ien ; i++ ) {
//         var row = [ columns ];
 
//         for ( var j=0 ; j<columns ; j++ ) {
//             row[j] = config.format.body( cells[ cellCounter ], i, j, cellNodes[ cellCounter ] );
//             cellCounter++;
//         }
 
//         body[i] = row;
//     }
 
//     var grouped_array_index = config.grouped_array_index;
 
//     if ( !(grouped_array_index == undefined) ) { //don't run grouping logic if rows aren't grouped
 
//         var row_array = dt.rows().nodes();
//         var row_data_array = dt.rows().data();
//         var iColspan = columns;
//         var sLastGroup = "";
//         var no_of_splices = 0;
 
//         for (var i = 0, row_length = body.length; i < row_length; i++) {
//             var sGroup = row_data_array[i][grouped_array_index];
 
//             if ( sGroup !== sLastGroup ) {
//                 var table_data = [];
 
//                 for (var $column_index = 0; $column_index < iColspan; $column_index++) {
//                     if ($column_index === 0)
//                     {
//                         // strips anything inside < > tags. hoping this won't be an issue in the future.
//                         table_data[$column_index] = sGroup.replace( /<[^>]*>/gi, '' ); + " ";
//                     }
//                     else
//                     {
//                         table_data[$column_index] = '';
//                     }
//                 }
//                 body.splice(i + no_of_splices, 0, table_data);
//                 no_of_splices++;
//                 sLastGroup = sGroup;
//             }
//         }
//     }
 
//     var data = {
//         header: header,
//         footer: footer,
//         body:   body
//     };

//     console.log(header);
//     console.log(body);
//     console.log(footer);  
 
//     if ( config.customizeData ) {
//         config.customizeData( data );
//     }
 
//     return data;
// };





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
          console.log(dataObject);
          var columns = [];
		  var tableHeaders;
          $.each(dataObject[0].columns, function(i, val){
                tableHeaders += "<th>" + val.title + "</th>";
                console.log( tableHeaders += "<th>" + val.title + "</th>");
          });

          var footer = $("<tfoot></tfoot>").appendTo('#patient-list-'+randomval);
          var footertr = $("<tr style='background-color: #e0e0e0;'></tr>").appendTo(footer);
          //Add footer cells
            for (var i = 0; i < dataObject[0].COLUMNS.length; i++) {
                $("<td style='font-weight: bold;'></td>").appendTo(footertr);
            }

      
     $('#patient-list-'+randomval).DataTable({    
        "dom": '<"float-right"B><"float-right"f><"float-left"r><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
        buttons: [ 
                   
            // {
            //     extend: 'copyHtml5',
            //     text: '<img src="' + assetBaseUrl + copy_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Copy">',
            // },
            {
                extend: 'excel',
                text: '<img src="' + assetBaseUrl + excel_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Excel">',
                titleAttr: 'Excel',
                filename: 'New-report',
                exportOptions: { 
                    orthogonal: 'export',  
                    grouped_array_index: [1]
                  
                 },
                  
            }
            // {
            //     extend: "excelHtml5",
            //     text: '<img src="' + assetBaseUrl + excel_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="RowGroupExcel">',
            //     title: '',
            //     filename: 'New-report',
            //     exportOptions: { 
            //         orthogonal: 'export',
            //         grouped_array_index: [1]
            //      },  
            //      action: function (e, dt, button, config, inOpts) {
            //         // console.log( e );  
            //         // console.log( dt );
            //     //    dt.columns(config.columns).indexes().map(function (idx) {
            //     //     //    console.log(idx);
            //     //     });  
            //         // console.log( button);  
            //         // console.log( config  );
            //         // console.log( inOpts);
            //         // alert( 'Button activated' ); 
                      
            //         // exportTableToCSV.apply(dt, [$('#' + tabid), 'export.xls']);
            //         rowGroupingExportData(dt,inOpts);
            //     }
            // }, 
        
            // {
            //     text: 'My button',
            //     action: function ( e, dt, node, config ) {
            //         alert( 'Button activated' );
            //     }
            // }
            // {
            //     extend: 'csvHtml5',
            //     text: '<img src="' + assetBaseUrl + csv_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="CSV">',
            //     titleAttr: 'CSV',
			// 	fieldSeparator: '\|',
            // },
            // {
            //     extend: 'pdfHtml5',
            //     text: '<img src="' + assetBaseUrl + pdf_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="PDF">',
            //     titleAttr: 'PDF' 
            // }
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
        order: [[1, 'asc']],
        rowGroup: {

            startRender: function ( rows, group ) {  
               var  noofdaysinmonth = dataObject[0].COLUMNS.length;
               var specificrowdata =  $('<tr/>')
                                      .append( '<td colspan="2">Total for '+group+'</td>' )

               for (var i = 2; i < noofdaysinmonth ; i++) {  
                                       
                    specificrowdata.append( '<td>'+rows
                                    .data()
                                    .pluck(i)
                                    .reduce( function (a, b) {
                                        return a + b;
                                    }, 0) +'</td>' ) 
                }
             
                    // var salaryAvg = rows
                    // .data()
                    // .pluck(i)
                    // .reduce( function (a, b) {
                    //     return a + b;
                    // }, 0) ;

                    // return $('<tr/>')
                    // .append( '<td colspan="2">Total for '+group+'</td>' )
                    // .append( '<td/>' )
                    // .append( '<td/>' )
                    // .append( '<td/>' )
                    // .append( '<td>'+salaryAvg+'</td>' ); 
              
                    return specificrowdata;
            }, 
            
            dataSrc: 1,
        },  
        "footerCallback": function( tfoot, data, start, end, display ) {
            $(tfoot).find('td').eq(0).html( "TOTAL");
            console.log(start) ;
            var api = this.api();
            console.log(api);
            var  noofdaysinmonth = dataObject[0].COLUMNS.length;
            for (var i = 2; i < noofdaysinmonth ; i++) {  
                    $( api.column( i ).footer() ).html(
                
                    api.column( i ).data().reduce( function ( a, b ) {
                    return a + b;
                    // console.log(c);
                }, 0 )
                // console.log(sum);
                );                          
            }  
             
         
        }
        
        // drawCallback: function () {
        //         var sum = $('#patient-list-'+randomval').DataTable().column(4).data().sum();
        //         $('#total').html(sum);
        // }
        // "footerCallback": function ( row, data, start, end, display ) {
        //     var api = this.api();
        //     console.log(api);
        //     total =  api
        //             .column( 4 )
        //             .reduce( function (a, b) {
        //                 return (a) + (b);
        //             }, 0 );


        //     <tfoot>
        //     <tr>
        //         <th colspan="4" style="text-align:right">Total:</th>
        //         <th></th>
        //     </tr>
        // </tfoot>

           // return $('<tr/>')
                    // .append( '<td colspan="2">Total for '+group+'</td>' )
                    // .append( '<td/>' )
                    // .append( '<td/>' )
                    // .append( '<td/>' )
                    // .append( '<td>'+salaryAvg+'</td>' ); 
              

            // return $('<tfoot/><tr/>')
            //         .append( '<th colspan="2">Total</th>' )
            //         .append( '<th>'+total+'</th>' );

                    
            //      // Update footer
            // $( api.column( 4 ).footer() ).html(
            //    ' ( $'+ total +' total)'
            // );
                   
        // }  
        
		
     });
     $('#load-monthly-billing-tbl').hide();  

      } 
        
        

       
    });

    
} 








        $(document).ready(function() {
           
           
            getEnrollmentTrackingList();
            function getMonth(date) {
            var month = date.getMonth() + 1;
            return month < 10 ? '0' + month : '' + month; // ('' + month) for string result
            }

            var c_month = (new Date().getMonth() + 1).toString().padStart(2, "0");
            var c_year = new Date().getFullYear();
            var current_MonthYear = c_year+'-'+c_month;
            // alert(current_MonthYear); 
            $("#monthly").val(current_MonthYear);
          
      
            util.getToDoListData(0, {{getPageModuleName()}});
            util.getAssignPatientListData(0, 0);

               
        }); 

        $("#searchbutton").click(function(){
         
            var monthly = $('#monthly').val();
           
            getEnrollmentTrackingList(monthly); 

           
    
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
           
          
            getEnrollmentTrackingList(monthly);  
        });  

       


    
        
        </script>
@endsection         