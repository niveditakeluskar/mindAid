@extends('Theme::layouts_2.to-do-master')
@section('page-css')
    
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Manually Adjust Timer Report</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
@include('Reports::filter')
 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-rightleft">
            <div class="card-body">
                <div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>Call data saved successfully! </strong><span id="text"></span>
               </div>
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="patient-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="15px">Sr No.</th>
                            <th width="20px">EMR/EHR ID</th>
                            <th width="50px">Patient Name</th>
                            <th width="15px">DOB</th>
                            <!-- <th width="">Number </th> -->
                            <th width="15px">Date of service</th>
                            <th width="30px">Conditions</th>
                            <th width="20px">Provider</th>
                            <th width="20px">Practice</th>
                            <th width="20px">Total time</th>
                            <th width="10px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="manually_adjust_time_modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route("manually.adjust.time") }}" method="POST" id="manually_adjust_time_form" name="manually_adjust_time_form" class="form-horizontal">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading1">Manually Adjust Time</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    {{ csrf_field() }}
                    
                    <input type="hidden" name="id" id="id">
                    @hidden("start_time", ["id" => "start_time"])
                    <input type="hidden" name="curmonth" id='curmonth'>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12 form-row">
                                <div class="col-md-3">
                                    <label>UID: </label>
                                    <span class="uid"></span>
                                </div>
                                <div class="col-md-5">
                                    <label>Name: </label>
                                    <span class="name"></span>
                                </div>
                                <div class="col-md-4">
                                    <label>Net Time: </label>
                                    <span class="net_time"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group mb-3">
                            <div class="col-md-12 forms-element">
                                <label for="care_manager_id"><span class="error">*</span> Care Manager</label>
                                @selectcaremanager("care_manager_id", ["id" => "care_manager_id", "placeholder" => "Select Care Manager"])
                            </div>
                            <div class="invalid-feedback ml-2"></div> 
                        </div>
                        <div class="">
                            <label for="time_to"><span class="error">*</span> Time to</label><br />
                            <div class="form-row forms-element">
                                <label class="radio radio-primary col-md-4 float-left">
                                <input type="radio" name="time_to" value="1" formControlName="radio">
                                <span>Increase</span>
                                <span class="checkmark"></span>
                                </label>
                                <label class="radio radio-primary col-md-4 float-left">
                                <input type="radio" name="time_to" value="0" formControlName="radio">
                                <span>Decrease</span>
                                <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="form-row invalid-feedback"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group mb-3 ">
                                <label for="time"><span class="error">*</span> Time</label>
                                @text("time", ["id" => "time", "placeholder" => "Enter time(hh:mm:ss)"])
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group mb-3 ">
                                <label for="module">Module</label>
                                @hidden("module_id", ["id" => "module_id"])
                                @text("module", ["id" => "module", "disabled" => "disabled"])
                            </div>
                        </div>
                        <div class="row form-group mb-3"> 
                            <div class="col-md-12 forms-element">
                                <label for="submodule_id" class=""><span class="error">*</span> Sub Module</label>
                                @select("Sub Module", "submodule_id", [], ["id" => "submodule_id", "class"=>"sub-module"])
                            </div>
                            <div class="invalid-feedback ml-2"></div>   
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group mb-3 ">
                                <label for="comment"><span class="error">*</span> Comment</label>
                                <textarea name="comment" class="form-control forms-element" id="comment"></textarea>
                                <div class="form-row invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit" class="btn  btn-primary m-1" id="save_time">Save Changes</button>
                                <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
    <script type="text/javascript">
        var getMonthlyPatientList = function(practice = null,provider=null,modules=null,monthly=null) {
            var columns =  [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: null, mRender: function(data, type, full, meta){
                    practice_emr = full['practice_emr'];
                        if(full['practice_emr'] == null){
                            practice_emr = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return practice_emr;
                        }
                    },
                    orderable: true
                }, 
                {data:null , mRender: function(data, type, full, meta){
                    m_Name = full['mname'];
                    if(full['mname'] == null){
                        m_Name = '';
                    }
                    if(data!='' && data!='NULL' && data!=undefined){
                        if(full['profile_img']=='' || full['profile_img']==null) {
                            return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' <span id="name_'+full["id"]+'">'+full['fname']+' '+m_Name+' '+full['lname']+'</span>';
                            // return ["<a href='/ccm/monthly-monitoring/"+full['id']+"'><img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname']+'</a>';
                        } else {
                            return ["<img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' <span id="name_'+full["id"]+'">'+full['fname']+' '+m_Name+' '+full['lname']+'</span>';
                            // return ["<a href='/ccm/monthly-monitoring/"+full['id']+"'><img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname']+'</a>';
                        }
                        // return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                    }
                },
                orderable: true
                },
                {data:null,
                    mRender: function(data, type, full, meta){
                        dob = full['dob'];
                        if(full['dob'] == null){
                            dob = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                             return util.viewsDateFormat(dob);
                        }
                    },
                    orderable: true
                },
                {data: 'rec_date',type: 'date-dd-mm-yyyy', name: 'rec_date',"render":function (value) {
                    if (value === null) return "";
                        return util.viewsDateFormat(value);
                    }
                },
                // {data: 'mob', name: 'mob',
                //     mRender: function(data, type, full, meta){
                //         home_number = ' | ' + full['home_number'];
                //         if(full['home_number'] == null){
                //             home_number = '';
                //         }
                //         if(data!='' && data!='NULL' && data!=undefined){
                //             return full['mob'] + home_number;
                //         }else{ return ''; }
                //     },
                //     orderable: false
                // },
                
                {data:null, mRender: function(data, type, full, meta){
                        condition = full['condition'];
                        if(full['condition'] == null){
                            condition = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return condition;
                        }
                    },
                    orderable: true
                },
                {data:null, mRender: function(data, type, full, meta){
                        name = full['name'];
                        if(full['name'] == null){
                            name = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return name;
                        }
                    },
                    orderable: true
                },
                {data:null, mRender: function(data, type, full, meta){
                        pract_name = full['pract_name'];
                        if(full['pract_name'] == null){
                            pract_name = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return pract_name;
                        }
                    },
                    orderable: true
                },
                {data: 'totaltime', name: 'totaltime',
                    mRender: function(data, type, full, meta){
                        if(data!='' && data!='NULL' && data!=undefined){
                            return '<span id="'+full["id"]+'">'+full["totaltime"]+'</span>';
                        }else{ return ''; }
                    },
                    orderable: true
                },

                {data: 'action', name: 'action', orderable: false, searchable: false}
                ]; 
                if(practice=='')
                {
                    practice=null;
                }
                if(provider=='')
                {
                    provider=null;
                }
                if(monthly=='')
                {
                    monthly=null;
                }
                 var url = "/reports/monthly-report/search/"+practice+"/"+provider+"/"+modules+"/"+monthly;      
                 console.log(url);          
                var table1 = util.renderDataTable('patient-list', url, columns, "{{ asset('') }}"); 
        }
        $(document).ready(function() {
          //  report.init();
            getMonthlyPatientList();
            util.getToDoListData(0, {{getPageModuleName()}});
            // $(".patient-div").hide(); // to hide patient search select

            $("[name='practices']").on("change", function () {
                 util.updatePhysicianList(parseInt($(this).val()), $("#physician"))
            });

            $("[name='physician']").on("change", function () {
                 // util.updatePhysicianList(parseInt($(this).val()), $("#physician"))
            });

            util.updateSubModuleList(parseInt(3), $("#sub_module"));
            $("[name='modules']").val(3).attr("selected", "selected").change();
            
            // $("[name='modules']").on("change", function () {
            //     // util.updatePatientList(parseInt($(this).val()), {{ getPageModuleName() }}, $("#patient"));
            // });
             



            $("[name='monthly']").on("change", function (){    
            });

            // .css({"opacity":"0.6", "cursor":"not-allowed", "pointer-events":"none"});
            $("form[name='manually_adjust_time_form']").submit(function (e) {
                e.preventDefault();
                if($("form[name='manually_adjust_time_form'] #submodule_id").val() == "Select Sub Module") { // condition added by pranali on 30Oct2020
                    $("form[name='manually_adjust_time_form'] #submodule_id").val("");
                }
                form.ajaxSubmit("manually_adjust_time_form", onSaveManuallyAdjustTimeForm);
            });
            $('#time').removeClass("is-invalid");
            $('#time').next(".invalid-feedback").html("");
            $('input[name="time_to"]').removeClass("is-invalid");
            $('input[name="time_to"]').next(".invalid-feedback").html("");
        });

        $("#month-search").click(function(){
            var practice = $('#practices').val();
            var provider = $('#physician').val();
            var modules = $('#modules').val();
            var monthly = $('#monthly').val();
            getMonthlyPatientList(practice,provider,modules,monthly);  
        });

               $("[name='practicesgrp']").on("change", function () { 
                alert("test");
                var practicegrp_id = $(this).val(); 
                alert(practicegrp_id);
                if(practicegrp_id==0){
                   
                }
                if(practicegrp_id!=''){
                    util.getPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#practices")); 
                }
                else{
                  
                     util.updatePracticeListWithoutOther(001, $("#practices"))   
                }   
            });


        var onSaveManuallyAdjustTimeForm = function (formObj, fields, response) {
            if (response.status == 200) {
                var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Time saved successfully! </strong><span id="text"></span>';
                showSubmitMsg(text, 'success');
            } else {
                var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Time not saved successfully! </strong><span id="text"></span>';
                showSubmitMsg(text, 'danger');
            }
        };

        var showSubmitMsg = function (alertMsg, alertType) {
            if (alertType == "success") {
                $("#success-alert .alert").addClass("alert-success");
                $("#success-alert .alert").removeClass("alert-danger");
                $('#manually_adjust_time_modal').modal('hide');
                var practice = $('#practices').val();
                var provider = $('#physician').val();
                var modules = $('#modules').val();
                var monthly = $('#monthly').val();
                getMonthlyPatientList(practice,provider,modules,monthly); 
            } else if (alertType == "danger") {
                $("#success-alert .alert").addClass("alert-danger");
                $("#success-alert .alert").removeClass("alert-success");
            }

            $("#success-alert .alert").html(alertMsg);
            $("#success-alert .alert").show();
            var scrollPos = $(".main-content").offset().top;
            $(window).scrollTop(scrollPos);
        }
        
        $('body').on('click', '.manually_adjust_time', function () {
            var monthly = $('#monthly').val();
            var currentYear = (new Date).getFullYear();
            var currentMonth = ((new Date).getMonth() + 1).toString().replace(/(^.$)/,"0$1");
            var premonth= (currentMonth-1);
            if((monthly == "" ) || (monthly == currentYear + '-' +currentMonth)){
                $("#curmonth").val(0);
            }else{
                $("#curmonth").val(1);
            }
            if((monthly == "" ) || (monthly == currentYear + '-' +currentMonth) || (monthly == currentYear + '-' +premonth)) {
                $('form#manually_adjust_time_form').trigger("reset");
                $('form#manually_adjust_time_form select').trigger("change");
                $('#id').val("");
                $('#start_time').val("");
                $('.uid').text("");
                $('.name').text("");
                $('.net_time').text("");
                var id = $(this).data('id');
                var module_id = $("#modules option:selected").val();
                var net_time= $('#'+id).text();
                $('.net_time').text(net_time); 
                $('#start_time').val(net_time);
                // util.getPatientNetTimeBasedOnModule(id, module_id, '#start_time')//$('#'+id).text();
                var name = $('#name_'+id).text();
                $('#id').val(id);
                $('.uid').text(id);
                $('.name').text(name);
                $('#module_id').val(module_id);
                $('#module').val($("#modules option:selected").text());
                util.updateSubModuleList(parseInt(module_id), $(".sub-module"));
                $('#manually_adjust_time_modal').modal('show');
                $("#save_time").prop('disabled', true);
            } else {
                alert("You can update only current month time spent.");
            }
        });
        
        $('body').on('change', '#time,input[type=radio][name=time_to]', function () {
            // alert($(this).val());
            var time = $("#time").val();
            var time_to = $("input[name='time_to']:checked").val();
            var time_regex = new RegExp(/^(((([0-1][0-9])|(2[0-3])):?[0-5][0-9]:?[0-5][0-9]+$))/g);
            if(time_regex.test(time)){
                if(time_to != undefined){
                    if(time_to == 0){
                        var start_time = $('#start_time').val();
                        var regExp = /(\d{1,2})\:(\d{1,2})\:(\d{1,2})/;
                        if(parseInt(time .replace(regExp, "$1$2$3")) <= parseInt(start_time .replace(regExp, "$1$2$3"))){
                            // alert("End time is greater");
                            $('#time').removeClass("is-invalid");
                            $('#time').next(".invalid-feedback").html("");
                            $("#save_time").prop('disabled', false);
                        } else {
                            // alert("End time is lesser");
                            $('#time').addClass("is-invalid");
                            $('#time').next(".invalid-feedback").html("You can not decrease the time, greater than current time.");
                            $("#save_time").prop('disabled', true);
                        }
                    } else {
                        $('#time').removeClass("is-invalid");
                        $('#time').next(".invalid-feedback").html("");
                        $("#save_time").prop('disabled', false);
                    }
                } else {
                    // alert("please select time to field");
                    $('input[name="time_to"]').addClass("is-invalid");
                    $('input[name="time_to"]').next(".invalid-feedback").html("The time to field is required.");
                    $("#save_time").prop('disabled', true);
                }
            } else {
                $('#time').addClass("is-invalid");
                $('#time').next(".invalid-feedback").html("Please enter time in HH:MM:SS formate.");
                $("#save_time").prop('disabled', true);
            }
        });
        $('table').on('draw.dt', function() {
            $('[data-toggle="tooltip"]').tooltip();
        }); 
    </script>
@endsection