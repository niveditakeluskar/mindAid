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
                            <th width="35px">Sr No.</th>
                            <th width="">Patient</th>
                            <th width="">DOB</th>
                            <th width="">Number </th>
                            <th width="">Last Modified by</th>
                            <th width="">Last Modified at</th>
                            <th width="">Last Contact Date</th>
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
<div id="app">
</div>
@endsection

@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script type="text/javascript">
        var getPatientList = function(patient_id = null) {
            var columns =  [
                                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                                // {data: null, mRender: function(data, type, full, meta){
                                //     m_Name = full['mname'];
                                //     if(full['mname'] == null){
                                //         m_Name = '';
                                //     }
                                //     if(data!='' && data!='NULL' && data!=undefined){
                                //         if(full['profile_img']=='' || full['profile_img']==null) {
                                //             return ["<a href='../monthly-monitoring/"+full['id']+"'><img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname']+'</a>';
                                //         } else {
                                //             return ["<a href='../monthly-monitoring/"+full['id']+"'><img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname']+'</a>';
                                //         }
                                //         // return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                //     }
                                // },
                                // orderable: false
                                // },
                                {data: 'patientName', name: 'patientName', orderable: false, searchable: false},
                                {data: 'dob', type: 'date-dd-mmm-yyyy', name: 'dob', "render":function (value) {
                                    if (value === null) return "";
                                        return moment(value).format('MM-DD-YYYY');
                                        // return util.viewsDateFormat(value);
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

                                {data: 'created_by_user', name: 'created_by_user',
                                    mRender: function(data, type, full, meta){
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return full['created_by_user'];
                                        }else { return ""; }
                                    },
                                    orderable: false
                                },
								
								// {data: 'last_modified_at', name: 'last_modified_at'},
								
                                    {data: 'last_modified_at',type: 'date-dd-mm-yyyy h:i:s', name: 'last_modified_at',"render":function (value) {
                                        if (value === null) return "";
                                        return value;
                                        }
                                },
                                
                                {data: 'last_contact_date',type: 'date-dd-mm-yyyy h:i:s', name: 'last_contact_date',"render":function (value) {
                                    if (value === null) return "";
                                        return util.viewsDateFormat(value);
                                    }
                                },

                                {data: 'action', name: 'action', orderable: false, searchable: false}
                            ]
            if (patient_id == null) {
                var table = util.renderDataTable('patient-list', "{{ route('monthly.monitoring.patients') }}", columns, "{{ asset('') }}");
            } else {
                var url1 = $(location).attr('href');
                var secondLevelLocation = url1.split('/').reverse()[2];
                if(secondLevelLocation == 'rpm'){
                    var url = '{{ route("rpm.monthly.monitoring.patients.search", ":id") }}';
                }else{
                    var url = '{{ route("monthly.monitoring.patients.search", ":id") }}';
                }
                url = url.replace(':id', patient_id);
                // console.log(url);
                var table1 = util.renderDataTable('patient-list', url, columns, "{{ asset('') }}");
            }  
        }
        $(document).ready(function() {
          //getPatientList();//hide the patientlist
			util.getToDoListData(0, {{getPageModuleName()}});
            $(".patient-div").hide(); // to hide patient search select

            $("[name='practices']").on("change", function () {
                $(".patient-div").show(); 
                if($(this).val()==''){
                var practiceId = null;
                util.updatePatientList(parseInt(practiceId), {{ getPageModuleName() }}, $("#patient"));
            }
            else
            {
                 util.updatePatientList(parseInt($(this).val()), {{ getPageModuleName() }}, $("#patient"));
            }
            });

            $("[name='patient_id']").on("change", function () {
                if($(this).val()==''){
                //getPatientList();
            }
            else
            {
                getPatientList($(this).val());
            }
            });
        });
        
   $('table').on('draw.dt', function()
   {
    $('[data-toggle="tooltip"]').tooltip();

    });

    
    </script>
@endsection