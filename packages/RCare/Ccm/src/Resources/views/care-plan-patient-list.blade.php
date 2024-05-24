@extends('Theme::layouts_2.to-do-master')
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')




<div class="breadcrusmb">

  <div class="row">
		<div class="col-md-11">
		   <h4 class="card-title mb-3">Care Plan Development Patients</h4>
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
                    <table id="patient-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="35px">Sr No.</th>
                            <th width="">Patient</th>
                            <th width="">DOB</th>
                          <!--   <th width="100px">Email</th> -->
                            <th width="">Number </th>
                            <th width="">Modify By </th>
                            <th width="">Modify On/th>
                            
                            <!-- <th width="80px">Medication</th>
                            <th width="80px">Compliance</th>
                            <th width="50px">HS </th>
                             <th width="35px">Status</th> -->
                            <th width="10px">Action</th>
                           <!--  <th width="35px">Status</th> -->
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
    <script src="{{asset('assets/js/vendor/dataTables.editor.min.js')}}"></script>
    
    <script type="text/javascript">
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

                                        return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                        
                                    }
                                },
                                orderable: false
                            },
                            {data: 'dob', type: 'date-dd-mmm-yyyy', name: 'dob',
                                "render":function (value) {
                                    if (value === null) return "";
                                    return moment(value).format('MM-DD-YYYY');
                                }
                            },
                            {data: 'mob', name: 'mob'},
                            {data: 'updated_by', name: 'updated_by'},
                            {data: 'updated_at', name: 'updated_at'},
                            // {data: '', name: '',     
                            //     render: function(data, type, full, meta){
                            //         if(data==1){
                            //             return "Active";
                            //         } else if(data==0){
                            //             return "Inactive";
                            //         } else {
                            //             return "-";
                            //         }
                            //     }
                            // },
                            // {data: '', name: '',     
                            //     render: function(data, type, full, meta){
                            //         if(data==1){
                            //             return "Active";
                            //         } else if(data==0){
                            //             return "Inactive";
                            //         } else {
                            //             return "-";
                            //         }
                            //     }
                            // },
                            // {data: '', name: '',     
                            //     render: function(data, type, full, meta){
                            //         if(data==1){
                            //             return "Active";
                            //         } else if(data==0){
                            //             return "Inactive";
                            //         } else {
                            //             return "-";
                            //         }
                            //     }
                            // },
                            // {data: '', name: '',     
                            //     render: function(data, type, full, meta){
                            //         if(data==1){
                            //             return "Active";
                            //         } else if(data==0){
                            //             return "Inactive";
                            //         } else {
                            //             return '<i class="i-Yess i-Yes"  style="color: green;" title="Active"></i>';
                            //         }
                            //     }
                            // },
                            {data: 'action', name: 'action', orderable: false, searchable: false}
                           /* {data: 'action2', name: 'action2', orderable: false, searchable: false}*/
                           
                            
                           
                            ]
                          
           
            if (patient_id == null) {
                var table = util.renderDataTable('patient-list', "{{ route('care.plan.development.list') }}", columns, "{{ asset('') }}");
            } else {
                var url = '{{ route("fetch.patient.monthly.service", ":id") }}';
                url = url.replace(':id', patient_id);
                // console.log(url);
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
@endsection