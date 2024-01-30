<template>
    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="card"> 
                <div class="card-body">
                    <div class="mb-4 ml-4">
                        <div class="alert alert-success" id="callwrapform-success-alert" style="display: none;">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>Call wrap-up data successfully! </strong><span id="text"></span>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-3"><h6><b>Call Notes for Review and Approval</b></h6></div>
                            <div class="col-md-3">
                                <select name="select_report" class ="custom-select show-tick mr-4"><!--  id="rpm-report" -->
                                    <option>Select Report</option>
                                    <option value="2">Daily History Report</option>
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
                    <form id="callwrapup_form" name="callwrapup_form" action="" method="post">
                        <input type="hidden" name="uid" :value="patientId"/>
                        <input type="hidden" name="patient_id" :value="patientId"/>
                        <input type="hidden" name="start_time" value="00:00:00"> 
                        <input type="hidden" name="end_time" value="00:00:00">
                        <input type="hidden" name="module_id" :value="moduleId"/>
                        <input type="hidden" name="component_id" :value="componentId"/>
                        <input type="hidden" name="stage_id" :value="callWrapUpStageId"/>
                        <input type="hidden" name="step_id" :value="callWrapUpStepId">
                        <input type="hidden" name="form_name" value="number_tracking_vitals_form">
                        <input type="hidden" name="billable" value="1">
                        <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="callWrapUpTime" />
                        <div class="row ml-3"> 
                            <div class="col-md-12 form-group">
                                <div class=" forms-element">
                                    <label class="col-md-12">EMR Monthly Summary
                                        <textarea  class="form-control" cols="90"  name="emr_monthly_summary[]" id="callwrap_up_emr_monthly_summary" ></textarea>
                                        <!-- onfocusout="saveEMR()" -->
                                    </label>
                                    <div class="invalid-feedback"></div>  
                                </div>
                            </div>
                            <div class="col-md-12" style="margin-bottom: 40px;">
                                <div class="row">
                                    <div class="col-md-3">
                                        <b><span style="margin-left: 20px; color: #69aac2;">Additional CCM Notes :</span></b>
                                    </div>
                                    <div class="col-md-1">
                                        <i id="addnotes" type="button" qno="1" class="add i-Add" style="color: rgb(44, 184, 234); font-size: 25px;float: left;"></i>
                                    </div>
                                </div>
                                <div class="row" id="additional_monthly_notes" style="margin-left: 0.05rem !important;"></div>
                            </div>
                            <div class="col-md-12 forms-element">  
                                <div class="row">
                                <div class="col-md-4">
                                    <label for="emr_entry_completed" class="checkbox checkbox-primary mr-3">
                                        <input type="checkbox" name="emr_entry_completed" id="emr_entry_completed" value="1" class="RRclass emr_entry_completed" formControlName="checkbox" />
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
                                    <div class="col-md-4">
                                        <label for="routine_response" class="checkbox checkbox-primary mr-3">
                                            <input type="checkbox" name="routine_response" id="routine_response" value="1" class="RRclass routine_response" formControlName="checkbox" />  
                                            <span>Routine Response</span>
                                            <span class="checkmark"></span>
                                        </label>
                                        <div class="col-md-12" id="routinediv">
                                            <span class="checkmark"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="urgent_emergent_response" class="checkbox checkbox-primary mr-3">
                                            <input type="checkbox" name="urgent_emergent_response" id="urgent_emergent_response" value="1" class="RRclass urgent_emergent_response" formControlName="checkbox" />  
                                            <span>Urgent/Emergent Response</span>
                                            <span class="checkmark"></span>
                                        </label>
                                        <div class="col-md-12" id="emergentdiv">
                                            <span class="checkmark"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="referral_order_support" class="checkbox checkbox-primary mr-3">
                                            <input type="checkbox" name="referral_order_support" id="referral_order_support" value="1" class="RRclass referral_order_support" formControlName="checkbox" />
                                            <span>Referral/Order Support</span>
                                            <span class="checkmark"></span>
                                        </label>
                                        <div class="col-md-12" id="referraldiv">
                                            <span class="checkmark"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="medication_support" class="checkbox checkbox-primary mr-3">
                                            <input type="checkbox" name="medication_support"  id="medication_support" value="1" class="RRclass medication_support" formControlName="checkbox" />
                                            <span>Medication Support</span>
                                            <span class="checkmark"></span>
                                        </label>
                                        <div class="col-md-12" id="medicationdiv">
                                            <span class="checkmark"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="verbal_education_review_with_patient" class="checkbox checkbox-primary mr-3">
                                            <input type="checkbox" name="verbal_education_review_with_patient"  id="verbal_education_review_with_patient" value="1" class="RRclass verbal_education_review_with_patient" formControlName="checkbox" />
                                            <span>Verbal Education/Review with Patient</span>
                                            <span class="checkmark"></span>
                                        </label>
                                        <div class="col-md-12" id="verbaldiv">
                                            <span class="checkmark"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="mailed_documents" class="checkbox checkbox-primary mr-3">
                                            <input type="checkbox" name="mailed_documents"  id="mailed_documents" value="1" class="RRclass mailed_documents" formControlName="checkbox" />
                                            <span>Mailed Documents</span>
                                            <span class="checkmark"></span>
                                        </label>
                                        <div class="col-md-12" id="maileddiv">
                                            <span class="checkmark"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="resource_support" class="checkbox checkbox-primary mr-3">
                                            <input type="checkbox" name="resource_support"  id="resource_support" value="1" class="RRclass resource_support" formControlName="checkbox" />
                                            <span>Resource Support</span>
                                            <span class="checkmark"></span>
                                        </label>
                                        <div class="col-md-12" id="resourcediv">
                                            <span class="checkmark"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="veterans_services" class="checkbox checkbox-primary mr-3">
                                            <input type="checkbox" name="veterans_services"  id="veterans_services" value="1" class="RRclass veterans_services" formControlName="checkbox" />
                                            <span>Veterans Services</span>
                                            <span class="checkmark"></span>
                                        </label>

                                        <div class="col-md-12" id="veteransdiv">
                                            <span class="checkmark"></span>
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <label for="authorized_cm_only" class="checkbox checkbox-primary mr-3">
                                            <input type="checkbox" name="authorized_cm_only"  id="authorized_cm_only" value="1" class="RRclass authorized_cm_only" formControlName="checkbox" />
                                            <span><b>Authorized CM Only:</b></span>
                                            <span class="checkmark"></span>
                                        </label>

                                        <div class="col-md-12" id="authorizeddiv">
                                            <span class="checkmark"></span>
                                        </div> 
                                    </div>
                                    <div class="col-md-4">
                                        <label for="no_additional_services_provided" class="checkbox checkbox-primary mr-3">
                                            <input type="checkbox" name="no_additional_services_provided"  id="no_additional_services_provided" value="1" class="RRclass no_additional_services_provided" formControlName="checkbox" />
                                            <span>No Additional Services Provided:</span>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                                <div id="checkboxerror" style="display:none; color:red; ">Please choose at least one response </div>
                            </div> 
                            <div class="form-row invalid-feedback"></div>
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
                    </form>
                    <div v-on:click="deleteCallWrapup(1)">testing</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {
    reactive,
    ref,
    onMounted,
    AgGridTable,
} from '../../../commonImports';
import axios from 'axios';

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

        const submitCallWrapUpFormData = () => {
            const formData = {
                uid: uid.value,
                patient_id: patient_id.value,
                start_time: start_time.value,
                end_time: end_time.value,
                module_id: module_id.value,
                component_id: component_id.value,
                stage_id: stage_id.value,
                step_id: step_id.value,
                form_name: form_name.value,
            };
            console.log("formData==>>", formData);
            // axios.post('/your-api-endpoint', formData)
            //   .then(response => {
            //     console.log('Form submitted successfully!', response.data);
            //   })
            //   .catch(error => {
            //     console.error('Error submitting form:', error);
            //   });
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
                    console.log("deleteCallWrapupResponse", deleteCallWrapupResponse);
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
            console.log(`Deleting call wrap with ID ${callWrapId}`);
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

        const exposeDeleteCallWrapup = () => {
            window.deleteCallWrapup = deleteCallWrapup;
        };

        onMounted(async () => {
            try {
                fetchCallWrapUpList();
                exposeDeleteCallWrapup();
                getStageID();
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
            
        };
    }
}
</script>






