/**
* 
*/
const URL_POPULATE_PREPARATION_NOTES = "/ccm/ajax/populate_preparation_notes";
const URL_POPULATE_RESEARCH_FOLLOWUP_PREPARATION_NOTES = "/ccm/ajax/populate_research_followup_preparation_notes";
const URL_POPULATE_HIPPA = "/ccm/ajax/populate_hippa";
const URL_POPULATE_HOMESERVICE = "/ccm/ajax/populate_home_service";
const URL_POPULATE_CALL_CLOSE = "/ccm/ajax/populate_call_close";
const URL_POPULATE_CALL_WRAPUP = "/ccm/ajax/populate_call_wrapup";
const URL_POPULATE_FOLLOWUP = "/ccm/ajax/populate_follow_up";
const URL_SAVE = "/ajax/tcm_save";
const URL_SUBMIT = "/ajax/tcm_submit";
const URL_PRINT = "/tcm/printing/";
const URL_POPULATE_DIAGNOSIS_LIST = '/ccm/patient-care-plan-list';


var baseURL = window.location.origin + '/';
var patient_id = $("#hidden_id").val();
var sPageURL = window.location.pathname;
parts = sPageURL.split("/"),
    module = parts[parts.length - 3],
    sub_module = parts[parts.length - 2];
/**
 * Populate the form of the given patient
 */
var taskcount = 0;
var allergycount = 0;
var labcount = 0;
var imagingcount = 0;
var healthdatacount = 0;
var summarycount = 0;

var populateForm = function (data, url) {
    $.get(
        url,
        data,
        function (result) {
            for (var key in result) {

                if(key == 'hippa_form'){
                    if (result[key] != null && typeof(result[key]) != "undefined" && result[key] != "" ) {
                        var hippa_vf = result[key]['static']['verification'];
                        alert(hippa_vf);
                        if(hippa_vf = 1){
                            $('#ccm-relationship-icon-tab').removeClass('disabled');
                            $('#ccm-research-follow-up-icon-tab').removeClass('disabled');
                            $('#ccm-general-questions-icon-tab').removeClass('disabled');
                            $('#ccm-call-close-icon-tab').removeClass('disabled');
                            // $('#ccm-call-wrapup-icon-tab').removeClass('disabled');
                        }
                    }else{
                        $('#ccm-relationship-icon-tab').addClass('disabled');
                        $('#ccm-research-follow-up-icon-tab').addClass('disabled');
                        $('#ccm-general-questions-icon-tab').addClass('disabled');
                        $('#ccm-call-close-icon-tab').addClass('disabled');
                        // $('#ccm-call-wrapup-icon-tab').addClass('disabled');

                        $("#ccm-relationship-icon-tab").css("background-color","#c0c0c047");
                        $('#ccm-research-follow-up-icon-tab').css("background-color","#c0c0c047");
                        $('#ccm-general-questions-icon-tab').css("background-color","#c0c0c047");
                        $('#ccm-call-close-icon-tab').css("background-color","#c0c0c047");
                        // $('#ccm-call-wrapup-icon-tab').css("background-color","#c0c0c047");
                    }
               }

                if (key == 'callwrapup_form') {  
                    // debugger;
                   
                    if (result[key] != null && typeof(result[key]) != "undefined" && result[key] != "" ) {
                        var emr_monthly_summarys = result[key].static['emr_monthly_summary'];
                        var summarys = result[key].static['summary'];
						var summaryslength = result[key].static['summary'].length;
                        var checklist_data = result[key].static['checklist_data'];
						
						if(result[key].static['additional_services'] != null && typeof(result[key].static['additional_services'])!= "undefined" && result[key].static['additional_services'] != ""){

                            var additionalservices = result[key].static['additional_services'][0]['notes'];  
                           

                            var resultnew = additionalservices.split(';').map(e => e.split(':'));
                            
                            for(var i = 0; i < resultnew.length; i++){
                                var servicedata = resultnew[i];
                                
                                for(var k = 0 ; k< servicedata.length; k++){
    
                                    var maindivname = servicedata[0];
                                    var lwercase = maindivname.toLowerCase();
                                    var trimcase = lwercase.trim();
                                    newtrimcase = trimcase.replace('/', '');
                                    var mymaindiv = newtrimcase.replace(/ /g, "");
                                    
                                 
                                    var finalservicedata = servicedata[k];

                                    if(finalservicedata == 'No Additional Services Provided ' || finalservicedata == "No Additional Services Provided " ){
                                        // alert('if');
                                        // alert(finalservicedata);
                                        if(finalservicedata!=''){
                                          
                                            $("form[name='callwrapup_form'] #no_additional_services_provided").prop('checked', true);  
                                           
                                        }
    
    
                                    }else{
                                    
                                    if (finalservicedata.indexOf(',') > -1) { 
                                        var additionaldata = finalservicedata.split(',');                                       
    
                                                for(var n = 0; n< additionaldata.length; n++){
                                                    w = additionaldata[n];
                                                    var y = w.trim();
                                                    var y_old = y.replace(/ /g, "_"); 
                                                    var mydata = y_old.replace(/\//g, "_");
                                                  
                                                    if(mydata!=''){
                                                    
                                                        var addname = "RRclass "+mydata;
                                                        var checkboxid = mymaindiv+'_'+mydata;
                                                       
                                                        $("form[name='callwrapup_form'] #"+checkboxid).prop('checked', true);
                                                        // $("form[name='callwrapup_form'] #"+mydata).prop('checked', true);
                                                        // $('input:checkbox[name="urgentemergentresponse[Interaction_with_Office_Staff]"][value="1"]').prop('checked',true);
                                                       
                                                    }
                                                  
                                                    
                                                }
    
                                               
                                     }else{
                                        var f_lowercase = finalservicedata.toLowerCase();
                                        var x = f_lowercase.trim();
                                        x = x.replace('/', ' ');
                                        var n_lowercase_old = x.replace(/ /g, "_");
                                        var n_lowercase = n_lowercase_old.replace(/\//g, "_");
                                     
                                        if( n_lowercase!='' ){
                                      
                                            $("form[name='callwrapup_form'] #"+n_lowercase ).prop('checked', true); 
                                            
                                        }
                                   
                                     }

                                    }

    
                                }
                            }
                           
    
                        }
    
                        callWrapUpShowHide();
						if(emr_monthly_summarys != null && emr_monthly_summarys != ""){
                            $("textarea#callwrap_up_emr_monthly_summary").html(emr_monthly_summarys[0]['notes']);
                        }
                      
						 var newwwchildrenlength = $("div#additional_monthly_notes").children().length;
                        var inc_notes = 0;
						  if(newwwchildrenlength == summaryslength ){
                        
                        }else{
                        for (var summary in summarys) {
                           
                              
                                var e_date = summarys[summary]['record_date'];
                                edate = e_date.split(' ');
                                // console.log(edate);
                                var enew_date = edate[0];
                                var echange_date_format = enew_date.split('-');
                                var e_set_date = echange_date_format[2] + '-' + echange_date_format[0] + '-' + echange_date_format[1];
                                console.log(e_set_date); 
                                // alert(e_set_date);  
                                

                                $('#additional_monthly_notes').append('<div class="additionalfeilds additionalfeilds row"  style="margin-left: 0.05rem !important;  margin-bottom: 0.5rem; "><div class="col-md-4"><input type="date" class="form-control" id="emr_monthly_summary_date_' + inc_notes + '" name="emr_monthly_summary_date[]" ><div class="invalid-feedback"></div></div><div class="col-md-8"><textarea  class="form-control " cols="90" style="margin-bottom: 1.1rem !important;"  name="emr_monthly_summary[]" >' + summarys[summary]['notes'] + '</textarea><div class="invalid-feedback"></div><i type="button" class="removenotes  i-Remove" style="color: #f44336;  font-size: 22px;margin-top: -37px;margin-right: -51px;float: right;"></i></div></div>');
                                $("form[name='callwrapup_form'] #emr_monthly_summary_date_" + inc_notes).val(e_set_date); 
                            
                            inc_notes++;  
                        }
						}
                        if (checklist_data && checklist_data['emr_entry_completed'] != null) {
                            var emr_entry_completed = checklist_data['emr_entry_completed'];
                            
                            if (emr_entry_completed == 1) {
                                $('#emr_entry_completed').prop('checked', true);// Checks it
                            } else {
                                $('#emr_entry_completed').prop('checked', false); // Unchecks it
                            }
                        }
                        if (checklist_data && checklist_data['called_office_patientbehalf'] != null) {
                            var checklist_data = result[key].static['checklist_data'];
                            var called_office_patientbehalf = checklist_data['called_office_patientbehalf'];
                            if (called_office_patientbehalf == 1) {
                                $('#called_office_patientbehalf').prop('checked', true);// Checks it
                            } else {
                                $('#called_office_patientbehalf').prop('checked', false); // Unchecks it
                            }
                        }
                        if (checklist_data && checklist_data['schedule_office_appointment'] != null) {
                            var checklist_data = result[key].static['checklist_data'];
                            var schedule_office_appointment = checklist_data['schedule_office_appointment'];
                            if (schedule_office_appointment == 1) {
                                $('#schedule_office_appointment').prop('checked', true);// Checks it
                            } else {
                                $('#schedule_office_appointment').prop('checked', false); // Unchecks it
                            }
                        }
                        if (checklist_data && checklist_data['resources_for_medication'] != null) {
                            var checklist_data = result[key].static['checklist_data'];
                            var resources_for_medication = checklist_data['resources_for_medication'];
                            if (resources_for_medication == 1) {
                                $('#resources_for_medication').prop('checked', true);// Checks it
                            } else {
                                $('#resources_for_medication').prop('checked', false); // Unchecks it
                            }
                        }
                        if (checklist_data && checklist_data['medical_renewal'] != null) {
                            var checklist_data = result[key].static['checklist_data'];
                            var medical_renewal = checklist_data['medical_renewal'];
                            if (medical_renewal == 1) {
                                $('#medical_renewal').prop('checked', true);// Checks it
                            } else {
                                $('#medical_renewal').prop('checked', false); // Unchecks it
                            }
                        }
                        if (checklist_data && checklist_data['referral_support'] != null) {
                            var checklist_data = result[key].static['checklist_data'];
                            var referral_support = checklist_data['referral_support'];
                            if (referral_support == 1) {
                                $('#referral_support').prop('checked', true);// Checks it
                            } else {
                                $('#referral_support').prop('checked', false); // Unchecks it
                            }
                        }
						if (checklist_data && checklist_data['no_other_services'] != null) {
                            var checklist_data = result[key].static['checklist_data'];
                            var no_other_services = checklist_data['no_other_services'];
                            if (no_other_services == 1) {
                                $('#no_other_services').prop('checked', true);// Checks it
                            } else {
                                $('#no_other_services').prop('checked', false); // Unchecks it
                            }
                        }
                    }
                } else {
                    form.dynamicFormPopulate(key, result[key]);
                    if (key == 'call_close_form') {
                        if (result[key] != '') {
                            var date_enrolled = result[key].static['q2_datetime'];
                            if (date_enrolled != undefined) {
                                var a = date_enrolled.split(" ")[0];
                                var day = a.split("-");
                                $mydate = day[2] + '-' + day[0] + '-' + day[1];
                                document.getElementById("next_month_call_date").value = $mydate;
                            }
                        }
                    }
                    if (key == 'followup_task_edit_notes') {
                        if (result[key][0].notes != null) {
                            var notes = result[key][0].notes;
                            $('#notes').html(notes);
                        }
                        if (result[key][0].task_notes != null) {
                            var task_notes = result[key][0].task_notes;
                            $('#task_notes').html(task_notes);
                            $('#topic').val(task_notes);
                        }
                        if (result[key][0].task_date != null) {
                            var task_date = result[key][0].task_date;
                            if (task_date != '') {
                                date = task_date.split(' ');
                                var new_date = date[0];
                                var change_date_format = new_date.split('-');
                                var set_date = change_date_format[2] + '-' + change_date_format[0] + '-' + change_date_format[1];
                                $('#task_date').html(date[0]);
                                document.getElementById("task_date_val").value = set_date;
                            }
                        }
                        if (result[key][0].master_followuptask != null) {
                            var category = result[key][0].master_followuptask['task'];
                            $('#category').html(category);
                        }
                        if (result[key][0].status_flag == 1) {
                            $('#status_flag').prop('checked', true);
                        } else {
                            $('#status_flag').prop('checked', false);
                        }
                    }
                    if (key == 'call_preparation_preparation_followup_form' && result[key].hasOwnProperty("static")) {
                        if (result[key].static['condition_requirnment1'] != null) {
                            var CR1 = result[key].static['condition_requirnment1'];
                        } else {
                            var CR1 = "";
                        }
                        if (result[key].static['condition_requirnment2'] != null) {
                            var CR2 = result[key].static['condition_requirnment2'];
                        } else {
                            var CR2 = "";
                        }
                        if (result[key].static['condition_requirnment3'] != null) {
                            var CR3 = result[key].static['condition_requirnment3'];
                        } else {
                            var CR3 = "";
                        }
                        if (result[key].static['report_requirnment1'] != null) {
                            var RR1 = result[key].static['report_requirnment1'];
                        } else {
                            var RR1 = "";
                        }
                        if (result[key].static['report_requirnment2'] != null) {
                            var RR2 = result[key].static['report_requirnment2'];
                        } else {
                            var RR2 = "";
                        }
                        if (result[key].static['report_requirnment4'] != null) {
                            var RR4 = result[key].static['report_requirnment4'];
                        } else {
                            var RR4 = "";
                        }
                        if (result[key].static['report_requirnment5'] != null) {
                            var RR5 = result[key].static['report_requirnment5'];
                        } else {
                            var RR5 = "";
                        }
                        if (CR1 == 1 || CR2 == 1 || CR3 == 1) {
                            $('form[name="call_preparation_preparation_followup_form"] #call_preparation_note').css('display', 'block');
                            $('form[name="research_follow_up_preparation_followup_form"] #research_follow_up_note').css('display', 'block');
                        } else {
                            $('form[name="call_preparation_preparation_followup_form"] #call_preparation_note').css('display', 'none');
                            $('form[name="research_follow_up_preparation_followup_form"] #research_follow_up_note').css('display', 'none');
                        }

                        if (RR1 == 1 || RR2 == 1 || RR4 == 1 || RR5 == 1) {
                            $('form[name="call_preparation_preparation_followup_form"] #call_preparation_requirnment').css('display', 'block');
                            $('form[name="research_follow_up_preparation_followup_form"] #research_follow_up_requirnment').css('display', 'block');
                        } else {
                            $('form[name="call_preparation_preparation_followup_form"] #call_preparation_requirnment').css('display', 'none');
                            $('form[name="research_follow_up_preparation_followup_form"] #research_follow_up_requirnment').css('display', 'none');
                        }

                        if (result[key].static['condition_requirnment4'] != null) {
                            var CR4 = result[key].static['condition_requirnment4'];
                        } else {
                            var CR4 = "";
                        }
                        if (CR1 == 1 || CR2 == 1 || CR3 == 1 || CR4 == 1) {
                            $("form[name='research_follow_up_preparation_followup_form'] #research_follow_up_data_present_in_emr_yes").prop("checked", true);
                        } else {
                            $("form[name='research_follow_up_preparation_followup_form'] #research_follow_up_data_present_in_emr_no").prop("checked", true);
                        }
                    }
                }
            }
        }
    ).fail(function (result) {
        console.error("Population Error:", result);
    });
};

/**
 * Add a practice via Ajax request
 */
var onPreparationFollowUp = function (formObj, fields, response) {
    if (response.status == 200) {
        util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
        $('#number_tracking_labs_form')[0].reset();
        $('#number_tracking_imaging_form')[0].reset();
        $('#append_imaging').html('');
        $('#number_tracking_healthdata_form')[0].reset();
        $('#append_healthdata').html('');
        var patient_id = $("#hidden_id").val();
        var module_id = $("#research_follow_up_preparation_followup_form input[name='module_id']").val();
        var stage_id = $("#call_preparation_preparation_followup_form input[name='stage_id']").val();
        var year = (new Date).getFullYear();
        var month = (new Date).getMonth() + 1; //add +1 for current mnth
        util.totalTimeSpentByCM();
        util.getPatientRelationshipBuilding($("#patient_id").val());
        util.getRelationBuild($("#patient_id").val());
        util.getPatientCareplanNotes(patient_id, module_id);
        util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);

        $("form[name='call_preparation_preparation_followup_form'] .alert").show();
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        var sPageURL = window.location.pathname;
        parts = sPageURL.split("/"),
            id = parts[parts.length - 1];
        var patientId = id;
        carePlanDevelopment.renderLabsTable();
        carePlanDevelopment.renderVitalTable();
        var data = "";
        var researchPreparationNotesForm = 'research_follow_up_preparation_followup_form';
        var preparationNotesFormPopulateURL = URL_POPULATE_RESEARCH_FOLLOWUP_PREPARATION_NOTES + "/" + patientId + "/current";
        populateForm(patientId, preparationNotesFormPopulateURL);

        var table = $('#callwrap-list');
        table.DataTable().ajax.reload();
        var timer_paused = $("form[name='call_preparation_preparation_followup_form'] input[name='end_time']").val();
        $("#timer_start").val(timer_paused);
        if (module == 'rpm') {
            setTimeout(function () { util.totalTimeSpent(patient_id, module_id, stage_id); $('.alert').fadeOut('fast'); }, 5000);
            goToNextStep("review_data_1_id");
        } else {
            setTimeout(function () { util.totalTimeSpent(patient_id, module_id, stage_id); $('.alert').fadeOut('fast'); }, 5000);
            goToNextStep("call_step_1_id");
        }
    } else if (response.status == 406) {
        alert(response.data.message);
    }
};
var onPreparationResearchFollowUp = function (formObj, fields, response) {
    if (response.status == 200) {
        util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
        var patient_id = $("#hidden_id").val();
        var module_id = $("#research_follow_up_preparation_followup_form input[name='module_id']").val();
        var year = (new Date).getFullYear();
        var month = (new Date).getMonth() + 1; //add +1 for current mnth
        util.getPatientCareplanNotes(patient_id, module_id);
        util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
        util.totalTimeSpentByCM();
        $("form[name='research_follow_up_preparation_followup_form'] .alert").show();
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        setTimeout(function () { $('.alert').fadeOut('fast'); }, 5000);
        goToNextStep("ccm-general-questions-icon-tab");
        var sPageURL = window.location.pathname;
        parts = sPageURL.split("/"),
            id = parts[parts.length - 1];
        var patientId = id;
        carePlanDevelopment.renderLabsTable();
        var data = "";
        var researchPreparationNotesForm = 'research_follow_up_preparation_followup_form';
        var preparationNotesFormPopulateURL = URL_POPULATE_RESEARCH_FOLLOWUP_PREPARATION_NOTES + "/" + patientId + "/current";
        populateForm(patientId, preparationNotesFormPopulateURL);
        var table = $('#callwrap-list');
        table.DataTable().ajax.reload();
        var timer_paused = $("form[name='research_follow_up_preparation_followup_form'] input[name='end_time']").val();
        $("#timer_start").val(timer_paused);
    } else if (response.status == 406) {
        alert(response.data.message);
    }
};
/**
 * Add a practice via Ajax request
 */
// var onCallHippa = function (formObj, fields, response) { //moved to common js
//     if (response.status == 200) {
//         util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
//         $("form[name='hippa_form'] .alert").show();
//         util.totalTimeSpentByCM();
//         var scrollPos = $(".main-content").offset().top;
//         $(window).scrollTop(scrollPos);
//         if (sub_module == 'care-plan-development') {
//             setTimeout(function () { $("form[name='hippa_form'] .alert").fadeOut(); }, 5000);
//             goToNextStep("review-patient-tab");
//         } else {
//             setTimeout(function () { $('.alert').fadeOut('fast'); }, 5000);
//             goToNextStep("ccm-relationship-icon-tab");
//         }
//         var timer_paused = $("form[name='hippa_form'] input[name='end_time']").val();
//         $("#timer_start").val(timer_paused);
//     }
// };

var onRelationship = function (formObj, fields, response) {
    if (response.status == 200) {
        util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
        $("form[name='relationship_form'] .alert").show();
        var table = $('#callwrap-list');
        table.DataTable().ajax.reload();
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        var patient_id = $("#relationship_form input[name='patient_id']").val();
        var module_id = $("#relationship_form input[name='module_id']").val();
        var year = (new Date).getFullYear();
        var month = (new Date).getMonth() + 1; //add +1 for current mnth
        util.getPatientRelationshipBuilding($("#patient_id").val());
        util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
        util.getPatientStatus(patient_id, module_id);
        util.totalTimeSpentByCM();
        setTimeout(function () { $('.alert').fadeOut('fast'); }, 5000);
        goToNextStep("ccm-research-follow-up-icon-tab");
        var timer_paused = $("form[name='relationship_form'] input[name='end_time']").val();
        $("#timer_start").val(timer_paused);
    }
};

// var onCallClose = function (formObj, fields, response) { //moved to common js
//     if (response.status == 200) {
//         util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
//         util.getToDoListData($("#patient_id").val(), $("form[name='call_close_form'] input[name='module_id']").val());
//         //util.getDataCalender($("#patient_id").val(), $("form[name='call_close_form'] input[name='module_id']").val());
//         util.totalTimeSpentByCM();
//         $("form[name='call_close_form'] .alert").show();
//         var scrollPos = $(".main-content").offset().top;
//         $(window).scrollTop(scrollPos);
//         setTimeout(function () { $('.alert').fadeOut('fast'); }, 5000);
//         if (sub_module == 'care-plan-development') {
//             goToNextStep("call-wrapup-tab");
//         } else {
//             goToNextStep("ccm-call-wrapup-icon-tab");
//             getFollowupList($('#patient_id').val(), $("form[name='call_close_form'] input[name='module_id']").val());
//         }
//         var timer_paused = $("form[name='call_close_form'] input[name='end_time']").val();
//         $("#timer_start").val(timer_paused);
//     }
// };

// var onCallWrapUp = function (formObj, fields, response) { //moved to common js
//     if (response.status == 200) {
//         util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
//         var timer_paused = $("form[name='callwrapup_form'] input[name='end_time']").val();
//         $("#timer_start").val(timer_paused);
//         var patient_id = $("#hidden_id").val();
//         var module_id = $("#callwrapup_form input[name='module_id']").val();
//         var year = (new Date).getFullYear();
//         var month = (new Date).getMonth() + 1; //add +1 for current mnth
//         util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
//         util.getToDoListData($("#patient_id").val(), $("form[name='callwrapup_form'] input[name='module_id']").val());
//         //util.getDataCalender($("#patient_id").val(), $("form[name='callwrapup_form'] input[name='module_id']").val());
//         if (sub_module == 'care-plan-development') {
//             util.updateFinalizeDate($("input[name='patient_id']").val(), $("input[name='module_id']").val());
//             util.totalTimeSpentByCM();
//             $("form[name='callwrapup_form'] .alert-success").show();
//             var scrollPos = $(".main-content").offset().top;
//             $(window).scrollTop(scrollPos);
//             setTimeout(function () { $("form[name='callwrapup_form'] .alert").fadeOut(); }, 5000);
//         } else {
//             $("#callwrapform-success-alert").show();
//             var table = $('#callwrap-list');
//             table.DataTable().ajax.reload();
//             $("#callwrapup_form")[0].reset();
//             var scrollPos = $(".main-content").offset().top;
//             $(window).scrollTop(scrollPos);
//             setTimeout(function () { $("#callwrapform-success-alert").fadeOut('fast'); }, 5000);
//             goToNextStep("followup-step");
//             $(".remove-icons").trigger("click");
//             $(".additionalfeilds").remove();
//             var sPageURL = window.location.pathname;
//             parts = sPageURL.split("/");
//             var patientId = parts[parts.length - 1];
//             var preparationNotesFormPopulateURL = URL_POPULATE_RESEARCH_FOLLOWUP_PREPARATION_NOTES + "/" + patientId + "/current";
//             populateForm(patientId, preparationNotesFormPopulateURL);
//         }
//     }
// };


var onFollowUpForm = function (formObj, fields, response) {
    if (response.status == 200 && $.trim(response.data) == '') {
        util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
        util.totalTimeSpentByCM();
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">� </button><strong> Followup Data saved successfully!</strong></div>';
        $("#error-msg").html(txt);
        $("#error-msg").show();
        setTimeout(function () {
            $("#error-msg").hide();
        }, 5000);
        $("form[name='followup_form']")[0].reset();
        $("#drpdwn_task_notes").html('');
        $("#task_name").val(' ');
        $("#notes_0").html(' ');
        $("#notes_0").val(' ');
        $("#followupmaster_task").val('')
        $("#scheduled_0").prop('checked', true);
        $("#task_date_0").val('');
        $("#task_date_0").prop('readonly', false);
        $(".remove-icons").trigger("click");
        var table = $('#callwrap-list');
        table.DataTable().ajax.reload();
        var table1 = $('#task-list');
        table1.DataTable().ajax.reload();
        util.getToDoListData($("#patient_id").val(), $("form[name='followup_form'] input[name='module_id']").val());
       // util.getDataCalender($("#patient_id").val(), $("form[name='followup_form'] input[name='module_id']").val());
       ccmcpdcommonJS.getFollowupList($('#patient_id').val(), $("form[name='followup_form'] input[name='module_id']").val());
        var timer_paused = $("form[name='followup_form'] input[name='end_time']").val();
        $("#timer_start").val(timer_paused);
        $("#timer_end").val(timer_paused);

    } else if (response.status == 200 && $.trim(response.data) == 'blank form') {
        var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">� </button><strong>Please fill at least one fields!</strong></div>';
        $("#error-msg").html(txt);
        $("#error-msg").show();
        setTimeout(function () {
            $("#error-msg").hide();
        }, 5000);
    }
};


var onFollowUpFormEditData = function (formObj, fields, response) {
    if (response.status == 200) {
        util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
        util.totalTimeSpentByCM();
        $("#followup_task_edit_notes")[0].reset();
        $('#task_date').html('');
        $('#topic').val('');
        $('#task_date_val').val('');
        $('#task_notes').html('');
        $('#notes').html('');
        $('#category').html('');
        $("#edit_notes_modal").modal('hide');
        var scrollPos = $(".main-content").offset().top;
        var table = $('#callwrap-list');
        var table1 = $('#task-list');
        table.DataTable().ajax.reload();
        table1.DataTable().ajax.reload();
        util.getToDoListData($("#patient_id").val(), $("form[name='followup_form'] input[name='module_id']").val());
        //util.getDataCalender($("#patient_id").val(), $("form[name='followup_form'] input[name='module_id']").val());
        ccmcpdcommonJS.getFollowupList($('#patient_id').val(), $("form[name='followup_form'] input[name='module_id']").val());
        $(window).scrollTop(scrollPos);
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">� </button><strong> Followup Data saved successfully!</strong></div>';
        $("#error-msg").html(txt);
        $("#error-msg").show();
        setTimeout(function () {
            $("#error-msg").hide();
        }, 5000);
        var timer_paused = $("form[name='followup_task_edit_notes'] input[name='end_time']").val();
        $("#timer_start").val(timer_paused);
        $("#timer_end").val(timer_paused);
    }
};

var onText = function (formObj, fields, response) {
    if (response.status == 200) {
        var str = response.data;
        if ((str.includes('HTTP')) || response.status == 406) {
            var errormsg = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">�</button><strong>' + response.data + '</strong></div>';
            $("form[name='text_form'] .twilo-error").html(errormsg);
            $(window).scrollTop(scrollPos);
        }
        var year = (new Date).getFullYear();
        var month = (new Date).getMonth() + 1;
        util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
        $("form[name='text_form'] .alert").show();
        var scrollPos = $(".main-content").offset().top;
        util.getPatientPreviousMonthNotes($("input[name='patient_id']").val(), $("input[name='module_id']").val(), month, year);
        var table = $('#callwrap-list');
        table.DataTable().ajax.reload();
        util.getToDoListData($("input[name='patient_id']").val(), $("input[name='module_id']").val());
        //util.getDataCalender($("input[name='patient_id']").val(), $("input[name='module_id']").val());
        var timer_paused = $("form[name='text_form'] input[name='end_time']").val();
        $("#timer_start").val(timer_paused);
        $("#timer_end").val(timer_paused);
        setTimeout(function () {
            $(".alert").hide();
            $("#error-msg").hide();
            goToNextStep("call_step_1_id");
            goToNextStep("ccm-call-wrapup-icon-tab");
        }, 5000);
    }
};

// var onCallStatus = function (formObj, fields, response) { //moved to common js
//     if (response.status == 200) {
//         util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
//         util.getToDoListData($("#patient_id").val(), $("form[name='callstatus_form'] input[name='module_id']").val());
//        // util.getDataCalender($("#patient_id").val(), $("form[name='callstatus_form'] input[name='module_id']").val());
//         var year = (new Date).getFullYear();
//         var month = (new Date).getMonth() + 1
//         util.getPatientPreviousMonthNotes($("#patient_id").val(), $("form[name='callstatus_form'] input[name='module_id']").val(), month, year);
//         $("form[name='callstatus_form'] #success-alert").show();
//         var scrollPos = $(".main-content").offset().top;
//         $(window).scrollTop(scrollPos);

//         var d = new Date();
//         if (d.getMonth() + 1 < 10) {
//             var getmonth = "0" + (d.getMonth() + 1);
//         } else {
//             var getmonth = (d.getMonth() + 1);
//         }
//         if (d.getDate() < 10) {
//             var getday = "0" + d.getDate();
//         } else {
//             var getday = d.getDate();
//         }
//         var strTime = d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
//         var myDate = getmonth + "-" + getday + "-" + d.getFullYear();
//         var strDate = myDate + " " + strTime;
//         if ($("form[name='callstatus_form'] input[name$='call_status']:checked").val() == 2 && $("#answer").val() == 3) {
//             var call_followup = $("#call_followup_date").val();
//             if (call_followup != '') {
//                 var datePart = call_followup.match(/\d+/g),
//                     year = datePart[0], // get only two digits
//                     month = datePart[1],
//                     day = datePart[2];
//                 var call_followup = month + '-' + day + '-' + year;
//             }

//             $("#history_list ul").prepend('<li><h5>Call Not Answered (' + strDate + ')</h5>'
//                 + '<table> <tr><th>Call Follow-up date</th></tr>'
//                 + '<tr>'
//                 + '<td>' + call_followup + '</td>'
//                 + '</tr>'
//                 + '</table>'
//                 + '<b> SMS Send:</b>' + $("#ccm_content_area").val() + '</li>');
//             var twiloError = $.trim(response.data);
//             if (twiloError === "" || twiloError === "null" || twiloError === null) {
//                 var errormsg = '';
//                 $("form[name='callstatus_form'] .twilo-error").html(errormsg);
//             } else {
//                 var errormsg = '<div class="alert alert-danger" id="danger-alert" style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">� </button><strong>' + response.data + '</strong></div>';
//                 $("form[name='callstatus_form'] .twilo-error").html(errormsg);
//                 setTimeout(function () {
//                     $("form[name='callstatus_form'] .twilo-error").html('');
//                 }, 5000);
//             }
//             $("form[name='callstatus_form'] #ccm_content_title option:last").prop("selected", "selected").change();
//             if (sub_module == 'care-plan-development') {
//                 goToNextStep("patient-datap-tab");
//             } else {
//                 goToNextStep("start-tab");
//                 getFollowupList($('#patient_id').val(), $("form[name='callstatus_form'] input[name='module_id']").val());
//             }
//         } else if ($("form[name='callstatus_form'] input[name$='call_status']:checked").val() == 2 && $("#answer").val() == 2) {
//             var call_followup = $("#call_followup_date").val();
//             if (call_followup != '') {
//                 var datePart = call_followup.match(/\d+/g),
//                     year = datePart[0], // get only two digits
//                     month = datePart[1],
//                     day = datePart[2];
//                 var call_followup = month + '-' + day + '-' + year;
//             }
//             $("#history_list ul").prepend('<li><h5>Call Not Answered (' + strDate + ')</h5>'
//                 + '<table> <tr><th>Call Follow-up date</th></tr>'
//                 + '<tr>'
//                 + '<td>' + call_followup + '</td>'
//                 + '</tr>'
//                 + '</table>'
//                 + '<b> No Voice Mail</b>' + '</li>');
//             $("form[name='callstatus_form'] #ccm_content_title option:last").prop("selected", "selected").change();

//             if (sub_module == 'care-plan-development') {
//                 goToNextStep("patient-datap-tab");
//             } else {
//                 goToNextStep("start-tab");
//                 getFollowupList($('#patient_id').val(), $("form[name='callstatus_form'] input[name='module_id']").val());
//             }
//         } else if ($("form[name='callstatus_form'] input[name$='call_status']:checked").val() == 2 && $("#answer").val() == 1) {
//             var call_followup = $("#call_followup_date").val();
//             if (call_followup != '') {
//                 var datePart = call_followup.match(/\d+/g),
//                     year = datePart[0], // get only two digits
//                     month = datePart[1],
//                     day = datePart[2];
//                 var call_followup = month + '-' + day + '-' + year;
//             }
//             $("#history_list ul").prepend('<li><h5>Call Not Answered (' + strDate + ')</h5>'
//                 + '<table> <tr><th>Call Follow-up date</th></tr>'
//                 + '<tr>'
//                 + '<td>' + call_followup + '</td>'
//                 + '</tr>'
//                 + '</table>'
//                 + '<b>Left Voice Mail : </b>' + $("#voice_mail_template").val() + '</li>');
//             $("form[name='callstatus_form'] #ccm_content_title option:last").prop("selected", "selected").change();

//             if (sub_module == 'care-plan-development') {
//                 goToNextStep("patient-datap-tab");
//             } else {
//                 goToNextStep("start-tab");
//                 getFollowupList($('#patient_id').val(), $("form[name='callstatus_form'] input[name='module_id']").val());
//             }
//         } else if ($("form[name='callstatus_form'] input[name$='call_status']:checked").val() == 1 && $("form[name='callstatus_form'] input[name$='call_continue_status']:checked").val() == 0) {
//             $("form[name='callstatus_form'] #ccm_content_title option:last").prop("selected", "selected").change();
//             var answer_followup_d = $("#answer_followup_date").val();
//             if (answer_followup_d != '') {
//                 var datePart = answer_followup_d.match(/\d+/g),
//                     year = datePart[0], // get only two digits
//                     month = datePart[1],
//                     day = datePart[2];
//                 var answer_followup_d = month + '-' + day + '-' + year;
//             }
//             var answer_followup_time = $("#answer_followup_time").val();
//             var inputEle = document.getElementById('answer_followup_time');

//             if (answer_followup_time != '') {
//                 var timeSplit = inputEle.value.split(':'),
//                     hours,
//                     minutes,
//                     meridian;
//                 hours = timeSplit[0];
//                 minutes = timeSplit[1];
//                 if (hours > 12) {
//                     meridian = 'PM';
//                     hours -= 12;
//                 } else if (hours < 12) {
//                     meridian = 'AM';
//                     if (hours == 0) {
//                         hours = 12;
//                     }
//                 } else {
//                     meridian = 'PM';
//                 }
//                 var inputEle = hours + ':' + minutes + ' ' + meridian;
//             } else {
//                 var inputEle = '';
//             }
//             $("#history_list ul").prepend('<li><h5>Call Answered (' + strDate + ') </h5>'
//                 + '<table> <tr><th>Call Continue Status</th><th>Call Follow-up date</th><th>Call Follow-up Time</th></tr>'
//                 + '<tr>'
//                 + '<td>No</td>'
//                 + '<td>' + answer_followup_d + '</td>'
//                 + '<td>' + inputEle + '</td>'
//                 + '</tr>'
//                 + '</table>'
//                 + '<b> Call Script:</b>' + $("#call_answer_template").val() + '</li>');
//             if (sub_module == 'care-plan-development') {
//                 goToNextStep("patient-datap-tab");
//             } else {
//                 goToNextStep("start-tab");
//                 getFollowupList($('#patient_id').val(), $("form[name='callstatus_form'] input[name='module_id']").val());
//             }
//         } else {
//             $("#history_list ul").prepend('<li><h5>Call Answered (' + strDate + ') </h5>'
//                 + '<table> <tr><th>Call Continue Status</th></tr>'
//                 + '<tr>'
//                 + '<td>Yes</td>'
//                 + '</tr>'
//                 + '</table>'
//                 + '<b> Call Script:</b>' + $("#call_answer_template").val() + '</li>');
//             if (sub_module == 'care-plan-development') {
//                 goToNextStep("verification-tab");
//             } else {
//                 goToNextStep("ccm-verification-icon-tab");
//                 getFollowupList($('#patient_id').val(), $("form[name='callstatus_form'] input[name='module_id']").val());
//             }
//         }
//         setTimeout(function () {
//             $("form[name='callstatus_form'] #success-alert").fadeOut();
//         }, 5000);
//         var timer_paused = $("form[name='callstatus_form'] input[name='end_time']").val();
//         $("#timer_start").val(timer_paused);
//         $("#timer_end").val(timer_paused);
//         var table = $('#callwrap-list');
//         table.DataTable().ajax.reload();
//     }
// };

var onSubmit = function (name) {
    $("form[name=" + name + "] input[name='timer']").val("12");
    return true;
};

var goToNextStep = function (id) {
    setTimeout(function () {
        $('#' + id).click();
    }, 5000);
}

$('#call_step_1_id').on('click', function () {
    $('#call-icon-tab').click();
});

$('#myIconTab li a').on('click', function () {
    var sPageURL = window.location.pathname;
    parts = sPageURL.split("/"),
        id = parts[parts.length - 1];
    var patientId = id;
    if ($(this).is("#ccm-call-close-icon-tab")) {
        if ($("#tempdiv").html().trim().length > 0) {
            var p = $("#tempdiv").html();
            $("#ignore").html(p);
            $("#tempdiv").html('');

        } else if ($("#parentdiv").html().trim().length > 0) {
            var p = $("#parentdiv").html();
            $("#ignore").html(p);
            $("#parentdiv").html('');
        }

        var preparationNotesFormPopulateURL = URL_POPULATE_PREPARATION_NOTES + "/" + patientId + "/current";
        populateForm(patientId, preparationNotesFormPopulateURL);
    } else if ($(this).is("#call-icon-tab")) {
        if ($("#ignore").html().trim().length > 0) {
            var p = $("#ignore").html();
            $("#tempdiv").html(p);
            $("#ignore").html('');
        } else if ($("#parentdiv").html().trim().length > 0) {
            var p = $("#parentdiv").html();
            $("#tempdiv").html(p);
            $("#parentdiv").html('');
        }
        var preparationNotesFormPopulateURL = URL_POPULATE_PREPARATION_NOTES + "/" + patientId + "/current";
        populateForm(patientId, preparationNotesFormPopulateURL);
    }
});

// var callMonthllyMonitoringInitFunctions = function () { //function  moved to common js
//     var allergy_type = $('form[name="allergy_drug_form"] input[name="allergy_type"]').val();
//     var id = $("#patient_id").val();
//     util.refreshAllergyCountCheckbox(id, allergy_type, 'allergy_drug_form');
//     carePlanDevelopment.renderDiagnosisTableData();
//     carePlanDevelopment.renderDMEServicesTableData();
//     carePlanDevelopment.renderHomeHealthServicesTableData();
//     carePlanDevelopment.renderTherapyServicesTableData();
//     carePlanDevelopment.renderSocialServicesTableData();
//     carePlanDevelopment.renderMedicalSuppliesServicesTableData();
//     carePlanDevelopment.renderOtherHealthServicesTableData();
//     carePlanDevelopment.renderDialysiServicesTableData();
//     carePlanDevelopment.renderdrugTableData();
//     carePlanDevelopment.renderFoodTableData();
//     carePlanDevelopment.renderEnviromentalTableData();
//     carePlanDevelopment.renderinsectTableData();
//     carePlanDevelopment.renderLatexTableData();
//     carePlanDevelopment.renderPetRelatedTableData();
//     carePlanDevelopment.renderAllergyOtherTableData();
//     carePlanDevelopment.renderLabsTable();
//     carePlanDevelopment.renderVitalTable();
//     carePlanDevelopment.renderMedicationsTableData();

//     var year = (new Date).getFullYear();
//     var month = (new Date).getMonth() + 1; //add +1 for current mnth
//     var patient_id = $("#hidden_id").val();
//     var module_id = $("input[name='module_id']").val();
//     var prepstage_id = $("#call_preparation_preparation_followup_form input[name='stage_id']").val();
//     util.totalTimeSpent(patient_id, module_id, prepstage_id);
//     util.getPatientRelationshipBuilding($("#patient_id").val());
//     util.getPatientDetails(patient_id, module_id);
//     util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
//     util.getPatientCareplanNotes(patient_id, module_id);
//     util.getPatientStatus(patient_id, module_id);
//     util.gatCaretoolData(patient_id, module_id);
//     util.getToDoListData($("#patient_id").val(), module_id);
//     //util.getDataCalender($("#patient_id").val(), module_id);
//     getFollowupList($('#patient_id').val(), module_id);
//     // Research followup data presentEMR
//     var patientId = $("#patient_id").val();
//     var checked_value1 = $("form[name='call_preparation_preparation_followup_form'] input[name$='condition_requirnment1']").prop('checked');
//     var checked_value2 = $("form[name='call_preparation_preparation_followup_form'] input[name$='condition_requirnment2']").prop('checked');
//     var checked_value3 = $("form[name='call_preparation_preparation_followup_form'] input[name$='condition_requirnment3']").prop('checked');
//     var checked_value4 = $("form[name='call_preparation_preparation_followup_form'] input[name$='condition_requirnment4']").prop('checked');
//     if (checked_value1 == true || checked_value2 == true || checked_value3 == true || checked_value4 == true) {
//         $("form[name='research_follow_up_preparation_followup_form'] #research_follow_up_data_present_in_emr_yes").prop("checked", true);
//         var researchFollowupPreparationNotesFormPopulateURL = URL_POPULATE_RESEARCH_FOLLOWUP_PREPARATION_NOTES + "/" + patientId + "/current";
//         populateForm(patientId, researchFollowupPreparationNotesFormPopulateURL);
//     } else if (checked_value1 == false || checked_value2 == false || checked_value3 == false || checked_value4 == false) {
//         $("form[name='research_follow_up_preparation_followup_form'] #research_follow_up_data_present_in_emr_no").prop("checked", true);
//         var researchFollowupPreparationNotesFormPopulateURL = URL_POPULATE_RESEARCH_FOLLOWUP_PREPARATION_NOTES + "/" + patientId + "/current";
//         populateForm(patientId, researchFollowupPreparationNotesFormPopulateURL);
//     } else {
//         $("form[name='research_follow_up_preparation_followup_form'] #research_follow_up_data_present_in_emr_no").prop("checked", true);
//         var preparationNotesFormPopulateURL = URL_POPULATE_PREPARATION_NOTES + "/" + patientId + "/current";
//         populateForm(patientId, preparationNotesFormPopulateURL);
//         $('.invalid-feedback').html('');
//         $("form[name='research_follow_up_preparation_followup_form']")[0].reset();
//         $("form[name='research_follow_up_preparation_followup_form'] #data_present_in_emr_show").show();
//     }

//     util.getCallScriptsById($("form[name='text_form'] #text_template_id").val(), '#templatearea_sms', "form[name='text_form'] input[name='template_type_id']", "form[name='text_form'] input[name='content_title']");

//     var sPageURL = window.location.pathname;
//     parts = sPageURL.split("/"),
//         id = parts[parts.length - 1];
//     var patientId = id;
//     var data = "";
//     var preparationNotesFormPopulateURL = URL_POPULATE_PREPARATION_NOTES + "/" + patientId + "/current";
//     populateForm(patientId, preparationNotesFormPopulateURL);
// }

var callWrapUpShowHide = function() {
    
    var newcheckbox_1 = $('form[name="callwrapup_form"] #routine_response').prop("checked");
    var newcheckbox_2 = $('form[name="callwrapup_form"] #urgent_emergent_response').prop("checked");
    var newcheckbox_3 = $('form[name="callwrapup_form"] #referral_order_support').prop("checked");
    var newcheckbox_4 = $('form[name="callwrapup_form"] #medication_support').prop("checked");
    var newcheckbox_5 = $('form[name="callwrapup_form"] #verbal_education_review_with_patient').prop("checked");
    var newcheckbox_6 = $('form[name="callwrapup_form"] #mailed_documents').prop("checked");
    var newcheckbox_7 = $('form[name="callwrapup_form"] #resource_support').prop("checked");
    var newcheckbox_8 = $('form[name="callwrapup_form"] #veterans_services').prop("checked");
    var newcheckbox_9 = $('form[name="callwrapup_form"] #authorized_cm_only').prop("checked");
    var newcheckbox_10 = $('form[name="callwrapup_form"] #no_additional_services_provided').prop("checked");
  
   
    if(newcheckbox_1 == true){
        $('form[name="callwrapup_form"] #routinediv').show();  
    }else{
        $('form[name="callwrapup_form"] #routinediv').hide();
    }

    if(newcheckbox_2 == true){
        $('form[name="callwrapup_form"] #emergentdiv').show();
    }else{
        $('form[name="callwrapup_form"] #emergentdiv').hide();
    }

    if(newcheckbox_3 == true){
        $('form[name="callwrapup_form"] #referraldiv').show();
    }else{
        $('form[name="callwrapup_form"] #referraldiv').hide();
    }
    
    if(newcheckbox_4 == true){
      
        $('form[name="callwrapup_form"] #medicationdiv').show();
    }else{
        $('form[name="callwrapup_form"] #medicationdiv').hide();
    }
    
    if(newcheckbox_5 == true){
        $('form[name="callwrapup_form"] #verbaldiv').show();
    }else{
        $('form[name="callwrapup_form"] #verbaldiv').hide();
    }

    if(newcheckbox_6 == true){
    $('form[name="callwrapup_form"] #maileddiv').show();
    }else{
        $('form[name="callwrapup_form"] #maileddiv').hide();
    }
    
    if(newcheckbox_7 == true){
        $('form[name="callwrapup_form"] #resourcediv').show();
    }else{
        $('form[name="callwrapup_form"] #resourcediv').hide();
    }
    
    if(newcheckbox_8 == true){
        $('form[name="callwrapup_form"] #veteransdiv').show();  
    }else{ 
        $('form[name="callwrapup_form"] #veteransdiv').hide();
    }

    if(newcheckbox_9 == true){
        $('form[name="callwrapup_form"] #authorizeddiv').show();  
    }else{ 
        $('form[name="callwrapup_form"] #authorizeddiv').hide();
    }

    if(newcheckbox_10 == true) {
            
        // alert('newcheckbox10  checked');
        
        $("form[name='callwrapup_form'] #authorizeddiv").hide();  
        $("form[name='callwrapup_form'] #veteransdiv").hide();   
        $("form[name='callwrapup_form'] #resourcediv").hide();   
        $("form[name='callwrapup_form'] #maileddiv").hide();
        $("form[name='callwrapup_form'] #verbaldiv").hide();   
        $("form[name='callwrapup_form'] #medicationdiv").hide(); 
        $("form[name='callwrapup_form'] #referraldiv").hide();
        $("form[name='callwrapup_form'] #emergentdiv").hide();
        $("form[name='callwrapup_form'] #routinediv").hide();
        $('form[name="callwrapup_form"] #authorizeddiv').hide(); 
        
        $("form[name='callwrapup_form'] #routine_response").prop("checked", false);
        $("form[name='callwrapup_form'] #urgent_emergent_response").prop("checked", false);
        $("form[name='callwrapup_form'] #referral_order_support").prop("checked", false);
        $("form[name='callwrapup_form'] #medication_support").prop("checked", false);
        $("form[name='callwrapup_form'] #verbal_education_review_with_patient").prop("checked", false);
        $("form[name='callwrapup_form'] #mailed_documents").prop("checked", false);
        $("form[name='callwrapup_form'] #resource_support").prop("checked", false);
        $("form[name='callwrapup_form'] #veterans_services").prop("checked", false);
        $("form[name='callwrapup_form'] #authorized_cm_only").prop("checked", false);

    }else{
        // alert('newcheckbox10  not checked');

        // $("form[name='callwrapup_form'] #authorizeddiv").show();  
        // $("form[name='callwrapup_form'] #veteransdiv").show();   
        // $("form[name='callwrapup_form'] #resourcediv").show();   
        // $("form[name='callwrapup_form'] #maileddiv").show();
        // $("form[name='callwrapup_form'] #verbaldiv").show();   
        // $("form[name='callwrapup_form'] #medicationdiv").show(); 
        // $("form[name='callwrapup_form'] #referraldiv").show();
        // $("form[name='callwrapup_form'] #emergentdiv").show();
        // $("form[name='callwrapup_form'] #routinediv").show();

        // $("form[name='callwrapup_form'] #routine_response").prop("checked", false);
        // $("form[name='callwrapup_form'] #urgent_emergent_response").prop("checked", false);
        // $("form[name='callwrapup_form'] #referral_order_support").prop("checked", false);
        // $("form[name='callwrapup_form'] #medication_support").prop("checked", false);
        // $("form[name='callwrapup_form'] #verbal_education_review_with_patient").prop("checked", false);
        // $("form[name='callwrapup_form'] #mailed_documents").prop("checked", false);
        // $("form[name='callwrapup_form'] #resource_support").prop("checked", false);
        // $("form[name='callwrapup_form'] #veterans_services").prop("checked", false);

    }

}


var init = function () {
    var year = (new Date).getFullYear();
    var month = (new Date).getMonth() + 1; //add +1 for current mnth
    var patient_id = $("#hidden_id").val();
    var module_id = $("input[name='module_id']").val();
	 util.getPatientDetails(patient_id, module_id);
    // util.redirectToWorklistPage();
    ccmcpdcommonJS.copyPreviousMonthDataToThisMonth($("#hidden_id").val(), $("#page_module_id").val());
    util.getDistinctDiagnosisCountForBubble(patient_id);
	callWrapUpShowHide();


// starts here
var allergy_type = $('form[name="allergy_drug_form"] input[name="allergy_type"]').val();
    var id = $("#patient_id").val();
    util.refreshAllergyCountCheckbox(id, allergy_type, 'allergy_drug_form');
    carePlanDevelopment.renderDiagnosisTableData();
    carePlanDevelopment.renderDMEServicesTableData();
    carePlanDevelopment.renderHomeHealthServicesTableData();
    carePlanDevelopment.renderTherapyServicesTableData();
    carePlanDevelopment.renderSocialServicesTableData();
    carePlanDevelopment.renderMedicalSuppliesServicesTableData();
    carePlanDevelopment.renderOtherHealthServicesTableData();
    carePlanDevelopment.renderDialysiServicesTableData();
    carePlanDevelopment.renderdrugTableData();
    carePlanDevelopment.renderFoodTableData();
    carePlanDevelopment.renderEnviromentalTableData();
    carePlanDevelopment.renderinsectTableData();
    carePlanDevelopment.renderLatexTableData();
    carePlanDevelopment.renderPetRelatedTableData();
    carePlanDevelopment.renderAllergyOtherTableData();
    carePlanDevelopment.renderLabsTable();
    carePlanDevelopment.renderVitalTable();
    carePlanDevelopment.renderMedicationsTableData();

   
    var prepstage_id = $("#call_preparation_preparation_followup_form input[name='stage_id']").val();
    util.totalTimeSpent(patient_id, module_id, prepstage_id);
    util.getPatientRelationshipBuilding($("#patient_id").val());
   
    util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
    util.getPatientCareplanNotes(patient_id, module_id);
    util.getPatientStatus(patient_id, module_id);
    util.gatCaretoolData(patient_id, module_id);
    util.getToDoListData($("#patient_id").val(), module_id);
    // util.getToDoListCalendarData($("#patient_id").val(), module_id);
    //util.getDataCalender($("#patient_id").val(), module_id);
    ccmcpdcommonJS.getFollowupList($('#patient_id').val(), module_id);
    // Research followup data presentEMR
    var patientId = $("#patient_id").val();
    var checked_value1 = $("form[name='call_preparation_preparation_followup_form'] input[name$='condition_requirnment1']").prop('checked');
    var checked_value2 = $("form[name='call_preparation_preparation_followup_form'] input[name$='condition_requirnment2']").prop('checked');
    var checked_value3 = $("form[name='call_preparation_preparation_followup_form'] input[name$='condition_requirnment3']").prop('checked');
    var checked_value4 = $("form[name='call_preparation_preparation_followup_form'] input[name$='condition_requirnment4']").prop('checked');
    if (checked_value1 == true || checked_value2 == true || checked_value3 == true || checked_value4 == true) {
        $("form[name='research_follow_up_preparation_followup_form'] #research_follow_up_data_present_in_emr_yes").prop("checked", true);
        var researchFollowupPreparationNotesFormPopulateURL = URL_POPULATE_RESEARCH_FOLLOWUP_PREPARATION_NOTES + "/" + patientId + "/current";
        populateForm(patientId, researchFollowupPreparationNotesFormPopulateURL);
    } else if (checked_value1 == false || checked_value2 == false || checked_value3 == false || checked_value4 == false) {
        $("form[name='research_follow_up_preparation_followup_form'] #research_follow_up_data_present_in_emr_no").prop("checked", true);
        var researchFollowupPreparationNotesFormPopulateURL = URL_POPULATE_RESEARCH_FOLLOWUP_PREPARATION_NOTES + "/" + patientId + "/current";
        populateForm(patientId, researchFollowupPreparationNotesFormPopulateURL);
    } else {
        $("form[name='research_follow_up_preparation_followup_form'] #research_follow_up_data_present_in_emr_no").prop("checked", true);
        var preparationNotesFormPopulateURL = URL_POPULATE_PREPARATION_NOTES + "/" + patientId + "/current";
        populateForm(patientId, preparationNotesFormPopulateURL);
        $('.invalid-feedback').html('');
        $("form[name='research_follow_up_preparation_followup_form']")[0].reset();
        $("form[name='research_follow_up_preparation_followup_form'] #data_present_in_emr_show").show();
    }

    util.getCallScriptsById($("form[name='text_form'] #text_template_id").val(), '#templatearea_sms', "form[name='text_form'] input[name='template_type_id']", "form[name='text_form'] input[name='content_title']");

    var sPageURL = window.location.pathname;
    parts = sPageURL.split("/"),
        id = parts[parts.length - 1];
    var patientId = id;
    var data = "";
    var preparationNotesFormPopulateURL = URL_POPULATE_PREPARATION_NOTES + "/" + patientId + "/current";
    ccmMonthlyMonitoring.populateForm(patientId, preparationNotesFormPopulateURL);



// ends here
	
	$("form[name='callwrapup_form'] #routine_response").change(function() {  
    if($("form[name='callwrapup_form'] #routine_response").is(":checked")) {
        $("form[name='callwrapup_form'] #routinediv").show();   
    }else{
        $("form[name='callwrapup_form'] #routinediv").hide();
        
    }
});
   

$("form[name='callwrapup_form'] #urgent_emergent_response").change(function() {  
    if($("form[name='callwrapup_form'] #urgent_emergent_response").is(":checked")) {
        $("form[name='callwrapup_form'] #emergentdiv").show();   
    }else{
        $("form[name='callwrapup_form'] #emergentdiv").hide();
        
    }
});

$("form[name='callwrapup_form'] #referral_order_support").change(function() {  
    if($("form[name='callwrapup_form'] #referral_order_support").is(":checked")) {
        $("form[name='callwrapup_form'] #referraldiv").show();   
    }else{
        $("form[name='callwrapup_form'] #referraldiv").hide();
        
    }
});

$("form[name='callwrapup_form'] #medication_support").change(function() {  
    if($("form[name='callwrapup_form'] #medication_support").is(":checked")) {
        $("form[name='callwrapup_form'] #medicationdiv").show();   
    }else{
        $("form[name='callwrapup_form'] #medicationdiv").hide();
        
    }
});

$("form[name='callwrapup_form'] #verbal_education_review_with_patient").change(function() {  
    if($("form[name='callwrapup_form'] #verbal_education_review_with_patient").is(":checked")) {
        $("form[name='callwrapup_form'] #verbaldiv").show();   
    }else{
        $("form[name='callwrapup_form'] #verbaldiv").hide();
        
    }
});

$("form[name='callwrapup_form'] #verbal_education_review_with_patient").change(function() {  
    if($("form[name='callwrapup_form'] #verbal_education_review_with_patient").is(":checked")) {
        $("form[name='callwrapup_form'] #verbaldiv").show();   
    }else{
        $("form[name='callwrapup_form'] #verbaldiv").hide();
        
    }
});

$("form[name='callwrapup_form'] #mailed_documents").change(function() {  
    if($("form[name='callwrapup_form'] #mailed_documents").is(":checked")) {
        $("form[name='callwrapup_form'] #maileddiv").show();   
    }else{
        $("form[name='callwrapup_form'] #maileddiv").hide();
        
    }
});

$("form[name='callwrapup_form'] #resource_support").change(function() {  
    if($("form[name='callwrapup_form'] #resource_support").is(":checked")) {
        $("form[name='callwrapup_form'] #resourcediv").show();   
    }else{
        $("form[name='callwrapup_form'] #resourcediv").hide();
        
    }
});

$("form[name='callwrapup_form'] #veterans_services").change(function() {  
    if($("form[name='callwrapup_form'] #veterans_services").is(":checked")) {
        $("form[name='callwrapup_form'] #veteransdiv").show();   
    }else{
        $("form[name='callwrapup_form'] #veteransdiv").hide(); 
    }
});

$("form[name='callwrapup_form'] #authorized_cm_only").change(function() {  
    if($("form[name='callwrapup_form'] #authorized_cm_only").is(":checked")) {
        $("form[name='callwrapup_form'] #authorizeddiv").show();   
    }else{
        $("form[name='callwrapup_form'] #authorizeddiv").hide(); 
    }
});

    $("form[name='callwrapup_form'] #no_additional_services_provided").change(function() {  
        if($("form[name='callwrapup_form'] #no_additional_services_provided").is(":checked")) {
            
            // alert('no additional checked');
            
            $("form[name='callwrapup_form'] #authorizeddiv").hide();  
            $("form[name='callwrapup_form'] #veteransdiv").hide();   
            $("form[name='callwrapup_form'] #resourcediv").hide();   
            $("form[name='callwrapup_form'] #maileddiv").hide();
            $("form[name='callwrapup_form'] #verbaldiv").hide();   
            $("form[name='callwrapup_form'] #medicationdiv").hide(); 
            $("form[name='callwrapup_form'] #referraldiv").hide();
            $("form[name='callwrapup_form'] #emergentdiv").hide();
            $("form[name='callwrapup_form'] #routinediv").hide();
            $("form[name='callwrapup_form'] #authorizeddiv").hide();
            
            $("form[name='callwrapup_form'] #routine_response").prop("checked", false);
            $("form[name='callwrapup_form'] #urgent_emergent_response").prop("checked", false);
            $("form[name='callwrapup_form'] #referral_order_support").prop("checked", false);
            $("form[name='callwrapup_form'] #medication_support").prop("checked", false);
            $("form[name='callwrapup_form'] #verbal_education_review_with_patient").prop("checked", false);
            $("form[name='callwrapup_form'] #mailed_documents").prop("checked", false);
            $("form[name='callwrapup_form'] #resource_support").prop("checked", false);
            $("form[name='callwrapup_form'] #veterans_services").prop("checked", false);
            $("form[name='callwrapup_form'] #authorized_cm_only").prop("checked", false);





        }else{
            // alert('authorized not checked');

            // $("form[name='callwrapup_form'] #authorizeddiv").show();  
            // $("form[name='callwrapup_form'] #veteransdiv").show();   
            // $("form[name='callwrapup_form'] #resourcediv").show();   
            // $("form[name='callwrapup_form'] #maileddiv").show();
            // $("form[name='callwrapup_form'] #verbaldiv").show();   
            // $("form[name='callwrapup_form'] #medicationdiv").show(); 
            // $("form[name='callwrapup_form'] #referraldiv").show();
            // $("form[name='callwrapup_form'] #emergentdiv").show();
            // $("form[name='callwrapup_form'] #routinediv").show();

            // $("form[name='callwrapup_form'] #routine_response").prop("checked", false);
            // $("form[name='callwrapup_form'] #urgent_emergent_response").prop("checked", false);
            // $("form[name='callwrapup_form'] #referral_order_support").prop("checked", false);
            // $("form[name='callwrapup_form'] #medication_support").prop("checked", false);
            // $("form[name='callwrapup_form'] #verbal_education_review_with_patient").prop("checked", false);
            // $("form[name='callwrapup_form'] #mailed_documents").prop("checked", false);
            // $("form[name='callwrapup_form'] #resource_support").prop("checked", false);
            // $("form[name='callwrapup_form'] #veterans_services").prop("checked", false);


            
    

            
        }
    });







    $("form[name='callstatus_form'] #ccm_content_title option:last").attr("selected", "selected").change();
    $("form[name='callstatus_form'] #call_scripts_select option:last").attr("selected", "selected").change();
    $("form[name='callstatus_form'] #voice_scripts_select option:last").attr("selected", "selected").change();
    var table = $('#callwrap-list');
    table.DataTable().ajax.reload();
    $("#health_issue_notes_div").hide();
    $("#next_month_call_div").hide();
    $("#home_service_yes_div").hide();
    $("form[name='callstatus_form'] #notAnswer").hide();
    $("form[name='callstatus_form'] #callAnswer").hide();

    var form_name = $("form[name='call_preparation_preparation_followup_form'] input[name='call_preparation']").val();
    if (form_name == 'call_preparation') {
        $("#relation_building").show();
    } else {
        $("#relation_building").hide();
    }

    //RPM- callwrap dropdwon
    $("#rpm-report").change(function () {
        if ($(this).val() == "1") {
            goToNextStep('review_data_1_id');
        }
        if ($(this).val() == "2") {
            var baseURL = window.location.origin + '/';
            var patient_id = $("#hidden_id").val();
            var d = new Date(), n = d.getMonth();
            window.open(baseURL + "reports/device-data-report/" + patient_id, '_blank');
        }
        else if ($(this).val() == "3") {
            var baseURL = window.location.origin + '/';
            var patient_id = $("#hidden_id").val();
            var d = new Date(), n = d.getMonth();
            window.open(baseURL + "rpm/alert-history/" + patient_id + "/" + (d.getMonth() + 1), '_blank');
        }
    });
    $("#call_preparation-code-diagnosis-modal, #research_follow_up-code-diagnosis-modal").click(function () {
        $(".modal-title").html('Create / Modify Care Plan');
        var formName = $(this).closest('form').attr('name');
        var ccm_stage = $('form[name=' + formName + '] input[name="stage_id"]').val();
        $("form[name='care_plan_form'] input[name='stage_id']").val(ccm_stage);
        $("form[name='care_plan_form'] input[name='step_id']").val('');
        $("form[name='care_plan_form']")[0].reset();
        $('#append_symptoms').html("");
        $('#append_goals').html("");
        $('#append_tasks').html("");
        $("#medications").hide();
        $("#vitalsHealth").hide();
        $("#allergy-information").hide();
        $("#healthcare-services").hide();
        $("#diagnosis-codes").show();
        carePlanDevelopment.renderDiagnosisTableData();
        if ($("#cpd_finalize").val() != 1) {
            alert("CPD Not Finalized");
        }
    });

    $("#call_preparation-medications-modal, #research_follow_up-medications-modal").click(function () {
        $(".modal-title").html('Medication');
        var formName = $(this).closest('form').attr('name');
        var ccm_stage = $('form[name=' + formName + '] input[name="stage_id"]').val();
        $("form[name='medications_form'] input[name='stage_id']").val(ccm_stage);
        $("form[name='medications_form'] input[name='step_id']").val('');
        $("form[name='medications_form']")[0].reset();
        $("#diagnosis-codes").hide();
        $("#vitalsHealth").hide();
        $("#allergy-information").hide();
        $("#healthcare-services").hide();
        $("#medications").show();
    });

    $("#call_preparation-allergies-model, #research_follow_up-allergies-model").click(function () {
        $(".modal-title").html('Allergies');
        var formName = $(this).closest('form').attr('name');
        var ccm_stage = $('form[name=' + formName + '] input[name="stage_id"]').val()
        $("form[name='allergy_food_form'] input[name='step_id']").val('');
        $("form[name='allergy_food_form'] input[name='stage_id']").val(ccm_stage);
        $("form[name='allergy_drug_form'] input[name='step_id']").val('');
        $("form[name='allergy_drug_form'] input[name='stage_id']").val(ccm_stage);
        $("form[name='allergy_enviromental_form'] input[name='step_id']").val('');
        $("form[name='allergy_enviromental_form'] input[name='stage_id']").val(ccm_stage);
        $("form[name='allergy_insect_form'] input[name='step_id']").val('');
        $("form[name='allergy_insect_form'] input[name='stage_id']").val(ccm_stage);
        $("form[name='allergy_latex_form'] input[name='step_id']").val('');
        $("form[name='allergy_latex_form'] input[name='stage_id']").val(ccm_stage);
        $("form[name='allergy_pet_related_form'] input[name='step_id']").val('');
        $("form[name='allergy_pet_related_form'] input[name='stage_id']").val(ccm_stage);
        $("form[name='allergy_other_allergy_form'] input[name='step_id']").val('');
        $("form[name='allergy_other_allergy_form'] input[name='stage_id']").val(ccm_stage);

        $("#allergy_food_form")[0].reset();
        $("#allergy_drug_form")[0].reset();
        $("#allergy_enviromental_form")[0].reset();
        $("#allergy_insect_form")[0].reset();
        $("#allergy_latex_form")[0].reset();
        $("#allergy_pet_related_form")[0].reset();
        $("#allergy_other_allergy_form")[0].reset();
        $("#medications").hide();
        $("#vitalsHealth").hide();
        $("#allergy-information").show();
        $("#healthcare-services").hide();
        $("#diagnosis-codes").hide();
    });

    $("#call_preparation-vitalsHealth-modal, #research_follow_up-vitalsHealth-modal").click(function () {
        $(".modal-title").html('Vitals & Health Data');
        var formName = $(this).closest('form').attr('name');
        var ccm_stage = $('form[name=' + formName + '] input[name="stage_id"]').val();
        $("form[name='number_tracking_vitals_form'] input[name='step_id']").val('');
        $("form[name='number_tracking_vitals_form'] input[name='stage_id']").val(ccm_stage);
        $("form[name='number_tracking_labs_form'] input[name='step_id']").val('');
        $("form[name='number_tracking_labs_form'] input[name='stage_id']").val(ccm_stage);
        $("form[name='number_tracking_imaging_form'] input[name='step_id']").val('');
        $("form[name='number_tracking_imaging_form'] input[name='stage_id']").val(ccm_stage);
        $("form[name='number_tracking_healthdata_form'] input[name='step_id']").val('');
        $("form[name='number_tracking_healthdata_form'] input[name='stage_id']").val(ccm_stage);
        $("#diagnosis-codes").hide();
        $("#medications").hide();
        $("#healthcare-services").hide();
        $("#allergy-information").hide();
        $("#vitalsHealth").show();
    });

    $("#call_preparation-services-modal, #research_follow_up-services-modal").click(function () {
        $(".modal-title").html('Services');
        var formName = $(this).closest('form').attr('name');
        var ccm_stage = $('form[name=' + formName + '] input[name="stage_id"]').val()
        $("form[name='service_dme_form'] input[name='step_id']").val('');
        $("form[name='service_dme_form'] input[name='stage_id']").val(ccm_stage);
        $("form[name='service_home_health_form'] input[name='step_id']").val('');
        $("form[name='service_home_health_form'] input[name='stage_id']").val(ccm_stage);
        $("form[name='service_medical_supplies_form'] input[name='step_id']").val('');
        $("form[name='service_medical_supplies_form'] input[name='stage_id']").val(ccm_stage);
        $("form[name='service_other_health_form'] input[name='step_id']").val('');
        $("form[name='service_other_health_form'] input[name='stage_id']").val(ccm_stage);
        $("form[name='service_social_form'] input[name='step_id']").val('');
        $("form[name='service_social_form'] input[name='stage_id']").val(ccm_stage);
        $("form[name='service_therapy_form'] input[name='step_id']").val('');
        $("form[name='service_therapy_form'] input[name='stage_id']").val(ccm_stage);
        $("form[name='service_dialysis_form'] input[name='step_id']").val('');
        $("form[name='service_dialysis_form'] input[name='stage_id']").val(ccm_stage);

        $("form[name='service_dme_form']")[0].reset();
        $("form[name='service_home_health_form']")[0].reset();
        $("form[name='service_medical_supplies_form']")[0].reset();
        $("form[name='service_other_health_form']")[0].reset();
        $("form[name='service_social_form']")[0].reset();
        $("form[name='service_therapy_form']")[0].reset();
        $("form[name='service_dialysis_form']")[0].reset();
        $("#diagnosis-codes").hide();
        $("#medications").hide();
        $("#allergy-information").hide();
        $("#vitalsHealth").hide();
        $("#healthcare-services").show();
    });
    $("form[name='callstatus_form'] #call-save-button").html('<button type="submit" class="btn btn-primary m-1">Next</button>');
    $("form[name='text_form'] #text_template_id option:last").attr("selected", "selected").change();

    form.ajaxForm("call_preparation_preparation_followup_form", onPreparationFollowUp, function () {
        var checked1 = $("form[name='call_preparation_preparation_followup_form'] input[name$='condition_requirnment1']").prop("checked");
        var checked2 = $("form[name='call_preparation_preparation_followup_form'] input[name$='condition_requirnment2']").prop("checked");
        var checked3 = $("form[name='call_preparation_preparation_followup_form'] input[name$='condition_requirnment3']").prop("checked");
        var checked4 = $("form[name='call_preparation_preparation_followup_form'] input[name$='condition_requirnment4']").prop("checked");
        var reportchecked1 = $("form[name='call_preparation_preparation_followup_form'] input[name$='report_requirnment1']").prop("checked");
        var reportchecked2 = $("form[name='call_preparation_preparation_followup_form'] input[name$='report_requirnment2']").prop("checked");
        var reportchecked3 = $("form[name='call_preparation_preparation_followup_form'] input[name$='report_requirnment3']").prop("checked");
        var reportchecked4 = $("form[name='call_preparation_preparation_followup_form'] input[name$='report_requirnment4']").prop("checked");
        var reportchecked5 = $("form[name='call_preparation_preparation_followup_form'] input[name$='report_requirnment5']").prop("checked");

        if ((checked1 == true || checked2 == true || checked3 == true || checked4 == true) && (reportchecked1 == true || reportchecked2 == true || reportchecked3 == true || reportchecked4 == true || reportchecked5 == true)) {
            $('form[name="call_preparation_preparation_followup_form"] #CPmsg').html("");
            $("#CPmsg").hide();
            $('form[name="call_preparation_preparation_followup_form"] #report_requirnment').html("");
            $("#report_requirnment").hide();
            $("#time-container").val(AppStopwatch.pauseClock);
            var timer_start = $("#timer_start").val();
            var timer_paused = $("#time-container").text();
            $("form[name='call_preparation_preparation_followup_form'] input[name='start_time']").val(timer_start);
            $("form[name='call_preparation_preparation_followup_form'] input[name='end_time']").val(timer_paused);
            $("#timer_end").val(timer_paused);
            $("#time-container").val(AppStopwatch.startClock);
            return true;
        } else {
            setTimeout(function () { $('form[name="call_preparation_preparation_followup_form"]').find(":submit").attr("disabled", false) }, 3000);
            if (checked1 == false && checked2 == false && checked3 == false && checked4 == false) {
                $('form[name="call_preparation_preparation_followup_form"] #CPmsg').show();
                $('form[name="call_preparation_preparation_followup_form"] #CPmsg').html("Please Select Anyone Checkbox!");
            }
            if (reportchecked1 == false && reportchecked2 == false && reportchecked3 == false && reportchecked4 == false && reportchecked5 == false) {
                $('form[name="call_preparation_preparation_followup_form"] #report_requirnment').show();
                $('form[name="call_preparation_preparation_followup_form"] #report_requirnment').html("Please Select Anyone Checkbox!");
            }
        }
    });

    //dignosis for careplan
    form.ajaxForm("care_plan_form", carePlanDevelopment.onDiagnosis, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('care_plan_form');
        return true;
    });

    //medication         
    form.ajaxForm("medications_form", carePlanDevelopment.onMedication, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('medications_form');
        return true;
    });

    //Number Tracking Vital
    form.ajaxForm("number_tracking_vitals_form", carePlanDevelopment.onNumberTrackingVital, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('number_tracking_vitals_form');
        return true;
    });

    form.ajaxForm("number_tracking_labs_form", carePlanDevelopment.onNumberTrackingLab, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('number_tracking_labs_form');
        return true;
    });

    form.ajaxForm("number_tracking_imaging_form", carePlanDevelopment.onNumberTrackingImaging, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('number_tracking_imaging_form');
        return true;
    });

    form.ajaxForm("number_tracking_healthdata_form", carePlanDevelopment.onNumberTrackingHealthdata, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('number_tracking_healthdata_form');
        return true;
    });
	
	//add for device(ashwini mali 0202)
    $('form[name="devices_form"] .submit-add-patient-devices').on('click', function (e) {
        carePlanDevelopment.updateTimerFieldsOnForm('devices_form');
        form.ajaxSubmit('devices_form', patientEnrollment.onMasterDevices);
    });
	
	$('form[name="fin_number_form"] .submit-add-patient-fin-number').on('click', function (e) {
        carePlanDevelopment.updateTimerFieldsOnForm('fin_number_form');
        form.ajaxSubmit('fin_number_form', patientEnrollment.onFinNumber);
    });

    $('form[name="personal_notes_form"] .submit-personal-notes').on('click', function (e) {
        carePlanDevelopment.updateTimerFieldsOnForm('personal_notes_form');
        form.ajaxSubmit('personal_notes_form', patientEnrollment.onPersonalNotes);
    });

    $('form[name="part_of_research_study_form"] .submit-part-of-research-study').on('click', function () {
        carePlanDevelopment.updateTimerFieldsOnForm('part_of_research_study_form');
        form.ajaxSubmit('part_of_research_study_form', patientEnrollment.onPartOfResearchStudy);
    });

    $('form[name="patient_threshold_form"] .submit-patient-threshold').on('click', function () {
        carePlanDevelopment.updateTimerFieldsOnForm('patient_threshold_form');
        form.ajaxSubmit('patient_threshold_form', patientEnrollment.onPatientThreshold);
    });

    // form.ajaxForm("callstatus_form", onCallStatus, function () {
    form.ajaxForm("callstatus_form", ccmcpdcommonJS.onCallStatus, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('callstatus_form');
        return true;
    });

    // form.ajaxForm("hippa_form", onCallHippa, function () {
    form.ajaxForm("hippa_form", ccmcpdcommonJS.onCallHippa, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('hippa_form');
        return true;
    });

    //Allergies         
    form.ajaxForm("allergy_food_form", carePlanDevelopment.onAllergy, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('allergy_food_form');
        return true;
    });

    form.ajaxForm("allergy_drug_form", carePlanDevelopment.onAllergy, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('allergy_drug_form');
        return true;
    });

    form.ajaxForm("allergy_enviromental_form", carePlanDevelopment.onAllergy, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('allergy_enviromental_form');
        return true;
    });

    form.ajaxForm("allergy_insect_form", carePlanDevelopment.onAllergy, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('allergy_insect_form');
        return true;
    });

    form.ajaxForm("allergy_latex_form", carePlanDevelopment.onAllergy, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('allergy_latex_form');
        return true;
    });

    form.ajaxForm("allergy_pet_related_form", carePlanDevelopment.onAllergy, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('allergy_pet_related_form');
        return true;
    });

    form.ajaxForm("allergy_other_allergy_form", carePlanDevelopment.onAllergy, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('allergy_other_allergy_form');
        return true;
    });

    $('body').on('click', '.editfollowupnotes', function () {
        $('#task_date').html('');
        $('#topic').val('');
        $('#task_date_val').val('');
        $('#task_notes').html('');
        $('#notes').html('');
        $('#category').html('');
        $("#followup_task_edit_notes")[0].reset();
        var patientId = $("#patient_id").val();
        var id = $(this).data('id');
        $("#hiden_id").val(id);
        $("#edit_notes_modal").modal('show');
        url = '/ccm/getFollowupListData-edit/' + id + '/' + patientId + '/followupnotespopulate';
        populateForm(id, url);
    });

    $('body').on('click', '.change_status_flag', function () {
        var id = $(this).data('id');
        var component_id = $("form[name='followup_form'] input[name='component_id']").val();
        var module_id = $("form[name='followup_form'] input[name='module_id']").val();
        var stage_id = $("form[name='followup_form'] input[name='stage_id']").val();
        var step_id = $("form[name='followup_form'] input[name='step_id']").val();
        var timer_start = $("#timer_start").val();
        var timer_paused = $("#time-container").text();
        if (confirm("Are you sure you want to change the Status")) {
            $.ajax({
                type: 'post',
                url: '/ccm/completeIncompleteTask',
                data: 'id=' + id + '&timer_start=' + timer_start + '&timer_paused=' + timer_paused + '&module_id=' + module_id + '&component_id=' + component_id + '&stage_id=' + stage_id + '&step_id=' + step_id + '&form_name=' + form_name,
                success: function (response) {
                    util.getToDoListData($("#patient_id").val(), $("form[name='followup_form'] input[name='module_id']").val());
                    //util.getDataCalender($("#patient_id").val(), $("form[name='followup_form'] input[name='module_id']").val());
                    var table = $('#callwrap-list');
                    table.DataTable().ajax.reload();
                    var table1 = $('#task-list');
                    table1.DataTable().ajax.reload();
                    $("#time-container").val(AppStopwatch.pauseClock);
                    $("#timer_start").val(timer_paused);
                    $("#timer_end").val(timer_paused);
                    $("#time-container").val(AppStopwatch.startClock);
                    util.totalTimeSpentByCM();
                    util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
                },
            });
        } else {
            return false;
        }
    });

    // $('.patient_data_allergies_tab').click(function (e) { 
    //     // alert('patient_data_allergies_tab'); 
    //     var target = $(e.target).attr("href") // activated tab  
    //     var form = $(target).find("form").attr('name');
    //     var allergy_type = $("form[name=" + form + "] input[name='allergy_type']").val();
    //     var id = $("#patient_id").val();
    //     util.refreshAllergyCountCheckbox(id, allergy_type, form);
    // });

    $('body').on('click', '.allergiesclick', function () {
        $("#allergy_food_form")[0].reset();
        $("#allergy_drug_form")[0].reset();
        $("#allergy_enviromental_form")[0].reset();
        $("#allergy_insect_form")[0].reset();
        $("#allergy_latex_form")[0].reset();
        $("#allergy_pet_related_form")[0].reset();
        $("#allergy_other_allergy_form")[0].reset();
        $("form[name='allergy_food_form'] #id").val('');
        $("form[name='allergy_drug_form'] #id").val('');
        $("form[name='allergy_enviromental_form'] #id").val('');
        $("form[name='allergy_insect_form'] #id").val('');
        $("form[name='allergy_latex_form'] #id").val('');
        $("form[name='allergy_pet_related_form'] #id").val('');
        $("form[name='allergy_other_allergy_form'] #id").val('');
    });

    form.ajaxForm("relationship_form", onRelationship, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('relationship_form');
        return true;
    });

    form.ajaxForm("research_follow_up_preparation_followup_form", onPreparationResearchFollowUp, function () {
        var checked1 = $("form[name='research_follow_up_preparation_followup_form'] input[name$='condition_requirnment1']").prop("checked");
        var checked2 = $("form[name='research_follow_up_preparation_followup_form'] input[name$='condition_requirnment2']").prop("checked");
        var checked3 = $("form[name='research_follow_up_preparation_followup_form'] input[name$='condition_requirnment3']").prop("checked");
        var checked4 = $("form[name='research_follow_up_preparation_followup_form'] input[name$='condition_requirnment4']").prop("checked");
        var reportchecked1 = $("form[name='research_follow_up_preparation_followup_form'] input[name$='report_requirnment1']").prop("checked");
        var reportchecked2 = $("form[name='research_follow_up_preparation_followup_form'] input[name$='report_requirnment2']").prop("checked");
        var reportchecked3 = $("form[name='research_follow_up_preparation_followup_form'] input[name$='report_requirnment3']").prop("checked");
        var reportchecked4 = $("form[name='research_follow_up_preparation_followup_form'] input[name$='report_requirnment4']").prop("checked");
        var reportchecked5 = $("form[name='research_follow_up_preparation_followup_form'] input[name$='report_requirnment5']").prop("checked");

        if ((checked1 == true || checked2 == true || checked3 == true || checked4 == true) && (reportchecked1 == true || reportchecked2 == true || reportchecked3 == true || reportchecked4 == true || reportchecked5 == true)) {
            $('form[name="research_follow_up_preparation_followup_form"] #CPmsg').html("");
            $('form[name="research_follow_up_preparation_followup_form"] #report_requirnment').html("");
            $("#CPmsg").hide();
            $("#report_requirnment").hide();
            carePlanDevelopment.updateTimerFieldsOnForm('research_follow_up_preparation_followup_form');
            return true;
        } else {
            setTimeout(function () { $('form[name="research_follow_up_preparation_followup_form"]').find(":submit").attr("disabled", false) }, 3000);
            if (checked1 == false && checked2 == false && checked3 == false && checked4 == false) {
                $('form[name="research_follow_up_preparation_followup_form"] #CPmsg').show();
                $('form[name="research_follow_up_preparation_followup_form"] #CPmsg').html("Please Select Anyone Checkbox!");
            }
            if (reportchecked1 == false && reportchecked2 == false && reportchecked3 == false && reportchecked4 == false && reportchecked5 == false) {
                $('form[name="research_follow_up_preparation_followup_form"] #report_requirnment').show();
                $('form[name="research_follow_up_preparation_followup_form"] #report_requirnment').html("Please Select Anyone Checkbox!");
            }
        }
    });

    // form.ajaxForm("call_close_form", onCallClose, function () {
    form.ajaxForm("call_close_form", ccmcpdcommonJS.onCallClose, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('call_close_form');
        return true;
    });

    form.ajaxForm("followup_form", onFollowUpForm, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('followup_form');
        return true;
    });

    form.ajaxForm("followup_task_edit_notes", onFollowUpFormEditData, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('followup_task_edit_notes');
        return true;
    });

     // form.ajaxForm("callwrapup_form", onCallWrapUp, function () {
    form.ajaxForm("callwrapup_form", ccmcpdcommonJS.onCallWrapUp, function () { //final changes
                   
            var checkbox_1 = $('form[name="callwrapup_form"] #routine_response ').prop("checked"); 
            var checkbox_2 = $('form[name="callwrapup_form"] #urgent_emergent_response ').prop("checked"); 
            var checkbox_3 = $('form[name="callwrapup_form"] #referral_order_support ').prop("checked"); 
            var checkbox_4 = $('form[name="callwrapup_form"] #medication_support ').prop("checked"); 
            var checkbox_5 = $('form[name="callwrapup_form"] #verbal_education_review_with_patient ').prop("checked"); 
            var checkbox_6 = $('form[name="callwrapup_form"] #mailed_documents ').prop("checked");  
            var checkbox_7 = $('form[name="callwrapup_form"] #resource_support ').prop("checked"); 
            var checkbox_8 = $('form[name="callwrapup_form"] #veterans_services ').prop("checked"); 
            var checkbox_9 = $('form[name="callwrapup_form"] #authorized_cm_only ').prop("checked"); 
            var checkbox_10 = $('form[name="callwrapup_form"] #no_additional_services_provided ').prop("checked"); 

           
            var checkbox1_length = $('#routinediv input:checked').length;
            var checkbox2_length = $('#emergentdiv input:checked').length;
            var checkbox3_length = $('#referraldiv input:checked').length;
            var checkbox4_length = $('#medicationdiv input:checked').length;
            var checkbox5_length = $('#verbaldiv input:checked').length;
            var checkbox6_length = $('#maileddiv input:checked').length;
            var checkbox7_length = $('#resourcediv input:checked').length;
            var checkbox8_length = $('#veteransdiv input:checked').length;
            var checkbox9_length = $('#authorizeddiv input:checked').length;


             // if(jQuery('#frmTest input[type=checkbox]:checked').length) { … }
            // var q = (jQuery('#routine_response input[type=checkbox]:checked').length) ;

            if( (checkbox_1 == true && (checkbox1_length > 0 ) ) || (checkbox_2 == true && (checkbox2_length >0) ) || 
                (checkbox_3 == true && (checkbox3_length > 0) ) || (checkbox_4 == true && (checkbox4_length >0) ) || 
                (checkbox_5 == true && (checkbox5_length > 0) ) || (checkbox_6 == true && (checkbox6_length >0) ) ||
                (checkbox_7 == true && (checkbox7_length > 0) ) || (checkbox_8 == true && (checkbox8_length >0) ) ||
                (checkbox_9 == true && (checkbox9_length > 0) ) || (checkbox_10 == true) 
                ) {	
                $('form[name="callwrapup_form"] #checkboxerror' ).css('display', 'none');
                carePlanDevelopment.updateTimerFieldsOnForm('callwrapup_form');
                return true; 
            } else {           
                $('form[name="callwrapup_form"] #checkboxerror' ).css('display', 'block');
                setTimeout(function () { $('form[name="callwrapup_form"]').find(":submit").attr("disabled", false) }, 3000);
                return false;
            }
          
    });



    form.ajaxForm("text_form", onText, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('text_form');
        return true;
    });

    $("form[name='call_close_form'] input[name='query1']").click(function () {
        var health_issue = $(this).val();
        if (health_issue == '1') {
            $("#health_issue_notes_div").show();
        } else {
            $("form[name='call_close_form'] textarea[name='q1_notes']").val('');//reset form value
            $("#health_issue_notes_div").hide();
        }
    });

    $("form[name='call_close_form'] input[name='query2']").click(function () {
        var call_next_month_data = $(this).val();
        if (call_next_month_data == '1' || call_next_month_data == '0') {
            $("#next_month_call_div").show();
        } else {
            $("#next_month_call_date").val('');//reset form value
            $("#next_month_call_time").val('');//reset form value
            $("form[name='call_close_form'] textarea[name='q2_notes']").val('');//reset form value
            $("#next_month_call_div").hide();
        }
    });

    $("form[name='number_tracking_vitals_form'] input[name='oxygen']").click(function () {
        var oxygen = $(this).val();
        if (oxygen == '0') {
            $("#Supplemental_notes_div").show();
        } else {
            $("form[name='number_tracking_vitals_form'] textarea[name='notes']").val('');//reset form value
            $("#Supplemental_notes_div").hide();
        }
    });

    $("form[name='callstatus_form'] input[name='call_continue_status']").click(function () {
        var call_continue_status = $(this).val();
        if (call_continue_status == '0') {
            $("#schedule_call_ans_next_call").show();
        } else {
            $("#answer_followup_date").val('');//reset form
            $("#schedule_call_ans_next_call").hide();
        }
    });

    $("form[name='callstatus_form'] input[name='answer_followup_time']").change(function () {
        var inputEle = document.getElementById('answer_followup_time');
        var timeSplit = inputEle.value.split(':'),
            hours,
            minutes,
            meridian;
        hours = timeSplit[0];
        minutes = timeSplit[1];
        if (hours > 12) {
            meridian = 'PM';
            hours -= 12;
        } else if (hours < 12) {
            meridian = 'AM';
            if (hours == 0) {
                hours = 12;
            }
        } else {
            meridian = 'PM';
        }
        var answer_followup_time = hours + ':' + minutes + ' ' + meridian;
        $("#hourtime").val(answer_followup_time);
    });

    $('#call_preparation_draft').on('click', function () {
        carePlanDevelopment.updateTimerFieldsOnForm('call_preparation_preparation_followup_form');
        $.ajax({
            url: '/ccm/monthly-monitoring-call-preparation-form-draft',
            type: 'POST',
            data: $("#call_preparation_preparation_followup_form").serialize(),
            success: function (data) {
                $('.invalid-feedback').html('');
                $(".form-control").removeClass("is-invalid");
                util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
                var patient_id = $("#hidden_id").val();
                var module_id = $("#call_preparation_preparation_followup_form input[name='module_id']").val();
                var stage_id = $("#call_preparation_preparation_followup_form input[name='stage_id']").val();
                var year = (new Date).getFullYear();
                var month = (new Date).getMonth() + 1; //add +1 for current mnth
                util.getPatientCareplanNotes(patient_id, module_id);
                util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
                util.totalTimeSpentByCM();
                $("form[name='call_preparation_preparation_followup_form'] .alert").show();
                var scrollPos = $(".main-content").offset().top;
                $(window).scrollTop(scrollPos);
                setTimeout(function () {
                    util.totalTimeSpent(patient_id, module_id, stage_id); $('.alert').fadeOut('fast');
                }, 5000);
                goToNextStep("call_step_1_id"); 
                var researchPreparationNotesForm = 'research_follow_up_preparation_followup_form';
                var preparationNotesFormPopulateURL = URL_POPULATE_RESEARCH_FOLLOWUP_PREPARATION_NOTES + "/" + patientId + "/current";
                populateForm(patientId, preparationNotesFormPopulateURL);

                var table = $('#callwrap-list');
                table.DataTable().ajax.reload();
                var timer_paused = $("form[name='call_preparation_preparation_followup_form'] input[name='end_time']").val();
                $("#timer_start").val(timer_paused);
            }
        })
    });

    //call.call.call-status-change
    $("form[name='callstatus_form'] input[name='call_status']").click(function () {
        var checked_call_option = $("form[name='callstatus_form'] input[name$='call_status']:checked").val();
        if (checked_call_option == 1 || checked_call_option == "1") {
            $('.invalid-feedback').html('');
            $("form[name='callstatus_form'] #notAnswer").hide();
            $("#answer").prop('selectedIndex', 0);//reset value
            $("#contact_number").prop('selectedIndex', 0);//reset value
            $("#call_followup_date").val('');//reset value  
            $("form[name='callstatus_form'] #callAnswer").show();
            $("form[name='callstatus_form'] #call_scripts_select option:last").attr("selected", "selected").change();
            $("form[name='callstatus_form'] #call-save-button").html('<button type="submit" class="btn  btn-primary m-1" id="save-callstatus">Next</button>');
            $("form[name='callstatus_form'] #call_action_script").val($("form[name='callstatus_form'] input[name='call_action_script'] option:selected").text());
            util.getCallScriptsById($("form[name='callstatus_form'] #call_scripts_select option:selected").val(), '.call_answer_template', "form[name='callstatus_form'] input[name='template_type_id']", "form[name='callstatus_form'] input[name='content_title']");
        } else if (checked_call_option == 2 || checked_call_option == "2") {
            $('.invalid-feedback').html('');
            $("form[name='callstatus_form'] #callAnswer").hide();
            $("#role1").prop('checked', false);//reset value
            $("#role2").prop('checked', false);//reset value
            $("#answer_followup_date").val('');//reset value
            $("form[name='callstatus_form'] #notAnswer").show();
            $("form[name='callstatus_form'] #ccm_content_title option:last").attr("selected", "selected").change();
            $("form[name='callstatus_form'] #call-save-button").html('<button type="submit" class="btn btn-primary m-1 call_status_submit" id="save_schedule_call">Schedule Call</button>');
            $("form[name='callstatus_form'] #call_action_script").val($("form[name='callstatus_form'] input[name='content_title'] option:selected").text());
            util.getCallScriptsById($("form[name='callstatus_form'] #ccm_content_title option:selected").val(), '#ccm_content_area', "form[name='callstatus_form'] input[name='template_type_id']", "form[name='callstatus_form'] input[name='content_title']");
        }
    });

    //text
    $("form[name='text_form'] #text_template_id").change(function () {
        util.getCallScriptsById($(this).val(), '#templatearea_sms', "form[name='text_form'] input[name='template_type_id']", "form[name='text_form'] input[name='content_title']");
    });

    util.getCallScriptsById($("form[name='text_form'] #text_template_id").val(), '#templatearea_sms', "form[name='text_form'] input[name='template_type_id']", "form[name='text_form'] input[name='content_title']");

    $("form[name='callstatus_form'] #call_scripts_select").change(function () {
        util.getCallScriptsById($(this).val(), '.call_answer_template', "form[name='callstatus_form'] input[name='template_type_id']", "form[name='callstatus_form'] input[name='content_title']");
    });

    $("form[name='callstatus_form'] #voice_scripts_select").change(function () {
        util.getCallScriptsById($(this).val(), '.voice_mail_template', "form[name='callstatus_form'] input[name='template_type_id']", "form[name='callstatus_form'] input[name='content_title']");
    });

    $("form[name='callstatus_form'] #ccm_content_title").change(function () {
        util.getCallScriptsById($(this).val(), '#ccm_content_area', "form[name='callstatus_form'] input[name='template_type_id']", "form[name='callstatus_form'] input[name='content_title']");
    });

    $(document).on("click", ".remove-icons", function () {
        var button_id = $(this).closest('div').attr('id');
        $('#' + button_id).remove();
        var id = $(this).attr("id");
        var count = id.split("_");
        if (count[1] == 'lebs') {
            $('#btn_removelabs_' + count[2]).remove();
        }
    });

    $("input[name$='newofficevisit']").click(function () {
        var newofficevisit = $(this).val();
        if (newofficevisit == "1") {
            $(".office_visit_note").show();
        } else {
            $("textarea[name='nov_notes']").val('');
            $(".office_visit_note").hide();
        }
    });

    $("input[name$='newdiagnosis']").click(function () {
        var newdiagnosis = $(this).val();
        if (newdiagnosis == "1") {
            $(".new_diagnosis_note").show();
        } else {
            $("textarea[name='nd_notes']").val('');
            $(".new_diagnosis_note").hide();
        }
    });

    $("input[name$='changetodme']").click(function () {
        var changetodme = $(this).val();
        if (changetodme == "1") {
            $(".change_dme_note").show();
        } else {
            $("textarea[name='ctd_notes']").val('');
            $(".change_dme_note").hide();
        }
    });

    $("input[name$='med_added_or_discon']").click(function () {
        var med_added_or_discon = $(this).val();
        if (med_added_or_discon == "1") {
            $(".med_add_dis_note").show();
        } else {
            $("textarea[name='med_added_or_discon_notes']").val('');
            $(".med_add_dis_note").hide();
        }
    });

    $("input[name$='newdme']").click(function () {
        var newdme = $(this).val();
        if (newdme == "1") {
            $(".new_dme_note").show();
        } else {
            $("textarea[name='dme_notes']").val('');
            $(".new_dme_note").hide();
        }
    });

    $("input[name='call_status']").click(function () {
        var checked_call_option = $("input[name='call_status']:checked").val();
        if (checked_call_option == "1") {
            $(".invalid-feedback").html("");
            $("#CcmNotAnswer").hide();
            $("#CcmCallAnswer").show();
            $("#save-callstatus").show();
            $("#call_scripts_select option:last")
                .attr("selected", "selected")
                .change();
        } else if (checked_call_option == "2") {
            $(".invalid-feedback").html("");
            $("#CcmNotAnswer").show();
            $("#CcmCallAnswer").hide();
            $("#save-callstatus").hide();
            $("#ccm_content_title option:last")
                .attr("selected", "selected")
                .change();
        }
    });

    $("form[name='research_follow_up_preparation_followup_form'] input[name='data_present_in_emr']").click(function () {
        var data_present_in_emr_option = $("form[name='research_follow_up_preparation_followup_form'] input[name='data_present_in_emr']:checked").val();
        if (data_present_in_emr_option == '0') {
            var preparationNotesFormPopulateURL = URL_POPULATE_PREPARATION_NOTES + "/" + patientId + "/current";
            populateForm(patientId, preparationNotesFormPopulateURL);
            $('.invalid-feedback').html('');
            $("form[name='research_follow_up_preparation_followup_form']")[0].reset();
            $("form[name='research_follow_up_preparation_followup_form'] #data_present_in_emr_show").show();
        } else if (data_present_in_emr_option == '1') {
            var researchFollowupPreparationNotesFormPopulateURL = URL_POPULATE_RESEARCH_FOLLOWUP_PREPARATION_NOTES + "/" + patientId + "/current";
            populateForm(patientId, researchFollowupPreparationNotesFormPopulateURL);
        }
    });

    $('#callwrap-list').on('click', 'tbody td.editable', function (e) {
        editor.inline(this);
        var sPageURL = window.location.pathname;
        parts = sPageURL.split("/");
        var patientId = parts[parts.length - 1];
        var preparationNotesFormPopulateURL = URL_POPULATE_RESEARCH_FOLLOWUP_PREPARATION_NOTES + "/" + patientId + "/current";
        populateForm(patientId, preparationNotesFormPopulateURL);
    });

    //services
    form.ajaxForm("service_dialysis_form", carePlanDevelopment.onServices, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('service_dialysis_form');
        return true;
    });

    form.ajaxForm("service_social_form", carePlanDevelopment.onServices, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('service_social_form');
        return true;
    });

    form.ajaxForm("service_home_health_form", carePlanDevelopment.onServices, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('service_home_health_form');
        return true;
    });

    form.ajaxForm("service_dme_form", carePlanDevelopment.onServices, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('service_dme_form');
        return true;
    });

    form.ajaxForm("service_medical_supplies_form", carePlanDevelopment.onServices, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('service_medical_supplies_form');
        return true;
    });

    form.ajaxForm("service_other_health_form", carePlanDevelopment.onServices, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('service_other_health_form');
        return true;
    });

    form.ajaxForm("service_therapy_form", carePlanDevelopment.onServices, function () {
        carePlanDevelopment.updateTimerFieldsOnForm('service_therapy_form');
        return true;
    });

    function setIntervalFunctionAgain() {
        var id = $("input[name='patient_id']").val();
        $.ajax({
            url: "/ccm/get-message-history/" + id,
            type: 'GET',
            success: function (res) {
                $("#ajax-message-history").html('');
                $("#ajax-message-history").append(res);
                setTimeout(function () { setIntervalFunction(); }, 10000);
            }
        });
    }

    function setIntervalFunction() {
        var id = $("input[name='patient_id']").val();
        $.ajax({
            url: "/ccm/get-message-history/" + id,
            type: 'GET',
            success: function (res) {
                $("#ajax-message-history").html('');
                $("#ajax-message-history").append(res);
                setTimeout(function () { setIntervalFunctionAgain(); }, 10000);
            }
        });
    }

    $(document).ready(function () {
        setIntervalFunction();
    });
};

$(document).on('click', 'i.removenotes', function (e) {
    e.preventDefault();
    $(this).parents('.additionalfeilds').remove();
});

$("#addnotes").click(function () {
    summarycount++;
    var childrenlength = $("div#additional_monthly_notes").children().length;
    $('#additional_monthly_notes').append('<div class="additionalfeilds row"  style="margin-left: 0.05rem !important; margin-bottom: 0.5rem;"><div class="col-md-4"><input type="date" class="form-control emr_monthly_summary_date" id="emr_monthly_summary_date_' + childrenlength + '"  name="emr_monthly_summary_date[]"><div class="invalid-feedback"></div></div><div class="col-md-8"><textarea  class="form-control " cols="90"  name="emr_monthly_summary[]" ></textarea><div class="invalid-feedback"></div><i type="button" class="removenotes  i-Remove" style="color: #f44336;  font-size: 22px;margin-top: -37px;margin-right: -51px;float: right;"></i></div></div>');
    var date = new Date();
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear();
    if (month < 10) month = "0" + month;
    if (day < 10) day = "0" + day;
    var today = year + "-" + month + "-" + day;
    $("form[name='callwrapup_form'] #emr_monthly_summary_date_" + childrenlength).val(today);
});

// Module Export---------------------------------------------------------------
// Export the module functions
window.ccmMonthlyMonitoring = {
    init: init,
    // onCallStatus: onCallStatus, //moved to common js
    // onCallHippa: onCallHippa, //moved to common js
    onRelationship: onRelationship,
    onPreparationResearchFollowUp: onPreparationResearchFollowUp,
    onPreparationFollowUp: onPreparationFollowUp,
	callWrapUpShowHide:callWrapUpShowHide,
    // onCallClose: onCallClose, //moved to common js
    // onCallWrapUp: onCallWrapUp, //moved to common js
    // callMonthllyMonitoringInitFunctions: callMonthllyMonitoringInitFunctions, //moved to common js
	populateForm:populateForm
};