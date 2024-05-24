@extends('Theme::layouts_2.to-do-master')
@section('main-content')
    @foreach($patient as $checklist)		

    <div class="separator-breadcrumb "></div>
    <div class="row text-align-center">
        @include('Theme::layouts_2.flash-message')  
        <div class="col-md-12">
            {{ csrf_field() }}
            <!--Add view Patient Overview -->
			@include('Patients::components.patient-basic-info')
			@include('Rpm::deviceTraning.device-traning-step')
        </div>
    </div>
    @endforeach
@foreach($patient as $checklist)

<div class="breadcrusmb">
	<div class="row">
		<div class="col-md-112">
			<!-- <h4 class="card-title mb-3">{{$checklist->fname}} {{$checklist->lname}}</h4> -->
		</div>
	</div>
	<input type="hidden" id="hidden_area" value="{{$checklist->id}}" name="hidden_area">
	<input type="hidden" id="content1_id" value="" name="">
	<input type="hidden" id="content2_id" value="" name="">
	<input type="hidden" id="content3_id" value="" name="">
	<input type="hidden" id="component_id" value="" name="">
	<input type="hidden" id="module_id" value="" name="">
	<input type="hidden" id="stage_id1" value="" name="">
	<input type="hidden" id="stage_id2" value="" name="">
	<input type="hidden" id="stage_id3" value="" name="">
	<input type="hidden" id="time_stop1" value="" name="">
	<input type="hidden" id="time_stop2" value="" name="">
	<!-- ss -->             
</div>

@endforeach
@endsection
@section('page-js')

<script type="text/javascript">
    var duration = moment.duration({
        'seconds': 30,
        'hour': 1,
        'minutes': 10
    });

    var timestamp = new Date(0,0,0,2,10,30);
    var interval = 1;
    setInterval(function () {
        timestamp = new Date(timestamp.getTime() + interval*1000);
        
        duration = moment.duration(duration.asSeconds() + interval, 'seconds');
        //.asSeconds() 
        // $('.countdown').text(Math.round(duration.asHours()) + 'h:' + Math.round(duration.asMinutes()) + 'm:' + Math.round(duration.asSeconds()) + 's'); //.seconds() 

       // $('.countdown1').text(duration.days() + 'd:' + duration.hours() + 'h:' + duration.minutes() + 'm:' + duration.seconds() + 's');
       // $('.countdown2').text(timestamp.getDay()+'d:'+timestamp.getHours()+'h:'+timestamp.getMinutes()+'m:'+timestamp.getSeconds()+'s');
        
    }, 1000);

    var AppStopwatch = (function () {
        var counter = 0,
            $stopwatch = {
                el: document.getElementById('stopwatch'),
                container: document.getElementById('time-container'),
                startControl: document.getElementById('start'),
                pauseControl: document.getElementById('pause'),
                stopControl: document.getElementById('stop')
            };

        var runClock;

        function displayTime() {
            var timestamp = new Date(0,0,0,0,0,0);
            var interval = 1;
            timestamp = new Date(timestamp.getTime() + interval*1000);
            $stopwatch.container.innerHTML = moment().hour(00).minute(00).second(counter++).format('HH : mm : ss');
        }

        function startWatch() {
            $("#start").hide();
            $("#pause").show();
            runClock = setInterval(displayTime, 1000);
        }

        function pauseWatch() {
            $("#start").show();
            $("#pause").hide();
            clearInterval(runClock);
        }

        function stopWatch() {
            
            // alert(counter);
            $("#display-val").html($stopwatch.container.innerHTML);
            clearInterval(runClock);
            counter=0;
            // $("#start").show();
        }

        return {
            startClock: startWatch,
            pauseClock: pauseWatch,
            stopClock: stopWatch,
            $start: $stopwatch.startControl,
            $pause: $stopwatch.pauseControl,
            $stop: $stopwatch.stopControl
        };
    })();

    //AppStopwatch.$start.addEventListener('click', AppStopwatch.startClock, false);
    //AppStopwatch.$pause.addEventListener('click', AppStopwatch.pauseClock, false);
    //AppStopwatch.$stop.addEventListener('click', AppStopwatch.stopClock, false);
    $( document ).ready(function() {
		util.stepWizard('tsf-wizard-1');
        $("#start").hide();
        $("#pause").show();
        $("#time-container").val(AppStopwatch.startClock);
    });

    function stopRunningClock() {
        console.log("in stop running clock");
        // AppStopwatch.$stop.addEventListener('click', AppStopwatch.stopClock, false);
        $("#time-container").val(AppStopwatch.stopClock);
        $("#start").show();
        $("#pause").hide();
    }


//    var timer = new Timer();
$(document).ready(function(){ 
		// debugger;
			$("#device_id").on('change', function(){
				$("#software_download_content").show();
				get_content($(this).val());
		       });
			   getPatientData();
			  
				  $(function() {
		        $(".checkbox").click(function(){

		        $('.delete').prop('disabled',$('input.checkbox:checked').length < 1);
		    });
		});
	function get_content(val){
		patient_id =  $("#hidden_area").val();
		$.ajax({
			type: 'post',
			url: '/rpm/getContent',
			data: {
				_token: '{!! csrf_token() !!}',
				device_id:val,
				patient_id : patient_id
			},
			dataType : 'json',
			success: function (response) {
				$("#content1").html(jQuery.parseJSON(response.stage1_content[0].content).message);
				$("#content2").html(jQuery.parseJSON(response.stage2_content[0].content).message);
				$("#content3").html(jQuery.parseJSON(response.stage3_content[0].content).message);
				$("#content1_id").val(jQuery.parseJSON(response.stage1_content[0].id));
				$("#content2_id").val(jQuery.parseJSON(response.stage2_content[0].id));
				$("#content3_id").val(jQuery.parseJSON(response.stage3_content[0].id));
				$("#component_id").val(jQuery.parseJSON(response.stage2_content[0].component_id));
				$("#module_id").val(jQuery.parseJSON(response.stage3_content[0].module_id));
				$("#stage_id1").val(jQuery.parseJSON(response.stage1_content[0].stage_id));
				$("#stage_id2").val(jQuery.parseJSON(response.stage2_content[0].stage_id));
				$("#stage_id3").val(jQuery.parseJSON(response.stage3_content[0].stage_id));
			}
		});
	}
	
});		

function get_content(val){
	// debugger;
		patient_id =  $("#hidden_area").val();
		$.ajax({
			type: 'post',
			url: '/rpm/getContent',
			data: {
				_token: '{!! csrf_token() !!}',
				device_id:val,
				patient_id : patient_id
			},
			dataType : 'json',
			success: function (response) {
				$("#content1").html(jQuery.parseJSON(response.stage1_content[0].content).message);
				$("#content2").html(jQuery.parseJSON(response.stage2_content[0].content).message);
				$("#content3").html(jQuery.parseJSON(response.stage3_content[0].content).message);
				$("#content1_id").val(jQuery.parseJSON(response.stage1_content[0].id));
				$("#content2_id").val(jQuery.parseJSON(response.stage2_content[0].id));
				$("#content3_id").val(jQuery.parseJSON(response.stage3_content[0].id));
				$("#component_id").val(jQuery.parseJSON(response.stage2_content[0].component_id));
				$("#module_id").val(jQuery.parseJSON(response.stage3_content[0].module_id));
				$("#stage_id1").val(jQuery.parseJSON(response.stage1_content[0].stage_id));
				$("#stage_id2").val(jQuery.parseJSON(response.stage2_content[0].stage_id));
				$("#stage_id3").val(jQuery.parseJSON(response.stage3_content[0].stage_id));
				getTimerData();
			}
		});
	}

function save(click){
		
		time_start = '';
		if(click == 2){		
			time_start = $("#time_stop1").val();
		}
		if(click == 3){		
			time_start = $("#time_stop2").val();
		}
		device_id = $("#device_id").val();
		content1_id =  $("#content1_id").val();
		content2_id =  $("#content2_id").val();
		content3_id =  $("#content3_id").val();
		component_id =  $("#component_id").val();
		module_id =  $("#module_id").val();
		stage_id1 =  $("#stage_id1").val();
		stage_id2 =  $("#stage_id2").val();
		stage_id3 =  $("#stage_id3").val();
		time_stop = $('.values').html();
		$.ajax({
			type: 'post',
			url: '/rpm/saveDeviceTraining',
			data: {
				_token: '{!! csrf_token() !!}',
				device_id  : device_id,
				patient_id  : patient_id,
				content1_id  : content1_id,
				content2_id  : content2_id,
				content3_id  : content3_id,
				component_id : component_id,
				module_id    : module_id,
				stage_id1     : stage_id1,
				stage_id2     : stage_id2,
				stage_id3     : stage_id3,
				time_start   : time_start,
				time_stop    : time_stop,
				click : click
			},
			/*dataType : 'json',*/
			success: function (response) {
				// response.trim();
				// alert(response);
				if(response == '        Device Training Completed'){
					toastr.success(response);
				}			
			}
		});
	}

	$(function(){
	  $("input[name$='software-download-Protocol']").change(function(){
		  debugger;
	  var item=$(this);

	  if(item.is(":checked"))
	  {
		var time = $('.values').html();
		// alert(time);
		$(".device_step1").trigger('click');
	  	save("1");
		$("#time_stop1").val(time);
		$("#check1").attr('data-type','next');

	    // window.location.href= item.data("target");
	  }
   
	 });
	  $("input[name$='software-usage-instruction']").change(function(){
	  var item=$(this);

	  if(item.is(":checked"))
	  {
		var time = $('.values').html();
		$(".device_step2").trigger('click');
		// alert(time);
	  	save("2");
		$("#time_stop2").val(time);
		
	    window.location.href= item.data("target");
	  }
	       
	 }); 
	   $("input[name$='device-traning']").change(function(){
	  var item=$(this);

	  if(item.is(":checked"))
	  {
		//   debugger;
		// var timer = new Timer();
			timer.stop();
			// timer.addEventListener('secondsUpdated', function (e) {
				var time = $('.values').html();
				// alert(time);
			// });
		// var stop_time = $('#chronoExample .values').html(timer.getTimeValues().minutes);
		// var time = JSON.stringify(stop_time);
		// alert(time);
		// timer.stop();
	  	save("3");
	    window.location.href= item.data("target");
	  }
	       
	 }); 

  });

  function getTimerData(){
		// debugger;
		
			// nextStep();
            // var pageURL = $(location).attr("href");
            // var str = pageURL.substr(0,pageURL.indexOf('#'));
            // window.location=str+""+'#step-3';
			module_id =  $("#module_id").val();
			component_id =  $("#component_id").val();
			patient_id =  $("#hidden_area").val();

		$.ajax({
			type: 'get',
			url: '/rpm/getTimerData',
			data: {
				_token: '{!! csrf_token() !!}',
				patient_id : patient_id,
				component_id : component_id,
				module_id : module_id
			},
			dataType : 'json',
			success: function (response) {
				// debugger;
				// alert(JSON.stringify(response));
				// stage_id = response.max_stage_id;
				// device_id = response.device_id;
				// download_protocol_completed = response.download_protocol_completed;
				// usage_instruction_completed = response.usage_instruction_completed;
				// device_training_completed = response.device_training_completed;

				// if(download_protocol_completed){
				// 	$('#check1').prop('checked', true);
				// }
				// if(usage_instruction_completed){
				// 	$('#check2').prop('checked', true);
				// }
				// if(device_training_completed){
				// 	$('#finalCheck').prop('checked', true);
				// }
				if(response !=0){
					stage_id = response.max_stage_id;
					device_id = response.device_id;
					download_protocol_completed = response.download_protocol_completed;
					usage_instruction_completed = response.usage_instruction_completed;
					device_training_completed = response.device_training_completed;
					sub_module_timer = response.sub_module_timer_off.split(":");
					// console.log(sub_module_timer.split(":"));
					hrs = sub_module_timer[0];
					mins = sub_module_timer[1];
					secs = sub_module_timer[2];
					
					// startTimerFrom(hrs, mins, secs);
					
					// displayTime();
					// $stopwatch = {
					// 	el: document.getElementById('stopwatch'),
					// 	container: document.getElementById('time-container'),
					// 	startControl: document.getElementById('start'),
					// 	pauseControl: document.getElementById('pause'),
					// 	stopControl: document.getElementById('stop')
					// };
					// var timestamp = new Date(0,0,0,0,0,0);
					// var interval = 1;
					// timestamp = new Date(timestamp.getTime() + interval*1000);
					// $stopwatch.container.innerHTML = moment().hour(hours).minute(minutes).second(seconds++).format('HH : mm : ss');
					// $("#sub_module_timer").text(moment().hour(hours).minute(minutes).second(seconds++).format('HH : mm : ss'));
					// function startWatch() {
					// $("#start").hide();
					// $("#pause").show();
					
					// runClock = setInterval(displayTime(hours,minutes,seconds), 1000);
				// }
					$("#device_id").val(device_id);
					// get_content(device_id);
					$("#software_download_content").show();
					next_stage_id = stage_id+1;
					if(download_protocol_completed){
					$('#check1').prop('checked', true);
					}
					if(usage_instruction_completed){
						$('#check2').prop('checked', true);
					}
					if(device_training_completed){
						$('#finalCheck').prop('checked', true);
					}
				}
				else{
					stage_id = response;
					next_stage_id = stage_id+1;
				}
				
				// alert(response);
			
				// alert(next_stage_id);
				var pageURL = $(location).attr("href");
				var str = pageURL.substr(0,pageURL.indexOf('#'));
				window.location=str+""+'#step-'+next_stage_id;

				// stage_id = response+1;
				// var pageURL = $(location).attr("href");
				// var str = pageURL.substr(0,pageURL.indexOf('#'));
				// window.location=str+""+'#step-'+stage_id;
			}
		});
        }
		function getPatientData(){

		// debugger;
			patient_id =  $("#hidden_area").val();
			$.ajax({
				type: 'get',
				url: '/rpm/getPatientData',
				data: {
					_token: '{!! csrf_token() !!}',
					patient_id : patient_id
				},
				dataType : 'json',
				success: function (response) {
					device_id = response;
					if(device_id != 0){
						get_content(device_id);
					}
					else{
						return;
					}
					
					// $("#content1").html(jQuery.parseJSON(response.stage1_content[0].content).message);
					// $("#content2").html(jQuery.parseJSON(response.stage2_content[0].content).message);
					// $("#content3").html(jQuery.parseJSON(response.stage3_content[0].content).message);
					// $("#content1_id").val(jQuery.parseJSON(response.stage1_content[0].id));
					// $("#content2_id").val(jQuery.parseJSON(response.stage2_content[0].id));
					// $("#content3_id").val(jQuery.parseJSON(response.stage3_content[0].id));
					// $("#component_id").val(jQuery.parseJSON(response.stage2_content[0].component_id));
					// $("#module_id").val(jQuery.parseJSON(response.stage3_content[0].module_id));
					// $("#stage_id1").val(jQuery.parseJSON(response.stage1_content[0].stage_id));
					// $("#stage_id2").val(jQuery.parseJSON(response.stage2_content[0].stage_id));
					// $("#stage_id3").val(jQuery.parseJSON(response.stage3_content[0].stage_id));
				}
			});
		}
	
	
		

    </script>
	
	<script src="{{asset('assets/js/vendor/toastr.min.js')}}"></script>
    <script src="{{asset('assets/js/toastr.script.js')}}"></script>

<script type="text/javascript">
  
    var time = '00:02:010';
    var splitTime = time.split(":");
    const H = splitTime[0];
    const M = splitTime[1];
    const S = splitTime[2];
</script>
<script src="{{ asset('assets/js/timer.js') }}"></script>

@endsection