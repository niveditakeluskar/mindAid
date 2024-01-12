<template>
    <div v-if="loading === 'done'">
        <div id='success'></div>
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card grey-bg">
                    <div class="card-body">
                        <div class="form-row">
                            <input type="hidden" name="hidden_id" id="hidden_id" :value="patientId">
                            <input type="hidden" name="page_module_id" id="page_module_id" :value="moduleId">
                            <input type="hidden" name="page_component_id" id="page_component_id">
                            <input type="hidden" name="service_status" id="service_status">
                            <input type="hidden" id="timer_runing_status" value="0">
                            <div class="col-md-1">

                                <img src="" class='user-image' style="width: 60px;" />
                            </div>
                            <div class="col-md-11">
                                <div class="form-row">
                                    <div class="col-md-2 right-divider">
                                        <span data-toggle="tooltip" data-placement="top" title="Name">{{
                                            patientDetails.patient[0].fname }} {{ patientDetails.patient[0].lname
    }}</span><br />
                                        <span data-toggle="tooltip" title="Gender (DOB)" data-original-title="Patient DOB">
                                            {{ (patientDetails.gender === 1) ? "Female" : "Male" }} /
                                        </span>
                                        <span data-toggle="tooltip" title="DOB" data-original-title="Patient DOB"> ({{
                                            patientDetails.age }}) {{ format_date(patientDetails.patient[0].dob)
    }}</span><br />
                                        <span data-toggle="tooltip" id="basix-info-fin_number" title="FIN Number"
                                            data-original-title="Patient FIN Number" style="padding-right:2px;">
                                            <i class="text-muted i-ID-Card"></i> :
                                            <a class="btn btn-info btn-sm patient_finnumber" @click="patient_finnumber_function"
                                             style="background-color:#27a7de;border:none;" id="patient_finnumber">
                                              <span id="fin_number" class="patient_fin_number" ></span>
                                            </a>
                                        </span><br/>
                                        <a class="btn btn-info btn-sm" style="background-color:#27a7de;border:none;" id="show-modal" @click="veteranServicefunction">
                                            Veteran Service -
                                            <span v-if="patientDetails.military_status == 0">Yes</span>
                                            <span v-else-if="patientDetails.military_status == 1">No</span>
                                            <span v-else>Unknown</span>
                                        </a>
                                    </div>
                                    <div class="col-md-3 right-divider">
                                        <span data-toggle="tooltip" data-placement="right" title="Contact Number"
                                            data-original-title="Patient Phone No."><i
                                                class="text-muted i-Old-Telephone"></i> : <b>{{
                                                    patientDetails.patient[0].mob }}</b></span><br>
                                        <span data-toggle="tooltip" id="basix-info-concent-text" title="Consent Text"
                                            data-original-title="Consent Text" style="padding-right:2px;"><i
                                                class="text-muted i-Speach-Bubble-Dialog"></i> : <span id="concent_to_text"
                                                class="patient_concent_to_text">
                                                {{ (patientDetails.consent_to_text === 1) ? "Consent to text - Yes" :
                                                    "Consent to text - No" }}
                                            </span></span><br />
                                        <span data-toggle="tooltip" data-placement="right" id="basix-info-address"
                                            title="Address" data-original-title="Patient Address"
                                            style="padding-right:2px;"><i class="text-muted i-Post-Sign"></i> :
                                            {{ patientDetails.add_1 }}, {{ patientDetails.add_2 }}, {{ patientDetails.city
                                            }}, {{ patientDetails.state }}, {{ patientDetails.zipcode }}
                                        </span><br>
                                        <a class="btn btn-info btn-sm" style="background-color:#27a7de;border:none;" id="show-modal1" @click="alertThresholdfunction">Alert Thresholds</a>
                                    </div>
                                    <div class="col-md-2 right-divider">
                                        <span data-toggle="tooltip" data-placement="top" title="Practice"
                                            data-original-title="Patient Practice">
                                            <i class="text-muted i-Hospital"></i> : {{ patientDetails.practice_name }}
                                        </span><br>
                                        <span data-toggle="tooltip" data-placement="top" title="Provider"
                                            data-original-title="Patient Provider">
                                            <i class="text-muted i-Doctor"></i> : {{ patientDetails.provider_name }}
                                        </span><br>
                                        <span data-toggle="tooltip" data-placement="top" title="EMR"
                                            data-original-title="Patient EMR">
                                            <i class="text-muted i-ID-Card"></i> : {{ patientDetails.practice_emr }}
                                        </span>
                                        <br><span data-toggle="tooltip" data-placement="top" title="Assign CM"
                                            data-original-title="Assign CM">
                                            <i class="text-muted i-Talk-Man"></i> : {{ patientDetails.caremanager_name }}
                                        </span>
                                    </div>
                                    <div class="col-md-2 right-divider">
                                        <i class="text-muted i-Search-People"></i>
                                        <span data-toggle="tooltip" data-placement="right" title="Enrollment Status"
                                            data-original-title="Patient Enrollment Status" id="PatientStatus" value="">
                                            {{ patientDetails.patient_enroll_date[0].status_value }}
                                        </span>
                                        <span  v-if="patientDetails.patient_enroll_date[0].status == 1" >
                                            <a @click="patientServiceStatus()" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass" data-target="#active-deactive" id="active" >
                                                <i class="i-Yess i-Yes" id="ideactive" data-toggle="tooltip" data-placement="top" data-original-title="Activate"></i>
                                            </a>
                                        </span>
                                        <span v-if="patientDetails.patient_enroll_date[0].status == 0">
                                            <a @click="patientServiceStatus()" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass" data-target="#active-deactive" id="suspend">
                                                <i class="i-Closee i-Close" id="isuspended" data-toggle="tooltip" data-placement="top" data-original-title="Suspended"></i>
                                            </a>
                                            {{ 'From:' + format_date(patientDetails.patient_enroll_date[0].suspended_from) +
                                                ' To ' + format_date(patientDetails.patient_enroll_date[0].suspended_to) }}
                                        </span>
                                        <span v-if="patientDetails.patient_enroll_date[0].status == 2">
                                            <a @click="patientServiceStatus()" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass" data-target="#active-deactive" id="deactive">
                                                <i class="i-Closee i-Close" id="ideactive" data-toggle="tooltip" data-placement="top" data-original-title="Deactivate"></i>
                                            </a>
                                        </span>
                                        <span v-if="patientDetails.patient_enroll_date[0].status == 3">
                                            <a @click="patientServiceStatus()" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass" data-target="#active-deactive" id="deceased" >
                                                <i class="i-Closee i-Close" id="ideceased" data-toggle="tooltip" data-placement="top" data-original-title="Deceased"></i>
                                            </a>
                                        </span>
                                        <br/>
                                        <span data-toggle="tooltip" data-placement="right" title="Enrolled Services" data-original-title="Patient Enrolled Services">
                                            <i class="text-muted i-Library"></i> :
                                            <span v-for="(service, index) in enrolledServices" :key="index" v-html="service"></span>
                                            {{ service }} 
                                        </span>
                                        <a style="margin-left: 15px; font-size: 15px;" class="adddeviceClass" id="deviceadd" @click="add_additional_devicesfunction">
                                        <i class="plus-icons i-Add" id="adddevice" data-toggle="tooltip" data-placement="top" data-original-title="Additional Device"></i></a>
                                        <br/>
                                        <div id="newenrolldate">
                                            <span data-toggle="tooltip" data-placement="right" title="Enrolled Date"
                                                data-original-title="Enrolled Date"><i class="text-muted i-Over-Time"></i> :
                                                {{ (patientDetails.date_enrolled) }} </span>
                                        </div>
                                        <span data-toggle="tooltip" data-placement="right" title="Device Code"
                                            data-original-title="Patient Device Code.">
                                            <i class="text-muted i-Hospital"></i> : PatientDevices
                                        </span>
                                        <input type="hidden" name="device_code" value="PatientDevices">
                                    </div>
                                    <div class="row col-md-3">
                                        <div class="col-md-11 careplan">
                                            <span data-toggle="tooltip" data-placement="right" title="Billable Time"
                                                data-original-title="Billable Time"><i class="text-muted i-Clock-4"></i> :
                                                <span class="last_time_spend"> {{ patientDetails.billable_time }}
                                                </span></span>
                                            <span data-toggle="tooltip" data-placement="right" title="Non Billable Time"
                                                data-original-title="Non Billable Time"> / <span
                                                    class="non_billabel_last_time_spend">
                                                    {{ patientDetails.non_billabel_time }} </span></span>
                                            <button class="button"
                                                style="border: 0px none;background: #f7f7f7;outline: none;"><a
                                                    href="/patients/registerd-patient-edit/" title="Edit Patient Info"
                                                    data-toggle="tooltip" data-placement="top"
                                                    data-original-title="Edit Patient Info"><i class=" editform i-Pen-4"
                                                        style="color: #2cb8ea;"></i></a></button>
                                            <div class="demo-div">
                                                <div class="stopwatch" id="stopwatch">
                                                    <i class="text-muted i-Timer1"></i> :
                                                    <div id="time-container" class="container" data-toggle="tooltip"
                                                        data-placement="right" title="Current Running Time"
                                                        data-original-title="Current Running Time"
                                                        style="display:none!important"></div>
                                                    <label for="Current Running Time" data-toggle="tooltip"
                                                        title="Current Running Time"
                                                        data-original-title="Current Running Time">
                                                        <span id="time-containers"></span></label>
                                                    <button class="button" id="start" data-toggle="tooltip"
                                                        data-placement="top" title="Start Timer"
                                                        data-original-title="Start Timer" @click="logTimeStart(patientId,moduleId,19,0,1,0,'log_time_ccm_monthly-monitoring')" style="display: none;cursor: pointer;"><img
                                                            src="/../assets/images/play.png"
                                                            style=" width: 28px;" /></button>
                                                    <button class="button" id="pause" data-toggle="tooltip"
                                                        data-placement="top" title="Pause Timer"
                                                        data-original-title="Pause Timer" @click="logTime(patientId,moduleId,19,0,1,0,'log_time_ccm_monthly-monitoring')" style="cursor: pointer;"><img
                                                            src="/../assets/images/pause.png"
                                                            style=" width: 28px;" /></button>
                                                    <button class="button" id="stop" data-toggle="tooltip"
                                                        data-placement="top" title="Stop Timer"
                                                        data-original-title="Stop Timer" @click="logTime(patientId,moduleId,19,0,1,0,'log_time_ccm_monthly-monitoring')" style="cursor: pointer;"><img
                                                            src="/../assets/images/stop.png"
                                                            style=" width: 28px; " /></button>
                                                    <button class="button" id="reset" data-toggle="tooltip"
                                                        data-placement="top" title="Reset Timer"
                                                        data-original-title="Reset Timer"
                                                        style="display:none;">Reset</button>
                                                    <button class="button" id="resetTickingTime" data-toggle="tooltip"
                                                        data-placement="top" title="resetTickingTime Timer"
                                                        data-original-title="resetTickingTime Timer"
                                                        style="display:none;">resetTickingTime</button>

                                                </div>
                                            </div>


                                                <a class="btn btn-info btn-sm" style="background-color:#27a7de;border:none;" id="personal_notes" @click="personalnotesfunction">Personal Notes</a> |
                                                <a class="btn btn-info btn-sm" style="background-color:#27a7de;border:none;" id="part_of_research_study" @click ="researchstudyfunction">Research Study</a>
                                        </div>
                                    </div>
                                    <div style="padding-left: 823px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted, defineProps } from 'vue';
import { usePage } from '@inertiajs/inertia-vue3';
import moment from 'moment';
import axios from 'axios';
const props = defineProps({
    patientId: Number,
    moduleId: Number,
    loading: "",
    // patientServices: [],
    // patientEnrolledServices:[] 
    enrolledServices:[]
});


// const enrolledServices = ref(null);
const patientDetails = ref(null);
var pause_stop_flag = 0;
var pause_next_stop_flag = 0;
const showAddPatientDevices = ref(false);
const patientServiceStatus = async()=>{
    var sPageURL = window.location.pathname;
    parts = sPageURL.split("/"),
    patientId = parts[parts.length - 1];
}
const veteranServicefunction = async() => {
    const VeteranServiceModal = document.getElementById('vateran-service');
      if (VeteranServiceModal) { 
        $(VeteranServiceModal).modal('show'); // Use jQuery to show the modal
      } else {
        console.error('Modal element not found or jQuery/Bootstrap not properly loaded');
      }
    patientVeteranServiceModalDetails();
}


function devicesclear() {  
    // alert('dadsadasdsa');
    $("#devices_form input[name='device_id']").val('');
    $('#partner_id').val(''); 
    $('#partner_devices_id').val('');
    $(`form[name="devices_form"]`).find(".is-invalid").removeClass("is-invalid");
    $(`form[name="devices_form"]`).find(".invalid-feedback").html("");

}


const add_devicesfunction = async() => {
    const DeviceModal = document.getElementById('add-patient-devices');
      if (DeviceModal) { 
        $(DeviceModal).modal('show'); // Use jQuery to show the modal
      } else {
        console.error('Modal element not found or jQuery/Bootstrap not properly loaded');
      }
    // patientVeteranServiceModalDetails();
}

const add_additional_devicesfunction = async() =>{
    const AdditionalDeviceModal = document.getElementById('additional-device');
      if (AdditionalDeviceModal) { 
        $(AdditionalDeviceModal).modal('show'); // Use jQuery to show the modal
      } else {
        console.error('Modal element not found or jQuery/Bootstrap not properly loaded');
      }
}

const patient_finnumber_function = async()=>{
    const FinNumberModal = document.getElementById('patient-finnumber');
      if (FinNumberModal) { 
        $(FinNumberModal).modal('show'); // Use jQuery to show the modal
      } else {
        console.error('Modal element not found or jQuery/Bootstrap not properly loaded');
      }
      patComDetails();

}
const alertThresholdfunction = async() => {
    const AlertThresholdeModal = document.getElementById('patient-threshold');
    if (AlertThresholdeModal) {
    $(AlertThresholdeModal).modal('show'); // Use jQuery to show the modal
    } else {
    console.error('Modal element not found or jQuery/Bootstrap not properly loaded');
    }
    patComDetails(); 
}
const personalnotesfunction = async() => {
    console.log('dadasdasdasdasdas'+personalnotesfunction);
    const PersonalNotesModal = document.getElementById('personal-notes');
    if (PersonalNotesModal) {
    $(PersonalNotesModal).modal('show'); // Use jQuery to show the modal 
    } else {
    console.error('Modal element not found or jQuery/Bootstrap not properly loaded');
    }
    patComDetails();
    
}

const researchstudyfunction = async() => { 
    const ResearchStudyModal = document.getElementById('part-of-research-study');
    if (ResearchStudyModal) {
    $(ResearchStudyModal).modal('show'); // Use jQuery to show the modal 
    } else {
    console.error('Modal element not found or jQuery/Bootstrap not properly loaded');
    }
    patComDetails();
    
}

const patientVeteranServiceModalDetails = async()=>{ 
    try {
        const response = await fetch(`/patients/patient-VeteranServiceData/${props.patientId}/patient-VeteranServiceData`);
        if (!response.ok) {
            throw new Error(`Failed to fetch Patient VeteranService details - ${response.status} ${response.statusText}`);
        }
        console.log('Fetched Patient VeteranService details:', response.data);
    }catch (error) {
        console.error('Error fetching Patient VeteranService details:', error.message); // Log specific error message
        // Handle the error appropriately
    }
}

const populateSaveValue=async()=>{
    try{
        var patientId = $("#hidden_id").val();
        const respose = await fetch(`/ccm/monthly-monitoring/${props.patientId}`);
        if(!respose.ok){
            throw new Error(`Failed to fetch Patient details - ${response.status} ${response.statusText}`);
        }
        const data = await response.json();
        patientSaveDetails.value = data;
        props.loading = "done";
        console.log('Fetched Patient save details:', data);
    }catch{

    }
}

onMounted(async () => {
    try {
        const response = await fetch(`/patients/patient-details/${props.patientId}/${props.moduleId}/patient-details`);
        if (!response.ok) {
            throw new Error(`Failed to fetch Patient details - ${response.status} ${response.statusText}`);
        }
        const data = await response.json();
        patientDetails.value = data;
        props.loading = "done";
        console.log('Fetched Patient details:', data);
        const patientServices = data.patient_services;
        const countEnrollServices = patientServices.length;
        const enrollServices = [];

        for (let i = 0; i < countEnrollServices; i++) {
            const enrollServicesStatus = patientServices[i].status;
            let patientEnrollServicesStatus = '';

            switch (enrollServicesStatus) {
                case 0:
                    patientEnrollServicesStatus = '<i class="i-Closee i-Close" id="isuspended" data-toggle="tooltip" data-placement="top" data-original-title="Suspended"></i>';
                    break;
                case 1:
                    patientEnrollServicesStatus = '<i class="i-Yess i-Yes" id="iactive" data-toggle="tooltip" data-placement="top" data-original-title="Activate"></i>';
                    break;
                case 2:
                    patientEnrollServicesStatus = '<i class="i-Closee i-Close" id="ideactive" data-toggle="tooltip" data-placement="top" data-original-title="Deactivate"></i>';
                    break;
                case 3:
                    patientEnrollServicesStatus = '<i class="i-Closee i-Close" id="ideceased" data-toggle="tooltip" data-placement="top" data-original-title="Deceased"></i>';
                    break;
                default:
                    break;
            }

            const module = patientServices[i].module.module;
            // console.log (module+"module");  
            // console.log(patientEnrollServicesStatus+"patientEnrollServicesStatus");
            const fetchedServices = `${module}-${patientEnrollServicesStatus}`;
            enrollServices.push(fetchedServices);
            if (module === 'RPM') { 
                // Toggle visibility using a reactive property
                this.showAddPatientDevices = true;
            }
            console.log("enrollServices", enrollServices);
        } 
        this.enrolledServices = enrollServices;
        console.log(enrolledServices +"enrollServices");
    } catch (error) {
        console.error('Error fetching Patient details:', error.message); // Log specific error message
        // Handle the error appropriately
    }
    const start_time = document.getElementById('page_landing_times').value;
    countDownFunc(props.patientId, props.moduleId, start_time);
});

function countDownFunc(patientId, moduleId, start_time) {
    axios({
        method: "GET",
        url: `/system/get-total-time/${patientId}/${moduleId}/${start_time}/total-time`,
    }).then(function (response) {
        if(pause_stop_flag == 0){
        var data = response.data;
        var final_time = data['total_time'];
        $("#ajax-message-history").html(data['history'])
        $("#time-containers").html(final_time);
        setTimeout(function () {
            const start_time = document.getElementById('page_landing_times').value;
            countDownFunc(patientId, moduleId, start_time);
    }, 60000);}
    }).catch(function (error) {
        console.error(error, error.response);
    });
    
}

function logTimeStart(patientId, moduleId, subModuleId, stageId, billable, stepId, formName){
    var timerStart = $('.form_start_time').val();
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
    axios({
        method: "POST",
        url: `/system/log-time/time`,
        data: {
            timerStart: '00:00:00',
            timerEnd: '00:00:00',
            patientId: patientId,
            moduleId: moduleId,
            subModuleId: subModuleId,
            stageId: stageId,
            billable: billable,
            uId: patientId,
            stepId: stepId,
            formName: formName,
            form_start_time: timerStart,
            pause_start_time: timerStart
        }
    }).then(function (response) {
        $("#timer_runing_status").val(0);
        $('.form_start_time').val(response.data.form_start_time);
        $("form").find(":submit").attr("disabled", false);
        $("form").find(":button").attr("disabled", false);
        $("#pause").show();
        $("#stop").show();
        $("#start").hide();
        pause_next_stop_flag = 0;
        setTimeout(function () {
            pause_stop_flag = 0;
            if(pause_next_stop_flag == 0){
                countDownFunc(patientId, moduleId, response.data.form_start_time);
            }
        }, 60000);
    }).catch(function (error) {
        console.error(error, error.response);
    });
}

function logTime(patientId, moduleId, subModuleId, stageId, billable, stepId, formName) {
    var form_start_time = $('.form_start_time').val();
    pause_stop_flag = 1;
    pause_next_stop_flag = 1;
    var timerStart = '00:00:00';
    var timerEnd = '00:00:00';
    $("#timer_runing_status").val(1);
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
    axios({
        method: "POST",
        url: `/system/log-time/time`,
        data: {
            timerStart: timerStart,
            timerEnd: timerEnd,
            patientId: patientId,
            moduleId: moduleId,
            subModuleId: subModuleId,
            stageId: stageId,
            billable: billable,
            uId: patientId,
            stepId: stepId,
            formName: formName,
            form_start_time: form_start_time,
        }
    }).then(function (response) {
        if (JSON.stringify(response.data.end_time) != "" && JSON.stringify(response.data.end_time) != null && JSON.stringify(response.data.end_time) != undefined) {
            $("#timer_start").val(response.data.end_time);
            $("#timer_end").val(response.data.end_time);
            $("#start").show();
            $("#pause").hide();
            $("#stop").hide();
            updateTimer(patientId, billable, moduleId);
            $("form").find(":submit").attr("disabled", true);
            $("form").find(":button").attr("disabled", true);
            //$(".last_time_spend").html(response.data.end_time);
            $('.form_start_time').val(response.data.form_start_time);
            alert("Timer paused and Time Logged successfully.");
        } else {
            alert("Unable to log time, please try after some time.");
        }

    }).catch(function (error) {
        console.error(error, error.response);
    });
}


function format_date(value) {
    if (value) {
        return moment(String(value)).format('MM-DD-YYYY')
    }
}

$('.submit-add-patient-fin-number').on('click', function() {
    // Serialize the form data 
    const formData = $('#fin_number_form').serialize();
    // Make an AJAX POST request to the specified route
    $.ajax({
      type: 'POST',
      url: '/patients/save-patient-fin-number',
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

$('.submit-add-patient-devices').on('click', function() {
    // Serialize the form data 
    const formData = $('#devices_form').serialize();
    // Make an AJAX POST request to the specified route
    $.ajax({
      type: 'POST',
      url: '/patients/master-devices',
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


$('.submit-patient-threshold').on('click', function() {
    // Serialize the form data 
    const formData = $('#patient_threshold_form').serialize();
    // Make an AJAX POST request to the specified route
    $.ajax({
      type: 'POST',
      url: '/patients/patient-threshold',
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
 
$('.submit-personal-notes').on('click', function() {
    // Serialize the form data
    const formData = $('#personal_notes_form').serialize();
    // Make an AJAX POST request to the specified route
    $.ajax({
      type: 'POST',
      url: '/patients/patient-personal-notes',
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

$('.submit-part-of-research-study').on('click', function() {
    // Serialize the form data
    const formData = $('#part_of_research_study_form').serialize();
    // Make an AJAX POST request to the specified route
    $.ajax({
      type: 'POST', 
      url: '/patients/patient-research-study',
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
</script> 
