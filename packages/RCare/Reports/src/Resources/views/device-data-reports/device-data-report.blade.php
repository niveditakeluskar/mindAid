@extends('Theme::layouts_2.to-do-master')
@section('page-css')
<style>
    .highcharts-figure,
    .highcharts-data-table table {
        min-width: 360px;
        max-width: 800px;
        margin: 1em auto;
    }
</style>
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Device Data Report</h4>
        </div>
    </div>
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <div class="form-row ">
                    <div class="col-md-3 form-group mb-3">
                        <label for="practicename">Practice Name</label>
                        @selectpracticespcp("practices", ["id" => "practices", "class" => "select2"])
                        <!-- selectpractices -->
                        <input type="hidden" id="hd_pid" name="hd_pid">
                        <input type="hidden" id="hd_p_name" name="hd_p_name">
                        <input type="hidden" id="hd_dob" name="hd_dob">
                        <input type="hidden" id="hd_mrn" name="hd_mrn">
                        <input type="hidden" id="hd_device" name="hd_device">
                    </div>
                    <div class="col-md-3 form-group mb-3 patient-div">
                        <label for="practicename">Patient Name</label>
                        @select("Patient", "patient_id", [], ["id" => "patient", "class" => "select2"])
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="month">From Month & Year</label>
                        @month('fromdate',["id" => "monthly"])
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary mt-4" id="ddsearch">Search</button>
                        <button type="button" id="btn" class="btn btn-success mt-4">generate PDF</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                @include('Theme::layouts.flash-message')
                <div class="table-responsive" id="appendtable">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col hide-graph" id="bp" style="display:none">
    <div class="card">
        <div class="card-body device_box">
            <a href="javascript:void(0)" type="button" class="btn btn-info btn-sm" style="background-color:#27a7de;border:none;" id="btnFullScreen">View in full screen</a>
            <div id="bpcontainer" style="height: 400px; width: 100%;"></div>
        </div>
    </div>
</div>
<br>
<div class="col hide-graph" id="hart" style="display:none">
    <div class="card">
        <div class="card-body device_box">
            <div id="hartcontainer" style="height: 400px; width: 100%;"></div>
        </div>
    </div>
</div>
<br>
<div class="col hide-graph" id="ox" style="display:none">
    <div class="card">
        <div class="card-body device_box">
            <div id="container" style="height: 400px; width: 100%;"></div>
        </div>
    </div>
</div>
<br>
<div class="col hide-graph" id="wt" style="display:none">
    <div class="card">
        <div class="card-body device_box">
            <div id="wtcontainer" style="height: 400px; width: 100%;"></div>
        </div>
    </div>
</div>
<div class="col hide-graph" id="temp" style="display:none">
    <div class="card">
        <div class="card-body device_box">
            <div id="tempcontainer" style="height: 400px; width: 100%;"></div>
        </div>
    </div>
</div>
<div class="col hide-graph" id="gulcose" style="display:none">
    <div class="card">
        <div class="card-body device_box">
            <div id="gulcontainer" style="height: 400px; width: 100%;"></div>
        </div>
    </div>
</div>
<div class="col hide-graph" id="spirometer" style="display:none">
    <div class="card">
        <div class="card-body device_box">
            <div id="spirocontainer" style="height: 400px; width: 100%;"></div>
        </div>
    </div>
</div>
<div id="app">
</div>
@endsection

@section('page-js')
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script src="{{asset('assets/js/tooltip.script.js')}}"></script>
<script src="{{asset('assets/js/jspdf.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/jspdf.plugin.autotable.js')}}"></script>
<script src="{{asset(mix('assets/js/laravel/commonHighchart.js'))}}"></script>
<script type="text/javascript">
    var getDeviceDataList = function(devices = null, patient_id = null, month = null) {
        var i = 1;
        var flag = 0;
        var flag2 = 0;
        var myArr = devices.split(",");
        var columns = [{
            data: 'effdatetime',
            type: 'date-dd-mm-yyyy h:i:s',
            name: 'effdatetime',
            "render": function(value) {
                if (value === null) return "";
                return util.viewsDateFormatWithTime(value);
            }
        }];

        for (i = 0; i < myArr.length; i++) {
			flag2 = 0;
            if (myArr[i] == 1) {
                columns.push({
                    data: null,
                    mRender: function(data, type, full, meta) {
                        wt = full['weight'];
                        if (full['weight'] == null) {
                            wt = '';
                        }
                        if (data != '' && data != 'NULL' && data != undefined) {
                            if (full['wt_alert_status'] == 1) {
                                return "<span style='color:red'>" + wt + '<i class="i-Danger" style="color:red"></i>' + "<span>";
                            } else {
                                return wt;
                            }
                        }
                    },
                    orderable: true
                });
            }
            if (myArr[i] == 2) {
                columns.push({
                    data: null,
                    mRender: function(data, type, full, meta) {
                        ox = full['oxy_qty'];
                        if (full['oxy_qty'] == null) {
                            ox = '';
                        }
                        if (data != '' && data != 'NULL' && data != undefined) {
                            if (full['ox_alert_status'] == 1) {
                                return "<span style='color:red'>" + ox + '<i class="i-Danger" style="color:red"></i>' + "<span>";
                            } else {
                                return ox;
                            }
                        }
                    },
                    orderable: true
                });
            }
            if (myArr[i] == 3) {
                columns.push({
                    data: null,
                    mRender: function(data, type, full, meta) {
                        bp = full['systolic_qty'] + ' / ' + full['diastolic_qty'];
                        if (full['systolic_qty'] == null) {
                            bp = '';
                        }
                        if (data != '' && data != 'NULL' && data != undefined) {
                            if (full['bp_alert_status'] == 1) {
                                return "<span style='color:red'>" + bp + '<i class="i-Danger" style="color:red"></i>' + "<span>";
                            } else {
                                return bp;
                            }
                        }
                    },
                    orderable: true
                });
            }
            if (myArr[i] == 2 || myArr[i] == 3) {
                if (flag2 == 0) {
                    columns.push({
                        data: null,
                        mRender: function(data, type, full, meta) {
                            hr = full['resting_heartrate'];
                            if (full['resting_heartrate'] == null) {
                                hr = '';
                            }
                            if (data != '' && data != 'NULL' && data != undefined) {
                                if (full['hr_alert_status'] == 1) {
                                    return "<span style='color:red'>" + hr + '<i class="i-Danger" style="color:red"></i>' + "<span>";
                                } else {
                                    return hr;
                                }
                            }
                        },
                        orderable: true
                    });
                }
                flag2 = 1;
            }
            if (myArr[i] == 4) {
                columns.push({
                    data: null,
                    mRender: function(data, type, full, meta) {
                        temp = full['bodytemp'];
                        if (full['bodytemp'] == null) {
                            temp = '';
                        }
                        if (data != '' && data != 'NULL' && data != undefined) {
                            if (full['temp_alert_status'] == 1) {
                                return "<span style='color:red'>" + temp + '<i class="i-Danger" style="color:red"></i>' + "<span>";
                            } else {
                                return temp;
                            }
                        }

                    },
                    orderable: true
                });
            }
            if (myArr[i] == 5) {
                columns.push({
                    data: null,
                    mRender: function(data, type, full, meta) {
                        fev = full['fev_value'];
                        if (full['fev_value'] == null) {
                            fev = '';
                        }
                        if (data != '' && data != 'NULL' && data != undefined) {
                            if (full['spt_alert_status'] == 1) {
                                return "<span style='color:red'>" + fev + '<i class="i-Danger" style="color:red"></i>' + "<span>";
                            } else {
                                return fev;
                            }
                        }

                    },
                    orderable: true
                }, {
                    data: null,
                    mRender: function(data, type, full, meta) {
                        pef = full['pef_value'];
                        if (full['pef_value'] == null) {
                            pef = '';
                        }
                        if (data != '' && data != 'NULL' && data != undefined) {
                            if (full['spt_alert_status'] == 1) {
                                return "<span style='color:red'>" + pef + '<i class="i-Danger" style="color:red"></i>' + "<span>";
                            } else {
                                return pef;
                            }
                        }


                    },
                    orderable: true
                });
            }
            if (myArr[i] == 6) {
                columns.push({
                    data: null,
                    mRender: function(data, type, full, meta) {
                        gul = full['value'];
                        if (full['value'] == null) {
                            gul = '';
                        }
                        if (data != '' && data != 'NULL' && data != undefined) {
                            if (full['glc_alert_status'] == 1) {
                                return "<span style='color:red'>" + gul + '<i class="i-Danger" style="color:red"></i>' + "<span>";
                            } else {
                                return gul;
                            }
                        }
                    },
                    orderable: true
                });
            }
        }

        var columns2 = [{
            data: 'effdatetime',
            type: 'date-dd-mm-yyyy h:i:s',
            name: 'effdatetime',
            "render": function(value) {
                if (value === null) return "";
                return util.viewsDateFormatWithTime(value);
            }
        }];

        for (i = 0; i < myArr.length; i++) {
            if (myArr[i] == 1) {
                columns2.push({
                    data: null,
                    mRender: function(data, type, full, meta) {
                        wt = full['weight'];
                        if (full['weight'] == null) {
                            wt = '-';
                        }
                        if (data != '' && data != 'NULL' && data != undefined) {
                            if (full['wt_alert_status'] == 1) {
                                return wt + "-r";
                            } else {
                                return wt;
                            }
                        }
                    },
                    orderable: true
                });
            }
            if (myArr[i] == 2) {
                columns2.push({
                    data: null,
                    mRender: function(data, type, full, meta) {
                        ox = full['oxy_qty'];
                        if (full['oxy_qty'] == null) {
                            ox = '-';
                        }
                        if (data != '' && data != 'NULL' && data != undefined) {
                            if (full['ox_alert_status'] == 1) {
                                return ox + "-r";
                            } else {
                                return ox;
                            }
                        }
                    },
                    orderable: true
                });
            }
            if (myArr[i] == 3) {
                columns2.push({
                    data: null,
                    mRender: function(data, type, full, meta) {
                        bp = full['systolic_qty'] + ' / ' + full['diastolic_qty'];
                        if (full['systolic_qty'] == null) {
                            bp = '-';
                        }
                        if (data != '' && data != 'NULL' && data != undefined) {
                            if (full['bp_alert_status'] == 1) {
                                return bp + "-r";
                            } else {
                                return bp;
                            }
                        }
                    },
                    orderable: true
                });
            }

            if (myArr[i] == 2 || myArr[i] == 3) {
                if (flag == 0) {
                    columns2.push({
                        data: null,
                        mRender: function(data, type, full, meta) {
                            hr = full['resting_heartrate'];
                            if (full['resting_heartrate'] == null) {
                                hr = '-';
                            }
                            if (data != '' && data != 'NULL' && data != undefined) {
                                if (full['hr_alert_status'] == 1) {
                                    return hr + "-r";
                                } else {
                                    return hr;
                                }
                            }
                        },
                        orderable: true
                    });
                }
                flag = 1;
            }

            if (myArr[i] == 4) {
                columns2.push({
                    data: null,
                    mRender: function(data, type, full, meta) {
                        temp = full['bodytemp'];
                        if (full['bodytemp'] == null) {
                            temp = '-';
                        }
                        if (data != '' && data != 'NULL' && data != undefined) {
                            if (full['temp_alert_status'] == 1) {
                                return temp + "-r";
                            } else {
                                return temp;
                            }
                        }

                    },
                    orderable: true
                });
            }
            if (myArr[i] == 5) {
                columns2.push({
                    data: null,
                    mRender: function(data, type, full, meta) {
                        fev = full['fev_value'];
                        if (full['fev_value'] == null) {
                            fev = '-';
                        }
                        if (data != '' && data != 'NULL' && data != undefined) {
                            if (full['spt_alert_status'] == 1) {
                                return fev + "-r";
                            } else {
                                return fev;
                            }
                        }

                    },
                    orderable: true
                }, {
                    data: null,
                    mRender: function(data, type, full, meta) {
                        pef = full['pef_value'];
                        if (full['pef_value'] == null) {
                            pef = '-';
                        }
                        if (data != '' && data != 'NULL' && data != undefined) {
                            if (full['spt_alert_status'] == 1) {
                                return pef + "-r";
                            } else {
                                return pef;
                            }
                        }


                    },
                    orderable: true
                });
            }
            if (myArr[i] == 6) {
                columns2.push({
                    data: null,
                    mRender: function(data, type, full, meta) {
                        gul = full['value'];
                        if (full['value'] == null) {
                            gul = '-';
                        }
                        if (data != '' && data != 'NULL' && data != undefined) {
                            if (full['glc_alert_status'] == 1) {
                                return gul + "-r";
                            } else {
                                return gul;
                            }
                        }
                    },
                    orderable: true
                });
            }
        }

        var url = "/reports/device-data-report/search/" + patient_id + '/' + month + '/' + devices;
        var table1 = util.renderDataTable('Activities-list_1', url, columns, "{{ asset('') }}");
        util.renderDataTable_pdf('Activities-list_2', url, columns2, "{{ asset('') }}");
    }

    let hd_temp_pd = [];
    let store = 0;
    let start = 0;

    function getChartAjax(practice_id, patient_id, month) {
        $.ajax({
            url: '/reports/graphreadingnew/' + practice_id + '/' + patient_id + '/' + month + '/graphchart',
            type: 'GET',
            async: true,
            dataType: "json",
            success: function(data) {
                var ta = JSON.stringify(data.uniArray);
                // console.log(JSON.stringify(data))
                if (data.p_name != null) {
                    $("#hd_p_name").val(JSON.parse(JSON.stringify(data.p_name)));
                    $("#hd_dob").val(JSON.parse(JSON.stringify(data.p_dob)));

                }
                $("#hd_mrn").val(JSON.parse(JSON.stringify(data.mrn.practice_emr)));
                // alert(ta)
                if (ta != '[]') {
                    $("#ox").show();
                    hd_temp_pd.push(2);
                    util.getChartOnclick(data, 'container', 2);
                } else {
                    $("#ox").hide();
                }
                //  getHartChartAjax(patient_id,month);


            }
        });
    }

    function getHartChartAjax(practice_id, patient_id, month) {
        $.ajax({
            url: '/reports/graphreadinghart/' + practice_id + '/' + patient_id + '/' + month + '/graphchart',
            type: 'GET',
            async: true,
            dataType: "json",
            success: function(data) {
                var ta = JSON.stringify(data.uniArray);
                if (data.p_name != null) {
                    $("#hd_p_name").val(JSON.parse(JSON.stringify(data.p_name)));
                    $("#hd_dob").val(JSON.parse(JSON.stringify(data.p_dob)));

                }
                $("#hd_mrn").val(JSON.parse(JSON.stringify(data.mrn.practice_emr)));
                if (ta != '[]') {
                    $("#hart").show();
                    hd_temp_pd.push(0);

                    util.getChartOnclick(data, 'hartcontainer', 0);
                } else {
                    $("#hart").hide();
                }
                //    getBPChartAjax(patient_id,month);


            }
        });
    }

    function getBPChartAjax(practice_id, patient_id, month) {
        $.ajax({
            url: '/reports/graphreadingbp/' + practice_id + '/' + patient_id + '/' + month + '/graphchart',
            type: 'GET',
            async: true,
            dataType: "json",
            success: function(data) {
                var ta = JSON.stringify(data.uniArray);
                if (data.p_name != null) {
                    $("#hd_p_name").val(JSON.parse(JSON.stringify(data.p_name)));
                    $("#hd_dob").val(JSON.parse(JSON.stringify(data.p_dob)));

                }
                $("#hd_mrn").val(JSON.parse(JSON.stringify(data.mrn.practice_emr)));
                if (ta != '[]') {
                    $("#bp").show();
                    hd_temp_pd.push(3);
                    util.getChartOnclick(data, 'bpcontainer', 3);
                } else {


                    $("#bp").hide();
                }
                //    getWtChartAjax(patient_id,month);

            }
        });
    }

    function getWtChartAjax(practice_id, patient_id, month) {
        $.ajax({
            url: '/reports/graphreadingwt/' + practice_id + '/' + patient_id + '/' + month + '/graphchart',
            type: 'GET',
            async: true,
            dataType: "json",
            success: function(data) {
                var ta = JSON.stringify(data.uniArray);
                if (data.p_name != null) {
                    $("#hd_p_name").val(JSON.parse(JSON.stringify(data.p_name)));
                    $("#hd_dob").val(JSON.parse(JSON.stringify(data.p_dob)));

                }
                $("#hd_mrn").val(JSON.parse(JSON.stringify(data.mrn.practice_emr)));
                if (ta != '[]') {
                    $("#wt").show();
                    hd_temp_pd.push(1);
                    util.getChartOnclick(data, 'wtcontainer', 1);
                } else {
                    $("#wt").hide();
                }
                //    getTempChartAjax(patient_id,month);

            }
        });
    }

    function getTempChartAjax(practice_id, patient_id, month) {
        $.ajax({
            url: '/reports/graphreadingtemp/' + practice_id + '/' + patient_id + '/' + month + '/graphchart',
            type: 'GET',
            async: true,
            dataType: "json",
            success: function(data) {
                var ta = JSON.stringify(data.uniArray);
                if (data.p_name != null) {
                    $("#hd_p_name").val(JSON.parse(JSON.stringify(data.p_name)));
                    $("#hd_dob").val(JSON.parse(JSON.stringify(data.p_dob)));

                }
                $("#hd_mrn").val(JSON.parse(JSON.stringify(data.mrn.practice_emr)));
                if (ta != '[]') {
                    $("#temp").show();
                    hd_temp_pd.push(4);
                    util.getChartOnclick(data, 'tempcontainer', 4);
                } else {
                    $("#temp").hide();
                }
                getGulChartAjax(patient_id, month);

            }
        });
    }

    function getGulChartAjax(practice_id, patient_id, month) {
        $.ajax({
            url: '/reports/graphreadinggul/' + practice_id + '/' + patient_id + '/' + month + '/graphchart',
            type: 'GET',
            async: true,
            dataType: "json",
            success: function(data) {
                var ta = JSON.stringify(data.uniArray);
                if (data.p_name != null) {
                    $("#hd_p_name").val(JSON.parse(JSON.stringify(data.p_name)));
                    $("#hd_dob").val(JSON.parse(JSON.stringify(data.p_dob)));

                }
                $("#hd_mrn").val(JSON.parse(JSON.stringify(data.mrn.practice_emr)));
                if (ta != '[]') {
                    $("#gulcose").show();
                    hd_temp_pd.push(6);
                    util.getChartOnclick(data, 'gulcontainer', 6);
                } else {
                    $("#gulcose").hide();
                }
                //   getSpiroChartAjax(patient_id,month);

            }
        });
    }

    function getSpiroChartAjax(practice_id, patient_id, month) {
        $.ajax({
            url: '/reports/graphreadingspiro/' + practice_id + '/' + patient_id + '/' + month + '/graphchart',
            type: 'GET',
            async: true,
            dataType: "json",
            success: function(data) {
                var ta = JSON.stringify(data.uniArray);
                if (data.p_name != null) {
                    $("#hd_p_name").val(JSON.parse(JSON.stringify(data.p_name)));
                    $("#hd_dob").val(JSON.parse(JSON.stringify(data.p_dob)));

                }
                $("#hd_mrn").val(JSON.parse(JSON.stringify(data.mrn.practice_emr)));
                if (ta != '[]') {
                    $("#spirometer").show();
                    hd_temp_pd.push(5);
                    util.getSpirometerChartOnclick(data, 'spirocontainer', 5);

                } else {
                    $("#spirometer").hide();
                }

            }
        });
    }

    $(document).ready(function() {
        var pageModduleId = '<?php echo getPageModuleName(); ?>';
        util.getToDoListData(0, pageModduleId);
        util.getAssignPatientListData(0, 0);
        $(".patient-div").hide();
        $("[name='practices']").on("change", function() {
            $(".patient-div").show();
            if ($(this).val() == '') {
                var practiceId = null;
                util.updatePatientListAssignedDevice(parseInt(practiceId), pageModduleId, $("#patient"));
            } else {
                util.updatePatientListAssignedDevice(parseInt($(this).val()), pageModduleId, $("#patient"));
            }
        });

        function getMonth(date) {
            var month = date.getMonth() + 1;
            return month < 10 ? '0' + month : '' + month; // ('' + month) for string result
        }

        var c_month = (new Date().getMonth() + 1).toString().padStart(2, "0");
        var c_year = new Date().getFullYear();
        var current_MonthYear = c_year + '-' + c_month;
        var fromdate = $("#monthly").val(current_MonthYear);
        var practice_id = $("#practicess").val();
        if (window.location.href.indexOf('device-data-report/') > 0) {
            var getUrl = window.location;
            var patient = getUrl.pathname.split('/')[3];
            $("#hd_pid").val(patient);
            $("#patient").val(patient);
            var fromdate = $("#monthly").val();
            if (patient != '') {
                hd_temp_pd = [];
                store = 0;
                $.ajax({
                    url: '/reports/getassigndevice/' + patient,
                    type: 'GET',
                    async: true,
                    dataType: "json",
                    success: function(data) {
                        if (data.patient_assign_deviceid != "") {
                            $('#hd_device').val(data.patient_assign_deviceid);
                            vitaltable(data.patient_assign_deviceid, practice_id, patient, fromdate)
                        } else {
                            alert("No Data");
                        }
                        getDeviceDataList(data.patient_assign_deviceid, patient, fromdate);
                    }
                });
            }
        }
    });

    $('#ddsearch').click(function() {
        var patient = $('#patient').val();
        var fromdate = $('#monthly').val();
        var practice_id = $('#practices').val();
        $('#hd_device').val('');
        $('#hd_pid').val(patient);
        if (practice_id == '') {
            alert('select practice.');
        } else if (patient == '') {
            alert('select patient.');
        } else if (fromdate == '') {
            alert('select month.');
        } else {
            hd_temp_pd = [];
            store = 0;
            $.ajax({
                url: '/reports/getassigndevice/' + patient,
                type: 'GET',
                async: true,
                dataType: "json",
                success: function(data) {
                    if (data.patient_assign_deviceid != "") {
                        $('#hd_device').val(data.patient_assign_deviceid);
                        vitaltable(data.patient_assign_deviceid, practice_id, patient, fromdate);
                    } else {
                        alert("No Data");
                    }
                }
            });
        }
    });

    function vitaltable(devices, practice_id, patient, fromdate) {
        $('#appendtable').empty();
        $('#appendtable').append(' <table id="Activities-list_1" class="display table table-striped table-bordered" style="width:100%" ><thead id="vitaltableheader1"></thead><tbody id="vitalstablebody1"></tbody> </table>');
        $('#appendtable').append('<table id="Activities-list_2" class="display table table-striped table-bordered" style="width:100%; display:none" ><thead id="vitaltableheader2"></thead><tbody id="vitalstablebody2"></tbody> </table>');
        var i = 1;
        var flag = 0;
        var myArr = devices.split(",");
        var head = "<tr><th>Timestamp</th>";
        for (i = 0; i < myArr.length; i++) {
            if (myArr[i] == 1) {
                head += "<th>WT</th>";
                getWtChartAjax(practice_id, patient, fromdate);
            }
            if (myArr[i] == 2) {
                head += "<th>O2</th>";
                getChartAjax(practice_id, patient, fromdate);
            }
            if (myArr[i] == 3) {
                head += "<th>BP</th>";
                getBPChartAjax(practice_id, patient, fromdate);
            }
            if (myArr[i] == 2 || myArr[i] == 3) {
                if (flag == 0) {
                    head += "<th>HR</th>";
                    getHartChartAjax(practice_id, patient, fromdate);
                }
                flag = 1;
            }
            if (myArr[i] == 4) {
                head += "<th>Temp</th>";
                getTempChartAjax(practice_id, patient, fromdate);
            }
            if (myArr[i] == 5) {
                head += "<th>FEV1</th><th>PEF</th>";
                getSpiroChartAjax(practice_id, patient, fromdate);
            }
            if (myArr[i] == 6) {
                head += "<th>Glucose</th>";
                getGulChartAjax(practice_id, patient, fromdate);
            }
        }
        head += "</tr>";
        $('#vitaltableheader1').html(head);
        $('#vitaltableheader2').html(head);
        getDeviceDataList(devices, patient, fromdate);
    }
    $('#btn').click(function() {
        var chk_practices = $('#practices').val();
        if (window.location.href.indexOf('device-data-report/') > 0) {
            var chk_patient = $('#hd_pid').val();
            var chk_prac = 1;
        } else {
            var chk_patient = $('#patient').val();
            var chk_prac = chk_practices;
        }
        if (chk_prac != "" && chk_patient != "") {
            window.jsPDF = window.jspdf.jsPDF;
            var charts = Highcharts.charts,
                doc = new jsPDF('p', 'pt', 'a4', true),

                pageHeight = doc.internal.pageSize.height,
                counter = 0,
                promises = [],
                yDocPos = 0,
                k = 0,
                i,
                j;

            var patient_ids = $("#hd_pid").val();
            var hd_p_name = $("#hd_p_name").val();
            var hd_dob = $("#hd_dob").val();
            var hd_mrn = $("#hd_mrn").val();
            var date_month = $('#monthly').val();
            var get_m_year = date_month.substring(0, 4);
            var get_m_month = date_month.slice(-2);

            var last_date = new Date(get_m_year, get_m_month, 0);
            var last_dt = last_date.getDate();
            var cur_month = (new Date().getMonth() + 1);
            var cur_year = new Date().getFullYear();
            var cur_day = new Date().getDate();
            var cur_MonthYear = cur_month + '-' + cur_day + '-' + cur_year;
            var last = new Date(cur_year, cur_month, 0);
            var lastDay = last.getDate();

            var dt = new Date();
            var h = dt.getHours(),
                m = dt.getMinutes();
            var mm;
            (m < 10) ? mm = "0" + m: mm = m;
            var hh;
            (h < 10) ? hh = "0" + h: hh = h;

            var times = (hh > 12) ? (hh - 12 + ':' + mm + ' PM') : (hh + ':' + mm + ' AM');

            doc.setDrawColor(0);
            doc.setFillColor(39, 168, 222);
            doc.rect(10, 10, 575, 45, 'F');

            doc.setTextColor(255, 255, 255);
            doc.setFont("helvetica");
            doc.setFont(undefined, "bold");
            doc.text(236, 37, 'Device Data Report');

            doc.setTextColor(0);
            doc.setFontSize(12)
            doc.setFont(undefined, "normal");
            doc.text(10, 80, hd_p_name);
            doc.setFontSize(10)
            doc.text(10, 93, 'DOB: ' + hd_dob);
            doc.text(100, 93, 'MRN: ' + hd_mrn);

            doc.text(10, 104, 'Generated on: ' + cur_MonthYear + " " + times);
            if (window.location.href.indexOf('device-data-report/') > 0) {
                doc.text(10, 116, 'Data from : ' + cur_month + "-01-" + cur_year + " to " + cur_month + "-" + lastDay + '-' + cur_year);
            } else {
                doc.text(10, 116, 'Data from : ' + get_m_month + "-01-" + get_m_year + " to " + get_m_month + "-" + last_dt + '-' + get_m_year);
            }

            // var z = 0;
            var toppdf = 117;
            var tblcolumn = 0;
            var data = tableToJson($('#Activities-list_2').get(0));
            // z = data.length;
            doc.autoTable({
                theme: 'grid',
                html: '#Activities-list_2',
                startY: 130,
                headStyles: {
                    fillColor: [39, 168, 222]
                },
                didParseCell: function(data) {
                    if (data.section === 'body' && data.cell.text[0].includes('-r')) {
                        data.cell.styles.textColor = "red";
                        data.cell.styles.fontStyle = 'bold';
                        data.cell.text[0] = data.cell.text[0].replace('-r', '');
                    }
                }
            });

            Highcharts.downloadURL = function(dataURL, filename) {
                if (dataURL.length > 2000000) {
                    dataURL = Highcharts.dataURLtoBlob(dataURL);
                    if (!dataURL) {
                        throw 'Data URL length limit reached';
                    }
                }
                promises.push(dataURL);
                counter++;
            };

            start = hd_temp_pd.length;
            if (store == hd_temp_pd.length) {
                var st = 0;
            } else {
                var st = store;
            }
            for (i = st; i < hd_temp_pd.length; i++) {

                if (hd_temp_pd[i] == 1) {
                    $('#wtcontainer').highcharts().exportChartLocal('image/png+xml');
                }
                if (hd_temp_pd[i] == 2) {
                    $('#container').highcharts().exportChartLocal('image/png+xml');
                }
                if (hd_temp_pd[i] == 3) {
                    $('#bpcontainer').highcharts().exportChartLocal('image/png+xml');
                }
                if (hd_temp_pd[i] == 0) {
                    $('#hartcontainer').highcharts().exportChartLocal('image/png+xml');
                }
                if (hd_temp_pd[i] == 4) {
                    $('#tempcontainer').highcharts().exportChartLocal('image/png+xml');
                }
                if (hd_temp_pd[i] == 6) {
                    $('#gulcontainer').highcharts().exportChartLocal('image/png+xml');
                }
                if (hd_temp_pd[i] == 5) {
                    $('#spirocontainer').highcharts().exportChartLocal('image/png+xml');
                }
            }
            if (hd_temp_pd.length > 0) {
                store = hd_temp_pd.length;
            } else {
                store = 0;
            }
            var addGraphHight = 0;
            var interval = setInterval(function() {
                clearInterval(interval);
                promises.forEach(function(img, index) {
                    // var remain_page_hight = pageHeight - [(z * 20) + 170 + addGraphHight + k * 140];
                    // if (yDocPos > remain_page_hight) {
                    //     alert('if');
                    //     doc.addPage();
                    //     yDocPos = 40;
                    //     k = 0;
                    //     z = 0;
                    // } else {
                    //     if (index == 0) {
                    //         alert('else -if');
                    //         yDocPos = (z * 20) + 170 + k * 140;
                    //         addGraphHight += 440;
                    //     } else {
                    //         alert('else-else');
                    //         yDocPos += 440;
                    //     } 
                    // }
                    doc.addPage(); //added when above code cmnt
                    yDocPos += 120; //added when above code cmnt
                    var top = yDocPos - 20;
                    doc.setDrawColor(0);
                    doc.setFillColor(242, 244, 244);
                    doc.rect(10, top, 575, 340, 'F');
                    doc.addImage(img, 'PNG', 25, yDocPos, 540, 300);
                    k++;
                });
                doc.save(hd_p_name + "-" + patient_ids + "-" + date_month + '.pdf');
            }, 100);
        } else {
            alert("Please select proper details");
        }
    });

    function tableToJson(table) {
        var data = [];
        // first row needs to be headers
        var headers = [];
        for (var i = 0; i < table.rows[0].cells.length; i++) {
            headers[i] = table.rows[0].cells[i].innerHTML.toLowerCase().replace(/ /gi, '');
        }
        // go through cells  
        for (var i = 0; i < table.rows.length; i++) {
            var tableRow = table.rows[i];
            var rowData = {};
            for (var j = 0; j < tableRow.cells.length; j++) {
                rowData[headers[j]] = tableRow.cells[j].innerHTML;
            }
            data.push(rowData);
        }
        return data;
    }
</script>
@endsection