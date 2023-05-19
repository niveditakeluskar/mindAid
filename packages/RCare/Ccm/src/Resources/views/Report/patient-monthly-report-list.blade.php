@extends('Theme::layouts_2.to-do-master')
@section('page-css')
    <!-- <link rel="stylesheet" href="{{-- asset('assets/styles/vendor/datatables.min.css') --}}"> -->
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Manually Adjust Timer</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
@include('Ccm::Report.filter')
 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-rightleft">
            <div class="card-body">
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="patient-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="35px">Sr No.</th>
                            <th width="">Patient</th>
                            <th width="">DOB</th>
                            <th width="">Number </th>
                            <th>Total time</th>
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
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12 form-group mb-3 ">
                                <label for="rolename"><span class="error">*</span> Care Manager</label>
                                @selectcaremanager("care_manager_id", ["id" => "care_manager_id", "placeholder" => "Select Care Manager"])
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group mb-3 ">
                                <label for="rolename"><span class="error">*</span> Time</label>
                                @text("time", ["id" => "time", "placeholder" => "Enter time(hh:mm:ss)"])
                            </div>
                        </div>
                        <div class="">
                            <label for="Continue this call?"><span class="error">*</span> Time to</label><br />
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
                                <label for="rolename">Module</label>
                                @hidden("module_id", ["id" => "module_id"])
                                @text("module", ["id" => "module", "disabled" => "disabled"])
                                @hidden("form_name", ["id" => "form_name", "value" => "manually_adjust_time_patient_monthly_report_form"])
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group mb-3">
                                <label for="component_id" class=""><span class="error">*</span> Sub Module</label>
                                @select("Sub Module", "component_id", [], ["id" => "component_id", "class"=>"sub-module"])
                            </div>   
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group mb-3 ">
                                <label for="rolename"><span class="error">*</span> Comment</label>
                                <textarea name="comment" class="form-control" id="comment"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                    <div class="row">
                    <div class="col-lg-12 text-right">
                    <button type="submit"  class="btn  btn-primary m-1">Save Changes</button>
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
    <script type="text/javascript">
        var getMonthlyPatientList = function(patient_id = null) {
            var columns =  [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'fname',name: 'fname', mRender: function(data, type, full, meta){
                    m_Name = full['mname'];
                    if(full['mname'] == null){
                        m_Name = '';
                    }
                    if(data!='' && data!='NULL' && data!=undefined){
                        if(full['profile_img']=='' || full['profile_img']==null) {
                            return ["<a href='/ccm/monthly-monitoring/"+full['id']+"'><img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname']+'</a>';
                        } else {
                            return ["<a href='/ccm/monthly-monitoring/"+full['id']+"'><img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname']+'</a>';
                        }
                        // return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                    }
                },
                orderable: false
                },
                {data: 'dob', type: 'date-dd-mmm-yyyy', name: 'dob', "render":function (value) {
                    if (value === null) return "";
                        return util.viewsDateFormat(value);
                    }
                },
                {data: 'mob', name: 'mob',
                    mRender: function(data, type, full, meta){
                        home_number = ' | ' + full['home_number'];
                        if(full['home_number'] == null){
                            home_number = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return full['mob'] + home_number;
                        }
                    },
                    orderable: false
                },

                {data: 'sum', name: 'sum',
                    mRender: function(data, type, full, meta){
                        if(data!='' && data!='NULL' && data!=undefined){
                            return full['sum'];
                        }else{ return '';}
                    },
                    orderable: false
                },

                {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            if (patient_id == null) {
                        var table = util.renderDataTable('patient-list', "{{ route('monthly.report.patients') }}", columns, "{{ asset('') }}");
            } else {
                var url = '{{ route("monthly.report.patients.search", ":id") }}';
                url = url.replace(':id', patient_id);
                // console.log(url);
                var table1 = util.renderDataTable('patient-list', url, columns, "{{ asset('') }}");
            }  
        }
        $(document).ready(function() {
            report.init();
            util.getToDoListData(0, {{getPageModuleName()}});
            // $(".patient-div").hide(); // to hide patient search select

            $("[name='practices']").on("change", function () {
                 util.updatePhysicianList(parseInt($(this).val()), $("#physician"))
            });

            $("[name='physician']").on("change", function () {
                 // util.updatePhysicianList(parseInt($(this).val()), $("#physician"))
            });

            $("[name='modules']").val(2).attr("selected", "selected").change();
            
            $("[name='modules']").on("change", function () {
                // util.updatePatientList(parseInt($(this).val()), {{ getPageModuleName() }}, $("#patient"));
            });

            $("[name='monthly']").on("change", function () {
                // var practices = $("#practices").val();
                // alert(practices);
                // var physician = $("#physician").val();
                // alert(physician);
                // var module =$("#modules").val();    
                // alert(module);
                // // getPatientList($(this).val());

                 // var store = $(this).val(); 
                // alert(store);
                // var [before, after] = store.split("-");
                // alert(before);
                // alert(after);
               // getPatientList(store);   
            });
            $("form[name='manually_adjust_time_form']").submit(function (e) {
                e.preventDefault();
                form.ajaxSubmit("manually_adjust_time_form", onSaveManuallyAdjustTimeForm);
            });
        });
        
        // $("#month-search").click(function(){
        //     alert('hey');
        //     var practice_id = $("#practice_id").val();
        //     var physician_id = $("#physician_id").val();
        //     var module_id = $("#module_id").val();
        //     $.ajax({
        //         type: 'post',
        //         data: {
        //             practice_id: practice_id,
        //             physician_id: physician_id,
        //             module_id: module_id
        //         },
        //         url:"/ccm/monthly.report.patients.search/?practice_id="+practice_id+"/"+"&physician_id="+physician_id+"/"+"&module_id="+module_id,

        //         success: function(data)
        //         {
        //             alert(data); // show response from the php script.
        //         }
        //     });
        // });

        var onSaveManuallyAdjustTimeForm = function (formObj, fields, response) {
            if (response.status == 200) {
                var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Time saved successfully! </strong><span id="text"></span>';
                showSubmitMsg('manually_adjust_time_form', text, 'success');
            } else {
                var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Time not saved successfully! </strong><span id="text"></span>';
                showSubmitMsg('manually_adjust_time_form', text, 'danger');
            }
        };

        var showSubmitMsg = function (formName, alertMsg, alertType) {
            if (alertType == "success") {
                $("form[name='text_form'] .alert").addClass("alert-success");
                $("form[name='text_form'] .alert").removeClass("alert-danger");
            } else if (alertType == "danger") {
                $("form[name='text_form'] .alert").addClass("alert-danger");
                $("form[name='text_form'] .alert").removeClass("alert-success");
            }

            $("form[name='" + formName + "'] .alert").html(alertMsg);
            $("form[name='" + formName + "'] .alert").show();
            var scrollPos = $(".main-content").offset().top;
            $(window).scrollTop(scrollPos);
        }

        $('body').on('click', '.manually_adjust_time', function () {
            $('form#manually_adjust_time_form').trigger("reset");
            $('form#manually_adjust_time_form select').trigger("change");
            $('#id').val("");
            var id = $(this).data('id');
            var module_id = $("#modules option:selected").val();
            $('#id').val(id);
            $('#module_id').val(module_id);
            $('#module').val($("#modules option:selected").text());
            util.updateSubModuleList(parseInt(module_id), $(".sub-module"));
            $('#manually_adjust_time_modal').modal('show');
        });
    </script>
@endsection