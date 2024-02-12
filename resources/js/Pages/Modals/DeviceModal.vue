<!-- ReviewCarePlanModal.vue -->
<template>
    <div class="overlay" :class="{ 'open': isOpen }" @click="closeModal"></div>
    <div class="modal fade" :class="{ 'open': isOpen }"> <!-- :style="{ display: isOpen ? 'block' : 'none' }"> -->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Devices</h4>
                <button type="button" class="close" @click="closeModal">×</button>
            </div>
            <div class="modal-body" style="padding-top:10px;">
                <loading-spinner :isLoading="isLoading"></loading-spinner>
                <div id="deviceAlert"></div>
                <form name="devices_form" id="devices_form" @submit.prevent="submitDeviceForm">
                    <input type="hidden" name="patient_id" :value="patientId" />
                    <input type="hidden" name="uid" :value="patientId">
                    <input type="hidden" name="start_time" :value="'00:00:00'">
                    <input type="hidden" name="end_time" :value="'00:00:00'">
                    <input type="hidden" name="module_id" :value="moduleId" />
                    <input type="hidden" name="component_id" :value="componentId" />
                    <input type="hidden" name="stage_id" :value="deviceStageId" />
                    <input type="hidden" name="form_name" value="devices_form">
                    <input type="hidden" name="idd" id="idd">
                    <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time"
                        :value="medicationTime" v-model="medicationTime">
                    <div class="row">
                        <div id="devices_success"></div>
                        <div class="col-md-12 form-group">
                            <label>Devices ID<span class='error'>*</span></label>
                            <input type="text" class="form-control" name="device_id" id="device_id">
                            <div class="invalid-feedback"></div>
                            <div class="invalid-feedback" v-if="formErrors.device_id" style="display: block;">{{
                                formErrors.device_id[0] }}</div>
                        </div>
                        <!-- <div class="col-md-6 form-group">
                                <label>Devices<span class='error'>*</span></label>
                                @selectdevices("devices", ["id"=> "editdevice"]) 
                            </div>  -->
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Partners<span class='error'>*</span></label>
                            <select class="custom-select show-tick" name="partner_id" v-model="selectedPartnerId" @change="handlePartnerDevice">
                                <option v-for="item in partnersOption" :key="item.id" :value="item.id">
                                    {{ item.partner_id }}
                                </option>
                            </select>
                            <div class="invalid-feedback" v-if="formErrors.partner_id" style="display: block;">{{
                                formErrors.partner_id[0] }}</div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Partner Devices<span class='error'>*</span></label>
                            <select class="custom-select show-tick" name="partner_devices_id" >
                                <option v-for="item in partnersDeviceOption" :key="item.id" :value="item.id">
                                    {{ item.device_name }}
                                </option>
                            </select>
                            <div class="invalid-feedback" v-if="formErrors.partner_devices_id" style="display: block;">{{
                                formErrors.partner_devices_id[0] }}</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary float-right submit-add-patient-devices">Submit</button>
                        <button type="button" class="btn btn-default float-left" @click="closeModal">Close</button>
                    </div>
                </form>
                <div class="separator-breadcrumb border-top"></div>
                <div class="row">
                    <div class="col-md-12">
                      
                            <AgGridTable :rowData="passRowData" :columnDefs="columnDefs"/>
                 
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" @click="closeModal">Close</button>
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
    AgGridTable
    // Add other common imports if needed
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
        return {
            isOpen: false,
            medicationTime: false,
        };
    },
    components: {
        AgGridTable,
    },
    methods: {
        openModal() {
            this.isOpen = true;
            this.medicationTime = document.getElementById('page_landing_times').value;
        },
        closeModal() {
            this.isOpen = false;
        },
    },
    setup(props) {
        const partnersOption = ref([]);
        const partnersDeviceOption = ref([]);
        const startTimeInput = ref(null);
        const isSaveButtonDisabled = ref(true);
        const selectedPartnerDeviceId = ref('');
        const comments = ref('');
        const formErrors = ref({});
        const showSuccessAlert = ref(false);
        const isLoading = ref(false);
        const goals = ref([]); // Use ref to declare reactive goals array
        const tasks = ref([]); // Use ref to declare reactive goals array
        const symptoms = ref([]); // Use ref to declare reactive goals array
        const isInitialGoalFilled = ref(false);
        const isInitialTaskFilled = ref(false);
        const isInitialSymptomFilled = ref(false);
        const goalsText = ref(''); // Use ref for the concatenated goals string
        const selectedPartnerId = ref('');
        const selectedCode = ref('');
        const passRowData = ref([]); // Initialize rowData as an empty array
        const loading = ref(false);
        const medicationTime = ref('');
        const deviceStageId = ref(0);

        let codeOptions = ref([]);
        let selectedMedication = ref('');
        const selectedEditDiagnosId = ref('');
        const selectedcondition = ref('');


        const columnDefs = ref([
            {
                headerName: 'Sr. No.',
                valueGetter: 'node.rowIndex + 1',
            },
            { headerName: 'Code', field: 'device_code' },
            { headerName: 'Device Condition', field: 'device_name' },
            { headerName: 'Partner', field: 'name' },
            { headerName: 'Partner Device', field: 'device_name' },
            { headerName: 'Last Modifed By', field: 'updated_at' },
            { headerName: 'Last Modifed On', field: 'updated_at' },
            {
                headerName: 'Action',
                field: 'action',
                cellRenderer: (params) => {
                    const linkContainer = document.createElement('div');
                    // Edit Button
                    const editLink = document.createElement('a');
                    editLink.href = 'javascript:void(0)';
                    editLink.dataset.toggle = 'tooltip';
                    editLink.dataset.id = params.data.id;
                    editLink.dataset.originalTitle = 'Edit';
                    editLink.classList.add('editDevicesdata');
                    editLink.title = 'Edit';

                    const editIcon = document.createElement('i');
                    editIcon.classList.add('editform', 'i-Pen-4');
                    editLink.appendChild(editIcon);

                    linkContainer.appendChild(editLink);

                    // Add a space
                    linkContainer.appendChild(document.createTextNode(' '));

                    // Status Button
                    const statusLink = document.createElement('a');
                    statusLink.href = 'javascript:void(0)';
                    statusLink.dataset.id = params.data.id;

                    if (params.data.status === 1) {
                        statusLink.classList.add('change_device_status_active');
                        statusLink.id = 'active';
                        statusLink.title = 'Active';
                    } else {
                        statusLink.classList.add('change_device_status_deactive');
                        statusLink.id = 'deactive';
                        statusLink.title = 'Deactive';
                    }

                    const statusIcon = document.createElement('i');
                    statusIcon.classList.add(params.data.status === 1 ? 'i-Yess' : 'i-Closee', params.data.status === 1 ? 'i-Yes' : 'i-Close');
                    statusLink.appendChild(statusIcon);

                    linkContainer.appendChild(statusLink);

                    return linkContainer;
                },
            }
        ]);

    

        const fetchDeviceList = async () => {
            try {
                loading.value = true;
                //await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
                const response = await fetch(`/patients/device-deviceslist/${props.patientId}`);
                if (!response.ok) {
                    throw new Error('Failed to fetch followup task list');
                }
                loading.value = false;
                const data = await response.json();
                // Check if data.data is not undefined before assigning it to rowData
                if (data.data) {
                    passRowData.value = data.data;
                } else {
                    console.error('Data is undefined in the response:', data);
                }
            } catch (error) {
                console.error('Error fetching followup task list:', error);
                loading.value = false;
            }
        };

        const handlePartnerDevice = async () => {
            try {
        const partnerid = selectedPartnerId; // Replace with the actual partner ID
        console.log(partnerid,"partner id");
        const response = await axios.get(`/patients/ajax/${partnerid}/practice/practiceId/moduleId/patient`);
        partnersDeviceOption.value = response.data;

       /*  if (selectedPartnerDevice.value) {
          // Set the default selected partner device if available
          selectedPartnerDevice.value = selectedPartnerDevice.value;
        } */
      } catch (error) {
        console.error(error, error.response);
      }
        }

        const editPatientDignosis = async (id) => {
            clearGoals();
            isLoading.value = true;
            try {
                selectedEditDiagnosId.value = id;

                const response = await fetch(`/ccm/diagnosis-select/${id}/${props.patientId}`);
                if (!response.ok) {
                    throw new Error('Failed to fetch followup task list');
                }
                const data = await response.json();
                const carePlanData = data.care_plan_form.static; // Adjust this based on your actual data structure
                if (carePlanData && carePlanData.goals) {
                    goals.value = JSON.parse(carePlanData.goals); // Parse the JSON string to an array
                }
                selectedPartnerDeviceId.value = carePlanData.diagnosis;
                selectedPartnerId.value = carePlanData.diagnosis;
                selectedCode.value = carePlanData.code;
                selectedcondition.value = carePlanData.condition;
                comments.value = carePlanData.comments;
                if (carePlanData && carePlanData.tasks) {
                    tasks.value = JSON.parse(carePlanData.tasks); // Parse the JSON string to an array
                }
                if (carePlanData && carePlanData.symptoms) {
                    symptoms.value = JSON.parse(carePlanData.symptoms); // Parse the JSON string to an array
                }
                isLoading.value = false;
                isSaveButtonDisabled.value = false;
            } catch (error) {
                console.error('Error fetching followup task list:', error);
                isLoading.value = false;
            }
        };

        const deletePatientDignosis = async (id) => {
            const module_id = props.moduleId;
            const component_id = props.componentId;
            const patient_id = props.patientId;
            const stage_id = document.querySelector(`form[name='care_plan_form'] input[name='stage_id']`).value;
            const step_id = document.querySelector(`form[name='care_plan_form'] input[name='step_id']`).value;
            const form_name = document.querySelector(`form[name='care_plan_form'] input[name='form_name']`).value;
            const timer_start = startTimeInput.value.value;;
            const timer_paused = document.getElementById('time-container').textContent;
            const billable = document.querySelector(`form[name='care_plan_form'] input[name='billable']`).value;
            const form_start_time = document.querySelector(`form[name='care_plan_form'] .form_start_time`).value;
            const result = confirm("Are you sure you want to delete the Condition");

            if (result) {
                try {
                    const response = await fetch('/ccm/delete-care-plan', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: new URLSearchParams({
                            id: id,
                            start_time: timer_start,
                            end_time: timer_paused,
                            module_id: module_id,
                            component_id: component_id,
                            patient_id: patient_id,
                            stage_id: stage_id,
                            step_id: step_id,
                            form_name: form_name,
                            billable: billable,
                            form_start_time: form_start_time
                        })
                    });

                    if (!response.ok) {
                        throw new Error(`Failed to delete care plan - ${response.status} ${response.statusText}`);
                    }
                    const responseData = await response.json();

                    alert("Deleted Successfully");

                    updateTimer(props.patientId, '1', props.moduleId);
                    document.querySelector('.form_start_time').value = responseData.form_start_time;
                    /* document.getElementById('time-container').textContent = AppStopwatch.pauseClock; */
                    /*       document.getElementById('timer_start').value = timer_paused;
                          document.getElementById('timer_end').value = timer_paused; */
                    /*    document.getElementById('time-container').textContent = AppStopwatch.startClock; */
                } catch (error) {
                    console.error('Error deleting care plan:', error.message);
                }
            }
        };

        const submitDeviceForm = async () => {
            isLoading.value = true;
            let myForm = document.getElementById('devices_form');
            let formData = new FormData(myForm);
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const response = await axios.post('/patients/master-devices', formData);
                if (response && response.status == 200) {
                    showSuccessAlert.value = true;
                    fetchDeviceList();
                    $('#deviceAlert').html('<div class="alert alert-success"> Data Saved Successfully </div>');
                    //alert("Saved Successfully");
                    //updateTimer(props.patientId, '1', props.moduleId);
                    //$(".form_start_time").val(response.data.form_start_time);
                    document.getElementById("devices_form").reset();
                    setTimeout(() => {
                        $('#deviceAlert').html('');
                       // medicationTime.value = document.getElementById('page_landing_times').value;
                    }, 3000);
                }
                isLoading.value = false;
            } catch (error) {
                isLoading.value = false;
                if (error.response && error.response.status === 422) {
                    formErrors.value = error.response.data.errors;
                    console.log(error.response.data.errors);
                    setTimeout(function () {
						formErrors.value = {};
                }, 3000);
                } else {
                    console.error('Error submitting form:', error);
                }
                isLoading.value = false;
            }
            // this.closeModal();
        };



        const fetchPartnerId = async () => {
            try {
                await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
                const response = await fetch(`/patients/get-activePartner`);
                if (!response.ok) {
                    throw new Error('Failed to fetch diagnosis list');
                }
                const partnerData = await response.json();
                partnersOption.value = Object.entries(partnerData).map(([id, partner_id]) => ({ id, partner_id }));

            } catch (error) {
                console.error('Error fetching diagnosis list:', error);
            }
        };

        const fetchPartnerDeviceId = async () => {
            try {
                await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
                const response = await fetch(`/patients/get-partner_devices`);
                if (!response.ok) {
                    throw new Error('Failed to fetch diagnosis list');
                }
                const partnersDeviceOptionData = await response.json();
                partnersDeviceOption.value = Object.entries(partnersDeviceOptionData).map(([id, device_name]) => ({ id, device_name }));

            } catch (error) {
                console.error('Error fetching diagnosis list:', error);
            }
        };

        let getStageID = async () => {
            try {
                const stageName = 'devices form';
                let response = await axios.get(`/get_stage_id/${props.moduleId}/${props.componentId}/${stageName}`);
                deviceStageId = response.data.stageID;
            } catch (error) {
                throw new Error('Failed to fetch Patient Data stageID');
            }
        };



        onMounted(async () => {
            fetchPartnerId();
            fetchPartnerDeviceId();
            fetchDeviceList();
            try {
                medicationTime.value = document.getElementById('page_landing_times').value;
                console.log("medication time", medicationTime);
            } catch (error) {
                console.error('Error on page load:', error);
            }
        });

        return {
            handlePartnerDevice,
            partnersDeviceOption,
            fetchPartnerDeviceId,
            fetchPartnerId,
            fetchDeviceList,
            isSaveButtonDisabled,
            selectedPartnerDeviceId,
            comments,
            selectedCode,
            loading,
            columnDefs,
            passRowData,
            partnersOption,
            codeOptions,
            selectedMedication,
            medicationTime,
            deviceStageId,
            selectedPartnerId,
            formErrors,
            goals,
            tasks,
            symptoms,
            isInitialGoalFilled,
            isInitialTaskFilled,
            isInitialSymptomFilled,
            goalsText,
            submitDeviceForm,
            isLoading,
            showSuccessAlert,
            selectedEditDiagnosId,
            editPatientDignosis,
            selectedcondition,
            startTimeInput,
        };
    }

};
</script>

<style scoped>
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
}</style>
