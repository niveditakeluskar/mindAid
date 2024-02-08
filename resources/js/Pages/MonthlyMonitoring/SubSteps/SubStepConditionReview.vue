<template>
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="form-row">
                        <span class="col-md-4">

                            <select class="custom-select show-tick" @change="setdata(ddDays)" id="ddl_days"
                                v-model="ddDays">
                                <option value="30" selected> 30 days</option>
                                <option value="60"> 60 days</option>
                                <option value="90"> 90 days</option>
                            </select>
                        </span>
                        <span class="col-md-8" style="padding-right:30%">

                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12" id="time_add_success_msg"></div>
                        <div id="success"></div>
                        <div class="col-md-12 steps_section">
                            <div class="title">
                                <input type="hidden" :value="patientId">
                                <input type="hidden" name="module_id" :value="moduleId">
                                <input type="hidden" name="sub_module_id" :value="componentId">
                                <input type="hidden" name="stage_id" :value="stageID">
                                <input type="hidden" name="step_id" :value="reviewReadingStepId">
                                <input type="hidden" name="form_name" value="rpm_review_form">
                                <h5 class="text-center card-title">RPM Readings</h5>
                            </div>
                        </div>
                    </div>


                    <div class="align-self-start pt-3">
                        <div class="d-flex justify-content-center">
                            <div class="mr-2" style="">
                                <div class="card">
                                    <div class="card-header device_header">Number of days</div>
                                    <div class="card-body device_box">
                                        <span id="txt_day" class="day">{{ day }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mr-2" style="">
                                <div class="card">
                                    <div class="card-header device_header">Number of Readings</div>
                                    <div class="card-body device_box">
                                        <span id="txt_reading" class="day">{{ reading }}</span>


                                    </div>
                                </div>
                            </div>
                            <div class="mr-2" style="">
                                <div class="card">
                                    <div class="card-header device_header">% Participation</div>
                                    <div class="card-body device_box">
                                        <span id="showpercentage" class="day">{{ percentage }}</span>
                                        <span class="day">%</span>


                                    </div>
                                </div>
                            </div>
                            <div class=" rounded" style="">
                                <div class="card">
                                    <div class="card-header device_header">Number of Alerts</div>
                                    <div class="card-body device_box">
                                        <span id="txt_alert" class="day">{{ text_alert }}</span>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <ul class="nav nav-tabs" id="patientdevicetab" role="tablist">
                        <li class="nav-item" v-for="list in  devicelist ">
                            <a :class="{ active: deviceID == list.id }" class="nav-link" @click="tabChange(list.id)"
                                id="device-icon-tab_" data-toggle="tab" href="#deviceid_" role="tab"
                                aria-controls="ccm-call" aria-selected="false"><i
                                    class="nav-icon color-icon i-Control-2 mr-1"></i>{{ list.device_name }}</a>
                        </li>
                    </ul>
                    <input type="hidden" id="hd_deviceid" :value="deviceID">


                    <div class="row mt-4">
                        <div class="col">
                            <div class="card">

                                <input type="hidden" name="cald-hid" id="cald-hid" :value="selectedMonthYear">

                                <Calendervue :patientId="patientId" :deviceID="deviceID" v-if="changedevice"
                                    @callParentMethod="ajexChart" />
                            </div>
                        </div>
                        <div>
                            <button type="button" id="btn_datalist" @click="dataList" class="btn btn-primary">Data
                                List</button>
                        </div>
                        <div class="col">
                            <div class="card">

                                <div class="card-body device_box">
                                    <div id="container1" style="height: 400px; width: 100%;"></div>

                                </div>
                            </div>
                        </div>
                    </div>



                    <div id="hd_tbl">
                        <hr>
                        <div class="form-row" v-if="toshowTable">
                            <div class="col-md-2 form-group mb-2">
                                <label for="date">From Date</label>
                                <input id="fromdate" min="1901-01-01" max="2999-12-31" class="form-control" name="date"
                                    type="date" :value="fromDate" autocomplete="off">
                            </div>
                            <div class="col-md-2 form-group mb-3">
                                <label for="date">To Date</label>
                                <input id="todate" min="1901-01-01" max="2999-12-31" class="form-control" name="date"
                                    type="date" :value="toDate" autocomplete="off">
                            </div>
                            <div>
                                <button type="button" id="searchbutton" class="btn btn-primary mt-4" @click="SerchReviewData">Search</button>
                                <button type="button" id="resetbutton" class="btn btn-primary mt-4" style="margin-left: 10px ">Reset</button>
                            </div>
                            <div class="col-md-2 text-right" id="address_btn"></div>
                        </div>
                        <ReviewTable :patientId="patientId" :deviceID="deviceID" :fromDate="fromDate" :toDate="toDate"
                            v-if="toshowTable" />
                    </div>
                    <hr>
                    <div class="card">
                        <form name="rpm_review_form" id="rpm_review_form" @submit.prevent="submitRpmReviewForm">
                            <input type="hidden" name="uid" :value="patientId" />
                            <input type="hidden" name="patient_id" :value="patientId" />
                            <input type="hidden" name="start_time" value="00:00:00">
                            <input type="hidden" name="end_time" value="00:00:00">
                            <input type="hidden" name="module_id" :value="moduleId" />
                            <input type="hidden" name="component_id" :value="componentId" />
                            <input type="hidden" name="stage_id" :value="stageID" />
                            <input type="hidden" name="step_id" :value="reviewNotesStepId">
                            <input type="hidden" name="form_name" id="rpm_review_form" value="rpm_review_form" />
                            <input type="hidden" name="device_id" id="device_id" :value="deviceID" />
                            <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time"
                                :value="time">
                            <div id='success'></div>
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="card-body">
                                            <div class="alert alert-success" id="success-alert"
                                                :style="{ display: showAlert ? 'block' : 'none' }">
                                                <button type="button" class="close" data-dismiss="alert">x</button>
                                                <strong>Review Note saved successfully! </strong><span id="text"></span>
                                            </div>
                                            <label>Care Manager Notes</label>
                                            <textarea name="notes"
                                                class="form-control forms-element notes_class">{{ notes }}</textarea>
                                            <div class="invalid-feedback" v-if="formErrors.notes" style="display: block;">
                                                {{
                                                    formErrors.notes[0] }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn  btn-primary">Save</button>
                            </div>
                        </form>
                    </div>

                    <hr>

                    <div class="modal fade" id="rpm_cm_modal" aria-hidden="true">
                        <div class="modal-dialog modal-lg" style="width: 800px!important;margin-left: 280px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Care Manager Notes</h4>
                                    <button type="button" class="close modalcancel" data-dismiss="modal">&times;</button>
                                </div>

                                <div class="modal-body">
                                    <form action="" name="rpm_cm_form" id="rpm_cm_form" method="POST"
                                        class=" form-horizontal">

                                        <div class="row">
                                            <div class="col-md-12 form-group mb-3">
                                                <input type="hidden" name="hd_timer_start" id="hd_timer_start">
                                                <input type="hidden" name="rpm_observation_id_bp"
                                                    id="rpm_observation_id_bp" />
                                                <input type="hidden" name="rpm_observation_id_hr"
                                                    id="rpm_observation_id_hr" />
                                                <input type="hidden" name="care_patient_id" id="care_patient_id" />
                                                <input type="hidden" name="p_id" id="p_id" />
                                                <input type="hidden" name="csseffdate" id="csseffdate" /> <input
                                                    type="hidden" id="module_id" name="module_id" :value="moduleId">
                                                <input type="hidden" id="component_id" name="component_id"
                                                    :value="componentId">
                                                <input type="hidden" name="table" id="table" />
                                                <input type="hidden" name="formname" id="formname" />
                                                <input type="hidden" name="form_name" id="form_name" value="rpm_cm_form">
                                                <input type="hidden" name="stage_id" id="stage_id" :value="stageId" />
                                                <input type="hidden" name="step_id" id="step_id" :value="reviewDataStepId">
                                                <input type="hidden" name="hd_chk_this" id="hd_chk_this" />
                                                <input type="hidden" name="device_id" id="device_id" />
                                                <input type="hidden" name="rpm_unit_bp" id="rpm_unit_bp" />
                                                <input type="hidden" name="rpm_unit_hr" id="rpm_unit_hr" />
                                                <label for="Notes">Notes<span style="color: red">*</span></label>
                                                <div class="forms-element">

                                                </div>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="mc-footer">
                                                <div class="row">
                                                    <div class="col-lg-12 text-right">
                                                        <button type="submit" dataid="rpm_cm_form"
                                                            class="btn btn-primary m-1">Submit</button>
                                                        <button type="button"
                                                            class="btn btn-outline-secondary m-1 modalcancel"
                                                            data-dismiss="modal">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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

import axios from 'axios';
import Calendervue from '../Components/Calender.vue';
import ReviewTable from '../Components/ReviewTable.vue';
import {
    ref,
    // Add other common imports if needed
} from '../../commonImports';
export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number
    },
    components: {
        Calendervue,
        ReviewTable
    },
    data() {
        return {
            navLink: 'nav-link',
            ddDays: 30,
            day: null,
            reading: null,
            text_alert: null,
            percentage: null,
            stageId: 0,
            reviewReadingStepId: null,
            reviewNotesStepId: null,
            reviewDataStepId: null,
            devicelist: null,
            selectedMonthYear: null,
            deviceID: 0,
            formErrors: {},
            changedevice: false,
            toshowTable: false,
            fromDate: null,
            toDate: null,
            notes: null,
        };
    },
    mounted() {
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js';
        script.async = true;


        document.body.appendChild(script);
        this.time = document.getElementById('page_landing_times').value;
        this.setdata(30);
        this.getStageID();
        this.getDevice();
    },
    methods: {
        async getDevice() {
            await axios.get(`/ccm/get_device_list/${this.patientId}/device_list`)
                .then(response => {
                    this.devicelist = response.data.content;
                    this.deviceID = response.data.deviceID;
                    this.changedevice = true,
                        this.selectedMonthYear = $("#calender").find(".fc-toolbar-title").html();
                    //var substr = $("#calender").find(".fc-toolbar-title").html();
                    //var month = (moment().month(substr).format("M"));
                    //var myArray = substr.split(" ");
                    //var year = myArray[1];
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        },

        async ajexChart() {
            this.toshowTable = false
            var substr = $("#calender").find(".fc-toolbar-title").html();
            this.selectedMonthYear = substr;
            var month = (moment().month(substr).format("M"));
            var myArray = substr.split(" ");
            var year = myArray[1];
            await axios.get(`/rpm/graphreadingnew-chart/${this.patientId}/${this.deviceID}/${month}/${year}/graphchart`)
                .then(response => {
                    this.changedevice = true;

                    this.getChartOnclick(response.data, "container1", this.deviceID);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        },
        async getStageID() {
            try {
                let stageName = 'Review_RPM';
                let response = await axios.get(`/get_stage_id/${this.moduleId}/${this.componentId}/${stageName}`);
                this.stageId = response.data.stageID;
                let stepname1 = 'Review-Rpm-Reading';
                let stepname2 = 'Review-Rpm-Caremanager-Notes';
                let stepname3 = 'Review-Rpm-DataList';
                let response1 = await axios.get(`/get_step_id/${this.moduleId}/${this.componentId}/${this.stageId}/${stepname1}`);
                let response2 = await axios.get(`/get_step_id/${this.moduleId}/${this.componentId}/${this.stageId}/${stepname2}`);
                let response3 = await axios.get(`/get_step_id/${this.moduleId}/${this.componentId}/${this.stageId}/${stepname3}`);
                this.reviewReadingStepId = response1.data.stepID;
                this.reviewNotesStepId = response2.data.stepID;
                this.reviewDataStepId = response3.data.stepID;
                let responsenotes = await axios.get(`/ccm/get_revew_notes/${this.patientId}`);
                this.notes = (responsenotes.data).trim();
                $("#preloader").hide();
            } catch (error) {
                throw new Error('Failed to fetch stageID');
            }
        },
        async setdata(month) {
            let response = await axios.get(`/rpm/getNumberOfReading/${month}/${this.patientId}`);
            this.day = month;
            this.reading = response.data.readingcnt;
            this.text_alert = response.data.alertcnt;
            var calcPerc = (response.data.readingcnt) * month / 100;
            var countPerc = calcPerc.toFixed(2);
            this.percentage = countPerc;
        },

        async submitRpmReviewForm() {
            let myForm = document.getElementById('rpm_review_form');
            let formData = new FormData(myForm);
            this.renderComponent = false;
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            try {
                this.formErrors = {};
                const response = await axios.post('/rpm/save-rpm-review-notes', formData);
                if (response && response.status == 200) {
                    this.renderComponent = true;
                    this.showAlert = true;
                    updateTimer(this.patientId, 1, this.moduleId);
                    $('.form_start_time').val(response.data.form_start_time);
                    setTimeout(() => {
                        this.time = document.getElementById('page_landing_times').value;
                        this.showAlert = false;
                    }, 3000);
                }
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.formErrors = error.response.data.errors;
                } else {
                    console.error('Error submitting form:', error);
                }
            }
        },

        async tabChange(id) {
            this.deviceID = id;
            var activedeviceid = id;
            var c_month = (new Date().getMonth() + 1).toString().padStart(2, "0");
            var c_year = new Date().getFullYear();
            var current_MonthYear = c_year + '-' + c_month;
            //calender and graph click js
            var abc = $("#calender").find(".fc-toolbar-title").html();
            if (abc != undefined) {
                this.changedevice = false;
                this.ajexChart();
            }
        },

        async SerchReviewData(){
            this.toshowTable = false;
            this.fromDate = $("#fromdate").val();
            this.toDate = $("#todate").val();
            let tab = 'observationsbp';
            await axios.get(`/rpm/patient-alert-history-list-device-link/${this.patientId}/${tab}/${this.fromDate}/${this.toDate}`)
                .then(response => {
                    this.toshowTable = true;
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        },

        async dataList() {
            var months = {
                January: 1,
                February: 2,
                March: 3,
                April: 4,
                May: 5,
                June: 6,
                July: 7,
                August: 8,
                September: 9,
                October: 10,
                November: 11,
                December: 12
            }
            var str = this.selectedMonthYear;
            var myArr = str.split(" ");
            var newMonth = months[myArr[0]];
            var Month = '';
            if (newMonth < 10) {
                Month = '0' + newMonth;
            } else {
                Month = newMonth;
            }
            var newYear = myArr[1];
            var newDate = newYear + "-" + Month + "-" + '01';
            var dateStr = new Date(newYear, Month, 1, 0);
            var ret = new Date(dateStr).toISOString();
            var str1 = ret.split("T");
            var lastDay = str1[0];
            this.fromDate = newDate;
            this.toDate = lastDay
            $("#address_btn").html('<button type="button" class="btn btn-primary mt-4 month-reset" id="Addressed"  >Addressed</button>');
            this.toshowTable = true;
        },

        async getChartOnclick(data, id, deviceid) {
            var patientarraydatetime = JSON.parse(JSON.stringify(data.uniArray));
            var reading = JSON.parse(JSON.stringify(data.arrayreading));
            var label = JSON.parse(JSON.stringify(data.label));
            var reading1 = JSON.parse(JSON.stringify(data.arrayreading1));
            var label1 = JSON.parse(JSON.stringify(data.label1));

            var arrayreading_min = JSON.stringify(data.arrayreading_min);
            var reading_min = JSON.parse(arrayreading_min);
            var arrayreading_max = JSON.stringify(data.arrayreading_max);
            var reading_max = JSON.parse(arrayreading_max);

            var arrayreading_min1 = JSON.stringify(data.arrayreading_min1);
            var reading_min1 = JSON.parse(arrayreading_min1);
            var arrayreading_max1 = JSON.stringify(data.arrayreading_max1);
            var reading_max1 = JSON.parse(arrayreading_max1);
            var title_name = JSON.parse(JSON.stringify(data.title_name));

            if (deviceid == 3) {
                var subtitle2 = " / <b>" + label1 + "</b>" + " - [Max:" + reading_max1 + " ]/[Min: " + reading_min1 + "]";
            } else {
                var subtitle2 = "";
            }

            var subtitle1 = "<b>" + label + "</b>" + " - [Min:" + reading_min + " ]/[Max: " + reading_max + "]";

            Highcharts.chart(id, {
                chart: {
                    type: 'spline',
                    events: {
                        load: function () {
                            this.series.forEach(function (s) {
                                s.update({
                                    showInLegend: s.points.length
                                });
                            });
                        }
                    }
                },
                xAxis: {
                    //type: 'datetime',
                    categories: patientarraydatetime,
                    crosshair: true,  //extra
                    index: 1,//extra
                    gridLineWidth: 1,
                },
                title: {
                    text: title_name
                },
                subtitle: {
                    text: subtitle1 + ' ' + subtitle2
                },
                yAxis: {
                    title: {
                        text: 'Readings' //'Wind speed (m/s)'
                    },
                    min: 0,
                    minorGridLineWidth: 0,

                    alternateGridColor: null,
                    plotBands: [
                        {
                            from: reading_min,
                            to: reading_max,
                            color: 'rgba(68, 170, 213, 0.1)',

                        },
                        {
                            from: reading_min1,
                            to: reading_max1,
                            color: 'rgba(243, 248, 157, 1)',//'rgba(269, 70, 213, 0.1)',

                        }
                    ]
                },
                tooltip: {
                    shared: true,
                    crosshairs: true
                },
                plotOptions: {
                    spline: {
                        lineWidth: 4,
                        states: {
                            hover: {
                                lineWidth: 5
                            }
                        },
                        marker: {
                            enabled: true
                        },
                    }
                },
                series: [{
                    name: label,
                    data: reading
                },
                {
                    name: label1,
                    data: reading1
                }],
                navigation: {
                    menuItemStyle: {
                        fontSize: '10px'
                    }
                }
            });

        },
    },



};


</script>

