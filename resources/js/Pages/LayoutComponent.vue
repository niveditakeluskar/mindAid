<template>
  <div>
    <!-- Header component -->
    <Header />
    <slot :moduleId="moduleId" />
    <LogoutConfirmationModal ref="logoutConfirmationModalRef" />
    <!-- Footer component -->
    <Footer />
  </div>
</template>

<script>
import Header from './Header.vue';
import Footer from './Footer.vue';
import axios from 'axios';
import LogoutConfirmationModal from './Modals/LogoutConfirmationModel.vue';
export default {
  components: {
    Header,
    Footer,
    axios,
    LogoutConfirmationModal,
  },
  data() {
    return {
      moduleId: null,
      idleTime:0,
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
      localStorage.setItem("idleTime", 0); //reset on mount
      this.initializeScripts(moduleId, patientId);
      // setInterval(this.checkTimeInterval.bind(this), 1000);
      setInterval(() => this.checkTimeInterval(), 1000);
    } catch (error) {
      console.error('Error fetching moduleID:', error);
    }
  },
  methods: {
    async getPageModuleID() {
      try {
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
    },
    openLogoutConfirmationModal() {
      this.$refs.logoutConfirmationModalRef.openModal();
    },
    async checkTimeInterval() {
      var showPopupTime = parseInt(localStorage.getItem("showPopupTime"));
      var sessionTimeoutInSeconds = parseInt(localStorage.getItem("sessionTimeoutInSeconds"));
      var systemDate = new Date(localStorage.getItem("systemDate"));
      var currentDate = new Date();
      var res = Math.abs(currentDate - systemDate) / 1000;
      var idleTime = parseInt(localStorage.getItem("idleTime")) + (res % 60);
      idleTime = Math.floor(idleTime);
      console.log("checkTimeInterval called", idleTime);
      if (idleTime >= showPopupTime && idleTime < sessionTimeoutInSeconds) {
        this.openLogoutConfirmationModal();
      } else if (idleTime >= sessionTimeoutInSeconds) {
        await axios.get('/logout');
        window.location.reload();
      }
      localStorage.setItem("idleTime", idleTime);
      localStorage.setItem("systemDate", currentDate);
    },

  },
};
</script>