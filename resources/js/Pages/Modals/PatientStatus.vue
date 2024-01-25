<template>
  <div class="overlay" :class="{ 'open': isOpen }" @click="closeModal"></div>
  <div class="modal fade" :class="{ 'open': isOpen }"> <!-- :style="{ display: isOpen ? 'block' : 'none' }"> -->
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title">Change Patient Status</h4>
              <button type="button" class="close" @click="closeModal">×</button>
          </div>
          <div class="modal-body" style="padding-top:10px;" id="active-deactive">
              <loading-spinner :isLoading="isLoading"></loading-spinner>
              <form name="devices_form" id="devices_form" @submit.prevent="submitDeviceForm">
              
              </form>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-default" @click="closeModal">Close</button>
          </div>
      </div>
  </div>
</template>
<script>
import {
    reactive,
    ref,
    onBeforeMount,
    onMounted,
} from '../commonImports';
import axios from 'axios';
import { getCurrentInstance, watchEffect, nextTick } from 'vue';

export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number,
    },
    data() {
  
    },
    components: {

    },
    methods: {

    },
    setup(props) {
      const isOpen = ref(false); 
      const openModal = () => {
            isOpen.value = true;
        };

        const closeModal = () => {
            isOpen.value = false;
        };

    const callExternalFunctionWithParams = (param1, param2) => {
      if ($.isNumeric(param1) == true) {
        //patient list
      /*   var module = $("input[name='module_id']").val();
        alert(module);
        $('#enrolledservice_modules').val(module).trigger('change');
        $('#enrolledservice_modules').change();
      } else { */
       let patientId = param1;
        //worklist
        var selmoduleId = $("#modules").val();
        axios({
          method: "GET",
          url: `/patients/patient-module/${patientId}/patient-module`,
        }).then(function (response) {
          $('.enrolledservice_modules').html('');
          const enr = response.data;
          var count_enroll = enr.length;
          for (var i = 0; i < count_enroll; i++) {
            $('.enrolledservice_modules').append(`<option value="${response.data[i].module_id}">${response.data[i].module.module}</option>`);
          }
          $("#enrolledservice_modules").val(selmoduleId).trigger('change');
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
      }else{
   
      }
    };
  // When the Submit button is clicked within the modal
      $('.submit-active-deactive').on('click', function () {
        // Serialize the form data
        const formData = $('#active_deactive_form').serialize();

        // Make an AJAX POST request to the specified route
        $.ajax({
          type: 'POST',
          url: '/patients/patient-active-deactive',
          data: formData,
          success: function (response) {
            // Display the response message within the modal
            $('#patientalertdiv').html('<div class="alert alert-success">' + response.message + '</div>');

            // Optionally, close the modal after a certain delay
            setTimeout(function () {
              $('#active-deactive').modal('hide');
            }, 3000); // Close the modal after 3 seconds (3000 milliseconds)
          },
          error: function (xhr, status, error) {
            // Display error messages in case of failure
            $('#patientalertdiv').html('<div class="alert alert-danger">Error: ' + error + '</div>');
          }
        });
      });

    return { 
            callExternalFunctionWithParams,
            isOpen,
            openModal,
            closeModal
           };
  },
};

</script>
<style>

.goal-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 5px;
}

/* Modal styles */
.modal {
    display: none;
    position: fixed;
    background-color: white;
    z-index: 1000;
    margin: 2%;
    opacity: 0;
    transition: opacity 0.3s ease;
}

/* Style the overlay */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
    display: none;
}

/* Show the overlay and modal when modal is open */
.modal.open {
    display: block;
    opacity: 1;
}

.overlay.open {
    display: block;
}

.modal-content {
    overflow-y: auto !important;
    height: 800px !important;
}

</style>
