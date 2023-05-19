@extends('Theme::layouts.master')
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
           <h4 class="card-title mb-3">Patients</h4>
        </div>
         <div class="col-md-1">
        </div>
    </div>           
</div>
<div class="separator-breadcrumb border-top"></div>
@include('Patients::components.filter-practice')
 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">           
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="patient-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th colspan="4"></th>
                            <th colspan="2">Monitoring Criterion</th>
                            <th colspan="2">Monthly</th>
                            <th colspan="2"></th>
                        </tr>
                        <tr>
                            <th width="50px">Sr No.</th>
                            <th width="200px">Patient</th>
                            <th width="100px">DOB</th>
                            <th width="130px">Number</th>
                            <th>Condition</th>
                            <th>Device</th>
                            <th width="170px">Time Accumulation</th>
                            <th width="170px">Interactions</th>
                            <th width="35px">Status</th>
                            <th width="">Device Training Completed</th>
                            <th width="20px">Action</th>
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
                                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                                { data: 'fname',name: 'fname',
                                    mRender: function(data, type, full, meta){
                                        m_Name = full['mname'];
                                            if(full['mname'] == null){
                                                m_Name = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return ["<a href='/rpm/device-traning/"+full['id']+"'><img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname']+'</a>';
                                        }
                                    },
                                    orderable: false
                                },
                                { data: 'dob', type: 'date-dd-mmm-yyyy', name: 'dob',
                                    "render":function (value) {
                                    if (value === null) return "";
                                        return moment(value).format('MM-DD-YYYY');
                                    }
                                },
                                {data: 'mob', name: 'mob'},
                                { data: 'dob', name: 'dob',
                                    render: function(data, type, full, meta){
                                        if(data==1){
                                            return "Active";
                                        } else if(data==0){
                                            return "Inactive";
                                        } else {
                                            return "BP";
                                        }
                                    }
                                },
                                { data: '', name: '',
                                    render: function(data, type, full, meta){
                                        if(data==1){
                                            return "Active";
                                        } else if(data==0){
                                            return "Inactive";
                                        } else {
                                            return "BP Cuff";
                                        }
                                    }
                                },
                                { data: '', name: '',
                                    render: function(data, type, full, meta){
                                        
                                            return '00:10:00';
                                    }
                                },
                                { data: '', name: '',
                                    render: function(data, type, full, meta){
                                        return '1. call on 12-12-2019<br/>2. Email on 11-11-2019';
                                    }
                                },
                                { data: '', name: '',
                                    render: function(data, type, full, meta){
                                        if(data==1){
                                            return "Active";
                                        } else if(data==0){
                                            return "Inactive";
                                        } else {
                                            return '<i class="i-Yess i-Yes"  style="color: green;" title="Active"></i>';
                                        }
                                    }
                                },

                        { data: 'device_training_completed', name: 'device_training_completed',
                                    render: function(data, type, full, meta){
                                        if(data==1){
                                            return "Active";
                                        } else if(data==0){
                                            return '<i class="i-Closee i-Close"  style="color: red;" title="Incomplete"></i>';
                                        } else {
                                            return '<i class="i-Yess i-Yes"  style="color: green;" title="Active"></i>';
                                        }
                                    }
                                },
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                        ]
            if (patient_id == null) {
                var table = util.renderDataTable('patient-list', "{{ route('device_patients_list') }}", columns, "{{ asset('') }}");  
            } else {
                var url = '{{ route("patient_device_details", ":id") }}';
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