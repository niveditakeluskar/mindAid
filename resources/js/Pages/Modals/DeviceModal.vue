<template>
    <div class="modal fade" :class="{ 'show': isOpen }" >
	<div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Devices</h4>
                <button type="button" class="close" @click="closeModal">×</button>
            </div>
            <div class="modal-body" style="padding-top:10px;">
                <loading-spinner :isLoading="isLoading"></loading-spinner>
                <div id="deviceModalAlert"></div>
                <form name="devices_form" id="devices_form" @submit.prevent="submitDeviceForm">
                    <input type="hidden" name="patient_id" :value="patientId" />
                    <input type="hidden" name="uid" :value="patientId">
                    <input type="hidden" name="start_time" :value="'00:00:00'">
                    <input type="hidden" name="end_time" :value="'00:00:00'">
                    <input type="hidden" name="module_id" :value="moduleId" />
                    <input type="hidden" name="component_id" :value="componentId" />
                    <input type="hidden" name="stage_id" :value="deviceStageId" />
                    <input type="hidden" name="form_name" value="devices_form">
                    <input type="hidden" name="idd" id="idd" v-model="selectedEditDeviceId">
                    <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time"
                        :value="medicationTime" v-model="medicationTime">
                    <div class="row">
                        <div id="devices_success"></div>
                        <div class="col-md-12 form-group">
                            <label>Devices ID<span class='error'>*</span></label>
                            <input type="text" class="form-control" name="device_id" id="device_id" v-model="selectedDeviceCode">
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
                            <select class="custom-select show-tick" name="partner_devices_id" v-model="selectedPartnerDeviceId">
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
    </div>
</template>

<script>
import {
    ref,
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
            document.body.classList.add('modal-open');
            this.medicationTime = document.getElementById('page_landing_times').value;
        },
        closeModal() {
            this.isOpen = false;
            document.body.classList.remove('modal-open');
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
        const selectedDeviceCode =ref('');
        const selectedCode = ref('');
        const passRowData = ref([]); // Initialize rowData as an empty array
        const loading = ref(false);
        const medicationTime = ref('');
        const deviceStageId = ref(0);

        let codeOptions = ref([]);
        let selectedMedication = ref('');
        const selectedEditDeviceId = ref('');
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
        editLink.dataset.id = params.data.id;
        editLink.dataset.toggle = 'tooltip';
        editLink.title = 'Edit';
        editLink.classList.add('editDevicesdata');
        const editIcon = document.createElement('i');
        editIcon.classList.add('editform', 'i-Pen-4');
        editLink.appendChild(editIcon);
        linkContainer.appendChild(editLink);
        // Add event listener to edit link
        editLink.addEventListener('click', (event) => {
            const id = event.target.dataset.id;
            editDeviceId(editLink.dataset.id);
        });
        // Status Button
        const statusLink = document.createElement('a');
        statusLink.href = 'javascript:void(0)';
        statusLink.dataset.id = params.data.id;
        statusLink.classList.add(params.data.status === 1 ? 'change_device_status_active' : 'change_device_status_deactive');
        statusLink.id = params.data.status === 1 ? 'active' : 'inactive';
        statusLink.title = params.data.status === 1 ? 'Active' : 'Inactive';
        const statusIcon = document.createElement('i');
        statusIcon.classList.add(params.data.status === 1 ? 'i-Yess' : 'i-Closee', 'i-Yes');
        statusLink.appendChild(statusIcon);
        linkContainer.appendChild(statusLink);
        // Add event listener to status link
        statusLink.addEventListener('click', (event) => {
            const id = event.target.dataset.id;
            changeStatus(statusLink.dataset.id);
        });
        return linkContainer;
    },
}



        ]);

      /*   const changeStatus = (id) => {
			const statusId = id;
			if (confirm('Are you sure you want to Activate this Device')) {
				fetch('/patients/delete-device/', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded',
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
					},
					body: new URLSearchParams({
						statusId
					})
				})
					.then(response => {
						if (!response.ok) {
							throw new Error(`HTTP error! Status: ${response.status}`);
						}
						return response.json();
					})
					.then(responseData => {
						document.getElementById('deviceModalAlert').innerHTML = '<div class="alert alert-success"> Data Saved Successfully </div>';
						setTimeout(() => {
							document.getElementById('deviceModalAlert').innerHTML = '';
						}, 3000);
					})
					.catch(error => {
						console.error('Error:', error);
					});
			} else {
				return false;
			}
		}; */
    
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
        const partnerid = selectedPartnerId.value; // Replace with the actual partner ID
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

        const editDeviceId = async (id) => {     
            isLoading.value = true;
            try {
                selectedEditDeviceId.value = id;
                const response = await fetch(`/patients/ajax/populatedevice/${id}`);
                if (!response.ok) {
                    throw new Error('Failed to fetch followup task list');
                }
                const data = await response.json();
                // console.log(data.devices_form[0],"device edit");
                // console.log(selectedEditDeviceId.value,"device edit ID");
                selectedDeviceCode.value = data.devices_form[0].device_code;
                selectedPartnerId.value = data.devices_form[0].partner_id;
                selectedPartnerDeviceId.value = data.devices_form[0].pdevice_id;
                isLoading.value = false;
                isSaveButtonDisabled.value = false;
            } catch (error) {
                console.error('Error fetching followup task list:', error);
                isLoading.value = false;
            }
        };

        const changeStatus = async (id) => {
            try {
                if (confirm('Are you sure you want to Activate this Device')) {
                    const response = await fetch(`/patients/delete-device/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: new URLSearchParams({ 
                            id: id
                        })
                    });

                    if (!response.ok) {
                        throw new Error(`Failed to change status - ${response.status} ${response.statusText}`);
                    }
                    if (response && response.status == 200) {
                        showSuccessAlert.value = true;
                        fetchDeviceList();
                        $('#deviceModalAlert').html('<div class="alert alert-success"> Request Processed Successfully </div>');
                        document.getElementById("devices_form").reset();
                        setTimeout(() => {
                            $('#deviceModalAlert').html('');
                        // medicationTime.value = document.getElementById('page_landing_times').value;
                        }, 3000);
                    }
                    // console.log(responseData);
                } else {
                    return false;
                }
            } catch (error) {
                console.error('Error changing status:', error.message);
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
                    $('#deviceModalAlert').html('<div class="alert alert-success"> Data Saved Successfully </div>');
                    //alert("Saved Successfully");
                    //updateTimer(props.patientId, '1', props.moduleId);
                    //$(".form_start_time").val(response.data.form_start_time);
                    document.getElementById("devices_form").reset();
                    setTimeout(() => {
                        $('#deviceModalAlert').html('');
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
            } catch (error) {
                console.error('Error on page load:', error);
            }
        });

        return {
            getStageID,
            handlePartnerDevice,
            partnersDeviceOption,
            fetchPartnerDeviceId,
            fetchPartnerId,
            fetchDeviceList,
            isSaveButtonDisabled,
            selectedPartnerDeviceId,
            selectedDeviceCode,
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
            selectedEditDeviceId,
            editDeviceId,
            selectedcondition,
            startTimeInput,
        };
    }

};
</script>

