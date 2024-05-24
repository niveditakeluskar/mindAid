@extends('Theme::layouts.to-do-master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/toastr.css')}}">
@endsection

@section('main-content')
@foreach($patient as $checklist)

<div class="breadcrusmb">
	<div class="row">
		<div class="col-md-11">
			<h4 class="card-title mb-3">{{$checklist->fname}} {{$checklist->lname}}</h4>
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
<div class="separator-breadcrumb border-top"></div>
<div class="row  mb-4 text-align-center">
	<div class="col-md-11  mb-4 patient_details">
	@include('Patients::components.patient-basic-info')
		<div id="smartwizard">
			<ul>
				<li class=""><a href="#step-1 ">Step 1<br /><small></small></a></li>
				<li class=""><a href="#step-2">Step 2<br /><small></small></a></li>
				<li><a href="#step-3">Step 3<br /><small></small></a></li>
			</ul>
			<div>
				<div id="step-1" class="">
					@include('Rpm::deviceTraning.device-traning-sub-steps.patient-traning-info-checklist')	 
				</div>
				<div id="step-2" class="" >
	           		@include('Rpm::deviceTraning.device-traning-sub-steps.software-usage-instruction')
				</div>
				<div id="step-3" class="">	
					<br>
			    	<div class="form-row">
						<div class="form-group col-md-12">
						 	@include('Rpm::deviceTraning.device-traning-sub-steps.device-traning')
						</div> 
					</div>	
					<br>
				</div>
			</div>
		</div>
	</div>
	
</div>
@endforeach
@endsection
@section('page-js')

<script type="text/javascript">
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
	  var item=$(this);

	  if(item.is(":checked"))
	  {
		var time = $('.values').html();
		alert(time);
	  	save("1");
		$("#time_stop1").val(time);
	    window.location.href= item.data("target");
	  }
   
	 });
	  $("input[name$='software-usage-instruction']").change(function(){
	  var item=$(this);

	  if(item.is(":checked"))
	  {
		var time = $('.values').html();
		alert(time);
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
				alert(time);
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
				debugger;
				alert(JSON.stringify(response));
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
					
					startTimerFrom(hrs, mins, secs);
					
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
			
				alert(next_stage_id);
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
					get_content(device_id);
					
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
		
// 		var AppStopwatch = (function () {
// 			var counter = 0,
// 				$stopwatch = {
// 					el: document.getElementById('stopwatch'),
// 					container: document.getElementById('time-container'),
// 					startControl: document.getElementById('start'),
// 					pauseControl: document.getElementById('pause'),
// 					stopControl: document.getElementById('stop')
// 				};

// 			var runClock;

// 			function displayTime() {
// 				debugger;
// 				var timestamp = new Date(0,0,0,0,0,0);
// 				var interval = 1;
// 				timestamp = new Date(timestamp.getTime() + interval*1000);
// 				$stopwatch.container.innerHTML = moment().hour(0).minute(0).second(counter++).format('HH : mm : ss');
// 			}

// 			function startWatch() {
// 				$("#start").hide();
// 				$("#pause").show();
// 				runClock = setInterval(displayTime, 1000);
// 			}

// 			function pauseWatch() {
// 				$("#start").show();
// 				$("#pause").hide();
// 				clearInterval(runClock);
// 			}

// 			function stopWatch() {
// 				$("#display-val").html($stopwatch.container.innerHTML);
// 				clearInterval(runClock);
// 				counter=0;
// 				$("#start").hide();
// 				$("#pause").hide();
// 				$("#stop").hide();
// 			}

// 			return {
// 				startClock: startWatch,
// 				pauseClock: pauseWatch,
// 				stopClock: stopWatch,
// 				$start: $stopwatch.startControl,
// 				$pause: $stopwatch.pauseControl,
// 				$stop: $stopwatch.stopControl,
// 				// displayTime : displayTime
// 			};
// })();

// AppStopwatch.$start.addEventListener('click', AppStopwatch.startClock, false);
// AppStopwatch.$pause.addEventListener('click', AppStopwatch.pauseClock, false);
// AppStopwatch.$stop.addEventListener('click', AppStopwatch.stopClock, false);

// function displayTime(hours,minutes,seconds) {
//         var timestamp = new Date(0,0,0,0,0,0);
//         var interval = 1;
//         timestamp = new Date(timestamp.getTime() + interval*1000);
// 		$("#sub_module_timer").text(moment().hour(hours).minute(minutes).second(seconds++).format('HH : mm : ss'));
//     }
	</script>
	<script src="{{asset('assets/js/vendor/toastr.min.js')}}"></script>
    <script src="{{asset('assets/js/toastr.script.js')}}"></script>
@endsection