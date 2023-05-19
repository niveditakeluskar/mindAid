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
/******/ 	return __webpack_require__(__webpack_require__.s = 4);
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

/***/ "./resources/gull/assets/js/libs/offline-exporting.js":
/*!************************************************************!*\
  !*** ./resources/gull/assets/js/libs/offline-exporting.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(module) {var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

/*
 Highcharts JS v8.1.2 (2020-06-16)

 Client side exporting module

 (c) 2015-2019 Torstein Honsi / Oystein Moseng

 License: www.highcharts.com/license
*/
(function (a) {
  "object" === ( false ? undefined : _typeof(module)) && module.exports ? (a["default"] = a, module.exports = a) :  true ? !(__WEBPACK_AMD_DEFINE_ARRAY__ = [!(function webpackMissingModule() { var e = new Error("Cannot find module 'highcharts'"); e.code = 'MODULE_NOT_FOUND'; throw e; }()), !(function webpackMissingModule() { var e = new Error("Cannot find module 'highcharts/modules/exporting'"); e.code = 'MODULE_NOT_FOUND'; throw e; }())], __WEBPACK_AMD_DEFINE_RESULT__ = (function (h) {
    a(h);
    a.Highcharts = h;
    return a;
  }).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__)) : undefined;
})(function (a) {
  function h(a, b, q, k) {
    a.hasOwnProperty(b) || (a[b] = k.apply(null, q));
  }

  a = a ? a._modules : {};
  h(a, "mixins/download-url.js", [a["parts/Globals.js"]], function (a) {
    var b = a.win,
        q = b.navigator,
        k = b.document,
        n = b.URL || b.webkitURL || b,
        e = /Edge\/\d+/.test(q.userAgent);

    a.dataURLtoBlob = function (a) {
      if ((a = a.match(/data:([^;]*)(;base64)?,([0-9A-Za-z+/]+)/)) && 3 < a.length && b.atob && b.ArrayBuffer && b.Uint8Array && b.Blob && n.createObjectURL) {
        var g = b.atob(a[3]),
            d = new b.ArrayBuffer(g.length);
        d = new b.Uint8Array(d);

        for (var e = 0; e < d.length; ++e) {
          d[e] = g.charCodeAt(e);
        }

        a = new b.Blob([d], {
          type: a[1]
        });
        return n.createObjectURL(a);
      }
    };

    a.downloadURL = function (g, n) {
      var d = k.createElement("a");

      if ("string" === typeof g || g instanceof String || !q.msSaveOrOpenBlob) {
        if (e || 2E6 < g.length) if (g = a.dataURLtoBlob(g), !g) throw Error("Failed to convert to blob");
        if ("undefined" !== typeof d.download) d.href = g, d.download = n, k.body.appendChild(d), d.click(), k.body.removeChild(d);else try {
          var h = b.open(g, "chart");
          if ("undefined" === typeof h || null === h) throw Error("Failed to open window");
        } catch (A) {
          b.location.href = g;
        }
      } else q.msSaveOrOpenBlob(g, n);
    };
  });
  h(a, "modules/offline-exporting.src.js", [a["parts/Chart.js"], a["parts/Globals.js"], a["parts/SVGRenderer.js"], a["parts/Utilities.js"]], function (a, b, h, k) {
    function n(a, b) {
      var f = g.getElementsByTagName("head")[0],
          c = g.createElement("script");
      c.type = "text/javascript";
      c.src = a;
      c.onload = b;

      c.onerror = function () {
        d("Error loading script " + a);
      };

      f.appendChild(c);
    }

    var e = b.win,
        g = b.doc,
        q = k.addEvent,
        d = k.error,
        F = k.extend,
        A = k.getOptions,
        C = k.merge,
        D = e.URL || e.webkitURL || e,
        x = e.navigator,
        B = /Edge\/|Trident\/|MSIE /.test(x.userAgent),
        G = B ? 150 : 0;
    b.CanVGRenderer = {};

    b.svgToDataUrl = function (a) {
      var b = -1 < x.userAgent.indexOf("WebKit") && 0 > x.userAgent.indexOf("Chrome");

      try {
        if (!b && 0 > x.userAgent.toLowerCase().indexOf("firefox")) return D.createObjectURL(new e.Blob([a], {
          type: "image/svg+xml;charset-utf-16"
        }));
      } catch (f) {}

      return "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(a);
    };

    b.imageToDataUrl = function (a, b, f, c, d, m, h, t, z) {
      var l = new e.Image(),
          r = function r() {
        setTimeout(function () {
          var e = g.createElement("canvas"),
              m = e.getContext && e.getContext("2d");

          try {
            if (m) {
              e.height = l.height * c;
              e.width = l.width * c;
              m.drawImage(l, 0, 0, e.width, e.height);

              try {
                var y = e.toDataURL(b);
                d(y, b, f, c);
              } catch (E) {
                _k(a, b, f, c);
              }
            } else h(a, b, f, c);
          } finally {
            z && z(a, b, f, c);
          }
        }, G);
      },
          u = function u() {
        t(a, b, f, c);
        z && z(a, b, f, c);
      };

      var _k = function k() {
        l = new e.Image();
        _k = m;
        l.crossOrigin = "Anonymous";
        l.onload = r;
        l.onerror = u;
        l.src = a;
      };

      l.onload = r;
      l.onerror = u;
      l.src = a;
    };

    b.downloadSVGLocal = function (a, d, f, c) {
      function u(a, b) {
        var c = a.width.baseVal.value + 2 * b;
        b = a.height.baseVal.value + 2 * b;
        c = new e.jsPDF(b > c ? "p" : "l", "pt", [c, b]);
        [].forEach.call(a.querySelectorAll('*[visibility="hidden"]'), function (a) {
          a.parentNode.removeChild(a);
        });
        e.svg2pdf(a, c, {
          removeInvalid: !0
        });
        return c.output("datauristring");
      }

      function m() {
        h.innerHTML = a;
        var e = h.getElementsByTagName("text"),
            d;
        [].forEach.call(e, function (a) {
          ["font-family", "font-size"].forEach(function (b) {
            for (var c = a; c && c !== h;) {
              if (c.style[b]) {
                a.style[b] = c.style[b];
                break;
              }

              c = c.parentNode;
            }
          });
          a.style["font-family"] = a.style["font-family"] && a.style["font-family"].split(" ").splice(-1);
          d = a.getElementsByTagName("title");
          [].forEach.call(d, function (b) {
            a.removeChild(b);
          });
        });
        e = u(h.firstChild, 0);

        try {
          b.downloadURL(e, r), c && c();
        } catch (H) {
          f(H);
        }
      }

      var k = !0,
          t = d.libURL || A().exporting.libURL,
          h = g.createElement("div"),
          l = d.type || "image/png",
          r = (d.filename || "chart") + "." + ("image/svg+xml" === l ? "svg" : l.split("/")[1]),
          q = d.scale || 1;
      t = "/" !== t.slice(-1) ? t + "/" : t;
      if ("image/svg+xml" === l) try {
        if ("undefined" !== typeof x.msSaveOrOpenBlob) {
          var w = new MSBlobBuilder();
          w.append(a);
          var p = w.getBlob("image/svg+xml");
        } else p = b.svgToDataUrl(a);

        b.downloadURL(p, r);
        c && c();
      } catch (y) {
        f(y);
      } else if ("application/pdf" === l) e.jsPDF && e.svg2pdf ? m() : (k = !0, n(t + "jspdf.js", function () {
        n(t + "svg2pdf.js", function () {
          m();
        });
      }));else {
        p = b.svgToDataUrl(a);

        var v = function v() {
          try {
            D.revokeObjectURL(p);
          } catch (y) {}
        };

        b.imageToDataUrl(p, l, {}, q, function (a) {
          try {
            b.downloadURL(a, r), c && c();
          } catch (E) {
            f(E);
          }
        }, function () {
          var d = g.createElement("canvas"),
              m = d.getContext("2d"),
              h = a.match(/^<svg[^>]*width\s*=\s*"?(\d+)"?[^>]*>/)[1] * q,
              u = a.match(/^<svg[^>]*height\s*=\s*"?(\d+)"?[^>]*>/)[1] * q,
              p = function p() {
            m.drawSvg(a, 0, 0, h, u);

            try {
              b.downloadURL(x.msSaveOrOpenBlob ? d.msToBlob() : d.toDataURL(l), r), c && c();
            } catch (I) {
              f(I);
            } finally {
              v();
            }
          };

          d.width = h;
          d.height = u;
          e.canvg ? p() : (k = !0, n(t + "rgbcolor.js", function () {
            n(t + "canvg.js", function () {
              p();
            });
          }));
        }, f, f, function () {
          k && v();
        });
      }
    };

    a.prototype.getSVGForLocalExport = function (a, e, d, c) {
      var f = this,
          m = 0,
          h,
          g,
          k,
          l,
          r = function r() {
        m === w.length && c(f.sanitizeSVG(h.innerHTML, g));
      },
          n = function n(a, b, c) {
        ++m;
        c.imageElement.setAttributeNS("http://www.w3.org/1999/xlink", "href", a);
        r();
      };

      f.unbindGetSVG = q(f, "getSVG", function (a) {
        g = a.chartCopy.options;
        h = a.chartCopy.container.cloneNode(!0);
      });
      f.getSVGForExport(a, e);
      var w = h.getElementsByTagName("image");

      try {
        if (!w.length) {
          c(f.sanitizeSVG(h.innerHTML, g));
          return;
        }

        var p = 0;

        for (k = w.length; p < k; ++p) {
          var v = w[p];
          (l = v.getAttributeNS("http://www.w3.org/1999/xlink", "href")) ? b.imageToDataUrl(l, "image/png", {
            imageElement: v
          }, a.scale, n, d, d, d) : (++m, v.parentNode.removeChild(v), r());
        }
      } catch (y) {
        d(y);
      }

      f.unbindGetSVG();
    };

    a.prototype.exportChartLocal = function (a, e) {
      var f = this,
          c = C(f.options.exporting, a),
          g = function g(a) {
        !1 === c.fallbackToExportServer ? c.error ? c.error(c, a) : d(28, !0) : f.exportChart(c);
      };

      a = function a() {
        return [].some.call(f.container.getElementsByTagName("image"), function (a) {
          a = a.getAttribute("href");
          return "" !== a && 0 !== a.indexOf("data:");
        });
      };

      B && f.styledMode && (h.prototype.inlineWhitelist = [/^blockSize/, /^border/, /^caretColor/, /^color/, /^columnRule/, /^columnRuleColor/, /^cssFloat/, /^cursor/, /^fill$/, /^fillOpacity/, /^font/, /^inlineSize/, /^length/, /^lineHeight/, /^opacity/, /^outline/, /^parentRule/, /^rx$/, /^ry$/, /^stroke/, /^textAlign/, /^textAnchor/, /^textDecoration/, /^transform/, /^vectorEffect/, /^visibility/, /^x$/, /^y$/]);
      B && ("application/pdf" === c.type || f.container.getElementsByTagName("image").length && "image/svg+xml" !== c.type) || "application/pdf" === c.type && a() ? g("Image type not supported for this chart/browser.") : f.getSVGForLocalExport(c, e, g, function (a) {
        -1 < a.indexOf("<foreignObject") && "image/svg+xml" !== c.type ? g("Image type not supportedfor charts with embedded HTML") : b.downloadSVGLocal(a, F({
          filename: f.getFilename()
        }, c), g);
      });
    };

    C(!0, A().exporting, {
      libURL: "https://code.highcharts.com/8.1.2/lib/",
      menuItemDefinitions: {
        downloadPNG: {
          textKey: "downloadPNG",
          onclick: function onclick() {
            this.exportChartLocal();
          }
        },
        downloadJPEG: {
          textKey: "downloadJPEG",
          onclick: function onclick() {
            this.exportChartLocal({
              type: "image/jpeg"
            });
          }
        },
        downloadSVG: {
          textKey: "downloadSVG",
          onclick: function onclick() {
            this.exportChartLocal({
              type: "image/svg+xml"
            });
          }
        },
        downloadPDF: {
          textKey: "downloadPDF",
          onclick: function onclick() {
            this.exportChartLocal({
              type: "application/pdf"
            });
          }
        }
      }
    });
  });
  h(a, "masters/modules/offline-exporting.src.js", [], function () {});
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../../../../node_modules/webpack/buildin/module.js */ "./node_modules/webpack/buildin/module.js")(module)))

/***/ }),

/***/ 4:
/*!******************************************************************!*\
  !*** multi ./resources/gull/assets/js/libs/offline-exporting.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /var/www/html/rcareproto2/resources/gull/assets/js/libs/offline-exporting.js */"./resources/gull/assets/js/libs/offline-exporting.js");


/***/ })

/******/ });