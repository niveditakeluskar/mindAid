@extends('Theme::layouts_2.to-do-master')       
@section('page-css')
<style type="text/css">   
    /*button#patientdetails {*/
      /*  margin-left: 20px;
        float: right;*/
       /* border: none;
        color: blue;
    }
    td .center{
      text-align: center;
      width: 100%;
    }
    th{
      text-align: center;    
  }*/

  input.larger {
        width: 30px;
        height: 30px;
        border-color: lightblue;
      }

</style>
 @section('page-title')

@endsection 
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">RPM Enrolled Patient Report</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">     
            <div class="card-body">
                <form id="rpm_enrolled_report_form" name="rpm_enrolled_report_form"  action ="">
                @csrf
                    <div class="form-row">
                        <!-- <div class="col-md-3 form-group mb-3">  
                            <label for="practicegrp">{{config('global.practice_group')}}</label>
                             @selectgrppractices("practicesgrp", ["class" => "select2","id" => "practicesgrp"]) 
                        </div> -->
                        <div class="col-md-4 form-group mb-3">
                            <label for="practicename">Practice</label>                  
                            @selectworklistpractices("practices", ["id" => "practices", "class" => "select2"])   
                        </div>
                        <div class="col-md-4 form-group mb-6">
                            <label for="practicename">Patient Name</label>
                            @selectallworklistccmpatient("Patient",["id" => "patient", "class" => "select2"])
                        </div>
                        <!-- <div class="col-md-2 form-group mb-3">
                            <label for="provider">Provider</label>
                            @selectpracticesphysician("provider",["id" => "physician","class"=>"custom-select show-tick select2"])
                        </div>  -->
                        <div class="col-md-4 form-group mb-2">
                            <label for="shippingstatus">Shipping Status</label>
                                <select id="shippingstatus" class="custom-select show-tick" name="shippingstatus">
                                    <option value selected>(None,Shipped,Delivered,Not shipped)</option>
                                    <option value="4" >None</option>
                                    <option value="1">Shipped</option>
                                    <option value="2">Delivered</option>
                                    <option value="3">Not shipped</option>
                                </select>
                        </div>
                        <div class="col-md-2 form-group mb-2">
                            <label for="enrolled_date_filter">Filter Enrollment Date</label> <br>
                            <input type="checkbox" class="larger" name="check_enrolled_date" id="check_enrolled_date">
                            <span class="checkmark"></span>
                        </div>
                        <div id="fromDateField" class="col-md-2 form-group mb-2" style="display: none;">
                            <label for="date">From Date</label>
                            @date('date',["id" => "fromdate"])          
                        </div>
                        <div id="toDateField" class="col-md-2 form-group mb-3" style="display: none;">
                            <label for="date">To Date</label>
                            @date('date',["id" => "todate"])                     
                        </div>
                        <div class="col-md-1">
                            <button type="button" id="searchbutton" class="btn btn-primary mt-4">Search</button>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-primary mt-4" id="month-reset">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            
        <div id="success"></div>
            <div class="card-body">
                @include('Theme::layouts.flash-message')
                <div class="alert alert-success" id="shipping-success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>Shipping action successfully! </strong><span id="text"></span>
                </div>
                <div class="table-responsive">
                    <table id="rpmenrolled-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sr No.</th>
                            <th>Patient Name</th> 
                            <th>Practice Name</th>
                            <th>Care Manger Name</th>                      
                            <th>DOB</th>
                            <!-- <th>Shipping Date</th> -->
                            <th>Enrollment Date</th>
                            <th>Shipping Status</th>
                            <th>Welcome Call</th>
                            <th>Device</th>
                            <th width="270px">Action</th> 
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

 <!-- The Modal -->
<div class="modal" id="shippingdetailmodel">  
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Shipping detail</h4>  
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body"> 
                <div class="row mb-4">   
                    <div class="col-md-12 mb-4">
                        <div class="card text-left">
                            <div class="card-body">
                                @include('Theme::layouts.flash-message')
                                <div class="alert alert-success" id="shipping-success-alert" style="display: none;">
                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    <strong>Shipping action successfully! </strong><span id="text"></span>
                                </div>
                                
                                <form action="{{ route("ajax.save.shipping")}}" method="post" name ="shipping_form"  id="shipping_form">
                                @csrf
                                    <div class="row">    
                                        <input type="hidden" id="patientIdField" name="patient_id" value="">
                                        <div class="col-md-4 form-group mb-3">
                                            <label for="device_code" class="control-label">Device ID</label><span class="error">*</span>
                                            <!-- <input type="text" id="device_code" name="device_code" class="form-control capital-first"><br> -->
                                            @selectpatientDevice("device_id", ["id" => "device_id", "class" => "select2"]) 
                                        </div>
                                        <div class="col-md-4 form-group mb-3">
                                            <label for="name" class="control-label">Courier Service Provider</label>
                                            @text("courier_service_provider", ["id" => "courier_service_provider", "class" => "form-control capital-first ", "placeholder" => "Enter courier service provider"])
                                        </div>
                                        <div class="col-md-4 form-group mb-3">
                                            <label for="name" class="control-label">Shipping Date</label>
                                            @date('shipping_date',["id" => "shipping_date"])
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 form-group mb-3">
                                            <label for="name" class="control-label">Shipping Status</label>
                                            <select id ="shipping_status" name="shipping_status" class="shipping_status custom-select show-tick">
                                                <!-- <option value='0'>None</option> -->
                                                <option value='3'>Not shipped</option>
                                                <option value='1'>Shipped</option>
                                                <option value='2'>Delivered</option>
                                            </select>
                                        </div>
                                        <div class="mr-3 d-inline-flex">
                                            <label for="welcome_call" class="control-label">Welcome Call</label> <span></span>    
                                            <label for="status_yes" class="radio radio-primary mr-3">
                                                <input type="radio" formcontrolname="radio" name="status" id="status_yes" value="1">
                                                <span>Yes</span>
                                                <span class="checkmark"></span>
                                            </label>
                                            <label for="status_no" class="radio radio-primary mr-3">
                                                <input type="radio" formcontrolname="radio" name="status" id="status_no" value="0" checked>
                                                <span>No</span>
                                                <span class="checkmark"></span>
                                            </label> 
                                        </div>
                                    </div>
                                    <div class="mc-footer">             
                                        <div class="row">
                                            <div class="col-12 text-right form-group mb-4">
                                                <button type="submit" class="btn btn-primary" id="saveshippingdetails">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>  
                                <div class="separator-breadcrumb border-top"></div>
                                <div class="row mb-4" id="device_list_table">
                                    <div class="col-md-12 mb-4">
                                        <div class="card text-left">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="shipping_data_list" class="display table table-striped table-bordered" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Sr No.</th>
                                                                <th>Device ID</th>
                                                                <th>Courier Service Provider</th>
                                                                <th>Shipping Date</th>
                                                                <th>Shipping Status</th>
                                                                <th>Welcome Call</th> 
                                                                <th>Last Modifed By</th>
                                                                <th>Last Modifed On</th>
                                                                <!-- <th>Action</th>    -->
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
                            </div>
                             <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal" id="devicedetailsmodel">  
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Devices</h4>  
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body"> 
                <div class="row mb-4">   
                    <div class="col-md-12 mb-4">
                        <div class="card text-left">
                            <div class="card-body">
                                @include('Theme::layouts.flash-message')
                                <div class="alert alert-success" id="shipping-success-alert" style="display: none;">
                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    <strong>Shipping action successfully! </strong><span id="text"></span>
                                </div>
                                <form action="{{ route("ajax.save.device")}}" method="post" name ="device_form"  id="device_form">
                                @csrf
                                    <div class="row">    
                                        <input type="hidden" id="patientIdField1" name="patient_id" value="">
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="name" class="control-label">Device Code</label><span class="error">*</span>
                                            @text("device_code", ["id" => "device_code", "class" => "form-control capital-first ", "placeholder" => "Enter device code"])
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>Partners<span class='error'>*</span></label>
                                            <!-- @selectrpmenrolledpartner("partner_id",["id" => "partner_id"]) -->
                                            @selectpartner("partner_id",["id" => "partner_id"])
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Partner Devices<span class='error'>*</span></label>
                                            @selectPartnerDevice("partner_devices_id",["id"=>"partner_devices_id"])
                                        </div> 
                                    </div>   
                                    <div class="mc-footer">             
                                        <div class="row">
                                            <div class="col-12 text-right form-group mb-4">
                                                <button type="submit" class="btn btn-primary" id="devicedetails">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form> 
                                
                                <div class="separator-breadcrumb border-top"></div>
                                <div class="row mb-4" id="device_list_table">
                                    <div class="col-md-12 mb-4">
                                        <div class="card text-left">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="devices_data_list" class="display table table-striped table-bordered" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Sr No.</th>
                                                                <th>Device Code</th>
                                                                <th>Partner</th>
                                                                <th>Partner Device</th>
                                                                <!-- <th>Partner Devices</th>  -->
                                                                <th>Last Modifed By</th>
                                                                <th>Last Modifed On</th>
                                                                <th>Action</th>   
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
                            </div>
                             <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
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
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
   
    <script type="text/javascript">   
        
        var getshippinglist = function(patient = null,shipping_status=null) { 
            var columns =  [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: null, 
                    mRender: function(data, type, full, meta){
                        device_code = full['device_code'];
                        if((full['device_code'] == null) ){
                            device_code = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if((full['device_code'] == null) ){
                                device_code = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return device_code ;
                            }               
                        }
                    },
                    orderable: true
                }, 

                {data: null, 
                    mRender: function(data, type, full, meta){
                        courier_service_provider = full['courier_service_provider'];
                        if((full['courier_service_provider'] == null) ){
                            courier_service_provider = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if((full['courier_service_provider'] == null) ){
                                courier_service_provider = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return courier_service_provider ;
                            }               
                        }
                    },
                    orderable: true
                }, 

                {data:null,
                    mRender: function(data, type, full, meta){
                        shipping_date = full['shipping_date'];
                        if(full['shipping_date'] == null){
                            return shipping_date = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return moment(shipping_date).format('MM-DD-YYYY');      
                        }
                    },
                    orderable: true
                },
                {data: null, 
                 mRender: function(data, type, full, meta){
                    shipping_status = full['shipping_status'];
                        if((full['shipping_status'] == null) ){
                            shipping_status = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if((full['shipping_status'] == null)){
                                shipping_status = '';
                            }
                            if((full['shipping_status'] == '0')){
                                shipping_status = 'None';
                            }
                            if((full['shipping_status'] == '1')){
                                shipping_status = 'Shipped';
                            }
                            if((full['shipping_status'] == '2')){
                                shipping_status = 'Delivered';
                            }
                            if((full['shipping_status'] == '3')){
                                shipping_status = 'Not Shipped';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return shipping_status;
                            }               
                        }
                    },
                    orderable: true
                }, 
                {data: null, 
                mRender: function(data, type, full, meta){
                    welcome_call = full['welcome_call'];
                        if((full['welcome_call'] == null) ){
                            welcome_call = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if((full['welcome_call'] == null)){
                                welcome_call = '';
                            }
                            if((full['welcome_call'] == '0')){
                                welcome_call = 'No';
                            }
                            if((full['welcome_call'] == '1')){
                                welcome_call = 'Yes';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return welcome_call;
                            }               
                        }
                    },
                    orderable: true
                }, 
                {
                data: 'f_name', name: 'f_name', render:
                    function (data, type, full, meta) {
                        if (data != '' && data != 'NULL' && data != undefined) {
                        return data + ' ' + full.l_name;
                        } else {
                        return '';
                        }
                    }
                },
                {
                    data: 'updated_at', type: 'date-dd-mm-yyyy h:i:s', name: 'updated_at', "render": function (value) {
                    if (value === null) return "";
                    return util.viewsDateFormatWithTime(value);
                    }
                },

            ];

            // if(practices=='')
            // { 
            //     practices=null;
            // } 
            if(patient=='')
            { 
                patient=null;
            }
            if(shipping_status==''){ shipping_status=null;}
            var url = "/reports/shippinglist/"+patient+'/'+shipping_status;
            // var url = "/reports/shippinglist/"+patient;
            console.log(url); 
            util.renderDataTable('shipping_data_list', url, columns, "{{ asset('') }}");     
        }   

        
        var getdevicecode = function(patient = null){
            var columns =  [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'device_code', name: 'device_code' },
            { data: 'name', name: 'name' },
            { data: 'device_name', name: 'device_name' },
            {
                data: 'f_name', name: 'f_name', render:
                function (data, type, full, meta) {
                    if (data != '' && data != 'NULL' && data != undefined) {
                    return data + ' ' + full.l_name;
                    } else {
                    return '';
                    }
                }
            },
            {
                data: 'updated_at', type: 'date-dd-mm-yyyy h:i:s', name: 'updated_at', "render": function (value) {
                if (value === null) return "";
                return util.viewsDateFormatWithTime(value);
                }
            },

            // {
            //  data: 'update_date', name: 'update_date', "render": function (value) {
            //    if (value === null || value == undefined || value == "") return "";
            //    return moment(value).format('MM-DD-YYYY');
            //  }
            // },

            { data: 'action', name: 'action', orderable: false, searchable: false } ];
            if(patient=='')
            { 
                patient=null;
            }

            var url = "/reports/devicelist-rpmenrolled/"+patient;
            console.log(url); 
            util.renderDataTable('devices_data_list', url, columns, "{{ asset('') }}");     
        }

        var getrpmenrolledpatientlist = function(practices = null,patient = null ,shipping_status=null,fromdate1=null,todate1=null) { 

            var columns =  [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: null,
                    mRender: function(data, type, full, meta){
                        patientf = full['pfname'];
                        patientl = full['plname'];
                        //patient = patientf +" "+ patientl;

                        if((full['pfname'] == null) || (full['plname'] == null)){
                            patientf = '';
                            patientl = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return  patientf +" "+ patientl;
                        }
                    },
                    orderable: true
                },
                
                {data: null, 
                    mRender: function(data, type, full, meta){
                        assignby1 = full['practicename'];
                        if((full['practicename'] == null) ){
                            assignby1 = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if((full['practicename'] == null) ){
                                assignby1 = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return assignby1 ;
                            }               
                        }
                    },
                    orderable: true
                }, 
                
                {data: null, 
                 mRender: function(data, type, full, meta){
                    assignto1 = full['caremanagerfname'];
                    assignto2 = full['caremanagerlname'];
                        if((full['caremanagerfname'] == null) || (full['caremanagerlname'] == null)){
                            assignto1 = '';
                            assignto2 = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if((full['caremanagerfname'] == null) || (full['caremanagerlname'] == null)){
                                assignto1 = '';
                                assignto2 = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return assignto1 + " " + assignto2;
                            }               
                        }
                    },
                    orderable: true
                }, 

                {data:null,
                    mRender: function(data, type, full, meta){
                        dob = full['pdob'];
                        if(full['pdob'] == null){
                            dob = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return moment(dob).format('MM-DD-YYYY');      
                        }
                    },
                    orderable: true
                },

                // {data:null,
                //     mRender: function(data, type, full, meta){
                //         shippingdate = full['shippingdate'];
                //         if(full['shippingdate'] == null){
                //             shippingdate = '';
                //             return shippingdate;
                //         }
                //         if(data!='' && data!='NULL' && data!=undefined){
                //             return moment(shippingdate).format('MM-DD-YYYY');      
                //         }
                //     },
                //     orderable: true
                // },
                {data:null,
                    mRender: function(data, type, full, meta){
                        dateenrolled = full['dateenrolled'];
                        if(full['dateenrolled'] == null){
                            dateenrolled = '';
                            return dateenrolled;
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return moment(dateenrolled).format('MM-DD-YYYY');      
                        }
                    },
                    orderable: true
                },
                

                // {data: 'shipping', name: 'shipping', orderable: false, searchable: false},
                {data: null, 
                 mRender: function(data, type, full, meta){
                    shipping_status_1 = full['shipped_count'];
                    shipping_status_2 = full['delivered_count'];
                    shipping_status_3 = full['notshipped_count'];
                        if(full['shipped_count'] == null || full['delivered_count'] == null ||  full['notshipped_count'] == null ){
                            shipping_status = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            shipping_status_1 = 'Shipped : ' + shipping_status_1;
                            shipping_status_2 = 'Delivered :' + shipping_status_2;
                            shipping_status_3 = 'Not Shipped :' + shipping_status_3;
                            shipping_status =  shipping_status_1 + "<br/>" + shipping_status_2 + "<br/>" + shipping_status_3;
                            return shipping_status;
                                          
                        }
                    },
                    orderable: true
                }, 
                {data: null, 
                 mRender: function(data, type, full, meta){
                    welcome_call = full['welcomecall'];
                        if((full['welcomecall'] == null) ){
                            welcome_call = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if((full['welcomecall'] == null)){
                                welcome_call = '';
                            }
                            if((full['welcomecall'] == '0')){
                                welcome_call = 'No';
                            }
                            if((full['welcomecall'] == '1')){
                                welcome_call = 'Yes';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return welcome_call;
                            }               
                        }
                    },
                    orderable: true
                }, 
                {data: 'device', name: 'device', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}

                ];
         
                if(practices=='')
                { 
                    practices=null;
                } 
                if(patient=='')
                { 
                    patient=null;
                }
                if(fromdate1==''){ fromdate1=null; }
                if(todate1=='')  { todate1=null; }
                if(shipping_status==''){ shipping_status=null;}
            
       
          
                var url = "/reports/rpmenrolledpatientlist/"+practices+'/'+patient+'/'+shipping_status+'/'+fromdate1+'/'+todate1;
                console.log(url); 
                util.renderDataTable('rpmenrolled-list', url, columns, "{{ asset('') }}");     
        } 

    function formatDate() {
    var d = new Date(),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year,month, day].join('-');
    }

    var currentdate = formatDate();  
    var date = new Date(); 

    var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);         
    var getmnth=("0" +(date.getMonth() + 1)).slice(-2);
    var firstDayWithSlashes = date.getFullYear()+ '-' + getmnth + '-' +('0' +(firstDay.getDate())).slice(-2);


$(document).ready(function(){ 
    enrolledShippingReport.init();
//   renderReportsMasterTable();

    $('#fromdate').val(firstDayWithSlashes);                         
    $('#todate').val(currentdate);
    var fromdate1=$('#fromdate').val();
    var todate1=$('#todate').val();
    getrpmenrolledpatientlist(null,null,null,null,null);    
}); 

$("[name='practices']").on("change", function () {
        var practiceId =$(this).val();
        if(practiceId =='' || practiceId=='0'){
            var  practiceId= null;
                // util.updatePatientList(parseInt(practiceId),parseInt(module_id), $("#patient"));
        }else if(practiceId!=''){
            util.getPatientList(parseInt(practiceId),$("#patient")); 
        }else { 
            // util.updatePatientList(parseInt($(this).val()),parseInt(module_id), $("#patient"));
        }
});

$('input[type="checkbox"]').click(function(){
    const toDateField = document.getElementById("toDateField");
    const fromDateField = document.getElementById("fromDateField");
    if($(this).prop("checked") == true){
       $('#check_enrolled_date').val(0);   
        toDateField.style.display = "block";
        fromDateField.style.display = "block"; 
    } else {
        $('#check_enrolled_date').val(1); 
        toDateField.style.display = "none";
        fromDateField.style.display = "none";
    }
});

$('#searchbutton').click(function(){
    var practice=$('#practices').val();
    var patient=$('#patient').val();
    var shipping_status=$('#shippingstatus').val();
    var check_enrolled_date=$('#check_enrolled_date').val();
    var fromdate1=$('#fromdate').val();
    var todate1=$('#todate').val();
    if(check_enrolled_date == '0'){  
        getrpmenrolledpatientlist(practice, patient, shipping_status, fromdate1, todate1);  
    }else if(check_enrolled_date == '1') {
        getrpmenrolledpatientlist(practice, patient, shipping_status, null, null);    
    }else{
        getrpmenrolledpatientlist(practice, patient, shipping_status, null, null);  
    }


    
});


$("#month-reset").click(function(){   
    $('#practices').val('').trigger('change');
    $('#patient').val('').trigger('change'); 
    $('#shippingstatus').val('').trigger('change'); 
    // var shipping_status=$('#shippingstatus').val();
    $('#fromdate').val(firstDayWithSlashes);                         
    $('#todate').val(currentdate);
    var fromdate1=$('#fromdate').val();
    var todate1=$('#todate').val();
    getrpmenrolledpatientlist(null,null,null,null,fromdate1,todate1);   
});

function getrefreshtable(){
    var practice=$('#practices').val();
    var patient=$('#patient').val();
    var shipping_status=$('#shippingstatus').val();
    var shipping_status=$('#shippingstatus').val();
    var check_enrolled_date=$('#check_enrolled_date').val();
    var fromdate1=$('#fromdate').val();
    var todate1=$('#todate').val(); 

    if(check_enrolled_date == 'on'){  
        getrpmenrolledpatientlist(practice, patient, shipping_status, null, null);     
   }else{
        getrpmenrolledpatientlist(practice, patient, shipping_status, fromdate1, todate1);
    }
}
function shippingdetail(rowid) {
    // Set the patient_id value in the hidden field
    $('#patientIdField').val(rowid);    
    // getshippinglist(rowid);
}

function devicedetails(rowid) {
    // Set the patient_id value in the hidden field
    $('#patientIdField1').val(rowid); 
    $("form[name='device_form'] #device_code").removeClass('is-invalid');
    $("form[name='shippdevice_forming_form'] #device_code").next('.invalid-feedback').html('');
    $("#device_form")[0].reset();
    $('#devicedetailsmodel').modal('show');  
    getdevicecode(rowid); 

    var partner_id = document.getElementById("partner_id");
    var defaultValue = "3"; 
    for (var i = 0; i < partner_id.options.length; i++) {
        if (partner_id.options[i].value === defaultValue) {
            partner_id.selectedIndex = i;
            break;
        }
    }

    var partner_devices_id = document.getElementById("partner_devices_id");
    var defaultValue1 = "17"; 
    for (var i = 0; i < partner_devices_id.options.length; i++) {
        if (partner_devices_id.options[i].value === defaultValue1) {
            partner_devices_id.selectedIndex = i;
            break;
        }
    }
}


function shipping_status(rowid,shipping_status){    
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
 
        var data = {
            id: rowid,
            shipping_status : shipping_status      
        }
        //alert(status_flag);
        $.ajax({ 
            type: 'POST',
            url: '/reports/shipping-statuschange',
            data: {id: rowid, shipping_status : shipping_status},
            success: function (data) { 
                $("#shipping-success-alert").show();
                var scrollPos = $(".main-content").offset().top;
                $(window).scrollTop(scrollPos);
                //getshippingListReport(practice,patient,modules,activedeactivestatus);
                getrefreshtable();
                //setTimeout($("#shipping-success-alert").hide(), 20000);
                setTimeout(function () {
                    $('#shipping-success-alert').alert('close');
                }, 3000);

            }
        });
    }

</script>
@endsection