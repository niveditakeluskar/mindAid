@extends('Theme::layouts_2.to-do-master')
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
<div class="breadcrusmb">
    <div class="row">
		<div class="col-md-11">
		   <h4 class="card-title mb-3">Patients</h4>
		</div>	
    </div>
</div>
<div class="separator-breadcrumb border-top"></div>
@include('Patients::components.filter-practice')
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
                            <th width="150px">Patient</th>
                            <th width="80px">DOB</th>
                            <th width="100px">Number </th>
                            <th width="80px">Medication</th>
                            <th width="80px">Compliance</th>
                            <th width="80px">HS </th>
                            <th width="">Modify By</th>
                            <th width="">Modify On</th>
                            <th width="35px">Status</th>
                            <th width="35px">Action</th>
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
    <script src="{{asset(mix('assets/js/laravel/rpmlist.js'))}}"></script>
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
                                        return ["<a href='/rpm/monthly-service/"+full['id']+"'><img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname']+'</a>';
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
                            {data: '', name: '',     
                                render: function(data, type, full, meta){
                                    if(data==1){
                                        return "Active";
                                    } else if(data==0){
                                        return "Inactive";
                                    } else {
                                        return "-";
                                    }
                                }
                            },
                            {data: '', name: '',     
                                render: function(data, type, full, meta){
                                    if(data==1){
                                        return "Active";
                                    } else if(data==0){
                                        return "Inactive";
                                    } else {
                                        return "-";
                                    }
                                }
                            },
                            {data: '', name: '',     
                                render: function(data, type, full, meta){
                                    if(data==1){
                                        return "Active";
                                    } else if(data==0){
                                        return "Inactive";
                                    } else {
                                        return "-";
                                    }
                                }
                            },

                            {data: 'created_by', name: 'created_by',
                                    mRender: function(data, type, full, meta){
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return full['created_by'];
                                        }
                                        else
                                        {
                                            return '';
                                        }
                                    },
                                    orderable: false
                            },

                            {data: 'created_at', name: 'created_at',
                                    mRender: function(data, type, full, meta){
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return full['created_at'];
                                        }else{
                                            return '';
                                        }
                                    },
                                    orderable: false
                            },

                            {data: '', name: '',     
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



                            {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
            if (patient_id == null) {
                // var table = util.renderDataTable('patient-list', "{{ route('fetch_patients_monthly_service') }}", columns, "{{ asset('') }}");
            } else {
                var url = '{{ route("patient_details", ":id") }}';
                url = url.replace(':id', patient_id);
                // console.log(url);
                var table1 = util.renderDataTable('patient-list', url, columns, "{{ asset('') }}");
            }  
        }
    </script>
    <script>
     $(document).ready(function() {
        
        $(".patient-div").hide();      
        rpmlist.init(); 
        util.getToDoListData(0, {{getPageModuleName()}});
     });
    </script>
@endsection