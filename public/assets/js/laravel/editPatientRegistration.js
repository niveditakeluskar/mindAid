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
/******/ 	return __webpack_require__(__webpack_require__.s = 16);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/laravel/js/editPatientRegistration.js":
/*!*********************************************************!*\
  !*** ./resources/laravel/js/editPatientRegistration.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var URL_SAVE = "/ajax/submitRegistration";
var URL_POPULATE = "/patients/ajax/populateForm";
/**
 * Populate the form of the given patient
 *
 * 
 */

var populateForm = function populateForm(data, url) {
  $.get(url, data, function (result) {
    console.log(result);

    for (var key in result) {
      if (result[key]["static"].poa) {
        $("#poa_first_name").removeAttr("disabled");
        $("#poa_last_name").removeAttr("disabled");
        $("#poa_relationship").removeAttr("disabled");
        $("#poa_phone").removeAttr("disabled");
        $("#poa_email").removeAttr("disabled");
      }

      if (result[key]["static"].no_email == "null" || result[key]["static"].no_email == 0) {
        $("#email").val();
      } else {
        $("#email").val('');
      }

      if (result[key]["static"].military_status == 0) {
        $('#veteran-question').show();
      }

      if ((result[key]["static"].primary_cell_phone == "null" || result[key]["static"].primary_cell_phone == 0) && (result[key]["static"].secondary_cell_phone == "null" || result[key]["static"].secondary_cell_phone == 0)) {
        $("#content_text").hide();
      } else {
        $("#content_text").show();
      }

      if (result[key]["static"].country_code == null || result[key]["static"].country_code == "null") {
        $('#country_code option:contains(United States (US) +1)').prop('selected', true); //alert( $("#country_code option:selected").text() );
      } else {
        $('form[name="patient_registration_form"] #country_code').val();
      }

      if (result[key]["static"].secondary_country_code == null || result[key]["static"].secondary_country_code == "null") {
        $('#secondary_country_code option:contains(United States (US) +1)').prop('selected', true); //alert( $("#secondary_country_code option:selected").text() );
      } else {
        $('form[name="patient_registration_form"] #secondary_country_code').val();
      }

      form.dynamicFormPopulate(key, result[key]); //util.updatePhysicianList(

      util.updatePcpPhysicianList( //added by priya 25feb2021 for remove other option
      parseInt($("[name='practice_id']").val()), $("#physician"), result[key]["static"].provider_id);
      var id = $("[name='practice_id']").val();
      $.ajax({
        url: "/patients/getOrg/" + id,
        type: 'GET',
        // dataType: 'json', // added data type
        success: function success(res) {
          // alert(res);
          $("#organization").val($.trim(res));
        }
      }); // updateBmi();
      // $("#dob").trigger("blur");

      $("#age").val(util.age(result[key]["static"].dob));

      if (result[key]["static"].profile_img == '' || result[key]["static"].profile_img == null) {
        $('#viewprofileimg').prepend('<img id="img" src="/assets/images/faces/avatar.png"  width="100px" height="100px"/>');
      } else {
        $('#viewprofileimg').prepend('<img id="img" src="' + result[key]["static"].profile_img + '"  width="100px" height="100px"/>');
      }

      $('#image_path').val(result[key]["static"].profile_img);
      patient_services = result[key].dynamic['patientService'];

      for (group in patient_services) {
        $("input[name='enroll[" + patient_services[group]['module_id'] + "]']").prop("checked", true);
      }

      patient_insurance = result[key].dynamic['patientInsurance'];

      for (group in patient_insurance) {
        if (patient_insurance[group]['ins_type'] == 'primary') {
          console.log($("input[name='ins_provider[1]']").attr("id"));
          $("#insurance_primary").val(patient_insurance[group]['ins_provider']);
          $("#insurance_primary_idnum").val(patient_insurance[group]['ins_id']);
          $("#primary_insurance_type").val(patient_insurance[group]['ins_type']);
        }

        if (patient_insurance[group]['ins_type'] == 'secondary') {
          console.log($("input[name='ins_provider[2]']").attr("id"));
          $("#insurance_secondary").val(patient_insurance[group]['ins_provider']);
          $("#secondary_insurance_type").val(patient_insurance[group]['ins_type']);
          $("#insurance_secondary_idnum").val(patient_insurance[group]['ins_id']);
        }
      }
    }
  }).fail(function (result) {
    console.error("Population Error:", result);
  });
};

var updateBmi = function updateBmi() {
  var result = 0;

  try {
    var weight = $("[name='vital_weight']").val();
    var height = $("[name='vital_height']").val();
    result = weight / Math.pow(height, 2) * 703;
  } catch (e) {
    console.warn(e);
  }

  $("#bmi").val(result.toFixed(1));
};
/**
 * Add a practice via Ajax request
 */


var onPatientRegisteration = function onPatientRegisteration(formObj, fields, response) {
  if (response.status == 200) {
    $('#error_msg').hide();
    $('#error_msg1').hide();
    $("form[name='patient_registration_form'] .alert").show();
    var scrollPos = $(".main-content").offset().top;
    $(window).scrollTop(scrollPos);
    setTimeout(function () {
      // console.log(JSON.stringify(response.data));
      var protocol = window.location.protocol;
      var sPageURL = window.location.hostname;
      var specific_url = response.data.trim();
      specific_url.trim(); //console.log(protocol+"  testurl "+sPageURL+" check sPageURL"+specific_url);

      var res = specific_url.split("/");
      var new_url = '';

      if (res[1] == 'device-order') {
        new_url = (protocol + "//" + sPageURL + "/" + res[0] + "/" + res[1]).replace(/\s/g, '');
      } else {
        new_url = (protocol + "//" + sPageURL + "/" + specific_url).replace(/\s/g, '');
      }

      window.location.href = new_url; // window.location.href = '/patients/registered-patient-list';
    }, 3000);
  } else {
    // var keyone= JSON.stringify(response.data.errors);
    //console.log(keyone+"*****error"); 
    $('#error_msg').show();
    $('#error_msg1').show();
    var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please Fill All Mandatory Fields!</strong></div>';
    $('#error_msg').html(txt);
    $('#error_msg1').html(txt);
    var keyone1 = response.data.errors;
    var keyval;

    for (var key1 in keyone1) {
      keyval1 = key1;
      break;
    } //console.log(keyval1 + " 1st key error");


    $("form[name='patient_registration_form'] input[name='" + keyval1 + "']").focus();
    var scrollPos = $("input[name='" + keyval1 + "']").offset().top;
    $(window).scrollTop(scrollPos);
  }
}; // $("#no_email").click(function () {
// 	if ($("#no_email").is(":checked")) {
// 		$("#email")
// 			.attr("disabled", "disabled")
// 			.val("");
// 		$("#email-preferred").attr("disabled", "disabled");
// 	} else {
// 		$("#email")
// 			.removeAttr("disabled");
// 		$("#email-preferred")
// 			.removeAttr("disabled");
// 	}
// });


$("#mob, #home_number").blur(function () {
  if ($("#mob").val() == '' && $("#home_number").val() == '') {
    $("#contact_preference_calling").prop('checked', false);
  } else {
    $("#contact_preference_calling").prop('checked', true);
  }
});
$("#home_number").blur(function () {
  if ($("#home_number").val() == '') {
    $('#scphn').hide();
  } else {
    $('#scphn').show();
  }
});
$("#consent_to_text").change(function () {
  //alert($("#consent_to_text-yes").val());
  if ($("#consent_to_text-yes").is(':checked')) {
    $("#contact_preference_sms").prop('checked', true);
  } else {
    $("#contact_preference_sms").prop('checked', false);
  }
});
$('#email').blur(function () {
  if (this.value != '') {
    $("#contact_preference_email").prop('checked', true);
  } else {
    $("#contact_preference_email").prop('checked', false);
  }
});
$("#no_email").click(function () {
  if ($('#no_email').prop("checked") == true) {
    $("#email").attr("disabled", "disabled").val("");
    $("#email-preferred").attr("disabled", "disabled");
    $("#contact_preference_email").prop('checked', false); // .css("background-color", "white");
  } else {
    $("#email").removeAttr("disabled");
    $("#email-preferred").removeAttr("disabled"); // .css("background-color", "red");
  }
});
$("#primary_cell_phone, #secondary_cell_phone").change(function () {
  if ($("#primary_cell_phone-yes").is(':checked') || $("#secondary_cell_phone-yes").is(':checked')) {
    $("#content_text").show();
  } else {
    $("#consent_to_text-no").prop('checked', true);
    $("#contact_preference_sms").prop('checked', false);
    $("#content_text").hide();
  }
});
$("#poa").click(function () {
  if ($("#poa").is(":checked")) {
    $("#poa_first_name").removeAttr("disabled");
    $("#poa_last_name").removeAttr("disabled");
    $("#poa_relationship").removeAttr("disabled");
    $("#poa_phone").removeAttr("disabled");
    $("#poa_email").removeAttr("disabled");
  } else {
    $("#poa_first_name").attr("disabled", "disabled");
    $("#poa_last_name").attr("disabled", "disabled");
    $("#poa_relationship").attr("disabled", "disabled");
    $("#poa_phone").attr("disabled", "disabled");
    $("#poa_email").attr("disabled", "disabled");
  }
});
$("#physician").on("change", function () {
  var choose_option = $(this).val();

  if (choose_option == 0) {
    $('#choose_provider').removeClass("col-6").addClass("col-3");
    $('.providers_name').show();
  } else {
    $('#choose_provider').removeClass("col-3").addClass("col-6");
    $('.providers_name').hide();
  }
});

var init = function init() {
  util.setLandingTime();
  util.redirectToWorklistPage(); //form.ajaxForm("patient_registration_form", onPatientRegisteration, function(){});
  // $("#age").val(util.age($("#dob").val()));
  // $('#country_code option:contains(United States (US) +1)').attr('selected', 'selected');
  // $('#secondary_country_code option:contains(United States (US) +1)').attr('selected', 'selected');

  $('#submit').on('click', function () {
    var timer_paused = $("#time-container").text();
    $("#end_time").val(timer_paused);
    form.ajaxSubmit("patient_registration_form", onPatientRegisteration);
  });
  $("[name='dob'], #dob").change(function () {
    $("#age").val(util.age($(this).val()));
  });
  var sPageURL = window.location.pathname;
  parts = sPageURL.split("/"), id = parts[parts.length - 4];
  var patientId = id;
  $("#module_id").val(parts[parts.length - 3]);
  $("#submodule_id").val(parts[parts.length - 2]);
  $("#enroll_service").val(parts[parts.length - 1]);

  if ($(".form_start_time").val() == "undefined" || $(".form_start_time").val() == '') {
    var start_time = null;
  } else {
    var start_time = $(".form_start_time").val();
  }

  util.updateTimeEveryMinutes(patientId, module_id, start_time);
  var data = "";
  var formpopulateurl = URL_POPULATE + "/" + patientId;
  populateForm(data, formpopulateurl);

  if ($('#military').val() == '0') {
    $('#veteran-question').show();
  } //alert($('#military').val());


  $("#practices").on("change", function () {
    //util.updatePhysicianList(
    util.updatePcpPhysicianList( //added by priya 25feb2021 for remove other option
    parseInt($(this).val()), $("#physician"));
    $(".providers_name").val('');
    $(".providers_name").hide();
    var id = $(this).val();
    $.ajax({
      url: "/patients/getOrg/" + id,
      type: 'GET',
      // dataType: 'json', // added data type
      success: function success(res) {
        // alert(res);
        $("#organization").val($.trim(res));
      }
    });
  });
};

var onResult = function onResult(form, fields, response, error) {
  if (error) {} else {
    window.location.href = response.data.redirect;
  }
};

window.editPatientRegistration = {
  init: init,
  onResult: onResult
};

/***/ }),

/***/ 16:
/*!***************************************************************!*\
  !*** multi ./resources/laravel/js/editPatientRegistration.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /var/www/html/mnt1/rcaregit/resources/laravel/js/editPatientRegistration.js */"./resources/laravel/js/editPatientRegistration.js");


/***/ })

/******/ });