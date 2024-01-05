$(document).ready(function() {
    "use strict";
    var $searchInput = $(".search-bar input");
    var $searchCloseBtn = $(".search-close");


   //for textarea automatic resize
     $('textarea').each(function () {    
    this.setAttribute('style', 'height:50px;overflow-y:hidden;');
     
   }).on('input', function () {
     this.style.height = 'auto';
     this.style.height = (this.scrollHeight) + 'px';
   });

    // Reusable utilities
    window.gullUtils = {
        isMobile: function isMobile() {
            return window && window.matchMedia("(max-width: 767px)").matches;
        },
        changeCssLink: function(storageKey, fileUrl) {
            localStorage.setItem(storageKey, fileUrl);
            location.reload();
        }
    };

    // Search toggle
    var $searchUI = $(".search-ui");
    $searchInput.on("focus", function() {
        $searchUI.addClass("open");
    });
    $searchCloseBtn.on("click", function() {
        $searchUI.removeClass("open");
    });

    // Secondary sidebar dropdown menu
    var $dropdown = $(".dropdown-sidemenu");
    var $subMenu = $(".submenu");

    $dropdown.find("> a").on("click", function(e) {
        e.preventDefault();
        e.stopPropagation();
        var $parent = $(this).parent(".dropdown-sidemenu");
        $dropdown.not($parent).removeClass("open");
        $(this)
            .parent(".dropdown-sidemenu")
            .toggleClass("open");
    });

    // Perfect scrollbar
    $(".perfect-scrollbar, [data-perfect-scrollbar]").each(function(index) {
        var $el = $(this);
        var ps = new PerfectScrollbar(this, {
            suppressScrollX: $el.data("suppress-scroll-x"),
            suppressScrollY: $el.data("suppress-scroll-y")
        });
    });

    // Full screen
    function cancelFullScreen(el) {
        var requestMethod =
            el.cancelFullScreen ||
            el.webkitCancelFullScreen ||
            el.mozCancelFullScreen ||
            el.exitFullscreen;
        if (requestMethod) {
            // cancel full screen.
            requestMethod.call(el);
        } else if (typeof window.ActiveXObject !== "undefined") {
            // Older IE.
            var wscript = new ActiveXObject("WScript.Shell");
            if (wscript !== null) {
                wscript.SendKeys("{F11}");
            }
        }
    }

    function requestFullScreen(el) {
        // Supports most browsers and their versions.
        var requestMethod =
            el.requestFullScreen ||
            el.webkitRequestFullScreen ||
            el.mozRequestFullScreen ||
            el.msRequestFullscreen;

        if (requestMethod) {
            // Native full screen.
            requestMethod.call(el);
        } else if (typeof window.ActiveXObject !== "undefined") {
            // Older IE.
            var wscript = new ActiveXObject("WScript.Shell");
            if (wscript !== null) {
                wscript.SendKeys("{F11}");
            }
        }
        return false;
    }

    function toggleFullscreen() {
        var elem = document.body;
        var isInFullScreen =
            (document.fullScreenElement &&
                document.fullScreenElement !== null) ||
            (document.mozFullScreen || document.webkitIsFullScreen);

        if (isInFullScreen) {
            cancelFullScreen(document);
        } else {
            requestFullScreen(elem);
        }
        return false;
    }
    $("[data-fullscreen]").on("click", toggleFullscreen);

    if ($("#status_block").length){
        dragElement(document.getElementById("status_block"));
    }
  
    function dragElement(elmnt) {
      var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
      if (document.getElementById(elmnt.id + "header")) {
        /* if present, the header is where you move the DIV from:*/
        document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
      } else {
        /* otherwise, move the DIV from anywhere inside the DIV:*/
        elmnt.onmousedown = dragMouseDown;
      }

      function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();
        // get the mouse cursor position at startup:
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;
        // call a function whenever the cursor moves:
        document.onmousemove = elementDrag;
      }

      function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();
        // calculate the new cursor position:
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
        // set the element's new position:
        elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
        elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
      }

      function closeDragElement() {
        /* stop moving when mouse button is released:*/
        document.onmouseup = null;
        document.onmousemove = null;
      }
    }
});

// PreLoader
// $(window).load(function() {
//     $('#preloader').fadeOut('slow', function() {
//         $(this).remove();
//     });
// });

// makes sure the whole site is loaded
$(window).on("load", function() {
    // will first fade out the loading animation
    jQuery("#loader").fadeOut();
    // will fade out the whole DIV that covers the website.
    jQuery("#preloader")
        .delay(500)
        .fadeOut("slow");
});
