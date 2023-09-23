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
/******/ 	return __webpack_require__(__webpack_require__.s = 22);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/gull/assets/js/customizer.script.js":
/*!*******************************************************!*\
  !*** ./resources/gull/assets/js/customizer.script.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  var $appAdminWrap = $(".app-admin-wrap");
  var $html = $("html"); // var $customizer = $(".customizer");

  var $customizer = $("#customizer_id");
  var $customizer1 = $("#customizer_id1");
  var $patientCaretool = $("#patientCaretool"); // var $current_month_id = $("#current_month_id")

  var $previous_month_id = $("#previous_month_id");
  var $patientCareplan_id = $("#patientCareplan_id");
  var $sidebarColor = $(".sidebar-colors a.color");
  setTimeout(function () {
    $("#customizer_id1").show();
  }, 2000);
  setTimeout(function () {
    $("#previous_month_id").show();
  }, 4000);
  setTimeout(function () {
    $("#patientCareplan_id").show();
  }, 5000); // Change sidebar color

  $sidebarColor.on("click", function (e) {
    e.preventDefault();
    $appAdminWrap.removeClass(function (index, className) {
      return (className.match(/(^|\s)sidebar-\S+/g) || []).join(" ");
    });
    $appAdminWrap.addClass($(this).data("sidebar-class"));
    $sidebarColor.removeClass("active");
    $(this).addClass("active");
  }); // Change Direction RTL/LTR

  $("#rtl-checkbox").change(function () {
    if (this.checked) {
      $html.attr("dir", "rtl");
    } else {
      $html.attr("dir", "ltr");
    }
  }); // Toggle customizer
  // $(".handle").on("click", function(e) {
  //     $customizer.toggleClass("open");
  // });

  $("#customizer_id1 .handle").on('click', function (e) {
    $customizer1.toggleClass('open');
    $customizer.removeClass("open"); //$patientCaretool.removeClass("open");

    $previous_month_id.removeClass("open");
    $patientCareplan_id.removeClass("open");
  });
  $("#customizer_id .handle").on('click', function (e) {
    //$customizer.toggleClass("open");
    $customizer.toggleClass("open"); //$patientCaretool.removeClass("open");

    $previous_month_id.removeClass("open");
    $patientCareplan_id.removeClass("open");
    $customizer1.removeClass('open');
  });
  /* $("#patientCaretool").hover(function (e) {
       $patientCaretool.addClass("open");
       $customizer.removeClass("open");
       $previous_month_id.removeClass("open");
       $patientCareplan_id.removeClass("open");
       $customizer1.removeClass('open');
   });*/
  // $("#current_month_id").hover(function (e) {
  //     $current_month_id.toggleClass("open");
  // });

  $("#previous_month_id .handle").on('click', function (e) {
    $previous_month_id.toggleClass("open"); //$patientCaretool.removeClass("open");

    $customizer.removeClass("open");
    $patientCareplan_id.removeClass("open");
    $customizer1.removeClass('open');
  });
  $("#patientCareplan_id .handle").on('click', function (e) {
    $patientCareplan_id.toggleClass("open");
    $previous_month_id.removeClass("open"); //$patientCaretool.removeClass("open");

    $customizer.removeClass("open");
    $customizer1.removeClass('open');
  });
  /*
  $("#customizer_id1").hover( function(e) {
      
      $(".patientStatus").removeClass("open");  //Add the active class to the area is hovered
          }, function () {
      $(".patientStatus").addClass("open");
  });
  */

  /*
  $("#customizer_id1").hover(enter, leave);
  $ht = $('.patientStatus').width();
  // alert($ht);
  $('.patientStatus').css({
      // 'right':'0px',
      'right': '-' + $ht + 'px',
      // 'opacity': 0,
      'display': 'block'
  });
  function enter(event) { // mouseenter IMG
      // removed image rollover code
       $('.patientStatus').data({ closing: false }).stop(true, false).animate({
          right: '0px',
          opacity: 1
      }, {
          // duration: 600  // slow the opening of the drawer
      });
  };
  function leave(event) { // mouseout IMG
      // removed image rollover code
       $('.patientStatus').data({ closing: true }).delay(600).animate({
          right: '-' + $ht + 'px',
          // opacity: 0
      }, {
          // duration: 600
      });
  };
   $('.patientStatus').hover(
      function () { // mouseenter Menu drawer
          if ($(this).data('closing')) {
              $(this).stop(true, true);
          }
      },
      function () { // mouseout Menu drawer
          $(this).delay(300).animate({
              // right: '0px',
              // opacity: 0
          }, {
              // duration: 600    
          });
       }
  );
   */
}); // makes sure the whole site is loaded
// $(window).on("load", function() {
//     let $themeLink = $("#gull-theme");
//     initTheme("gull-theme");
//     function initTheme(storageKey) {
//         if (!localStorage) {
//             return;
//         }
//         let fileUrl = localStorage.getItem(storageKey);
//         if (fileUrl) {
//             $themeLink.attr("href", fileUrl);
//         }
//     }
//     $(".bootstrap-colors .color").on("click", function(e) {
//         e.preventDefault();
//         let color = $(this).attr("title");
//         console.log(color);
//         let fileUrl = "/assets/styles/css/themes/" + color + ".min.css";
//         if (localStorage) {
//             gullUtils.changeCssLink("gull-theme", fileUrl);
//         } else {
//             $themeLink.attr("href", fileUrl);
//         }
//     });
//     // will first fade out the loading animation
//     jQuery("#loader").fadeOut();
//     // will fade out the whole DIV that covers the website.
//     jQuery("#preloader")
//         .delay(500)
//         .fadeOut("slow");
// });

/***/ }),

/***/ 22:
/*!*************************************************************!*\
  !*** multi ./resources/gull/assets/js/customizer.script.js ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/nivedita/public_html/rcaregit/resources/gull/assets/js/customizer.script.js */"./resources/gull/assets/js/customizer.script.js");


/***/ })

/******/ });