<template>
    <div class="modal fade" :class="{ 'show': isOpen }">
        <div class="modal-dialog modal-xl" style="padding-top:10px;  ">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create / Modify Care Plan</h4>
                    <button type="button" class="close" data-dismiss="modal" @click="closeModal">×</button>
                </div>
                <div class="modal-body" style="padding-top:0px; margin:0px;">
                    <loading-spinner :isLoading="isLoading"></loading-spinner>
                    <div class="row ">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group ccmPatientData" id="diagnosis-codes" name="diagnosis_codes"
                                        style="">
                                        <div class="row " id="diagnosis">
                                            <div class="col-md-12 ">
                                                <div class="success" id="success"></div>
                                                <div class="card-body diagnosis-Data">
                                                    <div class="row ">
                                                        <div class="col-md-12 ">
                                                            <ul class="nav nav-pills" id="myPillTab" role="tablist">
                                                                <li class="nav-item">
                                                                    <!-- <a class="nav-link active" id="medication-icon-pill" data-toggle="pill" href="#medication" role="tab" aria-controls="medication" aria-selected="true"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i>MEDICATION</a> -->
                                                                </li>
                                                            </ul>
                                                            <div class="tab-content mb-4" id="myPillTabContent">
                                                                <div class="tab-pane fade show active" id="diagnosis"
                                                                    role="tabpanel" aria-labelledby="diagnosis-icon-pill">
                                                                    <div class=" mb-4">
                                                                        <div class="reviewCareAlert"></div>
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
                                                                                <input type="hidden"
                                                                                    name="hiddenenablebutton"
                                                                                    id="hiddenenablebutton"> 
                                                                                <input type="hidden" name="editdiagnoid" id="editdiagnoid"
                                                                                    v-model="selectedEditDiagnosId">
                                                                                <input type="hidden" id="cpd_finalize"
                                                                                    value="1">
                                                                                <input type="hidden" name="billable"
                                                                                    value="1">
                                                                                <input type="hidden"
                                                                                    name="timearr[form_start_time]"
                                                                                    class="timearr form_start_time"
                                                                                    >
                                                                                <div class="row col-md-12">
                                                                                    <div class="col-md-6"><label>Condition
                                                                                            <span
                                                                                                class="error">*</span>:</label>
                                                                                        <input type="hidden"
                                                                                            name="condition"
                                                                                            v-model="selectedcondition">

                                                                                        <select id="diagnosis_condition"
                                                                                            class="custom-select show-tick"
                                                                                            name="diagnosis"
                                                                                            v-model="selectedDiagnosis"
                                                                                            @change="handleDiagnosisChange">
                                                                                            <option
                                                                                                v-for="item in diagnosisOptions"
                                                                                                :key="item.id"
                                                                                                :value="item.id">
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
                                                                                        <label><span
                                                                                                class="error"></span>&nbsp;
                                                                                        </label>
                                                                                        <button type="button"
                                                                                            class="col-md-12 btn btn-primary"
                                                                                            @click="() => changeCondition('care_plan_form')"
                                                                                            id="render_plan_form"> Display
                                                                                            Care
                                                                                            Plan</button>
                                                                                    </div>

                                                                                    <div class="col-md-4 emaillist">
                                                                                        <label>Code<span
                                                                                                class="error">*</span>
                                                                                            :</label>
                                                                                        <input type="hidden" id="codeid">
                                                                                        <select id="diagnosis_code"
                                                                                            class="custom-select show-tick"
                                                                                            name="code"
                                                                                            v-model="selectedCode"
                                                                                            @change="handleCodeAlert">
                                                                                            <option value="">Select Code
                                                                                            </option>
                                                                                            <option
                                                                                                v-for="code in codeOptions"
                                                                                                :key="code" :value="code">{{
                                                                                                    code }}</option>

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
                                                                                            class="form-control"
                                                                                            name="new_code" type="text"
                                                                                            value="" autocomplete="off">
                                                                                        <div class="invalid-feedback"></div>
                                                                                    </div>


                                                                                    <div>
                                                                                        <button type="button"
                                                                                            v-if="showButton_enable_diagnosis_button"
                                                                                            class="btn btn-primary mt-2 ml-3"
                                                                                            id="enable_diagnosis_button"
                                                                                            @click="() => enableDiagnosisbutton('care_plan_form')">Enable
                                                                                            Editing</button>
                                                                                        <button type="button"
                                                                                            v-if="showButton_disable_diagnosis_button"
                                                                                            class="btn btn-primary mt-2 ml-3"
                                                                                            id="disable_diagnosis_button"
                                                                                            @click="() => disableDiagnosisbutton('care_plan_form')">Disable
                                                                                            Editing</button>
                                                                                    </div>

                                                                                    <div class="col-md-12">
                                                                                        <label for="Template">Symptoms<span
                                                                                                class="error">*</span>
                                                                                            :</label>
                                                                                        <div v-for="(symptom, index) in symptoms"
                                                                                            :key="index"
                                                                                            class="goal-container">
                                                                                            <input :key="index"
                                                                                                v-model="symptoms[index]"
                                                                                                placeholder="Enter Symptoms"
                                                                                                :id="'symptoms_' + index"
                                                                                                class="form-control"
                                                                                                name="symptoms[]"
                                                                                                type="text"
                                                                                                autocomplete="off"
                                                                                                :disabled="isDisabled"
                                                                                                :required="index === 0 ? !isInitialSymptomFilled : false">
                                                                                            <i class="col-md-1 remove-icons i-Remove float-right mb-3"
                                                                                                v-if="showButton_remove"
                                                                                                @click="removeSymptoms(index)"
                                                                                                :id="'remove_symptoms_' + index"
                                                                                                title="Remove Symptoms"></i>
                                                                                        </div>
                                                                                        <div class="invalid-feedback"></div>
                                                                                        <i class="plus-icons i-Add"
                                                                                            id="append_symptoms_icons"
                                                                                            v-if="showButton_add"
                                                                                            @click="additionalsymptoms()"
                                                                                            title="Add symptons"></i>

                                                                                        <div class="col-md-10 mb-3"
                                                                                            id="append_symptoms"></div>
                                                                                    </div>

                                                                                    <div class="col-md-12">
                                                                                        <label
                                                                                            for="contactNumber">Goals<span
                                                                                                class="error">*</span>
                                                                                            :</label>
                                                                                        <div v-for="(goal, index) in goals"
                                                                                            :key="index"
                                                                                            class="goal-container">
                                                                                            <input :key="index"
                                                                                                v-model="goals[index]"
                                                                                                placeholder="Enter Goal"
                                                                                                :id="'goals_' + index"
                                                                                                class="form-control"
                                                                                                name="goals[]" type="text"
                                                                                                autocomplete="off"
                                                                                                :disabled="isDisabled"
                                                                                                :required="index === 0 ? !isInitialGoalFilled : false" >
                                                                                            <i class="col-md-1 remove-icons i-Remove float-right mb-3"
                                                                                                v-if="showButton_remove"
                                                                                                @click="removeGoal(index)"
                                                                                                :id="'remove_goal_' + index"
                                                                                                title="Remove Goal"></i>
                                                                                        </div>
                                                                                        <i class="plus-icons i-Add"
                                                                                            id="append_goals_icons"
                                                                                            v-if="showButton_add"
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
                                                                                            :key="index"
                                                                                            class="goal-container">
                                                                                            <textarea :key="index"
                                                                                                v-model="tasks[index]"
                                                                                                placeholder="Enter tasks"
                                                                                                :id="'tasks_' + index"
                                                                                                class="form-control"
                                                                                                name="tasks[]" type="text"
                                                                                                style="height:50px;overflow-y:hidden;"
                                                                                                :disabled="isDisabled"
                                                                                                :required="index === 0 ? !isInitialTaskFilled : false"></textarea>
                                                                                            <i class="col-md-1 remove-icons i-Remove float-right mb-3"
                                                                                                v-if="showButton_remove"
                                                                                                @click="removeTasks(index)"
                                                                                                :id="'remove_tasks_' + index"
                                                                                                title="Remove Task"></i>
                                                                                        </div>
                                                                                        <div class="invalid-feedback"></div>
                                                                                        <i class="plus-icons i-Add"
                                                                                            id="append_tasks_icons"
                                                                                            v-if="showButton_add"
                                                                                            @click="additionaltasks()"
                                                                                            title="Add task"></i>
                                                                                        <div class="col-md-10 mb-3"
                                                                                            id="append_tasks"></div>
                                                                                    </div>


                                                                                    <div class="col-md-12">
                                                                                        <label
                                                                                            for="Template">Comment:</label>
                                                                                        <textarea
                                                                                            class="forms-element form-control"
                                                                                            id="diagnosis_comments"
                                                                                            v-model="comments"
                                                                                            name="comments"
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
                                                                        <div class="reviewCareAlert" ></div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="separator-breadcrumb border-top"></div>
                                                            <div class="col-md-12">
                                                                <div class="row ml-2">
                                                                    <a href="javascript:void(0)" data-toggle="tooltip"
                                                                        data-original-title="green" title="green"
                                                                        onclick=""><i class="i-Closee  i-Data-Yes"
                                                                            style="color: #33ff33;"></i></a>&nbsp;<p>Care
                                                                        Plans reviewed for 0-6 months&nbsp; &nbsp; &nbsp;
                                                                    </p><a href="javascript:void(0)" data-toggle="tooltip"
                                                                        data-original-title="yellow" title="yellow"
                                                                        onclick=""><i class="i-Closee  i-Data-Yes"
                                                                            style="color: yellow;"></i></a>&nbsp;<p>Care
                                                                        Plans not reviewed for more than 6 months and less
                                                                        than 12 months&nbsp; &nbsp; &nbsp;</p><a
                                                                        href="javascript:void(0)" data-toggle="tooltip"
                                                                        data-original-title="red" title="red" onclick=""><i
                                                                            class="i-Closee  i-Data-Yes"
                                                                            style="color: red;"></i></a>&nbsp;<p>Care Plans
                                                                        not reviewed for more than or equal to 12
                                                                        months&nbsp; &nbsp; &nbsp;</p>
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
                    <div class="">
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
        const isDisabled = ref(false);
        const showButton_enable_diagnosis_button = ref(false);
        const showButton_disable_diagnosis_button = ref(false);
        const showButton_remove = ref(true);
        const showButton_add = ref(true);
        const selectedDiagnosisId = ref('');
        const comments = ref('');
        const formErrors = ref({});
        const showSuccessAlert = ref(false);
        const isLoading = ref(false);
        const goals = ref([]);
        const tasks = ref([]);
        const symptoms = ref([]);
        const isInitialGoalFilled = ref(false);
        const isInitialTaskFilled = ref(false);
        const isInitialSymptomFilled = ref(false);
        const goalsText = ref('');
        const selectedDiagnosis = ref('');
        const selectedCode = ref('');
        const passRowData = ref([]);
        const loading = ref(false);
        let diagnosisOptions = ref([]);
        let codeOptions = ref([]);
        let selectedMedication = ref('');
        const selectedEditDiagnosId = ref('');
        const selectedcondition = ref('');
        const isOpen = ref(false);
        let reviewCarePlanTimer = ref(null);
        const openModal = () => {
            isOpen.value = true;
            document.body.classList.add('modal-open');
            fetchCarePlanFormList();
            fetchDiagnosis();
            fetchCode();
            getStageID();
            additionalgoals();
            additionalsymptoms();
            additionaltasks();
            const reviewCaretimerElement = document.getElementById('page_landing_times').value;
            if (reviewCaretimerElement !== null) {
                //reviewCarePlanTimer.value = reviewCaretimerElement;
                $(".timearr").val(reviewCaretimerElement);
            }
        };

        const closeModal = () => {
            isOpen.value = false;
            document.body.classList.remove('modal-open');
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
                        editIconColor = '#33ff33';
                    } else if (data.iconcolor === 'yellow') {
                        editIconColor = 'yellow';
                    } else if (data.iconcolor === 'red') {
                        editIconColor = 'red';
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

                    link.addEventListener('click', (event, formName) => {
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



        let reviewCarePlanStageId = ref(0);
        let stepID = ref(0);

        const editPatientDignosis = async (id) => {
            clearGoals();
            fetchCode();
            showButton_enable_diagnosis_button.value = true;
            showButton_disable_diagnosis_button.value = true;
            showButton_add.value = false;
            showButton_remove.value = false;
            isLoading.value = true;
            // isDisabled.value = true;
            const formName = 'care_plan_form';
            $("form[name='" + formName + "'] #hiddenenablebutton").val(0);
            try {
                selectedEditDiagnosId.value = id;
                const response = await fetch(`/ccm/diagnosis-select/${id}/${props.patientId}`);
                if (!response.ok) {
                    throw new Error('Failed to fetch followup task list');
                }
                const data = await response.json();
                const carePlanData = data.care_plan_form.static;

                if (carePlanData && carePlanData.goals) {
                    goals.value = JSON.parse(carePlanData.goals);
                }
                selectedDiagnosisId.value = carePlanData.diagnosis;
                selectedDiagnosis.value = carePlanData.diagnosis;
                selectedCode.value = carePlanData.code;
                selectedcondition.value = carePlanData.condition;
                comments.value = carePlanData.comments;
                if (carePlanData && carePlanData.tasks) {
                    tasks.value = JSON.parse(carePlanData.tasks);
                }
                if (carePlanData && carePlanData.symptoms) {
                    symptoms.value = JSON.parse(carePlanData.symptoms);
                }
                isLoading.value = false;
                isSaveButtonDisabled.value = false;
                isDisabled.value = true;
            } catch (error) {
                console.error('Error fetching CPD list:', error);
                isLoading.value = false;
            }
        };

        const enableDiagnosisbutton = async (formName) => {
            showButton_add.value = true;
            showButton_remove.value = true;
            $("form[name='" + formName + "'] #hiddenenablebutton").val(1);
            isDisabled.value = false;
        }
        const disableDiagnosisbutton = async (formName) => {
            // showButton_add.value = false;
            // showButton_remove.value = false;
            // const id = $("form[name='" + formName + "'] #editdiagnoid").val();
            const edit_id =$("form[name='" + formName + "'] #editdiagnoid").val();
            $("form[name='" + formName + "'] #hiddenenablebutton").val(0);
            editPatientDignosis(edit_id);
        }

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
                    $('.reviewCareAlert').html('<div class="alert alert-success">Deleted Successfully</div>');
                    fetchCarePlanFormList();
                    updateTimer(props.patientId, '1', props.moduleId);
                    document.querySelector('.form_start_time').value = responseData.form_start_time;
                    setTimeout(() => {
                        $('.reviewCareAlert').html('');
                    }, 3000);
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
            showButton_enable_diagnosis_button.value = false;
            showButton_disable_diagnosis_button.value = false;
            showButton_add.value = true;
            showButton_remove.value = true;
            isDisabled.value = false;
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
                    selectedEditDiagnosId.value =' ';
                    $('.reviewCareAlert').html('<div class="alert alert-success"> Data Saved Successfully </div>');
                    document.getElementById("care_plan_form").reset();
                    isLoading.value = false;
                    fetchCarePlanFormList();
                    updateTimer(props.patientId, '1', props.moduleId);
                    $(".form_start_time").val(response.data.form_start_time);
                    //reviewCarePlanTimer.value = document.getElementById('page_landing_times').value;
                    setTimeout(() => {
                        var time = document.getElementById('page_landing_times').value;
                        $(".timearr").val(time);
                        $('.reviewCareAlert').html('');
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
                alert('please select condition!');
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

        const getCodeData = async () => {
            try {
                const response = await fetch(`/ccm/get_diagnosis_all_codes/${selectedDiagnosis.value}/get_diagnosis_all_codes`);
                if (!response.ok) {
                    throw new Error(`Failed to fetch Patient Preaparation - ${response.status} ${response.statusText}`);
                }
                const codeData = await response.json();
                codeOptions.value = codeData.map(item => item.code);

            } catch (error) {
                console.error('Error fetching Code:', error);
            }
        };

        let fetchCode = async () => {
            try {
                await new Promise((resolve) => setTimeout(resolve, 2000));
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
                    isDisabled.value = true;
                    showButton_add.value = false;
                    showButton_remove.value = false;
                    // $("form[name='" + formName + "'] #symptoms_0").prop("disabled", true);
                    // $("form[name='" + formName + "'] #goals_0").prop("disabled", true);
                    // $("form[name='" + formName + "'] #tasks_0").prop("disabled", true);
                    // $("form[name='" + formName + "']  .symptoms ").prop("disabled", true);
                    // $("form[name='" + formName + "']  .goals ").prop("disabled", true);
                    // $("form[name='" + formName + "']  .tasks  ").prop("disabled", true);
                    // $("form[name='" + formName + "']  #append_symptoms_icons  ").hide();
                    // $("form[name='" + formName + "']  #append_goals_icons  ").hide();
                    // $("form[name='" + formName + "']  #append_tasks_icons  ").hide();
                    // $("form[name='" + formName + "']  .removegoals  ").hide();
                    // $("form[name='" + formName + "']  .removesymptoms  ").hide();
                    // $("form[name='" + formName + "']  .removetasks  ").hide();
                } else {
                    // Your code for when count is 0
                    $("form[name='" + formName + "'] #hiddenenablebutton").val(1);
                    isDisabled.value = false;
                    showButton_add.value = true;
                    showButton_remove.value = true;
                    // $("form[name='" + formName + "'] #symptoms_0").prop("disabled", false);
                    // $("form[name='" + formName + "'] #symptoms_0").prop("disabled", false);
                    // $("form[name='" + formName + "'] #goals_0").prop("disabled", false);
                    // $("form[name='" + formName + "'] #tasks_0").prop("disabled", false);
                    // $("form[name='" + formName + "']  .symptoms ").prop("disabled", false);
                    // $("form[name='" + formName + "']  .goals ").prop("disabled", false);
                    // $("form[name='" + formName + "']  .tasks  ").prop("disabled", false);
                    // $("form[name='" + formName + "']  #append_symptoms_icons  ").show();
                    // $("form[name='" + formName + "']  #append_goals_icons  ").show();
                    // $("form[name='" + formName + "']  #append_tasks_icons  ").show();
                    // $("form[name='" + formName + "']  .removegoals  ").show();
                    // $("form[name='" + formName + "']  .removesymptoms  ").show();
                    // $("form[name='" + formName + "']  .removetasks  ").show();
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
            comments.value ='';
            selectedCode.value ='';
        };

        watchEffect(() => { 
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
            if (typeof formName !== 'string') {
                console.error('Invalid formName:', formName);
                isLoading.value = false;
                return;
            }
            $("form[name='" + formName + "'] #editdiagnoid").val();
            var editid = $("form[name='" + formName + "'] #editdiagnoid").val();
            $("form[name='diagnosis_code_form'] #editdiagnoid").val(editid);
            $("form[name='care_plan_form'] #editdiagnoid").val(editid);
            // $("form[name='" + formName + "'] #enable_diagnosis_button").hide();
            // $("form[name='" + formName + "'] #disable_diagnosis_button").hide();
            let currentPatientId = props.patientId;
            var id = selectedDiagnosis.value;
            var condition_name = $("form[name='" + formName + "'] #diagnosis_condition option:selected").text();
            var code = $("form[name='" + formName + "'] #diagnosis_code").val();
            getCodeData();
            getDiagnosisIdfromPatientdiagnosisid(editid, condition_name, code, formName, currentPatientId);
            if (typeof id === "string" && id.trim().length === 0) {
                isLoading.value = false;
                alert("Please select Condition options");
            } else {
                axios({
                    method: 'GET',
                    url: `/ccm/get-all-code-by-id/${id}/${props.patientId}/diagnosis`,
                }).then(response => {
                    clearGoals();
                    const carePlanData = response.data.care_plan_form.static;
                    selectedcondition.value = carePlanData.condition;
                    selectedCode.value = carePlanData.code;
                    if (carePlanData && carePlanData.goals) {
                        goals.value = JSON.parse(carePlanData.goals);
                    }
                    if (carePlanData && carePlanData.tasks) {
                        tasks.value = JSON.parse(carePlanData.tasks);
                    }
                    if (carePlanData && carePlanData.symptoms) {
                        symptoms.value = JSON.parse(carePlanData.symptoms);
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
            showButton_enable_diagnosis_button,
            showButton_disable_diagnosis_button,
            showButton_remove,
            showButton_add,
            isDisabled,
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
            enableDiagnosisbutton,
            disableDiagnosisbutton,
            selectedcondition,
            startTimeInput,
        };
    }

};
</script>
