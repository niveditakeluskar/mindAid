@extends('Theme::layouts_2.to-do-master')
@section('page-css')
    <style> 
        .disabled { 
            pointer-events: none; 
            cursor: default; 
        } 
    </style>
@endsection

@section('main-content')
<div id="app">
    <?php 
        if(isset($patient) && count($patient)>0){ 
            $patient_id = $patient[0]->id;
    ?>
	<div class="separator-breadcrumb "></div>
    <div class="row text-align-center">
        @include('Theme::layouts_2.flash-message')  
        @csrf
        <div class="col-md-12">
            {{ csrf_field() }}
            @include('Patients::components.patient-Ajaxbasic-info')
            @include('Patients::patient-enrollment.sub-steps.patient-enrollment-patient-details')		
        </div>
    </div>
     </div>
@endsection
@section('page-js')
    <!-- <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>    cmnt on 15thNov21 -->
     
    <!-- added on 15thnov21 -->
    <script src="{{asset(mix('assets/js/laravel/patientEnrollment.js'))}}"></script>
    <script type="text/javascript">
        var time = "<?php echo (isset($last_time_spend) && ($last_time_spend!='0')) ? $last_time_spend : '00:00:00'; ?>";
        var splitTime = time.split(":");
        var H = splitTime[0];
        var M = splitTime[1];
        var S = splitTime[2];
        $("#timer_start").val(time);
		$("#patient_time").val(time);
        
        $(document).ready(function(){  
            util.stepWizard('tsf-wizard-2');
            $("#start").hide();
            $("#pause").show();
            //following code updated by pranali on 7Nov2020 to check if patient is activated or deactivated and start/stop timer
            var patien_status = <?php echo $patient[0]->status; ?>;
            if(patien_status == 1){
                $("#time-container").val(AppStopwatch.startClock);
            } else {
                $("#time-container").val(AppStopwatch.pauseClock);
                $("#time-container").text('<?php echo (isset($last_time_spend) && ($last_time_spend!='0')) ? $last_time_spend : '00:00:00'; ?>');
            }
            patientEnrollment.init();
        });

        var backStep = function(val) {
            $("#s"+val).click();
        };
        
        $('#save_enroll_services').click(function() {
            valid = true;
            var re = 0;
			/*
            $("div.moduleCheck").each(function() {
                if (!$('input:checked', this).length > 0) {
                    if($('#p'+re).length==0){
                        $(this).after('<p style="color:red" id="p'+re+'">Atlist One Module Requred!</p>');
                    }
                    valid = false;
                } else {
                    $(this).next('p').remove(); 
                }
                re++;
            });
			*/
            if(valid == true){
                $("#save-enroll-services").click();
            }  
            return valid;
        });

        $('#save_checklist').click(function() {
            valid = true;
            var re = 0
            $("#checklist_form div.checkRadio").each(function() {
                if (!$('input:checked', this).length > 0) {
                    if($('#p'+re).length==0){
                        $(this).after('<p style="color:red" id="p'+re+'">This Field Requred!</p>');
                    }
                    valid = false;
                } else {
                    $(this).next('p').remove(); 
                }
                re++;
            });
            if(valid == true){
                $("#save-checklist").click();
            }  
            return false;
        });

        $('#save_checkliststatus').click(function() {
            valid = true;
            var re = 0
            $("#finalization_checklist_form div.checkRadio").each(function() {
                if (!$('input:checked', this).length > 0) {
                    if($('#p'+re).length==0){
                        $(this).after('<p style="color:red" id="p'+re+'">This Field Requred!</p>');
                    }
                    valid = false;
                } else {
                    $(this).next('p').remove(); 
                }
                re++;
            });
            if(valid == true){
                $("#save-checkliststatus").click();
            }  
            return false;
        });

        /*$('#checklist_form input, #finalization_checklist_form input').change(function() {
			console.log($(this).attr('name'));
			if ($('#checklist_form input[name = "'+this.name+'"]').is(':checked')){
				$('#checklist_status_form input[name = "'+this.name+'"][value = "'+this.value+'"]').trigger('click');
				//$('#checklist_status_form input[name = "'+this.name+'"][value = "'+this.value+'"]').attr("checked","checked");
				//$('#checklist_status_form input[name = "'+this.name+'"][value = "'+this.value+'"]').prop("checked",true);    
			}			
        });*/
        $('#checklist_form input, #finalization_checklist_form input').change(function() {
            $('#checklist_status_form input[name = "'+this.name+'"][value = "'+this.value+'"]').trigger('click');
        });

        // $("#save_checkliststatus").click(function() {
        //     // $("#checklist_status_form_questionnaire").html("");
        //     $("#checklist_form_questionnaire").clone().appendTo("checklist_status_form_questionnaire");
        //     $("#finalization_checklist_form_questionnaire").clone().appendTo("checklist_status_form_questionnaire");
        // });

        // var replaced = $("body").html().replace('[provider]',"<?php //$provider_data = (array)$patient_providers; echo empty($provider_data['provider_name']) ? '[provider]' : $provider_data['provider_name']; ?>");
        // $("body").html(replaced);
        // var replaced = $("body").html().replace('[Outgoing Phone Number]',"<?php $provider_data = (array)$patient_providers; echo empty($provider_data['outgoing_phone_number']) ? '[Outgoing Phone Number]' : $provider_data['outgoing_phone_number']; ?>");
        // $("body").html(replaced);

    </script>
    <script src="{{ asset('assets/js/timer.js') }}"></script>
@endsection

<?php }else{ ?>
@include("Ccm::monthly-monitoring.sub-steps.no-patient")
<?php } ?>