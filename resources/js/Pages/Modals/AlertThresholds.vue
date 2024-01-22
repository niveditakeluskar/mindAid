<!-- AlertThresholds.vue -->
<template>
    <div v-if="isOpen" class="overlay open" @click="closeModal"></div>
    <div v-if="isOpen" class="modal fade open">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">AlertThresholds</h4> 
                <button type="button" class="close" @click="closeModal">×</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger-threshold" id="threshold-alert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>Threshold data not saved! </strong><span id="text"></span>
                </div>
                <div class="alert alert-success-threshold" id="threshold-alert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>Threshold data saved successfully! </strong><span id="text"></span>
                </div>
                <div class="tab-content" id="myIconTabContent">
                    <div class="tab-pane show active" id="Patient-Threshold_1" role="tabpanel" aria-labelledby="call-icon-tab">
                        
                        <form name="patient_threshold_form" id="patient_threshold_form">
                            <input type="hidden" name="hidden_id" id="hidden_id" :value="patientId">
                            <input type="hidden" name="page_module_id" id="page_module_id" :value="moduleId">
                            <input type="hidden" name="page_component_id" id="page_component_id">
                            <input type="hidden" name="service_status" id="service_status">
                            <input type="hidden" id="timer_runing_status" value="0">
                            <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time">                                
                            <input type="hidden" name="form_name" value="patient_threshold_form">
                            <input type="hidden" name="start_time" value="00:00:00">
                            <input type="hidden" name="end_time" value="00:00:00">
                            <input type="hidden" name="id">
                            <input type="hidden" name="device_code" id="device_code" />
                            <!-- <input type="hidden" name="deviceid" id="deviceid" value=""> -->
                            <!-- count($PatientDevices)> 0 ? $PatientDevices[0]->device_code : '' -->
                            <div class="row">
                                <div class="col-md-6 form-group mb-3 ">
                                    <label for="practicename">Systolic High <!-- <span style="color:red">*</span> --></label>
                                    <input type="text" class="form-control" name="systolichigh" id="systolichigh" value="{{ $systolichigh }}">
                                    <!-- @text("systolichigh",["value" => $systolichigh ])  -->
                                </div>
                                <div class="col-md-6 form-group mb-3 ">
                                    <label for="practicename">Systolic Low <!-- <span style="color:red">*</span> --></label>
                                    <!-- @text("systoliclow",["value" => $systoliclow]) -->
                                    <input type="text" class="form-control" name="systoliclow" id="systoliclow" value="{{ $systoliclow }}">
                                </div>
                                <div class="col-md-6 form-group mb-3 ">
                                    <label for="practicename">Diastolic High <!-- <span style="color:red">*</span> --></label>
                                    <input type="text" class="form-control" name="diastolichigh" id="diastolichigh" value={{$diastolichigh}}>
                                    <!-- @text("systoliclow",["value" => $systoliclow]) -->
                                </div>
                                <div class="col-md-6 form-group mb-3 ">
                                    <label for="practicename">Diastolic Low <!-- <span style="color:red">*</span> --></label>
                                    <input type="text" class="form-control" name="diastoliclow" id="diastoliclow" value="{{$diastoliclow}}">
                                    <!-- @text("diastoliclow",["value" => $diastoliclow]) -->
                                </div>
                                <div class="col-md-6 form-group mb-3 ">
                                    <label for="practicename">Heart Rate High <!-- <span style="color:red">*</span> --></label>
                                    <input type="text" class="form-control" name="bpmhigh" id="bpmhigh" value="{{$bpmhigh}}">
                                    <!-- @text("bpmhigh",["value"=> $bpmhigh]) -->
                                </div>
                                <div class="col-md-6 form-group mb-3 ">
                                    <label for="practicename">Heart Rate Low <!-- <span style="color:red">*</span> --></label>
                                    <input type="text" class="form-control" name="bpmlow" id="bpmlow" value="{{$bpmlow}}">
                                    <!-- @text("bpmlow",["value" => $bpmlow]) -->
                                </div>
                                <div class="col-md-6 form-group mb-3 ">
                                    <label for="practicename">Oxygen Saturation High <!-- <span style="color:red">*</span> --></label>
                                    <input type="text" class="form-control" id="oxsathigh" name="oxsathigh" value="{{$oxsathigh}}">
                                    <!-- @text("oxsathigh",["value" => $oxsathigh]) -->
                                </div>
                                <div class="col-md-6 form-group mb-3 ">
                                    <label for="practicename">Oxygen Saturation Low <!-- <span style="color:red">*</span> --></label>
                                    <input type="text" class="form-control" name="oxsatlow" id="oxsatlow" value="{{$oxsatlow}}">
                                    <!-- @text("oxsatlow",["value" => $oxsatlow]) -->
                                </div>
                                <div class="col-md-6 form-group mb-3 ">
                                    <label for="practicename">Glucose High <!-- <span style="color:red">*</span> --></label>
                                    <input type="text" class="form-control" name="glucosehigh" id="glucosehigh" value="{{$glucosehigh}}">
                                    <!-- @text("glucosehigh",["value" => $glucosehigh]) -->
                                </div>
                                <div class="col-md-6 form-group mb-3 ">
                                    <label for="practicename">Glucose Low <!-- <span style="color:red">*</span> --></label>
                                    <input type="text" class="form-control" name="glucoselow" id="glucoselow" value="{{$glucoselow}}">
                                    <!-- @text("glucoselow",["value" => $glucoselow]) -->
                                </div>
                                <div class="col-md-6 form-group mb-3 ">
                                    <label for="practicename">Temperature High <!-- <span style="color:red">*</span> --></label>
                                    <input type="text" class="form-control" name="temperaturehigh" id="temperaturehigh" value="{{$temperaturehigh}}">
                                    <!-- @text("temperaturehigh",["value" => $temperaturehigh]) -->
                                </div>
                                <div class="col-md-6 form-group mb-3 ">
                                    <label for="practicename">Temperature Low <!-- <span style="color:red">*</span> --></label>
                                    <input type="text" class="form-control" name="temperaturelow" id="temperaturelow" value="{{$temperaturelow}}">
                                    <!-- @text("temperaturelow",["value" => $temperaturelow]) -->
                                </div>
                                <div class="col-md-6 form-group mb-3 ">
                                    <label for="practicename">Weight High </label>
                                    <input type="text" class="form-control" name="weighthigh" id="weighthigh" value="{{$weighthigh}}">
                                    <!-- @text("weighthigh",["id" =>"weighthigh", "value" => $weighthigh]) -->
                                </div>
                                <div class="col-md-6 form-group mb-3 ">
                                    <label for="practicename">Weight Low </label>
                                    <!-- @text("weightlow",["id" => "weightlow" , "value" => $weightlow]) -->
                                    <input type="text" class="form-control" name="weightlow" id="weightlow" value="{{$weightlow}}">
                                </div>
                                <div class="col-md-6 form-group mb-3 ">
                                    <label for="practicename">Spirometer-FEV High </label>
                                    <input type="text" class="form-control" name="spirometerfevhigh" id="spirometerfevhigh" value="{{$spirometerfevhigh}}">
                                    <!-- @text("spirometerfevhigh",["id" =>"spirometerfevhigh", "value" => $spirometerfevhigh]) -->
                                </div>
                                <div class="col-md-6 form-group mb-3 ">
                                    <label for="practicename">Spirometer-FEV low </label>
                                    <!-- @text("spirometerfevlow",["id" => "spirometerfevlow", "value" => $spirometerfevlow]) -->
                                    <input type="text" class="form-control" name="spirometerfevlow" id="spirometerfevlow" value="{{$spirometerfevlow}}">
                                </div>
                                <div class="col-md-6 form-group mb-3 ">
                                    <label for="practicename">Spirometer-PEF High </label>
                                    <!-- @text("spirometerpefhigh",["id" =>"spirometerpefhigh" , "value" => $spirometerpefhigh]) -->
                                    <input type="text" class="form-control" name="spirometerpefhigh" id="spirometerpefhigh" value="{{$spirometerpefhigh}}">
                                </div>
                                <div class="col-md-6 form-group mb-3 ">
                                    <label for="practicename">Spirometer-PEF low </label>
                                    <!-- @text("spirometerpeflow",["id" => "spirometerpeflow","value" => $spirometerpeflow]) -->
                                    <input type="text" class="form-control" name="spirometerpeflow" id="spirometerpeflow" value="{{$spirometerpeflow}}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <!-- if ($role == 3 || $role == 2 || $role == 5) { -->
                                    <button type="button" class="btn btn-primary float-right submit-patient-threshold">Submit</button>
                                    <button type="button" class="btn btn-default float-left" data-dismiss="modal">Close</button>
                                <!-- } -->
                            </div>
                        </form>
                    </div>
                    <!--===========Second tab ===================-->
                    <div class="tab-pane show " id="Patient-Threshold_2" role="tabpanel" aria-labelledby="call-icon-tab" style="margin-top: -26">
                        <h4 id="heading_thr">Practice Threshold</h4>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 form-group mb-3 ">
                                    <label>Systolic High : <p style="display:inline" id="p_systolichigh"></p></label>
                                </div>

                                <div class="col-md-6 form-group mb-3 ">
                                    <label>Systolic Low : <p style="display:inline" id="p_systoliclow"></p> </label>
                                </div>

                                <div class="col-md-6 form-group mb-3 ">
                                    <label>Diastolic High : <p style="display:inline" id="p_diastolichigh"></p> </label>
                                </div>

                                <div class="col-md-6 form-group mb-3 ">
                                    <label>Diastolic Low : <p style="display:inline" id="p_diastoliclow"></p></label>
                                </div>

                                <div class="col-md-6 form-group mb-3 ">
                                    <label>Heart Rate High : <p style="display:inline" id="p_heartratehigh"></p></label>
                                </div>
                                <div class="col-md-6 form-group mb-3 ">
                                    <label>Heart Rate Low : <p style="display:inline" id="p_heartratelow"></p> </label>
                                </div>

                                <div class="col-md-6 form-group mb-3 ">
                                    <label>Oxygen Saturation High : <p style="display:inline" id="p_oxygensaturationhigh"></p> </label>
                                </div>

                                <div class="col-md-6 form-group mb-3 ">
                                    <label>Oxygen Saturation Low : <p style="display:inline" id="p_Oxygensaturationlow"></p> </label>
                                </div>

                                <div class="col-md-6 form-group mb-3 ">
                                    <label>Glucose High : <p style="display:inline" id="p_glucosehigh"></p></label>
                                </div>

                                <div class="col-md-6 form-group mb-3 ">
                                    <label>Glucose Low : <p style="display:inline" id="p_glucoselow"></p></label>
                                </div>

                                <div class="col-md-6 form-group mb-3 ">
                                    <label>Temperature High : <p style="display:inline" id="p_temperaturehigh"></p></label>
                                </div>

                                <div class="col-md-6 form-group mb-3 ">
                                    <label>Temperature Low : <p style="display:inline" id="p_temperaturelow"></p></label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" @click="closeModal">Close</button>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number,
    },
    setup() {
        const isOpen = ref(false);
        const openModal = () => {
            console.log('Open modal called');
            isOpen.value = true;
        };

        const closeModal = () => {
            console.log('Close modal called');
        isOpen.value = false;
        };

        return {
            isOpen,
            openModal,
            closeModal,
        };
    },
};
</script>

<style>
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
    height: auto !important;
    /* 800px !important; */
}
</style>
