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
/******/ 	return __webpack_require__(__webpack_require__.s = 15);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/laravel/js/patientRegistration.js":
/*!*****************************************************!*\
  !*** ./resources/laravel/js/patientRegistration.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var URL_SAVE = "/ajax/submitRegistration"; // const URL_POPULATE = "/patients/ajax/populateForm";

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
        $("#poa_name").removeAttr("disabled");
        $("#poa_lname").removeAttr("disabled");
        $("#poa_relationship").removeAttr("disabled");
        $("#poa_phone").removeAttr("disabled");
        $("#poa_email").removeAttr("disabled");
        $("#poa_age").removeAttr("disabled");
      }

      form.dynamicFormPopulate(key, result[key]); //util.updatePhysicianList(

      util.updatePcpPhysicianList( //added by priya 25feb2021 for remove other option
      parseInt($("[name='practice_id']").val()), $("#physician"), result[key]["static"].provider_id);
      updateBmi();
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

var resetPatientRegisterationForm = function resetPatientRegisterationForm() {
  $("form[name='patient_registration_form'] #practices").val('');
  $("form[name='patient_registration_form'] #physician").val('');
  $("form[name='patient_registration_form'] #pro_name").val('');
  $("form[name='patient_registration_form'] #fname").val('');
  $("form[name='patient_registration_form'] #mname").val('');
  $("form[name='patient_registration_form'] #lname").val('');
  $("form[name='patient_registration_form'] [name='gender']").val('');
  $("form[name='patient_registration_form'] [name='marital_status']").val('');
  $("form[name='patient_registration_form'] [name='dob']").val('');
  $("form[name='patient_registration_form'] #age").val('');
  $("form[name='patient_registration_form'] #fin_number").val('');
  $("form[name='patient_registration_form'] #addr1").val('');
  $("form[name='patient_registration_form'] #addr2").val('');
  $("form[name='patient_registration_form'] #city").val('');
  $("form[name='patient_registration_form'] [name='state']").val('');
  $("form[name='patient_registration_form'] #zip").val('');
  $("form[name='patient_registration_form'] [name='ethnicity']").val('');
  $("form[name='patient_registration_form'] [name='ethnicity_2']").val('');
  $("form[name='patient_registration_form'] [name='education']").val('');
  $("form[name='patient_registration_form'] [name='occupation']").val('');
  $("form[name='patient_registration_form'] #occupation_description").val('');
  $("form[name='patient_registration_form'] [name='military_status']").val('');
  $("form[name='patient_registration_form'] #no_email").prop('checked', false);
  $("form[name='patient_registration_form'] [name='email']").val('');
  $("form[name='patient_registration_form'] [name='country_code']").val('+1');
  $("form[name='patient_registration_form'] [name='mob']").val('');
  $("form[name='patient_registration_form'] [name='secondary_country_code']").val('+1');
  $("form[name='patient_registration_form'] [name='home_number']").val('');
  $("form[name='patient_registration_form'] [name='other_contact_name']").val('');
  $("form[name='patient_registration_form'] [name='other_contact_relationship']").val('');
  $("form[name='patient_registration_form'] [name='other_contact_phone_number']").val('');
  $("form[name='patient_registration_form'] [name='other_contact_email']").val('');
  $("form[name='patient_registration_form'] [name='ins_provider[1]']").val('');
  $("form[name='patient_registration_form'] [name='ins_id[1]']").val('');
  $("form[name='patient_registration_form'] [name='ins_provider[2]']").val('');
  $("form[name='patient_registration_form'] [name='ins_id[2]']").val('');
  $("form[name='patient_registration_form'] [name='practice_emr']").val('');
  $("form[name='patient_registration_form'] #poa").prop('checked', false);
  $("form[name='patient_registration_form'] [name='poa_first_name']").val('');
  $("form[name='patient_registration_form'] [name='poa_last_name']").val('');
  $("form[name='patient_registration_form'] [name='poa_relationship']").val('');
  $("form[name='patient_registration_form'] [name='poa_phone_2']").val('');
  $("form[name='patient_registration_form'] [name='poa_email']").val('');
  $("form[name='patient_registration_form'] #contact_preference_calling").prop('checked', false);
  $("form[name='patient_registration_form'] #contact_preference_sms").prop('checked', false);
  $("form[name='patient_registration_form'] #contact_preference_email").prop('checked', false);
  $("form[name='patient_registration_form'] #contact_preference_letter").prop('checked', false);
  $("form[name='patient_registration_form'] [name='mon_0']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='mon_1']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='mon_2']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='mon_3']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='mon_any']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='tue_0']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='tue_1']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='tue_2']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='tue_3']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='tue_any']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='wed_0']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='wed_1']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='wed_2']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='wed_3']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='wed_any']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='thu_0']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='thu_1']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='thu_2']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='thu_3']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='thu_any']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='fri_0']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='fri_1']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='fri_2']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='fri_3']").prop('checked', false);
  $("form[name='patient_registration_form'] [name='fri_any']").prop('checked', false);
};
/**
 * Add a practice via Ajax request
 */


var onPatientRegisteration = function onPatientRegisteration(formObj, fields, response) {
  if (response.status == 200 && $.isNumeric($.trim(response.data)) == true) {
    // alert(response.data); 
    $('#error_msg').hide();
    $('#error_msg1').hide();
    $("form[name='patient_registration_form'] .alert").show();
    var scrollPos = $(".main-content").offset().top;
    $(window).scrollTop(scrollPos);
    $("#time-container").val(AppStopwatch.resetClock); // $("form[name='patient_registration_form']")[0].reset();

    resetPatientRegisterationForm();
    util.setLandingTime();
    $("form[name='patient_registration_form'] #practices").val('');
    $("form[name='patient_registration_form'] #physician").val('');
    $(".providers_name").hide();
    $("#patientId").val($.trim(response.data));
    $("#confirmdialog").modal('show');
  } else {
    // var errorcount=Object.keys(response.data.errors).length;  //
    //  var keyone= JSON.stringify(response.data.errors);
    //   console.log(keyone+"*****error"); 
    $("form[name='patient_registration_form'] #success-alert").hide();
    $('#error_msg').show();
    $('#error_msg1').show();
    var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please Fill All Mandatory Fields!</strong></div>';
    $('#error_msg').html(txt);
    $('#error_msg1').html(txt);
    var keyone1 = response.data.errors;
    var keyval1;

    for (var key1 in keyone1) {
      keyval1 = key1;
      break;
    }

    console.log(keyval1 + " 1st key error");
    $("form[name='patient_registration_form'] input[name='" + keyval1 + "']").focus();
    var scrollPos = $("input[name='" + keyval1 + "']").offset().top;
    $(window).scrollTop(scrollPos);
  }
};

$(".confirmation").click(function () {
  var module_id = '';
  var confirmation = $(this).val();

  if (confirmation === 'CCM' || confirmation === 'RPM') {
    util.getModuleId(confirmation);
  }

  setTimeout(function () {
    var module_id = $.trim($("#getModuleIdFromDB").val());
    var patientId = $.trim($("#patientId").val()); // Redirect on enrollment page  

    if (confirmation != 'No') {
      if (module_id != '') {
        window.location.href = '/patients/patient-enrollment/' + patientId + '/' + module_id; // $("form[name='patient_registration_form']")[0].reset();

        resetPatientRegisterationForm();
        $("form[name='patient_registration_form'] #practices").val('');
        $("form[name='patient_registration_form'] #physician").val('');
      }
    } else {
      // $("form[name='patient_registration_form']")[0].reset();
      resetPatientRegisterationForm();
      $("form[name='patient_registration_form'] #practices").val('');
      $("form[name='patient_registration_form'] #physician").val('');
      $("form[name='patient_registration_form'] #success-alert").hide();
      $('#confirmdialog').modal('hide');
    }
  }, 2000);
});
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
$("#primary_cell_phone, #secondary_cell_phone").change(function () {
  //alert($("#consent_to_text-yes").val());
  if ($("#primary_cell_phone-yes").is(':checked') || $("#secondary_cell_phone-yes").is(':checked')) {
    $("#content_text").show();
  } else {
    $("#consent_to_text-no").prop('checked', true);
    $("#contact_preference_sms").prop('checked', false);
    $("#content_text").hide();
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
}); // $('input:checkbox').change(function() {
//  //console.log("Change event: " + this.id);
//  if($('#no_email').prop("checked") == true){
//               $("#contact_preference_email").prop('checked', false);
//           }
//           else if($('#no_email').prop("checked") == false && $('#email').value!=''){
//               //console.log("Checkbox is unchecked.");
//               $("#contact_preference_email").prop('checked', true);
//           }
// });

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
/*$("form#patient_registration_form").submit(function(e) {
    e.preventDefault();    
    // var formData = new FormData(this);

    //  formData.append('profile_img',$('#profile_img')[0].files[0]);
    // console.log(formData);
     console.log("testing");
    $.ajax({
        url: '/patients/ajax/submitRegistration',
        type: 'POST',
        data: new FormData($('#patient_registration_form')[0]),
        mimeType:'multipart/form-data',
        success: function (data) {
         if (data.status == 200) { 
        // adminData.practices[response.data.id] = response.data;
        // notify.success("Practice added successfully!");
        // alert("Success");
        $("form[name='patient_registration_form'] .alert").show();
        $("#patient_registration_form")[0].reset();
        var scrollPos =  $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        setTimeout(function(){}, 3000);
        //goToNextStep("ccm-home-services-icon-tab");
    	
    }
        },
        cache: false,
        contentType: false,
        processData: false
    });
});*/

var init = function init() {
  util.setLandingTime();
  util.redirectToWorklistPage();

  if ($(".form_start_time").val() == "undefined" || $(".form_start_time").val() == '') {
    var start_time = null;
  } else {
    var start_time = $(".form_start_time").val();
  }

  util.updateTimeEveryMinutes(null, module_id, start_time); // $("#confirmdialog").modal('show');

  form.ajaxForm("patient_registration_form", onPatientRegisteration, function () {
    var uidError = $('form[name="patient_registration_form"]  #uid').next(".invalid-feedback").html();

    if (uidError.length > 0) {
      //uidError == "Patient with this First Name, Last Name and DOB already exists.") {
      $('#error_msg').show();
      $('#error_msg1').show();
      var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Patient with this First Name, Last Name and DOB already exist.</strong></div>';
      $('#error_msg').html(txt);
      $('#error_msg1').html(txt); // $("form[name='patient_registration_form'] input[name='lname']").focus();

      var scrollPos = $("form[name='patient_registration_form'] input[name='practice_id']").offset().top;
      $(window).scrollTop(scrollPos);
      e.preventDefault();
    } else {
      $('form[name="patient_registration_form"] input[name="lname"]').removeClass("is-invalid");
      $('form[name="patient_registration_form"] input[name="fname"]').removeClass("is-invalid");
      $('form[name="patient_registration_form"] input[name="dob"]').removeClass("is-invalid");
      $('form[name="patient_registration_form"]  #uid').next(".invalid-feedback").html('');
      $('#error_msg').hide();
      $('#error_msg1').hide();
      $("#time-container").val(AppStopwatch.pauseClock);
      var timer_start = $("#timer_start").val();
      var timer_paused = $("#time-container").text();
      $("input[name='timer_end']").val(timer_paused); // $("#timer_start").val(timer_paused);

      $("#timer_end").val(timer_paused);
      $("#time-container").val(AppStopwatch.startClock);
      return true;
    }
  });
  $('form[name="patient_registration_form"] input[name="fname"], form[name="patient_registration_form"] input[name="lname"], form[name="patient_registration_form"] input[name="dob"]').on("focusout", function () {
    var fName = $('form[name="patient_registration_form"] input[name="fname"]').val();
    var lName = $('form[name="patient_registration_form"] input[name="lname"]').val();
    var dob = $('form[name="patient_registration_form"] input[name="dob"]').val();
    var mName = $('form[name="patient_registration_form"] input[name="mname"]').val();

    if (fName != 'undefined' && fName != null && fName != "" && lName != 'undefined' && lName != null && lName != "" && dob != 'undefined' && dob != null && dob != "") {
      util.validateAndGenerateUid(fName, lName, dob, mName);
    }
  }); //=======================patient registration form submit===

  /*var sPageURL = window.location.pathname;
   parts = sPageURL.split("/"),
   id = parts[parts.length-1];
   var patientId = id;
   
   var data = "";
   var formpopulateurl = URL_POPULATE+"/"+patientId;
   populateForm(data, formpopulateurl);
  */
  // $('#submit').on('click', function(e) {
  //     $.ajax({
  // 		headers: {
  // 			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  // 		},
  //         type: 'post',
  //         url : '/rpm/ajax/submitRegistration',
  //         data : $("#patient_registration_form").serialize(),
  //         success: function (response) {
  // 			alert("Data inserted successfully");
  // 			$("#patient_registration_form").trigger("reset");
  //             //  sectionId = sectionId.replace("_form", '');
  //             //  renderAllergiesTable(patient_id, sectionId);
  //             //  $("#"+sectionId+"_form").trigger('reset');
  //         },
  //         error: function (data) {
  //             alert(JSON.stringify(data));
  //             console.log('Error:', data);
  //         }
  //         // error: function (request, status, error) {
  //         //     alert(JSON.stringify(request.responseJSON.errors));
  //         //     console.log(request.responseJSON.errors);
  //             // if(request.responseJSON.errors.content_title) {
  //             // $('[name="content_title"]').addClass("is-invalid");
  //             // $('[name="content_title"]').next(".invalid-feedback").html(request.responseJSON.errors.content_title);
  //             // } else {
  //             // $('[name="content_title"]').removeClass("is-invalid");
  //             // $('[name="content_title"]').next(".invalid-feedback").html('');
  //             // }
  //        // }
  //     });
  // });

  $("#practices").on("change", function () {
    // util.updatePhysicianList(parseInt($(this).val()), $("#physician"));
    util.updatePcpPhysicianList(parseInt($(this).val()), $("#physician")); //added by priya 25feb2021 for remove other option

    $(".providers_name").val('');
    $(".providers_name").hide(); //util.updatePcpPhysicianList(parseInt($(this).val()), $("#physician"));

    var id = $(this).val();
    $.ajax({
      url: "/patients/getOrg/" + id,
      type: 'GET',
      // dataType: 'json', // added data type
      success: function success(res) {
        $("#organization").val($.trim(res));
      }
    }); //cheeck practice is memorial
  });
  $("[name='dob'], #dob").change(function () {
    $("#age").val(util.age($(this).val()));
  });
};

var onResult = function onResult(form, fields, response, error) {
  // var myJSON = JSON.stringify(response);	
  // console.log(myJSON);
  if (error) {// if (response.data.errors.hasOwnProperty('insurance_primary_idnum')) {			
    // 	if($("input[name='insurance_primary_idnum']").val()!=''){
    // 		if (confirm("Would you like continue with duplicate Primary Policy Id?")) {
    // 		   $("#insurance_primary_idnum_check").val("1");
    // 		   if(Object.keys(response.data.errors).length==1){
    // 			    response.data.errors.remove('insurance_primary_idnum');
    // 			    $("form[name='patient_registration_form']").submit();
    // 		   }else{
    // 			    $("input[name='insurance_primary_idnum']").removeClass("is-invalid");
    // 			    $("input[name='insurance_primary_idnum']").next(".invalid-feedback").html("");
    // 		   }
    // 		}else{
    // 			$("#insurance_primary_idnum_check").val("0");
    // 		}
    // 	}
    // }
    // if (response.data.errors.hasOwnProperty('insurance_secondary_idnum')) {			
    // 	if($("input[name='insurance_secondary_idnum']").val()!=''){
    // 		if (confirm("Would you like continue with duplicate Secondary Policy Id?")) {
    // 		   $("#insurance_secondary_idnum_check").val("1");
    // 		   if(Object.keys(response.data.errors).length==1){
    // 			    response.data.errors.remove('insurance_secondary_idnum');
    // 			    $("form[name='patient_registration_form']").submit();
    // 		   }else{
    // 			    $("input[name='insurance_secondary_idnum']").removeClass("is-invalid");
    // 			    $("input[name='insurance_secondary_idnum']").next(".invalid-feedback").html("");
    // 		   }
    // 		}else{
    // 			$("#insurance_secondary_idnum_check").val("0");
    // 		}
    // 	}
    // }
  } else {
    window.location.href = response.data.redirect;
  }
}; // var init = function () {
// 	form.ajaxForm("patient_registration_form", onResult, undefined, function () {
//         alert("Invalid information provided");
// 		notify.danger("Invalid information provided");
// 		return true;
// 	});
// 	$("#practices").on("change", function () {
//         util.updatePhysicianList(parseInt($(this).val()), $("#physician"));
// 	});	
// };


window.patientRegistration = {
  init: init,
  onResult: onResult
};

/***/ }),

/***/ 15:
/*!***********************************************************!*\
  !*** multi ./resources/laravel/js/patientRegistration.js ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/nivedita/public_html/rcaregit/resources/laravel/js/patientRegistration.js */"./resources/laravel/js/patientRegistration.js");


/***/ })

/******/ });