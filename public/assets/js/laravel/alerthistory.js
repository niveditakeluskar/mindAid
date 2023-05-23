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
/******/ 	return __webpack_require__(__webpack_require__.s = 9);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/laravel/js/alerthistory.js":
/*!**********************************************!*\
  !*** ./resources/laravel/js/alerthistory.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var URL_POPULATE = "/rpm/ajax/populate-vital-alerts";
var parameterscnt = 0;
var newoperation = 0;
var i = 0;

var populateForm = function populateForm(data, url) {
  $.get(url, data, function (result) {
    console.log(result); // console.log(result.rpm_cm_form[0].notes);

    var len = result.rpm_cm_form.length; // console.log(len);

    if (len > 0) {
      var a = result.rpm_cm_form[0].notes;
      $("#notes").val(a);
    } else {
      $("#notes").val("");
    }

    for (var key in result) {
      form.dynamicFormPopulate(key, result[key]);
    }
  }).fail(function (result) {
    console.error("Population Error:", result);
  });
};

var onRPMCMNotesMainForm = function onRPMCMNotesMainForm(formObj, fields, response) {
  // console.log("response" + response.status); 
  // console.log("responsedata" + response);    
  if (response.status == 200 && $.trim(response.data) == '') {
    $("#rpm_cm_form")[0].reset();
    getAlertHistoryList();
    $("#success").show();
    $('#rpm_cm_modal').modal('hide');
    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Care Manager Notes Updated Successfully!</strong></div>';
    $("#success").html(txt);
    var scrollPos = $(".main-content").offset().top;
    $(window).scrollTop(scrollPos);
    setTimeout(function () {
      $("#success").hide();
    }, 3000);
  } // else if(response.status == 200 &&  $.trim(response.data)=='already-exsits'){
  //     $("#add_scheduler_form")[0].reset();
  //     getSchedulerlisting();
  //     $("#success").show();
  //     $('#rpm_cm_modal').modal('hide'); 
  //     var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Scheduler Already Exsits for the given Services,Date,Day and Time!</strong></div>';
  //     $("#success").html(txt);
  //     var scrollPos = $(".main-content").offset().top;
  //     $(window).scrollTop(scrollPos);
  //     setTimeout(function () {
  //         $("#success").hide();
  //     }, 3000);
  // } 

}; // var onEditSchedulerMainForm = function (formObj, fields, response) {
//     if (response.status == 200) {
//         $("#edit_scheduler_form")[0].reset();
//         getSchedulerlisting();
//         $("#success").show();
//         $('#edit_scheduler_modal').modal('hide');
//         var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Scheduler Updated Successfully!</strong></div>';
//         $("#success").html(txt);
//         var scrollPos = $(".main-content").offset().top;
//         $(window).scrollTop(scrollPos);
//         setTimeout(function () {
//             $("#success").hide();
//         }, 3000);
//     }
// };


var init = function init() {
  //alert("IN init");
  form.ajaxForm("rpm_cm_form", onRPMCMNotesMainForm, function () {
    // var checkboxescount = $('input[type="checkbox"]:checked').length; 
    // if(checkboxescount == 0)
    // {
    //     $("#services-error").show();
    //     var txt = 'Please select atleast one service'; 
    //     $("#services-error").html(txt);  
    //     return false;   
    // }
    // else{
    //     $("#services-error").hide();  
    //     return true;         
    // }
    return true;
  });
  $('#alert-history-list tbody').on('click', '.activealertpatientstatus', function () {
    //if($(this).is(":checked")){
    var table = $('#alert-history-list').DataTable();
    var rowdata = table.row($(this).parents('tr')).data(); //alert("rowdata".rowdata);
    // console.log("inside activealertclick"); 
    // console.log(rowdata);  

    var pfname = rowdata.pfname;
    var plname = rowdata.plname;
    var unit = rowdata.unit;
    var patientname = pfname + " " + plname;
    var rpmid = rowdata.tempid;
    var pid = rowdata.pid;
    var csseffdate = rowdata.csseffdate;
    var reading = rowdata.reading;
    var unit = rowdata.unit;
    var urlmm = "/rpm/monthly-monitoring/" + pid;
    $('#gotomm').html('<a href="' + urlmm + '"><u>Go To Monthly Monitoring</u></a>');

    if (unit == '%') {
      var vital = 'Oxygen';
    } else if (unit == 'mm[Hg]') {
      var vital = 'Blood Pressure';
    } else if (unit == 'beats/minute') {
      var vital = 'Heartrate';
    } else if (unit == 'mg/dl') {
      // alert('glucose');
      var vital = 'Glucose';
    } else if (unit == 'lbs') {
      var vital = 'Weight';
    } else if (unit == 'degrees F') {
      var vital = 'Temperature';
    } else {
      var vital = 'Spirometer';
    }

    $("#rpm_cm_modal").modal('show');
    $("#patientname").text(patientname);
    $("#patientvital").text(vital);
    $("#patient_id").val(pid);
    $("#vital").val(vital);
    $("#rpm_observation_id").val(rpmid);
    $("#notes").val();

    if (vital == 'Blood Pressure') {
      var r = reading.split("/");
      var newreading = r.join(" - "); // console.log(r);
      // console.log(newreading);

      var reading = newreading;
    }

    if (vital == 'Spirometer') {
      var _r = reading.split("/");

      var newreading = _r.join(" - "); // console.log(r);
      // console.log(newreading);


      var reading = newreading;
    }

    console.log(vital);
    var data = "";
    var formpopulateurl = URL_POPULATE + "/" + pid + "/" + vital + "/" + csseffdate + "/" + reading; // var a = decodeURI(formpopulateurl);
    // console.log(formpopulateurl);
    // console.log(a);

    populateForm(data, formpopulateurl);
  }); //  form.ajaxForm("edit_scheduler_form", onEditSchedulerMainForm, function () {
  //     return true;
  // });
  // $('body').on('click', '#scheduler-list .editScheduler', function () {
  //     $("#edit_scheduler_form")[0].reset();
  //     $('#edit_scheduler_modal').modal('show');
  //     var sPageURL = window.location.pathname;
  //     var parts = sPageURL.split("/"); 
  //     var id = $(this).data('id');
  //     var data = "";
  //     var formpopulateurl = URL_POPULATE + "/" + id;
  //     populateForm(data, formpopulateurl); 
  // });     
};

window.alerthistory = {
  init: init
};

/***/ }),

/***/ 9:
/*!****************************************************!*\
  !*** multi ./resources/laravel/js/alerthistory.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /var/www/html/rcarerepo/rcaregit/resources/laravel/js/alerthistory.js */"./resources/laravel/js/alerthistory.js");


/***/ })

/******/ });