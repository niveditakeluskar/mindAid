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
              <form name="active_deactive_form" id="active_deactive_form" @submit.prevent="submitDeviceForm">
                <input type="hidden" name="patient_id" value="" />
                        <input type="hidden" name="uid" value="">
                        <input type="hidden" name="start_time" value="00:00:00">
                        <input type="hidden" name="end_time" value="00:00:00">
                        <input type="hidden" name="module_id" value="" />
                        <input type="hidden" name="component_id" value="" />
                        <input type="hidden" name="form_name" value="active_deactive_form" />
                        <input type="hidden" name="id">
                        <input type="hidden" name="worklist" id="worklist">
                        <input type="hidden" name="patientid" id="patientid">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 form-group mb-3">
                                    <label for="module">Module</label>
                                    <select name="modules" id="enrolledservice_modules" class="custom-select show-tick enrolledservice_modules"></select>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="status"> Select the Status <span class="error">*</span></label>
                                        <span class="forms-element">
                                            <div class="form-row">
                                                <label class="radio radio-primary col-md-3 float-left" id="role1">
                                                    <input type="radio" id="role1" class="" name="status" value="1" formControlName="radio" @click ="showReasonOptions(1)">
                                                    <span>Active</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                                <label class="radio radio-primary col-md-3 float-left" id="role0">
                                                    <input type="radio" id="role0" class="" name="status" value="0" formControlName="radio" @click ="showReasonOptions(0)">
                                                    <span>Suspended</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                                <label class="radio radio-primary col-md-3 float-left" id="role2">
                                                    <input type="radio" id="role2" class="" name="status" value="2" formControlName="radio" @click ="showReasonOptions(2)">
                                                    <span>Deactivated</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                                <label class="radio radio-primary col-md-3 float-left" id="role3">
                                                    <input type="radio" id="role3" class="" name="status" value="3" formControlName="radio" @click ="showReasonOptions(3)">
                                                    <span>Deceased</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </span>
                                        <div class="form-row invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-12" id="date_value">
                                    <div class="form-group row">
                                        <div class="col-md-6 form-group mb-3" id="fromdate">
                                            <label for="date" id="from_date">From Date <span class="error">*</span></label>
                                           
                                            <input name="activedeactivefromdate" id="fromdate" type="date">
                                        </div>
                                        <div class="col-md-6 form-group mb-3" id="deceasedfromdate">
                                            <label for="date" id="from_date">Date of Deceased <span class="error">*</span></label>
                                     
                                            <input name="deceasedfromdate" id="deceasedfromdate" type="date">
                                        </div>
                                        <div class="col-md-6 form-group mb-3" id="todate">
                                            <label for="date">To Date <span class="error">*</span></label>
                                         
                                            <input name="activedeactivetodate" id="todate" type="date">
                                        </div>
                                        <div class="col-md-6 form-group mb-3" id="deactivation_drpdwn_div">
                                            <label for="deactivation_drpdwn">Reason for Deactivation</label>
                                            <select id="practices" class="custom-select show-tick select2">
                                            <option>Select Deactivation Reasons</option>
                                            <option v-for="Deactivation in Deactivations" :key="Deactivation.id" :value="Deactivation.id">
                                              {{ Deactivation.reasons }}
                                            </option>
                                          </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" id="reason">
                                    <div id="comments_div" class="mb-3 form-group">
                                        <label for="comments">Reason for status change <span class="error">*</span></label>
                                        <textarea class="form-control" name="deactivation_reason" id="comments"></textarea>
                                        <div id="comments" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary float-right submit-active-deactive">Submit</button>
                                <button type="button" class="btn btn-default float-left" data-dismiss="modal" @click="closeModal">Close</button>
                            </div>
                        </div>
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
      const Deactivations = ref([]);
      const isOpen = ref(false); 
      const openModal = () => {
            isOpen.value = true;
            fetchActiveDeactiveReasons();
        };

        const closeModal = () => {
            isOpen.value = false;
            $("#status").prop("checked", false);
        };
    
        const fetchActiveDeactiveReasons = async () => {
      try {
        const response = await fetch('/patients/get-deactivationreasons');
        const activedeactiveData = await response.json();
                const activedeactiveArray = Object.entries(activedeactiveData).map(([id, reasons]) => ({ id, reasons }));
                Deactivations.value = activedeactiveArray;
      } catch (error) {
        console.error('Error fetching ActiveDeactiveReasons:', error);
      }
    };

    const showReasonOptions = (param3) => {
      $("form[name='active_deactive_form'] #date_value").show();
      if(param3 == 1){
        $("form[name='active_deactive_form'] #fromdate").hide();
        $("form[name='active_deactive_form'] #deceasedfromdate").hide();
        $("form[name='active_deactive_form'] #todate").hide();
        $("form[name='active_deactive_form'] #deactivation_drpdwn_div").hide();
      }else if(param3 == 0){
        $("form[name='active_deactive_form'] #fromdate").show();
        $("form[name='active_deactive_form'] #deceasedfromdate").hide();
        $("form[name='active_deactive_form'] #todate").show();
        $("form[name='active_deactive_form'] #deactivation_drpdwn_div").hide();
      }else if(param3 == 2){
        $("form[name='active_deactive_form'] #fromdate").show();
        $("form[name='active_deactive_form'] #deceasedfromdate").hide();
        $("form[name='active_deactive_form'] #todate").hide();
        $("form[name='active_deactive_form'] #deactivation_drpdwn_div").show();
      }else if(param3 == 3){
        $("form[name='active_deactive_form'] #fromdate").hide();
        $("form[name='active_deactive_form'] #deceasedfromdate").show();
        $("form[name='active_deactive_form'] #todate").hide();
        $("form[name='active_deactive_form'] #deactivation_drpdwn_div").hide();
      }else{
        $("#status").prop("checked", false);
        $("form[name='active_deactive_form'] #date_value").hide();
        alert("Invalid Request");
      }
      };

    const callExternalFunctionWithParams = (param1, param2) => {
      if ($.isNumeric(param1) == true) {
       let patientId = param1;
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
        $("#status").prop("checked", false);
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
        closeModal();
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
      showReasonOptions,
            callExternalFunctionWithParams,
            isOpen,
            openModal,
            closeModal,
            fetchActiveDeactiveReasons,
            Deactivations
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
