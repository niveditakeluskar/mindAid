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
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/laravel/js/timer.js":
/*!***************************************!*\
  !*** ./resources/laravel/js/timer.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/**
 * Invoked after the form has been submitted
 */
var AppStopwatch = function () {
  var counter = 0,
      $stopwatch = {
    el: document.getElementById('stopwatch'),
    container: document.getElementById('time-container'),
    startControl: document.getElementById('start'),
    pauseControl: document.getElementById('pause'),
    stopControl: document.getElementById('stop')
  };
  var runClock;

  function displayTime() {
    var timestamp = new Date(0, 0, 0, 0, 0, 0);
    var interval = 1;
    timestamp = new Date(timestamp.getTime() + interval * 1000);
    $stopwatch.container.innerHTML = moment().hour(0).minute(0).second(counter++).format('HH : mm : ss');
  }

  function startWatch() {
    $("#start").hide();
    $("#pause").show();
    runClock = setInterval(displayTime, 1000);
  }

  function pauseWatch() {
    $("#start").show();
    $("#pause").hide();
    clearInterval(runClock);
  }

  function stopWatch() {
    $("#display-val").html($stopwatch.container.innerHTML);
    clearInterval(runClock);
    counter = 0;
    $("#start").show();
    $("#pause").hide();
    $("#stop").hide();
  }

  return {
    startClock: startWatch,
    pauseClock: pauseWatch,
    stopClock: stopWatch,
    $start: $stopwatch.startControl,
    $pause: $stopwatch.pauseControl,
    $stop: $stopwatch.stopControl
  };
}();

window.onload = function () {
  AppStopwatch.$start.addEventListener('click', AppStopwatch.startClock, false);
  AppStopwatch.$pause.addEventListener('click', AppStopwatch.pauseClock, false);
  AppStopwatch.$stop.addEventListener('click', AppStopwatch.stopClock, false);
};

/***/ }),

/***/ 1:
/*!*********************************************!*\
  !*** multi ./resources/laravel/js/timer.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /var/www/html/rcareproto2/resources/laravel/js/timer.js */"./resources/laravel/js/timer.js");


/***/ })

/******/ });