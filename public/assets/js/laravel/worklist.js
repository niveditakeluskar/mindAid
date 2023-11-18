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
/******/ 	return __webpack_require__(__webpack_require__.s = 17);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/laravel/js/worklist.js":
/*!******************************************!*\
  !*** ./resources/laravel/js/worklist.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var URL_POPULATE = "/org/ajax/medication_populate";

var populateForm = function populateForm(id, url) {
  //alert("url"+url);
  $.get(url, id, function (result) {
    console.log(result);

    for (var key in result) {
      form.dynamicFormPopulate(key, result[key]);
    }
  }).fail(function (result) {
    console.error("Population Error:", result);
  });
};

var onSaveConcentData = function onSaveConcentData(formObj, fields, response) {
  if (response.status == 200) {}
};

var onAddPatientActivityData = function onAddPatientActivityData(formObj, fields, response) {
  // console.log(response.data+"testresponse");
  if (response.status == 200 && $.trim(response.data) == '') {
    $("#success").show();
    getPatientList();
    $("#add_activity_form")[0].reset();
    $('#add-activities').modal('hide');
    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">� </button><strong>Patient Activity Added Successfully!</strong></div>';
    $("#success").html(txt);
    $("#month-search").trigger('click');
    var scrollPos = $(".main-content").offset().top;
    $(window).scrollTop(scrollPos);
    setTimeout(function () {
      $("#success").hide();
    }, 3000);
  } else if (response.status == 200 && $.trim(response.data) != '' && $.trim(response.data) == 'time is not more than 24 hours') {
    $("#success").show();
    getPatientList();
    $("#add_activity_form")[0].reset();
    $('#add-activities').modal('hide');
    var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">� </button><strong>Time should not more than 24 hours</strong></div>';
    $("#success").html(txt);
    $("#month-search").trigger('click');
    var scrollPos = $(".main-content").offset().top;
    setTimeout(function () {
      $("#success").hide();
    }, 3000);
  } else {
    if (response.data.errors.activity_id) {
      $("#activity_div").html('The activity field is required.');
    } else {
      $("#activity_div").html('');
    }
  }
};

$('body').on('click', '.patient_activity', function () {
  $('#add-activities').modal('show');
  var id = $(this).data('id');
  var spl = parts = id.split("/");
  var patient_id = spl[0];
  var timestart = spl[1];
  var practice_id = spl[2];
  var module_id = spl[3];
  $("#module_id").val(module_id);
  $("#patient_id").val(patient_id);
  $("#timestart").val(timestart);
  $("#practice_id").val(practice_id);
  $("#activity").val('');
  $("#net_time").val('');
  $("#net_time").removeClass("is-invalid");
  $("#net_time").next(".invalid-feedback").html('');
  $("#comments").val('');
  $("#comments").removeClass("is-invalid");
  $("#comments").next(".invalid-feedback").html('');
  $('#activity_id').val('').trigger('change');
  $("#activity_div").html('');
});
$("#activity_id").on("change", function () {
  var drpdwn_value = $("#activity_id").val();
  var devicetrn = $('#activity_id option:selected').text();

  if (devicetrn == 'Device Education') {
    $('#datediv').show();
  } else {
    $('#datediv').hide();
  }

  $('#net_time').val('');
  $('#timer_type').val('');
  $('#net_time').prop('readonly', false);
  $('#comments').val('');

  if (drpdwn_value == '') {} else {
    var opt = $(this).find(':selected');
    var sel = opt.text();
    var sel_id = opt.val();
    var practice_id = $("#practice_id").val();
    $("#activity").val(sel); //var og = opt.closest('optgroup').attr('label');

    $.ajax({
      type: 'get',
      url: '/task-management/activity_time/' + sel_id + '/' + practice_id,
      // data: {"id": sel_id},
      success: function success(response) {
        console.log(JSON.stringify(response) + 'activity_time');

        if (response != '') {
          var get_time = response[0].default_time;
          var time_required = response[0].time_required;
          var timer_type = response[0].timer_type;

          if (timer_type == 1) {
            // alert('11');
            if (get_time != '' && get_time != '00:00:00') {
              $('#net_time').val(get_time);
              $("#timer_type").val(timer_type);
              $('#net_time').prop('readonly', false); // $('#notes').hide();
            } else {
              $('#net_time').val('');
              $("#timer_type").val(timer_type);
              $('#net_time').prop('readonly', false); // $('#notes').show();
            }
          } else {
            // alert('22');
            // console.log('get_time2'+response[0].default_time);
            // console.log('time_required2'+ response[0].time_required);
            if (get_time != '') {
              $('#net_time').val(get_time);
              $("#timer_type").val(timer_type);
              $('#net_time').prop('readonly', true); // $('#notes').show();
            }

            if (time_required != '' && time_required != 'null' && time_required != null) {
              $('#net_time').val(time_required);
              $("#timer_type").val('2');
              $('#net_time').prop('readonly', true); // $('#notes').hide();
            }
          }
        }
      }
    });
  }
});

var init = function init() {
  form.ajaxForm("add_activity_form", onAddPatientActivityData, function () {
    return true;
  });
  $("form[name='concent_update_form']").submit(function (e) {
    e.preventDefault();
    form.ajaxSubmit("concent_update_form", onSaveConcentData);
  });
  form.ajaxForm("active_deactive_form", ccmcpdcommonJS.onSaveActiveDeactive, function () {
    var checkforworklist = $("form[name='active_deactive_form'] #worklistclick").val(); //alert(checkforworklist);

    if (checkforworklist != '1') {
      $("#time-container").val(AppStopwatch.pauseClock);
    }

    var timer_start = $("#timer_start").val();
    var timer_paused = $("#time-container").text();
    $("#active_deactive_form input[name='start_time']").val(timer_start);
    $("#active_deactive_form input[name='end_time']").val(timer_paused); // $("#timer_start").val(timer_paused);

    $("#timer_end").val(timer_paused);

    if (checkforworklist != '1') {
      $("#time-container").val(AppStopwatch.startClock);
    }

    var formdate = $('form[name="active_deactive_form"] #fromdate').val();
    var todate = $('form[name="active_deactive_form"] #todate').val();
    var eDate = new Date(todate);
    var sDate = new Date(formdate);

    if (todate != '' && todate != null) {
      if (formdate != '' && todate != '' && sDate > eDate) {
        alert("Please ensure that the Enrolled To Date is greater than or equal to the Enrolled From Date.");
        return false;
      }
    }

    return true;
  });
};

window.worklist = {
  init: init //onResult: onResult

};

/***/ }),

/***/ 17:
/*!************************************************!*\
  !*** multi ./resources/laravel/js/worklist.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /var/www/html/mnt1/rcaregit/resources/laravel/js/worklist.js */"./resources/laravel/js/worklist.js");


/***/ })

/******/ });