<template>
  <div>
    <!-- Header component -->
    <Header />

    <!-- Inertia's slot where individual pages will be rendered -->
    <slot />

    <!-- Footer component -->
    <Footer />
  </div>
</template>

<script>
import Header from './Header.vue'; // Import your header component
import Footer from './Footer.vue'; // Import your footer component
import axios from 'axios'; // Import Axios for HTTP requests

export default {
  components: {
    Header,
    Footer,
    axios
  },
  async mounted() {
    try {
      var str = window.location.href;
      var patientId = 0;
      str = str.split("/"); 
      if (str.length == 6) {
        patientId = str[5].split('#')[0];
      }
      const moduleID = await this.getPageModuleID(); // Fetch moduleID from the server
    
        this.initializeScripts(moduleID, patientId);

    } catch (error) {
      console.error('Error fetching moduleID:', error);
    }
  },
  methods: {
    callExternalFunctionWithParams(param1, param2) {
      const activeDeactiveModal = document.getElementById('active-deactive');
      if (activeDeactiveModal) {
        $(activeDeactiveModal).modal('show'); // Use jQuery to show the modal
      } else {
        console.error('Modal element not found or jQuery/Bootstrap not properly loaded');
      }

      var sPageURL = window.location.pathname;
      parts = sPageURL.split("/"),
        patientId = parts[parts.length - 1];
      if ($.isNumeric(patientId) == true) {
        //patient list
        var patientId = $("#hidden_id").val();
        var module = $("input[name='module_id']").val();
        var status = $("#service_status").val();
        $('#enrolledservice_modules').val(module).trigger('change');
        $('#enrolledservice_modules').change();
      } else {
        //worklist 
        var patientId = param1;
        var selmoduleId = $("#modules").val();
        axios({
        method: "GET",
        url: `/patients/patient-module/${patientId}/patient-module`,
    }).then(function (response) {
        $('.enrolledservice_modules').html('');
      const  enr = response.data;
      var count_enroll = enr.length;
        for (var i = 0; i < count_enroll; i++) {
            $('.enrolledservice_modules').append(`<option value="${response.data[i].module_id}">${response.data[i].module.module}</option>`);
        }
        $("#enrolledservice_modules").val(selmoduleId).trigger('change');
        // $("#patient-Ajaxdetails-model").html(response.data);
    }).catch(function (error) {
        console.error(error, error.response);
    });
        var status = param2;
        $("form[name='active_deactive_form'] #worklistclick").val("1");
        $("form[name='active_deactive_form'] #patientid").val(patientId);
        $("form[name='active_deactive_form'] #date_value").hide();
        $("form[name='active_deactive_form'] #fromdate").hide();
        $("form[name='active_deactive_form'] #todate").hide();
        if (status == 0) {
          $("form[name='active_deactive_form'] #role1").show();
          $("form[name='active_deactive_form'] #role0").hide();
          $("form[name='active_deactive_form'] #role2").show();
          $("form[name='active_deactive_form'] #role3").show();
        }
        if (status == 1) {
          $("form[name='active_deactive_form'] #role1").hide();
          $("form[name='active_deactive_form'] #role0").show();
          $("form[name='active_deactive_form'] #role2").show();
          $("form[name='active_deactive_form'] #role3").show();
        }
        if (status == 2) {
          // $("form[name='active_deactive_form'] #status-title").text('Activate/Suspend Or Deceased Patient');
          $("form[name='active_deactive_form'] #role1").show();
          $("form[name='active_deactive_form'] #role0").show();
          $("form[name='active_deactive_form'] #role2").hide();
          $("form[name='active_deactive_form'] #role3").show();
        }
        if (status == 3) {
          $("form[name='active_deactive_form'] #role1").show();
          $("form[name='active_deactive_form'] #role0").show();
          $("form[name='active_deactive_form'] #role2").show();
          $("form[name='active_deactive_form'] #role3").hide();
        }
      }
    },
      async getPageModuleID() {
      try {
        var url = encodeURIComponent(window.location.href);
        // Make an API call to your server to fetch the moduleID
        const response = await axios.get('/get_module_id'); // Replace this with your API endpoint
        return response.data.moduleID; // Assuming the server sends moduleID in the response
      } catch (error) {
        throw new Error('Failed to fetch moduleID');
      }
    },
    async initializeScripts(moduleID, patientId) {
      try {
    const taskMangeResp = await axios.get(`/task-management/patient-to-do/${patientId}/${moduleID}/list`);
    $("#toDoList").html(taskMangeResp.data);
    $('.badge').html($('#count_todo').val());
  } catch (error) {
    console.error(error);
  }

  try {
    const getCMtotaltimeResp = await axios.get(`/patients/getCMtotaltime`);
    var data = JSON.stringify(getCMtotaltimeResp.data);
    if (data == "null" || data == "") {
      var totalpatients = "00";
      var totaltime = "00";
    } else {
      var totalpatients = getCMtotaltimeResp.data[0].totalpatients;
      var totaltime = getCMtotaltimeResp.data.minutes;
    }
    var finaldata = " : " + totaltime + " / " + totalpatients;
    $(".cmtotaltimespent").html(finaldata);
 
  } catch (error) {
    console.error(error);
  }


      var $body = $("body");
      $('#dark-checkbox').change(function () {
        if ($(this).prop('checked')) {
          $body.addClass("dark-theme");
          var ch = 1;
        } else {
          $body.removeClass("dark-theme");
          var ch = 0;
        }
        $.ajax({
          method: "get",
          url: "/org/ajax/theme-dark",
          data: {
            darkmode: ch
          }
        });
      });


      $(document).ready(function() {
            localStorage.setItem("idleTime", 0);
            var data;
  axios({
    method: "GET",
    url: "/system/get-session-logout-time-with-popup-time"
  }).then(function (response) {
    var data = response.data;
    var logoutPopupTime = data.logoutpoptime;
    var sessionTimeout = data.session_timeout;
    var sessionTimeoutInSeconds = sessionTimeout * 60;
    var showPopupTime = sessionTimeoutInSeconds - logoutPopupTime;
    localStorage.setItem("idleTime", 0);
 localStorage.setItem("sessionTimeoutInSeconds", sessionTimeoutInSeconds); //changes by ashvini
 localStorage.setItem("showPopupTime", showPopupTime); //changes by ashvini
    var dt = new Date();
   localStorage.setItem("systemDate", dt);
 })["catch"](function (error) {
    console.error(error, error.response);
  });
            var idleInterval = setInterval(checkTimeInterval, 1000); // 1 Seconds
            $(this).mousemove(function(e) {
                // idleTime = 0;
                localStorage.setItem("idleTime", 0);
            });
            $(this).keypress(function(e) {
                // idleTime = 0;
                localStorage.setItem("idleTime", 0);
            });
        
        });
        var sessionIdleTime = 0; // Initialize sessionIdleTime
        var checkTimeInterval = function timerIncrement() {
            // idleTime = idleTime + 1; //Calls every 1 seconds
            sessionIdleTime = localStorage.getItem("idleTime");

            // var showPopupTime = sessionStorage.getItem("showPopupTime");
            // var sessionTimeoutInSeconds = sessionStorage.getItem("sessionTimeoutInSeconds");


            var showPopupTime = localStorage.getItem("showPopupTime"); //changes by ashvini
            var sessionTimeoutInSeconds = localStorage.getItem("sessionTimeoutInSeconds"); //changes by ashvini

            var systemDate = localStorage.getItem("systemDate");
            var currentDate = new Date();
            var res = Math.abs(Date.parse(currentDate) - Date.parse(systemDate)) / 1000;
            var idleTime = parseInt(sessionIdleTime) + (res % 60);


            //console.log("idleTime-" + idleTime);
            // console.log("showPopupTime-"+showPopupTime);
             console.log("sessionTimeoutInSeconds-"+sessionTimeoutInSeconds);


            if (idleTime >= showPopupTime) {

                console.log('idleTime in if loop idleTime >= showPopupTime');

                // $('#logout_modal').modal('show');   
                var visiblemodal = $('#logout_modal').is(':visible');
                if (visiblemodal) {
                    console.log('visiblemodal');
                } else {
                    $('#logout_modal').modal('show');
                }

                if (idleTime >= sessionTimeoutInSeconds) {
                    console.log('idleTime in if loop idleTime >= sessionTimeoutInSeconds');
                    var visiblemodal = $('#logout_modal').is(':visible');
                    if (visiblemodal) {
                        console.log('visiblemodal in sessiontimeout');
                        // $('#logout_modal').modal('hide');   
                        $("#sign-out-btn")[0].click();
                        var base_url = window.location.origin;
                        // alert(base_url);  
                        window.location.href = base_url + '/rcare-login';
                        window.location.reload();
                    }
                }
            }
            localStorage.setItem("idleTime", idleTime);
            // localStorage.setItem("idleTime", 0);
            localStorage.setItem("systemDate", currentDate);
        };


      //end of initializeScripts
      $("#logout_yes").click(function(e) {
            $("#sign-out-btn")[0].click();
        });

        $("#logout_no").click(function(e) {
            $('#logout_modal').modal('hide');
        });
         // When the Submit button is clicked within the modal
 $('.submit-active-deactive').on('click', function() {
    // Serialize the form data
    const formData = $('#active_deactive_form').serialize();

    // Make an AJAX POST request to the specified route
    $.ajax({
      type: 'POST',
      url: '/patients/patient-active-deactive',
      data: formData,
      success: function(response) {
        // Display the response message within the modal
        $('#patientalertdiv').html('<div class="alert alert-success">' + response.message + '</div>');

        // Optionally, close the modal after a certain delay
        setTimeout(function() {
          $('#active-deactive').modal('hide');
        }, 3000); // Close the modal after 3 seconds (3000 milliseconds)
      },
      error: function(xhr, status, error) {
        // Display error messages in case of failure
        $('#patientalertdiv').html('<div class="alert alert-danger">Error: ' + error + '</div>');
      }
    });
  });
      }
  },
  // Include your script tags here as an array of objects
  // Each object should contain `src` property for script import
  // This will prevent duplicate <script> elements
  scripts: [
    { src: "{{ asset('assets/js/script.js') }}" },
    { src: "{{ asset('assets/js/sidebar-horizontal.script.js') }}" },
    { src: "{{ asset('assets/js/laravel/app.js') }}" },
    { src: "{{ asset('assets/js/customizer.script.js') }}" }
  ]
};
</script>
