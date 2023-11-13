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
/******/ 	return __webpack_require__(__webpack_require__.s = 14);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/laravel/js/rpmReviewDataLink.js":
/*!***************************************************!*\
  !*** ./resources/laravel/js/rpmReviewDataLink.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var baseURL = window.location.origin + '/';
var addressed_arry = [];
var unit_bp_array = [];
var unit_hr_array = [];
var care_patient_id_array = [];
var observation_id_bp_array = [];
var observation_id_hr_array = [];
var csseffdate_array = [];
var formname_array = [];
var table_array = [];

var onResult = function onResult(form, fields, response, error) {
  if (error) {} else {
    window.location.href = response.data.redirect;
  }
};

var onRPMCMNotesMainForm = function onRPMCMNotesMainForm(formObj, fields, response) {
  if (response.status == 200 && $.trim(response.data) == '') {
    var activedeviceid = $('#hd_deviceid').val();
    var formname = "form[name='rpm_cm_form']"; // util.updateTimer($("#p_id").val(), 1, $(formname +" input[name='module_id']").val());

    $("#time-container").val(AppStopwatch.pauseClock);
    util.updateBillableNonBillableAndTickingTimer($("#p_id").val(), $(formname + " input[name='module_id']").val());
    $("#AddressSuccess_" + activedeviceid).show();
    $('#rpm_cm_modal').modal('hide');
    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Care Manager Notes Added Successfully!</strong></div>';
    $("#AddressSuccess_" + activedeviceid).html(txt); //showtable(activedeviceid);

    $("#searchbutton").trigger("click");
    setTimeout(function () {
      $("#AddressSuccess_" + activedeviceid).hide();
    }, 5000);
    $('#rpm_cm_form').trigger("reset"); // $("#rpm_cm_form")[0].reset();

    addressed_arry = [];
    unit_bp_array = [];
    unit_hr_array = [];
    care_patient_id_array = [];
    observation_id_bp_array = [];
    observation_id_hr_array = [];
    csseffdate_array = [];
    formname_array = [];
    table_array = [];
  }
};

var onCaremanagerNotes = function onCaremanagerNotes(formObj, fields, response) {
  if (response.status == 200) {
    $("#time-container").val(AppStopwatch.pauseClock);
    var formname = "form[name='rpm_review_form']";
    util.updateBillableNonBillableAndTickingTimer($("input[name='patient_id']").val(), $(formname + " input[name='module_id']").val());
    util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
    $("form[name='rpm_review_form'] .alert").show();
    var scrollPos = $(".main-content").offset().top;
    $(window).scrollTop(scrollPos);
    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Care Manager Notes Added Successfully!</strong></div>';
    $("#success").html(txt);
    setTimeout(function () {
      $("#success").fadeOut();
    }, 5000);
    setTimeout(function () {
      $('#call_step_1_id').click();
    }, 5000);
    var timer_paused = $("form[name='rpm_review_form'] input[name='end_time']").val();
    $("#timer_start").val(timer_paused);
    var table = $('#callwrap-list');
    table.DataTable().ajax.reload();
    $(".form_start_time").val(response.data.form_start_time); // $("#timer_end").val(timer_paused);
  }
};

$(document).on('click', '.activealertpatientstatus', function () {
  var activedeviceid = $('#hd_deviceid').val();

  if ($(this).is(":checked")) {
    if (jQuery.inArray(this.id, addressed_arry) != -1) {//match value i array
    } else {
      var table = $('#patient-alert-history-list_' + activedeviceid).DataTable();
      var rowdata = table.row($(this).parents('tr')).data(); //  console.log("anand"+JSON.stringify(rowdata));

      var rpm_unit_bp = rowdata.unit;
      var rpm_unit_hr = rowdata.hrunit;
      var rpm_observation_id_bp = rowdata.bptempid;
      var rpm_observation_id_hr = rowdata.hrtempid;
      var care_patient_id = rowdata.pid;
      var csseffdate = rowdata.csseffdate;
      $("#p_id").val(care_patient_id);
      /*var urlmm = "/rpm/monthly-monitoring/" + pid;
      $('#gotomm').html('<a href="' + urlmm + '"><u>Go To Monthly Monitoring</u></a>');*/

      table_array.push("parent");
      formname_array.push("rpmReviewDataLink_address");
      unit_bp_array.push(rpm_unit_bp);
      unit_hr_array.push(rpm_unit_hr);
      care_patient_id_array.push(care_patient_id);
      observation_id_bp_array.push(rpm_observation_id_bp);
      observation_id_hr_array.push(rpm_observation_id_hr);
      csseffdate_array.push(csseffdate);
      addressed_arry.push(this.id);
    }
  } else {
    if (jQuery.inArray(this.id, addressed_arry) != -1) {
      var index_address = addressed_arry.indexOf(this.id);
      addressed_arry.splice(index_address, 1);
    }
  }
});
$(document).on('click', '#Addressed', function () {
  var component_id = $('#component_id').val();
  var hd_timer_start = $("#timer_start").val();
  $("#hd_chk_this").val(addressed_arry);
  $("#care_patient_id").val(care_patient_id_array);
  $("#rpm_observation_id_bp").val(observation_id_bp_array);
  $("#rpm_observation_id_hr").val(observation_id_hr_array);
  $("#csseffdate").val(csseffdate_array);
  $("#rpm_unit_bp").val(unit_bp_array);
  $("#rpm_unit_hr").val(unit_hr_array);
  $("#table").val(table_array);
  $("#formname").val(formname_array);
  $("#hd_timer_start").val(hd_timer_start);

  if (addressed_arry.length !== 0) {
    $("#rpm_cm_modal").modal('show');
  } else {
    alert("Please select checkebox");
  }
}); //================================================== previous button click===================================================

$(document).on('click', '#cal1 .fc-prev-button', function () {
  var patient_id = $('#patient_id').val();
  var deviceid = $('#hd_deviceid').val();
  var abc = $(this).closest(".cal").find(".fc-center").html(); // alert(abc);

  if (abc != undefined) {
    var substr = abc.replace('<h2>', '').replace('</h2>', '');
    $('#cald-hid').val(substr);
    var month = moment().month(substr).format("M"); //var year = (moment().month(substr).format("Y"));

    var myArray = substr.split(" ");
    var year = myArray[1];
    util.getChartAjax(patient_id, deviceid, month, year);
  }
});
$(document).on('click', '#cal2 .fc-prev-button', function () {
  var patient_id = $('#patient_id').val();
  var deviceid = $('#hd_deviceid').val();
  var abc = $(this).closest(".cal").find(".fc-center").html(); // alert(abc);

  if (abc != undefined) {
    var substr = abc.replace('<h2>', '').replace('</h2>', '');
    $('#cald-hid').val(substr);
    var month = moment().month(substr).format("M"); //var year = (moment().month(substr).format("Y"));

    var myArray = substr.split(" ");
    var year = myArray[1];
    util.getChartAjax(patient_id, deviceid, month, year);
  }
});
$(document).on('click', '#cal3 .fc-prev-button', function () {
  var patient_id = $('#patient_id').val();
  var deviceid = $('#hd_deviceid').val();
  var abc = $(this).closest(".cal").find(".fc-center").html(); // alert(abc);

  if (abc != undefined) {
    var substr = abc.replace('<h2>', '').replace('</h2>', '');
    $('#cald-hid').val(substr);
    var month = moment().month(substr).format("M"); //var year = (moment().month(substr).format("Y"));

    var myArray = substr.split(" ");
    var year = myArray[1];
    util.getChartAjax(patient_id, deviceid, month, year);
  }
});
$(document).on('click', '#cal4 .fc-prev-button', function () {
  var patient_id = $('#patient_id').val();
  var deviceid = $('#hd_deviceid').val();
  var abc = $(this).closest(".cal").find(".fc-center").html(); // alert(abc);

  if (abc != undefined) {
    var substr = abc.replace('<h2>', '').replace('</h2>', '');
    $('#cald-hid').val(substr);
    var month = moment().month(substr).format("M"); //var year = (moment().month(substr).format("Y"));

    var myArray = substr.split(" ");
    var year = myArray[1];
    util.getChartAjax(patient_id, deviceid, month, year);
  }
});
$(document).on('click', '#cal5 .fc-prev-button', function () {
  var patient_id = $('#patient_id').val();
  var deviceid = $('#hd_deviceid').val();
  var abc = $(this).closest(".cal").find(".fc-center").html(); // alert(abc);

  if (abc != undefined) {
    var substr = abc.replace('<h2>', '').replace('</h2>', '');
    $('#cald-hid').val(substr);
    var month = moment().month(substr).format("M"); //var year = (moment().month(substr).format("Y"));

    var myArray = substr.split(" ");
    var year = myArray[1];
    util.getSpirometerChartAjax(patient_id, deviceid, month, year);
  }
});
$(document).on('click', '#cal6 .fc-prev-button', function () {
  var patient_id = $('#patient_id').val();
  var deviceid = $('#hd_deviceid').val();
  var abc = $(this).closest(".cal").find(".fc-center").html(); // alert(abc);

  if (abc != undefined) {
    var substr = abc.replace('<h2>', '').replace('</h2>', '');
    $('#cald-hid').val(substr);
    var month = moment().month(substr).format("M"); //var year = (moment().month(substr).format("Y"));

    var myArray = substr.split(" ");
    var year = myArray[1];
    util.getChartAjax(patient_id, deviceid, month, year);
  }
}); // ==================================next button==================================

$(document).on('click', '#cal1 .fc-next-button', function () {
  var patient_id = $('#patient_id').val();
  var deviceid = $('#hd_deviceid').val();
  var abc = $(this).closest(".cal").find(".fc-center").html(); // alert(abc);

  if (abc != undefined) {
    var substr = abc.replace('<h2>', '').replace('</h2>', '');
    $('#cald-hid').val(substr);
    var month = moment().month(substr).format("M"); //var year = (moment().month(substr).format("Y"));

    var myArray = substr.split(" ");
    var year = myArray[1];
    util.getChartAjax(patient_id, deviceid, month, year);
  }
});
$(document).on('click', '#cal2 .fc-next-button', function () {
  var patient_id = $('#patient_id').val();
  var deviceid = $('#hd_deviceid').val();
  var abc = $(this).closest(".cal").find(".fc-center").html(); // alert(abc);

  if (abc != undefined) {
    var substr = abc.replace('<h2>', '').replace('</h2>', '');
    $('#cald-hid').val(substr);
    var month = moment().month(substr).format("M"); //var year = (moment().month(substr).format("Y"));

    var myArray = substr.split(" ");
    var year = myArray[1];
    util.getChartAjax(patient_id, deviceid, month, year);
  }
});
$(document).on('click', '#cal3 .fc-next-button', function () {
  var patient_id = $('#patient_id').val();
  var deviceid = $('#hd_deviceid').val();
  var abc = $(this).closest(".cal").find(".fc-center").html(); // alert(abc);

  if (abc != undefined) {
    var substr = abc.replace('<h2>', '').replace('</h2>', '');
    $('#cald-hid').val(substr);
    var month = moment().month(substr).format("M"); //var year = (moment().month(substr).format("Y"));

    var myArray = substr.split(" ");
    var year = myArray[1];
    util.getChartAjax(patient_id, deviceid, month, year);
  }
});
$(document).on('click', '#cal4 .fc-next-button', function () {
  var patient_id = $('#patient_id').val();
  var deviceid = $('#hd_deviceid').val();
  var abc = $(this).closest(".cal").find(".fc-center").html(); // alert(abc);

  if (abc != undefined) {
    var substr = abc.replace('<h2>', '').replace('</h2>', '');
    $('#cald-hid').val(substr);
    var month = moment().month(substr).format("M"); //var year = (moment().month(substr).format("Y"));

    var myArray = substr.split(" ");
    var year = myArray[1];
    util.getChartAjax(patient_id, deviceid, month, year);
  }
});
$(document).on('click', '#cal5 .fc-next-button', function () {
  var patient_id = $('#patient_id').val();
  var deviceid = $('#hd_deviceid').val();
  var abc = $(this).closest(".cal").find(".fc-center").html(); // alert(abc);

  if (abc != undefined) {
    var substr = abc.replace('<h2>', '').replace('</h2>', '');
    $('#cald-hid').val(substr);
    var month = moment().month(substr).format("M"); //var year = (moment().month(substr).format("Y"));

    var myArray = substr.split(" ");
    var year = myArray[1];
    util.getSpirometerChartAjax(patient_id, deviceid, month, year);
  }
});
$(document).on('click', '#cal6 .fc-next-button', function () {
  var patient_id = $('#patient_id').val();
  var deviceid = $('#hd_deviceid').val();
  var abc = $(this).closest(".cal").find(".fc-center").html(); // alert(abc);

  if (abc != undefined) {
    var substr = abc.replace('<h2>', '').replace('</h2>', '');
    $('#cald-hid').val(substr);
    var month = moment().month(substr).format("M"); //var year = (moment().month(substr).format("Y"));

    var myArray = substr.split(" ");
    var year = myArray[1];
    util.getChartAjax(patient_id, deviceid, month, year);
  }
});

var reviewStatusChk = function reviewStatusChk(this_key) {
  $("#time-container").val(AppStopwatch.pauseClock);
  var activedeviceid = $('#hd_deviceid').val();

  if ($(this_key).is(":checked")) {
    var reviewstatus = 1;
  } else {
    var reviewstatus = 0;
  }

  var table = $('#patient-alert-history-list_' + activedeviceid).DataTable();
  var rowdata = table.row($(this_key).parents('tr')).data();
  var component_id = $('#component_id').val();
  var timer_start = $("#timer_start").val();
  var reviewdata = {
    care_patient_id: rowdata.pid,
    rpm_unit_bp: rowdata.unit,
    rpm_unit_hr: rowdata.hrunit,
    csseffdate: rowdata.csseffdate,
    reviewstatus: reviewstatus,
    component_id: component_id,
    rpm_observation_id_bp: rowdata.bptempid,
    rpm_observation_id_hr: rowdata.hrtempid,
    table: 'parent',
    formname: 'rpmReviewDataLink',
    hd_timer_start: timer_start,
    device_id: activedeviceid
  };
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $.ajax({
    type: 'POST',
    url: '/rpm/daily-reviewdatalink-updatereviewstatus',
    data: reviewdata,
    success: function success(data) {
      // debugger;
      $("#AddressSuccess_" + activedeviceid).show();
      var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Review Status Updated Successfully!</strong></div>';
      $("#AddressSuccess_" + activedeviceid).html(txt);
      var moduleid = $("input[name='module_id']").val();
      $("#time-container").val(AppStopwatch.pauseClock);
      util.updateBillableNonBillableAndTickingTimer(patient_id, moduleid);
      $('html, body').animate({
        scrollTop: $('#patient-alert-history-list_' + activedeviceid).offset().top
      }, 'slow');
      setTimeout(function () {
        $("#AddressSuccess_" + activedeviceid).hide();
      }, 5000);
    }
  });
};

var init = function init() {
  var year = new Date().getFullYear();
  var month = new Date().getMonth() + 1; //add +1 for current mnth

  var patient_id = $('#patient_id').val();
  var module_id = $('#module_id').val();
  util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
  util.getPatientCareplanNotes(patient_id, module_id);
  var deviceid = $("#hd_deviceid").val();
  var tabids = $("ul#patientdevicetab").find("li a.active").attr('id');

  if (tabids != "" && tabids != null && tabids != undefined) {
    var ress = tabids.split("_");
    var deviceids = ress[1];
    $('#hd_deviceid').val(deviceids);
  }

  form.ajaxForm("rpm_cm_form", onRPMCMNotesMainForm);
  form.ajaxForm("rpm_review_form", onCaremanagerNotes, function () {
    $("#time-container").val(AppStopwatch.pauseClock);
    var timer_start = $("#timer_start").val();
    var timer_paused = $("#time-container").text();

    if ($("form[name='rpm_review_form'] .form_start_time").val() == "undefined" || $("form[name='rpm_review_form'] .form_start_time").val() == '') {
      var form_start_time = $("#page_landing_times").val();
      $("form[name='rpm_review_form'] .form_start_time").val(form_start_time);
    }

    $("input[name='start_time']").val(timer_start);
    $("input[name='end_time']").val(timer_paused); // $("#timer_start").val(timer_paused);

    $("#timer_end").val(timer_paused);
    $("#time-container").val(AppStopwatch.startClock);
    return true;
  });
  var patient_id = $('#patient_id').val();
  var deviceid = $('#hd_deviceid').val();
  $('#calender1').hide();
  $('#calender' + deviceids).show();
  var abc = $("body").find(".fc-center").html(); // console.log("abc",abc);

  if (abc != undefined) {
    var substr = abc.replace('<h2>', '').replace('</h2>', '');
    $('#cald-hid').val(substr);
    var cal_month = moment().month(substr).format("M");
    var cal_year = moment().month(substr).format("Y");
    util.getChartAjax(patient_id, deviceid, cal_month, cal_year);
  }
}; //init


$('.tabclass').on('click', function () {
  $('#hd_tbl').hide();
  var tabid = this.id;

  if (tabid != "" && tabid != null && tabid != undefined) {
    var res = tabid.split("_");
    $('#hd_deviceid').val(res[1]);
    var activedeviceid = res[1];
  } else {
    var activedeviceid;
  }

  var patient_id = $('#patient_id').val(); // $("form[name='rpm_text_form_" + activedeviceid + "'] #text_template_id option:last").attr("selected", "selected").change();
  // util.getCallScriptsById($("form[name='rpm_text_form_" + activedeviceid + "'] #text_template_id option:selected").val(), '#templatearea_sms', "form[name='rpm_text_form_" + activedeviceid + "'] input[name='template_type_id']", "form[name='rpm_text_form_" + activedeviceid + "'] input[name='content_title']");

  var c_month = (new Date().getMonth() + 1).toString().padStart(2, "0");
  var c_year = new Date().getFullYear();
  var current_MonthYear = c_year + '-' + c_month;
  $("#monthly_" + activedeviceid).val(current_MonthYear);
  $("#monthlyto_" + activedeviceid).val(current_MonthYear); //calender and graph click js

  var deviceid = $('#hd_deviceid').val();

  if (deviceid == '1') {
    var abc = $("#cal1").closest(".cal").find(".fc-center").html();

    if (abc != undefined) {
      var substr = abc.replace('<h2>', '').replace('</h2>', '');
      $('#cald-hid').val(substr);
      var month = moment().month(substr).format("M"); //var year = (moment().month(substr).format("Y"));

      var myArray = substr.split(" ");
      var year = myArray[1];
      $('#calender1').show();
      $('#calender2').hide();
      $('#calender3').hide();
      $('#calender4').hide();
      $('#calender5').hide();
      $('#calender6').hide();
      util.getChartAjax(patient_id, deviceid, month, year);
    }
  }

  if (deviceid == '2') {
    var abc = $("#cal2").closest(".cal").find(".fc-center").html();

    if (abc != undefined) {
      var substr = abc.replace('<h2>', '').replace('</h2>', '');
      $('#cald-hid').val(substr);
      var month = moment().month(substr).format("M"); //var year = (moment().month(substr).format("Y"));

      var myArray = substr.split(" ");
      var year = myArray[1];
      $('#calender1').hide();
      $('#calender2').show();
      $('#calender3').hide();
      $('#calender4').hide();
      $('#calender5').hide();
      $('#calender6').hide();
      util.getChartAjax(patient_id, deviceid, month, year);
    }
  }

  if (deviceid == '3') {
    var abc = $("#cal3").closest(".cal").find(".fc-center").html();

    if (abc != undefined) {
      var substr = abc.replace('<h2>', '').replace('</h2>', '');
      $('#cald-hid').val(substr);
      var month = moment().month(substr).format("M"); //var year = (moment().month(substr).format("Y"));

      var myArray = substr.split(" ");
      var year = myArray[1];
      $('#calender1').hide();
      $('#calender2').hide();
      $('#calender3').show();
      $('#calender4').hide();
      $('#calender5').hide();
      $('#calender6').hide();
      util.getChartAjax(patient_id, deviceid, month, year);
    }
  }

  if (deviceid == '4') {
    var abc = $("#cal4").closest(".cal").find(".fc-center").html();

    if (abc != undefined) {
      var substr = abc.replace('<h2>', '').replace('</h2>', '');
      $('#cald-hid').val(substr);
      var month = moment().month(substr).format("M"); //var year = (moment().month(substr).format("Y"));

      var myArray = substr.split(" ");
      var year = myArray[1];
      $('#calender1').hide();
      $('#calender2').hide();
      $('#calender3').hide();
      $('#calender4').show();
      $('#calender5').hide();
      $('#calender6').hide();
      util.getChartAjax(patient_id, deviceid, month, year);
    }
  }

  if (deviceid == '5') {
    var abc = $("#cal5").closest(".cal").find(".fc-center").html();

    if (abc != undefined) {
      var substr = abc.replace('<h2>', '').replace('</h2>', '');
      $('#cald-hid').val(substr);
      var month = moment().month(substr).format("M"); //var year = (moment().month(substr).format("Y"));

      var myArray = substr.split(" ");
      var year = myArray[1];
      $('#calender1').hide();
      $('#calender2').hide();
      $('#calender3').hide();
      $('#calender4').hide();
      $('#calender5').show();
      $('#calender6').hide(); //util.getChartAjax(patient_id,deviceid,month,year);

      util.getSpirometerChartAjax(patient_id, deviceid, month, year);
    }
  }

  if (deviceid == '6') {
    var abc = $("#cal6").closest(".cal").find(".fc-center").html();

    if (abc != undefined) {
      var substr = abc.replace('<h2>', '').replace('</h2>', '');
      $('#cald-hid').val(substr);
      var month = moment().month(substr).format("M"); //var year = (moment().month(substr).format("Y"));

      var myArray = substr.split(" ");
      var year = myArray[1];
      $('#calender1').hide();
      $('#calender2').hide();
      $('#calender3').hide();
      $('#calender4').hide();
      $('#calender5').hide();
      $('#calender6').show();
      util.getChartAjax(patient_id, deviceid, month, year);
    }
  }
});
$('#rpm_cm_form .modalcancel,.close').click(function () {
  var activedeviceid = $('#hd_deviceid').val();
  var care_patient = $("#care_patient_id").val();

  if (care_patient != "" && care_patient != null && care_patient != undefined) {
    var care_patient_id = care_patient.split(",");
  }

  var hd_chk = $("#hd_chk_this").val();

  if (hd_chk != "" && hd_chk != null && hd_chk != undefined) {
    var hd_chk_this = hd_chk.split(",");

    for (var j = 0; j < hd_chk_this.length; j++) {
      var table_row_id = hd_chk_this[j].split("_")[1];
      $("#monthlyreviewlist_" + activedeviceid + " #activealertpatientstatus_" + table_row_id).trigger('click');
    }
  }
});
window.rpmReviewDataLink = {
  init: init,
  onResult: onResult,
  reviewStatusChk: reviewStatusChk // getChartOnclick:getChartOnclick,
  // util.getChartAjax:util.getChartAjaxWSS 

};

/***/ }),

/***/ 14:
/*!*********************************************************!*\
  !*** multi ./resources/laravel/js/rpmReviewDataLink.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/nivedita/public_html/rcaregit/resources/laravel/js/rpmReviewDataLink.js */"./resources/laravel/js/rpmReviewDataLink.js");


/***/ })

/******/ });