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
/******/ 	return __webpack_require__(__webpack_require__.s = 20);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/laravel/js/stageCode.js":
/*!*******************************************!*\
  !*** ./resources/laravel/js/stageCode.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/**
* Test-Form Javascript
*/
var URL_POPULATE = "/admin/ajax/role_populate";
var URL_SAVE = "/admin/ajax/role_save"; //create_user_role

var URL_UPDATE = "update-user-role";
/**
 * Invoked when the form is submitted
 *
 * @return {Boolean}
 */

var onSubmit = function onSubmit() {
  // $('#role_modal').modal('hide');
  return true;
};
/**
 * Invoked when errors in the form are detected
 */


var onErrors = function onErrors(form, fields, response) {
  if (response.data.errors) {
    for (var field in response.data.errors) {
      try {
        var id = fields.fields[field].parents("[role='tabpanel']").attr("id");
      } catch (e) {
        console.error("Field Error:", field, fields.fields);
      }
    }
  }

  $("form[name='create_stage']").attr("action", URL_SAVE);
  return true;
};
/**
 * Invoked after the form has been submitted
 */


var onResult = function onResult(form, fields, response, error) {
  if (error) console.log(error);

  if (response.status == 200) {
    notify.success("Role Saved Successfully");
    $('#role_modal').modal('hide');
  } else {
    notify.danger("Save Failed: Unknown Error");
  }
};
/**
 * Populate the form of the given patient
 *
 * @param {Integer} patientId
 */


var populateForm = function populateForm(id) {
  // alert(id);
  if (!id) return;
  $.get(URL_POPULATE, {
    role_id: id
  }, function (data) {
    console.log(data);
    form.dynamicFormPopulate("create_stage", data);
    form.evaluateRules("create_stage");
  }).fail(function (result) {
    console.error("Population Error:", result);
  });
};

var onAddstageCode = function onAddstageCode(formObj, fields, response) {
  if (response.status == 200) {
    renderStageCodeTable();
    $("#success").show();
    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">� </button><strong>Step Added Successfully!</strong></div>';
    $("#success").html(txt); // $("#success").html('<strong>User Saved Successfully!</strong>');        

    $('#create_stage_code').trigger("reset");
    $('#addStageCodeModel').modal('hide');
    var scrollPos = $(".main-content").offset().top;
    $(window).scrollTop(scrollPos);
    setTimeout(function () {
      $("#success").hide();
    }, 5000);
  }
};

var onUpdatestageCode = function onUpdatestageCode(formObj, fields, response) {
  //console.log("add user success"+response);
  if (response.status == 200) {
    renderStageCodeTable();
    $("#success").show();
    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">� </button><strong>Step Updated Successfully!</strong></div>';
    $("#success").html(txt);
    $('#editstagecodeForm').trigger("reset");
    $('#editStageCodeModel').modal('hide');
    var scrollPos = $(".main-content").offset().top;
    $(window).scrollTop(scrollPos);
    setTimeout(function () {
      $("#success").hide();
    }, 5000);
  }
};

$('#addStageCodeBtn, .addmenus').click(function () {
  $('#saveBtn').val("create-product");
  $('#product_id').val('');
  $('#productForm').trigger("reset");
  $('#addStageCodeHeading').html("Add Step");
  $('#addStageCodeModel').modal('show');
});
$('body').on('click', '.editStageCode', function () {
  var id = $(this).data('id');
  $.get("ajax/editStageCode" + '/' + id + '/edit', function (data) {
    $('#modelHeading').html("Edit Step");
    $('#saveBtn').val("edit-stage");
    $('#editStageCodeModel').modal('show'); // console.log(data);

    $('#stage_code_id').val(data[0].id);
    $('#edit_module').val(data[0].stage.module_id);
    $('#edit-stage-code').val(data[0].description);
    $('#edit-sequence').val(data[0].sequence);
    util.updateSubModuleList(parseInt(data[0].stage.module_id), $("#edit_sub_module"), parseInt(data[0].stage.submodule_id));
    util.updateStageList(parseInt(data[0].stage.submodule_id), $("#edit_stages"), parseInt(data[0].stage_id));
  });
});
$('body').on('click', '.deleteStageCode', function () {
  var stage_id = $(this).data("id");
  var checkstr = confirm("Are You sure want to chnage status !");
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  if (checkstr == true) {
    $.ajax({
      type: "POST",
      url: "ajax/deleteStageCode" + '/' + stage_id + '/delete',
      data: {
        "id": stage_id
      },
      success: function success(data) {
        renderStageCodeTable();
        $("#msg").show();
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">x</button><strong>' + data.success + '</strong></div>';
        $("#msg").html(txt);
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        setTimeout(function () {
          $("#msg").hide();
        }, 5000);
      },
      error: function error(data) {
        console.log('Error:', data);
      }
    });
  } else {
    return false;
  }
});
$(".module").on("change", function () {
  util.updateSubModuleList(parseInt($(this).val()), $(".sub_module"));
});
$(".sub_module").on("change", function () {
  util.updateStageList(parseInt($(this).val()), $(".stage"));
});
/**
 * Initialize the form
 */

var init = function init() {
  form.ajaxForm("create_stage_code", onAddstageCode, function () {
    //  form.ajaxForm("user_details", onUpdateUser, function(){});
    return true;
  });
  form.ajaxForm("editstagecodeForm", onUpdatestageCode, function () {
    //  form.ajaxForm("user_details", onUpdateUser, function(){});
    return true;
  });
};
/**
 * Export the module
 */


window.stageCode = {
  init: init,
  onErrors: onErrors,
  onResult: onResult,
  onSubmit: onSubmit
};

/***/ }),

/***/ 20:
/*!*************************************************!*\
  !*** multi ./resources/laravel/js/stageCode.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /var/www/html/rcaregit_staging/rcaregit/resources/laravel/js/stageCode.js */"./resources/laravel/js/stageCode.js");


/***/ })

/******/ });