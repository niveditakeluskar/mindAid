@extends('Theme::layouts.master')
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
    <div class="col-md-10">
       <h4 class="card-title mb-3">Order List</h4>
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
                    <table id="Order-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="30px">Sr No.</th>
                            <th width="1510px">Patient Name</th>
                            <th width="50px">MRN</th> 
                            <th width="70px">Order Id</th>
                            
                            
                            <th width="170px">Order Status</th>
                            <th width="1500px">Order Date</th>
                            
                            
                            <th width="50px">Tracking Number</th>
                            <th width="150px">Shipping Partner</th>
                            <th width="50px">Date Shipped</th> 
                            <th width="150px">Device Id</th>   

                           <!--  <th width="30px">Sr No.</th>
                            <th width="50px">Patient Name</th>
                            <th width="30px">Order Id</th>
                            
                            
                            <th width="80px">Order Status</th>
                            <th width="100px">Order Date</th>
                            <th width="50px">MRN</th> 
                            
                            <th width="50px">Tracking Number</th>
                            <th width="50px">Shipping Partner</th>
                            <th width="50px">Date Shipped</th> 
                            <th width="50px">Device Id</th>  --> 

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
     var renderAPITable =  function() {
        var columns = [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: null,
                            mRender: function(data, type, full, meta){
                                if(full!='' && full!='NULL' && full!=undefined){
                                    return full['bill_first_name']+" "+full['bill_last_name'];
                                }
                            },
                            orderable: false
                        },
                        {data: 'mrn',name:'mrn'}, 
                        {data: 'order_id',name:'order_id'},
                        
                        //  {data: 'bill_first_name',name:'bill_first_name'},
                        
                        {data: 'order_status',name:'order_status'},                                            
                        // {data: 'order_date',name:'order_date'},
                        {data: 'order_date', type: 'date-dd-mmm-yyyy', name: 'order_date',
                                    "render":function (value) {
                                    if (value === null) return "";
                                        return moment(value).format('MM/DD/YY HH:MM:SS');
                                    }
                        },
                         
                        
                        // {data: null,
                        //     mRender: function(data, type, full, meta){
                        //         if(data!='' && data!='NULL' && data!=undefined){
                        //             return full['mrn'];
                        //         }
                        //     },
                        //     orderable: false
                        // },   
                        // {data: 'provider',name:'provider'},      
                        // {data: 'tracking_num',name:'tracking_num'}, 
                        {data: null,
                            mRender: function(data, type, full, meta){
                                if(full['tracking_num']!='' && full['tracking_num']!='NULL' && full['tracking_num']!=undefined){ 
                                   
                                    return "<a href='https://www.ups.com/track?loc=en_US&tracknum="+full['tracking_num']+"&requester=WT/trackdetails' target='_blank' rel='noopener noreferrer'>"+full['tracking_num']+"</a>";
                                }
                            },
                            orderable: false
                        }, 
                        // {data: 'shipping_method',name:'shipping_method'},  
                        {data: null,
                            mRender: function(data, type, full, meta){
                                if(full['shipping_method']!='' && full['shipping_method']!='NULL' && full['shipping_method']!=undefined){
                                    var shp =  full['shipping_method'];
                                    var shp1 = shp.substring(0,3); 
                                    if(shp1 == 'UPS')
                                    {
                                        shp1 = 'USPS';
                                    }
                                   
                                    return shp1;
                                        
                                }
                            },
                            orderable: false
                        },                      
                        {data: 'date_shipped',name:'date_shipped'},
                        {data: 'device_id',name:'device_id'}
                      
                    ]
            var table = util.renderDataTable('Order-list', "{{ route('render.order.list') }}", columns, "{{ asset('') }}"); 
    }; 
        $(document).ready(function() {
           

            renderAPITable();           
            util.getToDoListData(0, {{getPageModuleName()}});
        });




      
    </script>
@endsection