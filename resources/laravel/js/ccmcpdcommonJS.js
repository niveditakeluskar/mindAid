/**
* Test-Form Javascript
*/
const URL_POPULATE = "/patients/ajax/active_deactive_populate";
const URL_SAVE = "/ajax/test_save";
const URL_POPULATE_PREPARATION_NOTES = "/ccm/ajax/populate_preparation_notes";
const URL_POPULATE_RESEARCH_FOLLOWUP_PREPARATION_NOTES = "/ccm/ajax/populate_research_followup_preparation_notes";


var baseURL = window.location.origin + '/';
var patient_id = $("#hidden_id").val();
var sPageURL = window.location.pathname;
parts = sPageURL.split("/"),
  module = parts[parts.length - 3],
  sub_module = parts[parts.length - 2];



/**
* Invoked when the form is submitted
*
* @return {Boolean}
*/
var onSubmit = function () {
  $(".tab-error").removeClass("tab-error");
  return true;
};

/**
 * Invoked when errors in the form are detected
 */
var onErrors = function (form, fields, response) {
  if (response.data.errors) {
    for (var field in response.data.errors) {
      try {
        let id = fields.fields[field].parents("[role='tabpanel']").attr("id");
        $(`#${id}-tab`).addClass("tab-error");
      } catch (e) {
        console.error("Field Error:", field, fields.fields);
      }
    }
  }
  $("form[name='test_form']").attr("action", URL_SAVE);
  return true;
};

/**
 * Invoked after the form has been submitted
 */
var onResult = function (form, fields, response, error) {
  if (error)
    console.error(error);
  if (response.status == 200) {
    // $("form[name='test_form']").attr("action", URL_SAVE);
    notify.success("Test Saved Successfully");
    $("#form-id").val(response.data.form);
    setTimeout(function () {
      if (confirm("Would you like to print now?")) {
        onPrint(null, true, response.data.form);
      }
    }, 1);
  } else {
    // $("form[name='test_form']").attr("action", URL_SAVE);
    notify.danger("Save Failed: Unknown Error");
  }
};

//add for device (ashwini mali 0202)
var devicesColumns = [
  { data: 'DT_RowIndex', name: 'DT_RowIndex' },
  { data: 'device_code', name: 'device_code' },
  { data: 'name', name: 'name' },
  { data: 'device_name', name: 'device_name' },
  {
    data: 'f_name', name: 'f_name', render:
      function (data, type, full, meta) {
        if (data != '' && data != 'NULL' && data != undefined) {
          return data + ' ' + full.l_name;
        } else {
          return '';
        }
      }
  },
  {
    data: 'updated_at', type: 'date-dd-mm-yyyy h:i:s', name: 'updated_at', "render": function (value) {
      if (value === null) return "";
      return util.viewsDateFormatWithTime(value);
    }
  },

  // {
  //  data: 'update_date', name: 'update_date', "render": function (value) {
  //    if (value === null || value == undefined || value == "") return "";
  //    return moment(value).format('MM-DD-YYYY');
  //  }
  // },

  { data: 'action', name: 'action', orderable: false, searchable: false },
];


/**
 * Populate the form of the given patient
 *
 * @param {Integer} patientId
 */
var populateForm = function (data, url) {

  $.get(
    url,
    data,
    function (result) {
      for (var key in result) {



        // if((result[key][0] != null || result[key][0] != 'undefined'  ||  result[key][0] != ''  )  ){
        //   var date_enrolled = result[key][0]['date_enrolled'];
        //   var a = date_enrolled.split(" ")[0];
        //   var day = a.split("-");
        //   $mydate = day[2] + '-' + day[0] + '-' + day[1];
        // }

        // if (key == 'enrolleddateform') {
        //   document.getElementById("date_enrolled").value = $mydate;
        // }


      }
    }
  ).fail(function (result) {
    console.error("Population Error:", result);
  });
};

var onSaveActiveDeactive = function (formObj, fields, response) {
  if (response.status == 200) {
    var varworklist = $("form[name='active_deactive_form'] #worklistclick").val();
    var status = $("form[name='active_deactive_form'] input[name ='status']:checked").val();
    if (status == 0) {
      var ps = "Suspended";
    } else if (status == 1) {
      var ps = "Activated";
    } else if (status == 2) {
      var ps = "Deactivated";
    }
    else {
      var ps = "marked as Deceased";
    }
    $("#patientalertdiv").show();
    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Patient ' + ps + ' Successfully!</strong></div>';
    // alert(txt);
    $("#patientalertdiv").html(txt);
    var scrollPos = $(".main-content").offset().top;
    $(window).scrollTop(scrollPos);
    setTimeout(function () {
      $("#patientalertdiv").hide();
      if (varworklist != '1') {
        location.reload();
      } else {
        $('#month-search').click();
        $('select[name="activedeactivestatus"] option[value="1"]').attr("selected", null);
        $('#active-deactive').modal('hide');
        $('form[name="active_deactive_form"] #todate').prop("disabled", false);
        $("form[name='active_deactive_form']")[0].reset();
      }
    }, 3000);
  }
};

var onActiveDeactiveClick = function ($pid, $status) {
  var sPageURL = window.location.pathname;
  parts = sPageURL.split("/"),
    patientId = parts[parts.length - 1];
  if ($.isNumeric(patientId) == true) {
    //patient list
    var patientId = $("#hidden_id").val();
    var module = $("input[name='module_id']").val();
    var status = $("#service_status").val();
    $('#enrolledservice_modules').val(module).trigger('change');
    $('#enrolledservice_modules').change();
    // util.getPatientDetails(patientId, module);
  } else {
    //worklist 
    var patientId = $pid;
    var selmoduleId = $("#modules").val();
    util.getPatientEnrollModule(patientId, selmoduleId);
    var status = $status;
    $("form[name='active_deactive_form'] #worklistclick").val("1");
    $("form[name='active_deactive_form'] #patientid").val(patientId);
    $("form[name='active_deactive_form'] #date_value").hide();
    $("form[name='active_deactive_form'] #fromdate").hide();
    $("form[name='active_deactive_form'] #todate").hide();
    if ($status == 0) {
      $("form[name='active_deactive_form'] #role1").show();
      $("form[name='active_deactive_form'] #role0").hide();
      $("form[name='active_deactive_form'] #role2").show();
      $("form[name='active_deactive_form'] #role3").show();
    }
    if ($status == 1) {
      $("form[name='active_deactive_form'] #role1").hide();
      $("form[name='active_deactive_form'] #role0").show();
      $("form[name='active_deactive_form'] #role2").show();
      $("form[name='active_deactive_form'] #role3").show();
    }
    if ($status == 2) {
      // $("form[name='active_deactive_form'] #status-title").text('Activate/Suspend Or Deceased Patient');
      $("form[name='active_deactive_form'] #role1").show();
      $("form[name='active_deactive_form'] #role0").show();
      $("form[name='active_deactive_form'] #role2").hide();
      $("form[name='active_deactive_form'] #role3").show();
    }
    if ($status == 3) {
      $("form[name='active_deactive_form'] #role1").show();
      $("form[name='active_deactive_form'] #role0").show();
      $("form[name='active_deactive_form'] #role2").show();
      $("form[name='active_deactive_form'] #role3").hide();
    }
  }
}


var onSaveenrolleddateform = function (formObj, fields, response) {
  if (response.status == 200) {
    $("#messagingbody").show();
    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Patient Enrolled Date Saved Successfully!</strong></div>';
    $("#messagingbody").html(txt);
    var scrollPos = $(".main-content").offset().top;
    $(window).scrollTop(scrollPos);
    //goToNextStep("call_step_1_id");
    setTimeout(function () {
      $("#messagingbody").hide();
      $('#enrolleddateModal').modal('hide');
      location.reload();
    }, 3000);
  }
  else {
    $("#messagingbody").show();
    var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill Enrolled Date!</strong></div>';
    $("#messagingbody").html(txt);
  }
};

function datapopulate(patientId) {
  var data = "";
  var formpopulateurl = URL_POPULATE + "/" + patientId;
  populateForm(data, formpopulateurl);
}

$("form[name='active_deactive_form'] input[name='status']").click(function () {
  var status_val = $("form[name='active_deactive_form'] input[name='status']:checked").val();
  if (status_val == 0) {
    $("form[name='active_deactive_form'] #date_value").show();
    $("form[name='active_deactive_form'] #fromdate").show();
    $("form[name='active_deactive_form'] #deceasedfromdate").hide();
    $("form[name='active_deactive_form'] #deceasedfromdate").val('');
    $("form[name='active_deactive_form'] #todate").show();
    $("form[name='active_deactive_form'] #deactivation_drpdwn_div").hide();
  } else if (status_val == 2) {
    $("form[name='active_deactive_form'] #date_value").show();
    $("form[name='active_deactive_form'] #fromdate").show();
    $("form[name='active_deactive_form'] #deceasedfromdate").hide();
    $("form[name='active_deactive_form'] #deceasedfromdate").val('');
    $("form[name='active_deactive_form'] #deactivation_drpdwn_div").show();
    $("form[name='active_deactive_form'] #todate").hide();
    $("form[name='active_deactive_form'] #todate").val('');
  } else if (status_val == 3) {
    $("form[name='active_deactive_form'] #date_value").show();
    $("form[name='active_deactive_form'] #fromdate").hide();
    $("form[name='active_deactive_form'] #fromdate").val('');
    $("form[name='active_deactive_form'] #deceasedfromdate").show();
    $("form[name='active_deactive_form'] #deactivation_drpdwn_div").hide();
    $("form[name='active_deactive_form'] #todate").hide();
    $("form[name='active_deactive_form'] #todate").val('');
  } else {
    $("form[name='active_deactive_form'] #date_value").hide();
    $("form[name='active_deactive_form'] #fromdate").hide();
    $("form[name='active_deactive_form'] #fromdate").val('');
    $("form[name='active_deactive_form'] #deceasedfromdate").hide();
    $("form[name='active_deactive_form'] #deceasedfromdate").val('');
    $("form[name='active_deactive_form'] #todate").hide();
    $("form[name='active_deactive_form'] #todate").val('');
    $("form[name='active_deactive_form'] #deactivation_drpdwn_div").hide();
    $("form[name='active_deactive_form'] #deactivation_drpdwn_div").val('');

  }
});

$('#newenrolldate').click(function () {
  changeenrolldate();
});

$('#answer').change(function () {
  if (this.value == 3) {
    $("#txt-msg").show();
  } else {
    $("#txt-msg").hide();
  }
  if (this.value == 1) {
    $("form[name='callstatus_form'] #voice_scripts_select option:last").attr("selected", "selected").change();
    $("#voicetextarea").show();
  } else {
    $("#voicetextarea").hide();
  }
});

$('#military').change(function () {
  if (this.value == '0') {
    $('#veteran-question').show();
  } else {
    $('#veteran-question').hide();
  }
});

var changeenrolldate = function () {
  var roleid = $('#roleid').val();
  var patient_id = $('#patient_id').val();
  // var module_id   = $('#module_id').val();

  if (roleid == '2') {
    $('#enrolleddateModal').modal('show');
    var data = "";
    const ENROLLEDDATEURL_POPULATE = "/ccm/monthly-monitoring/getenrolled-date";
    var formpopulateurl = ENROLLEDDATEURL_POPULATE + "/" + patient_id;
    populateForm(data, formpopulateurl);
  }
}

//add for device(ashwini mali 0202)
$('#add_patient_devices').click(function () { //alert("add_patient_devices")
  renderDeviceTableData();
});


$('.noallergiescheck').click(function () {
  var form = $(this).closest('form');
  var formname = $(form).attr('name');
  if (this.checked) {
    $("form[name='" + formname + "'] input[name='specify']").val("");
    $("form[name='" + formname + "'] input[name='type_of_reactions']").val("");
    $("form[name='" + formname + "'] input[name='severity']").val("");
    $("form[name='" + formname + "'] input[name='course_of_treatment']").val("");
    $("form[name='" + formname + "'] textarea[name='notes']").val("");

    $("form[name='" + formname + "'] input[name='specify']").attr("disabled", 'disabled');
    $("form[name='" + formname + "'] input[name='type_of_reactions']").prop("disabled", true);
    $("form[name='" + formname + "'] input[name='severity']").prop("disabled", true);
    $("form[name='" + formname + "'] input[name='course_of_treatment']").prop("disabled", true);
    $("form[name='" + formname + "'] textarea[name='notes']").prop("disabled", true);
  } else {
    $("form[name='" + formname + "']")[0].reset();
    $("form[name='" + formname + "'] input[name='specify']").prop("disabled", false);
    $("form[name='" + formname + "'] input[name='type_of_reactions']").prop("disabled", false);
    $("form[name='" + formname + "'] input[name='severity']").prop("disabled", false);
    $("form[name='" + formname + "'] input[name='course_of_treatment']").prop("disabled", false);
    $("form[name='" + formname + "'] textarea[name='notes']").prop("disabled", false);
  }
});

var callMonthllyMonitoringInitFunctions = function () {
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

  var year = (new Date).getFullYear();
  var month = (new Date).getMonth() + 1; //add +1 for current mnth
  var patient_id = $("#hidden_id").val();
  var module_id = $("input[name='module_id']").val();
  var prepstage_id = $("#call_preparation_preparation_followup_form input[name='stage_id']").val();
  util.totalTimeSpent(patient_id, module_id, prepstage_id);
  util.getPatientRelationshipBuilding($("#patient_id").val());
  util.getPatientDetails(patient_id, module_id);
  util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
  util.getPatientCareplanNotes(patient_id, module_id);
  util.getPatientStatus(patient_id, module_id);
  util.gatCaretoolData(patient_id, module_id);
  util.getToDoListData($("#patient_id").val(), module_id);
  //util.getDataCalender($("#patient_id").val(), module_id);
  getFollowupList($('#patient_id').val(), module_id);
  // Research followup data presentEMR
  var patientId = $("#patient_id").val();
  var checked_value1 = $("form[name='call_preparation_preparation_followup_form'] input[name$='condition_requirnment1']").prop('checked');
  var checked_value2 = $("form[name='call_preparation_preparation_followup_form'] input[name$='condition_requirnment2']").prop('checked');
  var checked_value3 = $("form[name='call_preparation_preparation_followup_form'] input[name$='condition_requirnment3']").prop('checked');
  var checked_value4 = $("form[name='call_preparation_preparation_followup_form'] input[name$='condition_requirnment4']").prop('checked');
  if (checked_value1 == true || checked_value2 == true || checked_value3 == true || checked_value4 == true) {
    $("form[name='research_follow_up_preparation_followup_form'] #research_follow_up_data_present_in_emr_yes").prop("checked", true);
    var researchFollowupPreparationNotesFormPopulateURL = URL_POPULATE_RESEARCH_FOLLOWUP_PREPARATION_NOTES + "/" + patientId + "/current";
    carePlanDevelopment.populateForm(patientId, researchFollowupPreparationNotesFormPopulateURL);
  } else if (checked_value1 == false || checked_value2 == false || checked_value3 == false || checked_value4 == false) {
    $("form[name='research_follow_up_preparation_followup_form'] #research_follow_up_data_present_in_emr_no").prop("checked", true);
    var researchFollowupPreparationNotesFormPopulateURL = URL_POPULATE_RESEARCH_FOLLOWUP_PREPARATION_NOTES + "/" + patientId + "/current";
    carePlanDevelopment.populateForm(patientId, researchFollowupPreparationNotesFormPopulateURL);
  } else {
    $("form[name='research_follow_up_preparation_followup_form'] #research_follow_up_data_present_in_emr_no").prop("checked", true);
    var preparationNotesFormPopulateURL = URL_POPULATE_PREPARATION_NOTES + "/" + patientId + "/current";
    carePlanDevelopment.populateForm(patientId, preparationNotesFormPopulateURL);
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
  populateForm(patientId, preparationNotesFormPopulateURL);
}

var onCallHippa = function (formObj, fields, response) {
  if (response.status == 200) {
    util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
    $(".form_start_time").val(response.data.form_start_time);
    
    $("form[name='hippa_form'] .alert").show();
    util.totalTimeSpentByCM();
    var scrollPos = $(".main-content").offset().top;
    $(window).scrollTop(scrollPos);
    if (sub_module == 'care-plan-development') {
      setTimeout(function () { $("form[name='hippa_form'] .alert").fadeOut(); }, 5000);
      goToNextStep("review-patient-tab");
    } else {
      setTimeout(function () { $('.alert').fadeOut('fast'); }, 5000);
      goToNextStep("ccm-relationship-icon-tab");
    }
    var timer_paused = $("form[name='hippa_form'] input[name='end_time']").val();
    $("#timer_start").val(timer_paused);
  }
};


var onCallStatus = function (formObj, fields, response) {
  if (response.status == 200) {
    util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
    util.getToDoListData($("#patient_id").val(), $("form[name='callstatus_form'] input[name='module_id']").val());
    $(".form_start_time").val(response.data.form_start_time);
    
    // util.getDataCalender($("#patient_id").val(), $("form[name='callstatus_form'] input[name='module_id']").val());
    var year = (new Date).getFullYear();
    var month = (new Date).getMonth() + 1
    util.getPatientPreviousMonthNotes($("#patient_id").val(), $("form[name='callstatus_form'] input[name='module_id']").val(), month, year);
    $("form[name='callstatus_form'] #success-alert").show();
    var scrollPos = $(".main-content").offset().top;
    $(window).scrollTop(scrollPos);

    var d = new Date();
    if (d.getMonth() + 1 < 10) {
      var getmonth = "0" + (d.getMonth() + 1);
    } else {
      var getmonth = (d.getMonth() + 1);
    }
    if (d.getDate() < 10) {
      var getday = "0" + d.getDate();
    } else {
      var getday = d.getDate();
    }
    var strTime = d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
    var myDate = getmonth + "-" + getday + "-" + d.getFullYear();
    var strDate = myDate + " " + strTime;
    if ($("form[name='callstatus_form'] input[name$='call_status']:checked").val() == 2 && $("#answer").val() == 3) {
      var call_followup = $("#call_followup_date").val();
      if (call_followup != '') {
        var datePart = call_followup.match(/\d+/g),
          year = datePart[0], // get only two digits
          month = datePart[1],
          day = datePart[2];
        var call_followup = month + '-' + day + '-' + year;
      }

      $("#history_list ul").prepend('<li><h5>Call Not Answered (' + strDate + ')</h5>'
        + '<table> <tr><th>Call Follow-up date</th></tr>'
        + '<tr>'
        + '<td>' + call_followup + '</td>'
        + '</tr>'
        + '</table>'
        + '<b> SMS Send:</b>' + $("#ccm_content_area").val() + '</li>');
      var twiloError = $.trim(response.data.errormsg);
      if (twiloError === "" || twiloError === "null" || twiloError === null) {
        var errormsg = '';
        $("form[name='callstatus_form'] .twilo-error").html(errormsg);
      } else {
        var errormsg = '<div class="alert alert-danger" id="danger-alert" style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">� </button><strong>' + response.data.errormsg + '</strong></div>';
        $("form[name='callstatus_form'] .twilo-error").html(errormsg);
        setTimeout(function () {
          $("form[name='callstatus_form'] .twilo-error").html('');
        }, 5000);
      }
      $("form[name='callstatus_form'] #ccm_content_title option:last").prop("selected", "selected").change();
      if (sub_module == 'care-plan-development') {
        goToNextStep("patient-datap-tab");
      } else {
        goToNextStep("start-tab");
        getFollowupList($('#patient_id').val(), $("form[name='callstatus_form'] input[name='module_id']").val());
      }
    } else if ($("form[name='callstatus_form'] input[name$='call_status']:checked").val() == 2 && $("#answer").val() == 2) {
      var call_followup = $("#call_followup_date").val();
      if (call_followup != '') {
        var datePart = call_followup.match(/\d+/g),
          year = datePart[0], // get only two digits
          month = datePart[1],
          day = datePart[2];
        var call_followup = month + '-' + day + '-' + year;
      }
      $("#history_list ul").prepend('<li><h5>Call Not Answered (' + strDate + ')</h5>'
        + '<table> <tr><th>Call Follow-up date</th></tr>'
        + '<tr>'
        + '<td>' + call_followup + '</td>'
        + '</tr>'
        + '</table>'
        + '<b> No Voice Mail</b>' + '</li>');
      $("form[name='callstatus_form'] #ccm_content_title option:last").prop("selected", "selected").change();

      if (sub_module == 'care-plan-development') {
        goToNextStep("patient-datap-tab");
      } else {
        goToNextStep("start-tab");
        getFollowupList($('#patient_id').val(), $("form[name='callstatus_form'] input[name='module_id']").val());
      }
    } else if ($("form[name='callstatus_form'] input[name$='call_status']:checked").val() == 2 && $("#answer").val() == 1) {
      var call_followup = $("#call_followup_date").val();
      if (call_followup != '') {
        var datePart = call_followup.match(/\d+/g),
          year = datePart[0], // get only two digits
          month = datePart[1],
          day = datePart[2];
        var call_followup = month + '-' + day + '-' + year;
      }
      $("#history_list ul").prepend('<li><h5>Call Not Answered (' + strDate + ')</h5>'
        + '<table> <tr><th>Call Follow-up date</th></tr>'
        + '<tr>'
        + '<td>' + call_followup + '</td>'
        + '</tr>'
        + '</table>'
        + '<b>Left Voice Mail : </b>' + $("#voice_mail_template").val() + '</li>');
      $("form[name='callstatus_form'] #ccm_content_title option:last").prop("selected", "selected").change();

      if (sub_module == 'care-plan-development') {
        goToNextStep("patient-datap-tab");
      } else {
        goToNextStep("start-tab");
        getFollowupList($('#patient_id').val(), $("form[name='callstatus_form'] input[name='module_id']").val());
      }
    } else if ($("form[name='callstatus_form'] input[name$='call_status']:checked").val() == 1 && $("form[name='callstatus_form'] input[name$='call_continue_status']:checked").val() == 0) {
      $("form[name='callstatus_form'] #ccm_content_title option:last").prop("selected", "selected").change();
      var answer_followup_d = $("#answer_followup_date").val();
      if (answer_followup_d != '') {
        var datePart = answer_followup_d.match(/\d+/g),
          year = datePart[0], // get only two digits
          month = datePart[1],
          day = datePart[2];
        var answer_followup_d = month + '-' + day + '-' + year;
      }
      var answer_followup_time = $("#answer_followup_time").val();
      var inputEle = document.getElementById('answer_followup_time');

      if (answer_followup_time != '') {
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
        var inputEle = hours + ':' + minutes + ' ' + meridian;
      } else {
        var inputEle = '';
      }
      $("#history_list ul").prepend('<li><h5>Call Answered (' + strDate + ') </h5>'
        + '<table> <tr><th>Call Continue Status</th><th>Call Follow-up date</th><th>Call Follow-up Time</th></tr>'
        + '<tr>'
        + '<td>No</td>'
        + '<td>' + answer_followup_d + '</td>'
        + '<td>' + inputEle + '</td>'
        + '</tr>'
        + '</table>'
        + '<b> Call Script:</b>' + $("#call_answer_template").val() + '</li>');
      if (sub_module == 'care-plan-development') {
        goToNextStep("patient-datap-tab");
      } else {
        goToNextStep("start-tab");
        getFollowupList($('#patient_id').val(), $("form[name='callstatus_form'] input[name='module_id']").val());
      }
    } else {
      $("#history_list ul").prepend('<li><h5>Call Answered (' + strDate + ') </h5>'
        + '<table> <tr><th>Call Continue Status</th></tr>'
        + '<tr>'
        + '<td>Yes</td>'
        + '</tr>'
        + '</table>'
        + '<b> Call Script:</b>' + $("#call_answer_template").val() + '</li>');
      if (sub_module == 'care-plan-development') {
        goToNextStep("verification-tab");
      } else {
        goToNextStep("ccm-verification-icon-tab");
        getFollowupList($('#patient_id').val(), $("form[name='callstatus_form'] input[name='module_id']").val());
      }
    }
    setTimeout(function () {
      $("form[name='callstatus_form'] #success-alert").fadeOut();
    }, 5000);
    var timer_paused = $("form[name='callstatus_form'] input[name='end_time']").val();
    $("#timer_start").val(timer_paused);
    $("#timer_end").val(timer_paused);
    var table = $('#callwrap-list');
    table.DataTable().ajax.reload();
  }
};

var onCallClose = function (formObj, fields, response) {
  if (response.status == 200) {
    util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
    util.getToDoListData($("#patient_id").val(), $("form[name='call_close_form'] input[name='module_id']").val());
    //util.getDataCalender($("#patient_id").val(), $("form[name='call_close_form'] input[name='module_id']").val());
    $(".form_start_time").val(response.data.form_start_time);
    
    util.totalTimeSpentByCM();
    $("form[name='call_close_form'] .alert").show();
    var scrollPos = $(".main-content").offset().top;
    $(window).scrollTop(scrollPos);
    setTimeout(function () { $('.alert').fadeOut('fast'); }, 5000);
    if (sub_module == 'care-plan-development') {
      goToNextStep("call-wrapup-tab");
    } else {
      goToNextStep("ccm-call-wrapup-icon-tab");
      getFollowupList($('#patient_id').val(), $("form[name='call_close_form'] input[name='module_id']").val());
    }
    var timer_paused = $("form[name='call_close_form'] input[name='end_time']").val();
    $("#timer_start").val(timer_paused);
  }
};

//  var onCallWrapUp = function (formObj, fields, response) { //moved to common js

//   var checkbox_1 = $('form[name="callwrapup_form"] #emr_entry_completed ').prop("checked");
//   var checkbox_2 = $('form[name="callwrapup_form"] #schedule_office_appointment ').prop("checked");
//   var checkbox_3 = $('form[name="callwrapup_form"] #resources_for_medication ').prop("checked");
//   var checkbox_4 = $('form[name="callwrapup_form"] #medical_renewal ').prop("checked");
//   var checkbox_5 = $('form[name="callwrapup_form"] #called_office_patientbehalf ').prop("checked");
//   var checkbox_6 = $('form[name="callwrapup_form"] #referral_support ').prop("checked");
//   var checkbox_7 = $('form[name="callwrapup_form"] #no_other_services ').prop("checked"); 

//   // alert(checkbox_1);

//   if( ((checkbox_1 == true || checkbox_2 == true ||  checkbox_3 == true || checkbox_4 == true || checkbox_5 == true || checkbox_6 == true || checkbox_7 == true )) )
//   //  && (response.status == 200) )       
//   {

//     $('form[name="callwrapup_form"] #checkboxerror' ).css('display', 'none');

//           if (response.status == 200) {
//               util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
//               var timer_paused = $("form[name='callwrapup_form'] input[name='end_time']").val();
//               $("#timer_start").val(timer_paused);
//               var patient_id = $("#hidden_id").val();
//               var module_id = $("#callwrapup_form input[name='module_id']").val();
//               var year = (new Date).getFullYear(); 
//               var month = (new Date).getMonth() + 1; //add +1 for current mnth
//               util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
//               util.getToDoListData($("#patient_id").val(), $("form[name='callwrapup_form'] input[name='module_id']").val());
//               //util.getDataCalender($("#patient_id").val(), $("form[name='callwrapup_form'] input[name='module_id']").val());
//               if (sub_module == 'care-plan-development') {

//                   util.updateFinalizeDate($("input[name='patient_id']").val(), $("input[name='module_id']").val());
//                   util.totalTimeSpentByCM();
//                   $("form[name='callwrapup_form'] .alert-success").show();
//                   var scrollPos = $(".main-content").offset().top;
//                   $(window).scrollTop(scrollPos);
//                   setTimeout(function () { $("form[name='callwrapup_form'] .alert").fadeOut(); }, 5000);
//                    //------ashwini ------//
//                     var table = $('#callwrap-list');
//                     table.DataTable().ajax.reload();
//                     $("#callwrapup_form")[0].reset();
//                     var sPageURL = window.location.pathname;
//                     parts = sPageURL.split("/");
//                     var patientId = parts[parts.length - 1];
//                     var preparationNotesFormPopulateURL = URL_POPULATE_RESEARCH_FOLLOWUP_PREPARATION_NOTES + "/" + patientId + "/current";
//                     ccmMonthlyMonitoring.populateForm(patientId, preparationNotesFormPopulateURL); //please donot change sept 12 2022
//               } else {

//                   $("#callwrapform-success-alert").show();
//                   var table = $('#callwrap-list');
//                   table.DataTable().ajax.reload();
//                   // $("#callwrapup_form")[0].reset();
//                   var scrollPos = $(".main-content").offset().top;
//                   $(window).scrollTop(scrollPos);
//                   setTimeout(function () { $("#callwrapform-success-alert").fadeOut('fast'); }, 5000);
//                   goToNextStep("followup-step");
//                   $(".remove-icons").trigger("click");
//                   $(".additionalfeilds").remove();
//                   var sPageURL = window.location.pathname;
//                   parts = sPageURL.split("/");
//                   var patientId = parts[parts.length - 1];
//                   var preparationNotesFormPopulateURL = URL_POPULATE_RESEARCH_FOLLOWUP_PREPARATION_NOTES + "/" + patientId + "/current";
//                   // carePlanDevelopment.populateForm(patientId, preparationNotesFormPopulateURL);
//                   ccmMonthlyMonitoring.populateForm(patientId, preparationNotesFormPopulateURL);  //please donot change sept 12 2022
//               }
//           }else{
//             return false;
//           }
//     }
//     else{
//       $('form[name="callwrapup_form"] #checkboxerror' ).css('display', 'block');
//       return false;
//     }
// };


var onCallWrapUp = function (formObj, fields, response) { //moved to common js //final changes
  var patient_id = fields.values.uid;
  var module_id = fields.values.module_id;
  if (response.status == 200) {
    util.updateTimer(patient_id, $("input[name='billable']").val(), module_id);
    var timer_paused = $("form[name='callwrapup_form'] input[name='end_time']").val();
    $("#timer_start").val(timer_paused);
    $(".form_start_time").val(response.data.form_start_time);
    
    var year = (new Date).getFullYear();
    var month = (new Date).getMonth() + 1; //add +1 for current mnth
    util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
    util.getToDoListData(patient_id, module_id);
    //util.getDataCalender(patient_id, module_id);
    if (sub_module == 'care-plan-development') {
      util.updateFinalizeDate(patient_id, module_id);
      util.totalTimeSpentByCM();
      $("form[name='callwrapup_form'] .alert-success").show();
      var scrollPos = $(".main-content").offset().top;
      $(window).scrollTop(scrollPos);
      setTimeout(function () { $("form[name='callwrapup_form'] .alert").fadeOut(); }, 5000);
      //------ashwini ------//
      // var table = $('#callwrap-list');
      // table.DataTable().ajax.reload();
      // $("#callwrapup_form")[0].reset();
      var preparationNotesFormPopulateURL = URL_POPULATE_RESEARCH_FOLLOWUP_PREPARATION_NOTES + "/" + patient_id + "/current";
      populateForm(patient_id, preparationNotesFormPopulateURL); //please donot change sept 12 2022
    } else {
      // util.updateBillableNonBillableAndTickingTimer(patient_id, module_id, 1);
      $("#callwrapform-success-alert").show();
      var table = $('#callwrap-list');
      table.DataTable().ajax.reload();
      // $("#callwrapup_form")[0].reset();
      var scrollPos = $(".main-content").offset().top;
      $(window).scrollTop(scrollPos);
      setTimeout(function () { $("#callwrapform-success-alert").fadeOut('fast'); }, 5000);

      $(".remove-icons").trigger("click");
      $(".additionalfeilds").remove();
      var preparationNotesFormPopulateURL = URL_POPULATE_RESEARCH_FOLLOWUP_PREPARATION_NOTES + "/" + patient_id + "/current";
      // carePlanDevelopment.populateForm(patient_id, preparationNotesFormPopulateURL);
      ccmMonthlyMonitoring.populateForm(patient_id, preparationNotesFormPopulateURL);  //please donot change sept 12 2022
      //location.reload(true);
      //alert(window.location.origin+sPageURL+'#3');
      window.location.replace(window.location.origin + sPageURL + '#3');

      goToNextStep("followup-step");
    }
  } else {
    return false;
  }
};





var goToNextStep = function (id) {
  setTimeout(function () {
    $('#' + id).click();
  }, 5000);
}

var getFollowupList = function (patient_id = null, module_id = null) {
  var columns = [
    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
    { data: 'task_notes', name: 'task_notes' },
    { data: 'task', name: 'task' },
    {
      data: 'notes', name: 'notes', render: function (data, type, full, meta) {
        if (data != '' && data != 'NULL' && data != undefined) {
          return full['notes'] + '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' + full['id'] + '" data-original-title="Edit" class="editfollowupnotes" title="Edit"><i class=" editform i-Pen-4"></i></a> ';
        } else { return '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' + full['id'] + '" data-original-title="Edit" class="editfollowupnotes" title="Edit"><i class=" editform i-Pen-4"></i></a>'; }
      }
    },
    {
      data: 'tt', type: 'date-dd-mm-yyyy',
      "render": function (value) {
        if (value === null) return "";
        return util.viewsDateFormat(value);
      }
    },
    {
      data: 'task_time', name: 'task_time',
      render: function (data, type, full, meta) {
        if (data != '' && data != 'NULL' && data != undefined) {
          return full['task_time'];
        } else {
          return '';
        }
      }
    },
    { data: 'action', name: 'action', orderable: false, searchable: false },
    {
      data: 'task_completed_at', type: 'date-dd-mm-yyyy h:i:s', name: 'task_completed_at', "render": function (value) {
        if (value === null) return "";
        return util.viewsDateFormat(value);
      }
    },
    {
      data: null,
      render: function (data, type, full, meta) {
        if (data != '' && data != 'NULL' && data != undefined) {
          return full['f_name'] + ' ' + full['l_name'];
        } else {
          return '';
        }
      }
    },
  ];
  var url = "/ccm/patient-followup-task/" + patient_id + '/' + module_id + "/followuplist";
  util.renderDataTable('task-list', url, columns, "{{ asset('') }}");
}

var copyPreviousMonthDataToThisMonth = function (patient_id, module_id) {
  if (!patient_id) {
    return;
  }
  if (!module_id) {
    return;
  }

  axios({
    method: "GET",
    url: `/ccm/ajax/copy-previous-month-data-to-this-month/${patient_id}/${module_id}/previous-month-data`,
  }).then(function (response) {
    // if (module == 'care-plan-development') {
    //   carePlanDevelopment.callCPDInitFunctions();
    // } else {
    //   callMonthllyMonitoringInitFunctions();
    // }
  }).catch(function (error) {
    console.error(error, error.response);
  });
}

var hideShowNKDAMsg = function (countid, msgid) {
  // var formName = $("#" + countid).parents("form").attr("id");
  var d = $("#" + countid).val();
  if (d > 0) {
    $("#" + msgid).show();
    setTimeout(function () {
      $("#" + msgid).hide();
    }, 5000);
  }
}

var noallergiescheck = function (formsObj) {
  var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
  var allergy_status = $("form[name='" + formName + "'] input[name ='allergy_status']:checked").val();
  if (allergy_status) {
    $("form[name='" + formName + "'] input[name='specify']").val("");
    $("form[name='" + formName + "'] input[name='type_of_reactions']").val("");
    $("form[name='" + formName + "'] input[name='severity']").val("");
    $("form[name='" + formName + "'] input[name='course_of_treatment']").val("");
    $("form[name='" + formName + "'] textarea[name='notes']").val("");

    $("form[name='" + formName + "'] input[name='specify']").attr("disabled", 'disabled');
    $("form[name='" + formName + "'] input[name='type_of_reactions']").prop("disabled", true);
    $("form[name='" + formName + "'] input[name='severity']").prop("disabled", true);
    $("form[name='" + formName + "'] input[name='course_of_treatment']").prop("disabled", true);
    $("form[name='" + formName + "'] textarea[name='notes']").prop("disabled", true);
  } else {
    $("form[name='" + formName + "']")[0].reset();
    $("form[name='" + formName + "'] input[name='specify']").prop("disabled", false);
    $("form[name='" + formName + "'] input[name='type_of_reactions']").prop("disabled", false);
    $("form[name='" + formName + "'] input[name='severity']").prop("disabled", false);
    $("form[name='" + formName + "'] input[name='course_of_treatment']").prop("disabled", false);
    $("form[name='" + formName + "'] textarea[name='notes']").prop("disabled", false);
  }
}

$('#enrolledservice_modules').on('change', function (event) {
  $("form[name='active_deactive_form'] #date_value").hide();
  $("form[name='active_deactive_form'] #fromdate").hide();
  $("form[name='active_deactive_form'] #todate").hide();
  var moduleId = $("#enrolledservice_modules").val();
  var patientId = ($("form[name='active_deactive_form'] input[name='patientid']").val() != '') ? $("form[name='active_deactive_form'] input[name='patientid']").val() : $("form[name='active_deactive_form'] input[name='patient_id']").val();
  $.ajax({
    type: 'GET',
    url: '/patients/patient-module-status/' + patientId + '/' + moduleId + '/patient-module-status',
    success: function (response) {
      var status = response[0].status;
      if (status == 0) {
        $("form[name='active_deactive_form'] #role1").show();
        $("form[name='active_deactive_form'] #role0").hide();
        $("form[name='active_deactive_form'] #role2").show();
        $("form[name='active_deactive_form'] #role3").show();
      }
      if (status == 1) {
        $("form[name='active_deactive_form'] #role1").hide();
        $("form[name='active_deactive_form'] #role0").show();
        $("form[name='active_deactive_form'] #role2").show();
        $("form[name='active_deactive_form'] #role3").show();
      }
      if (status == 2) {
        // $("form[name='active_deactive_form'] #status-title").text('Activate/Suspend Or Deceased Patient');
        $("form[name='active_deactive_form'] #role1").show();
        $("form[name='active_deactive_form'] #role0").show();
        $("form[name='active_deactive_form'] #role2").hide();
        $("form[name='active_deactive_form'] #role3").show();
      }
      if (status == 3) {
        $("form[name='active_deactive_form'] #role1").show();
        $("form[name='active_deactive_form'] #role0").show();
        $("form[name='active_deactive_form'] #role2").show();
        $("form[name='active_deactive_form'] #role3").hide();
      }
    }
  });
});



function newcheckquery2(value) {
  if (value == 0 || value == 1) {
    $("#ignore").show();
  } else {
    $("#ignore").hide();
  }
  if (value == 0) {
    $("#next_month_call_div").show();
  }
}

/**
 * Initialize the form
 */
var init = function () {
  var sPageURL = window.location.pathname;
  parts = sPageURL.split("/"),
    id = parts[parts.length - 1];
  var patientId = id;
  if ($.isNumeric(patientId)) {
    datapopulate(patientId);
  }
  //if($("form[name='"+ formName +"'] .timearr").length < 1){
  //	console.log('i am stuck here'+formName);

  $("form").append("<input type='hidden' name=timearr[form_start_time] class='timearr form_start_time'><input type='hidden' name=timearr['form_save_time'] class='form_save_time'><input type='hidden' name=timearr['pause_start_time']><input type='hidden' name=timearr['pause_end_time']><input type='hidden' name=timearr['extra_time']>");
  //}

  // var dropdown = document.getElementById("provider_subtype_id");
  // dropdown.onchange = function(event){
  $('#provider_subtype_id').on('change', function (event) {
    var subtype_id = $("#provider_subtype_id").val();
    alert(subtype_id);
    if (subtype_id == '0') {
      $("#specialist_name").show();
    } else {
      $("#specialist_name").hide();
    }
  });
  // var dropdown = document.getElementById("medication_med_id");
  // dropdown.onchange = function(event){
  /*$('#medication_med_id').on('change', function (event) {
    // if (dropdown.value === '0') {
    if (($('#medication_med_id').val()) === '0') {  
      //$("#med_name").show();
    } else {
      //$("#med_name").hide();
    }
  });*/

  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
  });

  $("form[name='enrolleddateform']")[0].reset();
  $("form[name='active_deactive_form']")[0].reset();
  $("form[name='active_deactive_form'] #date_value").hide();
  $("form[name='active_deactive_form'] #fromdate").hide();
  $("form[name='active_deactive_form'] #todate").hide();
  $("form[name='active_deactive_form'] #deactivation_drpdwn_div").hide();

  form.ajaxForm("enrolleddateform", onSaveenrolleddateform, function () {
    var selecteddate = $('#date_enrolled').val();
    var newselecteddate = new Date(selecteddate);
    var currentdate = new Date();
    if (newselecteddate > currentdate) {
      $("#messagingbody").show();
      var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Patient Enrolled Date must be less than or equals to Current Date!</strong></div>';
      $("#messagingbody").html(txt);
    } else {
      $("#time-container").val(AppStopwatch.pauseClock);
      var timer_start = $("#timer_start").val();
      var timer_paused = $("#time-container").text();
      $("form[name='enrolleddateform'] input[name='start_time']").val(timer_start);
      $("form[name='enrolleddateform'] input[name='end_time']").val(timer_paused);
      // $("#timer_start").val(timer_paused);
      $("#timer_end").val(timer_paused);
      $("#time-container").val(AppStopwatch.startClock);
      return true;
    }
  });

  form.ajaxForm("active_deactive_form", onSaveActiveDeactive, function () {
    var checkforworklist = $("form[name='active_deactive_form'] #worklistclick").val();
    if (checkforworklist != '1') {
      $("#time-container").val(AppStopwatch.pauseClock);
    }
    var timer_start = $("#timer_start").val();
    var timer_paused = $("#time-container").text();
    $("#active_deactive_form input[name='start_time']").val(timer_start);
    $("#active_deactive_form input[name='end_time']").val(timer_paused);
    // $("#timer_start").val(timer_paused);
    $("#timer_end").val(timer_paused);
    if (checkforworklist != '1') {
      $("#time-container").val(AppStopwatch.startClock);
    }
    var formdate = $('form[name="active_deactive_form"] #fromdate').val();
    var todate = $('form[name="active_deactive_form"] #todate').val();
    var eDate = new Date(todate);
    var sDate = new Date(formdate);
    if (todate != '' && todate != null) {
      if (formdate != '' && todate != '' && sDate > eDate) {
        alert("Please ensure that the Enrolled To Date is greater than or equal to the Enrolled From Date.");
        return false;
      }
    }
    return true;
  });

  function deleteDiagnosis(id) {
    var result = confirm("Are you sure you want to delete this data ?");
    var billable = $("input[name='billable']").val();
    if (result) {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type: 'post',
        url: '/ccm/delete-diagnosis',
        data: { "id": id, "billable": billable },
        success: function (response) {
          util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
          renderDiagnosisTable();
          renderReviewDiagnosisTable();
        },
      });
    }
  }
};

//add for device(ashwinni mali 0202)
var renderDeviceTableData = function () {
  var url = baseURL + "patients/device-deviceslist/" + patient_id;
  //alert(url);
  var table2 = util.renderDataTable('devices_data_list', url, devicesColumns, baseURL);
  return table2;
};

/**
 * Export the module
 */
window.ccmcpdcommonJS = {
  init: init,
  onErrors: onErrors,
  onResult: onResult,
  // onSubmit: onSubmit
  onSaveActiveDeactive: onSaveActiveDeactive,
  onActiveDeactiveClick: onActiveDeactiveClick,
  callMonthllyMonitoringInitFunctions: callMonthllyMonitoringInitFunctions,
  onCallHippa: onCallHippa,
  onCallStatus: onCallStatus,
  onCallClose: onCallClose,
  onCallWrapUp: onCallWrapUp,
  getFollowupList: getFollowupList,
  copyPreviousMonthDataToThisMonth: copyPreviousMonthDataToThisMonth,
  hideShowNKDAMsg: hideShowNKDAMsg,
  noallergiescheck: noallergiescheck,
  newcheckquery2: newcheckquery2,
  renderDeviceTableData: renderDeviceTableData
};