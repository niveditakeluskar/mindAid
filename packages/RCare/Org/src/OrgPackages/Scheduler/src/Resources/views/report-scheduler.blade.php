@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
<!-- added by ashwini mali 13-12-2022 -->
<!-- <div class="separator-breadcrumb border-top"></div> -->

<div _ngcontent-rei-c8="" class="row">
   
   <div _ngcontent-rei-c8="" style="width: 22%;margin-left: 1%; margin-right: 2%;" >
   <a href="{{ route('reportscheduler.totalpatientslist') }}" target="blank">
      <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Myspace"></i>
            <div _ngcontent-rei-c8="" class="content">
               <!-- <p _ngcontent-rei-c8="" class="text-muted" data-toggle="modal"  data-target="#mypatientModal" target="allpatient" style="width: 86px;height: 30px;">Patients</p> -->
               <p _ngcontent-rei-c8="" class="text-muted" style="width: 86px;height: 30px;">Patients</p><br>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" id="totalpatient"></p>
            </div>
         </div>
      </div>
      </a>
   </div>

   <div _ngcontent-rei-c8=""  style="width: 22%;margin-right: 2%;" >
   	<a href="{{ route('reportscheduler.totalactivepatient') }}" target="blank">
      <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Add-UserStar"></i>
            <div _ngcontent-rei-c8="" class="content">
               <p _ngcontent-rei-c8="" class="text-muted"  style="width: 55px;height: 30px;">Active Patient</p><br> 
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" id="totalPatientActive"></p>
            </div>
         </div>
      </div>
      </a>
   </div>
   <div _ngcontent-rei-c8="" style="width: 22%;margin-right: 2%;">
   	<a href="{{ route('reportscheduler.totalassignedtaskpatient') }}" target="blank">
      <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Checked-User"></i>
            <div _ngcontent-rei-c8="" class="content">
               <p _ngcontent-rei-c8="" class="text-muted"  style="width: 99px;height: 30px;">Assigned Task for Active Patient</p><br>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" id="totalPatientAssignTask"></p>
            </div>
         </div>
      </div>
      </a>
   </div> 
   <div _ngcontent-rei-c8="" style="width: 22%;margin-right: 2%;">
   	<a href="{{ route('reportscheduler.totalnonassignedtaskpatient') }}" target="blank">
      <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Remove-User"></i>
            <div _ngcontent-rei-c8="" class="content">
               <p _ngcontent-rei-c8="" class="text-muted" style="width: 99px;height: 30px;">Non Assigned Task for Active Patient</p><br>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2"  id="totalnonassignedpatient"></p>
            </div>
         </div>
      </div>
      </a>
   </div>
  </div>
<!-- close ashwini mali -->

<div class="row">
  <div class="col-lg-12 mb-3"> 
    <div class="card">
        <div class=" card-body"> 
          <div class="row">
            <div class="col-md-10">
             <div class="alert alert-success" id="success-alert" style="display: none;">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>Report Scheduler Data saved successfully! </strong><span id="text"></span>
              </div> 
              <div id="success"></div>
            </div>
            <div class="col-md-2">
              <a class="btn btn-success btn-sm mb-4 ml-5" data-toggle="modal" data-target="#add_reportscheduler_modal" id="addReportScheduler">Schedule a Report</a>  
            </div>
            <div class="separator-breadcrumb border-top"></div>
          <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
              @include('Theme::layouts.flash-message')
            <div class="table-responsive">
              <table id="reportscheduler-list" class="display table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
                          <th width ="10px">Sr No.</th>
                          <th width ="10px">Report Name</th> 
                          <th width ="10px">Users Name</th>       
                          <th width ="10px">Frequency</th>
                          <th width ="10px">Report Format</th>
                          <th width ="10px">Start Date</th> 
                          <th width ="10px">Day of Execution</th>
                          <th width ="10px">Time of Execution</th>
                          <th width ="10px">Created by</th>
                          <th width ="10px">Last Modified On</th> 
                          <th width ="10px">Action</th>
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
<!-- Modal -->
<div class="modal fade" id="add_report_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="
    width: 800px!important; 
    margin-left: 280px"> 
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1">Schedule a Report</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
            <form action="{{route('save.report.scheduler')}}"  method="POST" name="AddReportSchedulerForm" id="AddReportSchedulerForm">
                {{ csrf_field() }}
                <input type="hidden" id="checkcounter" value="">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group"> 
                        <div class="row">
                            <div class="col-md-3 form-group mb-3 ">
                                <label for="practicename">Report  <span style="color:red">*</span></label>
                                @selectReportName("report_id",["id"=>"report_id"]) 
                            </div>

                            <div class="col-md-3 form-group mb-3">
                                <label for="name" class="control-label">Users<span style="color:red">*</span></label>
                                <div class="wrapMulDrop">
                                    <button type="button" id="multiDrop" class="multiDrop form-control col-md-12">Select Users<i style="float:right;" class="icon ion-android-arrow-dropdown"></i></button> 
                                    <?php if (isset($all_users[0]->id) && $all_users[0]!=''){?>
                                    <ul style="overflow-y: scroll;height: 170px;">
                                        <?php foreach ($all_users as $key => $value): ?> 
                                        <li id="list_<?php echo $value->id;?>">
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="user_id[<?php echo $value->id;?>]"  id ="users_<?php echo $value->id;?>" type="checkbox" value =<?php echo $value->id ;?>><span class=""><?php echo $value->f_name.' '.$value->l_name?></span><span class="checkmark"></span>             
                                            </label> 
                                        </li>
                                        <?php endforeach ?> 
                                    </ul>
                                    <?php }else{?> 
                                    <?php }?>
                                </div>
                                <div class="is-invalid" id="user-required" style="color:red;font-size: 80%;"></div>
                            </div>


                            <div class="col-md-3 form-group mb-3">
                              <label for="reportformat">Report Format<span style="color:red">*</span></label>
                              <select class="custom-select" name="report_format" id="report_format">
                                <option value="">Select Format</option>
                                <option value="excel">Excel</option>
                              </select> 
                              <div class="invalid-feedback"></div>
                            </div>

                            
                            <div class="col-md-3 form-group mb-3 ">
                              <label for="frequency">Frequency <span style="color:red">*</span></label>
                              <select class="custom-select" name="frequency" id="frequency">
                                <option value="">Select Frequency</option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option> 
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                              </select> 
                              <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-4 form-group mb-3">
                                <label>Start Date<span style="color: red">*</span></label>
                                 @date("date_of_execution", ["id" => "date_of_execution"]) 
                                <!-- @date("start_date", ["id" => "start_date"]) -->
                            </div>
                            
                            <div class="col-md-4 form-group mb-3" id="days">
                                <label>Day of Execution <span style="color: red">*</span></label>
                                <div class="forms-element">
                                <select name="day_of_execution" id="day_of_execution" class="form-control">
                                    @for($i=1; $i < 32 ; $i++) <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                </select> 
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-4 form-group mb-3" style='display:none'; id="month">
                                <label>Month for execution <span style="color: red">*</span></label>
                                <select name="month_of_execution" id="month_of_execution" class="form-control">
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>  
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        
                            <div class="col-md-4 form-group mb-3" style='display:none'; id="week">
                                <label>Day of Execution <span style="color: red">*</span></label>
                                <div class="forms-element">
                                <select name="week_of_execution" id="week_of_execution" class="select2">
                                    <option value="1">Monday</option>
                                    <option value="2">Tuesday</option> 
                                    <option value="3">Wednesday</option>
                                    <option value="4">Thursday</option>
                                    <option value="5">Friday</option>
                                    <option value="6">Saturday</option>
                                    <option value="7">Sunday</option>
                                </select> 
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-4 form-group mb-3">
                                <label for="report_time_of_execution">Time of Execution (24 hours format)<span style="color: red">*</span></label>
                                @timetext("report_time_of_execution",["id" => "report_time_of_execution"])
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label>Comments<span style="color: red">*</span></label>
                                @text("comments", ["id" => "report_comments"])
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit"  class="btn  btn-primary m-1 save_practice" id="buttonHeading1">Save</button>
                                <!-- <button type="button" class="btn btn-info float-left additionalProvider" id="additionalProvider">Add Provider</button> -->
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


