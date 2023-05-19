@extends('Theme::layouts_2.to-do-master')

@section('main-content')

@foreach($patient as $checklist)
@csrf
<div class="separator-breadcrumb "></div>
<div class="row text-align-center">
    @include('Theme::layouts_2.flash-message')
    <div class="col-md-12">
        {{ csrf_field() }}
        <!--Add view Patient Overview -->
        @include('Ccm::sub-steps.patient-overview')
    </div>
</div>
@endforeach
@endsection
@section('bottom-js')
<script type="text/javascript">
    $(document).ready(function() {
        // autocomplete  
        //$('li#s2').click();
        $(function() {
            var availableTags = [
                "ActionScript",
                "AppleScript",
                "Asp",
                "BASIC",
                "C",
                "C++",
                "Clojure",
                "COBOL",
                "ColdFusion",
                "Erlang",
                "Fortran",
                "Groovy",
                "Haskell",
                "Java",
                "JavaScript",
                "Lisp",
                "Perl",
                "PHP",
                "Python",
                "Ruby",
                "Scala",
                "Scheme"
            ];
            $("#tagselect").autocomplete({
                source: availableTags
            });

        });

        util.stepWizard('tsf-wizard-2');
        $("#select-activity-1").show();

        $('#view_care_plan').on('click', function() {
            $('#care_plan_output').show();
        });

        // 	// $("#answered").click(function(){
        // 	// $("#answer").show();
        // 	// $("#not_answer").hide();
        // }); 

        // $("#not_answered").click(function(){
        // 	// debugger;
        // 	$("#not_answer").show();
        // 	$("#answer").hide();
        // 	// $('#content_title').val(130).change();
        // });


        $(".open_review_block").on('click', function() {
            var block = $(this).attr('id');
            var blockname = block.substr(block.indexOf("_") + 1);
            var section = blockname.substr(blockname.indexOf("_") + 1);
            $(".review_data_sections").hide();
            $("[name='" + blockname + "']").show();

            $("[name='" + blockname + "'] .nav-link").each(function() {
                hrefval = $(this).attr("href");
                $(this).attr("href", hrefval + "_review");
                var tabId = hrefval.substring(1, hrefval.length);
                var newtabId = tabId + "_review";
                // alert("[name='"+blockname+"'] #"+tabId+": "+hrefval+"_review");
                $("[name='" + blockname + "'] #" + tabId).attr("id", newtabId);

                formId = blockname + "_" + newtabId;
                $("[name='" + blockname + "'] #" + newtabId + " form").attr("id", formId);
                //$(hrefval).attr("id", hrefval+"_review");
            });
        });

        $('.click_id').on('click', function() {
            // debugger;
            var block = $(this).attr('id');
            var blockname = block.substr(block.indexOf("_") + 1);
            var section = blockname.substr(blockname.indexOf("_") + 1);
            //alert("section"+section);
            //alert("blockname"+blockname);

            $(".emr_data_sections").hide();
            $("[name='" + blockname + "']").show();
            $("[name='" + blockname + "'] .tab-pane").each(function() {
                tabid = $(this).attr('id');
                // alert("tabid "+tabid);
                formId = blockname + "_" + tabid;
                // alert("formid "+formId);
                $("[name='" + blockname + "'] #" + tabid + " form").attr("id", formId);
            });
        });
    });

    //caredevelopment ajax
    $('.patient_data-save').on('click', function() {

        modal_name = $("#myIconTab-care-plan-development li a.active").attr('href').slice(1);                    
        tabid = $(this).closest('.tab-pane').attr('id');
        alert(modal_name);
        alert(tabid);
        // $("#"+tabid+" form").attr('id', tabid+'_family_data-form');
        if (tabid == 'patient') {
            $("#" + tabid + " form").attr('id', modal_name + "_" + tabid + '_patient_data-form');
            var form_id = modal_name + "_" + tabid + '_patient_data-form';
            alert(form_id);
            var data = $("#" + form_id).serialize();
            alert(data);
            URL = '/ccm/savePatientData';
            DATA = "tab_name" + '=' + modal_name + "&" + data;
            alert(DATA);
            ajax_careplandata(URL, DATA);
        } else if (tabid == 'spouse' || tabid == 'care-giver' || tabid == 'emergency-contact') {
            $("#" + tabid + " form").attr('id', tabid + '_family_data-form');
            var form_id = tabid + '_family_data-form';
            // alert(form_id);
            var data = $("#" + form_id).serialize();
            alert(data);
            URL = '/ccm/savePatientfamilyData';
            DATA = "relationship" + '=' + tabid + "&" + "tab_name" + '=' + modal_name + "&" + data;
            // alert();
            ajax_careplandata(URL, DATA);
        } else if (tabid == 'pcp' || tabid == 'dentist' || tabid == 'vision') {
            $("#" + tabid + " form").attr('id', tabid + '_provider-form');
            var form_id = tabid + '_provider-form';
            // alert(form_id);
            var data = $("#" + form_id).serialize();
            URL = '/ccm/savePatientprovidersData';
            DATA = data;
            ajax_careplandata(URL, DATA);
        } else if (tabid == 'specialists') {
            // alert('specialists');
            $("#" + tabid + " form").attr('id', tabid + '_specialistprovider-form');
            var form_id = tabid + '_specialistprovider-form';
            // alert(form_id);
            var data = $("#" + form_id).serialize();
            URL = '/ccm/savePatientprovidersData';
            DATA = data;
            ajax_careplandata(URL, DATA);
        } else if (tabid == 'medication') {
            // ("#"+tabid+" form").attr('id', tabid+'_med-form');
            // var form_id =tabid+'_med-form';
            form_id = 'med-form';
            alert(form_id);
            var data = $("#" + form_id).serialize();
            URL = '/ccm/savePatientmedicationData';
            DATA = data;
            ajax_careplandata(URL, DATA);
        } else if (tabid == 'vitals') {
            // ("#"+tabid+" form").attr('id', tabid+'_med-form');
            // var form_id =tabid+'_med-form';
            form_id = 'vital-form';
            alert(form_id);
            var data = $("#" + form_id).serialize();
            URL = '/ccm/savePatientvitalData';
            DATA = data;
            ajax_careplandata(URL, DATA);
        } else if (tabid == 'sibiling-sibling') {
            // ("#"+tabid+"form").attr('id', tabid+'_siblings-form');
            // var form_id = tabid+'_siblings-form';
            // alert(form_id);
            var data = $("#sibiling-form").serialize();
            URL = '/ccm/savePatientfamilyData';
            DATA = "relationship" + '=' + tabid + "&" + "tab_name" + '=' + modal_name + "&" + data;
            ajax_careplandata(URL, DATA);
        } else if (tabid == 'children-sibling') {
            // ("#"+tabid+"form").attr('id', tabid+'_siblings-form');
            // var form_id = tabid+'_siblings-form';
            // alert(form_id);
            var data = $("#children-form").serialize();
            URL = '/ccm/savePatientfamilyData';
            DATA = "relationship" + '=' + tabid + "&" + "tab_name" + '=' + modal_name + "&" + data;
            ajax_careplandata(URL, DATA);
        } else if (tabid == 'grand_children-sibling') {
            var data = $("#grand_children-form").serialize();
            URL = '/ccm/savePatientfamilyData';
            DATA = "relationship" + '=' + tabid + "&" + "tab_name" + '=' + modal_name + "&" + data;
            ajax_careplandata(URL, DATA);
        } else if (tabid == 'care-plan-development-reviewdata-pcp') {
            $("#" + tabid + " form").attr('id', tabid + '_doctor-info-form');
            var form_id = tabid + '_doctor-info-form';
            alert(form_id);
            var data = $("#" + form_id).serialize();
            URL = '/ccm/savePatientprovidersData';
            DATA = data;
            ajax_careplandata(URL, DATA);
        }

    });



    //    util.stepWizard('tsf-wizard-2');
    //  $("#select-activity-1").show();
    /*
        $('#call_step_1_id').on('click', function(){    
            $('#call-wizard').smartWizard();
        });
    */
    // $('.preparation').on('click', function(){
    //     debugger;
    //     util.stepWizard('tsf-wizard-2');
    // });
    // $(".act1").on('click', function(){
    //     steps_id = $(this).attr("id");
    //  $("#call-step-container").removeClass('tsf-container');
    //  $("#activity-container").removeClass('tsf-container');
    //  $("#activity-container").addClass('tsf-container');
    //     // util.stepWizard('tsf-wizard-2');
    //     $("#"+steps_id).trigger('click');
    // });

    // $('.call-step-1').on('click', function(){
    //  $("#call-step-container").removeClass('tsf-container');
    //     $("#call-step-container").addClass('tsf-container');
    //     $("#activity-container").removeClass('tsf-container');

    //     util.stepWizard('tsf-wizard-3');
    // });






    // $("input[name$='emr_status']").click(function() {
    //     debugger;
    //          var pageURL = $(location).attr("href");  
    //          var str =  pageURL.split("#").pop();

    //          var emr_status = $(this).val();
    //          if(emr_status == '1' || emr_status == '2' || emr_status == '3') {
    //             $("#"+str+"-note").show();
    //          }else {
    //              $("#"+str+"-note").hide();
    //          }
    //       });
    /*
    $('#activity_selection').on('change', function(){
        
         // alert('conceptName'); 
        var conceptName = $('#activity_selection :selected').val();
            if(conceptName==1 || conceptName==2 || conceptName==3 || conceptName==4) {
                // var pageURL = $(location).attr("href"); 
                // alert(pageURL);
                // var str = pageURL.substr(0,pageURL.indexOf('#'));
                // alert(str);
                // window.location=str+""+'#step-'+1;
                // window.location=str+""+'#step-'+i;
                    util.stepWizard('tsf-wizard-2');
                    $("#select-activity-1").show();
                    $('#select-activity-5').hide();
                    // $('.smartwizard').removeAttr('id');

                    if(conceptName==1){
                        // util.stepWizard('tsf-wizard-2');
                        // $("#ac1_step1 a").trigger('click');
                        // util.stepWizard('tsf-wizard-2');
                        // $("#select-activity-1").show();
                        // $('#select-activity-3').hide();
                        // $('#select-activity-2').hide();
                        // $('#select-activity-4').hide();
                        // $('#select-activity-5').hide();
                        // $('#select-activity-6').hide();
                        // $(".preparation").show();
                        // $(".preparation").trigger('click');
                        // $(".call-steps").hide();
                        // $(".call-step-1").hide();
                        // $(".follow-up").hide();
                        // $(".text").hide();

                    }else if(conceptName==2){
                        
                        util.stepWizardHorizontal('tsf-wizard-3');
                        // util.stepWizard('tsf-wizard-2');
                        // $("#ac1_step2 a").trigger('click');
                      //  $("#answered").attr("id", "select-activity-1-answered");
                        // $("#select-activity-1").hide();
                        // $('#select-activity-3').hide();
                        // $('#select-activity-2').show();
                        // $('#select-activity-4').hide();
                        // $('#select-activity-5').hide();
                        // $('#select-activity-6').hide();
                        // $(".preparation").hide();
                        // $(".call-steps").show();
                        // $(".call-step-1").show();
                        
                        $(".call-step-1").trigger('click');
                        // $(".follow-up").hide();
                        // $(".text").hide();

                    }else if(conceptName==3){
                        // util.stepWizard('tsf-wizard-2');
                        // $("#ac1_step10 a").trigger('click');
                        // util.stepWizard('tsf-wizard-3');
                        // $(".preparation").hide();
                        // $(".call-steps").hide();
                        // $(".call-step-1").hide();
                        // $(".follow-up").show();
                        // $(".follow-up").trigger('click');
                        // $(".text").hide();
                        // $("#select-activity-3").show();
                        // $('#select-activity-1').hide();
                        // $('#select-activity-2').hide();
                        // $('#select-activity-4').hide();
                        // $('#select-activity-5').hide();
                        // $('#select-activity-6').hide();
                    }else if(conceptName==4){
                        // util.stepWizard('tsf-wizard-2');
                        // $("#ac1_step11 a").trigger('click');
                        // util.stepWizard('tsf-wizard-4');
                        // $(".preparation").hide();
                        // $(".call-steps").hide();
                        // $(".call-step-1").hide();
                        // $(".follow-up").hide();
                        // $(".text").show();
                        $(".text").trigger('click');
                        // $("#select-activity-4").show();
                        // $('#select-activity-3').hide();
                        // $('#select-activity-2').hide();
                        // $('#select-activity-5').hide();
                        // $('#select-activity-1').hide();
                        // $('#select-activity-6').hide();
                    }
           }
            else{
                    // $('.smartwizard').removeAttr('id');
                    // $('#select-activity-2 .smartwizard').attr('id','smartwizard');
                    util.stepWizard('tsf-wizard-5');
                    $("#select-activity-5").show();
                    $('#select-activity-1').hide();
                    
                    if(conceptName==5){
                        // util.stepWizard('tsf-wizard-5');
                        // $("#select-activity-5").show();
                        // $("#select-activity-1").hide();
                        // $('#select-activity-3').hide();
                        // $('#select-activity-2').hide();
                        // $('#select-activity-4').hide();
                        // $("#select-activity-6").hide();
                        $(".patient-data").trigger('click');
                    }else if(conceptName==6){
                        // util.stepWizardHorizontal('tsf-wizard-6');
                        // $("#select-activity-6").show();
                        // $("#select-activity-1").hide();
                        // $('#select-activity-3').hide();
                        // $('#select-activity-2').hide();
                        // $('#select-activity-4').hide();
                        // $("#select-activity-5").hide();
                        $(".care-plan-call").trigger('click');
                       // $("#answered").attr("id", "select-activity-2-answered");
                    }else if(conceptName==7){
                        $("#ac2_step7 a").trigger('click');
                    }
            }
        //    $('#smartwizard').smartWizard();
    });
	*/
    /*
        $('#open_health_services').click(function(){
            debugger;
            $('.smartwizard').removeAttr('id');
            // $('#healthcare-services .smartwizard').attr('id','smartwizard');
            $("[name='healthcare_services']").show();
            // $('#healthcare-services').show();
            $("[name='allergy_information']").hide();
            // $('#allergy-information').hide();
            $("[name='medications']").hide();
            // $('#medications').hide();
            $('#travel').hide();
            $('#hobbies').hide();
            $('#pets').hide();
            $('#codes-info-for-medical').hide();
            $('#doctors-information').hide();
            $('#live-info-for-anyone').hide();
            $("[name='family_info']").hide();
            $("[name='grandchildren_info']").hide();
            $('#smartwizard').smartWizard();
            id = $("#patient_id").val(); 
            // var sectionId = $("#dme-services-icon-pill").attr('href');
            // sectionId = sectionId.substring(1, sectionId.length);
            renderAllergiesTable(id, 'review_dme');
        });
        $('#open_allergies').click(function(){
            debugger;
            $('.smartwizard').removeAttr('id');
            // $('#allergy-information .smartwizard').attr('id','smartwizard');
            // $('#allergy-information').show();
            $("[name='allergy_information']").show();
            $("[name='medications']").hide();
            // $('#medications').hide();
            // $('#healthcare-services').hide();
            $("[name='healthcare_services']").hide();
            $('#travel').hide();
            $('#hobbies').hide();
            $('#pets').hide();
            $('#codes-info-for-medical').hide();
            $('#doctors-information').hide();
            $('#live-info-for-anyone').hide();
            $("[name='family_info']").hide();
            $("[name='grandchildren_info']").hide();
            $('#smartwizard').smartWizard();
            id = $("#patient_id").val(); 
            // var sectionId = $("#food-icon-pill").attr('href');
            // sectionId = sectionId.substring(1, sectionId.length);
            renderAllergiesTable(id, 'review_food');
        });
        $('#open_medications').click(function(){
            // debugger;
            $('.smartwizard').removeAttr('id');
            $('#allergy-information .smartwizard').attr('id','smartwizard');
            $("[name='allergy_information']").hide();
            // $('#allergy-information').hide();
            $("[name='medications']").show();
            // $('#medications').show();
            // $('#healthcare-services').hide();
            $("[name='healthcare_services']").hide();
            $('#travel').hide();
            $('#hobbies').hide();
            $('#pets').hide();
            $('#codes-info-for-medical').hide();
            $('#doctors-information').hide();
            $('#live-info-for-anyone').hide();
            $("[name='family_info']").hide();
            $("[name='grandchildren_info']").hide();
            $('#smartwizard').smartWizard();
        });
        $('#open_travel').click(function(){
            $('.smartwizard').removeAttr('id');
            $('#travel .smartwizard').attr('id','smartwizard');
            $('#travel').show();
            $("[name='allergy_information']").hide();
            // $('#allergy-information').hide();
            $("[name='medications']").hide();
            // $('#medications').hide();
            // $('#healthcare-services').hide();
            $("[name='healthcare_services']").hide();
            $('#hobbies').hide();
            $('#pets').hide();
            $('#codes-info-for-medical').hide();
            $('#doctors-information').hide();
            $('#live-info-for-anyone').hide();
            $("[name='family_info']").hide();
            $("[name='grandchildren_info']").hide();
            $('#smartwizard').smartWizard();
        });
        $('#open_hobbies').click(function(){
            $('.smartwizard').removeAttr('id');
            $('#hobbies .smartwizard').attr('id','smartwizard');
            $('#hobbies').show();
            $('#travel').hide();
            $("[name='allergy_information']").hide();
            // $('#allergy-information').hide();
            $("[name='medications']").hide();
            // $('#medications').hide();
            // $('#healthcare-services').hide();
            $("[name='healthcare_services']").hide();
            $('#pets').hide();
            $('#codes-info-for-medical').hide();
            $('#doctors-information').hide();
            $('#live-info-for-anyone').hide();
            $("[name='family_info']").hide();
            $("[name='grandchildren_info']").hide();
            $('#smartwizard').smartWizard();
        });
        $('#open_pets').click(function(){
            $('.smartwizard').removeAttr('id');
            $('#pets .smartwizard').attr('id','smartwizard');
            $('#pets').show();
            $('#hobbies').hide();
            $('#travel').hide();
            $("[name='allergy_information']").hide();
            // $('#allergy-information').hide();
            $("[name='medications']").hide();
            // $('#medications').hide();
            // $('#healthcare-services').hide();
            $("[name='healthcare_services']").hide();
            $('#codes-info-for-medical').hide();
            $('#doctors-information').hide();
            $('#live-info-for-anyone').hide();
            $("[name='family_info']").hide();
            $("[name='grandchildren_info']").hide();
            $('#smartwizard').smartWizard();
        });
        $('#open_family_info').click(function(){
            // debugger;
            // alert('hey baby');
            $('.smartwizard').removeAttr('id');
            // $('#family-info .smartwizard').attr('id','smartwizard');
            $("[name='family_info']").show();
            // $('#family-info').show();
            $('#live-info-for-anyone').hide();
            $('#sibiling-info').hide();
            $('#children-info').hide();
            $('#grandchildren-info').hide();
            $('#doctors-information').hide();
            $('#codes-info-for-medical').hide();
            $("[name='medications']").hide();
            $("[name='allergy_information']").hide();
            $('#pets').hide();
            $('#travel').hide();
            $("[name='healthcare_services']").hide();
            $("[name='grandchildren_info']").hide();
            $('#hobbies').hide();
            $('#smartwizard').smartWizard();
        });
         $('#open_live_info').click(function(){
            $('.smartwizard').removeAttr('id');
            // $('#live-info-for-anyone .smartwizard').attr('id','smartwizard');
            $("[name='live_info_for_anyone']").show();
            // $('#live-info-for-anyone').show();
            // $('#family-info').hide();
            $("[name='family_info']").hide();
            $('#sibiling-info').hide();
            $('#children-info').hide();
            $('#grandchildren-info').hide();
            $('#doctors-information').hide();
            $('#codes-info-for-medical').hide();
            $("[name='medications']").hide();
            $("[name='allergy_information']").hide();
            $('#pets').hide();
            $('#travel').hide();
            $("[name='healthcare_services']").hide();
            $("[name='grandchildren_info']").hide();
            $('#hobbies').hide();
            $('#smartwizard').smartWizard();
        });
         $('#open_sibiling_info').click(function(){
            $('.smartwizard').removeAttr('id');
            $('#sibiling-info .smartwizard').attr('id','smartwizard');
            $('#sibiling-info').show();
            // $('#family-info').hide();
            $("[name='family_info']").hide();
            $('#live-info-for-anyone').hide();
            $('#children-info').hide();
            $('#grandchildren-info').hide();
            $('#doctors-information').hide();
            $('#codes-info-for-medical').hide();
            $("[name='medications']").hide();
            $("[name='allergy_information']").hide();
            $('#pets').hide();
            $('#travel').hide();
            $("[name='healthcare_services']").hide();
            $("[name='grandchildren_info']").hide();
            $('#hobbies').hide();
            $('#smartwizard').smartWizard();
        });
         $('#open_children_info').click(function(){
            $('.smartwizard').removeAttr('id');
            $('#children-info .smartwizard').attr('id','smartwizard');
            $('#children-info').show();
            // $('#family-info').hide();
            $("[name='family_info']").hide();
            $('#live-info-for-anyone').hide();
            $('#sibiling-info').hide();
            $('#grandchildren-info').hide();
            $('#doctors-information').hide();
            $('#codes-info-for-medical').hide();
            $("[name='medications']").hide();
            $("[name='allergy_information']").hide();
            $('#pets').hide();
            $('#travel').hide();
            $("[name='healthcare_services']").hide();
            $("[name='grandchildren_info']").hide();
            $('#hobbies').hide();
            $('#smartwizard').smartWizard();
        });
          $('#open_grandchildren_info').click(function(){
            // debugger;
            $('.smartwizard').removeAttr('id');
            // $('#grandchildren .smartwizard').attr('id','smartwizard');
            $("[name='grandchildren_info']").show();
            // $('#grandchildren-info').show();
            // $('#family-info').hide();
            $("[name='family_info']").hide();
            $('#live-info-for-anyone').hide();
            $('#sibiling-info').hide();
            $('#children-info').hide();
            $('#doctors-information').hide();
            $('#codes-info-for-medical').hide();
            $("[name='medications']").hide();
            $("[name='allergy_information']").hide();
            $('#pets').hide();
            $('#travel').hide();
            $("[name='healthcare_services']").hide();
            $('#hobbies').hide();
            $('#smartwizard').smartWizard();
        });
          $('#open_doctors_information').click(function(){
            $('.smartwizard').removeAttr('id');
            $('#doctors-information .smartwizard').attr('id','smartwizard');
            $('#doctors-information').show();
            // $('#family-info').hide();
            $("[name='family_info']").hide();
            $('#live-info-for-anyone').hide();
            $('#sibiling-info').hide();
            $('#children-info').hide();
            $('#grandchildren-info').hide();
            $('#codes-info-for-medical').hide();
            $("[name='medications']").hide();
            $("[name='allergy_information']").hide();
            $('#pets').hide();
            $('#travel').hide();
            $("[name='healthcare_services']").hide();
            $("[name='grandchildren_info']").hide();
            $('#hobbies').hide();
            $('#smartwizard').smartWizard();
        });
          $('#open_codes_info_for_medical').click(function(){
            $('.smartwizard').removeAttr('id');
            $('#codes-info-for-medical .smartwizard').attr('id','smartwizard');
            $('#codes-info-for-medical').show();
            // $('#family-info').hide();
            $("[name='family_info']").hide();
            $('#live-info-for-anyone').hide();
            $('#sibiling-info').hide();
            $('#children-info').hide();
            $('#grandchildren-info').hide();
            $('#doctors-information').hide();
            $("[name='medications']").hide();
            $("[name='allergy_information']").hide();
            $('#pets').hide();
            $('#travel').hide();
            $("[name='healthcare_services']").hide();
            $("[name='grandchildren_info']").hide();
            $('#hobbies').hide();
            $('#smartwizard').smartWizard();
        });
           

        $("#additional_hobby").click(function(){
            $("#appendHobbyDiv").append($("#hobby").html());
        });
        $("#additional_pet").click(function(){
            $("#appendPetDiv").append($("#pet").html());
        });
    */


    //patient Data dropdown
    /*
    $('.click_id').click(function(){
        // debugger;
        evID = $(this).closest('td').prevAll('.patient_data').text();
        // alert(evID);
            if(evID=='Diagnosis Codes'){
                $('.smartwizard').removeAttr('id');
                $('#family .smartwizard').attr('id','smartwizard');
                $('#diagnosis-codes').show();
                $('#allergy-information').hide();
                $('#provider-information').hide();
                $('#family').hide();
                $('#healthcare-services').hide();
                $('#medications').hide();
                $('#numbers-tracking').hide();
            }
            if(evID=='Allergies'){
                // debugger;
                $('.smartwizard').removeAttr('id');
                $('#allergy-information .smartwizard').attr('id','smartwizard');
                $('#allergy-information').show();
                $('#diagnosis-codes').hide();
                $('#provider-information').hide();
                $('#family').hide();
                $('#healthcare-services').hide();
                $('#medications').hide();
                $('#numbers-tracking').hide();
                id = $("#patient_id").val(); 
                // var sectionId = $("#food-icon-pill").attr('href');
                // sectionId = sectionId.substring(1, sectionId.length);
                renderAllergiesTable(id, 'emr_food');
                // renderAllergiesTable(id, 'food');

            }
            if(evID=='Family'){
          
                $('.smartwizard').removeAttr('id');
                $('#family .smartwizard').attr('id','smartwizard');
                $('#family').show();
                $('#allergy-information').hide();
                $('#diagnosis-codes').hide();
                $('#provider-information').hide();
                $('#healthcare-services').hide();
                $('#medications').hide();
                $('#numbers-tracking').hide();  
            
            }
            if(evID=='Provider'){
                $('.smartwizard').removeAttr('id');
                $('#provider-information .smartwizard').attr('id','smartwizard');
                $('#provider-information').show();
                $('#allergy-information').hide();
                $('#diagnosis-codes').hide();
                $('#family').hide();
                $('#healthcare-services').hide();
                $('#medications').hide();
                $('#numbers-tracking').hide();  
            }
            if(evID=='Medications'){
                $('.smartwizard').removeAttr('id');
                $('#medications .smartwizard').attr('id','smartwizard');
                $('#medications').show();
                $('#family').hide();
                $('#allergy-information').hide();
                $('#diagnosis-codes').hide();
                $('#provider-information').hide();
                $('#healthcare-services').hide();
                $('#numbers-tracking').hide();
            }

            if(evID=='Services'){
                debugger;
                $('.smartwizard').removeAttr('id');
                $('#healthcare-services .smartwizard').attr('id','smartwizard');
                $('#medications').hide();
                $('#family').hide();
                $('#allergy-information').hide();
                $('#diagnosis-codes').hide();
                $('#provider-information').hide();
                $('#healthcare-services').show();
                $('#numbers-tracking').hide();
                id = $("#patient_id").val(); 
                // var sectionId = $("#dme-services-icon-pill").attr('href');
                // sectionId = sectionId.substring(1, sectionId.length);
                renderAllergiesTable(id, 'emr_dme');
                // renderAllergiesTable(id, 'dme');
            }

            if(evID=='Numbers Tracking'){
                $('.smartwizard').removeAttr('id');
                $('#numbers-tracking .smartwizard').attr('id','smartwizard');
                $('#numbers-tracking').show();
                $('#medications').hide();
                $('#family').hide();
                $('#allergy-information').hide();
                $('#diagnosis-codes').hide();
                $('#provider-information').hide();
                $('#healthcare-services').hide();
            }
            $('#smartwizard').smartWizard();

        });
		
		*/

    // }); 


    function next_step() {
        var pageURL = $(location).attr("href");
        var str = pageURL.substr(0, pageURL.indexOf('#'));
        // alert(str);
        var n = pageURL.search("#");
        var m = pageURL.slice(n + 6, n + 7)
        var i = parseInt(m) + 1;
        //var str2 = parseInt(str)+1;
        window.location = str + "" + '#step-' + i;

    }

    function backStep() {
        // alert(step);
        var pageURL = $(location).attr("href");
        var str = pageURL.substr(0, pageURL.indexOf('#'));
        // alert(str);
        var n = pageURL.search("#");
        var m = pageURL.slice(n + 6, n + 7)
        var i = parseInt(m) - 1;
        //var str2 = parseInt(str)+1;
        window.location = str + "" + '#step-' + i;
    }
    // call
    // $('#call_scripts').on('change', function() {
    //     // alert( this.value );
    //     var id = $("#call_scripts").val(); 
    //     // alert(id);
    //         $.ajax({
    //             type: 'get',
    //             url: '/rpm/fetch-getCallScriptsById',
    //             data : {
    //                 'id': id,
    //             },
    //             success: function (response) {
    //                 // alert(JSON.stringify(response[0].content).message);
    //                 $('#script').html(jQuery.parseJSON(response[0].content).message);
    //                 $modal = $('.script_modal');
    //                 $modal.modal('show');
    //             },
    //             dataType: 'JSON',
    //         });
    // });


    /* 

    $("#additional").click(function(){
        count = 1;
        $("#appendDiv").append($("#step2").html());
        $('#aditionalCounter').attr('text',count++);
    });
    //dem
    $("#additionaldme").click(function(){
        $("#appendDivdme").append($("#step1").html());
    });
    // for home-health-skilled
    $("#additionalhomehealthskilled").click(function(){
        $("#appendDivhomehealthskilled").append($("#step--2").html());
    });
    //appendDivdialysis
    $("#additionaldialysis").click(function(){
        $("#appendDivdialysis").append($("#step--3").html());
    });
    //therapy.blade.php
    $("#additionaltherapy").click(function(){
        $("#appendDivtherapy").append($("#step--4").html());
    });
    //therapy.blade.php
    $("#additionalmedicalsupplies").click(function(){
        $("#appendDivmedicalsupplies").append($("#step--5").html());
    });
    // do you live with anyone
    $("#additionallive").click(function(){
        $("#appendDivlive").append($("#steplive").html());
    });
    //sibigling
    $("#additionalsibiling").click(function(){
        $("#appendDivsibiling").append($("#stepsibiling").html());
    });

    //children
    $("#additionalchildren").click(function(){
        $("#appendDivforchildren").append($("#step1children").html());
    });
*/
    //save allergy

    // $('#save-preparation').click(function(){
    //     alert('hi');
    //     $.ajax({
    //        type: 'post',

    //        url: '/ccm/savePreparation',
    //        data: $('#preparation-form').serialize(), // getting filed value in serialize form
    //        success: function(response){

    //          alert('form was submitted');

    //        }
    //     });
    //      // to prevent refreshing the whole page page
    //        return false;
    //  });

    // $('.additional_button1').on('click', function() {
    //     debugger;
    //     alert( 'additional' );
    //     prefix= '/ccm';
    //     url_name = $("#url").val();
    //     url = '/ccm/' + url_name;
    //     var sectionId = $(this).closest("form").attr('id');
    //     alert(sectionId);
    //     patient_id = $("#patient_id").val(); 
    //     $.ajax({
    //         type: 'post',
    //         url : url,
    //         // url: '/ccm/saveHealthServices',
    //         data : $("#"+sectionId).serialize(),
    //         success: function (response) {
    //             debugger;
    //             sectionId = sectionId.substring(0, sectionId.indexOf('_'));
    //             renderAllergiesTable(patient_id, sectionId);
    //             $("#"+sectionId+"_form").trigger('reset');
    //             // alert("#"+sectionId+" _form");
    //             // $(".allergy_form").trigger('reset');
    //             // form_id = ("#"+sectionId+"_form");
    //             // alert(form_id);           
    //         },
    //     });
    // });


    $('.additional_button').on('click', function() {
        debugger;
        alert('additional');
        prefix = '/ccm';
        url_name = $("#url").val();
        url = '/ccm/' + url_name;
        var sectionId = $(this).closest("form").attr('id');
        alert(sectionId);
        patient_id = $("#patient_id").val();
        // var nearest_step_id = $(this).closest('.tab-pane').attr('id');
        // alert(nearest_step_id);
        // var sectionId = $(this).attr('href');
        //  sectionId = sectionId.substring(1, sectionId.length);
        // sectionId = sectionId.substring(0, sectionId.indexOf('_'));
        // $('.allergy_type').val(sectionId);
        // alert( sectionId );      
        // form_id = $("#"+nearest_step_id+" #allergy_form").val(); 
        // var form = $("#"+nearest_step_id+" #allergy_form").serialize();
        // var form = $(form).find().serialize();
        // console.log($("#"+nearest_step_id+" #allergy_form").html());
        // var dataVal = $("#"+nearest_step_id+" #allergy_form").serialize();
        //  alert(dataVal);
        $.ajax({
            type: 'post',
            url: url,
            // url: '/ccm/saveAllergy',
            data: $("#" + sectionId).serialize(),
            success: function(response) {
                debugger;
                sectionId = sectionId.replace("_form", '');
                // sectionId = sectionId.substring(0, sectionId.indexOf('_'));
                renderAllergiesTable(patient_id, sectionId);
                $("#" + sectionId + "_form").trigger('reset');
                // alert("#"+sectionId+" _form");
                // $(".allergy_form").trigger('reset');
                // form_id = ("#"+sectionId+"_form");
                // alert(form_id);           
            },
        });
    });

    var renderAllergiesTable = function(id, sectionId) {
        debugger;
        presectionId = sectionId;
        sectionId = sectionId.substring(sectionId.indexOf("_") + 1);
        alert(presectionId);
        alert(sectionId);

        if (sectionId == 'food' || sectionId == 'drug' || sectionId == 'enviromental' || sectionId == 'insect' || sectionId == 'latex' || sectionId == 'pet-related' || sectionId == 'other-allergy') {
            var columns1 = [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'specify',
                    name: 'specify'
                },
                {
                    data: 'type_of_reactions',
                    name: 'type_of_reactions'
                },
                {
                    data: 'severity',
                    name: 'severity'
                },
                {
                    data: 'course_of_treatment',
                    name: 'course_of_treatment'
                },
                {
                    data: 'notes',
                    name: 'notes'
                },
            ];
            var url = '/ccm/ajax/addAllergies/' + id + '/' + sectionId;
        }

        if (sectionId == 'dme' || sectionId == 'medical-supplies' || sectionId == 'other-health') {
            var columns1 = [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'specify',
                    name: 'specify'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'brand',
                    name: 'brand'
                },
                {
                    data: 'purpose',
                    name: 'purpose'
                },
                {
                    data: 'notes',
                    name: 'notes'
                },
            ];
            var url = '/ccm/ajax/healthServices1/' + id + '/' + sectionId;
        }

        if (sectionId == 'home-health-services' || sectionId == 'dialysis' || sectionId == 'therapy' || sectionId == 'social-services') {
            var columns1 = [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'specify',
                    name: 'specify'
                },
                {
                    data: 'from_whom',
                    name: 'from_whom'
                },
                {
                    data: 'from_where',
                    name: 'from_where'
                },
                {
                    data: 'purpose',
                    name: 'purpose'
                },
                {
                    data: 'frequency',
                    name: 'frequency'
                },
                {
                    data: 'duration',
                    name: 'duration'
                },
                {
                    data: 'notes',
                    name: 'notes'
                },
            ];
            var url = '/ccm/ajax/healthServices/' + id + '/' + sectionId;
        }

        $('#' + presectionId + ' .additional_table').attr('id', presectionId + "_additional_table");
        table_id = presectionId + "_additional_table";
        $('.patient_data_type').val(sectionId);
        // var url = '/ccm/ajax/addAllergies/'+id+'/'+sectionId;
        alert(url);
        alert(table_id);
        var table1 = util.renderDataTable(table_id, url, columns1, "{{ asset('') }}");
        // nearest_step_id = $(".nav-pills").find('a').attr('id');
        // nearest_step_id = $(".allergy_form").parent().parent().parent().parent().parent().parent().children().children().children().attr('id');
        // nearest_step_id = $(this).parent().attr('class');
        //  alert(nearest_step_id);
        // var nearest_step_id = $(".allergy_div").attr("id");
        // alert(nearest_step_id);
        // $('table').attr('id','test');

        // alert(table_id);

        // table_id = $("#"+nearest_step_id+" #additional_table"); 
        // alert(table_id);
        // table_id = $("#drug"+ ".additional_table").attr('id');
        // var table_id = $(this).after("table:first").attr("id");
        // alert( table_id );


        //url = url.replace(':id', id);
        // console.log(url);

    }
    /* $('.tabClick').on('click', function() {

         var sectionId = $(this).attr('href');
         sectionId = sectionId.substring(1, sectionId.length);
        patient_id = $("#patient_id").val(); 
        renderAllergiesTable(patient_id, sectionId);
     });*/

    // $('.allergiestab').on('click', function() {
    //      var sectionId = $(this).attr('href');
    //      sectionId = sectionId.substring(1, sectionId.length);
    //     patient_id = $("#patient_id").val(); 
    //     renderAllergiesTable(patient_id, sectionId);
    // });

    $('#patient_data-save').on('click', function() {
        // alert( 'patient_data' );
        $.ajax({
            type: 'post',
            url: '/ccm/savePatientData',
            data: $("#patient_data-form").serialize(),
            success: function(response) {
                //   alert(response);
            },
        });
    });


    function ajaxRenderTree(obj, label, id, count, tree_key) {
        var tree = JSON.stringify(obj);
        var treeObj = JSON.parse(tree);
        //alert("#" + id + '' + tree_key);
        var parentId = $("#" + id + '' + tree_key + '' + count).parents('.mb-4').attr('id');
         //alert(parentId);
        if ($("#" + id + '' + tree_key + '' + count).is(':checked')) {
            $("#" + id + '' + tree_key + '' + count).closest('#' + parentId)
                .find('input[type=radio]').not($("#" + id + '' + tree_key + '' + count))
                .prop('checked', false);
        }
        var treelength = (id + '' + tree_key).length;
        $("#question" + tree_key).find('.' + treelength).remove();
        if (treeObj[count].hasOwnProperty('qs')) {
            const propOwn = Object.getOwnPropertyNames(treeObj[count].qs);
            var qtncount = propOwn.length;
            for (var j = 1; j <= qtncount; j++) {
                var ids = id + '' + j;
                var tkey2 = tree_key + '' + ids;
                var qs_label = label.replace("[val]", "");
                var question_label = qs_label + "[qs][" + j + "][q]";
                var optkey = ids + '' + tree_key;
                if ((treeObj[count].qs[j]).hasOwnProperty('opt')) {
                    var cls = 'radioVal';
                } else {
                    var cls = 'radionotval';
                }
                if (treelength == 3) {
                    var tkey = tree_key;
                } else {
                    var tkey = tree_key + '' + ids.slice(0, -1);
                }

                $("#question" + tkey).append('<div class="mb-4 ' + cls + ' ' + treelength + '" id="' + tree_key + 'general_question' + ids + '"><label for="are-you-in-pain" class="col-md-12"><input type="hidden" name="' + question_label + '" value="' + treeObj[count].qs[j].q + '">' + treeObj[count].qs[j].q + '</label><div class="d-inline-flex mb-2 col-md-12" id="options' + optkey + '"></div>	<p class="message" style="color:red"></p><div id="question' + tkey2 + '"></div></div>');
                if ((treeObj[count].qs[j]).hasOwnProperty('opt')) {
                    var obj1 = JSON.stringify(treeObj[count].qs[j].opt);
                    var optcount = Object.getOwnPropertyNames(treeObj[count].qs[j].opt).length;
                    for (var i = 1; i <= optcount; i++) {
                        optlabelkey = ids + '' + tree_key+''+i;
                        var op_label = question_label.replace("[q]", "");
                        var option_label = op_label + '[opt][' + i + '][val]';
                        //alert(option_label);
                        var hidden_opt_id = 'opt' + ids + '' + i + '' + tree_key;
                        if (treeObj[count].qs[j].opt[i].val == 'default yes') {
                            $("#options" + optkey).append("<label class='radio radio-primary mr-3'><input type='hidden' id='" + hidden_opt_id + "' value='" + option_label + "'><input type='hidden' name='" + option_label + "' id='" + optlabelkey + "' value='" + treeObj[count].qs[j].opt[i].val + "'></label><input type='radio' style='display:none' checked>");
                            ajaxRenderTree1(treeObj[count].qs[j].opt, ids, j, i, tree_key);
                        } else {
                            $("#options" + optkey).append("<label class='radio radio-primary mr-3'><input type='hidden' id='" + hidden_opt_id + "' value='" + option_label + "'><input type='radio' name='" + option_label + "' id='" + optlabelkey + "' value='" + treeObj[count].qs[j].opt[i].val + "' onchange='ajaxRenderTree1(" + obj1 + "," + ids + "," + j + "," + i + "," + tree_key + ")' ><span>" + treeObj[count].qs[j].opt[i].val + "</span><span class='checkmark'></span></label>");
                        }

                    }

                }

            }
        }
    }

    function ajaxRenderTree1(obj, id, count, objCount, tree_key) {
        var label = $("#opt" + id + '' + objCount + '' + tree_key).val();
        //alert(label);
        ajaxRenderTree(obj, label, id, objCount, tree_key);
    }
    $('#generalQue').on('click', function() {
        //alert('1');
        $("#time-container").val(AppStopwatch.pauseClock);
        var timer_start = $("#timer_start").val();
        var timer_paused = $("#time-container").text().replace(/ /g, '');
        var stage_id = 11;
        var valid = true;
        $("div.radioVal").each(function() {
            if (!$('input:radio:checked', this).length > 0) {
                $('p.message', this).text("Please select at least one!");
                valid = false;
            } else {
                $('p.message', this).text("");
            }
        });

        if (valid == true) {
            //alert($("form#general_question_form").serialize());
            $.ajax({
                type: 'post',
                url: '/ccm/saveGeneralQuestion',
                data: $("form#general_question_form").serialize() + '&timer_start=' + timer_start + '&timer_paused=' + timer_paused + '&stage_id=' + stage_id,
                success: function(response) {
                    $("#timer_start").val(timer_paused);
                   // $("#general_question_form")[0].reset();
                    nextMove('call-close-icon-tab');
                },
            });
        }

        return valid;
    });

    function checkQuestion(obj, i) {
        //alert(obj);
        // var i = 1;
        var tree = JSON.stringify(obj);
        var treeobj = JSON.parse(tree);
        //alert(treeobj.qs.opt[i].val);
        for (j = 1; j < 10; j++) {
            if ((treeobj.qs.opt).hasOwnProperty(j)) {
                var prnt = $('input[value="' + treeobj.qs.q + '"]').parents('.mb-4').attr('id');
                $('#' + prnt).find('input:radio[value="' + treeobj.qs.opt[j].val + '"]').attr('checked', true).change();
                var obj1 = treeobj.qs.opt;
                renderEditquestion(obj1, j);
            }
        }

    }

    function renderEditquestion(obj1, i) {
        var tree = JSON.stringify(obj1);
        var treeobj = JSON.parse(tree);
        var l = 1;
        if (treeobj[i].hasOwnProperty('qs')) {
            for (j = 1; j < 10; j++) {
                if ((treeobj[i].qs[l]).hasOwnProperty('opt')) {
                    if ((treeobj[i].qs[l].opt).hasOwnProperty(j)) {
                        var prnt = $('input[value="' + treeobj[i].qs[l].q + '"]').parents('.mb-4').attr('id');
                        $('#' + prnt).find('input:radio[value="' + treeobj[i].qs[l].opt[j].val + '"]').attr('checked', true).change();
                        renderEditquestion(treeobj[i].qs[l].opt, j);
                    }
                }
            }
            //var prnt = $('input[value="' + treeobj[i].qs[i].q + '"]').parents('.mb-4').attr('id');
            //if ((treeobj[i].qs[i]).hasOwnProperty('opt')) {
            //  $('#' + prnt).find('input:radio[value="' + treeobj[i].qs[i].opt[i].val + '"]').attr('checked', true).change();
            //renderEditquestion(treeobj[i].qs[i].opt, i);
            //}
        }
    }

    function nextMove(id) {
        //alert('here');
        $('#' + id).click()
    }
</script>

@endsection