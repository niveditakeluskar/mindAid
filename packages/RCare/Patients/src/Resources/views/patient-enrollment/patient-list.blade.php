@extends('Theme::layouts_2.to-do-master')
@section('page-css')
    <!-- <link rel="stylesheet" href="{{-- asset('assets/styles/vendor/datatables.min.css') --}}"> -->
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Patients</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
@include('Patients::components.filter-practice-patient')
<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="patient-list" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Patient</th>
                                <th>DOB</th>
                                <th>Email</th>
                                <th>Phone No. </th>
                                <th>Enrolled Services</th>
                                <th>Communication Vehicle</th>
                                <th>Enroll</th>
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
<div id="app"></div>
@endsection

@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    <script type="text/javascript">
		var enrollModule = function(patient_id, module_id){
			//alert("pid"+patient_id);
			//alert("mid"+module_id);
			if(module_id!=''){
			var url = "/patients/patient-enrollment/"+patient_id+"/"+module_id;
			window.location.href =url;
			}
			
		}
        var getPatientList = function(patient_id = null) {
            var columns = [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},

                        {data: 'fname',name: 'fname',
                            mRender: function(data, type, full, meta){
                                  m_Name = full['mname'];
                                            if(full['mname'] == null){
                                                m_Name = '';
                                        }
                                if(data!='' && data!='NULL' && data!=undefined){
                                    if(full['profile_img']=='' || full['profile_img']==null) {
                                        return "<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                    } else {
                                        return "<img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                    }
                                    // return ["<a href='/patients/enroll-patient-checklist/"+full['id']+"'><img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname']+'</a>';
                                }
                            },
                            orderable: false,searchable: true
                        },
                        // {data: 'dob', name: 'dob'},
                        {data: 'dob', type: 'date-dd-mmm-yyyy', name: 'dob', "render":function (value) {
                            if (value === null) return "";
                                return moment(value).format('MM-DD-YYYY');
                                // return util.viewsDateFormat(value);
                            }
                        },
                        {data: 'email', name: 'email'},
                        {data: 'mob', name: 'mob',
                            mRender: function(data, type, full, meta){
                                home_number = ' | ' + full['home_number'];
                                if(full['home_number'] == null){
                                    home_number = '';
                                }
                                if(data!='' && data!='NULL' && data!=undefined){
                                    return full['mob'] + home_number;
                                }
                                else
                                {
                                    return '';
                                }
                            },
                            orderable: false
                        },
                        // {data: 'home_number', name: 'home_number'},
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
                    ];
            if (patient_id == null || patient_id=='0') {
                var table = util.renderDataTable('patient-list', "{{ route('patient.enrollment.patients') }}", columns, "{{ asset('') }}");
            } else {
                var url = '{{ route("patient.enrollment.patients.search", ":id") }}';
                url = url.replace(':id', patient_id);
                // console.log(url);
                var table1 = util.renderDataTable('patient-list', url, columns, "{{ asset('') }}");
            }  
        }
        $(document).ready(function() {
            //getPatientList();
            util.getToDoListData(0, {{ getPageModuleName() }});
            util.getAssignPatientListData(0, 0);
            $(".patient-div").hide(); // to hide patient search select

            $("[name='practices']").on("change", function () {
                $(".patient-div").show();
                //console.log($(this).val()+"test"+{{ getPageModuleName() }});
                  if($(this).val()=='0' || $(this).val()==''){
               getPatientList('0');
               util.updatePatientList(parseInt(''), {{ getPageModuleName() }}, $("#patient"));
                }
                else
                {
                    
                    util.updatePatientList(parseInt($(this).val()), {{ getPageModuleName() }}, $("#patient"));
                    
                }
                //util.updatePatientList(parseInt($(this).val()), $("#patient"));
            });

            $("[name='patient_id']").on("change", function () {
              
                getPatientList($(this).val());

            });
        });
    </script>
@endsection