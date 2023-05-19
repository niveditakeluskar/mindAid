@extends('Theme::layouts_2.to-do-master')
@section('page-css')
<style>
  
  .hovicon {
  height: 20%;
  width: 20%;
  
  border-radius: 50%;
  position: relative;
  z-index: 9;
  
}

</style>
@section('page-title')
Message Log
@endsection    
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Messaging</h4> 
        </div>
    </div>       
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                
                <form id="report_form" name="report_form" method="post" action ="">
                @csrf
                <div class="form-row">
                    <div class="col-md-2 form-group mb-3">
                        <label for="practicegrp">{{config('global.practice_group')}}</label>
                         @selectgrppractices("practicesgrp", ["class" => "select2","id" => "practicesgrp"]) 
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="practicename">Practice</label>
                          @selectGroupedPractices("practices",["id" => "practices", "class" => "form-control select2"])                         
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="caremanagername">Care Manger</label>
                        @selectcaremanagerwithAll("caremanagerid", ["class" => "select2","id" => "caremanagerid", "placeholder" => "Select Care Manager"])
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        
                        <label for="module">Module Name</label>
                            <select id="modules" class="custom-select show-tick" name="modules">
                            <option value="0">None</option>
                            <option value="3" selected>CCM</option>
                            <option value="2">RPM</option>
                            </select> 
                    
                    </div>
                    <div class="col-md-2 form-group mb-2" style="display:none">
                        <label for="date">From Date</label>
                        @date('date',["id" => "fromdate"])
                                               
                    </div>
                     <div class="col-md-2 form-group mb-3" style="display:none">
                        <label for="date">To Date</label>
                        @date('date',["id" => "todate"])
                                              
                    </div>
                    <div class="col-md-2 form-group mb-3">  
                          <label for="status">Status</label> 
                          <select id="status" name="status" class="custom-select show-tick" >
                            <option value="0" selected>All</option> 
                            <option value="accepted">Accepted</option>
                            <option value="queued">Queued</option>
                            <option value="sending">Sending</option>
                            <option value="sent">Sent</option> 
                            <option value="failed">Failed</option> 
                            <option value="delivered">Delivered</option> 
                            <option value="undelivered">Undelivered</option> 
                            <option value="receiving">Receiving</option> 
                            <option value="received">Received</option> 
                          </select>                          
                    </div>
                    <div class="col-md-2 form-group mb-3">  
                          <label for="status">Read Status</label> 
                          <select id="read_status" name="read_status" class="custom-select show-tick" >
                            <option value="2" selected>All</option> 
                            <option value="0">Read</option>
                            <option value="1">Unread</option>
                          </select> 
                    </div>      
                    <input type="hidden" name="unread_mess" id="unread_mess" value="0">
                    <div class="col-md-1 form-group mb-3">
                       <button type="button" class="btn btn-primary mt-4 ml-3" id="message">Search</button>
                    </div>
                    <div class="col-md-1 form-group mb-3">
                       <button type="button" class="btn btn-primary mt-4 ml-1" id="resetbutton">Reset</button>
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
                         
            <div class="messaging">
      <div class="inbox_msg">
        <div class="inbox_people">
          <div class="headind_srch">
            <div class="recent_heading">
            <ul class="nav nav-tabs" id="myIconTab" role="tablist">
                <li class="nav-item" onclick="$('#inbox_chat1').html(''); $('#inbox_chat1').hide();">
                    <a class="nav-link active" id="recent-icon-tab" data-toggle="tab" href="#ccm-call" role="tab" aria-controls="ccm-call" aria-selected="true"><i class="nav-icon color-icon i-Telephone mr-1"></i>Recent</a>
                </li>
                <li class="nav-item" onclick="getAllUser('concent patient list')">
                    <a class="nav-link" id="all-icon-tab" data-toggle="tab" href="#ccm-verification" role="tab" aria-controls="ccm-verification" aria-selected="false"><i class="nav-icon color-icon i-Gears mr-1"></i> All</a>
                </li>
                <!--li class="nav-item" onclick="$('#inbox_chat1').html(''); $('#inbox_chat1').hide();$('#unread_mess').val(1);">
                    <a class="nav-link" id="ccm-relationship-icon-tab" data-toggle="tab" href="#ccm-relationship" role="tab" aria-controls="ccm-relationship" aria-selected="false"><i class="nav-icon color-icon i-Spell-Check  mr-1"></i>Unread</a>
                </li-->
            </ul>
              <!--h4> <i class="i-Speach-Bubble-6" style ="margin-left: 70px;color: #27a8de;" onclick="getAllUser('concent patient list')"></i></h4-->
              
              <!--ul class="nav nav-tabs">
                  <li class="active"><a href="#">Recent</a></li>
                  <li><a href="#">All</a></li>
              </ul-->
            </div>
            <div class="srch_bar">
              <div class="stylish-input-group">
                <input type="text" class="search-bar" id="chat_serch" placeholder="Search" >
                <span class="input-group-addon">
                <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                </span> </div>
            </div>
          </div>
          <div class="inbox_chat" id="inbox_chat1" style="display:none">
            
          </div>
          <div class="inbox_chat" id="inbox_chat">
            
          </div>
        </div>
        <div class="d-flex bd-highlight" >
          <div class="img_cont col-md-1 hide" style="display:none">
            <img src="https://ptetutorials.com/images/user-profile.png" class="rounded-circle user_img">
            <span class="online_icon"></span>
          </div>
        
          <div class="user_info col-md-5 hide" style="display:none">
            <span id="chat_user_name"></span>
            <!--p id="chat_user_number"></p-->
          </div>
          <div class="user_info col-md-2 block hide"style="display:none" >
            <div class="hovicon effect-4 sub-b" id="mmlink"></div>
          </div>
          <div class="col-md-4 hide" style="display:none">
                                <div class="col-md-11 careplan">
                                    <label for="total time" data-toggle="tooltip" data-placement="right" title="Billable Time" data-original-title="Billable Time" style="color: white!important;"><i class="text-muted i-Clock-4" style="color: white!important;"></i> {{-- Total Time Elapsed --}}:
                                    <span class="last_time_spend" id="btime"></span></label>
                                    <label for="total time" data-toggle="tooltip" data-placement="right" title="Non Billable Time" data-original-title="Non Billable Time" style="color: white!important;">
                                     / <span class="non_billabel_last_time_spend" id="nbtime"></span></label>
                                    <!--button class="button" style="border: 0px none;background: #f7f7f7;outline: none;"><a href="/patients/registerd-patient-edit/" title="Edit Patient Info" data-toggle="tooltip" data-placement="top"  data-original-title="Edit Patient Info" ><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a></button-->
                                    <div class="demo-div">
                                        @hidden("timer_start",["id"=>"timer_start"])
                                        @hidden("timer_end",["id"=>"timer_end"])
                                        @hidden("page_landing_time",["id"=>"page_landing_time"])
                                        @hidden("patient_time",["id"=>"patient_time"])
                                        @hidden("pause_time",["id"=>"pause_time", "value"=>"0"])
                                        @hidden("play_time",["id"=>"play_time", "value"=>"0"])
                                        @hidden("pauseplaydiff",["id"=>"pauseplaydiff", "value"=>"0"])
                                        <div class="stopwatch" id="stopwatch" style="margin-top: -7px;">
                                            <i class="text-muted i-Timer1" style="color: white!important;"> :</i>
                                            <div id="time-container" class="container" data-toggle="tooltip" data-placement="right" title="Current Running Time" data-original-title="Current Running Time" style="color: white!important;"></div>
                                            <button class="button" id="start" data-toggle="tooltip" data-placement="top" title="Start Timer" data-original-title="Start Timer" style="background: #27a8de;"><img src="{{asset('assets/images/play.png')}}" style=" width: 28px;" /></button>
                                            <button class="button" id="pause" data-toggle="tooltip" data-placement="top" title="Pause Timer" data-original-title="Pause Timer" style="background: #27a8de;"><img src="{{asset('assets/images/pause.png')}}" style=" width: 28px;" /></button>
                                            <button class="button" id="stop" data-toggle="tooltip" data-placement="top" title="Stop Timer" data-original-title="Stop Timer" style="background: #27a8de;"><img src="{{asset('assets/images/stop.png')}}" style=" width: 28px;" /></button>
                                            <button class="button" id="reset" data-toggle="tooltip" data-placement="top" title="Reset Timer" data-original-title="Reset Timer" style="display:none;">Reset</button>
                                            <button class="button" id="resetTickingTime" data-toggle="tooltip" data-placement="top" title="resetTickingTime Timer" data-original-title="resetTickingTime Timer" style="display:none;">resetTickingTime</button>
                                            <a href="javascript:void(0)" id="log_time" data-toggle="tooltip" data-placement="right" title="Log Time" data-original-title="Log Time" 
                                            style="padding: 0px 8px;"><img src="{{asset('assets/images/log_time.png')}}" style=" width: 27px;" /></a>

                                        </div>
                                    </div>       
                                       
                                </div>
                            </div>
        </div>
        <div class="mesgs">
          <div class="msg_history">
               
          </div>
          <div class="type_msg">
            <div class="input_msg_write">
              <input type="text" class="write_msg" id="user_message" placeholder="Type a message" />
              <input type="hidden" name="user_id" id="user_id">
              <input type="hidden" name="user_number" id="user_number">
              <input type="hidden" name="user_name" id="user_name">
              <input type="hidden" name="start_time" value="00:00:00">
              <input type="hidden" name="end_time" value="00:00:00">
              <input type="hidden" name="hidden_id" id="hidden_id" value= "0">
              <input type="hidden" name="module_id" value="{{getPageModuleName()}}" >
              <input type="hidden" name="regi_mnth" id="regi_mnth" value="" >
              <button class="msg_send_btn" type="button" id="msg_send_btn"><i class="i-Paper-Plane" aria-hidden="true"></i></button>
            </div>
          </div>
        </div>
      </div>
      
      
      
      
    </div>
                
            </div>
        </div>
    </div>
</div>

<div id="concent-form" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
            <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Patient Edit </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                    <div id="concent-alert" class="alert alert-success" style="display: none;">
                    <button type="button" data-dismiss="alert" class="close">x</button> <strong> Consent Details Updated successfully! </strong>
                    <span id="text"></span></div>
                        <form action="{{ route("update.concent.data") }}" method="post" name ="concent_update_form"  id="concent_update_form">
                            @csrf   
                            <input type="hidden" name="uid" id="uid">
                            <div class="row">
								<div class="col-md-3">
											<label>Primary No. Country Code</label>
											@selectcountrycode("country_code", ["id" => "country_code"]) 
										</div>
										
									<div class="col-md-3">
										<label for="phone_primary">Primary Phone Number<span class="error">*</span></label>
										<div class="input-group form-group">
											<div class="input-group-prepend btn-group btn-group-toggle" >
												<label class="btn btn-outline-primary" for="mob">
												Preferred
												@input("radio", "preferred_contact", ["id" => "phone-primary-preferred", "value" => "0", "data-feedback" => "contact-preferred-feedback"])
												</label>
											</div>
											<!-- @phone("phone_primary") -->
											@phone("mob", ["id"=>"mob"])
										</div> 
									</div>
									<div class="col-md-1 form-group">
                                        <label for="phone_primary">Is Cell Phone<span class="error">*</span></label>
                                        <div class="mr-3 d-inline-flex align-self-center">
                                            <label class="radio radio-primary mr-3">
                                                <input type="radio" id="primary_cell_phone-yes"  formControlName="radio" name="primary_cell_phone" value="1">
                                                <span>Yes</span>
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="radio radio-primary mr-3">
                                                <input type="radio" id="primary_cell_phone-no" formControlName="radio" name="primary_cell_phone"  value ="0" >
                                                <span>No</span>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
									</div>
									<div class="col-md-2 form-group iscellmargin" id="content_text">
                                        <label for="phone_primary">Consent To Text<span class="error">*</span></label>
                                        <div class="mr-3 d-inline-flex align-self-center">
                                            <label class="radio radio-primary mr-3">
                                                <input type="radio" id="consent_to_text-yes"  formControlName="radio" name="consent_to_text" value="1">
                                                <span>Yes</span>
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="radio radio-primary mr-3">
                                                <input type="radio" id="consent_to_text-no" formControlName="radio" name="consent_to_text"  value ="0" >
                                                <span>No</span>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
										
									</div>
								</div> 
                                <div class="row">	
									<div class="col-md-3">
											<label>Secondary No. Country Code</label>
											@selectcountrycode("secondary_country_code", ["id" => "secondary_country_code"]) 
										</div>
										
									<div class="col-md-3">
										<label for="phone_secondary">Secondary Phone Number</label>
										<div class="input-group form-group">
											<div class="input-group-prepend btn-group btn-group-toggle" >
												<label class="btn btn-outline-primary" for="home_number">
												Preferred
												@input("radio", "preferred_contact", ["id" => "phone-secondary-preferred", "value" => "1", "data-feedback" => "contact-preferred-feedback"])
												</label>
											</div>
											<!-- @phone("phone_secondary") -->
											@phone("home_number", ["id"=>"home_number"])
										</div>
									</div>
									<div class="col-md-1 form-group">
                                        <label for="phone_primary">Is Cell Phone<span class="error">*</span></label>
                                        <div class="mr-3 d-inline-flex align-self-center">
                                            <label class="radio radio-primary mr-3">
                                                <input type="radio"  id="secondary_cell_phone-yes" formControlName="radio" name="secondary_cell_phone" value="1">
                                                <span>Yes</span>
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="radio radio-primary mr-3">
                                                <input type="radio" id="secondary_cell_phone-no" formControlName="radio" name="secondary_cell_phone"  value ="0" >
                                                <span>No</span>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
											
										</div>
								</div>                            
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary float-right submit-concent-text">Submit</button>
                                <button type="button" class="btn btn-default float-left" data-dismiss="modal">Close</button>
                            </div>
                        </form>
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

    <script type="text/javascript">
        var time = '00:00:00';
        var splitTime = time.split(":");
        var H = splitTime[0];
        var M = splitTime[1];
        var S = splitTime[2];
        //alert(time);
        // $("#timer_start").val(time);

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

        $(document).ready(function() {                
            var role = '<?php echo session()->get('role_type') ?>';
            if(role == 'Care Managers'){
                $('#caremanagerid').val(<?php echo session()->get('userid'); ?>).trigger('change');
                $('#caremanagerid').prop('disabled', 'disabled');
            }
           //alert('<?php echo session()->get('role_type') ?>');
            // $("#time-container").val(AppStopwatch.stopClock);	
            $('#fromdate').val(firstDayWithSlashes);                      
            $('#todate').val(currentdate);
           // util.getToDoListData(0, {{getPageModuleName()}});
            getChatUserList();
        });

        $('#resetbutton').click(function(){
            $('#practicesgrp').val('').trigger('change');
            $('#practices').val('').trigger('change');

            $('#modules').val(3);
            $('#caremanagerid').val('').trigger('change');

            var practicesgrp =$('#practicesgrp').val();
            var practice=$('#practices').val();
            var caremanagerid=$('#caremanagerid').val();
            var moduleid=$('#modules').val(); 
            var fromdate1=$('#fromdate').val();
            var todate1=$('#todate').val();
            var status = $('#status').val();
            $('#fromdate').val(firstDayWithSlashes);                      
            $('#todate').val(currentdate); 
            $('#status').val('0');
            $('#read_status').val('0');
            // getAdditionalActivitiesList(practicesgrp,practice,caremanagerid,moduleid,fromdate1,todate1,status);
        });

        $("#chat_serch").keyup(function () {
            var val = $(this).val();
            var id = $('#caremanagerid').val();
            if(id == ''){
                id = null;
            }
            if(val.length >= 1){
                $.ajax({
                    type: 'get',
                    url: '/messaging/get-user-list/'+val+'/'+id,
                    success: function(response) {
                        // $(".inbox_chat1").html('');
                        $("#inbox_chat1").html(response);
                        $('#inbox_chat1').show();
                    },
                });
            }else{
                $("#inbox_chat1").html('');
                $('#inbox_chat1').hide();
            }
        });

       function getAllUser(val){
        var id = $('#caremanagerid').val();
        if(id == ''){
                id = null;
            }
            $("#preloader").show();
        $.ajax({
                    type: 'get',
                    url: '/messaging/get-user-list/'+val+'/'+id,
                    success: function(response) {
                        // $(".inbox_chat1").html('');
                        $("#inbox_chat1").html(response);
                        $('#inbox_chat1').show();
                        $("#preloader").hide();
                    },
                });
       }

        function callBackgetHistory(){
            var id = $('#user_id').val();
            var name = $('#user_name').val();
            var number =  $('#user_number').val();
            $("#user_id").val(id);
            $("#user_number").val(number);
            $("#user_name").val(name);
            $('#chat_user_name').html(name);
            $('.chat_list').removeClass('active_chat');
            $("#"+id).addClass('active_chat');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/messaging/get-message-history',
                data: 'id=' + id,
                success: function(response) {
                    $(".msg_history").html(response.chat);
                },
            });
            setTimeout(function () { callBackgetHistory(); }, 10000);
            //getHistory($('#user_id').val(), $('#user_number').val(), $('#user_name').val());

        }

        function getConsent(id,number,name){
            $("#user_id").val(id);
            $("#user_number").val(number);
            $("#user_name").val(name);
            $('#chat_user_name').html(name);
            $('.chat_list').removeClass('active_chat');
            $("#"+id).addClass('active_chat');
            $(".msg_history").html('<p style="text-aligned:center">This Patient Not conscnt with text. Assign conscent to text <a href=>Click </a></p>');
        }

        function getHistory(id,number,name){
            $("#user_id").val(id);
            $("#user_number").val(number);
            $("#user_name").val(name);
            $('#chat_user_name').html(name);
            $("#hidden_id").val(id); 
            $('.chat_list').removeClass('active_chat');
            $("#"+id).addClass('active_chat');
            var patient_id = $("#hidden_id").val(); 
            var module_id = 3;//$("input[name='module_id']").val();
            util.getPatientPreviousMonthCalender(patient_id,module_id);
            var regis =  $("input[name='regi_mnth']").val();
            var d = new Date();
            var currentMonth= moment().format("MM");
            var currentYear = moment().format("YYYY"); 
            $("#display_month_year").html(moment().format("MMM YYYY"));
            newPreviousMonth = moment(d, 'YYYY/MM/DD');
            $('#log_time').attr('onclick',"util.logTimeManually($('#timer_start').val(), $('#time-container').text(), $('#user_id').val(),{{ getPageModuleName() }}, {{ getPageSubModuleName() }}, 0, 1, $('#user_id').val(), 0, 'log_time_<?php $uriSegments = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));echo $uriSegments[1].'_'.$uriSegments[2];?>');" );
            

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/messaging/get-message-history',
                data: 'id=' + id,
                success: function(response) {
                    $('.hide').show();
                    $("#time-container").val(AppStopwatch.pauseClock);	
                    $("#mmlink").html(response.link);
                    $(".msg_history").html(response.chat);
                    $("#btime").html(response.billable_time);
                    $("#nbtime").html(response.non_billabel_time);
                    document.getElementsByClassName('msg_history')[0].scrollTop = document.getElementsByClassName('msg_history')[0].scrollHeight;
                    var year = (new Date).getFullYear();
                    var month = (new Date).getMonth() + 1; 
                    util.updateBillableNonBillableAndTickingTimer(id, '3');
                    util.getPatientDetails(id,{{getPageModuleName()}});
                    util.getPatientPreviousMonthNotes(id, {{getPageModuleName()}}, month, year);
                    util.getPatientCareplanNotes(id, {{getPageModuleName()}});
                    util.getPatientStatus(id, 3);
                    util.gatCaretoolData(id, {{getPageModuleName()}});
                    util.getToDoListData(id, {{getPageModuleName()}});
                },
            });
            //alert($('.active_chat').attr('id'));
            setTimeout(function () { callBackgetHistory(); }, 10000);
        }

        $('#msg_send_btn').click(function(){
            $("#time-container").val(AppStopwatch.pauseClock);
            var timer_start = $("#timer_start").val();
            var timer_paused = $("#time-container").text().replace(/ /g, '');
            $("input[name='start_time']").val(timer_start);
            $("input[name='end_time']").val(timer_paused);
            //alert(timer_paused);
            // $("#timer_start").val(timer_paused);
            $("#timer_end").val(timer_paused);
            $("#time-container").val(AppStopwatch.startClock);
            var id = $("#user_id").val();
            var text = $("#user_message").val();
            var number = $("#user_number").val();
            var name = $("#user_name").val();
            if(text != ""){
                var module = $('#modules').val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'post',
                    url: '/messaging/send-message',
                    data: 'id=' + id + '&text=' + text + '&module=' + module + '&number=' + number + '&timer_start=' + timer_start + '&timer_paused=' + timer_paused,
                    success: function(response) {
                        $("#user_message").val('');
                        getHistory(id,number,name);
                    },
                });
            }
        });

        function getChatUserList(){
            var practicesgrp =$('#practicesgrp').val(); 
            if(practicesgrp == ''){
                practicesgrp = null;
            }
            var practice=$('#practices').val();     
            if(practice == ''){
                practice = null;
            }
            var caremanager=$('#caremanagerid').val();
            if(caremanager == ''){
                caremanager = null;
            }
            var moduleid=$('#modules').val();    
            var fromdate1=$('#fromdate').val();
            var todate1=$('#todate').val();   
            var status = $('#status').val();      
            var eDate = new Date(todate1);
            var uid = $('#user_id').val();
            var read_status = $('#read_status').val();

            var sDate = new Date(fromdate1);
            if(fromdate1!= '' && todate1!= '' && sDate> eDate) {
                alert("Please ensure that the To Date is greater than or equal to the From Date.");
                return false;
            } else {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'post',
                    url: '/messaging/get-message',
                    data: 'practicesgrp=' + practicesgrp + '&practice='+practice+'&caremanager='+caremanager+'&module='+moduleid+'&fromdate='+fromdate1+'&todate='+todate1+'&status='+status+'&uid='+uid+'&read_status='+read_status,
                    success: function(response) {
                        $("#inbox_chat").html(response);
                       // $('#inbox_chat1').hide();
                    },
                });
            }
            setTimeout(function () { getChatUserList(); }, 10000);
        }

        $('#message').click(function(){ 
            getChatUserList();  
        });

        function viewSendMessage(messageid){
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            $.ajax({
                type: 'post',
                url: '/messaging/view-message',
                data: 'id=' + messageid,
                success: function(response) {
                    $("#viewmessage").html(response);
                    $("#ajaxModel").modal('show');
                },
            });
        }

        function reSend(messageid){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/messaging/resend-message',
                data: 'id=' + messageid,
                success: function(response) {
                    $("#viewmessage").html(response);
                },
            });
        }
        $('body').on('click', '.editContact', function () {
            var val = $(this).data('id');
            $('#concent-form').modal('show');
            $("#uid").val(val);
            $.ajax({
                type: 'get',
                url: '/messaging/get-user-concent/'+val,
                success: function(response) {
                    $("#country_code").val(response[0].country_code);
                    $("#mob").val(response[0].mob);
                    $("#home_number").val(response[0].home_number);
                    $("#secondary_country_code").val(response[0].secondary_country_code);
                    if(response[0].primary_cell_phone == 1){
                        $("#primary_cell_phone-yes").prop( "checked", true );
                    }else{
                        $("#primary_cell_phone-no").prop( "checked", true );
                    } 
                    if(response[0].consent_to_text == 1){
                        $("#consent_to_text-yes").prop( "checked", true );
                    }else{
                        $("#consent_to_text-no").prop( "checked", true );
                    }
                    if(response[0].secondary_cell_phone == 1){
                        $("#secondary_cell_phone-yes").prop( "checked", true );
                    }else{
                        $("#secondary_cell_phone-no").prop( "checked", true );
                    }
                },
            });
        });
    </script>
    <script src="{{ asset('assets/js/timer.js?id='.filemtime(public_path('assets/js/timer.js'))) }}"></script>
@endsection 