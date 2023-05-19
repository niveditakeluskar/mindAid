@extends('Theme::layouts_2.to-do-master')
@section('page-css')
  
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Additional Activities Report</h4>
             <button type="button" id="exportbutton" class="btn btn-primary">Export to excel</button>   
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
                        <label for="caremanagerid">Care Manager</label>
                       @selectcaremanagerwithAll("caremanagerid", ["id" => "caremanagerid", "placeholder" => "Select Care Manager","class" => "select2"])
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
                  <table id="example" class="display" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Date</th>
                          <th>Description</th>
                          <th>Index</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                          <th>Name</th>
                          <th>Date</th>
                          <th>Description</th>
                          <th>Index</th>
                        </tr>
                      </tfoot>
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



      <link href="https://nightly.datatables.net/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
    <script src="https://nightly.datatables.net/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.js"></script>
    <link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.colVis.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    
   
    <script type="text/javascript">
    
$(document).ready(function() {
  function format ( d ) {
    var html = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
                '<thead>' +  
                    '<tr>' +
                        '<th> </th>' +
                        '<th>Result</th>' +
                        '<th>Notes</th>' +
                    '</tr>' +
                '</thead>';
    for (i=0; i<d.results.length; i++) {
        result = d.results[i];

        html += '<tr>' +
                    '<td></td>' +
                    '<td class="text-left">' + result.result + '</td>' +
                    '<td class="text-left">' + result.note + '</td>' +
                '</tr>';
        
    }



    return html;
  }
  
  var data = [
    {
      "fname": 'Test 1',
      "date": '06/15/2018',
      "description": 'This is test 1',
      results: [
        {
          "result": 'passed',
          "note": 'note 1'
        }
      ]
     },
    {
      fname: 'Test 2',
      date: '10/31/2018',
      description: 'This is test 2',
      results: [
        {
          result: 'failed 1',
          note: 'note 2a'
        },
        {
          result: 'failed 2',
          note: 'note 2b'
        }
      ]
    },
    {
      fname: 'Test 3',
      date: '03/05/2018',
      description: 'This is test 3',
      results: [
     
      ]
    },
  ];

  // var data=[{"rwserialid":15872,"pid":440249191,"description":"74","vital_unit":"beats\/minute","date":"08-02-2021 05:45:10","reviewedflag":0,"rwaddressed":0,"alert":0,"vitaldeviceid":3,"name":"Michael","plname":"Palmer","pmname":"","pdob":"1947-01-30","pprofileimg":"","practicename":null,"providername":null,"caremanagerfname":null,"caremanagerlname":null,"rppracticeid":null,"vital_threshold":"60-100","rwthreshold_type":null,"results":[],"childdatacount":0},{"rwserialid":12007,"pid":440249191,"description":"117\/69","vital_unit":"mm[Hg]","date":"08-02-2021 05:45:10","reviewedflag":0,"rwaddressed":0,"alert":0,"vitaldeviceid":3,"name":"Michael","plname":"Palmer","pmname":"","pdob":"1947-01-30","pprofileimg":"","practicename":null,"providername":null,"caremanagerfname":null,"caremanagerlname":null,"rppracticeid":null,"vital_threshold":"100-140\/60-100","rwthreshold_type":null,"results":[],"childdatacount":0},{"rwserialid":15873,"pid":337756499,"description":"80","vital_unit":"beats\/minute","date":"08-02-2021 06:54:48","reviewedflag":0,"rwaddressed":0,"alert":0,"vitaldeviceid":3,"name":"Martha","plname":"Line","pmname":"E","pdob":"1939-09-24","pprofileimg":"","practicename":null,"providername":null,"caremanagerfname":null,"caremanagerlname":null,"rppracticeid":null,"vital_threshold":"60-100","rwthreshold_type":null,"results":[],"childdatacount":0},{"rwserialid":12008,"pid":337756499,"description":"147\/68","vital_unit":"mm[Hg]","date":"08-02-2021 06:54:48","reviewedflag":0,"rwaddressed":0,"alert":0,"vitaldeviceid":3,"name":"Martha","plname":"Line","pmname":"E","pdob":"1939-09-24","pprofileimg":"","practicename":null,"providername":null,"caremanagerfname":null,"caremanagerlname":null,"rppracticeid":null,"vital_threshold":"130-170\/60-100","rwthreshold_type":null,"results":[],"childdatacount":0},{"rwserialid":346,"pid":1731602886,"description":"154","vital_unit":"lbs","date":"08-02-2021 07:24:52","reviewedflag":0,"rwaddressed":0,"alert":0,"vitaldeviceid":1,"name":"Patricia","plname":"Stevenson","pmname":"","pdob":"1955-08-31","pprofileimg":"","practicename":null,"providername":null,"caremanagerfname":null,"caremanagerlname":null,"rppracticeid":null,"vital_threshold":"140-160","rwthreshold_type":null,"results":[],"childdatacount":0},{"rwserialid":3952,"pid":250514671,"description":"94","vital_unit":"%","date":"08-02-2021 07:33:12","reviewedflag":0,"rwaddressed":0,"alert":0,"vitaldeviceid":2,"name":"Alice","plname":"Dolinger","pmname":null,"pdob":"1972-06-06","pprofileimg":null,"practicename":"HMG - Weber City","providername":null,"caremanagerfname":"Kesi","caremanagerlname":"Vandyke","rppracticeid":56,"vital_threshold":"92-100","rwthreshold_type":null,"results":[],"childdatacount":0},{"rwserialid":15874,"pid":250514671,"description":"77","vital_unit":"beats\/minute","date":"08-02-2021 07:33:12","reviewedflag":0,"rwaddressed":0,"alert":0,"vitaldeviceid":2,"name":"Alice","plname":"Dolinger","pmname":null,"pdob":"1972-06-06","pprofileimg":null,"practicename":"HMG - Weber City","providername":null,"caremanagerfname":"Kesi","caremanagerlname":"Vandyke","rppracticeid":56,"vital_threshold":"60-100","rwthreshold_type":null,"results":[],"childdatacount":0},{"rwserialid":3953,"pid":846545805,"description":"91","vital_unit":"%","date":"08-02-2021 12:06:43","reviewedflag":0,"rwaddressed":0,"alert":0,"vitaldeviceid":2,"name":"April","plname":"Clifton","pmname":null,"pdob":"1974-04-22","pprofileimg":null,"practicename":"HMG - Duffield","providername":"Roach Stephanie FNP","caremanagerfname":null,"caremanagerlname":null,"rppracticeid":52,"vital_threshold":"90-100","rwthreshold_type":null,"results":[],"childdatacount":0},{"rwserialid":15875,"pid":846545805,"description":"92","vital_unit":"beats\/minute","date":"08-02-2021 12:06:43","reviewedflag":0,"rwaddressed":0,"alert":0,"vitaldeviceid":2,"name":"April","plname":"Clifton","pmname":null,"pdob":"1974-04-22","pprofileimg":null,"practicename":"HMG - Duffield","providername":"Roach Stephanie FNP","caremanagerfname":null,"caremanagerlname":null,"rppracticeid":52,"vital_threshold":"60-100","rwthreshold_type":null,"results":[],"childdatacount":0},{"rwserialid":15876,"pid":1605781862,"description":"64","vital_unit":"beats\/minute","date":"08-06-2021 16:33:45","reviewedflag":0,"rwaddressed":0,"alert":0,"vitaldeviceid":3,"name":"Ronnie","plname":"Waddell","pmname":"D","pdob":"1957-04-27","pprofileimg":null,"practicename":"HMG - Duffield","providername":null,"caremanagerfname":null,"caremanagerlname":null,"rppracticeid":52,"vital_threshold":"60-100","rwthreshold_type":null,"results":[],"childdatacount":0}];

  var table = $('#example').DataTable({
    data: data,
    columns: [
      {
        data: 'fname'
      },
      {
        data: 'date'
      },
      {
        data: 'description'
      },
      {
        data: null,
        visible: false,
        render: function (data, type, row, meta) {
          return meta.row;
        }
      }
    ],
    dom: 'Bfrtip',
    buttons: [{
      extend: 'excelHtml5',
      customize: function( xlsx ) {
        var table = $('#example').DataTable();
        
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
        var DT_row;        // Row count in Excel sheet.

        var rowCount = 1;
        
        // Itereate each row in the sheet data.
        $(sheetData).children().each(function(index) {

          // Used for DT row() API to get child data.
          var rowIndex = index - 1;
          
          // Don't process row if its the header row.
          if (index > 0) {
            
            // Get row
            var row = $(this.outerHTML);
          
            // Set the Excel row attr to the current Excel row count.
            row.attr('r', rowCount);
            
            var colCount = 1;
            
            // Iterate each cell in the row to change the rwo number.
            row.children().each(function(index) {
              var cell = $(this);
            
              // Set each cell's row value.
              var rc = cell.attr('r');
              rc = rc.replace(/\d+$/, "") + rowCount;
              cell.attr('r', rc);         
              console.log(colCount+" === "+numColumns);
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
            var childData = table.row(DT_row, {search: 'none', order: 'index'}).data().results;
            
            if (childData.length > 0) {
              // Prepare Excel formated row
              headerRow = '<row r="' + rowCount + 
                        '" s="2"><c t="inlineStr" r="A' + rowCount + 
                        '"><is><t>' + 
                        '</t></is></c><c t="inlineStr" r="B' + rowCount + 
                        '" s="2"><is><t>Result' + 
                        '</t></is></c><c t="inlineStr" r="C' + rowCount + 
                        '" s="2"><is><t>Notes' + 
                        '</t></is></c></row>';
              
              // Append header row to sheetData.
              $('sheetData', sheet).append(headerRow);
              rowCount++; // Inc excelt row counter.
               
            }
            
            // The child data is an array of rows
            for (c=0; c<childData.length; c++) {
              
              // Get row data.
              child = childData[c];
              
              // Prepare Excel formated row
              childRow = '<row r="' + rowCount + 
                        '"><c t="inlineStr" r="A' + rowCount + 
                        '"><is><t>' + 
                        '</t></is></c><c t="inlineStr" r="B' + rowCount + 
                        '"><is><t>' + child.result + 
                        '</t></is></c><c t="inlineStr" r="C' + rowCount + 
                        '"><is><t>' + child.note + 
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
    }]
  });

    $('#example').on('click', 'tbody td', function () {

        var tr = $(this).closest('tr');
        var row = table.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            var data = row.data();

            row.child( format(data) ).show();
            tr.addClass('shown');
        }
    } );


});
 
    </script>
@endsection