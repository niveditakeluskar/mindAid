<template>
    <div class="row">
        <div class="col-lg-12 mb-3">
            <form id="callwrapup_form" name="callwrapup_form" @submit.prevent="submitCallWrapUpFormData">
                <div class="card"> 
                    <div class="card-body">
                        <div class="mb-4 ml-4">
                            <div class="alert alert-success" id="callwrapform-success-alert" :style="{ display: showCallWrapUpAlert ? 'block' : 'none' }">
                                <button type="button" class="close" data-dismiss="alert">x</button>
                                <strong>Call wrap-up data successfully! </strong><span id="text"></span>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3"><h6><b>Call Notes for Review and Approval</b></h6></div>
                                <div class="col-md-3" v-if="moduleId === 2"> <!-- show only for RPM-->
                                    <select name="select_report" class ="custom-select show-tick mr-4" v-model="selectedReport" @change="onRPMReportChanged()"><!--  id="rpm-report" -->
                                        <option>Select Report</option>
                                        <!-- <option value="1">Summary Report</option> -->
                                        <option value="2">Daily History Report</option>
                                        <!-- <option value="3">Alert History Report</option> -->
                                    </select>
                                </div> 
                                <div class="col-md-3">
                                    <a :href="`/ccm/monthly-monitoring/call-wrap-up-word/${patientId}`" class="btn btn-primary" target="_blank">Care Manager Notes Word Format</a>   <!-- Docs Care Plan -->
                                </div>
                            </div>
                        </div>
                        <div class="row m-1">
                            <div class="col-12"> 
                                <AgGridTable :rowData="callWrapRowData" :columnDefs="callWrapColumnDefs"/>
                            </div>
                        </div>
                        <input type="hidden" name="uid" :value="patientId"/>
                        <input type="hidden" name="patient_id" :value="patientId"/>
                        <input type="hidden" name="start_time" value="00:00:00"> 
                        <input type="hidden" name="end_time" value="00:00:00">
                        <input type="hidden" name="module_id" :value="moduleId"/>
                        <input type="hidden" name="component_id" :value="componentId"/>
                        <input type="hidden" name="stage_id" :value="callWrapUpStageId"/>
                        <input type="hidden" name="step_id" :value="callWrapUpStepId">
                        <input type="hidden" name="form_name" value="callwrapup_form">
                        <input type="hidden" name="billable" value="1">
                        <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="callWrapUpTime" />
                        <div class="row ml-3"> 
                            <div class="col-md-12 form-group">
                                <div class=" forms-element">
                                    <label class="col-md-12">EMR Monthly Summary
                                        <textarea  class="form-control" cols="90"  name="emr_monthly_summary[]" id="callwrap_up_emr_monthly_summary" 
                                        @blur="saveEMRNotes"  v-model="emr_monthly_summary" ></textarea>
                                    </label>
                                    <div class="invalid-feedback" v-if="formErrors && formErrors['emr_monthly_summary.0']" style="display: block;">{{ formErrors['emr_monthly_summary.0'][0] }}</div>
                                </div>
                            </div>
                            <div class="col-md-12" style="margin-bottom: 40px;">
                                <div class="row">
                                    <div class="col-md-3">
                                        <b><span style="margin-left: 20px; color: #69aac2;">Additional CCM Notes :</span></b>
                                    </div>
                                    <div class="col-md-1">
                                        <i @click="addNewNotesRow" id="addnotes" type="button" qno="1" class="add i-Add" style="color: rgb(44, 184, 234); font-size: 25px;float: left;"></i>
                                    </div>
                                </div>
                                <div class="row" id="additional_monthly_notes" style="margin-left: 0.05rem !important;">
                                    <div class="additionalfeilds row" style="margin-left: 0.05rem !important; margin-bottom: 0.5rem;" v-for="(notesRow, index) in notesRows" :key="index">
                                        <div class="col-md-4">
                                            <input type="date" v-model="notesRow.date" name="emr_monthly_summary_date[]" class="form-control emr_monthly_summary_date" :id="`emr_monthly_summary_date_${index}`" />
                                            <div class="invalid-feedback" v-if="formErrors && formErrors['emr_monthly_summary_date.' + index]" style="display: block;">{{ formErrors['emr_monthly_summary_date.' + index][0] }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <textarea v-model="notesRow.text" class="form-control emrsummary" cols="90" name="emr_monthly_summary[]" :id="`emr_monthly_summary_${index}`"  @blur="saveEMRNotes"></textarea>
                                            <div class="invalid-feedback" v-if="formErrors && formErrors['emr_monthly_summary.' + (index + 1)]" style="display: block;">{{ formErrors['emr_monthly_summary.' + (index + 1)][0] }}</div>
                                        </div>
                                        <div class="col-md-1" style="top: 15px;">
                                            <i @click="deleteNotesRow(index)" type="button" class="removenotes  i-Remove" style="color: #f44336; font-size: 22px;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 forms-element">  
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="emr_entry_completed" class="checkbox checkbox-primary mr-3">
                                            <input type="checkbox" name="emr_entry_completed" id="emr_entry_completed" value="1" class="RRclass emr_entry_completed"
                                             v-model="emr_monthly_summary_completed" :checked="emr_monthly_summary_completed"
                                            formControlName="checkbox" /> 
                                            <span>EMR system entry completed</span>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>  
                            <hr style="width:100%">
                            <div class="col-md-12 forms-element">  
                                <div class="row">
                                    <div class="col-md-12"><b><span style="margin-left: 20px; color: #69aac2;">Additional Services :</span></b></div>
                                    <div  v-if="groupedData && groupedData.length > 0" v-for="(group, index) in groupedData" :key="index"  class="col-md-4">
                                        <div>
                                            <label :for="`${ group.name.replace(/[\s/]/g, '_').toLowerCase() }`" class="checkbox checkbox-primary mr-3">
                                                <input type="checkbox" v-model="group.checked" :name="`${group.name.replace(/[\s/]/g, '_').toLowerCase()}`" :id="`${group.name.replace(/[\s/]/g, '_').toLowerCase()}`" value="true" class="RRclass" :class="`${group.name.replace(/[\s/]/g, '_').toLowerCase()}`" formControlName="checkbox" />  
                                                <span>{{ group.name }}</span>
                                                <span class="checkmark"></span>
                                            </label>
                                            <div v-if="group.checked" class="col-md-12" v-for="item in group.items" :key="item.id">
                                                <label :for="`${group.name.replace(/[\s/]/g, '').toLowerCase()}_${item.activity.replace(/[\s/]/g, '_').toLowerCase()}`" class="checkbox checkbox-primary mr-3">
                                                    <input type="checkbox" :name="`${group.name.replace(/[\s/]/g, '').toLowerCase()}[${item.activity.replace(/[\s/]/g, '_').toLowerCase()}]`" :id="`${group.name.replace(/[\s/]/g, '').toLowerCase()}_${item.activity.replace(/[\s/]/g, '_').toLowerCase()}`" value="true" :class="`${item.activity.replace(/[\s/]/g, '_').toLowerCase()}`" formControlName="checkbox" />
                                                    <span>{{ item.activity }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                                <span class="checkmark"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div>
                                            <label for="no_additional_services_provided" class="checkbox checkbox-primary mr-3">
                                                <input type="checkbox" name="no_additional_services_provided" id="no_additional_services_provided" class="RRclass no_additional_services_provided" formcontrolname="checkbox" value="true" v-model="noAdditionalServicesProvided">
                                                <span>No Additional Services Provided</span>
                                                <span class="checkmark"></span>
                                            </label><!---->
                                        </div>
                                    </div>
                                </div>
                                <div style="color:red;" v-if="additionalErrors">{{ additionalErrorsMsg }}</div>
                            </div> 
                            <div class="form-row invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="mc-footer">
                            <div class="row"> 
                                <div class="col-lg-12 text-right">
                                    <button type="submit" class="btn btn-primary m-1" id="submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import {
    reactive,
    ref,
    onMounted,
    watch,
    AgGridTable,
    computed,
} from '../../../commonImports';
import axios from 'axios';
import moment from 'moment';

export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number,
    },
    components: {
        AgGridTable,
    },
    setup(props) {
        const callWrapRowData = ref([]);
        const loading = ref(false);
        const callWrapUpStageId = ref(0);
        let callWrapUpTime = ref(null);
        const activityData = ref([]);
        const groupedData = ref([]);
        let formErrors = ref([]);
        let additionalErrors = ref(false);
        let additionalErrorsMsg = ref(null);
        const activities = ref([]);
        let showCallWrapUpAlert = ref(false);
        const noAdditionalServicesProvided = ref('');
        const notesRows = ref([]);
        // let patient_Emr_monthly_summary = ref([]);
        const emr_monthly_summary =ref([]);
        const emr_monthly_summary_completed =ref([]);
        let selectedReport = ref('');
        const callWrapColumnDefs = ref([
            {
                headerName: 'Seq.',
                valueGetter: 'node.rowIndex + 1',
                width: 20,
            },
            { headerName: 'Topic', field: 'topic', filter: true },
            { headerName: 'Care Manager Notes', field: 'notes', width: 100, suppressSizeToFit: true },
            { headerName: 'Action Taken', field: 'action_taken', width: 60 },
            {
                headerName: 'Action', field: 'action', width: 20,
                cellRenderer: function (params) {
                    const row = params.data;
                    return row.action;
                },
            },
        ]);

        const fetchCallWrapUpList = async () => {
            try {
                loading.value = true;
                await new Promise((resolve) => setTimeout(resolve, 2000));
                const response = await fetch(`/ccm/monthly-monitoring-call-wrap-up/${props.patientId}`);
                if (!response.ok) {
                    throw new Error('Failed to fetch call wrap up list');
                }
                loading.value = false;
                const data = await response.json();
                callWrapRowData.value = data.data;
            } catch (error) {
                console.error('Error fetching call wrap up list:', error);
                loading.value = false;
            }
        };

        const checkAdditionalServicesSelected = () => {
            const selectedServices = groupedData.value.some(group => group.checked);
            return selectedServices;
        };

        const submitCallWrapUpFormData = async () => {
            formErrors.value = {};
            additionalErrors.value = false;
            additionalErrorsMsg.value = null;
            let myForm = document.getElementById('callwrapup_form');
            let formData = new FormData(myForm);
            const formDataObject = {};
            const selectedValues = [];
            if (!noAdditionalServicesProvided.value) {
                const additionalServicesSelected = checkAdditionalServicesSelected();

                if (!additionalServicesSelected) {
                    additionalErrors.value = true;
                    additionalErrorsMsg.value = 'Please choose at least one additional service';
                    return;
                }

                const isValid = groupedData.value.every(group => {
                    if (group.checked) {
                        const hasSelectedActivities = group.items.some(item => {
                            const checkboxName = `${group.name.replace(/[\s/]/g, '').toLowerCase()}[${item.activity.replace(/[\s/]/g, '_').toLowerCase()}]`;
                            formDataObject[checkboxName] = formData.get(checkboxName);
                            const isChecked = formDataObject[checkboxName] === 'true';
                            if (isChecked) {
                                selectedValues.push(checkboxName);
                            }
                            return isChecked;
                        });

                        if (!hasSelectedActivities) {
                            additionalErrors.value = true;
                            additionalErrorsMsg.value = 'Please choose at least one sub additional service';
                        }
                        return hasSelectedActivities;
                    }
                    return true;
                });

                if (!isValid) {
                    return;
                }
            }
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const saveCallWrapUpFormResponse = await axios.post('/ccm/monthly-monitoring-call-callwrapup', formData);
                if (saveCallWrapUpFormResponse && saveCallWrapUpFormResponse.status == 200) {
                    $(".form_start_time").val(saveCallWrapUpFormResponse.data.form_start_time);
                    showCallWrapUpAlert.value = true;
                    updateTimer(props.patientId, '1', props.moduleId);
                    await fetchCallWrapUpList();
                    document.getElementById("callwrapup_form").reset();
                    setTimeout(() => {
                        showCallWrapUpAlert.value = false;
                        callWrapUpTime.value = document.getElementById('page_landing_times').value;
                    }, 3000);
                    formErrors.value = [];
                    additionalErrors.value = false;
                    groupedData.value.forEach((group) => {
                        group.checked = false;
                        group.items.forEach((item) => {
                            const checkboxName = `${group.name.replace(/[\s/]/g, '').toLowerCase()}[${item.activity.replace(/[\s/]/g, '_').toLowerCase()}]`;
                            formDataObject[checkboxName] = false;
                        });
                    });
                    notesRows.value = [];
                    populateFunction();
                }
            } catch (error) {
                if (error.response.status && error.response.status === 422) {
                    formErrors.value = error.response.data.errors;
                } else {
                    console.error('Error submitting form:', error);
                }
            }
        };

        const deleteCallWrapup = async (callWrapId) => {
            if (window.confirm("Are you sure you want to delete this notes?")) {
                const formData = {
                    id: callWrapId,
                    start_time: "00:00:00",
                    end_time: "00:00:00",
                    form_start_time: document.getElementById('page_landing_times').value,
                };
                try {
                    const deleteCallWrapupResponse = await axios.get(`/ccm/delete-callwrapup-notes/${props.patientId}`, {
                        params: {
                            id: callWrapId,
                            start_time: "00:00:00",
                            end_time: "00:00:00",
                            form_start_time: document.getElementById('page_landing_times').value,
                        }
                    });
                    // showDMEAlert.value = true;
                    updateTimer(props.patientId, '1', props.moduleId);
                    $(".form_start_time").val((deleteCallWrapupResponse.data).trim());
                    await fetchCallWrapUpList();
                    setTimeout(() => {
                        // showDMEAlert.value = false;
                        callWrapUpTime.value = document.getElementById('page_landing_times').value;
                    }, 3000);
                } catch (error) {
                    console.error('Error deletting record:', error);
                }
            }
        };

        let getStageID = async () => {
            try {
                let stageName = 'Call_Wrap_Up';
                const response = await axios.get(`/get_stage_id/${props.moduleId}/${props.componentId}/${stageName}`);
                callWrapUpStageId.value = response.data.stageID;
            } catch (error) {
                console.error('Error fetching stageID:', error);
                throw new Error('Failed to fetch stageID');
            }
        };  

        let populateFunction = async () => {
            try {
                const response = await fetch(`/ccm/populate-monthly-monitoring-data/${props.patientId}`);
                if (!response.ok) {
                    throw new Error(`Failed to fetch Patient Call wrap-up - ${response.status} ${response.statusText}`);
                }
                const data = await response.json();
                const callwrapup_form = data.callwrapup_form;
                console.log("Patient Call wrap-up data", callwrapup_form); 
                if (callwrapup_form.emr_monthly_summary != '') {
                    emr_monthly_summary.value = callwrapup_form.emr_monthly_summary[0].notes;
                }
                if (callwrapup_form.checklist_data && callwrapup_form.checklist_data['emr_entry_completed'] != null) {
                    emr_monthly_summary_completed.value = callwrapup_form.checklist_data.emr_entry_completed;
                }
                if (callwrapup_form.summary && (callwrapup_form.summary.length != null || callwrapup_form.summary.length != 0)) {
                    callwrapup_form.summary.forEach((summary) => {
                        notesRows.value.push({ date: moment(summary.record_date, 'MM-DD-YYYY').format('YYYY-MM-DD'), text: summary.notes });
                    });
                }
                if (callwrapup_form.additional_services && callwrapup_form.additional_services.length > 0) {
                    const additionalServicesData = callwrapup_form.additional_services[0].notes.trim();
                    console.log("data", additionalServicesData);
                    if (additionalServicesData == 'No Additional Services Provided') {
                        noAdditionalServicesProvided.value = true;
                    } else {
                        const additionalServicesArray = additionalServicesData.split(';').map(e => e.trim()); // Split by ';' and trim each item
                        console.log("additionalServicesArray", additionalServicesArray);
                        additionalServicesArray.forEach(service => {
                                const checkboxName = service.split(':')[0].toLowerCase().replace(/ /g, '_');
                                console.log("checkboxName", checkboxName);
                            if (checkboxName != '') {
                                $(`form[name='callwrapup_form'] #${checkboxName}`).prop('checked', true);
                                let data = service.split(':')[1].toLowerCase().trim().replace(/ /g, '_');
                                let itemData = data.split(',').forEach((activity) => { 
                                    console.log("activity", activity);
                                });
                            }
                        });
                    }
                }
            } catch (error) {
                console.error('Error fetching Call wrap-up:', error);
            }
        };

        const exposeDeleteCallWrapup = () => {
            window.deleteCallWrapup = deleteCallWrapup;
        };

        const groupActivitiesByType = async () => {
            const groups = {};
            try {
                const response = await axios.get('/ccm/monthly-monitoring-call-wrap-up-activities/activities');
                activityData.value = response.data;
                // activities.value = response.data;
                activityData.value.forEach((activity) => {
                    if (!groups[activity.activity_type]) {
                        groups[activity.activity_type] = { name: activity.activity_type, items: [] };
                    }
                    groups[activity.activity_type].items.push(activity);
                });
                // Convert the object into an array
                groupedData.value = Object.values(groups).map((group) => ({ ...group, checked: false }));
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        };

        watch(noAdditionalServicesProvided, (value) => {
            const formDataObject = reactive({});
            if (value) {
                groupedData.value.forEach((group) => {
                    group.checked = false;
                    group.items.forEach((item) => {
                        const checkboxName = `${group.name.replace(/[\s/]/g, '').toLowerCase()}[${item.activity.replace(/[\s/]/g, '_').toLowerCase()}]`;
                        formDataObject[checkboxName] = false;
                    });
                });
                additionalErrors.value = false;
                additionalErrorsMsg.value = null;
            }
        }, { immediate: true }
        );

        watch(groupedData, (newValue) => {
            const selectedServices = newValue.some(group => group.checked);
            if (selectedServices) {
                noAdditionalServicesProvided.value = false;
            }
        }, { deep: true, immediate: true }
        );

        const addNewNotesRow = () => {
            const today = new Date().toISOString().split('T')[0];
            notesRows.value.push({ date: today, text: '' });
        };

        const deleteNotesRow = (index) => {
            notesRows.value.splice(index, 1);
        };

        const onRPMReportChanged = () => {
            let report_id = selectedReport.value;
            if (report_id == 2) {
                window.open(`/reports/device-data-report/${props.patientId}`, '_blank');
            } else if (report_id == 3) {
                const currentDate = new Date();
                const currentMonth = currentDate.getMonth() + 1;
                window.open(`/rpm/alert-history/${props.patientId}/${currentMonth}`, '_blank');
            }
        };

        const saveEMRNotes = async () => {
            formErrors.value = {};
            let myForm = document.getElementById('callwrapup_form');
            let formData = new FormData(myForm);
            const formDataObject = {};
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const saveEMRResponse = await axios.post('/ccm/saveEmrSummary', formData);
                if (saveEMRResponse && saveEMRResponse.status == 200) {
                    $(".form_start_time").val(saveEMRResponse.data.form_start_time);
                    updateTimer(props.patientId, '1', props.moduleId);
                    await fetchCallWrapUpList();
                    setTimeout(() => {
                        showCallWrapUpAlert.value = false;
                        callWrapUpTime.value = document.getElementById('page_landing_times').value;
                    }, 3000);
                    formErrors.value = [];
                    additionalErrors.value = false;
                }
            } catch (error) {
                if (error.response.status && error.response.status === 422) {
                    formErrors.value = error.response.data.errors;
                } else {
                    console.error('Error submitting form:', error);
                }
            }
        };

        onMounted(async () => {
            try {
                fetchCallWrapUpList();
                exposeDeleteCallWrapup();
                getStageID();
                populateFunction();
                groupActivitiesByType();
                callWrapUpTime.value = document.getElementById('page_landing_times').value;
            } catch (error) {
                console.error('Error on page load:', error);
            }
        });

        return {
            loading,
            callWrapColumnDefs,
            callWrapRowData,
            callWrapUpStageId,
            callWrapUpTime,
            fetchCallWrapUpList,
            submitCallWrapUpFormData,
            deleteCallWrapup,
            groupedData,
            checkAdditionalServicesSelected,
            formErrors,
            additionalErrors,
            additionalErrorsMsg,
            showCallWrapUpAlert,
            noAdditionalServicesProvided,
            notesRows,
            addNewNotesRow,
            deleteNotesRow,
            onRPMReportChanged,
            selectedReport,
            saveEMRNotes,
            emr_monthly_summary,
            emr_monthly_summary_completed,
        };
    }
}
</script>






