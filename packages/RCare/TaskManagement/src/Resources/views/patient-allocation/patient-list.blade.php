@extends('Theme::layouts_2.to-do-master') 

@section('page-css') 
    <!-- <link rel="stylesheet" href="{{-- asset('assets/styles/vendor/datatables.min.css') --}}"> -->
    
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Patient CCM Assignment</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<form id="user_patients_form" name="user_patients_form" action="{{ route("task.management.user") }}" method="post" > 
<div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>Patient assigned successfully! </strong><span id="text"></span>
               </div>
   @csrf
@include('TaskManagement::patient-allocation.components.filter-practice-patient')
 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left"> 
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
                            <th width="">Address </th>
                            <th width="">Last Modified by</th>
                            <th width="10px">Select</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
        <div class="card-footer">
               <div class="mc-footer">
                  <div class="row"> 
                     <div class="col-lg-12 text-right">
                        <button type="submit" class="btn btn-primary m-1 save_user_patients" id="user_patients_save">Save</button>
                     </div>
                  </div>
               </div>
            </div>
    </div>
</div>
</form>
<div id="app">
</div>
@endsection

@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    <script src="{{asset(mix('assets/js/laravel/taskManage.js'))}}"></script>
    <script type="text/javascript">
        var getPatientList = function(practice_id = null,caremanager_id = null) {
            console.log(practice_id+'pp'+caremanager_id +'abc');
            var columns =  [
                                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                                {data: null, mRender: function(data, type, full, meta){
                                    m_Name = full['mname'];
                                    if(full['mname'] == null){
                                        m_Name = '';
                                    } 
                                    if(data!='' && data!='NULL' && data!=undefined){
                                        if(full['profile_img']=='' || full['profile_img']==null) {
                                           // return ["<a href='/ccm/monthly-monitoring/"+full['id']+"'><img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname']+'</a>';
                                        return  ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                        } else {
                                            return ["<img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                           // return ["<a href='/ccm/monthly-monitoring/"+full['id']+"'><img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname']+'</a>';
                                        }
                                        // return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                    }
                                }, 
                                orderable: false
                                },
                                {data: 'dob', type: 'date-dd-mmm-yyyy', name: 'dob', "render":function (value) {
                                    if (value === null) return "";
                                        // return util.viewsDateFormat(value);
                                        return moment(value).format('MM-DD-YYYY');
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
                                {data: 'add_1', name: 'add_1',
                                    mRender: function(data, type, full, meta){
                                        add_2 = ' | ' + full['add_2'];
                                        if(full['add_2'] == null){
                                            add_2 = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return full['add_1'] + add_2;
                                        }
                                    },
                                    orderable: false
                                },

                                {data: 'f_name', name: 'f_name',
                                    mRender: function(data, type, full, meta){
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return full['f_name']+' '+full['l_name'];
                                        }else { return ""; }
                                    },
                                    orderable: false
                                },

                                {data: 'action', name: 'action', orderable: false, searchable: false}
                            ]
            if(practice_id == null) {
                var table = util.renderDataTable('patient-list', "{{ route('patients') }}", columns, "{{ asset('') }}");
            }else if(practice_id!='' && caremanager_id==''){
                 var url = "/task-management/patients-search/"+practice_id+"/"+caremanager_id+"/patient_search";
                 console.log(url + "else if");
                 var table1 = util.renderDataTable('patient-list', url, columns, "{{ asset('') }}");
            }
            else{
                var url = "/task-management/patients-search/"+practice_id+"/"+caremanager_id+"/patient_search";
                console.log(url + "else");
                var table1 = util.renderDataTable('patient-list', url, columns, "{{ asset('') }}");
            }  
        }
        $(document).ready(function() {
            taskManage.init();
            $(':input[type="submit"]').prop('disabled', true);
            //getPatientList(); //for hide the general listing of the table 
           // util.getToDoListData(0, {{getPageModuleName()}});
            $(".patient-div").hide(); // to hide patient search select

            $("[name='practices']").on("change", function () {
                var practice_id = $(this).val();
                $(".patient-div").show();
                if($(this).val()==''){ 
                    // getPatientList();
                    // util.updateCaremanagerPatientList(parseInt($(this).val()), $("#caremanager"));
                }
                else
                {   
                    //getPatientList(practice_id,null);
                    util.updateCaremanagerList(parseInt($(this).val()), $("#caremanager"));
                }
            });

            $("[name='caremanager']").on("change", function () { 
                var practice_id = $("#practices option:selected").val();
                var caremanager_id = $(this).val();
                console.log(practice_id +"practice");
                console.log(caremanager_id +"caremanager");
                if($(this).val()==''){
                //getPatientList();
            }
            else
            {
                 getPatientList(practice_id,caremanager_id);
                 $(':input[type="submit"]').prop('disabled', false);
            }
            });
        });
        $('table').on('draw.dt', function() {
            $('[data-toggle="tooltip"]').tooltip();
        }); 
    </script>
@endsection