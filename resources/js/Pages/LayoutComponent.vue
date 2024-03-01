<template>
  <div>
    <!-- Header component -->
    <Header />

    <!-- Inertia's slot where individual pages will be rendered -->
    <slot :moduleId="moduleId" />
       <template>
    <div>
      <!-- Your other content -->

      <modal v-if="isLogoutModalVisible" @click.prevent="closeLogoutModal">
        <p slot="header">Logout Alert</p>
        <p>You are inactive on the screen for a few minutes. Do you really want to logout?</p>
        <button @click.prevent="logout">Yes</button>
        <button @click.prevent="closeLogoutModal">No</button>
      </modal>
    </div>
    <div>
      <button @click="showModal">Open Modal</button>

      <Modal v-model="isModalVisible">
        <p>Your modal content goes here.</p>
        <button @click="closeModal">Close Modal</button>
      </Modal>
    </div>

  </template>

    <!-- Footer component -->
    <Footer />
  </div>
</template>

<script>
import Header from './Header.vue';
import Footer from './Footer.vue';
import axios from 'axios';
import { ref } from 'vue';
import { Modal } from 'vue3-modal';

export default {
  components: {
    Header,
    Footer,
    axios,
    Modal,
  },
  setup() {
    const isModalVisible = ref(false);

    const showModal = () => {
      isModalVisible.value = true;
    };

    const closeModal = () => {
      isModalVisible.value = false;
    };

    return {
      isModalVisible,
      showModal,
      closeModal,
    };
  },
  data() {
    return {
      moduleId: null,
      isLogoutModalVisible: false,
    };
  },
  async mounted() {
    try {
      var str = window.location.href;
      var patientId = 0;
      str = str.split("/");
      if (str.length == 6) {
        patientId = str[5].split('#')[0];
      }
      const moduleId = await this.getPageModuleID();
      this.initializeScripts(moduleId, patientId);
      console.log("isLogoutModalVisible==>", this.isLogoutModalVisible);
      setInterval(this.checkTimeInterval.bind(this), 1000);
    } catch (error) {
      console.error('Error fetching moduleID:', error);
    }
  },
  methods: {
    async getPageModuleID() {
      try {
        /*    var url = encodeURIComponent(window.location.href); */
        const response = await axios.get('/get_module_id');
        return response.data.moduleID;

      } catch (error) {
        throw new Error('Failed to fetch moduleID');
      }
    },
    async initializeScripts(moduleId, patientId) {
      try {
        const taskMangeResp = await axios.get(`/task-management/patient-to-do/${patientId}/${moduleId}/list`);
        $("#toDoList").html(taskMangeResp.data);
        $('.badge').html($('#count_todo').val());

        const cmAssignpatientstatus = await axios.get(`/patients/cm-assignpatient/0/${patientId}/cmassignpatient`);
        $("#patientassignlist").html(cmAssignpatientstatus.data);

        const patientStatus = await axios.get(`/patients/patient-status/${patientId}/${moduleId}/status`);
        $("#status_blockcontent").html(patientStatus.data);
        const carePlanStatus = await axios.get(`/ccm/careplan-status/${patientId}/${moduleId}/careplanstatus`);
        $("#careplan_blockcontent").html(carePlanStatus.data);
        let currentDate = new Date();
        let year = currentDate.getFullYear();
        let month = currentDate.getMonth() + 1;
        const previousMonths = await axios.get(`/ccm/previous-month-status/${patientId}/${moduleId}/${month}/${year}/previousstatus`);
        $("#previousMonthData").html(previousMonths.data);

        const previousMonthsmonths = await axios.get(`/ccm/previous-month-calendar/${patientId}/${moduleId}/previousstatus`);
        $("#regi_mnth").val(previousMonthsmonths.data.created_at);

        $("#display_month_year").html(moment().format("MMMM YYYY"));

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

      $(document).ready(function () {
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
        // var idleInterval = setInterval(checkTimeInterval, 1000); // 1 Seconds
        $(this).mousemove(function (e) {
          // idleTime = 0;
          localStorage.setItem("idleTime", 0);
        });
        $(this).keypress(function (e) {
          // idleTime = 0;
          localStorage.setItem("idleTime", 0);
        });
      });

      // var sessionIdleTime = 0; // Initialize sessionIdleTime
      // var checkTimeInterval = function timerIncrement() {
      //   sessionIdleTime = localStorage.getItem("idleTime");
      //   var showPopupTime = localStorage.getItem("showPopupTime"); //changes by ashvini
      //   var sessionTimeoutInSeconds = localStorage.getItem("sessionTimeoutInSeconds"); //changes by ashvini
      //   var systemDate = localStorage.getItem("systemDate");
      //   var currentDate = new Date();
      //   var res = Math.abs(Date.parse(currentDate) - Date.parse(systemDate)) / 1000;
      //   var idleTime = parseInt(sessionIdleTime) + (res % 60);
      //   if (idleTime >= showPopupTime) {
      //     // // $('#logout_modal').modal('show');
      //     // var visiblemodal = $('#logout_modal').is(':visible');
      //     // if (visiblemodal) {
      //     //   console.log('visiblemodal');
      //     // } else {
      //     //   $('#logout_modal').modal('show');
      //     // }
      //     console.log('(idleTime >= showPopupTime');
      //     this.isLogoutModalVisible = true;

      //     if (idleTime >= sessionTimeoutInSeconds) {
      //       console.log('(idleTime >= sessionTimeoutInSeconds');
      //       var visiblemodal = $('#logout_modal').is(':visible');
      //       if (visiblemodal) {
      //         // $('#logout_modal').modal('hide');
      //         // $("#sign-out-btn")[0].click();
      //         window.location.href = base_url + '/logout';
      //         var base_url = window.location.origin;
      //         // alert(base_url);  
      //         window.location.href = base_url + '/rcare-login';
      //         window.location.reload();
      //       }
      //     }
      //   }
      //   localStorage.setItem("idleTime", idleTime);
      //   // localStorage.setItem("idleTime", 0);
      //   localStorage.setItem("systemDate", currentDate);
      // };

      // //end of initializeScripts
      // // $("#logout_yes").click(function (e) {
      // //   // $("#sign-out-btn")[0].click();
      // //   window.location.href = base_url + '/logout';
      // // });

      // // $("#logout_no").click(function (e) {
      // //   $('#logout_modal').modal('hide');
      // // });
    },
    // logout() {
    //   window.location.href = '/logout';
    //   this.isLogoutModalVisible = false;
    // },
    // closeLogoutModal() {
    //   this.isLogoutModalVisible = false;
    // },
    logout() {
      // Handle logout logic
      // Close the modal after handling the logic
      window.location.href = '/logout';
      this.$modal.hide('modal-logout'); // Assuming you give the modal a name
    },
    closeLogoutModal() {
      this.$modal.hide('modal-logout');
    },
    checkTimeInterval() {
      // This function is defined as a method within the Vue component
      var showPopupTime = localStorage.getItem("showPopupTime");
      var sessionTimeoutInSeconds = localStorage.getItem("sessionTimeoutInSeconds");
      var systemDate = localStorage.getItem("systemDate");
      var currentDate = new Date();
      var res = Math.abs(Date.parse(currentDate) - Date.parse(systemDate)) / 1000;
      var idleTime = parseInt(localStorage.getItem("idleTime")) + (res % 60);
      console.log("idleTime==>", idleTime);
      if (idleTime >= showPopupTime) {
        console.log('(idleTime >= showPopupTime)');
        this.isLogoutModalVisible = true;
        console.log("isLogoutModalVisible==>", this.isLogoutModalVisible);

        if (idleTime >= sessionTimeoutInSeconds) {
          console.log('(idleTime >= sessionTimeoutInSeconds)');
          window.location.href = '/logout';
          window.location.reload();
          window.location.href = '/rcare-login';
          window.location.reload();
        }
      }
      localStorage.setItem("idleTime", idleTime);
      localStorage.setItem("systemDate", currentDate);
    },
  },
};
</script>
