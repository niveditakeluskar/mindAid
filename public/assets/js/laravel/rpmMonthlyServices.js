!function(e){var t={};function n(i){if(t[i])return t[i].exports;var a=t[i]={i:i,l:!1,exports:{}};return e[i].call(a.exports,a,a.exports,n),a.l=!0,a.exports}n.m=e,n.c=t,n.d=function(e,t,i){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:i})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var i=Object.create(null);if(n.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var a in e)n.d(i,a,function(t){return e[t]}.bind(null,a));return i},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="/",n(n.s=8)}({8:function(e,t,n){e.exports=n("jRDW")},jRDW:function(e,t){var n=function(e,t){$.get(t,e,(function(e){for(var t in e)form.dynamicFormPopulate(t,e[t])})).fail((function(e){console.error("Population Error:",e)}))},i=function(e,t,n){if(200==n.status){$("form[name='monthlyservice'] .alert").show(),$("form[name='monthlyservice']")[0].reset(),$("#success1").show(),$("#danger1").hide();var i='<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Step2 Added Successfully!</strong></div>';$("#success1").html(i);var a=$(".main-content").offset().top;$(window).scrollTop(a),setTimeout((function(){$("#success1").hide(),s("monthly_step_3")}),3e3);var o=$("form[name='part_of_research_study_form'] input[name='end_time']").val();$("#timer_start").val(o),$(".last_time_spend").html(o)}else{$("form[name='monthlyservice'] .alert").show(),$("#danger1").show(),$("#success1").hide();i='<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill all required fields!</strong></div>';$("#danger1").html(i),setTimeout((function(){$("#danger1").hide()}),3e3)}},a=function(e,t,n){if(200==n.status){$("form[name='monthly_service_form'] .alert").show(),$("form[name='monthly_service_form']")[0].reset(),$("#success2").show(),$("#danger2").hide();var i='<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Step2 Added Successfully!</strong></div>';$("#success2").html(i);var a=$(".main-content").offset().top;$(window).scrollTop(a),setTimeout((function(){$("#success2").hide(),s("monthly_step_3")}),3e3);var o=$("form[name='part_of_research_study_form'] input[name='end_time']").val();$("#timer_start").val(o),$(".last_time_spend").html(o)}else{$("form[name='monthly_service_form'] .alert").show(),$("#danger2").show(),$("#success2").hide();i='<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill all required fields!</strong></div>';$("#danger2").html(i),setTimeout((function(){$("#danger2").hide()}),3e3)}},o=function(e,t,n){if(200==n.status){$("form[name='monthly_within_guideline_form'] .alert").show(),$("form[name='monthly_within_guideline_form']")[0].reset(),$("#success3").show(),$("#danger3").hide();var i='<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Step2 Added Successfully!</strong></div>';$("#success3").html(i);var a=$(".main-content").offset().top;$(window).scrollTop(a),setTimeout((function(){$("#success3").hide(),s("monthly_step_3")}),3e3);var o=$("form[name='part_of_research_study_form'] input[name='end_time']").val();$("#timer_start").val(o),$(".last_time_spend").html(o)}else{$("form[name='monthly_within_guideline_form'] .alert").show(),$("#success3").hide(),$("#danger3").show();i='<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill all required fields!</strong></div>';$("#danger3").html(i),setTimeout((function(){$("#danger3").hide()}),3e3)}},r=function(e,t,n){if(200==n.status){$("form[name='monthly_out_of_guidelines'] .alert").show(),$("form[name='monthly_out_of_guidelines']")[0].reset(),$("#success4").show(),$("#danger4").hide();var i='<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Step2 Added Successfully!</strong></div>';$("#success4").html(i);var a=$(".main-content").offset().top;$(window).scrollTop(a),setTimeout((function(){$("#success4").hide(),s("monthly_step_3")}),3e3);var o=$("form[name='part_of_research_study_form'] input[name='end_time']").val();$("#timer_start").val(o),$(".last_time_spend").html(o)}else{$("form[name='monthly_out_of_guidelines'] .alert").show(),$("#danger4").show(),$("#success4").hide();i='<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill all required fields!</strong></div>';$("#danger4").html(i),setTimeout((function(){$("#danger4").hide()}),3e3)}},s=function(e){setTimeout($("#"+e).click(),3e4)},l=function(){var e=$("#review_data").find("option:selected").val()||"",t=$("#review_data").find("option:selected").text()||"";if($("#summary_review").html(t),1==e){var n=$("#contact_via").val();if("text"==n){var i=$("#text_contact_number").find("option:selected").text()||"",a=$("#content_title").find("option:selected").text()||"",o=$("#content_area").text()||"";$("#summary_text_contact").html(i),$("#summary_text_template").html(a),$("#summary_text_template_content").html(o),$("#summary_not_recorded_text").show(),$("#summary_not_recorded_call").hide(),$("#summary_within_guidelines").hide(),$("#summary_out_of_guidelines").hide()}if("call"==n){var r=$("input[name='call_status']:checked").attr("radioLable")||"";if($("#summary_call_status").html(r),"Call Answered"==r){var s=$("#call_content_title").find("option:selected").text()||"",l=$("#script").html()||"";$("#summary_content_name").html(s),$("#summary_call_template_content").html("Message: "+l)}else{var m=$("#answer").find("option:selected").text()||"";$("#summary_content_name").html(m),$("#summary_call_template_content").html("")}$("#summary_not_recorded_text").hide(),$("#summary_not_recorded_call").show(),$("#summary_within_guidelines").hide(),$("#summary_out_of_guidelines").hide()}}if(2==e){var c=$("#within_guidelines_contact_number").find("option:selected").text()||"",_=$("#within_guideline_content_title").find("option:selected").text()||"",d=$("#content_area_msg").text()||"";alert(d),$("#summary_leave_message_in_emr").prop("checked")?$("#summary_leave_message_in_emr").html("leaved message in emr"):$("#summary_leave_message_in_emr").html("not leaved message in emr"),$("#summary_within_guidelines_contact_number").html(c),$("#summary_within_guideline_content_title").html(_),$("#summary_within_guideline_content_area_msg").html(d),$("#summary_not_recorded_text").hide(),$("#summary_not_recorded_call").hide(),$("#summary_within_guidelines").show(),$("#summary_out_of_guidelines").hide()}if(3==e){var u=$("input[name='patient_condition']:checked").val()||"";$("#summary_questionnaire").css("pointer-events","none"),1==u?($("#emergency_range_div").html(""),$("#summary_patient_condition").html(""),$("#summary_patient_condition").text("In Call PCP Office Range"),$("#summary_questionnaire").html(""),$("#office_range_div").clone().appendTo("#summary_questionnaire")):($("#office_range_div").html(""),$("#summary_patient_condition").html(""),$("#summary_patient_condition").text("In Emergency Range"),$("#summary_questionnaire").html(""),$("#emergency_range_div").clone().appendTo("#summary_questionnaire")),$("#summary_not_recorded_text").hide(),$("#summary_not_recorded_call").hide(),$("#summary_within_guidelines").hide(),$("#summary_out_of_guidelines").show()}};window.rpmMonthlyServices={init:function(){var e=$("#hidden_id").val(),t=$("input[name='module_id']").val(),m=(new Date).getFullYear(),c=(new Date).getMonth()+1;if(util.getPatientStatus(e,t),util.getPatientCareplanNotes(e,t),util.getPatientPreviousMonthNotes(e,t,c,m),$("form[name='monthly_within_guideline_form'] #within_guideline_content_title").on("change",(function(){util.getCallScriptsById($("form[name='monthly_within_guideline_form'] #within_guideline_content_title").val(),"#trial","form[name='monthly_within_guideline_form'] input[name='template_type_id']","form[name='within_guideline_content_title'] input[name='content_title']")})),$('form[name="personal_notes_form"] .submit-personal-notes').on("click",(function(e){$("#time-container").val(AppStopwatch.pauseClock);var t=$("#timer_start").val(),n=$("#time-container").text();$("form[name='personal_notes_form'] input[name='start_time']").val(t),$("form[name='personal_notes_form'] input[name='end_time']").val(n),$("#timer_end").val(n),$("#time-container").val(AppStopwatch.startClock),form.ajaxSubmit("personal_notes_form",patientEnrollment.onPersonalNotes)})),$('form[name="part_of_research_study_form"] .submit-part-of-research-study').on("click",(function(){$("#time-container").val(AppStopwatch.pauseClock);var e=$("#timer_start").val(),t=$("#time-container").text();$("form[name='part_of_research_study_form'] input[name='start_time']").val(e),$("form[name='part_of_research_study_form'] [name='end_time']").val(t),$("#timer_end").val(t),$("#time-container").val(AppStopwatch.startClock),form.ajaxSubmit("part_of_research_study_form",patientEnrollment.onPartOfResearchStudy)})),$('form[name="patient_threshold_form"] .submit-patient-threshold').on("click",(function(){$("#time-container").val(AppStopwatch.pauseClock);var e=$("#timer_start").val(),t=$("#time-container").text();$("form[name='patient_threshold_form'] input[name='start_time']").val(e),$("form[name='patient_threshold_form'] [name='end_time']").val(t),$("#timer_end").val(t),$("#time-container").val(AppStopwatch.startClock),form.ajaxSubmit("patient_threshold_form",patientEnrollment.onPatientThreshold)})),form.ajaxForm("monthlyservice",i,(function(){$("#time-container").val(AppStopwatch.pauseClock);var e=$("#timer_start").val(),t=$("#time-container").text();return $("form[name='monthlyservice'] input[name='start_time']").val(e),$("form[name='monthlyservice'] input[name='end_time']").val(t),$("input[name='end_time']").val(t),$("#time-container").val(AppStopwatch.startClock),!0})),form.ajaxForm("monthly_service_form",a,(function(){$("#time-container").val(AppStopwatch.pauseClock);var e=$("#timer_start").val(),t=$("#time-container").text();return $("form[name='monthly_service_form'] input[name='start_time']").val(e),$("form[name='monthly_service_form'] input[name='end_time']").val(t),$("input[name='end_time']").val(t),$("#time-container").val(AppStopwatch.startClock),!0})),form.ajaxForm("monthly_within_guideline_form",o,(function(){$("#time-container").val(AppStopwatch.pauseClock);var e=$("#timer_start").val(),t=$("#time-container").text();return $("form[name='monthly_within_guideline_form'] input[name='start_time']").val(e),$("form[name='monthly_within_guideline_form'] input[name='end_time']").val(t),$("input[name='end_time']").val(t),$("#time-container").val(AppStopwatch.startClock),!0})),form.ajaxForm("monthly_out_of_guidelines",r,(function(){$("#time-container").val(AppStopwatch.pauseClock);var e=$("#timer_start").val(),t=$("#time-container").text();return $("form[name='monthly_out_of_guidelines'] input[name='start_time']").val(e),$("form[name='monthly_out_of_guidelines'] input[name='end_time']").val(t),$("input[name='end_time']").val(t),$("#time-container").val(AppStopwatch.startClock),!0})),$("#content-template").hide(),$("#monthly_services_nxt_txt_date_div").hide(),$("#summary").hide(),$("#contact_via").val(""),$("#summary_not_recorded_text").hide(),$("#summary_not_recorded_call").hide(),$("#summary_within_guidelines").hide(),$("#summary_out_of_guidelines").hide(),$("#start").hide(),$("#pause").show(),$("#time-container").val(AppStopwatch.startClock),$("#monthly_services_nxt_txt_date_btn").click((function(){$("#monthly_services_nxt_txt_date_div").show()})),$("#review_data").change((function(){if(1==$("#review_data").val()&&($("#buttons").show(),$("#within_guidelines").hide(),$("#out_of_guidelines").hide(),$("#question").hide(),$("#questionnaireButtons").hide(),$("#record_details").hide(),$("#summary").hide()),2==$("#review_data").val()){s("monthly_step_2"),$(".step2").trigger("click"),$("#within_guidelines").show(),$("#out_of_guidelines").hide(),$("#buttons").hide(),$("#content-template").hide(),$("#call_section").hide(),$("#question").hide(),$("#questionnaireButtons").hide(),$("#record_details").hide(),$("#summary").hide();var e=$("#trial").html();$("#content_area_msg").val($(e).text())}3==$("#review_data").val()&&($("#questionnaireButtons").show(),$("#question").show(),$("#buttons").hide(),$("#content-template").hide(),$("#call_section").hide(),$("#within_guidelines").hide(),$("#record_details").hide(),$("#summary").hide())})),$("#answered").prop("checked")){if("2"==$("input[name=call_radio]:checked","#action").val())$("#date").val(),$("#time").val()}$("#text").click((function(){s("monthly_step_2"),$(".step2").trigger("click"),$("#content-template").show(),$("#template_type_id").val(2),$("#call_section").hide(),$("#summary_not_recorded_text").show(),$("#summary_not_recorded_call").hide(),$("#contact_via").val("text")})),$("#calling").click((function(){s("monthly_step_2"),$(".step2").trigger("click"),$("#call_section").show(),$("#content-template").hide(),$("#summary_not_recorded_text").hide(),$("#summary_not_recorded_call").show(),$("#contact_via").val("call")})),$("#not_answered").click((function(){$("#content_area").text(""),$("#content_area_msg").text(""),$("#answer").show(),$("#call_not_answered_save").show(),$("#call_scripts").hide()})),$("#answered").click((function(){$("#answer").hide(),$("#call_scripts").show(),$("#call_not_answered_save").hide()})),$("#call_content_title").change((function(){var e=$(this).val();$.ajax({type:"get",url:"/rpm/getContente",data:{_token:"{!! csrf_token() !!}",content_title:e},success:function(e){var t=jQuery.parseJSON(e[0].content).message;$("#script").html(t),$("#call_content_area").val(t)}})})),$(".contact_number").change((function(){var e=$(this).val();$("#phone").val(e)})),$(".content_title").change((function(){var e=$(this).val();$.ajax({type:"get",url:"/rpm/getContente",data:{_token:"{!! csrf_token() !!}",content_title:e},success:function(e){var t=jQuery.parseJSON(e[0].content).message;$(".content_area").html($(t).text())}})})),$("#office_range").click((function(){s("monthly_step_2"),$(".step2").trigger("click"),$("#out_of_guidelines").show(),$("#questionnaireButtons").show(),$("#office_range_div").show(),$("#emergency_range_div").hide(),$("#patient-condition").val(1)})),$("#emergency_range").click((function(){s("monthly_step_2"),$(".step2").trigger("click"),$("#out_of_guidelines").show(),$("#questionnaireButtons").show(),$("#office_range_div").hide(),$("#emergency_range_div").show(),$("#patient-condition").val(2)})),$("form[name='monthlyservice'] .content_title option:last").attr("selected","selected").change(),$("form[name='monthly_service_form'] #call_content_title option:last").attr("selected","selected").change(),$("form[name='monthly_within_guideline_form'] #within_guideline_content_title option:last").attr("selected","selected").change(),$("#save_office_pcp").click((function(){$("#record_details").show()})),$("#save_emergency_pcp").click((function(){$("#record_details").show()})),$("#end_call, #call_not_answered_save, #save_within_guideline, #send").click((function(){$("#monthly_services_nxt_txt_date_div").hide()})),$(".summarize-details").click((function(){alert("summerize changes"),l(),$("#summary").show()})),$("#completebutton").click((function(){$("#msgdiv").show();$("#msgdiv").html('<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Step3 Added Successfully!</strong></div>'),setTimeout((function(){$("#msgdiv").hide()}),3e3)})),$("form[name='rpm_medications_form'] #medication_med_id").on("change",(function(){var e=$("#medication_med_id").val();if("other"==e)$(".med_id").removeClass("col-md-6").addClass("col-md-4"),$(".description").removeClass("col-md-6").addClass("col-md-4"),$("#med_name").show(),$("#medication_description").val(""),$("#medication_purpose").val(""),$("#medication_strength").val(""),$("#medication_dosage").val(""),$("#medication_route").val(""),$("#medication_frequency").val(""),$("#duration").val(""),$("#medication_drug_reaction").val(""),$("#medication_pharmacogenetic_test").val("");else{$(".med_id").removeClass("col-md-4").addClass("col-md-6"),$(".description").removeClass("col-md-4").addClass("col-md-6"),$("#med_name").hide(),$("#medication_description").val(""),$("#medication_purpose").val(""),$("#medication_strength").val(""),$("#medication_dosage").val(""),$("#medication_route").val(""),$("#medication_frequency").val(""),$("#duration").val(""),$("#medication_drug_reaction").val(""),$("#medication_pharmacogenetic_test").val("");var t=window.location.pathname;parts=t.split("/"),patientId=parts[parts.length-1],id=patientId,url="/rpm/get-selected-medications_patient-by-id/"+patientId+"/"+e+"/selectedmedicationspatient",n(id,url)}})),$("body").on("click",".editMedicationsRpmPatient",(function(){var e=$(this).data("id");url="/rpm/get-all-medications_patient-by-id/"+e+"/medicationspatient",n(e,url)})),$("body").on("click",".deleteMedicationsRpmPatient",(function(){var e=$(this).data("id");if(!confirm("Are you sure you want to delete this?"))return!1;$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}}),$.ajax({type:"post",url:"/rpm/delete-medications_patient-by-id/"+e,data:{id:e},success:function(e){renderMedicationsTable()}})}))},nextStep:s,getFormValues:l}}});