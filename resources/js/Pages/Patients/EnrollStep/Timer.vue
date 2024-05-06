<template>
    <div class="card-header mb-3">
        <div class="row">
            <div class="col-10"></div>
            <div class="col-2">
                <span data-toggle="tooltip" data-placement="right" title="Billable Time"
                                                data-original-title="Billable Time"><i class="text-muted i-Clock-4"></i> :
                                                <span class="last_time_spend" :textContent="billable_time">
                                                </span></span>
                <span data-toggle="tooltip" data-placement="right" title="Non Billable Time"
                                                data-original-title="Non Billable Time"> / <span
                                                    class="non_billabel_last_time_spend" :textContent="non_billable_time">
                                                </span></span>
                <div class="demo-div">
                    <div class="stopwatch" id="stopwatch">
                        <i class="text-muted i-Timer1"></i> :
                        <div id="time-container" class="container" data-toggle="tooltip" data-placement="right"
                            title="Current Running Time" data-original-title="Current Running Time"
                            style="display:none!important">
                        </div>
                        <label for="Current Running Time" data-toggle="tooltip" title="Current Running Time"
                            data-original-title="Current Running Time">
                            <span id="time-containers" :textContent="total_time"></span></label>
                        <a class="button" id="start" data-toggle="tooltip" data-placement="right" title="Start Timer"
                            data-original-title="Start Timer" style="display: none;cursor: pointer;" @click="logTimeStart(patientId, moduleId, componentId, 0, 1, 0, 'log_time_patient_registration')"><img
                                src="@@/assets/images/play.png" style=" width: 28px;" /></a>
                        <a class="button" id="pause" data-toggle="tooltip" data-placement="right" title="Pause Timer"
                            data-original-title="Pause Timer" style="cursor: pointer;" @click="logTime(patientId, moduleId, componentId, 0, 1, 0, 'log_time_patient_registration')" ><img
                                src="@@/assets/images/pause.png" style=" width: 28px;" /></a>
                        <a class="button" id="stop" data-toggle="tooltip" data-placement="right" title="Stop Timer"
                            data-original-title="Stop Timer" style="cursor: pointer;" @click="logTime(patientId, moduleId, componentId, 0, 1, 0, 'log_time_patient_registration')"><img
                                src="@@/assets/images/stop.png" style=" width: 28px;" /></a>
                        <button class="button" id="reset" data-toggle="tooltip" data-placement="top" title="Reset Timer"
                            data-original-title="Reset Timer" style="display:none;">Reset</button>
                        <button class="button" id="resetTickingTime" data-toggle="tooltip" data-placement="top"
                            title="resetTickingTime Timer" data-original-title="resetTickingTime Timer"
                            style="display:none;">resetTickingTime</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {
    ref,
    onMounted,
    watch
} from '../../commonImports';
import axios from 'axios';
export default {
    props: {
        moduleId: Number,
        componentId: Number,
        stageId: Number,
        patientId: Number,
    },
    setup(props) {
        const total_time = ref();
        const billable_time = ref();
        const non_billable_time = ref();
        var pause_stop_flag = 0;
        var pause_next_stop_flag = 0;

        const countDownFunc = async () => {
            const start_time = document.getElementById('page_landing_times').value;
            await axios.get(`/system/get-total-time/${props.patientId}/${props.moduleId}/${start_time}/total-time`)
                .then(response => {
                    if (pause_stop_flag == 0) {
                        var data = response.data;
                        var final_time = data['total_time'];
                        total_time.value = final_time;
                        billable_time.value = data['billable_time'];
                        non_billable_time.value = data['non_billable_time'];
                        setTimeout(function () {
                            countDownFunc();
                        }, 60000);
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
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

        onMounted(async () => {
            countDownFunc();
        });

        return {
            total_time,
            billable_time,
            non_billable_time,
            countDownFunc,
            logTimeStart,
            logTime
        }
    }
};
</script>