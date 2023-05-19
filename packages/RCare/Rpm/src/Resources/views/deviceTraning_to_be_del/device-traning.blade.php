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

<div class="breadcrusmb">
	<div class="row"> 
		<div class="col-md-112">
			<!-- <h4 class="card-title mb-3">{{$checklist->fname}} {{$checklist->lname}}</h4> -->
		</div>
	</div>
	<!-- ss -->             
</div>
<?php   $software_download_protocol_id_stage_id = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Software Download Protocol');
		$software_usage_instruction_id_stage_id = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Software Usage Instruction');
		$software_training_id_stage_id = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Software Training'); ?>

<div id="app"> </div>
@endsection
@section('page-js')
<script type="text/javascript">
   
   var time = "<?php echo (isset($last_time_spend) && ($last_time_spend!='0')) ? $last_time_spend : '00:00:00'; ?>";
        var splitTime = time.split(":");
        const H = splitTime[0];
        const M = splitTime[1];
        const S = splitTime[2];
        $("#timer_start").val(time);
       
    
    $(document ).ready(function() {
    	deviceTraning.init();
		util.stepWizard('tsf-wizard-1');  
            $("#start").hide();
            $("#pause").show();
            $("#time-container").val(AppStopwatch.startClock);
        //Device Taraing on change
	    $("#device_id").on('change', function(){
			$("#software_download_content").show();
			$("#step2_devices").val($(this).val());
			$("#step3_devices").val($(this).val());
			get_content($(this).val());
		});
		getPatientData();
    });

    
// $(document).ready(function(){ 
// 	$("#device_id").on('change', function(){
// 		$("#software_download_content").show();
// 		$("#step2_devices").val($(this).val());
// 		$("#step3_devices").val($(this).val());
// 		get_content($(this).val());
// 	});
// 	getPatientData();
// });		

function get_content(val){
	// debugger;
		patient_id =  $("#hidden_area").val();
		$.ajax({
			type: 'post',
			url: '/rpm/getContent',
			data: {
				_token: '{!! csrf_token() !!}',
				device_id:val,
				patient_id : patient_id,
				software_download_protocol_id_stage_id : '<?php echo $software_download_protocol_id_stage_id; ?>',
				software_usage_instruction_id_stage_id : '<?php echo $software_usage_instruction_id_stage_id; ?>',
				software_training_id_stage_id : '<?php echo $software_training_id_stage_id; ?>', 

			},
			dataType : 'json',
			success: function (response) {

				// console.log(JSON.stringify(response.stage1_content));
				// console.log(JSON.stringify(response.stage2_content));
				// console.log(JSON.stringify(response.stage3_content));
				var a = JSON.stringify(response.stage1_content);
				var b = JSON.stringify(response.stage2_content);
				var c = JSON.stringify(response.stage3_content);
				if(a == 1)
				{
					
					// $("#content1").html("Please contact administrator!");
					$("form[name='patient_traning_info_and_checklist_form'] .alert").show();
					$("#newdanger1").show();
					var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>No Template added. Contact Administrator to add the template for this step</strong></div>';
					$("#newdanger1").html(txt);
					$("#save_patient_traning_info_and_checklist").attr("disabled","disabled");
				}
				else{
					$("#newdanger1").hide(); 
					$("#save_patient_traning_info_and_checklist").prop("disabled", false); 
					$("#content1").html(jQuery.parseJSON(response.stage1_content[0].content).message);
					$("#content1_id").val(jQuery.parseJSON(response.stage1_content[0].id));
					$("#stage_id1").val(jQuery.parseJSON(response.stage1_content[0].stage_id));
				}

				if(b == 1)
				{
					// $("#content2").html("Please contact administrator!");
					$("form[name='software_usage_instruction_form'] .alert").show();
					$("#newdanger2").show();
					var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>No Template added. Contact Administrator to add the template for this step</strong></div>';
					$("#newdanger2").html(txt);
					$("#save_software_usage_instruction_form").attr("disabled","disabled");
				}
				else{
					$("#newdanger2").hide(); 
					$("#save_software_usage_instruction_form").prop("disabled", false); 
					$("#content2").html(jQuery.parseJSON(response.stage2_content[0].content).message);
					$("#content2_id").val(jQuery.parseJSON(response.stage2_content[0].id));
					$("#stage_id2").val(jQuery.parseJSON(response.stage2_content[0].stage_id));
				}


				if(c == 1)
						{
							// $("#content3").html("Please contact administrator!");
							$("form[name='device_traning_form'] .alert").show();
							$("#newdanger3").show();
							var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>No Template added. Contact Administrator to add the template for this step</strong></div>';
							$("#newdanger3").html(txt);
							$("#save_device_traning_form").attr("disabled","disabled");
						}
						else{
					$("#newdanger3").hide();  
					$("#save_device_traning_form").prop("disabled", false); 
					$("#content3").html(jQuery.parseJSON(response.stage3_content[0].content).message);
					$("#content3_id").val(jQuery.parseJSON(response.stage3_content[0].id));
					$("#stage_id3").val(jQuery.parseJSON(response.stage3_content[0].stage_id)); 
				} 


				
				
            //    }
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
	
				}
			});
		}
	
    </script>

<script src="{{ asset('assets/js/timer.js') }}"></script>

@endsection