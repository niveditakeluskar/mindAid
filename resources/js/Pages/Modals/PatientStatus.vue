<template>
    <div class="modal fade" :class="{ 'show': isOpen }" >
		<div class="modal-dialog ">
        <form name="active_deactive_form" id="active_deactive_form" @submit.prevent="submitPatientForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Change Patient Status</h4>
                    <button type="button" class="close" @click="closeModal">×</button>
                </div>
                <div class="modal-body" style="padding-top:10px;" id="active-deactive">
                    <loading-spinner :isLoading="isLoading"></loading-spinner>
                    <div id="patientalertdiv"></div>
                    <input type="hidden" name="patient_id" id="patientStatId"/>
                    <input type="hidden" name="uid" id="uid" >
                    <input type="hidden" name="start_time" value="00:00:00">
                    <input type="hidden" name="end_time" value="00:00:00">
                    <input type="hidden" name="module_id" :value="moduleId" />
                    <input type="hidden" name="component_id" :value="componentId" />
                    <input type="hidden" name="form_name" value="active_deactive_form" />
                    <input type="hidden" name="id">
                    <input type="hidden" name="worklist" id="worklist">
                    <input type="hidden" name="patientid" id="patientid">
                    <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" >
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 form-group mb-3">
                                <label for="module">Module</label>
                                <select name="modules" id="enrolledservice_modules" @change="handleModuleChange" class="custom-select show-tick enrolledservice_modules"></select>
                                <div class="invalid-feedback"  v-if="formErrors.modules" style="display: block;">{{ formErrors.modules[0] }}</div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="status"> Select the Status <span class="error">*</span></label>
                                    <span class="forms-element">
                                        <div class="form-row">
                                            <label class="radio radio-primary col-md-3 float-left" id="role1">
                                                <input type="radio" id="role1" class="" name="status" value="1" formControlName="radio"
                                                @click="showReasonOptions(1)">
                                                <span>Active</span>
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="radio radio-primary col-md-3 float-left" id="role0">
                                                <input type="radio" id="role0" class="" name="status" value="0" formControlName="radio"
                                                @click="showReasonOptions(0)">
                                                <span>Suspended</span>
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="radio radio-primary col-md-3 float-left" id="role2">
                                                <input type="radio" id="role2" class="" name="status" value="2" formControlName="radio"
                                                @click="showReasonOptions(2)">
                                                <span>Deactivated</span>
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="radio radio-primary col-md-3 float-left" id="role3">
                                                <input type="radio" id="role3" class="" name="status" value="3" formControlName="radio"
                                                @click="showReasonOptions(3)">
                                                <span>Deceased</span>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </span>
                                    <div class="form-row invalid-feedback"  v-if="formErrors.status" style="display: block;">{{ formErrors.status[0] }}</div>
                                </div>
                            </div>
                            <div class="col-md-12" id="date_value"> 
                                <div class="form-group row">
                                    <div class="col-md-6 form-group" id="fromdate">
                                        <label for="date" id="from_date">From Date <span class="error">*</span></label>
                                        <input name="activedeactivefromdate" id="fromdate" type="date" class="form-control" max="9999-12-31"  min="1902-01-01">
                                        <div class="form-row invalid-feedback"  v-if="formErrors.activedeactivefromdate" style="display: block;">
                                            {{ formErrors.activedeactivefromdate[0] }}</div>
                                    </div>
                                    <div class="col-md-6 form-group" id="deceasedfromdate">
                                        <label for="date" id="from_date">Date of Deceased <span class="error">*</span></label>
                                        <input name="deceasedfromdate" id="deceasedfromdate" type="date" class="form-control" max="9999-12-31" min="1902-01-01">
                                        <div class="form-row invalid-feedback"  v-if="formErrors.deceasedfromdate" style="display: block;">
                                            {{ formErrors.deceasedfromdate[0] }}</div>
                                    </div>
                                    <div class="col-md-6 form-group" id="todate">
                                        <label for="date">To Date <span class="error">*</span></label>
                                        <input name="activedeactivetodate" type="date" class="form-control" max="9999-12-31"  min="1902-01-01">
                                        <div class="form-row invalid-feedback"  v-if="formErrors.activedeactivetodate" style="display: block;">
                                            {{ formErrors.activedeactivetodate[0] }}</div>
                                    </div>
                                    <div class="col-md-6 form-group" id="deactivation_drpdwn_div">
                                        <label for="deactivation_drpdwn">Reason for Deactivation</label>
                                        <select id="practices" class="custom-select show-tick select2" name="deactivation_drpdwn">
                                            <option value="">Select Deactivation Reasons</option>
                                            <option v-for="Deactivation in Deactivations" :key="Deactivation.id" :value="Deactivation.id">
                                            {{ Deactivation.reasons }}
                                            </option>
                                        </select>
                                        <div class="form-row invalid-feedback"  v-if="formErrors.deactivation_drpdwn" style="display: block;">{{ formErrors.deactivation_drpdwn[0] }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" id="reason">
                                <div id="comments_div" class="mb-3 form-group">
                                    <label for="comments">Reason for status change <span class="error">*</span></label>
                                    <textarea class="form-control" name="deactivation_reason" id="comments"></textarea>
                                    <div id="comments" class="invalid-feedback"></div>
                                </div>
                                <div class="form-row invalid-feedback"  v-if="formErrors.deactivation_reason" style="display: block;">{{ formErrors.deactivation_reason[0] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary float-right submit-active-deactive">Submit</button>
                    <button type="button" class="btn btn-default float-left" data-dismiss="modal" @click="closeModal">Close</button>
                </div>
            </div>
        </form>
		</div>
    </div>
</template>
<script>
    import {
        ref,
    } from '../commonImports';
    import axios from 'axios';

    export default {
        props: {
            moduleId: Number,
            componentId: Number,
            PatientWorkList:{
                type: Function,
                required: true,
            },
            PatientBasicInfoTab:{
                type: Function,
                required: true,
            },
        },
    setup(props) {
            const statusId = ref('');
            const Deactivations = ref([]);
            const isOpen = ref(false);
            const isLoading = ref(false);
            const formErrors = ref({});
            const time = ref('');
            const openModal = async () => {
                isOpen.value = true;
                document.body.classList.add('modal-open');
                fetchActiveDeactiveReasons();
                const element = document.getElementById('page_landing_times');
                if (!element || element.value === null) {
                    const landingTime = await fetchLandingTime();
                    if (landingTime) {
                        //time.value = landingTime;
                        $(".timearr").val(landingTime);
                    }
                } else {
                    //time.value = element.value;
                    $(".timearr").val(element.value);
                }
            };
            let selectPatientId = ref('');
            const closeModal = () => {
                isOpen.value = false;
                document.body.classList.remove('modal-open');
                $("#status").prop("checked", false);
            };

            const fetchLandingTime = async () => {
                try {
                    const response = await fetch('/system/get-landing-time');
                    if (!response.ok) {
                        throw new Error('Failed to fetch landing time');
                    }
                    const data = await response.json();
                    return data.landing_time;
                } catch (error) {
                    console.error('Error fetching landing time:', error);
                    return null;
                }
            };

            const fetchActiveDeactiveReasons = async () => {
                try {
                    const response = await fetch('/patients/get-deactivationreasons');
                    const activedeactiveData = await response.json();
                    const activedeactiveArray = Object.entries(activedeactiveData).map(([id, reasons]) => ({ id, reasons }));
                    Deactivations.value = activedeactiveArray;
                } catch (error) {
                    console.error('Error fetching ActiveDeactiveReasons:', error);
                }
            };

            const showReasonOptions = (param3) => {

                $("form[name='active_deactive_form'] #date_value").show();
                if (param3 == 1) {
                    statusId.value = 1;
                    $("form[name='active_deactive_form'] #fromdate").hide();
                    $("form[name='active_deactive_form'] #deceasedfromdate").hide();
                    $("form[name='active_deactive_form'] #todate").hide();
                    $("form[name='active_deactive_form'] #deactivation_drpdwn_div").hide();
                } else if (param3 == 0) {
                    statusId.value = 0;
                    $("form[name='active_deactive_form'] #fromdate").show();
                    $("form[name='active_deactive_form'] #deceasedfromdate").hide();
                    $("form[name='active_deactive_form'] #todate").show();
                    $("form[name='active_deactive_form'] #deactivation_drpdwn_div").hide();
                } else if (param3 == 2) {
                    statusId.value = 2;
                    $("form[name='active_deactive_form'] #fromdate").show();
                    $("form[name='active_deactive_form'] #deceasedfromdate").hide();
                    $("form[name='active_deactive_form'] #todate").hide();
                    $("form[name='active_deactive_form'] #deactivation_drpdwn_div").show();
                } else if (param3 == 3) {
                    statusId.value = 3;
                    $("form[name='active_deactive_form'] #fromdate").hide();
                    $("form[name='active_deactive_form'] #deceasedfromdate").show();
                    $("form[name='active_deactive_form'] #todate").hide();
                    $("form[name='active_deactive_form'] #deactivation_drpdwn_div").hide();
                } else {
                    statusId.value = '';
                    $("#status").prop("checked", false);
                    $("form[name='active_deactive_form'] #date_value").hide();
                    alert("Invalid Request");
                }
            };

            const handleModuleChange = () =>{
                var available_moduleId = $("#enrolledservice_modules").val();
                
                var selmoduleId;
                const patIdModule = $("#patientStatId").val();
                if (available_moduleId !== undefined) {
                        selmoduleId = available_moduleId;
                    } else {
                        const baseUrl = window.location.origin;
                        const sPageURL = window.location.pathname;
                        const parts = sPageURL.split("/");
                        var selmodule_name= parts[parts.length - 3];
                        if(selmodule_name=='ccm'){
                            selmoduleId = 3;
                        }
                        if(selmodule_name=='rpm'){
                            selmoduleId = 2;
                        }
                    }
                    var statusValue; 
                    axios({
                        method: "GET",
                        url: `/patients/patient-module/${patIdModule}/patient-module`,
                    }).then(function (response) {
                        const enr = response.data;
                        enr.forEach(function(item) {
                            if (item.module_id == selmoduleId) { 
                                statusValue = item.status;
                            }
                        });
                        
                        $("#status").prop("checked", false);
                    if (statusValue == 0) {
                        $("form[name='active_deactive_form'] #role1").show();
                        $("form[name='active_deactive_form'] #role0").hide();
                        $("form[name='active_deactive_form'] #role2").show();
                        $("form[name='active_deactive_form'] #role3").show();
                    }
                    if (statusValue == 1) {
                    $("form[name='active_deactive_form'] #role1").hide();
                    $("form[name='active_deactive_form'] #role0").show();
                    $("form[name='active_deactive_form'] #role2").show();
                    $("form[name='active_deactive_form'] #role3").show();
                    }
                    if (statusValue == 2) {
                    $("form[name='active_deactive_form'] #role1").show();
                    $("form[name='active_deactive_form'] #role0").show();
                    $("form[name='active_deactive_form'] #role2").hide();
                    $("form[name='active_deactive_form'] #role3").show();
                    }
                    if (statusValue == 3) {
                    $("form[name='active_deactive_form'] #role1").show();
                    $("form[name='active_deactive_form'] #role0").show();
                    $("form[name='active_deactive_form'] #role2").show();
                    $("form[name='active_deactive_form'] #role3").hide();
                    } 
                    }).catch(function (error) {
                        console.error(error, error.response);
                    });

         
         
                }

            const callExternalFunctionWithParams = (param1, param2) => {
                if ($.isNumeric(param1) == true) {
                    const patientId = param1;
                    var available_moduleId = $("#modules").val();
                    var selmoduleId;

                    if (available_moduleId !== undefined) {
                        selmoduleId = available_moduleId;
                    } else {
                        const baseUrl = window.location.origin;
                        const sPageURL = window.location.pathname;
                        const parts = sPageURL.split("/");
                        var selmodule_name= parts[parts.length - 3];
                        if(selmodule_name=='ccm'){
                            selmoduleId = 3;
                        }
                        if(selmodule_name=='rpm'){
                            selmoduleId = 2;
                        }
                        // alert(selmoduleId + ' selmoduleId');
                    }
                    axios({
                        method: "GET",
                        url: `/patients/patient-module/${patientId}/patient-module`,
                    }).then(function (response) {
                    $('.enrolledservice_modules').html('');
                        const enr = response.data;
                        var count_enroll = enr.length;
                        for (var i = 0; i < count_enroll; i++) {
                            $('.enrolledservice_modules').append(`<option value="${response.data[i].module_id}">${response.data[i].module.module}</option>`);
                        }
                        $("#enrolledservice_modules").val(selmoduleId).trigger('change');
                    }).catch(function (error) {
                        console.error(error, error.response);
                    });

                    var status = param2;
                    $("form[name='active_deactive_form'] #worklistclick").val("1");
                    $("form[name='active_deactive_form'] #patientid").val(patientId);
                    $("form[name='active_deactive_form'] #patientStatId").val(patientId);
                    $("form[name='active_deactive_form'] #uid").val(patientId);

                    $("form[name='active_deactive_form'] #date_value").hide();
                    $("form[name='active_deactive_form'] #fromdate").hide();
                    $("form[name='active_deactive_form'] #todate").hide();
                    $("#status").prop("checked", false);
                    if (status == 0) {
                        $("form[name='active_deactive_form'] #role1").show();
                        $("form[name='active_deactive_form'] #role0").hide();
                        $("form[name='active_deactive_form'] #role2").show();
                        $("form[name='active_deactive_form'] #role3").show();
                    }
                    if (status == 1) {
                    $("form[name='active_deactive_form'] #role1").hide();
                    $("form[name='active_deactive_form'] #role0").show();
                    $("form[name='active_deactive_form'] #role2").show();
                    $("form[name='active_deactive_form'] #role3").show();
                    }
                    if (status == 2) {
                    $("form[name='active_deactive_form'] #role1").show();
                    $("form[name='active_deactive_form'] #role0").show();
                    $("form[name='active_deactive_form'] #role2").hide();
                    $("form[name='active_deactive_form'] #role3").show();
                    }
                    if (status == 3) {
                    $("form[name='active_deactive_form'] #role1").show();
                    $("form[name='active_deactive_form'] #role0").show();
                    $("form[name='active_deactive_form'] #role2").show();
                    $("form[name='active_deactive_form'] #role3").hide();
                    }
                } else {
                    closeModal();
                }
            };

            const submitPatientForm = async () => {
                isLoading.value = true;
                let myForm = document.getElementById('active_deactive_form');
                let formData = new FormData(myForm);
                axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
                try {
                    const response = await axios.post('/patients/patient-active-deactive', formData);
                    if (response && response.status == 200) {
                        if (typeof props.PatientWorkList=== 'function') {
                            props.PatientWorkList();
                            }
                        const currentUrl = window.location.href;
                        const urlParts = currentUrl.split("/");
                        const id = urlParts[urlParts.length - 1];
                        if(!isNaN(id)){
                            updateTimer(id, '1', props.moduleId);
                        }
                        if(typeof props.PatientBasicInfoTab === 'function'){
                                props.PatientBasicInfoTab();
                            }
                        $(".form_start_time").val(response.data.form_start_time);
                        //time.value = response.data.form_start_time;
                        var successMsg = "";
                        if (statusId.value == 0) {
                            successMsg = "Patient Suspended Successfully!";
                        } else if (statusId.value == 1) {
                            successMsg = "Patient Activated Successfully!";
                        } else if (statusId.value == 2) {
                            successMsg = "Patient Deactivated Successfully!";
                        } else if (statusId.value == 3) {
                            successMsg = "Patient marked as Deceased Successfully!";
                        }
                        $('#patientalertdiv').html('<div class="alert alert-success"><strong>'+successMsg+'</strong></div>');
                        document.getElementById("active_deactive_form").reset();
                        setTimeout(function () {
                            var time = document.getElementById('page_landing_times').value;
                            $(".timearr").val(time);
                            $('#patientalertdiv').html('');
                        }, 3000);
                    }
                    setTimeout(function () {
                        closeModal();
                    }, 3000); // Close the modal after 3 seconds (3000 milliseconds)
                    isLoading.value = false;
                } catch (error) {
                    isLoading.value = false;
                    if (error.response && error.response.status === 422) {
                        formErrors.value = error.response.data.errors;
                        setTimeout(function () {
                            formErrors.value = {};
                        }, 3000);
                        console.log(error.response.data.errors);
                    } else {
                        $('#patientalertdiv').html('<div class="alert alert-danger">Error: ' + error + '</div>');
                        console.error('Error submitting form:', error);
                        setTimeout(function () {
                            $('#patientalertdiv').html('');
                        }, 3000);
                    }
                }
            };
            return {
                fetchLandingTime,
                selectPatientId,
                time,
                submitPatientForm,
                showReasonOptions,
                callExternalFunctionWithParams,
                isOpen,
                openModal,
                closeModal,
                fetchActiveDeactiveReasons,
                Deactivations,
                isLoading,
                formErrors,
                handleModuleChange
            };
        },
    };
</script>
