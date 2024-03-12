@extends('Theme::layouts.master')
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')

<div class="breadcrusmb">

    <div class="row">
    <div class="col-md-11">
       <h4 class="card-title mb-3">ECG RPM Alert</h4>
    </div>
    <!--  <div class="col-md-1">
     <a href="javascript:void(0)" id="addDevices" class="addDevice"><i class="add-icons i-Administrator" data-toggle="tooltip" data-placement="top" title="Add Devices"></i><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Devices"></i></a>  
    </div> -->
    </div>
   <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                <form id="api_exception_form" name="api_exception_form"  action ="">
                @csrf
                <div class="form-row">
                    <div class="col-md-2 form-group mb-2">
                        <label for="date">From Date</label>
                        @date('date',["id" => "fromdate"])
                                               
                    </div>
                     <div class="col-md-2 form-group mb-3">
                        <label for="date">To Date</label>
                        @date('date',["id" => "todate"])
                                              
                    </div>
                    
                    <div class="col-md-2 form-group mb-3">
                       <button type="button" id="searchbutton" class="btn btn-primary mt-4">Search</button>                   
                       <button type="button" id="resetbutton" class="btn btn-primary mt-4">Reset</button>
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
            <div class="card-body">
                <div class="alert alert-success" id="success-alert" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong> <span id='text'></span> </strong>
                </div>
                <div id="success"></div>
           <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="rpm-alert-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                         <tr>
                            <th width="30px">Sr No.</th>
                            <th width="70px">Patient Name</th>
                            <th width="50px">Device Code</th>
                            <th width="50px">Practice</th>
                             <th width="50px">Alert Type</th>
                            <th width="50px">Threshold</th>                            
                            <!-- <th width="50px">Care Plan</th> -->
                            <th width="50px">Flag</th>
                            <th width="50px">Addressed By</th>
                            <th width="50px">Addressed Time</th>     
                            <th width="50px">Timestamp</th>
                             <th width="50px">Obervation Id</th>
                            <th width="50px">Match Status</th>
                            <th width="50px">Alert Status</th>                           
                            <th width="50px">Notes</th>
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


<div class="modal fade" id="rpm_alert_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="
    width: 800px!important;
    margin-left: 280px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">ECG RPM Alert Notes</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <form  name="rpm_alert_form" id="rpm_alert_form" method="POST" class="form-horizontal">
                    {{ csrf_field() }}
                   
                        <div class="row">
                            <div class="col-md-12 form-group mb-3">
                                
                                <label for="Notes">Notes : </label>
                                <div class="forms-element" id="notes">
                                  
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>    
                         </div>  
                    <div class="card-footer" >  
                        <div class="mc-footer">
                            <div class="row">
                                <div class="col-lg-6 text-left" id="gotomm"></div>
                                <div class="col-lg-6 text-right">
                                    <button type="submit" class="btn  btn-primary m-1" style="display: none;">Submit</button>
                                    <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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
   
        $(document).ready(function() {
        	rpmECGAlert.init();
            util.getToDoListData(0, {{getPageModuleName()}});
        });

  
    </script>
@endsection