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
<!-- <div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <div class="form-row ">
                    <div class="col-md-6 form-group mb-3">
                        <label for="practicename">Practice Name</label>
                        @selectpractices("practices", ["id" => "practices"])
                    </div>
                    <div class="col-md-6 form-group mb-3 patient-div" >
                        <label for="practicename">Patient Name</label>
                        @select("Patient", "patient_id", [], ["id" => "patient"])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
           <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="patient-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th >Sr No.</th>
                            <th >Patient</th>
                            <th >DOB</th>
                            <th >Email</th>
                            <th >Phone No. </th>
                            <th >Enrollment Status</th>
                            <!-- <th width="50px">Calling</th>
                            <th width="50px">Text</th>
                            <th width="50px">Email</th>
                            <th width="50px">Postal Letter</th> -->
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

    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    <script type="text/javascript">

    var getPatientList = function(patient_id = null) {
        var columns = [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'fname',name: 'fname',
                            mRender: function(data, type, full, meta){
                                if(data!='' && data!='NULL' && data!=undefined){
                                    return ["<a href='/rpm/enroll-patient-details/"+full['id']+"'><img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+full['mname']+' '+full['lname']+'</a>';
                                }
                            },
                            orderable: false
                        },
                        {data: 'dob', name: 'dob'},
                        {data: 'email', name: 'email'},
                        {data: 'phone_primary', name: 'phone_primary'},
                        {data: 'phone_primary', name: 'phone_primary',
                            mRender: function(data, type, full, meta){
                                if(data!='' && data!='NULL' && data!=undefined){
                                    return '-';
                                }
                            }
                        },
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
            var table = util.renderDataTable('patient-list', "{{ route('patients-enroll') }}", columns, "{{ asset('') }}");
        } else {
            var url = '{{ route("patient_enroll_details", ":id") }}';
            url = url.replace(':id', patient_id);
            var table1 = util.renderDataTable('patient-list', url, columns, "{{ asset('') }}");
        }
    }

    </script>
    <script>
     $(document).ready(function() {
        getPatientList();
        $(".patient-div").hide();
        enrollPatient.init();
     });
    </script>