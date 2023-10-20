/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 12);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/laravel/js/rpmworklist.js":
/*!*********************************************!*\
  !*** ./resources/laravel/js/rpmworklist.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var baseURL = window.location.origin + '/';

var renderDataTableExport = function renderDataTableExport(tabid, url, columnData, assetBaseUrl) {
  var copyflag = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : "0";
  var copyTo = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : '';
  var rows_selected = [];
  var copy_img = "assets/images/copy_icon.png";
  var excel_img = "assets/images/excel_icon.png";
  var pdf_img = "assets/images/pdf_icon.png";
  var csv_img = "assets/images/csv_icon.png";
  var table = $('#' + tabid).DataTable({
    "dom": '<"float-right"B><"float-right"f><"float-left"r><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
    // dom     : '<"float-right"B><"clearfix"><"navbar-text"><"float-left"lr><"float-right"f><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
    // dom     : '<"navbar-text"><"float-left"lr><"float-right"f><"float-right"B><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
    buttons: [{
      extend: 'copyHtml5',
      text: '<img src="' + assetBaseUrl + copy_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Copy">'
    }, {
      extend: 'excelHtml5',
      text: '<img src="' + assetBaseUrl + excel_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Excel">',
      titleAttr: 'Excel',
      customize: function customize(xlsx) {
        // Get number of columns to remove last hidden index column.
        var numColumns = table.columns().header().count(); // Get sheet.

        var sheet = xlsx.xl.worksheets['sheet1.xml'];
        var col = $('col', sheet); // Set the column width.

        $(col[1]).attr('width', 20); // Get a clone of the sheet data.        

        var sheetData = $('sheetData', sheet).clone(); // Clear the current sheet data for appending rows.

        $('sheetData', sheet).empty(); // Row index from last column.

        var DT_row; // Row count in Excel sheet.

        var rowCount = 1; // Itereate each row in the sheet data.

        $(sheetData).children().each(function (index) {
          // Used for DT row() API to get child data.
          var rowIndex = index - 1; // Don't process row if its the header row.

          if (index > 0) {
            // Get row
            var row = $(this.outerHTML); // Set the Excel row attr to the current Excel row count.

            row.attr('r', rowCount);
            var colCount = 1; // Iterate each cell in the row to change the rwo number.

            row.children().each(function (index) {
              var cell = $(this); // Set each cell's row value.

              var rc = cell.attr('r');
              rc = rc.replace(/\d+$/, "") + rowCount;
              cell.attr('r', rc);

              if (colCount === numColumns) {
                DT_row = cell.text();
                cell.html('');
              }

              colCount++;
            }); // Get the row HTML and append to sheetData.

            row = row[0].outerHTML;
            $('sheetData', sheet).append(row);
            rowCount++; // Get the child data - could be any data attached to the row.

            var childData = table.row(DT_row, {
              search: 'none',
              order: 'index'
            }).data().results;

            if (childData.length > 0) {
              // Prepare Excel formated row
              headerRow = '<row r="' + rowCount + '" s="2"><c t="inlineStr" r="A' + rowCount + '"><is><t>' + '</t></is></c><c t="inlineStr" r="B' + rowCount + '" s="2"><is><t>Sr.No.' + '</t></is></c><c t="inlineStr" r="C' + rowCount + '" s="2"><is><t>Patient' + '</t></is></c><c t="inlineStr" r="D' + rowCount + '" s="2"><is><t>DOB' + '</t></is></c><c t="inlineStr" r="E' + rowCount + '" s="2"><is><t>Clinic' + '</t></is></c><c t="inlineStr" r="F' + rowCount + '" s="2"><is><t>Provider' + '</t></is></c><c t="inlineStr" r="G' + rowCount + '" s="2"><is><t>Care Manager' + '</t></is></c><c t="inlineStr" r="H' + rowCount + '" s="2"><is><t>Device' + '</t></is></c><c t="inlineStr" r="I' + rowCount + '" s="2"><is><t>Range' + '</t></is></c><c t="inlineStr" r="J' + rowCount + '" s="2"><is><t>Reading' + '</t></is></c><c t="inlineStr" r="K' + rowCount + '" s="2"><is><t>Date' + '</t></is></c><c t="inlineStr" r="L' + rowCount + '" s="2"><is><t>Time' + '</t></is></c><c t="inlineStr" r="M' + rowCount + '" s="2"><is><t>Review' + '</t></is></c><c t="inlineStr" r="N' + rowCount + '" s="2"><is><t>Addressed' + '</t></is></c></row>'; // Append header row to sheetData.

              $('sheetData', sheet).append(headerRow);
              rowCount++; // Inc excelt row counter.
            } // The child data is an array of rows


            for (c = 0; c < childData.length; c++) {
              // Get row data.
              child = childData[c];
              var r_unit = child.unit;

              if (child.unit == null) {
                r_unit = '';
                var vital = ''; // return 'Weight';
              } else {
                if (r_unit == '%') {
                  // return 'Pulse Oximeter';
                  var vital = 'Oxygen';
                } else if (r_unit == 'beats/minute') {
                  // var vital= 'Spirometer';
                  var vital = 'Heartrate';
                } else if (r_unit == 'mm[Hg]' || r_unit == 'mmHg') {
                  //mmHg
                  // var vital= 'Blood Pressure Cuff'
                  var vital = 'Blood Pressure';
                } else if (r_unit == 'mg/dl') {
                  var vital = 'Glucose';
                } else if (r_unit == 'L' || r_unit == 'L/min') {
                  var vital = 'FEV1 and PEF';
                } else if (r_unit == 'degrees F') {
                  var vital = 'Temperature';
                } else {
                  var vital = 'Weight';
                }
              }

              if (child.thresholdtype == null) {
                //    var  vitalthreshold = child.vitalthreshold+" "+"( )";
                var vitalthreshold = " ";
              } else {
                var vitalthreshold = child.vitalthreshold + " " + "(" + child.thresholdtype + ")";
              }

              var totaltime = child.csseffdate;
              var dateonly = totaltime.split(" ");
              var reviewedflag = child.reviewedflag;

              if (reviewedflag == "1") {
                reviewedflag = "Yes";
              } else {
                reviewedflag = "No";
              }

              var rwaddressed = child.rwaddressed;

              if (rwaddressed == "1") {
                rwaddressed = "Yes";
              } else {
                rwaddressed = "No";
              }

              if (child.caremanagerfname == null || child.caremanagerfname == undefined) {
                var caremanager = '';
              } else {
                var caremanager = child.caremanagerfname + " " + child.caremanagerlname;
              }

              var srno = c + 1; // Prepare Excel formated row

              childRow = '<row r="' + rowCount + '"><c t="inlineStr" r="A' + rowCount + '"><is><t>' + '</t></is></c><c t="inlineStr" r="B' + rowCount + '"><is><t>' + srno + '</t></is></c><c t="inlineStr" r="C' + rowCount + '"><is><t>' + child.pfname + " " + child.plname + '</t></is></c><c t="inlineStr" r="D' + rowCount + '"><is><t>' + child.pdob + '</t></is></c><c t="inlineStr" r="E' + rowCount + '"><is><t>' + child.practicename + '</t></is></c><c t="inlineStr" r="F' + rowCount + '"><is><t>' + child.providername + '</t></is></c><c t="inlineStr" r="G' + rowCount + '"><is><t>' + caremanager + '</t></is></c><c t="inlineStr" r="H' + rowCount + '"><is><t>' + vital + '</t></is></c><c t="inlineStr" r="I' + rowCount + '"><is><t>' + vitalthreshold + '</t></is></c><c t="inlineStr" r="J' + rowCount + '"><is><t>' + child.reading + " " + child.unit + '</t></is></c><c t="inlineStr" r="K' + rowCount + '"><is><t>' + dateonly[0] + '</t></is></c><c t="inlineStr" r="L' + rowCount + '"><is><t>' + dateonly[1] + '</t></is></c><c t="inlineStr" r="M' + rowCount + '"><is><t>' + reviewedflag + '</t></is></c><c t="inlineStr" r="N' + rowCount + '"><is><t>' + rwaddressed + '</t></is></c></row>'; // Append row to sheetData.

              $('sheetData', sheet).append(childRow);
              rowCount++; // Inc excelt row counter.
            } // Just append the header row and increment the excel row counter.

          } else {
            var row = $(this.outerHTML);
            var colCount = 1; // Remove the last header cell.

            row.children().each(function (index) {
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
      }
    }, {
      extend: 'csvHtml5',
      text: '<img src="' + assetBaseUrl + csv_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="CSV">',
      titleAttr: 'CSV',
      fieldSeparator: '\|'
    }, {
      extend: 'pdfHtml5',
      text: '<img src="' + assetBaseUrl + pdf_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="PDF">',
      titleAttr: 'PDF'
    }],
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
    'rowCallback': function rowCallback(row, data, dataIndex) {
      // Get row ID
      var rowId = data[0]; // If row ID is in the list of selected row IDs

      if ($.inArray(rowId, rows_selected) !== -1) {
        $(row).find('input[type="checkbox"]').prop('checked', true);
        $(row).addClass('selected');
      }
    },
    "columnDefs": [{
      "targets": '_all',
      "defaultContent": ""
    }]
    /*,
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
};

var getDailyReviewPatientList = function getDailyReviewPatientList() {
  var practicesgrp = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
  var practices = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
  var provider = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
  var patient = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
  var caremanagerid = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : null;
  var fromdate = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : null;
  var todate = arguments.length > 6 && arguments[6] !== undefined ? arguments[6] : null;
  var reviewedstatus = arguments.length > 7 && arguments[7] !== undefined ? arguments[7] : null;
  var columns = [{
    data: 'DT_RowIndex',
    name: 'DT_RowIndex'
  }, {
    data: 'pfname',
    name: 'pfname',
    mRender: function mRender(data, type, full, meta) {
      m_Name = full['pmname'];

      if (full['pmname'] == null) {
        m_Name = '';
      }

      if (data != '' && data != 'NULL' && data != undefined) {
        return ["<a href='/rpm/monthly-monitoring/" + full['pid'] + "'>"] + ' ' + full['pfname'] + ' ' + m_Name + ' ' + full['plname'] + '</a>';
      }
    },
    orderable: false
  }, {
    data: 'pfname',
    name: 'pfname',
    mRender: function mRender(data, type, full, meta) {
      m_Name = full['pmname'];

      if (full['pmname'] == null) {
        m_Name = '';
      }

      if (data != '' && data != 'NULL' && data != undefined) {
        return ["<a href='/rpm/daily-review/" + full['pid'] + "/" + full['vitaldeviceid'] + "'>"] + '<button type="button" class="btn btn-primary">Click</button></a>';
      }
    },
    orderable: false
  }, {
    data: 'pdob',
    type: 'date-dd-mmm-yyyy',
    name: 'pdob',
    "render": function render(value) {
      if (value === null) return "";
      return moment(value).format('MM-DD-YYYY');
    }
  }, {
    data: 'practicename',
    name: 'practicename',
    mRender: function mRender(data, type, full, meta) {
      practice_name = full['practicename'];

      if (full['practicename'] == null) {
        practice_name = '';
      } else {
        return practice_name;
      }
    },
    orderable: false
  }, {
    data: 'vital_unit',
    name: 'unit',
    mRender: function mRender(data, type, full, meta) {
      if (full['vital_unit'] == 'beats/minute') {
        return 'Heartrate';
      } else {
        return full['vital_name'];
      }
      /* if(full['vital_unit'] == null){
           r_unit = '';
       }
       else{
           if(r_unit == '%'){
               // return 'Pulse Oximeter';
               return 'Oxygen';
           }
           else if(r_unit == 'beats/minute'){
               // return 'Spirometer';
               return 'Heartrate';
           }
           else if(r_unit == 'mm[Hg]' ){
               //mmHg
               // return 'Blood Pressure Cuff'
               return 'Blood Pressure';
           }
           else if(r_unit == 'mg/dl'){
               return 'Glucose';
           }
           else if(r_unit == 'L' || r_unit == 'L/min'){
               return 'FEV1 and PEF';
           }
           else if(r_unit == 'degrees F'){
               return 'Temperature';
           }
           else{
               return 'Weight'
           }
       }*/

    },
    orderable: false
  }, // { data: null,
  //     mRender: function(data, type, full, meta){
  //         thres = full['thresholdrange'];
  //             if(full['thresholdrange'] == null){
  //                 thres = '';
  //         }
  //         if(thres!='' && thres!='NULL' && thres!=undefined){
  //             return thres;
  //         }
  //     },
  //     orderable: false
  // },
  {
    data: 'vital_threshold',
    name: 'vital_threshold',
    mRender: function mRender(data, type, full, meta) {
      if (full['rwthreshold_type'] == null) {
        // vitalthreshold = full['vital_threshold']+" "+"("+" "+")";
        var vitalthreshold = " ";
        return vitalthreshold;
      } else {
        vitalthreshold = full['vital_threshold'] + " " + "(" + full['rwthreshold_type'] + ")";
        return vitalthreshold;
      }
    },
    orderable: false
  }, {
    data: 'reading',
    name: 'reading',
    mRender: function mRender(data, type, full, meta) {
      m_reading = full['reading'];
      m_unit = full['vital_unit'];
      m_alert = full['alert']; // if(full['alert'] == null){
      //     m_alert = '';
      // }

      if (full['reading'] == null) {
        m_reading = 'No Vital taken for 3 days';
        return "<span style='color:red'>" + m_reading + '<i class="i-Danger" style="color:red"></i>' + "<span>";
      } else {
        if (m_alert == 1) {
          return "<span style='color:red'>" + m_reading + ' ' + m_unit + '<i class="i-Danger" style="color:red"></i>' + "<span>";
        } else {
          return m_reading + " " + m_unit;
        }
      }
    },
    orderable: false
  }, // {data: 'csseffdate',type:'dd-mm-yyyy h:i:s', name:'csseffdate',"render":function (value) {
  //     if (value === null) return "";
  //         return util.viewsDateFormat(value);  
  //     }
  // },  
  {
    data: null,
    mRender: function mRender(data, type, full, meta) {
      totaltime = full['csseffdate'];

      if (totaltime != null) {
        var dateonly = totaltime.split(" ");

        if (full['csseffdate'] == null) {
          dateonly = '';
        }

        if (full['csseffdate'] != '' && full['csseffdate'] != 'NULL' && full['csseffdate'] != undefined) {
          return dateonly[0];
        }
      } else {
        return null;
      }
    },
    orderable: true
  }, {
    data: null,
    mRender: function mRender(data, type, full, meta) {
      totaltime = full['csseffdate'];

      if (totaltime != null) {
        var timeonly = totaltime.split(" ");

        if (full['csseffdate'] == null) {
          timeonly = '';
        }

        if (full['csseffdate'] != '' && full['csseffdate'] != 'NULL' && full['csseffdate'] != undefined) {
          return timeonly[1];
        }
      } else {
        return null;
      }
    },
    orderable: true
  }, {
    data: null,
    'render': function render(data, type, full, meta) {
      if (full['reviewedflag'] == null || full['reviewedflag'] == 0) {
        check = '';
        reviewstatus = "No";
      } else {
        check = 'checked';
        reviewstatus = "Yes";
      }

      return '<input type="checkbox" id="reviewpatientstatus_' + full['pid'] + '" class="reviewpatientstatus" name="reviewpatientstatus" value="' + full['pid'] + '" ' + check + '><label style="display:none">' + reviewstatus + '</label>';
    },
    orderable: false
  }, {
    data: null,
    mRender: function mRender(data, type, full, meta) {
      if (full['rwaddressed'] == null || full['rwaddressed'] == 0) {
        check = '';
        readonly = '';
        addressstatus = 'No';
      } else {
        check = 'checked';
        readonly = 'disabled';
        addressstatus = 'Yes';
      }

      if (full['alert'] == 1) {
        return '<input type="checkbox" id="activealertpatientstatus_' + meta.row + '_' + full['pid'] + '" class="patientaddressed" name="patientaddressed"  value="' + full['pid'] + '" ' + check + '  ' + readonly + ' ><label style="display:none">' + addressstatus + '</label>';
      }
      /*  addressed = full['rwaddressed'];     
        if(full['rwaddressed'] == 1){ 
            check = 'checked';
            reviewstatus="Yes";
            addressed = '<input type="checkbox" id="activealertpatientstatus_'+meta.row+'_'+full['pid']+'" class="patientaddressed" name="patientaddressed" value="'+full['pid']+'" '+check+'><label style="display:none">'+reviewstatus+'</label>';
        }
        else{
            check = '';
             reviewstatus="No";
            addressed = '<input type="checkbox" id="activealertpatientstatus_'+meta.row+'_'+full['pid']+'" class="patientaddressed" name="patientaddressed" value="'+full['pid']+'" '+check+'><label style="display:none">'+reviewstatus+'</label>';
        }
        return addressed;*/

    },
    orderable: true
  }, // { data: 'csslastdate',name: 'csslastdate',
  //     mRender: function(data, type, full, meta){
  //         csslastdate = full['csslastdate'];
  //             if(full['csslastdate'] == null){
  //                 csslastdate = '';
  //         }
  //         else{
  //             return csslastdate;
  //         }
  //     },
  //     orderable: false
  // }, 
  {
    data: 'csslastdate',
    name: 'csslastdate',
    "render": function render(value) {
      if (value === null) return "";
      return moment(value).format('MM-DD-YYYY');
    },
    orderable: true
  }, {
    data: 'providername',
    name: 'providername',
    mRender: function mRender(data, type, full, meta) {
      providername = full['providername'];

      if (full['providername'] == null) {
        providername = '';
      } else {
        return providername;
      }
    },
    orderable: false
  }, {
    data: 'caremanagerfname',
    name: 'caremanagerfname',
    mRender: function mRender(data, type, full, meta) {
      caremanagerfname = full['caremanagerfname'];

      if (full['caremanagerfname'] == null) {
        caremanagerfname = '';
      } else {
        return full['caremanagerfname'] + ' ' + full['caremanagerlname'];
      }
    },
    orderable: false
  }, {
    data: 'action',
    name: 'action'
  }, {
    data: null,
    visible: false,
    render: function render(data, type, row, meta) {
      return meta.row;
    }
  } // demo for chart 17-06-2021
  // {data: null, 'render': function (data, type, full, meta){
  //        // ["<a href='/rpm/daily-reading/"+full['pid']+"/"+unitval+"'><img src='http://rcareproto2.d-insights.global/assets/images/faces/avatar.png' width='50px' class='user-image' />"]+' '+full['pfname']+' '+m_Name+' '+full['plname']+'</a>';
  //        //  return ["<a href= "{{ route('read-chart') }}" hi </a>";
  //        abc = '<input type ="button" value="Click Me">';
  //        return abc;
  //      },
  //      orderable: false
  // }
  //{ data: 'action', name: 'action', orderable: false, searchable: false}
  ];

  if (practicesgrp == '') {
    practicesgrp = null;
  }

  if (practices == '') {
    practices = null;
  }

  if (provider == '') {
    provider = null;
  }

  if (patient == '') {
    patient = null;
  }

  if (caremanagerid == '') {
    caremanagerid = null;
  }

  if (fromdate == '') {
    fromdate = null;
  }

  if (todate == '') {
    todate = null;
  }

  if (reviewedstatus == '' || reviewedstatus == null) {
    reviewedstatus = 0;
  }

  var url = "/rpm/daily-review-list/search/" + practicesgrp + "/" + practices + "/" + provider + "/" + patient + "/" + caremanagerid + "/" + fromdate + "/" + todate + "/" + reviewedstatus; // console.log(url);

  var parenttable = renderDataTableExport('dailyreviewlist', url, columns, baseURL); // Updates "Select all" control in a data table

  function updateDataTableSelectAllCtrl(table) {
    var $table = parenttable.table().node();
    var $chkbox_all = $('tbody input[type="checkbox"]', $table);
    var $chkbox_checked = $('tbody input[type="checkbox"]:checked', $table);
    var chkbox_select_all = $('thead input[name="select_all"]', $table).get(0); // If none of the checkboxes are checked

    if ($chkbox_checked.length === 0) {
      chkbox_select_all.checked = false;

      if ('indeterminate' in chkbox_select_all) {
        chkbox_select_all.indeterminate = false;
      } // If all of the checkboxes are checked

    } else if ($chkbox_checked.length === $chkbox_all.length) {
      alert($chkbox_checked.length);
      chkbox_select_all.checked = true;

      if ('indeterminate' in chkbox_select_all) {
        chkbox_select_all.indeterminate = false;
      } // If some of the checkboxes are checked

    } else {
      alert("some");
      chkbox_select_all.checked = true;

      if ('indeterminate' in chkbox_select_all) {
        chkbox_select_all.indeterminate = true;
      }
    }
  }

  $('#example-select-all').on('click', function () {
    // alert("helllo");                  
    // var rows = parenttable.rows().nodes();
    var $table = parenttable.table().node();
    var rows = parenttable.rows({
      page: 'current'
    }).nodes();
    $('input[type="checkbox"]', rows).prop('checked', this.checked); // var $chkbox_all = $('tbody input[type="checkbox"]', $table);
    // var $chkbox_checked = $('tbody input[type="checkbox"]:checked', $table);
    // var chkbox_select_all = $('thead input[name="select_all"]', $table).get(0);
    // // If none of the checkboxes are checked
    // if ($chkbox_checked.length === 0) {
    //     alert("within");
    //     chkbox_select_all.checked = false;
    //     if ('indeterminate' in chkbox_select_all) {
    //     $('input[type="checkbox"]', table.cells().nodes()).prop('checked',false); 
    //     chkbox_select_all.indeterminate = false;
    //     }
    //     // If all of the checkboxes are checked
    // } else if ($chkbox_checked.length === $chkbox_all.length) {
    //     alert($chkbox_checked.length);
    //     chkbox_select_all.checked = true;
    //     if ('indeterminate' in chkbox_select_all) {
    //         $('input[type="checkbox"]', table.cells().nodes()).prop('checked',true); 
    //         // $('thead input[name="select_all"]', $table).prop('checked', false);
    //         chkbox_select_all.indeterminate = false;
    //     }
    //     // If some of the checkboxes are checked
    // } else {
    //     chkbox_select_all.checked = true;
    //     alert("else");
    //     alert($chkbox_checked.length);
    //     if ('indeterminate' in chkbox_select_all) {
    //     $('input[type="checkbox"]', table.cells().nodes()).prop('checked',false); 
    //     chkbox_select_all.indeterminate = true;
    //     }
    // }
    // Update state of "Select all" control

    updateDataTableSelectAllCtrl(parenttable);
  }); //  // Handle click on table cells with checkboxes
  //     $('#dailyreviewlist').on('click', 'tbody td, thead th:first-child', function(e){
  //         alert("dsfsfs");
  //         $(this).parent().find('input[type="checkbox"]').trigger('click');
  //     });
  // Handle click on "Select all" control

  $('thead input[name="select_all"]', parenttable.table().container()).on('click', function (e) {
    if (this.checked) {
      $('#dailyreviewlist tbody input[type="checkbox"]:not(:checked)').trigger('click');
    } else {
      $('#dailyreviewlist tbody input[type="checkbox"]:checked').trigger('click');
    } // Prevent click event from propagating to parent


    e.stopPropagation();
  }); // Handle table draw event

  parenttable.on('draw', function () {
    // Update state of "Select all" control
    updateDataTableSelectAllCtrl(table);
  });
  $('#example-select-all').click(function () {
    // alert("i am called");
    var checkbox_value = [];
    var notcheckbox_value = []; // var rowcollection =  parenttable.$("input[type='checkbox']:checked",{"page": "all"});
    // var rowcollection1 =  parenttable.$("input:checkbox:not(:checked)",{"page": "all"});

    var rowcollection = parenttable.$("input[type='checkbox']:checked", {
      "page": "current"
    });
    var rowcollection1 = parenttable.$("input:checkbox:not(:checked)", {
      "page": "current"
    }); // console.log(rowcollection);

    rowcollection.each(function (index, elem) {
      // console.log(elem);
      var row = $(this);
      var rowdata = parenttable.row($(this).parents('tr')).data(); // console.log(rowdata);

      var patient_id = rowdata.pid;
      var serialid = rowdata.rwserialid;
      var childdatacount = rowdata.childdatacount;
      var component_id = $('#component_id').val();
      var stage_id = $('#stage_id').val();
      var step_id = $('#step_id').val(); // var componentid = '<?php echo $component_id; ?>';
      // if(reviewstatus == "Yes"){
      //     var newreviewstatus = 1;
      // }
      // else{
      //     var newreviewstatus = 0;
      // }

      if ($(this).is(":checked")) {
        var reviewstatus = 1;
      } else {
        var reviewstatus = 0;
      }

      var reviewdata1 = {
        patient_id: rowdata.pid,
        unit: rowdata.vital_unit,
        csseffdate: rowdata.csseffdate,
        reviewstatus: reviewstatus,
        component_id: component_id,
        serialid: serialid,
        vital_name: rowdata.vital_name,
        reading: rowdata.reading,
        vitaldeviceid: rowdata.vitaldeviceid,
        table: 'parent',
        formname: 'RPMWorklistreviewedcompleted',
        stage_id: stage_id,
        step_id: step_id
      };
      console.log(reviewdata1);
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type: 'POST',
        url: '/rpm/daily-review-updatereviewstatus',
        data: reviewdata1,
        success: function success(data) {
          var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Review Status Updated Successfully!</strong></div>';
          $("#success").html(txt);
          util.totalTimeSpentByCM();
          util.updateTimer(patient_id, 1, component_id);
          var scrollPos = $(".main-content").offset().top;
          $(window).scrollTop(scrollPos);
          setTimeout(function () {
            $("#success").hide();
          }, 3000);
        }
      });
      checkbox_value.push($(elem).val());
    });
    rowcollection1.each(function (index, elem) {
      notcheckbox_value.push($(elem).val());
    }); // var modules=$('#modules').val();
    //    console.log(checkbox_value);
    //      var data = {
    //     patient_id: checkbox_value,
    //     uncheckedpatient_id: notcheckbox_value,
    //     module_id: modules         
    // }
    //      $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    // });
    // $.ajax({
    //     type: 'POST',
    //     url: '/reports/billupdate',
    //     data: data,
    //     success: function (data) {
    //         console.log("save cuccess");
    //         getCareManagerList();
    //     }
    // });
  });
  return parenttable;
};

var getReviewDailyChildList = function getReviewDailyChildList() {
  var row = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
  var $Find1 = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
  var patient_id = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
  var fromdate = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
  var unittable = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : null;
  var reviewedstatus = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : null;
  var serialid = arguments.length > 6 && arguments[6] !== undefined ? arguments[6] : null;
  var columns = [{
    data: 'DT_RowIndex',
    name: 'DT_RowIndex'
  }, // { data: 'pfname',name: 'pfname',
  //     mRender: function(data, type, full, meta){
  //         m_Name = full['pmname'];
  //             if(full['pmname'] == null){
  //                 m_Name = '';
  //             }
  //          r_unit = full['unit'];
  //         if(data!='' && data!='NULL' && data!=undefined){
  //             return ["<a href='/rpm/daily-review/"+full['pid']+"/"+full['deviceid']+"'>"]+' '+full['pfname']+' '+m_Name+' '+full['plname']+'</a>';
  //             // return ["<a href='/rpm/daily-reading/"+full['pid']'><img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['pfname']+' '+m_Name+' '+full['plname']+'</a>';
  //         }
  //     },
  //     orderable: false
  // },
  {
    data: 'pfname',
    name: 'pfname',
    mRender: function mRender(data, type, full, meta) {
      m_Name = full['pmname'];

      if (full['pmname'] == null) {
        m_Name = '';
      }

      if (data != '' && data != 'NULL' && data != undefined) {
        return ["<a href='/rpm/monthly-monitoring/" + full['pid'] + "'>"] + ' ' + full['pfname'] + ' ' + m_Name + ' ' + full['plname'] + '</a>';
      }
    },
    orderable: false
  }, {
    data: 'pfname',
    name: 'pfname',
    mRender: function mRender(data, type, full, meta) {
      m_Name = full['pmname'];

      if (full['pmname'] == null) {
        m_Name = '';
      }

      if (data != '' && data != 'NULL' && data != undefined) {
        return ["<a href='/rpm/daily-review/" + full['pid'] + "/" + full['deviceid'] + "'>"] + '<button type="button" class="btn btn-primary">Click</button></a>';
      }
    },
    orderable: false
  }, {
    data: 'pdob',
    type: 'date-dd-mmm-yyyy',
    name: 'pdob',
    "render": function render(value) {
      if (value === null) return "";
      return moment(value).format('MM-DD-YYYY');
    }
  }, {
    data: 'practicename',
    name: 'practicename',
    mRender: function mRender(data, type, full, meta) {
      practice_name = full['practicename'];

      if (full['practicename'] == null) {
        practice_name = '';
      } else {
        return practice_name;
      }
    },
    orderable: false
  }, {
    data: 'providername',
    name: 'providername',
    mRender: function mRender(data, type, full, meta) {
      providername = full['providername'];

      if (full['providername'] == null) {
        providername = '';
      } else {
        return providername;
      }
    },
    orderable: false
  }, {
    data: 'caremanagerfname',
    name: 'caremanagerfname',
    mRender: function mRender(data, type, full, meta) {
      caremanagerfname = full['caremanagerfname'];

      if (full['caremanagerfname'] == null) {
        caremanagerfname = '';
      } else {
        return full['caremanagerfname'] + ' ' + full['caremanagerlname'];
      }
    },
    orderable: false
  }, {
    data: 'unit',
    name: 'unit',
    mRender: function mRender(data, type, full, meta) {
      // r_unit = full['unit'];
      if (full['unit'] == 'beats/minute') {
        return 'Heartrate';
      } else {
        return full['vital_name'];
      }
      /* //  alert(r_unit)
             if(full['unit'] == null){
                 r_unit = '';
             }
              else{
                 if(r_unit == '%'){
                     // return 'Pulse Oximeter';
                     return 'Oxygen';
                 }
                 else if(r_unit == 'beats/minute'){
                     // return 'Spirometer';
                     return 'Heartrate';
                 }
                 else if(r_unit == 'mm[Hg]' || r_unit == 'mmHg' ){
                     //mmHg
                     // return 'Blood Pressure Cuff'
                     return 'Blood Pressure';
                 }
                 else if(r_unit == 'mg/dl'){
                     return 'Glucose';
                 }
                 else if(r_unit == 'L' || r_unit == 'L/min'){
                     return 'FEV1 and PEF';
                 }
                 else if(r_unit == 'degrees F'){
                     return 'Temperature';
                 }
                 else{
                     return 'Weight';
                 }
             }*/

    },
    orderable: false
  }, // { data: null,
  //     mRender: function(data, type, full, meta){
  //         thres = full['thresholdrange'];
  //             if(full['thresholdrange'] == null){
  //                 thres = '';
  //         }
  //         if(thres!='' && thres!='NULL' && thres!=undefined){
  //             return thres;
  //         }
  //     },
  //     orderable: false
  // },  
  {
    data: 'vitalthreshold',
    name: 'vitalthreshold',
    mRender: function mRender(data, type, full, meta) {
      vitalthreshold = full['vitalthreshold'];

      if (full['thresholdtype'] == null) {
        // vitalthreshold = full['vitalthreshold']+" "+"("+" "+")";
        var vitalthreshold = " ";
        return vitalthreshold;
      } else {
        vitalthreshold = full['vitalthreshold'] + " " + "(" + full['thresholdtype'] + ")";
        return vitalthreshold;
      }

      return vitalthreshold;
    },
    orderable: false
  }, {
    data: 'reading',
    name: 'reading',
    mRender: function mRender(data, type, full, meta) {
      m_reading = full['reading'];
      m_unit = full['unit'];
      m_alert = full['alert']; //     if(full['reading'] == null){
      //         m_reading = '';
      // }
      // else{
      //     return m_reading+' '+m_unit;
      // }

      if (full['reading'] == null) {
        m_reading = ''; // return "<span style='color:red'>"+m_reading+'<i class="i-Danger" style="color:red"></i>'+"<span>";
      } else {
        if (m_alert == 1) {
          return "<span style='color:red'>" + m_reading + ' ' + m_unit + '<i class="i-Danger" style="color:red"></i>' + "<span>";
        } else {
          return m_reading + " " + m_unit;
        }
      }
    },
    orderable: false
  }, // {data:null,
  //     mRender: function(data, type, full, meta){
  //         totaltime = full['csseffdate'];  
  //         if(full['csseffdate'] == null){
  //             totaltime = '';
  //         }
  //         if(data!='' && data!='NULL' && data!=undefined){
  //             return totaltime;
  //         }
  //     },
  //     orderable: true   
  // },
  {
    data: null,
    mRender: function mRender(data, type, full, meta) {
      totaltime = full['csseffdate'];
      var dateonly = totaltime.split(" ");

      if (full['csseffdate'] == null) {
        dateonly = '';
      }

      if (full['csseffdate'] != '' && full['csseffdate'] != 'NULL' && full['csseffdate'] != undefined) {
        return dateonly[0];
      }
    },
    orderable: true
  }, {
    data: null,
    mRender: function mRender(data, type, full, meta) {
      totaltime = full['csseffdate'];
      var timeonly = totaltime.split(" ");

      if (full['csseffdate'] == null) {
        timeonly = '';
      }

      if (full['csseffdate'] != '' && full['csseffdate'] != 'NULL' && full['csseffdate'] != undefined) {
        return timeonly[1];
      }
    },
    orderable: true
  }, {
    data: null,
    'render': function render(data, type, full, meta) {
      if (full['reviewedflag'] == null || full['reviewedflag'] == 0) {
        check = '';
      } else {
        check = 'checked';
      }

      return '<input type="checkbox" id="childreviewpatientstatus_' + full['pid'] + '" class="childreviewpatientstatus" name="childreviewpatientstatus" value="' + full['pid'] + '" ' + check + '>';
    },
    orderable: false
  }, {
    data: null,
    mRender: function mRender(data, type, full, meta) {
      if (full['addressed'] == null || full['addressed'] == 0) {
        check = '';
        readonly = '';
        addressstatus = 'No';
      } else {
        check = 'checked';
        readonly = 'disabled';
        addressstatus = 'Yes';
      }

      if (full['alert'] == 1) {
        return '<input type="checkbox" id="childactivealertpatientstatus_' + meta.row + '_' + full['pid'] + '" class="patientaddressed" name="patientaddressed"  value="' + full['pid'] + '" ' + check + '  ' + readonly + ' ><label style="display:none">' + addressstatus + '</label>';
      }
    },
    orderable: true
  }];
  var childurl = "/rpm/daily-review-list-details/" + patient_id + '/' + fromdate + '/' + unittable + '/' + reviewedstatus + '/' + serialid;
  var childtableid = 'Reviewdaily-Child-list' + patient_id;
  var childtable = util.renderDataTable(childtableid, childurl, columns, baseURL);
  return childtable;
};

$('#searchbutton').click(function () {
  var practicesgrp = $('#practicesgrp').val();
  var practice = $('#practices').val();
  var provider = $('#physician').val();
  var patient = $('#patient').val();
  var caremanagerid = $('#caremanagerid').val();
  var fromdate = $('#fromdate').val();
  var todate = $('#todate').val();
  var reviewedstatus = $('#reviewedstatus').val();

  if (fromdate != '' && todate != '' && fromdate > todate) {
    alert("Please ensure that the To Date is greater than or equal to the From Date.");
    return false;
  } else {
    getDailyReviewPatientList(practicesgrp, practice, provider, patient, caremanagerid, fromdate, todate, reviewedstatus);
  }
});
$('#resetbutton').click(function () {
  var mindate = '<?php echo $mineffdate?>';
  var maxdate = '<?php echo $maxeffdate?>';
  $('#fromdate').val(mindate);
  $('#todate').val(maxdate);
  $fromdate = $('#fromdate').val();
  $todate = $('#todate').val();
  $('#practicesgrp').val('').trigger('change');
  $('#caremanagerid').val('').trigger('change');
  $('#practices').val('').trigger('change');
  $('#physician').val('').trigger('change');
  $('#patient').val('').trigger('change');
  $('#reviewedstatus').val(0).trigger('change');
  var practicesgrp = $('#practicesgrp').val();
  var practice = $('#practices').val();
  var provider = $('#physician').val();
  var patient = $('#patient').val();
  var caremanagerid = $('#caremanagerid').val();
  var fromdate = $('#fromdate').val();
  var todate = $('#todate').val();
  var reviewedstatus = $('#reviewedstatus').val();
  getDailyReviewPatientList(practicesgrp, practice, provider, patient, caremanagerid, fromdate, todate, reviewedstatus);
});
$('#dailyreviewlist tbody').on('change', '.reviewpatientstatus', function () {
  // alert("review");  
  if ($(this).is(":checked")) {
    var reviewstatus = 1;
  } else {
    var reviewstatus = 0;
  }

  var table = $('#dailyreviewlist').DataTable();
  var rowdata = table.row($(this).parents('tr')).data();
  var patient_id = rowdata.pid;
  var serialid = rowdata.rwserialid;
  var childdatacount = rowdata.childdatacount;
  var component_id = $('#component_id').val();
  var stage_id = $('#stage_id').val();
  var step_id = $('#step_id').val(); // var componentid = '<?php echo $component_id; ?>';

  var reviewdata = {
    patient_id: rowdata.pid,
    unit: rowdata.vital_unit,
    csseffdate: rowdata.csseffdate,
    reviewstatus: reviewstatus,
    component_id: component_id,
    serialid: serialid,
    vital_name: rowdata.vital_name,
    reading: rowdata.reading,
    vitaldeviceid: rowdata.vitaldeviceid,
    table: 'parent',
    formname: 'RPMWorklistreviewedcompleted',
    stage_id: stage_id,
    step_id: step_id
  };

  if (reviewstatus == 1 && childdatacount > 0) {
    var fullfromdate = rowdata.csseffdate;
    var newfromdate = fullfromdate.split(" ");
    var fromdate = newfromdate[0];
    var reviewedstatus = 0;
    var r = rowdata.vital_unit;
    var serialid = rowdata.rwserialid;
    var vital_name = rowdata.vital_name;
    var reading = rowdata.reading;

    if (r == 'mm[Hg]' || r == 'mmHg') {
      var unittable = 'observationsbp';
    } else if (r == '%') {
      var unittable = 'observationsoxymeter';
    } else if (r == 'mg/dl') {
      var unittable = 'observationsglucose';
    } else if (r == 'lbs') {
      var unittable = 'observationsweight';
    } else if (r == 'beats/minute') {
      var unittable = 'observationsheartrate';
    } else if (r == 'degrees F') {
      var unittable = 'observationstemperature';
    } else {
      var unittable = 'observationsspirometer';
    }

    var tr = $(this).closest('tr');
    tr.addClass('shown');
    row = $('#dailyreviewlist').DataTable().row(tr);
    tr.find('.plus-icons').removeClass('i-Add');
    tr.find('.plus-icons').addClass('i-Remove');
    var reviewchild = ' <div class="table-responsive">';
    reviewchild = reviewchild + '<table id="Reviewdaily-Child-list' + patient_id + '" class="display table table-striped table-bordered reviewdailychildtable" style="width:100%">';
    reviewchild = reviewchild + '<thead>';
    reviewchild = reviewchild + '<tr>';
    reviewchild = reviewchild + '<th width="35px">Sr No.</th>';
    reviewchild = reviewchild + '<th width="35px">Patient</th>';
    reviewchild = reviewchild + '<th width="35px">Daily Review</th>';
    reviewchild = reviewchild + '<th width="205px">DOB</th>';
    reviewchild = reviewchild + '<th width="205px">Clinic</th>';
    reviewchild = reviewchild + '<th width="97px">Provider</th>';
    reviewchild = reviewchild + '<th width="97px">CareManager</th>';
    reviewchild = reviewchild + '<th width="97px">Vital</th>';
    reviewchild = reviewchild + '<th width="97px">Range</th>';
    reviewchild = reviewchild + '<th width="97px">Reading</th>';
    reviewchild = reviewchild + '<th width="97px">Date</th>';
    reviewchild = reviewchild + '<th width="97px">Time</th>';
    reviewchild = reviewchild + '<th width="97px">Review</th>';
    reviewchild = reviewchild + '<th width="97px">Addressed</th>';
    reviewchild = reviewchild + '</tr></thead><tbody></tbody> </table></div>';
    row.child(reviewchild).show();
    getReviewDailyChildList(row, row.data().FindField1, patient_id, fromdate, unittable, reviewedstatus, serialid);
  } else {// alert("else");
  } // $(this).parents('tr').find('.reviewdetailsclick').trigger('click');


  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $.ajax({
    type: 'POST',
    url: '/rpm/daily-review-updatereviewstatus',
    data: reviewdata,
    success: function success(data) {
      var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Review Status Updated Successfully!</strong></div>';
      $("#success").html(txt);
      util.totalTimeSpentByCM();
      util.updateTimer(patient_id, 1, component_id);
      var scrollPos = $(".main-content").offset().top;
      $(window).scrollTop(scrollPos);
      setTimeout(function () {
        $("#success").hide();
      }, 3000);
    }
  });
});
var addressed_arry = [];
var patientname_array = [];
var pid_array = [];
var rpmid_array = [];
var csseffdate_array = [];
var vital_array = [];
var table_array = [];
var formname_array = [];
var unit_array = [];
$('#dailyreviewlist tbody').on('change', '.patientaddressed', function () {
  //alert("addressed");  
  if ($(this).is(":checked")) {
    var addressedstatus = 1;

    if (this.id.split("_")[0] == "activealertpatientstatus") {
      var table = $('#dailyreviewlist').DataTable();
      var rowdata = table.row($(this).parents('tr')).data();
      var unit = rowdata.vital_unit;
      var rpmid = rowdata.rwserialid;
      table_array.push("parent");
      formname_array.push("RPMworklistaddressedcompleted");
    } else {
      var patient_id = this.id.split("_")[2]; // Reviewdaily-Child-list292633552

      var table = $('#Reviewdaily-Child-list' + patient_id).DataTable();
      var rowdata = table.row($(this).parents('tr')).data(); // var rowdata = table.row($(this).parents('tr')).data();
      //alert("inside else");
      //  console.log(rowdata);

      var unit = rowdata.unit;
      var rpmid = rowdata.tempid;
      table_array.push("child");
      formname_array.push("child_RPMworklistaddressedcompleted");
    } // var table = $('#dailyreviewlist').DataTable();
    // var rowdata = table.row($(this).parents('tr')).data();  
    // console.log(rowdata);         
    // var patient_id =rowdata.pid;
    // var serialid = rowdata.rwserialid;
    // var childdatacount = rowdata.childdatacount;
    // var unit = rowdata.vital_unit; 


    var component_id = $('#component_id').val();
    var stage_id = $('#stage_id').val();
    var step_id = $('#step_id').val();
    var pfname = rowdata.pfname;
    var plname = rowdata.plname;
    var patientname = pfname + " " + plname;
    var pid = rowdata.pid;
    var csseffdate = rowdata.csseffdate; //     var reviewdata = {
    //     patient_id: rowdata.pid,
    //     unit: rowdata.vital_unit,
    //     csseffdate : rowdata.csseffdate,
    //     reviewstatus : reviewstatus,
    //     component_id :  component_id,
    //     serialid : serialid,
    //     table : 'parent',
    //     formname : 'RPMWorklistreviewedcompleted'      
    //     }
    //     if(reviewstatus == 1 && childdatacount>0)
    //     {
    //         var fullfromdate = rowdata.csseffdate;
    //         var newfromdate = fullfromdate.split(" ");
    //         var fromdate =  newfromdate[0]; 
    //         var reviewedstatus = 0;
    //         var r = rowdata.vital_unit;
    //         var serialid = rowdata.rwserialid; 

    if (unit == 'mm[Hg]' || unit == 'mmHg') {
      // var  unittable = 'observationsbp';
      var vital = 'observationsbp';
    } else if (unit == '%') {
      // var unittable = 'observationsoxymeter';
      var vital = 'observationsoxymeter';
    } else if (unit == 'mg/dl') {
      //  var unittable = 'observationsglucose';
      var vital = 'observationsglucose';
    } else if (unit == 'lbs') {
      // var unittable = 'observationsweight';
      var vital = 'observationsweight';
    } else if (unit == 'beats/minute') {
      // var unittable = 'observationsheartrate';
      var vital = 'observationsheartrate';
    } else if (unit == 'degrees F') {
      // var unittable = 'observationstemperature';
      var vital = 'observationstemperature';
    } else {
      // var unittable = 'observationsspirometer';
      var vital = 'observationsspirometer';
    }

    if ($.inArray(pid, pid_array) != -1 || pid_array.length === 0) {
      // patientarray.push(patient_id);
      // rpmid.push(serialid);
      // vitalarray.push(vital);
      // unitarray.push(unit);
      // csseffdatearray.push(csseffdate);
      // deviceid.push(rowdata.deviceid);  
      patientname_array.push(patientname);
      vital_array.push(vital);
      pid_array.push(pid);
      rpmid_array.push(rpmid);
      csseffdate_array.push(csseffdate); // addressed_arry.push(this.id);

      unit_array.push(unit);
    } else {
      $(this).prop('checked', false);
      alert("select only same patient!");
    } // console.log(pid_array);
    // $("#patientname").text(patientname);
    // $("#patientvital").text(vitalarray);     
    // $("#patient_id").val(patientarray); 
    // $("#vital").val(vitalarray); 
    // $("#rpm_observation_id").val(rpmid);  
    // $("#deviceid").val(deviceid);  
    // $("#unit").val(unitarray); 
    // $("#csseffdate").val(csseffdatearray);


    $("#care_patient_id").val(pid_array);
    $("#rpm_observation_id").val(rpmid_array);
    $("#vital").val(vital_array);
    $("#csseffdate").val(csseffdate_array);
    $("#unit").val(unit_array);
    $("#table").val(table_array);
    $("#formname").val(formname_array);
  } else {
    var addressedstatus = 0;
  }
});
$('#addressbutton').click(function () {
  $("#rpm_cm_modal").modal('show');
}); //   $('#addresssubmit').click(function(){
//         alert("baby"); 
//         e.preventDefault();
//     var notes = $('#notes').val();
//     var addressdata = {
//     patientarray: patientarray,
//     rpmid:rpmid,
//     vitalarray:vitalarray,
//     unitarray:unitarray,
//     csseffdatearray:csseffdatearray,
//     notes:notes,
//     formname : 'RPMWorklistaddressedcompleted'      
//     }
//     $.ajaxSetup({    
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         }
//    });
//    $.ajax({
//         type: 'POST',
//         url: '/rpm/daily-review-updateaddressed',  
//         data: addressdata,
//         success: function (data) {  
//             var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Review Status Updated Successfully!</strong></div>';
//             $("#success").html(txt);
//             // util.updateTimer(patient_id, 1, component_id); 
//             var scrollPos = $(".main-content").offset().top;
//             $(window).scrollTop(scrollPos);
//             setTimeout(function () {
//                 $("#success").hide();
//             }, 3000);
//         }
//    });
//   })

var onRPMCMNotesMainForm = function onRPMCMNotesMainForm(formObj, fields, response) {
  // console.log("response" + response.status); 
  // console.log("responsedata" + response);    
  if (response.status == 200 && $.trim(response.data) == '') {
    $("#rpm_cm_form")[0].reset();
    getDailyReviewPatientList();
    $("#success").show();
    $('#rpm_cm_modal').modal('hide');
    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Care Manager Notes Added Successfully!</strong></div>';
    $("#success").html(txt);
    util.totalTimeSpentByCM();
    var scrollPos = $(".main-content").offset().top;
    $(window).scrollTop(scrollPos);
    setTimeout(function () {
      $("#success").hide();
    }, 3000);
  }
};

var init = function init() {
  // $('#patientdiv').hide();
  // alert("call js");
  form.ajaxForm("rpm_cm_form", onRPMCMNotesMainForm, function () {
    return true;
  }); //getDailyReviewPatientList();  

  var mindate = $('#mineffdate').val();
  var maxdate = $('#maxeffdate').val();
  var roleid = $('#roleid').val();
  var component_id = $('#component_id').val();
  $('#fromdate').val(mindate);
  $('#todate').val(maxdate);
  var fromdate = $('#fromdate').val();
  var todate = $('#todate').val();
  var reviewedstatus = $('#reviewedstatus').val();

  if (roleid == '5') {
    $('#cmdiv').hide();
  }

  getDailyReviewPatientList(null, null, null, null, null, fromdate, todate, reviewedstatus);
  $("[name='practicesgrp']").on("change", function () {
    var practicegrp_id = $(this).val();

    if (practicegrp_id == 0) {}

    if (practicegrp_id != '') {
      util.getPracticelistaccordingtopracticegrp(parseInt(practicegrp_id), $("#practices"));
    } else {
      util.updatePracticeListWithoutOther(null, $("#practices"));
    }
  });
  $("[name='practices']").on("change", function () {
    var practice_id = $(this).val();

    if (practice_id == 0) {
      getDailyReviewPatientList();
    }

    if (practice_id != '') {
      $('#patientdiv').show();
      util.getRpmPatientList(parseInt(practice_id), $("#patient"));
      util.updatePcpPhysicianList(parseInt($(this).val()), $("#physician")); //added by priya 25feb2021 for remove other option
    } else {
      getDailyReviewPatientList();
      $('#patientdiv').hide();
    }
  }); // $("[name='practices']").on("change", function () { //cmnt on 7th june22
  //     var practice_id = $(this).val(); 
  //     if(practice_id!=''){ 
  //        // alert(practice_id); 
  //         // util.updatePhysicianProviderListWithoutOther(parseInt($(this).val()), $("#physician"));
  //         util.updatePcpPhysicianList(parseInt($(this).val()), $("form[name='daily_report_form'] #physician")); //added by priya 25feb2021 for remove other option
  //         }
  //         else{
  //             getDailyReviewPatientList();  
  //         }
  // });

  $("[name='provider']").on("change", function () {
    var provider = $(this).val();

    if (provider != '') {
      $('#patientdiv').show();
      util.getRpmProviderPatientList(parseInt($(this).val()), $("#patient"));
    } else {
      getDailyReviewPatientList();
      $('#patientdiv').hide();
    }
  });
  $('#dailyreviewlist tbody').on('click', 'td a', function () {
    // alert("hello");     
    var data_id = $(this).attr('id'); // alert(data_id);      

    var res = data_id.split("/");
    var patient_id = res[0];
    var fromdate = res[1];
    var unittable = res[2];
    var reviewedstatus = res[3];
    var serialid = res[4];
    var tr = $(this).closest('tr'),
        row = $('#dailyreviewlist').DataTable().row(tr);

    if (row.child.isShown()) {
      destroyChild(row);
      tr.removeClass('shown');
      $(this).find('.plus-icons').removeClass('i-Remove');
      $(this).find('.plus-icons').addClass('i-Add'); // $(this).attr("class","http://i.imgur.com/SD7Dz.png");
    } else {
      tr.addClass('shown'); //$(this).attr("src","https://i.imgur.com/d4ICC.png");

      $(this).find('.plus-icons').removeClass('i-Add');
      $(this).find('.plus-icons').addClass('i-Remove');
      var reviewchild = ' <div class="table-responsive">';
      reviewchild = reviewchild + '<table id="Reviewdaily-Child-list' + patient_id + '" class="display table table-striped table-bordered reviewdailychildtable" style="width:100%">';
      reviewchild = reviewchild + '<thead>';
      reviewchild = reviewchild + '<tr>';
      reviewchild = reviewchild + '<th width="35px">Sr No.</th>';
      reviewchild = reviewchild + '<th width="35px">Patient</th>';
      reviewchild = reviewchild + '<th width="35px">Daily Review</th>';
      reviewchild = reviewchild + '<th width="205px">DOB</th>';
      reviewchild = reviewchild + '<th width="205px">Clinic</th>';
      reviewchild = reviewchild + '<th width="97px">Provider</th>';
      reviewchild = reviewchild + '<th width="97px">CareManager</th>';
      reviewchild = reviewchild + '<th width="97px">Vital</th>';
      reviewchild = reviewchild + '<th width="97px">Range</th>';
      reviewchild = reviewchild + '<th width="97px">Reading</th>';
      reviewchild = reviewchild + '<th width="97px">Date</th>';
      reviewchild = reviewchild + '<th width="97px">Time</th>';
      reviewchild = reviewchild + '<th width="97px">Review</th>';
      reviewchild = reviewchild + '<th width="97px">Addressed</th>';
      reviewchild = reviewchild + '</tr></thead><tbody></tbody> </table></div>';
      row.child(reviewchild).show();
      getReviewDailyChildList(row, row.data().FindField1, patient_id, fromdate, unittable, reviewedstatus, serialid);
    }

    $('#Reviewdaily-Child-list' + patient_id + ' tbody').on('change', '.childreviewpatientstatus', function () {
      //    alert("child hello");  
      if ($(this).is(":checked")) {
        var childreviewstatus = 1;
      } else {
        var childreviewstatus = 0;
      }

      var table = $('#Reviewdaily-Child-list' + patient_id).DataTable();
      var childrowdata = table.row($(this).parents('tr')).data(); // console.log(childrowdata);

      childReviewstatus(childrowdata, childreviewstatus);
    });
  });

  function destroyChild(row) {
    var tabledestroy = $("childtable", row.child());
    tabledestroy.detach();
    tabledestroy.DataTable().destroy();
    row.child.hide();
  }

  function childReviewstatus(childrowdata, childreviewstatus) {
    // var componentid = '<?php echo $component_id; ?>';
    var childreviewdata = {
      patient_id: childrowdata.pid,
      unit: childrowdata.vital_unit,
      csseffdate: childrowdata.csseffdate,
      reviewstatus: childreviewstatus,
      component_id: component_id,
      serialid: childrowdata.tempid,
      table: 'child'
    };
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: 'POST',
      url: '/rpm/daily-review-updatereviewstatus',
      data: childreviewdata,
      success: function success(data) {
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Review Status Updated Successfully!</strong></div>';
        $("#success").html(txt);
        util.totalTimeSpentByCM();
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        setTimeout(function () {
          $("#success").hide();
        }, 3000);
      }
    });
  }
};

window.rpmworklist = {
  init: init //onResult: onResult

};

/***/ }),

/***/ 12:
/*!***************************************************!*\
  !*** multi ./resources/laravel/js/rpmworklist.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/nivedita/public_html/rcaregit/resources/laravel/js/rpmworklist.js */"./resources/laravel/js/rpmworklist.js");


/***/ })

/******/ });