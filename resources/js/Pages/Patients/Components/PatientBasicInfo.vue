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
                                        <span data-toggle="tooltip" data-placement="top" title="Name" :textContent="patientName"></span><br />
                                        <span data-toggle="tooltip" title="Gender">
                                            <span v-if="patientGender == '0'">Male | <span :textContent="patientAge"></span></span>
                                            <span v-else-if="patientGender == '1'">Female |( <span :textContent="patientAge"></span> )</span>
                                            <span v-else>'| '</span>
                                        </span>
                                        <span data-toggle="tooltip" title="DOB" :textContent="patientDob"></span><br />
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
                                            <span>Unknown</span>
                                        </a>
                                        
                                        
                                    </div>
                                    <div class="col-md-3 right-divider">
                                        <span data-toggle="tooltip" data-placement="right" title="Contact Number"><i class="text-muted i-Old-Telephone"></i> : <b></b><span :textContent="patientMob"></span></span><br>
                                        <span data-toggle="tooltip" id="basix-info-concent-text" title="Consent Text"
                                            data-original-title="Consent Text" style="padding-right:2px;"><i
                                                class="text-muted i-Speach-Bubble-Dialog"></i> : <span id="concent_to_text"
                                                class="patient_concent_to_text"> Consent to text -
                                                <span v-if="consent_to_text == '0'">NO </span>
                                                <span v-else-if="consent_to_text == '1'">Yes </span>
                                                <span v-else>''</span>
                                            </span></span><br />
                                        <span data-toggle="tooltip" data-placement="right"
                                            title="Address" style="padding-right:2px;"><i class="text-muted i-Post-Sign"></i>:
                                            <span id="basic-info-address" :textContent="patientAddress" ></span>
                                        </span><br>
                                        <a class="btn btn-info btn-sm" style="background-color:#27a7de;border:none;" id="show-modal1">Alert Thresholds</a>
                                       
                                    </div>
                                    <div class="col-md-2 right-divider">
                                        <span data-toggle="tooltip" data-placement="top" title="Practice"
                                            data-original-title="Patient Practice">
                                            <i class="text-muted i-Hospital"></i> :<sapn :textContent="practice_name"> </sapn>
                                        </span><br>
                                        <span data-toggle="tooltip" data-placement="top" title="Provider"
                                            data-original-title="Patient Provider">
                                            <i class="text-muted i-Doctor"></i> :<sapn :textContent="provider_name"> </sapn>
                                        </span><br>
                                        <span data-toggle="tooltip" data-placement="top" title="EMR"
                                            data-original-title="Patient EMR">
                                            <i class="text-muted i-ID-Card"></i> :<sapn :textContent="practice_emr"> </sapn>
                                        </span>
                                        <br><span data-toggle="tooltip" data-placement="top" title="Assign CM"
                                            data-original-title="Assign CM">
                                            <i class="text-muted i-Talk-Man"></i> :<sapn :textContent="caremanager_name"> </sapn>
                                        </span>
                                    </div>
                                    <div class="col-md-2 right-divider">
                                        <i class="text-muted i-Search-People"></i>
                                        <span data-toggle="tooltip" data-placement="right" title="Enrollment Status" :textContent="patient_module"
                                            data-original-title="Patient Enrollment Status" id="PatientStatus">
                                        </span>
                                        <span patient_enroll_date v-if = "patient_module_status=='1'">
                                            <a @click="() => patientServiceStatus('1')" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass" data-target="#active-deactive" id="active" >
                                                <i class="i-Yess i-Yes" id="ideactive" data-toggle="tooltip" data-placement="top" data-original-title="Activate"></i>
                                            </a>
                                        </span>
                                        
                                        <span patient_enroll_date v-if = "patient_module_status=='0'">
                                            <a @click="() => patientServiceStatus('0')" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass" data-target="#active-deactive" id="suspend">
                                                <i class="i-Closee i-Close" id="isuspended" data-toggle="tooltip" data-placement="top" data-original-title="Suspended"></i>
                                            </a>
                                            Form :  <span :textContent="suspended_from_date"></span>
                                            To :    <span :textContent="suspended_to_date"></span>
                                        </span>
                                        <span patient_enroll_date v-if = "patient_module_status=='2'">
                                            <a @click="() => patientServiceStatus('2')" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass" data-target="#active-deactive" id="deactive">
                                                <i class="i-Closee i-Close" id="ideactive" data-toggle="tooltip" data-placement="top" data-original-title="Deactivate"></i>
                                            </a>
                                        </span>
                                        <span patient_enroll_date v-if = "patient_module_status=='3'">
                                            <a @click="() => patientServiceStatus('3')" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass" data-target="#active-deactive" id="deceased" >
                                                <i class="i-Closee i-Close" id="ideceased" data-toggle="tooltip" data-placement="top" data-original-title="Deceased"></i>
                                            </a>
                                        </span>
                                        <br/>
                                        <span data-toggle="tooltip" data-placement="right" title="Enrolled Services" data-original-title="Patient Enrolled Services">
                                            <i class="text-muted i-Library"></i> :
                                            <span v-for="(service, index) in enrolledServices" :key="index" v-html="service"></span>
                                        </span>
                                        <a style="margin-left: 15px; font-size: 15px;" class="adddeviceClass" id="deviceadd" @click="add_devicesfunction">
                                        <i class="plus-icons i-Add" id="adddevice" data-toggle="tooltip" data-placement="top" data-original-title="Additional Device"></i></a>
                                        <AddDeviceModal ref="AddDeviceModalRef" :patientId="patientId" :moduleId="moduleId" :componentId="componentId" :stageid="stageid" />
                                        <br/>
                                        <!-- add-patient-devices -->
                                        <a class="btn btn-info btn-sm" style="background-color:#27a7de;border:none;" id="add-patient-devices" @click="add_additional_devicesfunction">Devices</a>
                                        <DeviceModal ref="DeviceModalRef" :patientId="patientId" :moduleId="moduleId" :componentId="componentId" :stageid="stageid" />
                                        
                                        
                                        <div id="newenrolldate">
                                            <span data-toggle="tooltip" data-placement="right" title="Enrolled Date"
                                                data-original-title="Enrolled Date"><i class="text-muted i-Over-Time"></i> :
                                                <span :textContent="date_enrolled"></span></span>
                                        </div>
                                        <span data-toggle="tooltip" data-placement="right" title="Device Code"
                                            data-original-title="Patient Device Code.">
                                            <i class="text-muted i-Hospital"></i> : 
                                            <span :textContent="patient_device"></span>
                                        </span>
                                        <input type="hidden" name="device_code" value="PatientDevices">
                                    </div>
                                    <div class="row col-md-3">
                                        <div class="col-md-11 careplan">
                                            <span data-toggle="tooltip" data-placement="right" title="Billable Time"
                                                data-original-title="Billable Time"><i class="text-muted i-Clock-4"></i> :
                                                <span class="last_time_spend">billable_time
                                                </span></span>
                                            <span data-toggle="tooltip" data-placement="right" title="Non Billable Time"
                                                data-original-title="Non Billable Time"> / <span
                                                    class="non_billabel_last_time_spend">
                                                    non_billabel_time</span></span>
                                            <button class="button"
                                                style="border: 0px none;background: #f7f7f7;outline: none;"><a
                                                    :href="url" title="Edit Patient Info"
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
                                                <a class="btn btn-info btn-sm" style="background-color:#27a7de;border:none;" id="personal_notes" @click="personalnotesfunction">Personal Notes</a> 
                                                <PersonalNotes ref="personalnotesRef" :patientId="patientId" :moduleId="moduleId" :componentId="componentId" :stageid="stageid" />|
                                                <a class="btn btn-info btn-sm" style="background-color:#27a7de;border:none;" id="part_of_research_study"
                                                @click ="researchstudyfunction">Research Study</a>
                                                <ResearchStudy ref="researchstudyRef" :patientId="patientId" :moduleId="moduleId" :componentId="componentId" :stageid="stageid" />
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
<script>
import { ref, onMounted, defineProps } from 'vue';
import { usePage } from '@inertiajs/inertia-vue3';
import moment from 'moment';
import axios from 'axios';
import AddDeviceModal from '../../Modals/AddDeviceModal.vue';
import DeviceModal from '../../Modals/DeviceModal.vue';
import patientStatus from '../../Modals/patientStatus.vue'; // Import your layout component
export default {
    props: {
		patientId: Number,
		moduleId: Number,
        stageid: Number,
		componentId: Number,
        loading: "",
    patientServices: [],
    patientEnrolledServices:[],
	},
  components: {
    AddDeviceModal,
    DeviceModal,
  },
  setup(props) {
    const { callExternalFunctionWithParams } = patientStatus.setup();
    const veteranRef = ref();
    const add_devicesRef = ref();
    const additional_devicesRef = ref();
    const AddDeviceModalRef =ref();
    const DeviceModalRef =ref();
    const patientName = ref();
    const patientGender = ref();
    const patientAge = ref();
    const patientDob = ref();
    const patientMob = ref();
    const consent_to_text = ref();
    const patientAddress = ref();
    const practice_name = ref();
    const provider_name = ref();
    const practice_emr = ref();
    const caremanager_name = ref();
    const date_enrolled = ref();
    const patient_module = ref();
    const patient_module_status = ref();
    const suspended_from_date = ref();
    const suspended_to_date = ref();
    const patient_device = ref(); 
/*     const props = defineProps({
    patientId: Number,
    moduleId: Number,
    componentId:Number,
    loading: "",
    patientServices: [],
    patientEnrolledServices:[],
    // enrolledServices:[] 
}); */


// const enrolledServices = ref(null);
const enrolledServices = ref([]);
const patientDetails = ref(null);
const url = '/patients/registerd-patient-edit/'+props.patientId+'/'+ props.moduleId+'/'+props.componentId+'/0';
var pause_stop_flag = 0;
var pause_next_stop_flag = 0;
const showAddPatientDevices = ref(false);

    const patientServiceStatus = async(pstatus)=>{
        callExternalFunctionWithParams(props.patientId,pstatus);
        }



    const add_devicesfunction = async() =>{
        console.log("openModeladdDevices");
        AddDeviceModalRef.value.openModal();
    };

    const add_additional_devicesfunction = async() =>{
        //additional_devicesRef.value.openModal();
        DeviceModalRef.value.openModal();
    };


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

const patientAlertThresholdsModalDetails = async()=>{ 
    try {
        const response = await fetch(`/patients/patient-details/${props.patientId}/${props.moduleId}/patient-details`);
        if (!response.ok) {
            throw new Error(`Failed to fetch Patient alertThreshold details - ${response.status} ${response.statusText}`);
        }
        console.log('Fetched Patient alertThreshold details:', response.data); 
    }catch (error) {
        console.error('Error fetching Patient alertThreshold details:', error.message); // Log specific error message
        // Handle the error appropriately
    }
}

const patComDetails = async()=> {
    try {
        const response = await fetch(`/patients/patient-details/${props.patientId}/${props.moduleId}/patient-details`);
        if (!response.ok) {
            throw new Error(`Failed to fetch Patient details - ${response.status} ${response.statusText}`);
        }
        const data = await response.json();
        patientDetails.value = data;
        patientName.value =  data.patient[0].fname + data.patient[0].lname;
        patientGender.value = data.gender;
        patientAge.value = data.age;
        patientDob.value =  data.patient[0].dob;
        patientMob.value = data.patient[0].mob;
        consent_to_text.value = data.consent_to_text;   
        patientAddress.value = data.PatientAddress.add_1 +','+ data.PatientAddress.add_2 +','+data.PatientAddress.city+','+data.PatientAddress.state+','+data.PatientAddress.zipcode;
        practice_name.value = data.practice_name;
        provider_name.value = data.provider_name;
        practice_emr.value = data.practice_emr;
        caremanager_name.value = data.caremanager_name;
        date_enrolled.value = data.date_enrolled; 
        patient_module.value = data.patient_services[0].module.module;
        patient_module_status.value = data.patient_services[0].module.status;
        suspended_from_date.value = data.patient_services[0].suspended_from;
        suspended_to_date.value = data.patient_services[0].suspended_to;
        patient_device.value = data.device_code +' '+data.patient_assign_device+' '+data.device_status 
        // console.log(data.add_1+ "PATIENT FNAME");
        console.log(data.patient_services[0].module.module+'date_enrolled');
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
                // this.showAddPatientDevices = true;
            }
            console.log("enrollServices", enrollServices);
        }
        enrolledServices.value = enrollServices;
        console.log(enrolledServices +"enrollServices");
    } catch (error) { 
        console.error('Error fetching Patient details:', error.message); // Log specific error message
        // Handle the error appropriately
    }
}

onMounted(async () => {
    patComDetails();
    // alert(props.componentId+"componentId");
/*     const start_time = document.getElementById('page_landing_times').value;
    countDownFunc(props.patientId, props.moduleId, start_time); */
});

    return {
        patientServiceStatus,
        AddDeviceModalRef,
        DeviceModalRef,
      veteranRef,
      add_devicesfunction,
      add_devicesRef,
      add_additional_devicesfunction,
      additional_devicesRef,
      patientName,
      patientGender,
      patientAge,
      patientDob,
      patientMob,
      consent_to_text,
      patientAddress,
      practice_name,
      provider_name,
      practice_emr,
      caremanager_name, 
      date_enrolled,
      patient_module,
      patient_module_status,
      suspended_from_date,
      suspended_to_date,
      patient_device,
      enrolledServices,
    };
  },
};


/* 
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
    var timearr= document.getElementById('page_landing_times').value;
    $('.timearr').val(timearr);
    var patientId = $('input[name="patient_id"]').val();
    var moduleId = $('input[name="module_id"]').val();
    // Make an AJAX POST request to the specified route
    $.ajax({
      type: 'POST',
      url: '/patients/save-patient-fin-number',
      data: formData,
      success: function(response) { 
        // Display the response message within the modal
        $('#devices_success').html('<div class="alert alert-success">' + response.message + '</div>');
        // Optionally, close the modal after a certain delay
        setTimeout(function() {
          $('#patient-finnumber').modal('hide');
        }, 3000); // Close the modal after 3 seconds (3000 milliseconds)
        updateTimer(patientId, 1,moduleId);
      },
      error: function(xhr, status, error) {
        // Display error messages in case of failure
        $('#devices_success').html('<div class="alert alert-danger">Error: ' + error + '</div>');
      }
    });
});

$('.submit-patient-add_device').on('click', function() {
    // Serialize the form data 
    const formData = $('#patient_add_device_form').serialize();
    // Make an AJAX POST request to the specified route
    var timearr= document.getElementById('page_landing_times').value;
    $('.timearr').val(timearr);
    var patientId = $('input[name="patient_id"]').val();
    var moduleId = $('input[name="module_id"]').val();
    $.ajax({
      type: 'POST',
      url: '/ccm/additional-device-email',
      data: formData,
      success: function(response) { 
        // Display the response message within the modal
        $('#alert-success-additional-device').show();

        // Optionally, close the modal after a certain delay
        setTimeout(function() {
          $('#additional-device').modal('hide');
        }, 3000); // Close the modal after 3 seconds (3000 milliseconds)
        updateTimer(patientId, 1,moduleId);
      },
      error: function(xhr, status, error) {
        // Display error messages in case of failure
        $('#alert-success-additional-device').hide();
      }
    });
});


 
$('.submit-personal-notes').on('click', function() { 
    // Serialize the form data
    const formData = $('#personal_notes_form').serialize();
    // Make an AJAX POST request to the specified route
    var timearr= document.getElementById('page_landing_times').value;
    $('.timearr').val(timearr);
    var patientId = $('input[name="patient_id"]').val();
    var moduleId = $('input[name="module_id"]').val();
    $.ajax({
      type: 'POST',
      url: '/patients/patient-personal-notes',
      data: formData,
      success: function(response) { 
        // Display the response message within the modal
        $('#patientalertdiv').html('<div class="alert alert-success">' + response.message + '</div>');

        // Optionally, close the modal after a certain delay
        setTimeout(function() {
          $('#personal-notes').modal('hide');
        }, 3000); // Close the modal after 3 seconds (3000 milliseconds)
        updateTimer(patientId, 1,moduleId);
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
    var timearr= document.getElementById('page_landing_times').value;
    $('.timearr').val(timearr);
    var patientId = $('input[name="patient_id"]').val();
    var moduleId = $('input[name="module_id"]').val();
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
          $('#part-of-research-study').modal('hide');
        }, 3000); // Close the modal after 3 seconds (3000 milliseconds)
        updateTimer(patientId, 1,moduleId);

      },
      error: function(xhr, status, error) {
        // Display error messages in case of failure
        $('#patientalertdiv').html('<div class="alert alert-danger">Error: ' + error + '</div>');
      }
    });
});
 */</script> 
