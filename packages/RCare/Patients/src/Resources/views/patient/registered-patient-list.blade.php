@extends('Theme::layouts.master')
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/sass/css/bootstrap-select.scss')}}">
@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
		<div class="col-md-11">
		   <h4 class="card-title mb-3">Patients</h4>
		</div>
		 <div class="col-md-1">
		 <!-- <a class="" href="javascript:void(0)" id="addUser"><i class="add-icons i-Administrator" data-toggle="tooltip" data-placement="top" title="Add User"></i><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add User"></i></a>   -->
		</div>
</div>
   <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
@include('Rpm::patient.filter-practice-patient')
<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
           <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="registered-patient-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th >Sr No.</th>
                            <th style="width:200px">Patient</th>
                            <th >DOB</th>
                            <th style="width:200px">Email</th>
                            <th >Phone No. </th>
                            <th style="width:150px">Enrolled Services</th>
                            <th >Communication Vehicle</th>
                            <th >Action</th>
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
@endsection

@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    <script type="text/javascript">
        var getPatientList = function(patient_id = null) {
            var columns = [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'fname',name: 'fname',
                            mRender : function(data, type, full, meta){
                                m_Name = full['mname'];
                                if(full['mname'] == null){
                                    m_Name = '';
                                }
                                if(data!='' && data!='NULL' && data!=undefined){
                                    if(full['profile_img']=='' || full['profile_img']==null)
                                    {
                                        // return ["<a href='/patients/registerd-patient-edit/"+full['id']+"'><img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname']+'</a>';
                                        return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                    } else {
                                        // return ["<a href='/patients/registerd-patient-edit/"+full['id']+"'><img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname']+'</a>';
                                        return ["<img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                    }
                                }                              
                            },
                            orderable: false,
                        },
                       // {name:'fname'},
                        {data: 'dob', name: 'dob'},
                        {data: 'email', name: 'email'},
                        {data: 'mob', name: 'mob',
                            mRender: function(data, type, full, meta){
                                home_number = ' | ' + full['home_number'];
                                if(full['home_number'] == null){
                                    home_number = '';
                                }
                                if(data!='' && data!='NULL' && data!=undefined){
                                    return full['mob'] + home_number;
                                }else{
                                    return "";
                                }
                            },
                            orderable: false
                        },
                        {data: "patient_services", render: "[, ].module.module", orderable: false },
                        {data: 'contact_preference_calling', name: 'contact_preference_calling',
                            mRender: function(data, type, full, meta){
                                var return_images = '';
                                if(data!='' && data!='NULL' && data!=undefined){
                                    return_images = return_images + ["<i class='text-muted i-Headset' data-toggle='tooltip' data-placement='top' data-original-title='Call'></i>"];
                                }  else {
                                    return_images =  return_images;
                                }
                                if(full['contact_preference_sms']!='' && full['contact_preference_sms']!='NULL' && full['contact_preference_sms']!=undefined) {
                                    return_images = return_images + [" <i class='text-muted i-Letter-Open' data-toggle='tooltip' data-placement='top' data-original-title='Text'></i>"];
                                } else {
                                    return_images =  return_images;
                                }
                                if(full['contact_preference_email']!='' && full['contact_preference_email']!='NULL' && full['contact_preference_email']!=undefined) {
                                    return_images = return_images + [" <i class ='text-muted i-Email' data-toggle='tooltip' data-placement='top' data-original-title='Email'></i>"];
                                } else {
                                    return_images =  return_images;
                                }
                                if(full['contact_preference_letter']!='' && full['contact_preference_letter']!='NULL' && full['contact_preference_letter']!=undefined) {
                                    return_images = return_images + [" <i class ='text-muted i-Mailbox-Empty' data-toggle='tooltip' data-placement='top' data-original-title='Letter'></i>"];
                                } else { 
                                    return_images =  return_images;
                                }
                                return return_images;
                            },
                            orderable: false
                        },
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
            if (patient_id == null) {
                var table = util.renderDataTable('registered-patient-list', "{{ route('registered_patients_list') }}", columns, "{{ asset('') }}");
            } else {
                var url = '{{ route("patients.registered.patients.search", ":id") }}';
                url = url.replace(':id', patient_id);
                // console.log(url);
                var table1 = util.renderDataTable('registered-patient-list', url, columns, "{{ asset('') }}");
            }
        }
    </script>
    <script>
     $(document).ready(function() {
        getPatientList();
        $(".patient-div").hide();
        // enrollPatient.init();
        util.getToDoListData(0, {{getPageModuleName()}});
        $("[name='practices']").on("change", function () {
            $(".patient-div").show();
            util.updatePatientList(parseInt($(this).val()), {{ getPageModuleName() }}, $("#patient"));
        });

        $("[name='patient_id']").on("change", function () {
            getPatientList($(this).val());
        });
     });
    </script>
@endsection