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
                                <img src="@@/assets/images/faces/avatar.png" class='user-image' style="width: 60px;" />
                            </div>
                            <div class="col-md-11"> 
                                <div class="form-row">
                                    <div class="col-md-2 right-divider">
                                        <div data-toggle="tooltip" data-placement="top" title="Name"
                                            :textContent="patientName" class="mb-1"></div>
                                        <div data-toggle="tooltip" title="Gender" class="mb-1">
                                            <span v-if="patientGender == '0'">Male | <span
                                                    :textContent="patientAge"></span></span>
                                            <span v-else-if="patientGender == '1'">Female |( <span
                                                    :textContent="patientAge"></span> )</span>
                                            <span v-else>'| '</span>
                                        </div>
                                        <div data-toggle="tooltip" title="DOB" :textContent="patientDob" class="mb-1"></div>
                                        <div data-toggle="tooltip" id="basix-info-fin_number" title="FIN Number"
                                            data-original-title="Patient FIN Number" class="mb-1 pr-4">
                                            <i class="text-muted i-ID-Card"></i> :
                                            <a class="btn btn-info btn-sm patient_finnumber"
                                                @click="patient_finnumber_function"
                                                style="background-color:#27a7de;border:none;" href="javascript:void(0)"
                                                id="patient_finnumber">
                                                <span id="fin_number" class="patient_fin_number"
                                                    :textContent="finNumber"></span>
                                            </a>
                                            <FinNumber ref="finnumberRef" :patientId="patientId" :moduleId="moduleId"
                                                :componentId="componentId" :stageid="stageid" :finNumber="finNumber" :patientFinNumberTab="PatientBasicInfoReload"/>
                                        </div>
                                        <a class="btn btn-info btn-sm" style="background-color:#27a7de;border:none;"
                                            href="javascript:void(0)" id="show-modal" @click="veteranServicefunction">
                                            Veteran Service -
                                            <span v-if="military_status == 0">Yes</span>
                                            <span v-else-if="military_status == 1">No</span>
                                            <span v-else-if="military_status == 2">Unknown</span>
                                            <span v-else="military_status==''"></span>
                                        </a>
                                        <Veteran ref="veteranRef" :patientId="patientId" :moduleId="moduleId"
                                            :componentId="componentId" :stageid="stageid" />

                                    </div>
                                    <div class="col-md-3 right-divider">
                                        <div data-toggle="tooltip" data-placement="right" title="Contact Number"
                                            class="mb-1"><i class="text-muted i-Old-Telephone"></i> : <b><span
                                                    :textContent="patientMob"></span></b></div>
                                        <div data-toggle="tooltip" id="basix-info-concent-text" title="Consent Text"
                                            data-original-title="Consent Text" class="mb-1 pr-4"><i
                                                class="text-muted i-Speach-Bubble-Dialog"></i> : <span id="concent_to_text"
                                                class="patient_concent_to_text"> Consent to text -
                                                <span v-if="consent_to_text == '0'">NO </span>
                                                <span v-else-if="consent_to_text == '1'">Yes </span>
                                                <span v-else>''</span>
                                            </span></div>
                                        <div data-toggle="tooltip" data-placement="right" title="Address" class="mb-1 pr-4">
                                            <i class="text-muted i-Post-Sign"></i>:
                                            <span id="basic-info-address" :textContent="patientAddress"></span>
                                        </div>
                                        <a class="btn btn-info btn-sm" style="background-color:#27a7de;border:none;"
                                            href="javascript:void(0)" id="show-modal1" @click="alertThresholdfunction">Alert Thresholds</a>
                                        <AlertThresholds ref="alertThresholdsRef" :patientId="patientId"
                                            :moduleId="moduleId" :componentId="componentId" :stageid="stageid"
                                            :patient_systolichigh="patient_systolichigh"
                                            :patient_systoliclow="patient_systoliclow"
                                            :patient_diastolichigh="patient_diastolichigh"
                                            :patient_diastoliclow="patient_diastoliclow" :patient_bpmhigh="patient_bpmhigh"
                                            :patient_bpmlow="patient_bpmlow" :patient_oxsathigh="patient_oxsathigh"
                                            :patient_oxsatlow="patient_oxsatlow" :patient_glucosehigh="patient_glucosehigh"
                                            :patient_glucoselow="patient_glucoselow"
                                            :patient_temperaturehigh="patient_temperaturehigh"
                                            :patient_temperaturelow="patient_temperaturelow"
                                            :patient_weighthigh="patient_weighthigh" :patient_weightlow="patient_weightlow"
                                            :patient_spirometerfevhigh="patient_spirometerfevhigh"
                                            :patient_spirometerfevlow="patient_spirometerfevlow"
                                            :patient_spirometerpefhigh="patient_spirometerpefhigh"
                                            :patient_spirometerpeflow="patient_spirometerpeflow" />
                                    </div>
                                    <div class="col-md-2 right-divider">
                                        <div data-toggle="tooltip" data-placement="top" title="Practice"
                                            data-original-title="Patient Practice" class="mb-1">
                                            <i class="text-muted i-Hospital"></i> :<sapn :textContent="practice_name">
                                            </sapn>
                                        </div>
                                        <div data-toggle="tooltip" data-placement="top" title="Provider"
                                            data-original-title="Patient Provider" class="mb-1">
                                            <i class="text-muted i-Doctor"></i> :<sapn :textContent="provider_name"> </sapn>
                                        </div>
                                        <div data-toggle="tooltip" data-placement="top" title="EMR"
                                            data-original-title="Patient EMR" class="mb-1">
                                            <i class="text-muted i-ID-Card"></i> :<sapn :textContent="practice_emr"> </sapn>
                                        </div>
                                        <div data-toggle="tooltip" data-placement="top" title="Assign CM"
                                            data-original-title="Assign CM" class="mb-1">
                                            <i class="text-muted i-Talk-Man"></i> :<sapn :textContent="caremanager_name">
                                            </sapn>
                                        </div>
                                    </div>
                                    <div class="col-md-2 right-divider">
                                        <i class="text-muted i-Search-People"></i>
                                        <span data-toggle="tooltip" data-placement="right" title="Enrollment Status"
                                            data-original-title="Patient Enrollment Status"
                                            id="PatientStatus" class="mb-1">
                                        </span> 
                                        <!-- :textContent="patient_module" -->
                                        <PatientStatus ref="PatientStatusRef" :moduleId="moduleId" :componentId="componentId" :PatientBasicInfoTab="PatientBasicInfoReload"/>
                                        <span patient_enroll_date v-if="patient_module_status == '1'"> Active
                                            <a @click="() => patientServiceStatus('1')" style="margin-left: 15px;"
                                                class="ActiveDeactiveClass" id="active">
                                                <i class="i-Yess i-Yes" id="ideactive" data-toggle="tooltip"
                                                    data-placement="top" data-original-title="Activate"></i>
                                            </a>
                                        </span> 

                                        <span patient_enroll_date v-if="patient_module_status == '0'"> Suspended
                                            <a @click="() => patientServiceStatus('0')" style="margin-left: 15px;"
                                                class="ActiveDeactiveClass" id="suspend">
                                                <i class="i-Closee i-Close" id="isuspended" data-toggle="tooltip"
                                                    data-placement="top" data-original-title="Suspended"></i>
                                            </a>
                                                From - : <span :textContent="suspended_from_date "></span>
                                                To - : <span :textContent="suspended_to_date "></span>
                                        </span>
                                        <span patient_enroll_date v-if="patient_module_status == '2'"> Deactivated
                                            <a @click="() => patientServiceStatus('2')" style="margin-left: 15px;"
                                                class="ActiveDeactiveClass" id="deactive">
                                                <i class="i-Closee i-Close" id="ideactive" data-toggle="tooltip"
                                                    data-placement="top" data-original-title="Deactivate"></i>
                                            </a>
                                            <span :textContent="suspended_from_date"></span>
                                        </span>
                                        <span patient_enroll_date v-if="patient_module_status == '3'">  Deceased
                                            <a @click="() => patientServiceStatus('3')" style="margin-left: 15px;"
                                                class="ActiveDeactiveClass" id="deceased">
                                                <i class="i-Closee i-Close" id="ideceased" data-toggle="tooltip"
                                                    data-placement="top" data-original-title="Deceased"></i>
                                            </a>
                                        </span>
                                        <br />
                                        <span data-toggle="tooltip" data-placement="right" title="Enrolled Services"
                                            data-original-title="Patient Enrolled Services">
                                            <i class="text-muted i-Library"></i> :
                                            <span v-for="(service, index) in enrolledServices" :key="index"
                                                v-html="service"></span>
                                        </span>
                                        <!-- <a style="margin-left: 15px; font-size: 15px;" class="adddeviceClass" id="deviceadd"
                                            @click="add_devicesfunction">
                                            <i class="plus-icons i-Add" id="adddevice" data-toggle="tooltip"
                                                data-placement="top" data-original-title="Additional Device"></i></a> -->
                                        <AddDeviceModal ref="AddDeviceModalRef" :patientId="patientId" :moduleId="moduleId"
                                            :componentId="componentId" :stageid="stageid" />
                                        <br />
                                        <span v-for="service in patientenrolledServices" :key="service">
                                            <span v-if="service.trim() === 'RPM'">
                                                <!-- Display "btn" if service is "RPM" -->
                                                <a class="btn btn-info btn-sm" style="background-color:#27a7de;border:none;" href="javascript:void(0)" id="add-patient-devices" @click="add_devicesfunction">Devices</a>
                                                <DeviceModal ref="DeviceModalRef" :patientId="patientId" :moduleId="moduleId" :componentId="componentId" :stageid="stageid" :patientAddDeviceTab="PatientBasicInfoReload"/>
                                            </span>
                                        </span>
                                        <!-- add-patient-devices -->
                                        <div id="newenrolldate">
                                            <span data-toggle="tooltip" data-placement="right" title="Enrolled Date"
                                                data-original-title="Enrolled Date"><i class="text-muted i-Over-Time"></i> :
                                                <span :textContent="date_enrolled"></span></span>
                                        </div>
                                        <span data-toggle="tooltip" data-placement="right" title="Device Code"
                                            data-original-title="Patient Device Code.">
                                            <i class="text-muted i-Hospital"></i> :
                                            <span v-html="patient_device"></span>
                                        </span>
                                        <input type="hidden" name="device_code" value="PatientDevices">
                                    </div>
                                    <div class="row col-md-3">
                                        <div class="col-md-11 careplan">
                                            <span data-toggle="tooltip" data-placement="right" title="Billable Time"
                                                data-original-title="Billable Time"><i class="text-muted i-Clock-4"></i> :
                                                <span class="last_time_spend" :textContent="billable_time">
                                                </span></span>
                                            <span data-toggle="tooltip" data-placement="right" title="Non Billable Time"
                                                data-original-title="Non Billable Time"> / <span
                                                    class="non_billabel_last_time_spend" :textContent="non_billabel_time">
                                                </span></span>
                                            <button class="button"
                                                style="border: 0px none;background: #f7f7f7;outline: none;"><a
                                                    :href="EditPatientUrl" title="Edit Patient Info" data-toggle="tooltip"
                                                    data-placement="top" data-original-title="Edit Patient Info"><i
                                                        class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a></button>
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
                                                        <span id="time-containers" :textContent="total_time"></span></label>
                                                    <button class="button" id="start" data-toggle="tooltip"
                                                        data-placement="top" title="Start Timer"
                                                        data-original-title="Start Timer"
                                                        @click="logTimeStart(patientId, moduleId, 19, 0, 1, 0, 'log_time_ccm_monthly-monitoring')"
                                                        style="display: none;cursor: pointer;"><img
                                                            src="@@/assets/images/play.png"
                                                            style=" width: 28px;" /></button>
                                                    <button class="button" id="pause" data-toggle="tooltip"
                                                        data-placement="top" title="Pause Timer"
                                                        data-original-title="Pause Timer"
                                                        @click="logTime(patientId, moduleId, 19, 0, 1, 0, 'log_time_ccm_monthly-monitoring')"
                                                        style="cursor: pointer;"><img src="@@/assets/images/pause.png"
                                                            style=" width: 28px;" /></button>
                                                    <button class="button" id="stop" data-toggle="tooltip"
                                                        data-placement="top" title="Stop Timer"
                                                        data-original-title="Stop Timer"
                                                        @click="logTime(patientId, moduleId, 19, 0, 1, 0, 'log_time_ccm_monthly-monitoring')"
                                                        style="cursor: pointer;"><img src="@@/assets/images/stop.png"
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
                                            <a class="btn btn-info btn-sm" style="background-color:#27a7de;border:none;"
                                                href="javascript:void(0)" id="personal_notes"
                                                @click="personalnotesfunction">Personal Notes</a>
                                            <PersonalNotes ref="personalnotesRef" :patientId="patientId"
                                                :moduleId="moduleId" :componentId="componentId" :stageid="stageid"
                                                :personal_notes_data="personal_notes_data" />|
                                            <a class="btn btn-info btn-sm" style="background-color:#27a7de;border:none;"
                                                href="javascript:void(0)" id="part_of_research_study"
                                                @click="researchstudyfunction">Research
                                                Study</a>
                                            <ResearchStudy ref="researchstudyRef" :patientId="patientId"
                                                :moduleId="moduleId" :componentId="componentId" :stageid="stageid"
                                                :research_study_data="research_study_data" />
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
import { usePage } from '@inertiajs/vue3';
import moment from 'moment';
import axios from 'axios';
import AddDeviceModal from '../../Modals/AddDeviceModal.vue';
import DeviceModal from '../../Modals/DeviceModal.vue';
import PatientStatus from '../../Modals/PatientStatus.vue'; // Import your layout component
import AlertThresholds from '../../Modals/AlertThresholds.vue'; // Import your layout component
import Veteran from '../../Modals/VeteranService.vue';
import AddDevices from '../../Modals/AddDevices.vue';
import FinNumber from '../../Modals/FinNumber.vue';
import PersonalNotes from '../../Modals/PersonalNotes.vue';
import ResearchStudy from '../../Modals/ResearchStudy.vue';
export default {
    props: {
        patientId: Number,
        moduleId: Number,
        stageid: Number,
        componentId: Number,
        personal_notes_data: String,
        research_study_data: String,
        patient_systolichigh: Number,
        patient_systoliclow: Number,
        patient_diastolichigh: Number,
        patient_diastoliclow: Number,
        patient_bpmhigh: Number,
        patient_bpmlow: Number,
        patient_oxsathigh: Number,
        patient_oxsatlow: Number,
        patient_glucosehigh: Number, 
        patient_glucoselow: Number,
        patient_temperaturehigh: Number,
        patient_temperaturelow: Number,
        patient_weighthigh: Number,
        patient_weightlow: Number,
        patient_spirometerfevhigh: Number,
        patient_spirometerfevlow: Number,
        patient_spirometerpefhigh: Number,
        patient_spirometerpeflow: Number,
        loading: "",
        // patientServices: [],
        // patientEnrolledServices: [],
    },
    components: {
        AddDeviceModal,
        DeviceModal,
        AlertThresholds,
        Veteran,
        FinNumber,
        PersonalNotes,
        ResearchStudy,
        AddDevices,
        PatientStatus,
        // AdditionalDevices,
    },
    setup(props) {
        const { callExternalFunctionWithParams } = PatientStatus.setup();
        const veteranRef = ref();
        const AddDeviceModalRef = ref();
        const DeviceModalRef = ref();
        const alertThresholdsRef = ref();
        const finnumberRef = ref();
        const personalnotesRef = ref();
        const researchstudyRef = ref();
        const patientName = ref();
        const patientGender = ref();
        const patientAge = ref();
        const patientDob = ref();
        const patientMob = ref();
        const finNumber = ref();
        const consent_to_text = ref();
        const military_status = ref();
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
        const personal_notes_data = ref();
        const research_study_data = ref();
        const patient_systolichigh = ref();
        const patient_systoliclow = ref();
        const patient_diastolichigh = ref();
        const patient_diastoliclow = ref();
        const patient_bpmhigh = ref();
        const patient_bpmlow = ref();
        const patient_oxsathigh = ref();
        const patient_oxsatlow = ref();
        const patient_glucosehigh = ref();
        const patient_glucoselow = ref();
        const patient_temperaturehigh = ref();
        const patient_temperaturelow = ref();
        const patient_weighthigh = ref();
        const patient_weightlow = ref();
        const patient_spirometerfevhigh = ref();
        const patient_spirometerfevlow = ref();
        const patient_spirometerpefhigh = ref();
        const patient_spirometerpeflow = ref();
        const billable_time = ref();
        const non_billabel_time = ref();
        const total_time = ref(); 
        const PatientStatusRef = ref();

        // const enrolledServices = ref(null);
        const enrolledServices = ref([]);
        const patientenrolledServices = ref([]);
        const patientDetails = ref(null);
        const EditPatientUrl = '/patients/registered-patient-edit/' + props.patientId + '/' + props.moduleId + '/' + props.componentId + '/0';
        var pause_stop_flag = 0;
        var pause_next_stop_flag = 0;
        const showAddPatientDevices = ref(false);

        const patientServiceStatus = async (pstatus) => {
            PatientStatusRef.value.openModal();
            callExternalFunctionWithParams(props.patientId, pstatus);
        }


        const add_devicesfunction = async () => {
            console.log("openModeladdDevices");
            AddDeviceModalRef.value.openModal();
        };

        const add_additional_devicesfunction = async () => {
            DeviceModalRef.value.openModal();
        };

        const veteranServicefunction = async () => {
            veteranRef.value.openModal();
        }

        const alertThresholdfunction = async () => {
            alertThresholdsRef.value.openModal();
        };


        const personalnotesfunction = async () => {
            personalnotesRef.value.openModal();
        };

        const researchstudyfunction = async () => {
            researchstudyRef.value.openModal();
            // patComDetails();
        };

        const patient_finnumber_function = async () => {
            finnumberRef.value.openModal();
        }

        const patComDetails = async () => {
            try {
                const response = await fetch(`/patients/patient-details/${props.patientId}/${props.moduleId}/patient-details`);
                if (!response.ok) {
                    throw new Error(`Failed to fetch Patient details - ${response.status} ${response.statusText}`);
                }
                const data = await response.json();
                // alert(data.patient[0].fin_number);
                const dobParts = data.patient[0].dob.split('-');
                const formattedDob = `${dobParts[1]}-${dobParts[2]}-${dobParts[0]}`;
                patientDetails.value = data;
                billable_time.value = data.billable_time;
                non_billabel_time.value = data.non_billabel_time;
                // patientName.value = data.patient[0].fname + " " + data.patient[0].lname;
                // Capitalize the first letter of the first name
                const patientFname = data.patient[0].fname.replace(/\b\w/g, (char) => char.toUpperCase());

                // Capitalize the first letter of the last name
                const patientLname = data.patient[0].lname.replace(/\b\w/g, (char) => char.toUpperCase());

                patientName.value = `${patientFname} ${patientLname}`;
                patientGender.value = data.gender;
                patientAge.value = data.age;
                patientDob.value = formattedDob;
                patientMob.value = data.patient[0].mob;
                consent_to_text.value = data.consent_to_text;
                finNumber.value = data.patient[0].fin_number;
                consent_to_text.value = data.consent_to_text;
                military_status.value = data.military_status;
                if (data.PatientAddress) {
                    patientAddress.value = `${data.PatientAddress.add_1}, ${data.PatientAddress.add_2}, ${data.PatientAddress.city}, ${data.PatientAddress.state}, ${data.PatientAddress.zipcode}`;
                } else {
                    // Handle the case when PatientAddress is null
                    patientAddress.value = '';
                }
                practice_name.value = data.practice_name;
                provider_name.value = data.provider_name;
                practice_emr.value = data.practice_emr;
                // caremanager_name.value = data.caremanager_name;
                caremanager_name.value = data.caremanager_name.replace(/\b\w/g, (char) => char.toUpperCase());
                date_enrolled.value = data.date_enrolled;
                patient_module.value = data.patient_services[0].module.module;
                patient_module_status.value = data.patient_services[0].status;

                // suspended_from_date.value = data.patient_services[0].suspended_from;
                const suspendedFromDate = data.patient_services[0].suspended_from;
                if (suspendedFromDate) {
                    const dateObject = new Date(suspendedFromDate);
                    const formattedDate = `${String(dateObject.getMonth() + 1).padStart(2, '0')}-${String(dateObject.getDate()).padStart(2, '0')}-${dateObject.getFullYear()}`;
                    suspended_from_date.value = formattedDate;
                } else {
                    suspended_from_date.value = ''; // Handle the case when suspended_from_date is null or undefined
                }
                // suspended_to_date.value = data.patient_services[0].suspended_to;
                const suspendedToDate = data.patient_services[0].suspended_to;
                if (suspendedToDate) {
                    const dateObject = new Date(suspendedToDate);
                    const formattedDate = `${String(dateObject.getMonth() + 1).padStart(2, '0')}-${String(dateObject.getDate()).padStart(2, '0')}-${dateObject.getFullYear()}`;
                    suspended_to_date.value = formattedDate;
                } else {
                    suspended_to_date.value = ''; // Handle the case when suspended_from_date is null or undefined
                }
                patient_device.value = data.device_code;
                // patient_device.value = data.device_code + ' ' + data.patient_assign_device + ' ' + data.device_status

                personal_notes_data.value = data.personal_notes;
                research_study_data.value = data.research_study;

                patient_systolichigh.value = data.systolichigh;
                patient_systoliclow.value = data.systoliclow;
                patient_diastolichigh.value = data.diastolichigh;
                patient_diastoliclow.value = data.diastoliclow;
                patient_bpmhigh.value = data.bpmhigh;
                patient_bpmlow.value = data.bpmlow;
                patient_oxsathigh.value = data.oxsathigh;
                patient_oxsatlow.value = data.oxsatlow;
                patient_glucosehigh.value = data.glucosehigh;
                patient_glucoselow.value = data.glucoselow;
                patient_temperaturehigh.value = data.temperaturehigh;
                patient_temperaturelow.value = data.temperaturelow;
                patient_weighthigh.value = data.weighthigh;
                patient_weightlow.value = data.weightlow;
                patient_spirometerfevhigh.value = data.spirometerfevhigh;
                patient_spirometerfevlow.value = data.temperaturelow;
                patient_spirometerpefhigh.value = data.spirometerpefhigh;
                patient_spirometerpeflow.value = data.spirometerpeflow;
                props.loading = "done";

                const patientServices = data.patient_services;
                const countEnrollServices = patientServices.length;
                const enrollServices = [];
                const enrolledModule = [];
                for (let i = 0; i < countEnrollServices; i++) { 
                    const enrollServicesStatus = patientServices[i].status;
                    let patientEnrollServicesStatus = '';
                    //  console.log(enrollServicesStatus +"enrollServicesStatus");
                    if(enrollServicesStatus == 1 ){ //'Active';
                     patientEnrollServicesStatus ='<i class="i-Yess i-Yes" id="iactive" data-toggle="tooltip" data-placement="top" data-original-title="Activate"></i>';
                    }else if(enrollServicesStatus == 0){ //'Suspended'
                        patientEnrollServicesStatus ='<i class="i-Closee i-Close" id="isuspended" data-toggle="tooltip" data-placement="top" data-original-title="Suspended"></i>';
                    }else if (enrollServicesStatus == 2){ //'Deactivated'
                        patientEnrollServicesStatus = '<i class="i-Closee i-Close" id="ideactive" data-toggle="tooltip" data-placement="top" data-original-title="Deactivate"></i>';
                    }else if(enrollServicesStatus == 3){ //'Deceased'
                        patientEnrollServicesStatus ='<i class="i-Closee i-Close" id="ideceased" data-toggle="tooltip" data-placement="top" data-original-title="Deceased"></i>';
                    }

                    const module = patientServices[i].module.module +' ';
                    // console.log(patientServices[i].module.module+"rrrrrr");
                    // if (patientServices[i].module.module.trim().includes('RPM')) {
                    //     $("#add-patient-devices").show();
                    // } else {
                    //     $("#add-patient-devices").hide();
                    // }
                    const fetchedServices = `${module} - ${patientEnrollServicesStatus}`;
                    enrollServices.push(fetchedServices);
                    enrolledModule.push(module);
                }
                enrolledServices.value = enrollServices;
                patientenrolledServices.value = enrolledModule;

            } catch (error) {
                console.error('Error fetching Patient details:', error.message); // Log specific error message
                // Handle the error appropriately
            }
        }

        const logTimeStart = async (patientId, moduleId, subModuleId, stageId, billable, stepId, formName) => {
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
                $(".change_status_flag").attr("disabled", false);
                
                $("#pause").show();
                $("#stop").show();
                $("#start").hide();
                pause_next_stop_flag = 0;
                setTimeout(function () {
                    pause_stop_flag = 0;
                    if (pause_next_stop_flag == 0) {
                        countDownFunc(patientId, moduleId, response.data.form_start_time);
                    }
                }, 60000);
            }).catch(function (error) {
                console.error(error, error.response);
            });
        }

        const logTime = async (patientId, moduleId, subModuleId, stageId, billable, stepId, formName) => {
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
                    $(".change_status_flag").attr("disabled", true);
                    
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

        const countDownFunc = async () => {
            const start_time = document.getElementById('page_landing_times').value;
            await axios.get(`/system/get-total-time/${props.patientId}/${props.moduleId}/${start_time}/total-time`)
                .then(response => {
                    if (pause_stop_flag == 0) {
                        var data = response.data;
                        var final_time = data['total_time'];
                        $("#ajax-message-history").html(data['history'])
                        total_time.value = final_time;
                        setTimeout(function () {
                            countDownFunc();
                        }, 60000);
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });

        }

        const PatientBasicInfoReload = async () => {
            patComDetails();
            countDownFunc();
        };

        onMounted(async () => {
            patComDetails();
            countDownFunc();
        });

        return {
            PatientBasicInfoReload,
            patientServiceStatus,
            EditPatientUrl,
            AddDeviceModalRef,
            DeviceModalRef,
            veteranRef,
            add_devicesfunction,
            add_additional_devicesfunction,
            alertThresholdfunction,
            alertThresholdsRef,
            veteranServicefunction,
            veteranRef,
            patient_finnumber_function,
            finnumberRef,
            personalnotesfunction,
            personalnotesRef,
            researchstudyfunction,
            PatientStatusRef,
            researchstudyRef,
            patientName,
            patientGender,
            patientAge,
            patientDob,
            patientMob,
            consent_to_text,
            finNumber,
            consent_to_text,
            military_status,
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
            patientenrolledServices,
            personal_notes_data,
            research_study_data,
            patient_systolichigh,
            patient_systoliclow,
            patient_diastolichigh,
            patient_diastoliclow,
            patient_bpmhigh,
            patient_bpmlow,
            patient_oxsathigh,
            patient_oxsatlow,
            patient_glucosehigh,
            patient_glucoselow,
            patient_temperaturehigh,
            patient_temperaturelow,
            patient_weighthigh,
            patient_weightlow,
            patient_spirometerfevhigh,
            patient_spirometerfevlow,
            patient_spirometerpefhigh,
            patient_spirometerpeflow,
            countDownFunc,
            billable_time,
            non_billabel_time,
            total_time,
            logTime,
            logTimeStart

        };
    },
};

</script>