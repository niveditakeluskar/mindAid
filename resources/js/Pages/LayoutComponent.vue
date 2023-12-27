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
import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import Header from './Header.vue'; // Import your header component
import Footer from './Footer.vue'; // Import your footer component
import axios from 'axios'; // Import Axios for HTTP requests
// import { mapActions } from 'vuex';

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
    // ...mapActions(['fetchPatientModules']), // Map the fetchPatientModules action
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
    initializeScripts(moduleID, patientId) {

  axios({
    method: "GET",
    url: "/task-management/patient-to-do/".concat(patientId, "/").concat(moduleID, "/list")
  }).then(function (response) {
    // console.log(response.data);
    $("#toDoList").html(response.data);
    //alert();
    $('.badge').html($('#count_todo').val());
  })["catch"](function (error) {
    console.error(error, error.response);
  });


  axios({
    method: "GET",
    url: "/patients/getCMtotaltime"
  }).then(function (response) {
    var data = JSON.stringify(response.data);
    if (data == "null" || data == "") {
      var totalpatients = "00";
      var totaltime = "00";
    } else {
      var totalpatients = response.data[0].totalpatients;
      var totaltime = response.data.minutes;
    }
    var finaldata = " : " + totaltime + " / " + totalpatients;
    $(".cmtotaltimespent").html(finaldata);
    //console.log(response.data[0].totalpatients+" checkdata "+finaldata+"testdata"+data);
  })["catch"](function (error) {
    console.error(error, error.response);
  });

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

        
      //end of initializeScripts
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
