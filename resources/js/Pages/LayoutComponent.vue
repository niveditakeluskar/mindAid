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

export default {
  components: {
    Header,
    Footer,
  },
  async mounted() {
    try {
      var str = window.location.href;
      var patientId = 0;
      str = str.split("/");
      console.log("str length", str.length);
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


      util.getToDoListData(patientId, moduleID);
      util.totalTimeSpentByCM();
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
