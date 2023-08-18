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
/******/ 	return __webpack_require__(__webpack_require__.s = 13);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/laravel/js/deviceOrder.js":
/*!*********************************************!*\
  !*** ./resources/laravel/js/deviceOrder.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/**
 * 
 */
var URL_POPULATE = "/org/ajax/populateDeviceForm";
var count = 0;
/**
 * Populate the form of the given patient
 *
 * 
 */

var populateForm = function populateForm(data, url) {
  $.get(url, data, function (result) {
    for (var key in result) {
      form.dynamicFormPopulate(key, result[key]);
      var shippingdata = $("input[name='shippingdata']:checked").val();
      console.log(result[key]);

      if (key == "patient_registration_form") {
        var addresslocation = "<?php echo $addresslocation; ?>";
        $('#fname').val(result[key]["static"].fname);
        $('#lname').val(result[key]["static"].lname);
        $('#add_1').val(result[key]["static"].add_1);
        $('#mob').val(result[key]["static"].mob);
        $('#dob').val(result[key]["static"].dob);
        $('#gender').val(result[key]["static"].gender);
        $('#city').val(result[key]["static"].city);
        $('#state').val(result[key]["static"].state);
        $('#practice_emr').val(result[key]["static"].practice_emr);
        $('#zip').val(result[key]["static"].zipcode);
        $('#officelocation').val(addresslocation);
        $('#physician').val(result[key]["static"].provider_id);
        var practice_id = result[key]["static"].practice_id;
        var patient_id = result[key]["static"].patient_id;
        $('#patient_id').val(patient_id);
        $('#practiceid').val(practice_id);
        $('#fname').val(result[key]["static"].fname); //alert(result[key].static.email+'-static'+result[key].dynamic[0].email+'dynamic');

        if (result[key]["static"].email == "" || result[key]["static"].email == null || result[key]["static"].email == "null") {
          $('#email').val(result[key].dynamic[0].email);
        } else {
          $('#email').val(result[key]["static"].email);
        }

        if (shippingdata == 'shipping_patient_add') {
          $('#shipping_fname').val(result[key]["static"].fname);
          $('#shipping_lname').val(result[key]["static"].lname);
          $('#shipping_mob').val(result[key]["static"].mob);
          $('#shipping_add').val(result[key]["static"].add_1);
          $('#shipping_email').val(result[key]["static"].email);
          $('#shipping_city').val(result[key]["static"].city);
          $('#shipping_zip').val(result[key]["static"].zipcode);
          $('#shipping_state').val(result[key]["static"].state);
        }

        var provider_id = result[key]["static"].provider_id;

        if (practice_id != '' || practice_id == 0) {
          util.updatePcpPhysicianList(practice_id, $("#physician"), provider_id);
        }
      }

      if (key == "shipping_details") {
        $('#shipping_fname').val(result[key]["static"].headquaters_fname);
        $('#shipping_lname').val(result[key]["static"].headquaters_lname);
        $('#shipping_mob').val(result[key]["static"].headquaters_phone);
        $('#shipping_add').val(result[key]["static"].headquaters_address);
        $('#shipping_email').val(result[key]["static"].headquaters_email);
        $('#shipping_city').val(result[key]["static"].headquaters_city);
        $('#shipping_zip').val(result[key]["static"].headquaters_zip);
        $('#shipping_state').val(result[key]["static"].headquaters_state);
      }
    }
  }).fail(function (result) {
    console.error("Population Error:", result);
  });
}; //init mei dala


function GetSelectedItem(e) {
  var sel = e.getAttribute("id");
  var msg = "Hello";
  var blank = " ";

  if (sel) {
    //alert("Selected values: " + selected.join(","));
    document.getElementById("seldev").style.display = "block";
    document.getElementById("seldev").innerHTML = sel;
  } else {
    document.getElementById("seldev").style.display = "none";
    document.getElementById("seldev").innerHTML = blank;
  }
}

$('#additionalmedtime').click(function () {
  count++;
  $('#append_medreminder').append('<div class=" row btn_remove" id="btn_removemedtime_' + count + '"><div class="col-md-3"><input id="time_' + count + '" name="time[]" type="time"  autocomplete="off" class="form-control"></div><div class="col-md-3"><input id="message_' + count + '" name="message[]" type="text" autocomplete="off" class="form-control"></div><div class="col-md-1"><i class="remove-icons i-Remove mb-3" id="remove_medtime_' + count + '" title="Remove Med time"></i></div><br><br></div>');
});

var init = function init() {
  var onDeviceOrderForm = function onDeviceOrderForm(formObj, fields, response) {
    alert("hi");
    console.log(response);

    if (response.status == 200) {
      if ($.trim(response.data) == "exist") {
        $("#danger-alert").show();
      } else {
        $("#danger-alert").hide();
      }

      $('#deviceerror').hide();
      $("#multiDrop").removeClass('is-invalid');
      $('#patienterror').hide();
      var response_validation = response.data.validation;
      var response_data = jQuery.parseJSON(response.data);
      var scrollPos = $(".main-content").offset().top;
      $(window).scrollTop(scrollPos);

      if (response_validation == "" || response_validation == undefined) {
        $("#error_msg").hide();
        $("#error_msg1").hide();
        $('#orderno').html(response_data.sourceId);
        $('#orderno1').html(response_data.sourceId);
        $("form[name='device_order_form']")[0].reset();
        $("form[name='device_order_form'] .alert-success").show();
        setTimeout(function () {
          window.location.href = '/rpm/device-order-list';
        }, 3000);
      } else {
        var validation = response_data.join(", \n");
        console.log(validation + "checkvalidn");

        if (validation != "") {
          $("#error_msg").show();
          $("#error_msg1").show();
          var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>' + validation + '</strong></div>';
          $("#error_msg").html(txt);
          $("#error_msg1").html(txt);
        }
      }
    } else {
      //console.log($('#seldev').is(':empty')+"check length");
      var existerror = response.data;

      if (existerror.errors.patient_id == undefined) {
        $('#patienterror').hide();
      } else {
        $('#patienterror').show();
      }

      if ($('#seldev').is(':empty') == true) {
        $('#deviceerror').show();
        $("#multiDrop").addClass('is-invalid');
      } else {
        $('#deviceerror').hide();
        $("#multiDrop").removeClass('is-invalid');
      }

      $("#error_msg").show();
      $("#error_msg1").show();
      var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill all mandatory field!</strong></div>';
      $("#error_msg").html(txt);
      $("#error_msg1").html(txt);
    }
  };

  $("[name='practices']").on("change", function () {
    $('#patientdiv').show(); // alert("hello dropdown");

    util.updatePatientList(parseInt($(this).val()), '2', $("#patient_id"));
    util.updatePartner(parseInt($(this).val()), '2', $("#partnerid"));
  });
  $("[name='patient_id']").on("change", function () {
    var patient_id = $(this).val();
    alert(patient_id);
    var enrollservice = $('#enrollservice').val();
    var mid = $('#mid').val();
    var cid = $('#cid').val();
    var url = '<a href="/patients/registerd-patient-edit/' + patient_id + '/' + mid + '/' + cid + '/' + enrollservice + '" title="Edit Patient Info" data-toggle="tooltip" data-placement="top"  data-original-title="Edit Patient Info" ><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>';
    $('#editpatient').html(url);
    $("#device_order_form")[0].reset();
    var data = "";
    var formpopulateurl = '/rpm/getPatientDetails/' + patient_id;
    alert(formpopulateurl);
    populateForm(data, formpopulateurl);
  });
  form.ajaxForm("device_order_form", onDeviceOrderForm, function () {
    return true;
  });
  $("input[name='systemid']").change(function () {
    if ($(this).val() == "Inventory") {
      $('#devicecode').show();
      $('#extradiv').show();
    } else {
      $('#devicecode').hide();
      $('#extradiv').hide();
    }
  });
  $("input[name='shippingdata']").change(function () {
    var patientid = $('#patient_id').val();
    var data = "";

    if ($(this).val() == 'shipping_patient_add') {
      if (patientid == '') {
        alert("Please select Patient!");
      } else {
        var formpopulateurl = '/rpm/getPatientDetails/' + patientid;
        populateForm(data, formpopulateurl);
      }
    } else if ($(this).val() == 'shipping_renova_headq') {
      $('#shipping_fname').val("");
      $('#shipping_lname').val("");
      $('#shipping_mob').val("");
      $('#shipping_add').val("");
      $('#shipping_email').val("");
      $('#shipping_city').val("");
      $('#shipping_zip').val("");
      $('#shipping_state').val("");
      var formpopulateurl = '/rpm/getshippingdetails';
      populateForm(data, formpopulateurl);
    } else {
      $('#shipping_fname').val("");
      $('#shipping_lname').val("");
      $('#shipping_mob').val("");
      $('#shipping_add').val("");
      $('#shipping_email').val("");
      $('#shipping_city').val("");
      $('#shipping_zip').val("");
      $('#shipping_state').val("");
    }
  });
  $('#shipping').change(function () {
    if (this.checked) {
      $('#shipping_div').hide();
    } else {
      $('#shipping_div').css("display", "block");
    }
  }); //Multiple Dropdown Select

  $('.multiDrop').on('click', function (event) {
    event.stopPropagation();
    $(this).next('ul').slideToggle();
  });
  $(document).on('click', function () {
    // debugger;
    if (!$(event.target).closest('.wrapMulDrop').length) {
      $('.wrapMulDrop ul').slideUp();
    }
  });
  $('.wrapMulDrop ul li input[type="checkbox"]').on('change', function () {
    var x = $('.wrapMulDrop ul li input[type="checkbox"]:checked').length;
    $('.wrapMulDrop ul li input[type="checkbox"]').each(function () {
      if (this.checked) {
        var y = $(this).val();
        var values = new Array();
        var val = new Array();
        var dev;

        if (y == '1') {
          dev = "Weighing Scale";
        } else if (y == '2') {
          dev = "Pulse Oximeter";
        } else if (y == '3') {
          dev = "Blood Pressure Cuff";
        } else if (y == '4') {
          dev = "Digital Thermometer";
        } else if (y == '5') {
          dev = "Spirometer";
        } else if (y == '6') {
          dev = "Glucometer";
        }

        var checkboxes = document.querySelectorAll('input[type=checkbox]:checked');

        for (var i = 0; i < checkboxes.length; i++) {
          if (checkboxes[i].value == '1') {
            devv = "Weighing Scale";
          } else if (checkboxes[i].value == '2') {
            devv = "Pulse Oximeter";
          } else if (checkboxes[i].value == '3') {
            devv = "Blood Pressure Cuff";
          } else if (checkboxes[i].value == '4') {
            devv = "Digital Thermometer";
          } else if (checkboxes[i].value == '5') {
            devv = "Spirometer";
          } else if (checkboxes[i].value == '6') {
            devv = "Glucose Monitor With Monthly Refills";
          } else if (checkboxes[i].value == '8') {
            devv = "Glucose Monitor With Out Monthly Refills";
          }

          values.push(devv);
        }

        if (values.length >= 1) {
          document.getElementById("seldev").style.display = "block";
          document.getElementById("seldev").innerHTML = values;
        } else if (values.length == " ") {
          document.getElementById("seldev").style.display = "none";
        }

        if ($(this).val() == '1') {
          $('#Weight_Option').css("display", "block");
        }

        if ($(this).val() == '3') {
          $('#BP_Option').css("display", "block");
        }
      } else {
        if ($(this).val() == '1') {
          $('#Weight_Option').css("display", "none");
        }

        if ($(this).val() == '3') {
          $('#BP_Option').css("display", "none");
        }
      }
    });

    if (x != "") {
      $('.multiDrop').html(x + " " + "selected");
    } else if (x < 1) {
      $('.multiDrop').html('Select devices<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
    }
  });
};

$(document).on("click", ".remove-icons", function () {
  var button_id = $(this).attr('id');
  var res = button_id.split("_");
  $('#btn_removemedtime_' + res[2]).remove();
});
window.deviceOrder = {
  init: init
};

/***/ }),

/***/ 13:
/*!***************************************************!*\
  !*** multi ./resources/laravel/js/deviceOrder.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/ashwinim/public_html/rcaregit/resources/laravel/js/deviceOrder.js */"./resources/laravel/js/deviceOrder.js");


/***/ })

/******/ });