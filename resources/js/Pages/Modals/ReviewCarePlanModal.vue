<template>
    <div class="overlay" :class="{ 'open': isOpen }" @click="closeModal"></div>
    <div class="modal fade" :class="{ 'open': isOpen }"> <!-- :style="{ display: isOpen ? 'block' : 'none' }"> -->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create / Modify Care Plan</h4>
                <button type="button" class="close" data-dismiss="modal" @click="closeModal">×</button>
            </div>
            <div class="modal-body" style="padding-top:10px;">
                <loading-spinner :isLoading="isLoading"></loading-spinner>
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group ccmPatientData" id="diagnosis-codes" name="diagnosis_codes" style="">
                                    <div class="row mb-4" id="diagnosis">
                                        <div class="col-md-12 mb-4">
                                            <div class="success" id="success"></div>
                                            <div class="card-body diagnosis-Data">
                                                <div class="row mb-4">
                                                    <div class="col-md-12 mb-4">
                                                        <ul class="nav nav-pills" id="myPillTab" role="tablist">
                                                            <li class="nav-item">
                                                                <!-- <a class="nav-link active" id="medication-icon-pill" data-toggle="pill" href="#medication" role="tab" aria-controls="medication" aria-selected="true"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i>MEDICATION</a> -->
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content" id="myPillTabContent">
                                                            <div class="tab-pane fade show active" id="diagnosis"
                                                                role="tabpanel" aria-labelledby="diagnosis-icon-pill">
                                                                <div class="card mb-4">
                                                                    <div id="reviewCareAlert"></div>
                                                                    <form id="care_plan_form" name="care_plan_form"
                                                                        @submit.prevent="submitCarePlanForm">
                                                                      
                                                                        <div class="alert alert-danger"
                                                                            v-if="showSuccessAlert">
                                                                            <button type="button" class="close"
                                                                                data-dismiss="alert">x</button>
                                                                            <strong> Fill All Mandatory Fields!
                                                                            </strong><span id="text"></span>
                                                                        </div>

                                                                        <div class="form-row col-md-12">
                                                                            <input type="hidden" name="uid"
                                                                                :value="patientId" />
                                                                            <input type="hidden" name="patient_id"
                                                                                :value="patientId" />
                                                                            <input type="hidden" name="start_time"
                                                                                ref="startTimeInput" value="00:00:00">
                                                                            <input type="hidden" name="end_time"
                                                                                value="00:00:00">
                                                                            <input type="hidden" name="module_id"
                                                                                :value="moduleId" />
                                                                            <input type="hidden" name="component_id"
                                                                                :value="componentId" />
                                                                            <input type="hidden" name="stage_id"
                                                                                value="11" />
                                                                            <input type="hidden" name="step_id"
                                                                                :value="stepID">
                                                                            <input type="hidden" name="form_name"
                                                                                value="care_plan_form">
                                                                            <input type="hidden" name="diagnosis_id"
                                                                                id="diagnosis_id"
                                                                                v-model="selectedDiagnosisId">
                                                                            <input type="hidden" name="hiddenenablebutton"
                                                                                id="hiddenenablebutton">
                                                                            <input type="hidden" name="editdiagnoid"
                                                                                v-model="selectedEditDiagnosId">
                                                                            <input type="hidden" id="cpd_finalize"
                                                                                value="1">
                                                                            <input type="hidden" name="billable" value="1">
                                                                            <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" 
                                                                            :value="reviewCarePlanTimer">
                                                                            <div class="row col-md-12">
                                                                                <div class="col-md-6"><label>Condition
                                                                                        <span
                                                                                            class="error">*</span>:</label>
                                                                                    <input type="hidden" name="condition"
                                                                                        v-model="selectedcondition">
                                                                                    <select id="diagnosis_condition"
                                                                                        class="custom-select show-tick"
                                                                                        name="diagnosis"
                                                                                        v-model="selectedDiagnosis"
                                                                                        @change="handleDiagnosisChange">
                                                                                        <option
                                                                                            v-for="item in diagnosisOptions"
                                                                                            :key="item.id" :value="item.id">
                                                                                            {{ item.description }}
                                                                                        </option>
                                                                                    </select>
                                                                                    <div class="invalid-feedback"
                                                                                        v-if="formErrors.diagnosis"
                                                                                        style="display: block;">{{
                                                                                            formErrors.diagnosis[0] }}
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-2">
                                                                                    <label><span class="error"></span>&nbsp;
                                                                                    </label>
<button type="button" class="col-md-12 btn btn-primary" @click="() => changeCondition('care_plan_form')"  id="render_plan_form"> Display Care Plan</button>
                                                                                </div>

                                                                                <div class="col-md-4 emaillist">
                                                                                    <label>Code<span class="error">*</span>
                                                                                        :</label>
                                                                                    <input type="hidden" id="codeid">
                                                                                    <select id="diagnosis_code"
                                                                                        class="custom-select show-tick"
                                                                                        name="code" v-model="selectedCode"
                                                                                        @change="handleCodeAlert">
                                                                                        <option value="">Select Code
                                                                                        </option>
                                                                                        <option v-for="code in codeOptions" :key="code" :value="code">{{ code }}</option>

                                                                                    </select>
                                                                                    <div class="invalid-feedback"
                                                                                        v-if="formErrors.code"
                                                                                        style="display: block;">{{
                                                                                            formErrors.code[0] }}</div>
                                                                                </div>

                                                                                <div class="col-md-3 otherlist"
                                                                                    id="otherlist" style="display:none">
                                                                                    <label>New Code <span
                                                                                            class="error">*</span>:</label>
                                                                                    <input id="new_code"
                                                                                        class="form-control" name="new_code"
                                                                                        type="text" value=""
                                                                                        autocomplete="off">
                                                                                    <div class="invalid-feedback"></div>
                                                                                </div>


                                                                                <div>
                                                                                    <button type="button"
                                                                                        class="btn btn-primary mt-2 ml-3"
                                                                                        id="enable_diagnosis_button"
                                                                                        onclick="carePlanDevelopment.enableDiagnosisbutton(this)"
                                                                                        style="display:none">Enable
                                                                                        Editing</button>
                                                                                    <button type="button"
                                                                                        class="btn btn-primary mt-2 ml-3"
                                                                                        id="disable_diagnosis_button"
                                                                                        onclick="carePlanDevelopment.disableDiagnosisbutton(this)"
                                                                                        style="display:none">Disable
                                                                                        Editing</button>
                                                                                </div>

                                                                                <div class="col-md-12">
                                                                                    <label for="Template">Symptoms<span
                                                                                            class="error">*</span>
                                                                                        :</label>
                                                                                    <div v-for="(symptom, index) in symptoms"
                                                                                        :key="index" class="goal-container">
                                                                                        <input :key="index"
                                                                                            v-model="symptoms[index]"
                                                                                            placeholder="Enter Symptoms"
                                                                                            :id="'symptoms_' + index"
                                                                                            class="form-control"
                                                                                            name="symptoms[]" type="text"
                                                                                            autocomplete="off"
                                                                                            :required="index === 0 ? !isInitialSymptomFilled : false">
                                                                                        <i class="col-md-1 remove-icons i-Remove float-right mb-3"
                                                                                            @click="removeSymptoms(index)"
                                                                                            :id="'remove_symptoms_' + index"
                                                                                            title="Remove Symptoms"></i>
                                                                                    </div>
                                                                                    <div class="invalid-feedback"></div>
                                                                                    <i class="plus-icons i-Add"
                                                                                        id="append_symptoms_icons"
                                                                                        @click="additionalsymptoms()"
                                                                                        title="Add symptons"></i>

                                                                                    <div class="col-md-10 mb-3"
                                                                                        id="append_symptoms"></div>
                                                                                </div>

                                                                                <div class="col-md-12">
                                                                                    <label for="contactNumber">Goals<span
                                                                                            class="error">*</span>
                                                                                        :</label>
                                                                                    <div v-for="(goal, index) in goals"
                                                                                        :key="index" class="goal-container">
                                                                                        <input :key="index"
                                                                                            v-model="goals[index]"
                                                                                            placeholder="Enter Goal"
                                                                                            :id="'goals_' + index"
                                                                                            class="form-control"
                                                                                            name="goals[]" type="text"
                                                                                            autocomplete="off"
                                                                                            :required="index === 0 ? !isInitialGoalFilled : false">
                                                                                        <i class="col-md-1 remove-icons i-Remove float-right mb-3"
                                                                                            @click="removeGoal(index)"
                                                                                            :id="'remove_goal_' + index"
                                                                                            title="Remove Goal"></i>
                                                                                    </div>
                                                                                    <i class="plus-icons i-Add"
                                                                                        id="append_goals_icons"
                                                                                        @click="additionalgoals()"
                                                                                        title="Add goals"></i>
                                                                                    <div class="invalid-feedback"></div>
                                                                                    <div class="col-md-10 mb-3"
                                                                                        id="append_goals"></div>
                                                                                </div>

                                                                                <div class="col-md-12">
                                                                                    <label for="emailTo">Tasks<span
                                                                                            class="error">*</span>
                                                                                        :</label>
                                                                                    <div v-for="(task, index) in tasks"
                                                                                        :key="index" class="goal-container">
                                                                                        <textarea :key="index"
                                                                                            v-model="tasks[index]"
                                                                                            placeholder="Enter tasks"
                                                                                            :id="'tasks_' + index"
                                                                                            class="form-control"
                                                                                            name="tasks[]" type="text"
                                                                                            style="height:50px;overflow-y:hidden;"
                                                                                            :required="index === 0 ? !isInitialTaskFilled : false"></textarea>
                                                                                        <i class="col-md-1 remove-icons i-Remove float-right mb-3"
                                                                                            @click="removeTasks(index)"
                                                                                            :id="'remove_tasks_' + index"
                                                                                            title="Remove Task"></i>
                                                                                    </div>
                                                                                    <div class="invalid-feedback"></div>
                                                                                    <i class="plus-icons i-Add"
                                                                                        id="append_tasks_icons"
                                                                                        @click="additionaltasks()"
                                                                                        title="Add task"></i>
                                                                                    <div class="col-md-10 mb-3"
                                                                                        id="append_tasks"></div>
                                                                                </div>


                                                                                <div class="col-md-12">
                                                                                    <label for="Template">Comment:</label>
                                                                                    <textarea
                                                                                        class="forms-element form-control"
                                                                                        id="diagnosis_comments"
                                                                                        v-model="comments" name="comments"
                                                                                        style="height:50px;overflow-y:hidden;"></textarea>
                                                                                    <div class="invalid-feedback"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <div class="alert alert-danger"
                                                                            style="display: none;">
                                                                            <button type="button" class="close"
                                                                                data-dismiss="alert">x</button>
                                                                            <strong> Fill All Mandatory Fields!
                                                                            </strong><span id="text"></span>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <button type="submit"
                                                                                class="btn btn-primary float-right save_care_plan_form"
                                                                                id="save_care_plan_form"
                                                                                :disabled="isSaveButtonDisabled">Review/Save</button>
                                                                        </div>
                                                                      
                                                                    
                                                                    </form>
                                                                    <div id="reviewCareAlert"></div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="separator-breadcrumb border-top"></div>
                <div class="row">
                    <div class="col-md-12">
                        <AgGridTable :rowData="passRowData" :columnDefs="columnDefs" />
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
    components: {
        AgGridTable,
    },
 
    setup(props) {
        const startTimeInput = ref(null);
        const isSaveButtonDisabled = ref(true);
        const selectedDiagnosisId = ref('');
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
        const selectedDiagnosis = ref('');
        const selectedCode = ref('');
        const passRowData = ref([]); // Initialize rowData as an empty array
        const loading = ref(false);
        let diagnosisOptions = ref([]);
        let codeOptions = ref([]);
        let selectedMedication = ref('');
        const selectedEditDiagnosId = ref('');
        const selectedcondition = ref('');
        const isOpen = ref(false);

        const openModal = () => {
            isOpen.value = true;
            fetchCarePlanFormList();
            fetchDiagnosis();
            fetchCode();
            getStageID();
            additionalgoals();
            additionalsymptoms();
            additionaltasks();
           const reviewCaretimerElement = document.getElementById('page_landing_times').value;
                if (reviewCaretimerElement !== null) {
                    reviewCarePlanTimer.value = reviewCaretimerElement.value;
                    }
        };

        const closeModal = () => {
            isOpen.value = false;
        };

        let columnDefs = ref([
            {
                headerName: 'Sr. No.',
                valueGetter: 'node.rowIndex + 1',
            },
            { headerName: 'Code', field: 'code' },
            { headerName: 'Condition', field: 'condition' },
            { headerName: 'Last Modified By', field: 'users.f_name' },
            { headerName: 'Last Modified On', field: 'updated_at' },
            { headerName: 'Last Review Date', field: 'updated_at' },
            { headerName: 'Last Updated On', field: 'update_date' },
            {
                headerName: 'Action',
                field: 'action',
                cellRenderer: (params) => {
                    const link = document.createElement('div');
                    const editIcon = document.createElement('i');
                    const deleteIcon = document.createElement('i');

                    const { data } = params;

                    let editIconColor = 'black';
                    let deleteIconColor = 'red';

                    if (data.iconcolor === 'green') {
                        editIconColor = 'green';
                    } else if (data.iconcolor === 'yellow') {
                        editIconColor = 'yellow';
                    }

                    editIcon.classList.add('text-20', 'i-Closee', 'i-Data-Yes');
                    editIcon.style.color = editIconColor;

                    const space = document.createTextNode(' '); // Add space between icons

                    deleteIcon.classList.add('text-20', 'i-Close');
                    deleteIcon.style.color = deleteIconColor;

                    link.appendChild(editIcon);
                    link.appendChild(space);
                    link.appendChild(deleteIcon);

                    link.classList.add('ActiveDeactiveClass');
                    link.style.cursor = 'pointer';

                    link.addEventListener('click', (event) => {
                        if (event.target === editIcon) {
                            editPatientDignosis(data.id, event.target);
                        } else if (event.target === deleteIcon) {
                            deletePatientDignosis(data.id, event.target);
                        }
                    });

                    return link;
                },
            }

        ]);


        let reviewCarePlanTimer = ref(null);
        let reviewCarePlanStageId = ref(0);
        let stepID = ref(0);

        const editPatientDignosis = async (id) => {
            clearGoals();
            isLoading.value = true;
            try {
                selectedEditDiagnosId.value = id;
                //diagnosisOptions
                const response = await fetch(`/ccm/diagnosis-select/${id}/${props.patientId}`);
                if (!response.ok) {
                    throw new Error('Failed to fetch followup task list');
                }
                const data = await response.json();
                const carePlanData = data.care_plan_form.static; // Adjust this based on your actual data structure
                if (carePlanData && carePlanData.goals) {
                    goals.value = JSON.parse(carePlanData.goals); // Parse the JSON string to an array
                }
                selectedDiagnosisId.value = carePlanData.diagnosis;
                selectedDiagnosis.value = carePlanData.diagnosis;
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
                console.error('Error fetching CPD list:', error);
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
            const form_start_time = document.querySelector('input[name="timearr[form_start_time]"]').value;
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
                    clearGoals();
                    nextTick(() => {
                additionalgoals();
                additionalsymptoms();
                additionaltasks();
                isSaveButtonDisabled.value = false;
            });
                    $('#reviewCareAlert').html('<div class="alert alert-success">Deleted Successfully</div>');
                    fetchCarePlanFormList();
                    updateTimer(props.patientId, '1', props.moduleId);
                    document.querySelector('.form_start_time').value = responseData.form_start_time;
                } catch (error) {
                    console.error('Error deleting care plan:', error.message);
                }
            }
        };


        const fetchCarePlanFormList = async () => {
            try {
                loading.value = true;
                //await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
                const response = await fetch(`/ccm/care-plan-development-diagnosis-diagnosislist/${props.patientId}`);
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

        const submitCarePlanForm = async () => {
            isLoading.value = true;
            let myForm = document.getElementById('care_plan_form');
            let formData = new FormData(myForm);
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const response = await axios.post('/ccm/care-plan-development-diagnosis-save', formData);
                if (response && response.status == 200) {
                    clearGoals();
                    nextTick(() => {
                additionalgoals();
                additionalsymptoms();
                additionaltasks();
                isSaveButtonDisabled.value = false;
            });
                    selectedCode.value = '';
            selectedDiagnosis.value = '';
            comments.value = '';
                    $('#reviewCareAlert').html('<div class="alert alert-success"> Data Saved Successfully </div>');
                    document.getElementById("care_plan_form").reset();
                    fetchCarePlanFormList();
                    updateTimer(props.patientId, '1', props.moduleId);
                    $(".form_start_time").val(response.data.form_start_time);
                    reviewCarePlanTimer.value = document.getElementById('page_landing_times').value;
                    setTimeout(() => {
                        $('#reviewCareAlert').html('');
                    }, 3000);
                }
                isLoading.value = false;
            } catch (error) {
                isLoading.value = false;
                if (error.response && error.response.status === 422) {
                    formErrors.value = error.response.data.errors;
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

        const handleDiagnosisChange = () => {
            clearGoals();
            nextTick(() => {
                additionalgoals();
                additionalsymptoms();
                additionaltasks();
                isSaveButtonDisabled.value = false;
            });
        };

        const handleCodeAlert = () => {
            alert("Are you sure you want to change the code?");
            if (selectedDiagnosis.value === '') {
                alert('please selecte condition!');
            };
        }

        let fetchDiagnosis = async () => {
            try {
                await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
                const response = await fetch(`/ccm/diagnosis-conditions`);
                if (!response.ok) {
                    throw new Error('Failed to fetch diagnosis list');
                }
                const diagnosisData = await response.json();
                const diagnosisArray = Object.entries(diagnosisData).map(([id, description]) => ({ id, description }));
                diagnosisOptions.value = diagnosisArray;
            } catch (error) {
                console.error('Error fetching diagnosis list:', error);
            }
        };

       const getCodeData = async()=>{
        try {
            const response = await fetch(`/ccm/get_diagnosis_all_codes/${selectedDiagnosis.value}/get_diagnosis_all_codes`); 
				if(!response.ok){ 
						throw new Error(`Failed to fetch Patient Preaparation - ${response.status} ${response.statusText}`);
				}
                const codeData = await response.json();
                codeOptions.value = codeData.map(item => item.code);

        } catch (error) {
            console.error('Error fetching Code:', error); // Log specific error message
        }
       };

        let fetchCode = async () => {
            try {
                await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
                const response = await fetch(`/ccm/activediagnosis-code`);
                if (!response.ok) {
                    throw new Error('Failed to fetch code list');
                }
                const codeData = await response.json();
                const codeOptionsArray = Object.entries(codeData).map(([key, value]) => ({
            value: key,
            code: value
        }));

            if (Array.isArray(codeOptionsArray)) {
            codeOptions.value = codeOptionsArray.map(item => item.code); 
                 }
            } catch (error) {
                console.error('Error fetching code list:', error);
            }
        };

        let getStageID = async () => {
            try {
                let medicationSageName = 'Preparation';
                let response = await axios.get(`/get_stage_id/${props.moduleId}/${props.componentId}/${medicationSageName}`);
                reviewCarePlanStageId = response.data.stageID;
                getStepID(reviewCarePlanStageId);
            } catch (error) {
                throw new Error('Failed to fetch Patient Data stageID');
            }
        };

        let getStepID = async (sid) => {
            try {
                let stepname = 'Care Plan';
                let response = await axios.get(`/get_step_id/${props.moduleId}/${props.componentId}/${sid}/${stepname}`);
                stepID = response.data.stepID;
            } catch (error) {
                throw new Error('Failed to fetch stageID');
            }
        };

        const getDiagnosisIdfromPatientdiagnosisid = (editid, condition_name, code, formName, patientId) => {
            if (!editid) {
                return;
            }
            axios({
                method: 'GET',
                url: `/org/ajax/diagnosis/${editid}/${patientId}/${condition_name}/${code}/editpatientdiagnosisId`,
            }).then(response => {

                if (response.data > 0) {
                    // Your code for when count is greater than 0
                    $("form[name='" + formName + "'] #hiddenenablebutton").val(0);
                    $("form[name='" + formName + "'] #symptoms_0").prop("disabled", true);
                    $("form[name='" + formName + "'] #goals_0").prop("disabled", true);
                    $("form[name='" + formName + "'] #tasks_0").prop("disabled", true);
                    $("form[name='" + formName + "']  .symptoms ").prop("disabled", true);
                    $("form[name='" + formName + "']  .goals ").prop("disabled", true);
                    $("form[name='" + formName + "']  .tasks  ").prop("disabled", true);
                    $("form[name='" + formName + "']  #append_symptoms_icons  ").hide();
                    $("form[name='" + formName + "']  #append_goals_icons  ").hide();
                    $("form[name='" + formName + "']  #append_tasks_icons  ").hide();
                    $("form[name='" + formName + "']  .removegoals  ").hide();
                    $("form[name='" + formName + "']  .removesymptoms  ").hide();
                    $("form[name='" + formName + "']  .removetasks  ").hide();
                } else {
                    // Your code for when count is 0
                    $("form[name='" + formName + "'] #hiddenenablebutton").val(1);
                    $("form[name='" + formName + "'] #symptoms_0").prop("disabled", false);
                    $("form[name='" + formName + "'] #symptoms_0").prop("disabled", false);
                    $("form[name='" + formName + "'] #goals_0").prop("disabled", false);
                    $("form[name='" + formName + "'] #tasks_0").prop("disabled", false);
                    $("form[name='" + formName + "']  .symptoms ").prop("disabled", false);
                    $("form[name='" + formName + "']  .goals ").prop("disabled", false);
                    $("form[name='" + formName + "']  .tasks  ").prop("disabled", false);
                    $("form[name='" + formName + "']  #append_symptoms_icons  ").show();
                    $("form[name='" + formName + "']  #append_goals_icons  ").show();
                    $("form[name='" + formName + "']  #append_tasks_icons  ").show();
                    $("form[name='" + formName + "']  .removegoals  ").show();
                    $("form[name='" + formName + "']  .removesymptoms  ").show();
                    $("form[name='" + formName + "']  .removetasks  ").show();
                }
            }).catch(error => {
                console.error(error, error.response);
            });
        };

        let inc_tasks = 0;
        let inc_symptoms = 0;
        let inc_goals = 0;

        const additionalgoals = () => {
            if (goals.value.length > 0 && goals.value[0].trim() !== '') {
                isInitialGoalFilled.value = true;
            }
            goals.value.push('');
        };
        const removeGoal = (index) => {
            goals.value.splice(index, 1);
        };
        const clearGoals = () => {

            goals.value = [];
            tasks.value = [];
            symptoms.value = [];
        };

        watchEffect(() => {
            // Update goalsText whenever goals array changes
            goals.value = goals.value.filter((goal) => goal.trim() !== '');
            symptoms.value = symptoms.value.filter((symptom) => symptom.trim() !== '');
            tasks.value = tasks.value.filter((task) => task.trim() !== '');
        });

        const additionalsymptoms = () => {
            if (symptoms.value.length > 0 && symptoms.value[0].trim() !== '') {
                isInitialSymptomFilled.value = true;
            }
            symptoms.value.push('');
        }
        const removeSymptoms = (index) => {
            symptoms.value.splice(index, 1);
        };
        const clearSymptoms = () => {
            symptoms.value = [];
        };

        const additionaltasks = () => {
            if (tasks.value.length > 0 && tasks.value[0].trim() !== '') {
                isInitialTaskFilled.value = true;
            }
            tasks.value.push('');
        };

        const removeTasks = (index) => {
            tasks.value.splice(index, 1);
        };
        const clearTasks = () => {
            tasks.value = [];
        };

        const changeCondition = (formName) => {
            isLoading.value = true;
            // Ensure formName is a string
            if (typeof formName !== 'string') {
                console.error('Invalid formName:', formName);
                isLoading.value = false;
                return;
            }
            $("form[name='" + formName + "'] #editdiagnoid").val();
            var editid = $("form[name='" + formName + "'] #editdiagnoid").val();
            $("form[name='diagnosis_code_form'] #editdiagnoid").val(editid);
            $("form[name='care_plan_form'] #editdiagnoid").val(editid); // $("form[name='" + formName + "'] #enable_diagnosis_button").hide();
            // $("form[name='" + formName + "'] #disable_diagnosis_button").hide();
            let currentPatientId = props.patientId;
            var id = selectedDiagnosis.value;
            var condition_name = $("form[name='" + formName + "'] #diagnosis_condition option:selected").text();
            var code = $("form[name='" + formName + "'] #diagnosis_code").val();
            getCodeData();
            getDiagnosisIdfromPatientdiagnosisid(editid, condition_name, code, formName, currentPatientId);
            $("form[name='" + formName + "'] input[name='condition']").val(condition_name);
            $("form[name='" + formName + "'] #diagnosis_code").val("");
            $("form[name='" + formName + "'] #append_symptoms").html("");
            $("form[name='" + formName + "'] #append_goals").html("");
            $("form[name='" + formName + "'] #append_tasks").html("");
            $("form[name='" + formName + "'] #symptoms_0").val("");
            $("form[name='" + formName + "'] #goals_0").val("");
            $("form[name='" + formName + "'] #tasks_0").val("");
            $("form[name='" + formName + "'] #support").val("");
            $("form[name='" + formName + "'] textarea[name='comments']").val("");
            $("form[name='" + formName + "'] textarea[name='comments']").text('');

            if (typeof id === "string" && id.trim().length === 0) {
                isLoading.value = false;
                alert("Please select Condition options");
            } else {
                axios({
                    method: 'GET',
                    url: `/ccm/get-all-code-by-id/${id}/${props.patientId}/diagnosis`,
                }).then(response => {
                    clearGoals();
                    const carePlanData = response.data.care_plan_form.static; // Adjust this based on your actual data structure
                    selectedCode.value = carePlanData.code;
                    if (carePlanData && carePlanData.goals) {
                        goals.value = JSON.parse(carePlanData.goals); // Parse the JSON string to an array
                    }
                    if (carePlanData && carePlanData.tasks) {
                        tasks.value = JSON.parse(carePlanData.tasks); // Parse the JSON string to an array
                    }
                    if (carePlanData && carePlanData.symptoms) {
                        symptoms.value = JSON.parse(carePlanData.symptoms); // Parse the JSON string to an array
                    }
                    isLoading.value = false;
                }).catch(error => {
                    console.error(error, error.response);
                });
            }
            if (id == null || id == '' || id == "") {
                isSaveButtonDisabled.value = true;

                $("form[name='" + formName + "'] #save_diagnosis_form").prop("disabled", true);
            } else {
                isSaveButtonDisabled.value = false;
                $("form[name='" + formName + "'] #save_diagnosis_form").prop("disabled", false);
            }

        };



     /*    onMounted(async () => {

        }); */

        return {
            openModal,
            closeModal,
            isOpen,
            getCodeData,
            isSaveButtonDisabled,
            selectedDiagnosisId,
            comments,
            selectedCode,
            loading,
            columnDefs,
            passRowData,
            diagnosisOptions,
            codeOptions,
            selectedMedication,
            reviewCarePlanTimer,
            reviewCarePlanStageId,
            selectedDiagnosis,
            handleDiagnosisChange,
            handleCodeAlert,
            formErrors,
            stepID,
            goals,
            tasks,
            symptoms,
            isInitialGoalFilled,
            isInitialTaskFilled,
            isInitialSymptomFilled,
            goalsText,
            submitCarePlanForm,
            fetchCarePlanFormList,
            getDiagnosisIdfromPatientdiagnosisid,
            changeCondition,
            additionalgoals,
            additionaltasks,
            additionalsymptoms,
            removeGoal,
            clearGoals,
            removeTasks,
            clearTasks,
            removeSymptoms,
            clearSymptoms,
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
    /* height: 800px !important; */
}
</style>
