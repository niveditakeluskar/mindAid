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
/******/ 	return __webpack_require__(__webpack_require__.s = 18);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/webpack/buildin/module.js":
/*!***********************************!*\
  !*** (webpack)/buildin/module.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = function(module) {
	if (!module.webpackPolyfill) {
		module.deprecate = function() {};
		module.paths = [];
		// module.parent = undefined by default
		if (!module.children) module.children = [];
		Object.defineProperty(module, "loaded", {
			enumerable: true,
			get: function() {
				return module.l;
			}
		});
		Object.defineProperty(module, "id", {
			enumerable: true,
			get: function() {
				return module.i;
			}
		});
		module.webpackPolyfill = 1;
	}
	return module;
};


/***/ }),

/***/ "./resources/laravel/js/patientEnrollment.js":
/*!***************************************************!*\
  !*** ./resources/laravel/js/patientEnrollment.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(module) {/**
 * 
 */
var URL_POPULATE = "/patients/ajax/populate";
var baseURL = window.location.origin + '/';
var patient_id = $("#hidden_id").val();
var sPageURL = window.location.pathname;
parts = sPageURL.split("/"), module = parts[parts.length - 3], sub_module = parts[parts.length - 2];
/**
 * Populate the form of the given patient
 *
 * 
 */

var populateForm = function populateForm(data, url) {
  $.get(url, data, function (result) {
    // console.log(result);
    for (var key in result) {
      form.dynamicFormPopulate(key, result[key]);

      if (key == "fin_number_form") {
        var finnumber = result[key][0].fin_number;
        $("form[name='fin_number_form'] #fin_number_new").val(result[key][0].fin_number);
      }

      if (key == "enroll_services_form") {
        patient_services = result[key].dynamic['patientService'];

        for (group in patient_services) {
          $("input[name='enroll[" + patient_services[group]['module_id'] + "]']").prop("checked", true);
        }
      }

      if (key == "edit_diagnosis_careplan_form") {
        $('input[name="codenm"]').result[key]["static"]['code'];
      }

      if (key == "devices_form") {
        //console.log(result[key][0].device_code);
        //console.log(result[key][0].pdevice_id);
        var partner_id = result[key][0].partner_id;
        var pdevice_id = result[key][0].pdevice_id;
        $('#idd').val(result[key][0].id);
        $("form[name='devices_form'] #device_id").val(result[key][0].device_code);
        $("#partner_id").val(partner_id); //$("#partner_devices_id").val(pdevice_id);

        if (partner_id != null) {
          util.updatePartnerDevice(partner_id, $("form[name='" + key + "'] #partner_devices_id"), pdevice_id);
        } else {
          util.updatePartnerDevice(parseInt(partner_id), $("form[name='" + key + "'] #partner_devices_id"));
        }
      }
    }
  }).fail(function (result) {
    console.error("Population Error:", result);
  });
};

var showSubmitMsg = function showSubmitMsg(formName, alertMsg, alertType) {
  if (alertType == "success") {
    $("form[name='text_form'] .alert").addClass("alert-success");
    $("form[name='text_form'] .alert").removeClass("alert-danger");
  } else if (alertType == "danger") {
    $("form[name='text_form'] .alert").addClass("alert-danger");
    $("form[name='text_form'] .alert").removeClass("alert-success");
  }

  $("form[name='" + formName + "'] .alert").html(alertMsg);
  $("form[name='" + formName + "'] .alert").show();
  var scrollPos = $(".main-content").offset().top;
  $(window).scrollTop(scrollPos);
};

var onTextSave = function onTextSave(formObj, fields, response) {
  if (response.status == 200) {
    var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Text data saved successfully! </strong><span id="text"></span>';
    showSubmitMsg('text_form', text, 'success');
    var timer_paused = $("form[name='text_form'] input[name='end_time']").val();
    $("#timer_start").val(timer_paused);
    util.totalTimeSpentByCM(); // $("#timer_end").val(timer_paused);
    // goToNextStep("ccm-hippa-icon-tab");
  } else {
    var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Text data not saved successfully! </strong><span id="text"></span>';
    showSubmitMsg('text_form', text, 'danger');
  }
};

var onEmailSave = function onEmailSave(formObj, fields, response) {
  if (response.status == 200) {
    var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Email data saved successfully! </strong><span id="text"></span>';
    showSubmitMsg('email_form', text, 'success');
    var timer_paused = $("form[name='email_form'] input[name='end_time']").val();
    $("#timer_start").val(timer_paused);
    util.totalTimeSpentByCM(); // $("#timer_end").val(timer_paused);
  } else {
    var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Email data not saved successfully! </strong><span id="text"></span>';
    showSubmitMsg('email_form', text, 'danger');
  }
};

var onSaveCallSatus = function onSaveCallSatus(formObj, fields, response) {
  if (response.status == 200) {
    util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
    util.totalTimeSpentByCM();
    var enrollment_id = response.data.enrollment_id;
    $(".enrollment_id").val(enrollment_id); //var stepid=val(enrollment_id);

    $("form[name='call_status_form'] .alert").show();
    var scrollPos = $(".main-content").offset().top;
    $(window).scrollTop(scrollPos); //  var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Data saved successfully! </strong><span id="text"></span>';
    //  showSubmitMsg('call_status_form', text, 'success');

    $("#error_msg").html('');
    util.getToDoListData($("#hidden_id").val(), $("form[name='homeservice_form'] input[name='module_id']").val());
    var call_status = $("form[name='call_status_form'] input[name='call_status']:checked").val();
    var call_continue_status = $("form[name='call_status_form'] input[name='call_continue_status']:checked").val();

    if (call_status == '1' && call_continue_status == '1') {
      setTimeout(function () {
        goToNextStep("s2");
      }, 3000); //comnt by me
    } else if (call_status == '1' && call_continue_status == '0') {
      //$("#time-container").val(AppStopwatch.pauseClock);
      setTimeout(function () {
        goToNextStep("s4"); //comnt by me

        $("form[name='call_status_form'] .alert").hide();
      }, 3000); //$("#s7").removeClass("disabled");
      //$("#step-7 #enrollment_messages").text("Call Ended..!!");
      //setTimeout(function () { goToNextStep("s7"); }, 3000);
    } else if (call_status == '2') {
      $("#time-container").val(AppStopwatch.pauseClock);
      $("#s7").removeClass("disabled");
      $("#step-7 #enrollment_messages").text("Call Not Answered..!!"); //comnt by me

      setTimeout(function () {
        goToNextStep("s7");
      }, 3000);
    }

    if (response.data.stepid == '4') {
      window.location.href = '/patients/patient-enrollment/patients';
    }

    setTimeout(function () {
      $("form[name='call_status_form'] .alert").hide();
    }, 3000);
    var timer_paused = $("form[name='call_status_form'] input[name='end_time']").val();
    $("#timer_start").val(timer_paused);
    $('.form_start_time').val(response.data.form_start_time); // $("#timer_end").val(timer_paused);
  } else {
    // var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Please Fill All Mandatory Fields! </strong><span id="text"></span>';
    // showSubmitMsg('call_status_form', text, 'danger');
    var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please Fill All Mandatory Fields!</strong></div>';
    $("#error_msg").html(txt);
  }
};

var onSaveCallSatusFinal = function onSaveCallSatusFinal(formObj, fields, response) {
  // console.log("test resopnse"+response.status);
  if (response.status == 200) {
    util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
    util.totalTimeSpentByCM();
    $("#error_msg3").html('');
    $("form[name='call_status_form_final'] #success-alert").show();
    $("form[name='call_status_form_final'] #success-alert").css('display', 'block');
    setTimeout(function () {
      window.location.href = '/patients/patient-enrollment/patients';
      $("form[name='call_status_form_final'] #success-alert").hide();
    }, 3000);
    util.getToDoListData($("#hidden_id").val(), $("#page_module_id").val());
    var timer_paused = $("form[name='call_status_form_final'] input[name='end_time']").val();
    $("#timer_start").val(timer_paused);
    $('.form_start_time').val(response.data.form_start_time); // $("#timer_end").val(timer_paused);
  } else {
    // var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Please Fill All Mandatory Fields! </strong><span id="text"></span>';
    // showSubmitMsg('call_status_form_final', text, 'danger');
    var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please Fill All Mandatory Fields!</strong></div>';
    $("#error_msg3").html(txt);
  }
};

var onSaveEnrollmentSatus = function onSaveEnrollmentSatus(formObj, fields, response) {
  if (response.status == 200) {
    util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
    util.totalTimeSpentByCM();
    var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Enrollment status saved successfully! </strong><span id="text"></span>';
    showSubmitMsg('enrollment_status_form', text, 'success');
    var enrol_status = $("form[name='enrollment_status_form'] input[name='enrol_status']:checked").val();

    if (enrol_status == '1') {
      setTimeout(function () {
        goToNextStep("s3");
      }, 3000);
    } else if (enrol_status == '2') {
      $("#time-container").val(AppStopwatch.pauseClock);
      var call_back_date = $("form[name='enrollment_status_form'] input[name='call_back_date']").val();
      var call_back_time = $("form[name='enrollment_status_form'] input[name='call_back_time']").val();
      $("#s7").removeClass("disabled");
      $("#step-7 #enrollment_messages").text("Call Back Date-" + call_back_date + " Time-" + call_back_time);
      setTimeout(function () {
        goToNextStep("s7");
      }, 3000);
      util.getToDoListData($("#hidden_id").val(), $("#page_module_id").val());
    } else if (enrol_status == '3') {
      $("#time-container").val(AppStopwatch.pauseClock);
      $("#s7").removeClass("disabled");
      $("#step-7 #enrollment_messages").text("Refused to enroll..!!");
      setTimeout(function () {
        goToNextStep("s7");
      }, 3000);
    }

    var timer_paused = $("form[name='enrollment_status_form'] input[name='end_time']").val();
    $("#timer_start").val(timer_paused); // $("#timer_end").val(timer_paused);
  } else {
    var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Enrollment status not saved successfully! </strong><span id="text"></span>';
    showSubmitMsg('enrollment_status_form', text, 'danger');
  }
};

var onSaveEnrollServicesSatus = function onSaveEnrollServicesSatus(formObj, fields, response) {
  if (response.status == 200) {
    util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
    var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Enroll services saved successfully! </strong><span id="text"></span>';
    showSubmitMsg('enroll_services_form', text, 'success');
    util.totalTimeSpentByCM();
    setTimeout(function () {
      goToNextStep("s4");
    }, 3000);
    var timer_paused = $("form[name='enroll_services_form'] input[name='end_time']").val();
    $("#timer_start").val(timer_paused); // $("#timer_end").val(timer_paused);
  } else {
    var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Enroll services not saved successfully! </strong><span id="text"></span>';
    showSubmitMsg('enroll_services_form', text, 'danger');
  }
};

var onSaveChecklist = function onSaveChecklist(formObj, fields, response) {
  // console.log("test" + response.status);
  if (response.status == 200) {
    util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
    util.totalTimeSpentByCM();
    $("#error_msg1").html('');
    $('#check_hidden').val(response.data.history_id); // var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Enrollment checklist saved successfully! </strong><span id="text"></span>';
    // showSubmitMsg('checklist_form', text, 'success');

    $("form[name='checklist_form'] .alert").show();

    if ($("input[name='patient_reviews_and_signs']:checked").val() == 1) {
      setTimeout(function () {
        goToNextStep("s3");
        $("form[name='patient_reviews_and_signs'] .alert").hide();
      }, 3000);
    } else {
      setTimeout(function () {
        goToNextStep("s4");
        $("form[name='patient_reviews_and_signs'] .alert").hide();
      }, 3000);
    }

    var timer_paused = $("form[name='checklist_form'] input[name='end_time']").val();
    $("#timer_start").val(timer_paused);
    $('.form_start_time').val(response.data.form_start_time); // $("#timer_end").val(timer_paused);
  } else {
    var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please Fill All Mandatory Fields!</strong></div>';
    $("#error_msg1").html(txt); // if(response.data.errors.enroll_status){
    //     // console.log("elsetest"+response.data.errors.enroll_status);
    //     // $("form[name='checklist_form'] input[name='enroll_status']").addClass("is-invalid");
    //     $("form[name='checklist_form'] #invalid_feedback]").html('Patient review and sign field is required.');
    // }
  }
};

var onSaveFinalizationChecklistStatus = function onSaveFinalizationChecklistStatus(formObj, fields, response) {
  if (response.status == 200) {
    util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
    util.totalTimeSpentByCM(); //console.log(response.data);

    $("#error_msg2").html('');
    $('#final_hidden').val(response.data); // var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong> Patient enrolled successfully! </strong><span id="text"></span>';
    // showSubmitMsg('finalization_checklist_form', text, 'success');

    $("form[name='finalization_checklist_form'] .alert").show();
    setTimeout(function () {
      goToNextStep("s6");
      window.location.href = "/patients/patient-enrollment/patients";
      $("form[name='finalization_checklist_form'] .alert").hide();
    }, 3000);
    var timer_paused = $("form[name='finalization_checklist_form'] input[name='end_time']").val();
    $("#timer_start").val(timer_paused);
    $('.form_start_time').val(response.data.form_start_time); // $("#timer_end").val(timer_paused);
  } else {
    var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill all mandatory fields!</strong></div>';
    $("#error_msg2").html(txt);
  }
};

var onSaveConcentData = function onSaveConcentData(formObj, fields, response) {
  if (response.status == 200) {
    $("#concent-alert").show();
    setTimeout(function () {
      $("#concent-alert").hide();
      $('#concent-form').modal('hide');
    }, 3000);
  }
};

var onSaveChecklistSatus = function onSaveChecklistSatus(formObj, fields, response) {
  if (response.status == 200) {
    util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
    util.totalTimeSpentByCM();
    $("#error_msg").html('');
    var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Enrollment checklist saved successfully! </strong><span id="text"></span>';
    showSubmitMsg('checklist_status_form', text, 'success');
    $("#time-container").val(AppStopwatch.pauseClock); // $("#s7").removeClass("disabled");
    // $("#step-7 #enrollment_messages").text("Patient Enrollment Completed..!!");

    setTimeout(function () {
      goToNextStep("s3");
    }, 3000);
    var timer_paused = $("form[name='checklist_status_form'] input[name='end_time']").val();
    $("#timer_start").val(timer_paused); // $("#timer_end").val(timer_paused);
  } else {
    var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please Fill All Mandatory Fields! </strong></div>';
    $("#error_msg").html(txt);
  }
};

var onSubmit = function onSubmit(name) {
  //$(".tab-error").removeClass("tab-error");
  $("form[name=" + name + "] input[name='timer']").val("12");
  return true;
};

function goToNextStep(id) {
  $("#" + id).removeClass("disabled");
  $('#' + id).click(); // setTimeout($('#' + id).click(),30000);
}

function ajaxRenderTree(obj, label, id, count, tree_key) {
  alert('here');
}

var onMasterDevices = function onMasterDevices(formObj, fields, response) {
  if (response.status == 200) {
    //$("#add-patient-devices").modal('hide');
    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Devices saved successfully!</strong></div>';
    $("#devices_success").html(txt);
    $("#devices_success").show();
    setTimeout(function () {
      $("#devices_success").hide();
    }, 3000);
    var patient_id = $("#devices_form input[name='patient_id']").val();
    var module_id = $("#devices_form input[name='module_id']").val();
    $("#devices_form input[name='device_id']").val('');
    $('#partner_id').val('');
    $('#partner_devices_id').val('');
    $("form[name=\"devices_form\"]").find(".is-invalid").removeClass("is-invalid");
    $("form[name=\"devices_form\"]").find(".invalid-feedback").html("");
    util.totalTimeSpentByCM();
    util.getPatientStatus(patient_id, module_id);
    ccmcpdcommonJS.renderDeviceTableData();
    var timer_paused = $("form[name='devices_form'] input[name='end_time']").val();
    $("#timer_start").val(timer_paused);
  } else {
    var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill mandatory fields!</strong></div>';
    $("#devices_success").html(txt);
    $("#devices_success").show();
    setTimeout(function () {
      $("#devices_success").hide();
    }, 3000);
  }
};

var onFinNumber = function onFinNumber(formObj, fields, response) {
  if (response.status == 200) {
    util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
    $("#patient-finnumber").modal('hide');
    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Fin Number Update successfully!</strong></div>';
    $("#success").html(txt);
    $("#success").show();
    setTimeout(function () {
      $("#success").hide();
    }, 3000);
    var finnumber = $("#fin_number_form input[name='fin_number']").val();
    console.log(finnumber);
    $("a span#fin_number").html(finnumber);
    var timer_paused = $("form[name='fin_number_form'] input[name='end_time']").val();
    $("#timer_start").val(timer_paused);
    $('.form_start_time').val(response.data.form_start_time);
    var patient_id = $("input[name='patient_id']").val();
    var module_id = $("input[name='module_id']").val();
    util.totalTimeSpentByCM();
  } else {
    var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill mandatory fields!</strong></div>';
    $("#success").html(txt);
    $("#success").show();
    setTimeout(function () {
      $("#success").hide();
    }, 3000);
  }
};

var onPersonalNotes = function onPersonalNotes(formObj, fields, response) {
  if (response.status == 200) {
    util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val()); // $("#personal_notes_form")[0].reset();

    $("#personal-notes").modal('hide');
    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Personal notes saved successfully!</strong></div>';
    $("#success").html(txt);
    $("#success").show();
    setTimeout(function () {
      $("#success").hide();
    }, 3000);
    var patient_id = $("#personal_notes_form input[name='patient_id']").val();
    var module_id = $("#personal_notes_form input[name='module_id']").val();
    util.totalTimeSpentByCM();
    util.getPatientStatus(patient_id, module_id);
    var timer_paused = $("form[name='personal_notes_form'] input[name='end_time']").val();
    $("#timer_start").val(timer_paused);
    $('.form_start_time').val(response.data.form_start_time);
  } else {
    var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill mandatory fields!</strong></div>';
    $("#success").html(txt);
    $("#success").show();
    setTimeout(function () {
      $("#success").hide();
    }, 3000);
  }
};

var onPartOfResearchStudy = function onPartOfResearchStudy(formObj, fields, response) {
  if (response.status == 200) {
    if (sub_module == 'care-plan-development') {
      util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
    } else {
      util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
    }

    util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val()); // $("#personal_notes_form")[0].reset();

    $("#part-of-research-study").modal('hide');
    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Part Of Research Study Inserted Successfully!</strong></div>';
    $("#success").html(txt);
    $("#success").show();
    setTimeout(function () {
      $("#success").hide();
    }, 3000);
    util.totalTimeSpentByCM();
    var patient_id = $("#part_of_research_study_form input[name='patient_id']").val();
    var module_id = $("#part_of_research_study_form input[name='module_id']").val();
    util.getPatientStatus(patient_id, module_id);
    var timer_paused = $("form[name='part_of_research_study_form'] input[name='end_time']").val();
    $("#timer_start").val(timer_paused);
    $('.form_start_time').val(response.data.form_start_time);
  } else {
    var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill mandatory fields!</strong></div>';
    $("#success").html(txt);
    $("#success").show();
    setTimeout(function () {
      $("#success").hide();
    }, 3000);
  }
};

var onPatientThreshold = function onPatientThreshold(formObj, fields, response) {
  if (response.status == 200) {
    if (sub_module == 'care-plan-development') {
      util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
    } else {
      util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
    }

    util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val()); // $("#personal_notes_form")[0].reset();

    $("#patient-threshold").modal('hide');
    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Patient Threshold Inserted Successfully!</strong></div>';
    $("#success").html(txt);
    $("#success").show();
    setTimeout(function () {
      $("#success").hide();
    }, 3000);
    util.totalTimeSpentByCM();
    var patient_id = $("#patient_threshold_form input[name='patient_id']").val();
    var module_id = $("#patient_threshold_form input[name='module_id']").val();
    util.getPatientStatus(patient_id, module_id);
    var timer_paused = $("form[name='patient_threshold_form'] input[name='end_time']").val();
    $("#timer_start").val(timer_paused);
    $('.form_start_time').val(response.data.form_start_time);
  } else {
    var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill mandatory fields!</strong></div>';
    $("#success").html(txt);
    $("#success").show();
    setTimeout(function () {
      $("#success").hide();
    }, 3000);
  }
};

var onAdditionalDeviceEmail = function onAdditionalDeviceEmail(formObj, fields, response) {
  if (response.status == 200) {
    if (sub_module == 'care-plan-development') {
      util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
    } else {
      util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
    }

    util.totalTimeSpentByCM();
    $("#device-alert").show();
    $('#add_replace_device').change(); //$("form[name='patient_add_device_form'] #email_title option:first").attr("selected", "selected").change();

    $("form[name='patient_add_device_form'] #email_title option:last").attr("selected", "selected").change(); // $("#add_replace_device").reset();

    setTimeout(function () {
      $("#device-alert").hide();
    }, 3000);
  }
};

var enrollModule = function enrollModule(patient_id, module_id) {
  //alert("pid"+patient_id);
  //alert("mid"+module_id);
  if (module_id != '') {
    var url = "/patients/patient-enrollment/" + patient_id + "/" + module_id;
    window.location.href = url;
  } // populateForm(patient_id, "ajax/populatetimeslots/"+ patient_id);

};

var init = function init() {
  $("form").append("<input type='hidden' name=timearr[form_start_time] class='timearr form_start_time'><input type='hidden' name=timearr['form_save_time'] class='form_save_time'><input type='hidden' name=timearr['pause_start_time']><input type='hidden' name=timearr['pause_end_time']><input type='hidden' name=timearr['extra_time']>");
  util.setLandingTime();
  util.redirectToWorklistPage();
  util.stepWizard('tsf-wizard-1');
  util.getPatientDetails($("#hidden_id").val(), $("#page_module_id").val());
  util.getToDoListData($("#hidden_id").val(), $("#page_module_id").val());
  util.gatCaretoolData($("#hidden_id").val(), $("#page_module_id").val());
  util.getPatientStatus($("#hidden_id").val(), $("#page_module_id").val());

  if ($(".form_start_time").val() == "undefined" || $(".form_start_time").val() == '') {
    var start_time = null;
  } else {
    var start_time = $(".form_start_time").val();
  }

  util.updateTimeEveryMinutes(patient_id, module_id, start_time);
  $("#main-text-btn").click(function () {
    $("#text_step_div").show();
    $("#email_step_div").hide();
    $("#call_step_div").hide();
  });
  $("#main-email-btn").click(function () {
    $("#text_step_div").hide();
    $("#email_step_div").show();
    $("#call_step_div").hide();
  });
  $("#main-call-btn").click(function () {
    $("#text_step_div").hide();
    $("#email_step_div").hide();
    $("#call_step_div").show(); // util.getQuestionnaireScript( $("form[name='checklist_form'] input[name='module_id']").val(), $("form[name='checklist_form'] input[name='component_id']").val(), $("form[name='checklist_form'] input[name='stage_id']").val(), $("form[name='checklist_form'] input[name='step_id']").val(), "#checklist_questionnaire_div");
  }); //select default value for text script(last entered script)

  $("form[name='text_form'] #text_template_id option:last").attr("selected", "selected").change();
  util.getCallScriptsById($("form[name='text_form'] #text_template_id").val(), '#templatearea_sms', 'form[name="text_form"] input[name="template_type_id"]', '#text_content_title');
  $("form[name='email_form'] #email_template_id option:last").attr("selected", "selected").change();
  util.getCallScriptsById($("form[name='email_form'] #email_template_id").val(), '#email_template_area', 'form[name="email_form"] input[name="template_type_id"]', '#email_content_title', 'form[name="email_form"] #subject');
  $("form[name='enrollment_status_form'] #enrollment_status_template_id option:last").attr("selected", "selected").change();
  util.getCallScriptsById($("form[name='enrollment_status_form'] #enrollment_status_template_id").val(), '.enrollment_status_script', 'form[name="enrollment_status_form"] input[name="template_type_id"]', 'form[name="enrollment_status_form"] input[name="content_title"]');
  $("form[name='text_form']").submit(function (e) {
    e.preventDefault();
    $("#time-container").val(AppStopwatch.pauseClock);
    var timer_start = $("#timer_start").val();
    var timer_paused = $("#time-container").text();
    $("#text_form input[name='start_time']").val(timer_start);
    $("#text_form input[name='end_time']").val(timer_paused); // $("#timer_start").val(timer_paused);

    $("#timer_end").val(timer_paused);
    $("#time-container").val(AppStopwatch.startClock);
    form.ajaxSubmit("text_form", onTextSave);
  });
  $('form[name="fin_number_form"] .submit-add-patient-fin-number').on('click', function (e) {
    $("#time-container").val(AppStopwatch.pauseClock);
    var timer_start = $("#timer_start").val();
    var timer_paused = $("#time-container").text();
    $("form[name='fin_number_form'] input[name='start_time']").val(timer_start);
    $("form[name='fin_number_form'] input[name='end_time']").val(timer_paused); // $("#timer_start").val(timer_paused);

    $("#timer_end").val(timer_paused);
    $("#time-container").val(AppStopwatch.startClock);
    form.ajaxSubmit('fin_number_form', onFinNumber);
  });
  $('form[name="personal_notes_form"] .submit-personal-notes').on('click', function (e) {
    $("#time-container").val(AppStopwatch.pauseClock);
    var timer_start = $("#timer_start").val();
    var timer_paused = $("#time-container").text();
    $("form[name='personal_notes_form'] input[name='start_time']").val(timer_start);
    $("form[name='personal_notes_form'] input[name='end_time']").val(timer_paused); // $("#timer_start").val(timer_paused);

    $("#timer_end").val(timer_paused);
    $("#time-container").val(AppStopwatch.startClock);
    form.ajaxSubmit('personal_notes_form', onPersonalNotes);
  });
  $('form[name="part_of_research_study_form"] .submit-part-of-research-study').on('click', function () {
    alert('Yes You part_of_research_study_form!!');
    $("#time-container").val(AppStopwatch.pauseClock);
    var timer_start = $("#timer_start").val();
    var timer_paused = $("#time-container").text();
    $("form[name='part_of_research_study_form'] input[name='start_time']").val(timer_start);
    $("form[name='part_of_research_study_form'] [name='end_time']").val(timer_paused); // $("#timer_start").val(timer_paused);

    $("#timer_end").val(timer_paused);
    $("#time-container").val(AppStopwatch.startClock);
    form.ajaxSubmit('part_of_research_study_form', onPartOfResearchStudy);
  });
  $('form[name="patient_threshold_form"] .submit-patient-threshold').on('click', function () {
    alert('Yes You patient_threshold_form!!');
    $("#time-container").val(AppStopwatch.pauseClock);
    var timer_start = $("#timer_start").val();
    var timer_paused = $("#time-container").text();
    $("form[name='patient_threshold_form'] input[name='start_time']").val(timer_start);
    $("form[name='patient_threshold_form'] [name='end_time']").val(timer_paused); // $("#timer_start").val(timer_paused);

    $("#timer_end").val(timer_paused);
    $("#time-container").val(AppStopwatch.startClock);
    form.ajaxSubmit('patient_threshold_form', onPatientThreshold);
  });
  $("form[name='email_form']").submit(function (e) {
    e.preventDefault();
    $("#time-container").val(AppStopwatch.pauseClock);
    var timer_start = $("#timer_start").val();
    var timer_paused = $("#time-container").text();
    $("#email_form input[name='start_time']").val(timer_start);
    $("#email_form input[name='end_time']").val(timer_paused); // $("#timer_start").val(timer_paused);

    $("#timer_end").val(timer_paused);
    $("#time-container").val(AppStopwatch.startClock);
    form.ajaxSubmit("email_form", onEmailSave);
  });
  $("form[name='call_status_form']").submit(function (e) {
    e.preventDefault();
    $("#time-container").val(AppStopwatch.pauseClock);
    var timer_start = $("#timer_start").val();
    var timer_paused = $("#time-container").text();
    $("#call_status_form input[name='start_time']").val(timer_start);
    $("#call_status_form input[name='end_time']").val(timer_paused); // $("#timer_start").val(timer_paused);

    $("#timer_end").val(timer_paused);
    $("#time-container").val(AppStopwatch.startClock);
    form.ajaxSubmit("call_status_form", onSaveCallSatus);
  });
  $("form[name='call_status_form_final']").submit(function (e) {
    e.preventDefault();
    $("#time-container").val(AppStopwatch.pauseClock);
    var timer_start = $("#timer_start").val();
    var timer_paused = $("#time-container").text();
    $("#call_status_form_final input[name='start_time']").val(timer_start);
    $("#call_status_form_final input[name='end_time']").val(timer_paused); // $("#timer_start").val(timer_paused);

    $("#timer_end").val(timer_paused);
    $("#time-container").val(AppStopwatch.startClock);
    form.ajaxSubmit("call_status_form_final", onSaveCallSatusFinal);
  });
  $("form[name='enrollment_status_form']").submit(function (e) {
    e.preventDefault();
    $("#time-container").val(AppStopwatch.pauseClock);
    var timer_start = $("#timer_start").val();
    var timer_paused = $("#time-container").text();
    $("#enrollment_status_form input[name='start_time']").val(timer_start);
    $("#enrollment_status_form input[name='end_time']").val(timer_paused); // $("#timer_start").val(timer_paused);

    $("#timer_end").val(timer_paused);
    $("#time-container").val(AppStopwatch.startClock);
    form.ajaxSubmit("enrollment_status_form", onSaveEnrollmentSatus);
  });
  $("form[name='enroll_services_form']").submit(function (e) {
    e.preventDefault();
    $("#time-container").val(AppStopwatch.pauseClock);
    var timer_start = $("#timer_start").val();
    var timer_paused = $("#time-container").text();
    $("#enroll_services_form input[name='start_time']").val(timer_start);
    $("#enroll_services_form input[name='end_time']").val(timer_paused); // $("#timer_start").val(timer_paused);

    $("#timer_end").val(timer_paused);
    $("#time-container").val(AppStopwatch.startClock);
    form.ajaxSubmit("enroll_services_form", onSaveEnrollServicesSatus);
  });
  $("form[name='checklist_form']").submit(function (e) {
    e.preventDefault();
    $("#time-container").val(AppStopwatch.pauseClock);
    var timer_start = $("#timer_start").val();
    var timer_paused = $("#time-container").text();
    $("#checklist_form input[name='start_time']").val(timer_start);
    $("#checklist_form input[name='end_time']").val(timer_paused); // $("#timer_start").val(timer_paused);

    $("#timer_end").val(timer_paused);
    $("#time-container").val(AppStopwatch.startClock);
    form.ajaxSubmit("checklist_form", onSaveChecklist);
  });
  $("form[name='finalization_checklist_form']").submit(function (e) {
    e.preventDefault();
    $("#time-container").val(AppStopwatch.pauseClock);
    var timer_start = $("#timer_start").val();
    var timer_paused = $("#time-container").text();
    $("#finalization_checklist_form input[name='start_time']").val(timer_start);
    $("#finalization_checklist_form input[name='end_time']").val(timer_paused); // $("#timer_start").val(timer_paused);

    $("#timer_end").val(timer_paused);
    $("#time-container").val(AppStopwatch.startClock);
    form.ajaxSubmit("finalization_checklist_form", onSaveFinalizationChecklistStatus);
  });
  $("form[name='checklist_status_form']").submit(function (e) {
    e.preventDefault();
    $("#time-container").val(AppStopwatch.pauseClock);
    var timer_start = $("#timer_start").val();
    var timer_paused = $("#time-container").text();
    $("#checklist_status_form input[name='start_time']").val(timer_start);
    $("#checklist_status_form input[name='end_time']").val(timer_paused); // $("#timer_start").val(timer_paused);

    $("#timer_end").val(timer_paused);
    $("#time-container").val(AppStopwatch.startClock);
    form.ajaxSubmit("checklist_status_form", onSaveChecklistSatus);
  }); //text

  $("form[name='text_form'] #text_template_id").change(function () {
    util.getCallScriptsById($(this).val(), '#templatearea_sms', 'form[name="text_form"] input[name="template_type_id"]', '#text_content_title');
  }); //email

  $("form[name='email_form'] #email_template_id").change(function () {
    util.getCallScriptsById($(this).val(), '#email_template_area', 'form[name="email_form"] input[name="template_type_id"]', '#email_content_title', 'form[name="email_form"] #subject');
  }); //call.step-1

  $("form[name='call_status_form'] #call_scripts_select").change(function () {
    util.getCallScriptsById($(this).val(), '.call_answer_template', 'form[name="call_status_form"] input[name="template_type_id"]', 'form[name="call_status_form"] input[name="content_title"]');
  });
  $("form[name='call_status_form'] #call_not_answer_template_id").change(function () {
    util.getCallScriptsById($(this).val(), '#call_not_answer_content_area', 'form[name="call_status_form"] input[name="template_type_id"]', 'form[name="call_status_form"] input[name="content_title"]');
  });
  $("form[name='enrollment_status_form'] #enrollment_status_template_id").change(function () {
    util.getCallScriptsById($("form[name='enrollment_status_form'] #enrollment_status_template_id").val(), '.enrollment_status_script', 'form[name="enrollment_status_form"] input[name="template_type_id"]', 'form[name="enrollment_status_form"] input[name="content_title"]');
  });
  $("form[name='patient_add_device_form'] #email_title option:last").attr("selected", "selected").change();
  $("form[name='patient_add_device_form'] #email_title").change(function () {
    alert('here'); //util.getCallScriptsById($(this).val(), '#ccm_content_area', "form[name='callstatus_form'] input[name='template_type_id']", "form[name='callstatus_form'] input[name='content_title']");
  });
  $("form[name='call_status_form'] input[name='call_status']").click(function () {
    var checked_call_option = $("form[name='call_status_form'] input[name$='call_status']:checked").val();

    if (checked_call_option == '1') {
      $('.invalid-feedback').html('');
      $("form[name='call_status_form'] #callNotAnswer").hide();
      $("form[name='call_status_form'] #callAnswer").show();
      $("form[name='call_status_form'] #call_scripts_select option:last").attr("selected", "selected").change();
      $("form[name='call_status_form'] #call-save-button").html('<button type="submit" class="btn  btn-primary m-1" id="save-callstatus">Next</button>');
      $("form[name='call_status_form'] #call_action_script").val($("form[name='call_status_form'] input[name='call_action_script'] option:selected").text());
      util.getCallScriptsById($("form[name='call_status_form'] #call_scripts_select").val(), '.call_answer_template');
      $("form[name='call_status_form'] input[name='content_title']").val($("form[name='call_status_form'] #call_scripts_select option:selected").text());
    } else if (checked_call_option == '2') {
      $('.invalid-feedback').html('');
      $("form[name='call_status_form'] #callNotAnswer").show();
      $("form[name='call_status_form'] #callAnswer").hide();
      $("form[name='call_status_form'] #call_not_answer_template_id option:last").attr("selected", "selected").change();
      $("form[name='call_status_form'] #call-save-button").html('<button type="submit" class="btn btn-primary m-1 call_status_submit" id="save_schedule_call">Send Text Message</button>');
      $("form[name='call_status_form'] #call_action_script").val($("form[name='call_status_form'] input[name='content_title'] option:selected").text());
      util.getCallScriptsById($("form[name='call_status_form'] #call_not_answer_template_id").val(), '#call_not_answer_content_area');
      $("form[name='call_status_form'] input[name='content_title']").val($("form[name='call_status_form'] #call_not_answer_template_id option:selected").text());
    }
  });
  $("form[name='call_status_form_final'] input[name='enroll_status']").click(function () {
    var enrol_status_option = $("form[name='call_status_form_final'] input[name$='enroll_status']:checked").val();

    if (enrol_status_option == '3') {
      $('.invalid-feedback').html('');
      $("form[name='call_status_form_final'] #enrollment-call-back-date-time").hide();
      $("form[name='call_status_form_final'] #enrollment-refused-reason").show();
    } else if (enrol_status_option == '2') {
      $('.invalid-feedback').html('');
      $("form[name='call_status_form_final'] #enrollment-call-back-date-time").show();
      $("form[name='call_status_form_final'] #enrollment-refused-reason").hide();
    }
  });
  $("#is_this_cell_phone").on('change', function () {
    if ($(this).val() == 'no') {
      $(".do_you_have_cellph").css('display', 'block');
    } else {
      $(".do_you_have_cellph").css('display', 'none');
    }
  });
  $('#do_you_have_cell_phone').on('change', function () {
    if ($(this).val() == 'yes') {
      $(".cell_phone").css('display', 'block');
    } else {
      $(".cell_phone").css('display', 'none');
    }
  });
  var patientId = $("#hidden_id").val(); // var url = URL_POPULATE+"/"+patientId;

  populateForm(patientId, URL_POPULATE + "/" + patientId);
};

$('form[name="patient_add_device_form"] .submit-patient-add_device').on('click', function () {
  $("#time-container").val(AppStopwatch.pauseClock);
  var timer_start = $("#timer_start").val();
  var timer_paused = $("#time-container").text();
  $("form[name='patient_add_device_form'] input[name='start_time']").val(timer_start);
  $("form[name='patient_add_device_form'] [name='end_time']").val(timer_paused); // $("#timer_start").val(timer_paused);

  var mail_content = CKEDITOR.instances['email_title_area'].getData();
  $("#mail_content").val(mail_content);
  $("#timer_end").val(timer_paused);
  $("#time-container").val(AppStopwatch.startClock);
  form.ajaxSubmit('patient_add_device_form', onAdditionalDeviceEmail);
});
$("form[name='patient_add_device_form'] #email_title").change(function () {
  //alert($(this).val());
  util.getEmailScriptsBYId($(this).val(), '#email_title_area', "form[name='patient_add_device_form'] input[name='template_type_id']", "form[name='patient_add_device_form'] input[name='email_sub']", "form[name='patient_add_device_form'] input[name='email_from']");
});

if ($("#email_title").length) {
  $("form[name='patient_add_device_form'] #email_title option:last").attr("selected", "selected").change();
} //Multiple Dropdown Select


$('.multiDropDevice').on('click', function (event) {
  event.stopPropagation();
  $(this).next('ul').slideToggle();
});
$('#multiDropDevice').on('click', function () {
  // debugger;
  if (!$(event.target).closest('.wrapMulDropDevice').length) {
    $('.wrapMulDropDevice ul').slideUp();
  }
});
$("form[name='concent_update_form']").submit(function (e) {
  e.preventDefault();
  form.ajaxSubmit("concent_update_form", onSaveConcentData);
});
$(document).on('click', function (event) {
  // debugger;
  if (!$(event.target).closest('.wrapMulDropDevice').length) {
    $('.wrapMulDropDevice ul').slideUp();
  }
});
$('#add_replace_device').on('change', function () {
  var patientid = $("form[name='patient_add_device_form'] input[name='patient_id']").val();
  var add_replace_device = this.value;
  var token = $("form[name='patient_add_device_form'] input[name='_token']").val();
  $.ajax({
    type: 'post',
    url: '/patients/getDevice',
    data: 'patientid=' + patientid + '&add_replace_device=' + add_replace_device + '&_token=' + token,
    success: function success(response) {
      $(".wrapMulDropDevice ul").html(response);
    }
  });
});
$('body').on('click', '.change_device_status_active', function () {
  var id = $(this).data('id');

  if (confirm("Are you sure you want to Deactivate this Device")) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: 'post',
      url: '/patients/delete-device/' + id,
      // data: {"_token": "{{ csrf_token() }}","id": id},
      data: {
        "id": id
      },
      // alert(data);
      success: function success(response) {
        ccmcpdcommonJS.renderDeviceTableData();
        $("#success").show();
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong> Deactivated Successfully!</strong></div>';
        $("#success").html(txt);
        setTimeout(function () {
          $("#success").hide();
        }, 3000);
      }
    });
  } else {
    return false;
  }
});
$('body').on('click', '.change_device_status_deactive', function () {
  var id = $(this).data('id');

  if (confirm("Are you sure you want to Activate this Device")) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: 'post',
      url: '/patients/delete-device/' + id,
      // data: {"_token": "{{ csrf_token() }}","id": id},
      data: {
        "id": id
      },
      success: function success(response) {
        ccmcpdcommonJS.renderDeviceTableData();
        $("#success").show();
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Device  Activated Successfully!</strong></div>';
        $("#success").html(txt);
        setTimeout(function () {
          $("#success").hide();
        }, 3000);
      }
    });
  } else {
    return false;
  }
});
$('body').on('click', '.editDevicesdata', function () {
  //alert("working");
  $("#devices_form input[name='device_id']").val('');
  $('#partner_id').val('');
  $('#partner_devices_id').val('');
  $("form[name=\"devices_form\"]").find(".is-invalid").removeClass("is-invalid");
  $("form[name=\"devices_form\"]").find(".invalid-feedback").html("");
  $("#modelHeading1").text('Edit Device');
  var sPageURL = window.location.pathname;
  parts = sPageURL.split("/"), id = parts[parts.length - 1];
  var id = $(this).data('id');
  var data = "";
  URL_POPULATE1 = "/patients/ajax/populatedevice";
  var formpopulateurl = URL_POPULATE1 + "/" + id;
  console.log("formpopulateurl" + formpopulateurl);
  console.log("data" + data); //populateForm(id, "ajax/populatedevice/"+ id);

  populateForm(data, formpopulateurl);
});
$('body').on('click', '.patient_finnumber', function () {
  $("#fin_number_form input[name='fin_number']").val('');
  $("form[name=\"fin_number_form\"]").find(".is-invalid").removeClass("is-invalid");
  $("form[name=\"fin_number_form\"]").find(".invalid-feedback").html("");
  var sPageURL = window.location.pathname;
  parts = sPageURL.split("/");
  id = parts[parts.length - 1];
  var idd = $(this).data('id');
  var data = "";
  URL_POPULATE1 = "/patients/ajax/populatefinnumber";
  var formpopulateurl = URL_POPULATE1 + "/" + id;
  populateForm(data, formpopulateurl);
});
$(document).ready(function () {
  $('#add_replace_device').change();
}); // Module Export ---------------------------------------------------------------
// Export the module functions

window.patientEnrollment = {
  init: init,
  onPersonalNotes: onPersonalNotes,
  onFinNumber: onFinNumber,
  onMasterDevices: onMasterDevices,
  onPartOfResearchStudy: onPartOfResearchStudy,
  onPatientThreshold: onPatientThreshold,
  onAdditionalDeviceEmail: onAdditionalDeviceEmail
};
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../../node_modules/webpack/buildin/module.js */ "./node_modules/webpack/buildin/module.js")(module)))

/***/ }),

/***/ 18:
/*!*********************************************************!*\
  !*** multi ./resources/laravel/js/patientEnrollment.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/nivedita/public_html/rcaregit/resources/laravel/js/patientEnrollment.js */"./resources/laravel/js/patientEnrollment.js");


/***/ })

/******/ });