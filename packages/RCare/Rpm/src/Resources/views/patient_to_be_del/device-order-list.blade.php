@extends('Theme::layouts.master')
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
    <div class="col-md-10">
       <h4 class="card-title mb-3">Device Orders</h4>
    </div>
  
     <div class="col-md-1">
     <a class="" href='{{route("device.order")}}' id="addCarePLanTemplate"><i class="add-icons i-Administrator" data-toggle="tooltip" data-placement="top" title="Place Order"></i><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Place Order"></i></a>  
    </div>
</div>
   <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<div id="success"></div>

 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
              <div id="msgsccess"></div>   
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="Device-Order-list" class="display datatable table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <!-- <th width="30px">Sr No.</th>
                            <th width="30px">Patient Name</th>
                            <th width="50px">MRN</th> 
                            <th width="70px">Order Id</th> 
                            <th width="50px">Devices</th>
                            <th width="80px">Source Id</th>
                            <th width="50px">Group Code</th>                            
                            <th width="50px">Order Date</th> 
                            <th width="50px">Tracking Number</th>
                            <th width="150px">Shipping Partner</th>
                            <th width="50px">Date Shipped</th> 
                            <th width="70px">Device Code</th>  
                             <th width="70px">Device Status</th>  
                            <th width="50px">View Details</th>    -->

                            <th>Sr No.</th>
                            <th>Patient Name</th>
                            <th>DOB</th>
                            <th>MRN</th> 
                            <th>Order Id</th> 
                            <th>Devices</th>                           
                            <th>Group Code</th>                            
                            <th>Order Date</th> 
                            <th>Tracking Number</th>
                            <th>Shipping Partner</th>
                            <th>Date Shipped</th> 
                            <th>Device Code</th>  
                             <th>Device Status</th> 
                              <th>Source Id</th> 
                            <th>View Details</th>                              
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
     var renderDeviceOrderTable =  function() {
        var columns = [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: null,
                            mRender: function(data, type, full, meta){
                                if(data!='' && data!='NULL' && data!=undefined){
                                    return full['pfname']+' '+full['plastname'];
                                }
                            },
                            orderable: true
                        },
                         {data: 'dob', type: 'date-dd-mmm-yyyy', name: 'dob',
                             "render":function (value) {
                            if (value === null) return "";
                             return moment(value).format('MM-DD-YYYY');
                            }
                            },
                        {data: 'mrn',name:'mrn'},  
                        {data: 'oid',name:'oid'},
                        {data: 'devicefinal',name:'devicefinal'},                                           
                        {data: 'group_code',name:'group_code'},                                              
                        {data: 'created_at',name:'created_at'},
                        {data: 'tracking_num',name:'tracking_num'},
                        {data: 'shippingoption',name:'shippingoption'},
                        {data: 'date_shipped',name:'date_shipped'},
                        {data: 'device_code',name:'device_code'},
                        {data: 'device_status',name:'device_status'},
                          {data: null,
                            mRender: function(data, type, full, meta){
                                if(data!='' && data!='NULL' && data!=undefined){
                                    return full['sourceid'];
                                }
                            },
                            orderable: true
                        },  
                        {data: 'details',name:'details'}

                      
                    ]
            var table = util.renderDataTable('Device-Order-list', "{{ route('render.device.order.list') }}", columns, "{{ asset('') }}"); 
    }; 
        $(document).ready(function() {
            renderDeviceOrderTable();

             setInterval(function() {
          getdeviceidfromApi();
          }, 60 * 1000);
           // carePlanTemplate.init();
           //diagnosisCode.init();
            util.getToDoListData(0, {{getPageModuleName()}});
        });


            function getdeviceidfromApi()
               {
                 // alert("test");
                  $.ajax({url: "/rpm/place-order-details", success: function(result){
                  
                  }});
               }

       var codevailable= function() {
        alert("test");
         if(confirm("Are you sure you want to change the code?")){       
           var data={
             condition: $('#condition').val(),
             code: $('#code').val()
            }
       
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
     $.ajax({
     type:'POST',
     url:'/org/code-availabel',
     data:data,   
     success:function (data) {      
     
       
     }
   });

     }
    else{
        return false;
    }
       };
    </script>
@endsection