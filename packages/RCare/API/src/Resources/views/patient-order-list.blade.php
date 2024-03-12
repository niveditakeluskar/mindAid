@extends('Theme::layouts_2.to-do-master')

@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')


<div class="breadcrusmb">

  <div class="row">
		<div class="col-md-11">
		   <h4 class="card-title mb-3">Patient Orders</h4>
		</div>
		
</div>
    
</div>
<div class="separator-breadcrumb border-top"></div>

 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
           <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="patient-order-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="40px">Sr No.</th>
                            <th width="80px">Patient</th>
                            <th width="80px">DOB</th>
                            <th width="100px">Order Date</th>
                            <th width="100px">Partner MRN</th>
                            <th width="35px">Device</th>                            
                              <th width="35px">Tracking No</th>
                               <th width="35px">Carrier Name</th>
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
<div id='app'></div>
@endsection

@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script type="text/javascript">
        var columns = [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'fname',name: 'fname',
                            mRender: function(data, type, full, meta){
                                  m_Name = full['mname'];
                                            if(full['mname'] == null){
                                                m_Name = '';
                                        }
                                if(data!='' && data!='NULL' && data!=undefined){
                                    // if(full['profile_img']=='' || full['profile_img']==null) {
                                        return "<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                    // } else {
                                    //     return "<img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                    // }
                                 
                                }
                            },
                            orderable: false
                        },
                       {data: 'dob', type: 'date-dd-mmm-yyyy', name: 'dob', "render":function (value) {
                            if (value === null) return "";
                                return util.viewsDateFormat(value);
                            }
                        },
                        {data: 'order_date', name: 'order_date'},
                        {data: 'partner_mrn', name: 'partner_mrn'},
                         {data: 'device', name: 'device'},
                           {data: 'tracking_no', name: 'tracking_no'},
                             {data: 'carrier_name', name: 'carrier_name'},                              

                    ]
        var table = util.renderDataTable('patient-order-list', "{{ route('order_list') }}", columns, "{{ asset('') }}");  
        // $(document).ready(function() {
        //     util.getToDoListData(0, {{getPageModuleName()}});
        // });
    </script>
@endsection