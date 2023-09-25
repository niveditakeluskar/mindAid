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
/******/ 	return __webpack_require__(__webpack_require__.s = 11);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/laravel/js/rpmPatientDeviceReading.js":
/*!*********************************************************!*\
  !*** ./resources/laravel/js/rpmPatientDeviceReading.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var baseURL = window.location.origin + '/';
var addressed_arry = [];
var patientname_array = [];
var pid_array = [];
var rpmid_array = [];
var csseffdate_array = [];
var vital_array = [];
var table_array = [];
var formname_array = [];
var unit_array = [];

var onRPMCMNotesMainForm = function onRPMCMNotesMainForm(formObj, fields, response) {
  if (response.status == 200 && $.trim(response.data) == '') {
    var activedeviceid = $('#activedevice').val();
    var formname = "form[name='rpm_cm_form']"; // util.updateTimer($("#p_id").val(), 1, $(formname +" input[name='module_id']").val());

    $("#time-container").val(AppStopwatch.pauseClock);
    util.updateBillableNonBillableAndTickingTimer($("#p_id").val(), $(formname + " input[name='module_id']").val());
    $("#AddressSuccess_" + activedeviceid).show();
    $('#rpm_cm_modal').modal('hide');
    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Care Manager Notes Added Successfully!</strong></div>';
    $("#AddressSuccess_" + activedeviceid).html(txt);
    showtable(activedeviceid); // var timer_paused = $( formname + " input[name='end_time']").val();
    // $("#timer_start").val(timer_paused);

    setTimeout(function () {
      $("#AddressSuccess_" + activedeviceid).hide();
    }, 3000);
    $("#rpm_cm_form")[0].reset();
    addressed_arry = [];
    patientname_array = [];
    pid_array = [];
    rpmid_array = [];
    csseffdate_array = [];
    vital_array = [];
    table_array = [];
    formname_array = [];
    unit_array = [];
  }
};

var onText = function onText(formObj, fields, response) {
  if (response.status == 200) {
    var activedeviceid = $('#activedevice').val();
    var formname = "form[name='rpm_text_form_" + activedeviceid + "']";
    util.updateTimer($(formname + " input[name='patient_id']").val(), 1, $(formname + " input[name='module_id']").val());
    var errormsg = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>' + response.data + '</strong></div>';
    $(formname + " .twilo-error").show();
    $(formname + " .alert-success").show();
    $(formname + " .twilo-error").html(errormsg);
    var timer_paused = $(formname + " input[name='end_time']").val();
    $("#timer_start").val(timer_paused);
    setTimeout(function () {
      $(formname + " #success-alert").hide();
      $(formname + " .twilo-error").hide();
      $(formname)[0].reset();
    }, 3000);
  }
};

var onCareNote = function onCareNote(formObj, fields, response) {
  if (response.status == 200) {
    var activedeviceid = $('#activedevice').val();
    var formname = "form[name='rpm_carenote_form_" + activedeviceid + "']";
    util.updateTimer($(formname + " input[name='patient_id']").val(), 1, $(formname + " input[name='module_id']").val());
    $("#success_" + activedeviceid).show();
    var scrollPos = $(".main-content").offset().top;
    var errormsg = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>' + response.data + '</strong></div>';
    $("#success_" + activedeviceid).html(errormsg);
    var timer_paused = $("form[name='rpm_carenote_form_" + activedeviceid + "'] input[name='end_time']").val();
    $("#timer_start").val(timer_paused);
    $(window).scrollTop(scrollPos);
    setTimeout(function () {
      $("#success_" + activedeviceid).hide(); // $(formname)[0].reset();
      // setCareNote();
    }, 3000);
  }
};

var destroyChild = function destroyChild(row) {
  var tabledestroy = $("childtable", row.child());
  tabledestroy.detach();
  tabledestroy.DataTable().destroy();
  row.child.hide();
};

var singlereview = function singlereview(formnames, data, deviceid, childrowdata, childreviewstatus) {
  $("#time-container").val(AppStopwatch.pauseClock);
  var component_id = $('#component_id').val();
  var timer_start = $("#timer_start").val();
  var moduleid = $("input[name='module_id']").val();

  if (formnames == "checkreviewclick") {
    var activedeviceid = $('#activedevice').val();
    var checkid = data.id;
    var res = checkid.replace("review_", "");
    var deviceid = deviceid;
    var msgpopup = "success_";
    var patient_id = $("#patient_id").val();
    var csseffdate = $("#csseffdt_" + deviceid + "_" + res).val();
    var reviewstatus;
    data.checked == true ? reviewstatus = 1 : reviewstatus = 0;
    var serialid = $('#id_' + deviceid + "_" + res).val();
    var tablename = 'parent';
    var formname = 'rpmdailyreviewedreadingcompleted';
  } else
    /*if(formname=="childReviewstatus")*/
    {
      var activedeviceid = $('#activedevice').val();
      var patient_id = childrowdata.pid;
      var deviceid = childrowdata.unit;
      var csseffdate = childrowdata.csseffdate;
      var reviewstatus = childreviewstatus;
      var serialid = childrowdata.tempid;
      var tablename = 'child';
      var formname = 'child_rpmdailyreviewedcompleted';
      var msgpopup = "datatableSuccess_";
    }

  var childreviewdata = {
    patient_id: patient_id,
    unit: deviceid,
    csseffdate: csseffdate,
    reviewstatus: reviewstatus,
    component_id: component_id,
    serialid: serialid,
    table: tablename,
    formname: formname,
    timer_start: timer_start
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
      $("#" + msgpopup + activedeviceid).show();
      var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Review Status Updated Successfully!</strong></div>';
      $("#" + msgpopup + activedeviceid).html(txt);
      $("#time-container").val(AppStopwatch.pauseClock);
      util.updateBillableNonBillableAndTickingTimer(patient_id, moduleid);

      if (formnames == "checkreviewclick") {
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
      } else {
        $('html, body').animate({
          scrollTop: $('#review-table_' + activedeviceid).offset().top
        }, 'slow');
      }

      setTimeout(function () {
        $("#" + msgpopup + activedeviceid).hide();
      }, 3000);
    }
  });
};

var checkreviewclick = function checkreviewclick(data, deviceid) {
  singlereview("checkreviewclick", data, deviceid, '', '');
};

var renderDataTableExport = function renderDataTableExport(tabid, url, columnData, assetBaseUrl) {
  var copyflag = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : "0";
  var copyTo = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : '';
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
              headerRow = '<row r="' + rowCount + '" s="2"><c t="inlineStr" r="A' + rowCount + '"><is><t>' + '</t></is></c><c t="inlineStr" r="B' + rowCount + '" s="2"><is><t>Sr.No.' + '</t></is></c><c t="inlineStr" r="C' + rowCount + '" s="2"><is><t>Vital' + '</t></is></c><c t="inlineStr" r="D' + rowCount + '" s="2"><is><t>Range' + '</t></is></c><c t="inlineStr" r="E' + rowCount + '" s="2"><is><t>Date' + '</t></is></c><c t="inlineStr" r="F' + rowCount + '" s="2"><is><t>Time' + '</t></is></c><c t="inlineStr" r="G' + rowCount + '" s="2"><is><t>Reviewed' + '</t></is></c><c t="inlineStr" r="H' + rowCount + '" s="2"><is><t>Addressed Alert' + '</t></is></c></row>'; // Append header row to sheetData.

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
                } else if (r_unit == 'mm[Hg]') {
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
                var vitalthreshold = child.vitalthreshold + " " + "(" + " " + ")";
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

              if (child.caremanagerfname == null) {
                var caremanagerfname = '';
              } else {
                var caremanagerfname = child.caremanagerfname + ' ' + child.caremanagerlname;
              }

              var srno = c + 1; // Prepare Excel formated row

              childRow = '<row r="' + rowCount + '"><c t="inlineStr" r="A' + rowCount + '"><is><t>' + '</t></is></c><c t="inlineStr" r="B' + rowCount + '"><is><t>' + srno + '</t></is></c><c t="inlineStr" r="C' + rowCount + '"><is><t>' + vital + '</t></is></c><c t="inlineStr" r="D' + rowCount + '"><is><t>' + vitalthreshold + '</t></is></c><c t="inlineStr" r="E' + rowCount + '"><is><t>' + child.reading + " " + child.unit + '</t></is></c><c t="inlineStr" r="F' + rowCount + '"><is><t>' + dateonly[0] + '</t></is></c><c t="inlineStr" r="G' + rowCount + '"><is><t>' + dateonly[1] + '</t></is></c><c t="inlineStr" r="H' + rowCount + '"><is><t>' + reviewedflag + '</t></is></c><c t="inlineStr" r="I' + rowCount + '"><is><t>' + rwaddressed + '</t></is></c></row>'; // Append row to sheetData.

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
    "columnDefs": [{
      "targets": '_all',
      "defaultContent": ""
    } // , {
    //         "targets": [ 9 ],
    //         "visible": false
    //     }
    ]
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

var getMonthlyReviewPatientList = function getMonthlyReviewPatientList(patientid, deviceid) {
  var monthly = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
  var monthlyto = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
  var columns = [{
    data: 'DT_RowIndex',
    name: 'DT_RowIndex'
  }, {
    data: 'action',
    name: 'action'
  }, {
    data: 'vital_unit',
    name: 'unit',
    mRender: function mRender(data, type, full, meta) {
      if (full['vital_unit'] == 'beats/minute') {
        return 'Heartrate';
      } else {
        return full['vital_name'];
      }
      /* r_unit = full['vital_unit'];
       if (full['vital_unit'] == null) {
           r_unit = '';
       } else {
           if (r_unit == '%') {
               // return 'Pulse Oximeter';
               return 'Oxygen';
           } else if (r_unit == 'beats/minute') {
               // return 'Spirometer';
               return 'Heartrate';
           } else if (r_unit == 'mm[Hg]' || r_unit == 'mmHg') {
               //mmHg
               // return 'Blood Pressure Cuff'
               return 'Blood Pressure';
           } else if (r_unit == 'mg/dl') {
               return 'Glucose';
           } else if (r_unit == 'L' || r_unit == 'L/min') {
               return 'FEV1 and PEF';
           } else if (r_unit == 'degrees F') {
               return 'Temperature';
           } else {
               return 'Weight'
           }
       }*/

    },
    orderable: false
  }, {
    data: null,
    mRender: function mRender(data, type, full, meta) {
      /* thres = full['vital_threshold'];
       if (full['vital_threshold'] == null) {
           thres = '';
       }
       if (thres != '' && thres != 'NULL' && thres != undefined) {
           return thres;
       }*/
      if (full['rwthreshold_type'] == null) {
        // vitalthreshold = full['vital_threshold'] + " " + "(" + " " + ")";
        if (full['vital_threshold'] != null) {
          vitalthreshold = full['vital_threshold'];
        } else {
          vitalthreshold = "";
        }

        return vitalthreshold;
      } else {
        if (full['vital_threshold'] != null) {
          vitalthreshold = full['vital_threshold'] + " " + "(" + full['rwthreshold_type'] + ")";
        } else {
          vitalthreshold = "";
        }

        return vitalthreshold;
      }
    },
    orderable: false
  }, {
    data: 'reading',
    name: 'reading',
    mRender: function mRender(data, type, full, meta) {
      m_reading = full['reading'];
      m_alert = full['alert'];

      if (full['reading'] == null) {
        m_reading = '';
      } else {
        if (full['vital_unit'] == null) {
          m_unit = '';
        } else {
          m_unit = full['vital_unit'];
        }

        if (m_alert == 1) {
          return "<span style='color:red'>" + m_reading + ' ' + m_unit + '<i class="i-Danger" style="color:red"></i>' + "<span>";
        } else {
          return m_reading + " " + m_unit;
        }
      }
    },
    orderable: false
  }, {
    data: null,
    mRender: function mRender(data, type, full, meta) {
      totaltime = full['csseffdate'];
      var dateonly = totaltime.split(" ");

      if (full['csseffdate'] != '' && full['csseffdate'] != 'NULL' && full['csseffdate'] != undefined) {
        return dateonly[0];
      } else {
        return dateonly = '';
      }
    },
    orderable: true
  }, {
    data: null,
    mRender: function mRender(data, type, full, meta) {
      totaltime = full['csseffdate'];
      var timeonly = totaltime.split(" ");

      if (full['csseffdate'] != '' && full['csseffdate'] != 'NULL' && full['csseffdate'] != undefined) {
        return timeonly[1];
      } else {
        return timeonly = "";
      }
    },
    orderable: true
  }, {
    data: null,
    'render': function render(data, type, full, meta) {
      if (full['reviewedflag'] == null || full['reviewedflag'] == 0) {
        check = '';
        reviewstatus = 'No';
      } else {
        check = 'checked';
        reviewstatus = 'Yes';
      }

      return '<input type="checkbox" id="reviewpatientstatus_' + meta.row + '" onchange="rpmPatientDeviceReading.reviewStatusChk(this)" class="reviewpatientstatus" name="reviewpatientstatus" value="' + full['pid'] + '" ' + check + '><label style="display:none">' + reviewstatus + '</label>';
    },
    orderable: false
  }, {
    data: null,
    'render': function render(data, type, full, meta) {
      if (full['rwaddressed'] == null || full['rwaddressed'] == 0) {
        check = '';
        readonly = '';
        addressstatus = 'No';
      } else {
        check = 'checked';
        readonly = 'disabled';
        addressstatus = 'Yes';
      }

      if (m_alert == 1) {
        return '<input type="checkbox" id="activealertpatientstatus_' + meta.row + '" class="activealertpatientstatus" name="activealertpatientstatus"  value="' + full['pid'] + '" ' + check + '  ' + readonly + ' ><label style="display:none">' + addressstatus + '</label>';
      }
    },
    orderable: true
  }, {
    data: null,
    visible: false,
    render: function render(data, type, row, meta) {
      return meta.row;
    }
  }];

  if (monthly == '') {
    monthly = null;
  }

  if (monthlyto == '') {
    monthlyto = null;
  }

  var url = "/rpm/monthly-review-list/" + patientid + "/" + monthly + "/" + monthlyto + "/" + deviceid;
  var pdtableid = 'monthlyreviewlist_' + deviceid;
  var table1 = renderDataTableExport(pdtableid, url, columns, baseURL);
  return table1;
};

var myactivedeviceid = $('#activedevice').val(); //* =====================for child table ======================================    

var getReviewMonthlyChildList = function getReviewMonthlyChildList() {
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
  }, {
    data: 'unit',
    name: 'unit',
    mRender: function mRender(data, type, full, meta) {
      if (full['unit'] == 'beats/minute') {
        return 'Heartrate';
      } else {
        return full['vital_name'];
      }
      /* r_unit = full['unit'];
       if (full['unit'] == null) {
           r_unit = '';
       } else {
           if (r_unit == '%') {
               return 'Oximeter';
           } else if (r_unit == 'mm[Hg]' || r_unit == 'mmHg') {
               return 'Blood Pressure';
           } else if (r_unit == 'lbs') {
               return 'Weight';
           } else if (r_unit == 'L/min' || r_unit == 'L') {
               return 'Spirometer';
           } else if (r_unit == 'mg/dl') {
               return 'Glucose';
           } else if (r_unit == 'beats/minute') {
               return 'Heartrate';
           } else {
               return 'Temperature';
           }
       }*/

    },
    orderable: false
  }, {
    data: 'vitalthreshold',
    name: 'vitalthreshold',
    mRender: function mRender(data, type, full, meta) {
      // vitalthreshold = full['vitalthreshold'];
      // return vitalthreshold;
      vitalthreshold = full['vitalthreshold'];

      if (full['rwthreshold_type'] == null) {
        // vitalthreshold = full['vitalthreshold'] + " " + "(" + " " + ")";
        if (full['vitalthreshold'] != null) {
          vitalthreshold = full['vitalthreshold'];
        } else {
          vitalthreshold = '';
        }

        return vitalthreshold;
      } else {
        if (full['vitalthreshold'] != null) {
          vitalthreshold = full['vitalthreshold'] + " " + "(" + full['thresholdtype'] + ")";
        } else {
          vitalthreshold = '';
        }

        return vitalthreshold;
      }

      return vitalthreshold;
    },
    orderable: false
  }, {
    data: 'reading',
    name: 'reading',
    mRender: function mRender(data, type, full, meta) {
      m_reading = full['reading']; // m_unit = full['unit'];

      m_alert = full['alert'];

      if (full['reading'] == null) {
        m_reading = '';
      } else {
        if (full['unit'] == null) {
          m_unit = '';
        } else {
          m_unit = full['unit'];
        }

        if (m_alert == 1) {
          return "<span style='color:red'>" + m_reading + ' ' + m_unit + '<i class="i-Danger" style="color:red"></i>' + "<span>";
        } else {
          return m_reading + " " + m_unit;
        }
      }
    },
    orderable: false
  }, {
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

      return '<input type="checkbox" id="childreviewpatientstatus_' + meta.row + '" class="childreviewpatientstatus" name="childreviewpatientstatus" value="' + full['pid'] + '" ' + check + '>';
    },
    orderable: false
  }, {
    data: null,
    'render': function render(data, type, full, meta) {
      if (full['addressed'] == null || full['addressed'] == 0) {
        check = '';
        readonly = '';
      } else {
        check = 'checked';
        readonly = 'disabled';
      }

      if (m_alert == 1) {
        return '<input type="checkbox" id="childactivealertpatientstatus_' + meta.row + '" class="activealertpatientstatus" name="childactivealertpatientstatus"  value="' + full['pid'] + '" ' + check + ' ' + readonly + '>';
      }
    },
    orderable: true
  }];
  var childurl = "/rpm/daily-review-list-details/" + patient_id + '/' + fromdate + '/' + unittable + '/' + reviewedstatus + '/' + serialid;
  var deviceid = $('#activedevice').val();
  var childtableid = 'ReviewMonthly-Child-list' + patient_id + '_' + deviceid;
  childtable = util.renderDataTable(childtableid, childurl, columns, baseURL);
};

var setCareNote = function setCareNote() {
  var patient_id = $("#patient_id").val();

  if (patient_id != "") {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: 'POST',
      url: '/rpm/getCareNoteData',
      data: {
        patient_id: patient_id
      },
      success: function success(data) {
        $("textarea#CareManagerNotes").val(data.trim());
      }
    });
  }
};

var showtable = function showtable(deviceid) {
  var patient_id = $("#patient_id").val();
  $('#review-table_' + deviceid).show();
  $.ajax({
    url: '/rpm/rpm-protocol/' + deviceid,
    type: 'get',
    success: function success(data) {
      var btn = '<a href="' + data.filename + '"  target="_blank" title="Start" ><button type="button" id="detailsbutton" class="btn btn-primary mt-4"> Protocol</button></a>';
      $('#rpmfilename_' + deviceid).html(btn);
    }
  });
  getMonthlyReviewPatientList(patient_id, deviceid); // this is for scroll down 

  $('html, body').animate({
    scrollTop: $('#review-table_' + deviceid).offset().top
  }, 'slow');
};

var dailyReadingFunction = function dailyReadingFunction(patient_id, activateddeviceid) {
  // function dailyReadingFunction(patient_id,activateddeviceid){   
  $.ajax({
    url: '/rpm/daily-device-reading/' + patient_id + '/' + activateddeviceid,
    type: 'get',
    success: function success(data) {
      for (var i = 0; i < data.reading.length; i++) {
        $("#reading_" + activateddeviceid + "_" + i).text(data.reading[i]);
        $("#unit_" + activateddeviceid + "_" + i).text(data.unit[i]);
        $("#id_" + activateddeviceid + "_" + i).val(data.id[i]);
        $("#csseffdt_" + activateddeviceid + "_" + i).val(data.effdatetime[i]);

        if (data.reading[i] == "N/A") {
          $(".review_" + activateddeviceid + "_" + i).attr('disabled', 'disabled');
        } else {
          $(".review_" + activateddeviceid + "_" + i).removeAttr("disabled");
        }

        if (data.review[i] == "1") {
          $(".review_" + activateddeviceid + "_" + i).prop("checked", true);
        } else {
          $(".review_" + activateddeviceid + "_" + i).prop("checked", false);
        }

        if (data.reading[i] != "N/A" && data.alert_status[i] == "1") {
          if (data.addressed[i] == "1") {
            $("#reading_" + activateddeviceid + "_" + i).css("color", "red");
            $("#unit_" + activateddeviceid + "_" + i).css("color", "red");
            $("#addressed_" + activateddeviceid + "_" + i).removeClass('i-Danger');
            $("#addressed_" + activateddeviceid + "_" + i).addClass('i-Yes');
          } else {
            $("#reading_" + activateddeviceid + "_" + i).css("color", "");
            $("#unit_" + activateddeviceid + "_" + i).css("color", "");
            $("#addressed_" + activateddeviceid + "_" + i).removeClass('i-Yes');
            $("#addressed_" + activateddeviceid + "_" + i).addClass('i-Danger');
          }
        } else {
          $("#reading_" + activateddeviceid + "_" + i).css("color", "");
          $("#unit_" + activateddeviceid + "_" + i).css("color", "");
          $("#addressed_" + activateddeviceid + "_" + i).removeClass('i-Yes');
          $("#addressed_" + activateddeviceid + "_" + i).removeClass('i-Danger');
        }
      }
    }
  });
};

var reviewStatusChk = function reviewStatusChk(this_key) {
  $("#time-container").val(AppStopwatch.pauseClock);
  var activedeviceid = $('#activedevice').val();

  if ($(this_key).is(":checked")) {
    var reviewstatus = 1;
  } else {
    var reviewstatus = 0;
  } //alert("activedeviceid"+activedeviceid);


  var table = $('#monthlyreviewlist_' + activedeviceid).DataTable();
  var rowdata = table.row($(this_key).parents('tr')).data();
  var patient_id = rowdata.pid;
  var serialid = rowdata.rwserialid;
  var childdatacount = rowdata.childdatacount;
  var component_id = $('#component_id').val();
  var componentid = '<?php echo $component_id; ?>';
  var timer_start = $("#timer_start").val();
  var reviewdata = {
    patient_id: rowdata.pid,
    unit: rowdata.vital_unit,
    csseffdate: rowdata.csseffdate,
    reviewstatus: reviewstatus,
    component_id: component_id,
    serialid: serialid,
    table: 'parent',
    formname: 'rpmdailyreviewedcompleted',
    timer_start: timer_start
  };

  if (reviewstatus == 1 && childdatacount > 0) {
    var fullfromdate = rowdata.csseffdate;
    var newfromdate = fullfromdate.split(" ");
    var fromdate = newfromdate[0];
    var reviewedstatus = 0;
    var r = rowdata.vital_unit;
    var serialid = rowdata.rwserialid;

    if (r == 'mm[Hg]' || r == 'mmHg') {
      var unittable = 'observationsbp';
    } else if (r == '%') {
      var unittable = 'observationsoxymeter';
    } else if (r == 'beats/minute') {
      var unittable = 'observationsheartrate';
    } else if (r == 'lbs') {
      var unittable = 'observationsweight';
    } else if (r == 'L/min' || r == 'L') {
      var unittable = 'observationsspirometer';
    } else if (r == 'mg/dl') {
      var unittable = 'observationsglucose';
    } else {} //==this is for delete child table first then append table


    $('.i-Remove').removeClass('i-Remove');
    $('.plus-icons').addClass('i-Add');
    $('.shown').removeClass('shown');
    $(".child_tb").remove(); //=======================================================

    var tr = $(this_key).closest('tr');
    tr.addClass('shown');
    row = $('#monthlyreviewlist_' + activedeviceid).DataTable().row(tr);
    tr.find('i').removeClass('i-Add');
    tr.find('i').addClass('i-Remove'); //anand 

    var reviewchild = ' <div class="table-responsive child_tb">';
    reviewchild = reviewchild + '<table id="ReviewMonthly-Child-list' + patient_id + '_' + activedeviceid + '" class="display table table-striped table-bordered reviewdailychildtable" style="width:100%">';
    reviewchild = reviewchild + '<thead>';
    reviewchild = reviewchild + '<tr>';
    reviewchild = reviewchild + '<th width="35px">Sr No.</th>';
    reviewchild = reviewchild + '<th width="97px">Vital</th>';
    reviewchild = reviewchild + '<th width="97px">Range</th>';
    reviewchild = reviewchild + '<th width="97px">Reading</th>';
    reviewchild = reviewchild + '<th width="97px">Date</th>';
    reviewchild = reviewchild + '<th width="97px">Time</th>';
    reviewchild = reviewchild + '<th width="97px">Reviewed</th>';
    reviewchild = reviewchild + '<th width="97px">Addressed Alert</th>';
    reviewchild = reviewchild + '</tr></thead><tbody></tbody> </table></div>';
    row.child(reviewchild).show();
    getReviewMonthlyChildList(row, row.data().FindField1, patient_id, fromdate, unittable, reviewedstatus, serialid);
  }

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
      // debugger;
      $("#datatableSuccess_" + activedeviceid).show();
      var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Review Status Updated Successfully!</strong></div>';
      $("#datatableSuccess_" + activedeviceid).html(txt);
      var moduleid = $("input[name='module_id']").val();
      $("#time-container").val(AppStopwatch.pauseClock);
      util.updateBillableNonBillableAndTickingTimer(patient_id, moduleid);
      $('html, body').animate({
        scrollTop: $('#review-table_' + activedeviceid).offset().top
      }, 'slow');
      setTimeout(function () {
        $("#datatableSuccess_" + activedeviceid).hide();
      }, 3000);
    }
  });
};

var init = function init() {
  util.redirectToWorklistPage();
  var year = new Date().getFullYear();
  var month = new Date().getMonth() + 1; //add +1 for current mnth

  var patient_id = $('#patient_id').val();
  var module_id = $('#module_id').val();
  var tabids = $("ul#patientdevicetab").find("li a.active").attr('id');
  var ress;

  if (tabids != "" && tabids != null && tabids != undefined) {
    util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
    util.getPatientCareplanNotes(patient_id, module_id);
    util.getPatientDetails(patient_id, module_id);
    ress = tabids.split("_");
    var deviceids = ress[1];
    $('#activedevice').val(deviceids); //$("form[name='rpm_text_form_" + deviceids + "'] #text_template_id option:last").attr("selected", "selected").change();

    util.stepWizard('tsf-wizard-monthly-service');
    $("#start").hide();
    $("#pause").show();
    util.updateTimer(patient_id, 1, module_id);
  }

  $("#time-container").val(AppStopwatch.startClock);

  if (tabids != "" && tabids != null && tabids != undefined) {
    util.getPatientStatus(patient_id, module_id); // dailyReadingFunction(patient_id, deviceids);

    getMonthlyReviewPatientList(patient_id, deviceids);
    util.getToDoListData(patient_id, module_id);
    var activedeviceid = $('#activedevice').val();
    document.getElementById('device-icon-tab_' + activedeviceid).click();
    setCareNote();
    setInterval(function () {
      activedeviceid = $('#activedevice').val();
      dailyReadingFunction(patient_id, activedeviceid);
    }, 60000); //every 1 min    
  }

  $('.notes_form .btn').on('click', function () {
    formid = $(this).attr('dataid');
    $("#time-container").val(AppStopwatch.pauseClock);
    var timer_start = $("#timer_start").val();
    var timer_paused = $("#time-container").text();
    $("form[name='" + formid + "'] input[name='start_time']").val(timer_start);
    $("form[name='" + formid + "'] input[name='end_time']").val(timer_paused);
    $("#timer_end").val(timer_paused);
    $("#time-container").val(AppStopwatch.startClock);
    form.ajaxSubmit(formid, onCareNote);
    return false;
  });
  form.ajaxForm("rpm_cm_form", onRPMCMNotesMainForm); //priya code (patient info)

  $('form[name="personal_notes_form"] .submit-personal-notes').on('click', function (e) {
    $("#time-container").val(AppStopwatch.pauseClock);
    var timer_start = $("#timer_start").val();
    var timer_paused = $("#time-container").text();
    $("form[name='personal_notes_form'] input[name='start_time']").val(timer_start);
    $("form[name='personal_notes_form'] input[name='end_time']").val(timer_paused); // $("#timer_start").val(timer_paused);

    $("#timer_end").val(timer_paused);
    $("#time-container").val(AppStopwatch.startClock);
    form.ajaxSubmit('personal_notes_form', patientEnrollment.onPersonalNotes);
  });
  $('form[name="part_of_research_study_form"] .submit-part-of-research-study').on('click', function () {
    $("#time-container").val(AppStopwatch.pauseClock);
    var timer_start = $("#timer_start").val();
    var timer_paused = $("#time-container").text();
    $("form[name='part_of_research_study_form'] input[name='start_time']").val(timer_start);
    $("form[name='part_of_research_study_form'] [name='end_time']").val(timer_paused); // $("#timer_start").val(timer_paused);

    $("#timer_end").val(timer_paused);
    $("#time-container").val(AppStopwatch.startClock);
    form.ajaxSubmit('part_of_research_study_form', patientEnrollment.onPartOfResearchStudy);
  });
  $('form[name="patient_threshold_form"] .submit-patient-threshold').on('click', function () {
    $("#time-container").val(AppStopwatch.pauseClock);
    var timer_start = $("#timer_start").val();
    var timer_paused = $("#time-container").text();
    $("form[name='patient_threshold_form'] input[name='start_time']").val(timer_start);
    $("form[name='patient_threshold_form'] [name='end_time']").val(timer_paused); // $("#timer_start").val(timer_paused);

    $("#timer_end").val(timer_paused);
    $("#time-container").val(AppStopwatch.startClock);
    form.ajaxSubmit('patient_threshold_form', patientEnrollment.onPatientThreshold);
  }); //===========================

  $('.text_form .btn_sub').on('click', function () {
    formid = $(this).attr('dataid');
    $("#time-container").val(AppStopwatch.pauseClock);
    var timer_start = $("#timer_start").val();
    var timer_paused = $("#time-container").text();
    $("form[name='" + formid + "'] input[name='start_time']").val(timer_start);
    $("form[name='" + formid + "'] input[name='end_time']").val(timer_paused);
    $("#timer_end").val(timer_paused);
    $("#time-container").val(AppStopwatch.startClock);
    form.ajaxSubmit(formid, onText);
    return false;
  });
  $('form[name="patient_threshold_form"] .submit-patient-threshold').on('click', function () {
    $("#time-container").val(AppStopwatch.pauseClock);
    var timer_start = $("#timer_start").val();
    var timer_paused = $("#time-container").text();
    $("form[name='patient_threshold_form'] input[name='start_time']").val(timer_start);
    $("form[name='patient_threshold_form'] [name='end_time']").val(timer_paused); // $("#timer_start").val(timer_paused);

    $("#timer_end").val(timer_paused);
    $("#time-container").val(AppStopwatch.startClock);
    form.ajaxSubmit('patient_threshold_form', patientEnrollment.onPatientThreshold);
  });
  $("form[name='rpm_text_form_" + activedeviceid + "'] #text_template_id").change(function () {
    var deviceidd = $(this).val();
    var patient_id = $('#patient_id').val();
    $.ajax({
      url: '/ccm/get-call-scripts-by-id/' + deviceidd + '/' + patient_id + "/call-script",
      type: 'get',
      success: function success(data) {
        $("form[name='rpm_text_form_" + activedeviceid + "'] #templatearea_sms").html(data[0].content_title);
      }
    });
  });
  $("#month-search_" + activedeviceid).click(function () {
    var patient_id = $('#patient_id').val();
    var monthly = $('#monthly_' + activedeviceid).val();
    var monthlyto = $('#monthlyto_' + activedeviceid).val();

    if (monthlyto < monthly) {
      $('#monthlyto_' + activedeviceid).addClass("is-invalid");
      $('#monthlyto_' + activedeviceid).next(".invalid-feedback").html("Please select to-month properly .");
      $('#monthly_' + activedeviceid).addClass("is-invalid");
      $('#monthly_' + activedeviceid).next(".invalid-feedback").html("Please select from-month properly .");
    } else {
      $('#monthlyto_' + activedeviceid).removeClass("is-invalid");
      $('#monthlyto_' + activedeviceid).removeClass("invalid-feedback");
      $('#monthly_' + activedeviceid).removeClass("is-invalid");
      $('#monthly_' + activedeviceid).removeClass("invalid-feedback");
      getMonthlyReviewPatientList(patient_id, activedeviceid, monthly, monthlyto);
    }
  });
  $("#month-reset_" + activedeviceid).click(function () {
    var patient_id = $('#patient_id').val();
    $('#monthlyto_' + activedeviceid).removeClass("is-invalid");
    $('#monthlyto_' + activedeviceid).removeClass("invalid-feedback");
    $('#monthly_' + activedeviceid).removeClass("is-invalid");
    $('#monthly_' + activedeviceid).removeClass("invalid-feedback");
    var c_month = (new Date().getMonth() + 1).toString().padStart(2, "0");
    var c_year = new Date().getFullYear();
    var current_MonthYear = c_year + '-' + c_month;
    $("#monthly_" + activedeviceid).val(current_MonthYear);
    $("#monthlyto_" + activedeviceid).val(current_MonthYear);
    var monthly = $("#monthly_" + activedeviceid).val();
    var monthlyto = $("#monthlyto_" + activedeviceid).val();
    getMonthlyReviewPatientList(patient_id, activedeviceid, monthly, monthlyto);
  }); //======================================= Parent table========================================================

  $('#monthlyreviewlist_' + activedeviceid + ' tbody').on('click', 'td a', function () {
    var data_id = $(this).attr('id');
    var res = data_id.split("/");
    var patient_id = res[0];
    var fromdate = res[1];
    var unittable = res[2];
    var reviewedstatus = res[3];
    var serialid = res[4]; //anand

    $('.i-Remove').removeClass('i-Remove');
    $('.plus-icons').addClass('i-Add');
    $('.shown').removeClass('shown'); //anand

    $(".child_tb").remove();
    var tr = $(this).closest('tr');
    row = $('#monthlyreviewlist_' + activedeviceid).DataTable().row(tr);

    if (row.child.isShown()) {
      destroyChild(row);
      tr.removeClass('shown');
      $(this).find('i').removeClass('i-Remove');
      $(this).find('i').addClass('i-Add');
    } else {
      tr.addClass('shown');
      $(this).find('i').removeClass('i-Add');
      $(this).find('i').addClass('i-Remove');
      var reviewchild = ' <div class="table-responsive child_tb">';
      reviewchild = reviewchild + '<table id="ReviewMonthly-Child-list' + patient_id + '_' + activedeviceid + '" class="display table table-striped table-bordered reviewdailychildtable" style="width:100%">';
      reviewchild = reviewchild + '<thead>';
      reviewchild = reviewchild + '<tr>';
      reviewchild = reviewchild + '<th width="35px">Sr No.</th>';
      reviewchild = reviewchild + '<th width="97px">vital</th>';
      reviewchild = reviewchild + '<th width="97px">Range</th>';
      reviewchild = reviewchild + '<th width="97px">Reading</th>';
      reviewchild = reviewchild + '<th width="97px">Date</th>';
      reviewchild = reviewchild + '<th width="97px">Time</th>';
      reviewchild = reviewchild + '<th width="97px">Reviewed</th>';
      reviewchild = reviewchild + '<th width="97px">Addressed</th>';
      reviewchild = reviewchild + '</tr></thead><tbody></tbody> </table></div>';
      row.child(reviewchild).show();
      getReviewMonthlyChildList(row, row.data().FindField1, patient_id, fromdate, unittable, reviewedstatus, serialid);
    }

    $('#ReviewMonthly-Child-list' + patient_id + '_' + activedeviceid + ' tbody').on('change', '.childreviewpatientstatus', function () {
      if ($(this).is(":checked")) {
        var childreviewstatus = 1;
      } else {
        var childreviewstatus = 0;
      }

      var table = $('#ReviewMonthly-Child-list' + patient_id + '_' + activedeviceid).DataTable();
      var childrowdata = table.row($(this).parents('tr')).data(); //  console.log(JSON.stringify(childrowdata));
      //  childReviewstatus(childrowdata, childreviewstatus);

      singlereview("childReviewstatus", '', '', childrowdata, childreviewstatus);
    });
  });
  $('.textbtn').click(function () {
    var getid = this.id;
    var deviceid = getid.split("_");
    $('#textcard_' + deviceid[1]).show(); //alert(deviceid[1]);

    $("form[name='rpm_text_form_" + deviceid[1] + "'] #text_template_id option:last").attr("selected", "selected").change(); // util.getCallScriptsById($("form[name='rpm_text_form_" + deviceid[1] + "'] #text_template_id option:selected").val(), "form[name='rpm_text_form_" + deviceid[1] + "'] #templatearea_sms", "form[name='rpm_text_form_" + deviceid[1] + "'] input[name='template_type_id']", "form[name='rpm_text_form_" + deviceid[1] + "'] input[name='content_title']");
    // this is for scroll down 

    $('html, body').animate({
      scrollTop: $('#textcard_' + deviceid[1]).offset().top
    }, 'slow');
  });
  $(".reviwe_text").change(function () {
    var avtive = $("ul#patientdevicetab").find("li a.active").attr('id');
    var getid = avtive.split("_");
    var aid = getid[1];
    util.getCallScriptsById($(this).val(), "form[name='rpm_text_form_" + aid + "'] #templatearea_sms", "form[name='rpm_text_form_" + aid + "'] input[name='template_type_id']", "form[name='rpm_text_form_" + aid + "'] input[name='content_title']");
  });
  $('.close,.cancel').click(function () {
    var divclass = $(this).parent().attr('class');
    var divid = $(this).parent().attr('id');
    $('#' + divclass + "_" + divid).hide();
    $('#' + divclass).hide();
  });
  $('#rpm_cm_form .modalcancel,.close').click(function () {
    var care_patient = $("#care_patient_id").val();
    var care_patient_id = care_patient.split(",");
    var hd_chk = $("#hd_chk_this").val();
    var hd_chk_this = hd_chk.split(",");

    for (var j = 0; j < hd_chk_this.length; j++) {
      var table_row_id = hd_chk_this[j].split("_")[1];

      if (hd_chk_this[j].split("_")[0] == "activealertpatientstatus") {
        $("#monthlyreviewlist_" + activedeviceid + " #activealertpatientstatus_" + table_row_id).trigger('click');
      } else {
        $("#ReviewMonthly-Child-list" + care_patient_id[j] + "_" + activedeviceid + " #childactivealertpatientstatus_" + table_row_id).trigger('click');
      }
    }
  });
  $('#monthlyreviewlist_' + activedeviceid + ' tbody').on('change', '.activealertpatientstatus', function () {
    if ($(this).is(":checked")) {
      if (jQuery.inArray(this.id, addressed_arry) != -1) {//match value i array
      } else {
        if (this.id.split("_")[0] == "activealertpatientstatus") {
          var table = $('#monthlyreviewlist_' + activedeviceid).DataTable();
          var rowdata = table.row($(this).parents('tr')).data();
          var unit = rowdata.vital_unit;
          var rpmid = rowdata.rwserialid;
          table_array.push("parent");
          formname_array.push("rpmdailyreviewedreadingcompleted");
        } else {
          var table = $('#ReviewMonthly-Child-list' + patient_id + '_' + activedeviceid).DataTable();
          var rowdata = table.row($(this).parents('tr')).data();
          var unit = rowdata.unit;
          var rpmid = rowdata.tempid;
          table_array.push("child");
          formname_array.push("child_rpmdailyreviewedcompleted");
        } //console.log(JSON.stringify(rowdata));  


        var pfname = rowdata.pfname;
        var plname = rowdata.plname;
        var patientname = pfname + " " + plname;
        var pid = rowdata.pid;
        var csseffdate = rowdata.csseffdate;
        $("#p_id").val(pid); //$(this).closest("tr").find(".reviewpatientstatus").prop('checked',true);

        var urlmm = "/rpm/monthly-monitoring/" + pid;
        $('#gotomm').html('<a href="' + urlmm + '"><u>Go To Monthly Monitoring</u></a>');

        if (unit == '%') {
          var vital = 'Oximeter';
        } else if (unit == 'mm[Hg]' || unit == 'mmHg') {
          var vital = 'Blood Pressure';
        } else if (unit == 'beats/minute') {
          var vital = 'Heartrate';
        } else if (unit == 'mg/dl') {
          var vital = 'Glucose';
        } else if (unit == 'lbs') {
          var vital = 'Weight';
        } else if (unit == 'degrees F') {
          var vital = 'Temperature';
        } else {
          var vital = 'Spirometer';
        }

        patientname_array.push(patientname);
        vital_array.push(vital);
        pid_array.push(pid);
        rpmid_array.push(rpmid);
        csseffdate_array.push(csseffdate);
        addressed_arry.push(this.id);
        unit_array.push(unit);
      }
    } else {
      if (jQuery.inArray(this.id, addressed_arry) != -1) {
        var index_address = addressed_arry.indexOf(this.id);
        addressed_arry.splice(index_address, 1);
      }
    }
  });
  $('#Addressed_' + activedeviceid).on('click', function () {
    // alert(addressed_arry)
    var timer_start = $("#timer_start").val();
    $("#hd_chk_this").val(addressed_arry);
    $("#care_patient_id").val(pid_array);
    $("#rpm_observation_id").val(rpmid_array);
    $("#vital").val(vital_array);
    $("#csseffdate").val(csseffdate_array);
    $("#unit").val(unit_array);
    $("#table").val(table_array);
    $("#formname").val(formname_array);
    $("#hd_timer_start").val(timer_start);

    if (addressed_arry.length !== 0) {
      $("#rpm_cm_modal").modal('show');
    } else {
      alert("Please select checkebox");
    }
  }); // $("form[name='template_content'] #text_template_id option:last").attr("selected", "selected").change();

  /*
  $('#monthlyreviewlist_' + activedeviceid + ' tbody').on('change','.activealertpatientstatus',function(){
          if($(this).is(":checked")){
              var table = $('#monthlyreviewlist_' + activedeviceid).DataTable();
              var rowdata = table.row($(this).parents('tr')).data();
                 
              var pfname = rowdata.pfname;
              var plname = rowdata.plname;
              var unit   = rowdata.vital_unit;
              var rpmid  = rowdata.rwserialid;
              var pid    = rowdata.pid;
              var patientname = pfname+" "+plname;
              
             //  var deviceid=rowdata.deviceid;
               var csseffdate=rowdata.csseffdate;
             //$(this).closest("tr").find(".reviewpatientstatus").prop('checked',true);
             var urlmm="/rpm/monthly-monitoring/"+pid;
              $('#gotomm').html('<a href="'+urlmm+'"><u>Go To Monthly Monitoring</u></a>');
            // input#activealertpatientstatus_0.activealertpatientstatus 
             $("#hd_chk_this").val(this.id);
  
  
              if(unit=='%'){
                  var vital = 'Oxygen';
              }
              else if(unit=='mm[Hg]'){
                 var vital = 'Blood Pressure';
              }
              else if(unit=='beats/minute'){
                 var vital =  'Heartrate';
              } 
              else if(unit=='mg/dl'){
                  var vital =  'Glucose';
              }
              else if(unit=='lbs'){
                  var vital =  'Weight';
              }
              else if(unit=='degrees F'){
                  var vital =  'Temperature';
              }
              else{
                  var vital = 'Spirometer';
              }
             
              $("#rpm_cm_modal").modal('show');
              $("#patientname").text(patientname);
              $("#patientvital").text(vital);     
              $("#care_patient_id").val(pid); 
              $("#rpm_observation_id").val(rpmid);  
              $("#vital").val(vital); 
              $("#csseffdate").val(csseffdate); 
          }
  
  
      }); 
  */
}; //init


$('.tabclass').on('click', function () {
  var tabid = this.id;
  var res = tabid.split("_");
  $('#activedevice').val(res[1]);
  var activedeviceid = res[1];
  var patient_id = $('#patient_id').val(); //$("form[name='rpm_text_form_" + activedeviceid + "'] #text_template_id option:last").attr("selected", "selected").change();
  //util.getCallScriptsById($("form[name='rpm_text_form_" + activedeviceid + "'] #text_template_id option:selected").val(), '#templatearea_sms', "form[name='rpm_text_form_" + activedeviceid + "'] input[name='template_type_id']", "form[name='rpm_text_form_" + activedeviceid + "'] input[name='content_title']");

  dailyReadingFunction(patient_id, activedeviceid);
  var c_month = (new Date().getMonth() + 1).toString().padStart(2, "0");
  var c_year = new Date().getFullYear();
  var current_MonthYear = c_year + '-' + c_month;
  $("#monthly_" + activedeviceid).val(current_MonthYear);
  $("#monthlyto_" + activedeviceid).val(current_MonthYear);
});
window.rpmPatientDeviceReading = {
  init: init,
  showtable: showtable,
  dailyReadingFunction: dailyReadingFunction,
  checkreviewclick: checkreviewclick,
  // childReviewstatus: childReviewstatus,
  setCareNote: setCareNote,
  reviewStatusChk: reviewStatusChk,
  singlereview: singlereview
};

/***/ }),

/***/ 11:
/*!***************************************************************!*\
  !*** multi ./resources/laravel/js/rpmPatientDeviceReading.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/nivedita/public_html/rcaregit/resources/laravel/js/rpmPatientDeviceReading.js */"./resources/laravel/js/rpmPatientDeviceReading.js");


/***/ })

/******/ });