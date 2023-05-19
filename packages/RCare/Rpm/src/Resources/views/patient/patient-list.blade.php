@extends('Theme::layouts_2.to-do-master')

@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')


@include('Rpm::patient.filter-practice-patient')

<div class="breadcrusmb">

  <div class="row">
		<div class="col-md-11">
		   <h4 class="card-title mb-3">Patients</h4>
		</div>
		 <div class="col-md-1">
		 <a class="" href="javascript:void(0)" id="addUser"><i class="add-icons i-Administrator" data-toggle="tooltip" data-placement="top" title="Add User"></i><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add User"></i></a>  
		</div>
</div>
   <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<!-- @include('Rpm::patient.filter-practice-patient') -->
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
                            <th width="40px">Sr No.</th>
                            <th width="">Patient</th>
                            <th width="80px">DOB</th>
                            <th width="100px">Email</th>
                            <th width="100px">Phone No. </th>
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
@endsection

@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    <script type="text/javascript">
        var columns = [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'fname',name: 'fname',
                            mRender: function(data, type, full, meta){
                                if(data!='' && data!='NULL' && data!=undefined){
                                    return ["<a href='/rpm/enroll-patient-checklist/"+full['id']+"'><img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+full['mname']+' '+full['lname']+'</a>';
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
                        {data: 'email', name: 'email'},
                        {data: 'phone_primary', name: 'phone_primary'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
        var table = util.renderDataTable('patient-list', "{{ route('patients_list') }}", columns, "{{ asset('') }}");  

    </script>
@endsection