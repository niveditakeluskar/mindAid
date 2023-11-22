$(document).ready(function () {
    var $appAdminWrap = $(".app-admin-wrap");
    var $html = $("html");
    // var $customizer = $(".customizer");
    var $customizer = $("#customizer_id")
    var $customizer1 = $("#customizer_id1")
    var $patientCaretool = $("#patientCaretool")
    // var $current_month_id = $("#current_month_id")
    var $previous_month_id = $("#previous_month_id")
    var $patientCareplan_id = $("#patientCareplan_id")
    var $sidebarColor = $(".sidebar-colors a.color");
    setTimeout(function () {
        $("#customizer_id1").show();
    }, 2000);
    setTimeout(function () {
        $("#previous_month_id").show();
    }, 4000);
    setTimeout(function () {
        $("#patientCareplan_id").show();
    }, 5000);
    // Change sidebar color
    $sidebarColor.on("click", function (e) {
        e.preventDefault();
        $appAdminWrap.removeClass(function (index, className) {
            return (className.match(/(^|\s)sidebar-\S+/g) || []).join(" ");
        });
        $appAdminWrap.addClass($(this).data("sidebar-class"));
        $sidebarColor.removeClass("active");
        $(this).addClass("active");
    });
    // Change Direction RTL/LTR
    $("#rtl-checkbox").change(function () {
        if (this.checked) {
            $html.attr("dir", "rtl");
        } else {
            $html.attr("dir", "ltr");
        }
    });

    // Toggle customizer
    // $(".handle").on("click", function(e) {
    //     $customizer.toggleClass("open");
    // });

    $("#customizer_id1 .handle").on('click', function (e) {
        $customizer1.toggleClass('open');
        $customizer.removeClass("open");
        //$patientCaretool.removeClass("open");
        $previous_month_id.removeClass("open");
        $patientCareplan_id.removeClass("open");
    });

    $("#customizer_id .handle").on('click', function (e) {
        //$customizer.toggleClass("open");
        $customizer.toggleClass("open");
        //$patientCaretool.removeClass("open");
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
        $previous_month_id.toggleClass("open");
        //$patientCaretool.removeClass("open");
        $customizer.removeClass("open");
        $patientCareplan_id.removeClass("open");
        $customizer1.removeClass('open');
    });

    $("#patientCareplan_id .handle").on('click', function (e) {
        $patientCareplan_id.toggleClass("open");
        $previous_month_id.removeClass("open");
        //$patientCaretool.removeClass("open");
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

});

// makes sure the whole site is loaded
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
