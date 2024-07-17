<script>
import axios from "axios";
import moment from "moment";
import { populateForm, getFields } from "./Form.vue";

export let getStageID = async (moduleId, componentId, stageName) => {
  try {
    let response = await axios.get(
      `/get_stage_id/${moduleId}/${componentId}/${stageName}`
    );
    let stageId = response.data.stageID;
    return stageId;
  } catch (error) {
    throw new Error("Failed to fetch Patient Data stageID");
  }
};

export let getStepID = async (moduleId, componentId, stageId, stepName) => {
  try {
    let response = await axios.get(
      `/get_step_id/${moduleId}/${componentId}/${stageId}/${stepName}`
    );
    let stepId = response.data.stepID;
    return stepId;
  } catch (error) {
    throw new Error("Failed to fetch stageID");
  }
};

export const updateTimer = (patientID, billable, moduleId) => {
  console.log("in new updateTimer function");
  axios({
    method: "GET",
    url: `/system/get-updated-time/${patientID}/${billable}/${moduleId}/total-time`,
  })
    .then(function (response) {
      if (billable == 1) {
        $(".last_time_spend").html(response["billable_time"]);
      } else {
        $(".non_billabel_last_time_spend").html(response["non_billable_time"]);
      }
      $("#time-containers").html(response["total_time"]);
    })
    .catch(function (error) {
      console.error(error, error.response);
    });
};

export var getPatientDetails = function (patientId, moduleId) {
  axios({
    method: "GET",
    url: `/patients/patient-details/${patientId}/${moduleId}/patient-details`,
  })
    .then(function (response) {
      $(".enrolledservice_modules").html("");
      var enr = response.data.patient_services.length;
      for (var i = 0; i < enr; i++) {
        var module_id = response.data.patient_services[i].module_id;
        $(".enrolledservice_modules").append(
          `<option value="${module_id}">${response.data.patient_services[i].module.module}</option>`
        );
      }

      //
      var count_enroll_Services = response.data.patient_services.length;
      var enroll_services = [];
      var enroll_services_status = [];
      for (var i = 0; i < count_enroll_Services; i++) {
        var patient_enroll_services_status = "";
        var enroll_services_status = response.data.patient_services[i].status;
        if (enroll_services_status == 0) {
          patient_enroll_services_status =
            '<i class="i-Closee i-Close" id="isuspended" data-toggle="tooltip" data-placement="top" data-original-title="Suspended"></i>';
        } else if (enroll_services_status == 1) {
          patient_enroll_services_status =
            '<i class="i-Yess i-Yes" id="iactive" data-toggle="tooltip" data-placement="top" data-original-title="Activate"></i>';
        } else if (enroll_services_status == 2) {
          patient_enroll_services_status =
            '<i class="i-Closee i-Close" id="ideactive" data-toggle="tooltip" data-placement="top" data-original-title="Deactivate"></i>';
        } else if (enroll_services_status == 3) {
          patient_enroll_services_status =
            '<i class="i-Closee i-Close" id="ideceased" data-toggle="tooltip" data-placement="top" data-original-title="Deceased"></i>';
        }
        enroll_services[i] =
          response.data.patient_services[i].module.module +
          "-" +
          patient_enroll_services_status;
        if (response.data.patient_services[i].module.module == "RPM") {
          $("#add_patient_devices").show();
        }
      }
      $(".patient_enroll_services").html(enroll_services.toString());
      //patient profile picture
      var path = response.data.patient[0].profile_img;
      var default_img_path =
        window.location.origin + "/assets/images/faces/avatar.png";
      var img = "";
      if (path) {
        img =
          "<img src='" + path + "' class='user-image' style='width: 60px;' />";
      } else {
        img =
          "<img src='" +
          default_img_path +
          "' class='user-image' style='width: 60px;' />";
      }
      $(".patient_img").html(img);

      //second column
      var fname = "";
      if (response.data.patient[0].fname != "") {
        var first_name = response.data.patient[0].fname;
        fname = first_name.toLowerCase().replace(/\b[a-z]/g, function (letter) {
          return letter.toUpperCase();
        });
      }
      var lname = "";
      if (response.data.patient[0].lname != "") {
        var last_name = response.data.patient[0].lname;
        lname = last_name.toLowerCase().replace(/\b[a-z]/g, function (letter) {
          return letter.toUpperCase();
        });
      }
      $(".patient_name").text(fname + " " + lname);

      var gender;
      if (response.data.gender == "1") {
        gender = "Female /";
      } else if (response.data.gender == "2") {
        gender = "Male /";
      } else {
        gender = "";
      }
      var mmddyydob = moment(response.data.patient[0].dob).format("MM-DD-YYYY");
      $(".patient_gender").text(gender + "(" + response.data.age + ")");
      $(".patient_dob").text(mmddyydob);

      if (response.data.patient[0].fin_number != "") {
        var fin_number = response.data.patient[0].fin_number;
      }
      $(".patient_fin_number").text(fin_number);

      var military_status;
      if (response.data.military_status == "0") {
        military_status = "Veteran Service - Yes";
      } else if (response.data.military_status == "1") {
        military_status = "Veteran Service - No";
      } else {
        military_status = "Veteran Service - Unknown";
      }
      $(".patient_vateran_service").html(military_status);

      //third column
      var home_num = "";
      var mob = "";
      if (
        response.data.patient[0].home_number != "" &&
        response.data.patient[0].home_number != null
      ) {
        home_num = response.data.patient[0].home_number;
      }
      if (
        response.data.patient[0].mob != "" &&
        response.data.patient[0].mob != null
      ) {
        mob = response.data.patient[0].mob;
      }
      $(".patient_contact_num").text(mob + "|" + home_num);
      var consent_to_text;
      if (response.data.consent_to_text == "1") {
        consent_to_text = "Consent to text - Yes";
      } else {
        consent_to_text = "Consent to text - No";
      }
      $(".patient_concent_to_text").html(consent_to_text);

      var add1 = "";
      var add2 = "";
      var city = "";
      var state = "";
      var zipcode = "";
      if (response.data.add_1) {
        var add1 = response.data.add_1;
      }
      if (response.data.add_2) {
        var add2 = ", " + response.data.add_2;
      }
      if (response.data.city) {
        var city = ", " + response.data.city;
      }
      if (response.data.state) {
        var state = ", " + response.data.state;
      }
      if (response.data.zipcode) {
        var zipcode = ", " + response.data.zipcode;
      }
      $(".patient_address").text(add1 + add2 + state + city + zipcode);

      //forth column
      var practice = "";
      if (response.data.practice_name != "") {
        practice = response.data.practice_name;
      }
      $(".patient_practice").text(practice);

      var provider = "";
      if (response.data.provider_name != "") {
        provider = response.data.provider_name;
      }
      $(".patient_provider").html(provider);

      var practice_emr = "";
      if (response.data.practice_emr != "") {
        practice_emr = response.data.practice_emr;
      }
      $(".patient_practice_emr").html(practice_emr);

      var assignCM = "";
      if (response.data.caremanager_name != "") {
        var cm_name = response.data.caremanager_name;
        assignCM = cm_name.toLowerCase().replace(/\b[a-z]/g, function (letter) {
          return letter.toUpperCase();
        });
      }

      $(".patient_assign_cm").text(assignCM);

      //fift column
      if (response.data.allreadydevice == 0) {
        $(".patient_add_device").hide();
      } else {
        $(".patient_add_device").show();
      }
      $("#vateran_service_title").html(military_status);

      var patient_status = "";
      var fromDate = "";
      var toDate = "";
      var statusDate;
      if (response.data.suspended_to != "") {
        toDate = response.data.suspended_to;
      }
      if (response.data.suspended_from != "") {
        fromDate = response.data.suspended_from;
      }

      if (
        response.data.hasOwnProperty("patient_enroll_date") &&
        response.data.patient_enroll_date.length > 0
      ) {
        if (response.data.patient_enroll_date[0].status == 1) {
          patient_status = "Active";
          statusDate = "";
          $(".patient_service_active_id").html(
            '<a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass" data-target="#active-deactive" onClick=ccmcpdcommonJS.onActiveDeactiveClick(' +
              patientId +
              ',1)  id="active"><i class="i-Yess i-Yes" id="iactive" data-toggle="tooltip" data-placement="top" data-original-title="Activate"></i></a>'
          );
        } else if (response.data.patient_enroll_date[0].status == 0) {
          patient_status = "Suspended";
          statusDate = "From" + fromDate + "To" + toDate;
          $(".patient_service_active_id").html(
            '<a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass" data-target="#active-deactive"  onClick=ccmcpdcommonJS.onActiveDeactiveClick(' +
              patientId +
              ',0) id="suspend"><i class="i-Closee i-Close" id="isuspended" data-toggle="tooltip" data-placement="top" data-original-title="Suspended"></i></a>'
          );
        } else if (response.data.patient_enroll_date[0].status == 2) {
          patient_status = "Deactivated";
          statusDate = fromDate;
          $(".patient_service_active_id").html(
            '<a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass" data-target="#active-deactive" onClick=ccmcpdcommonJS.onActiveDeactiveClick(' +
              patientId +
              ',2) id="deactive"><i class="i-Closee i-Close" id="ideactive" data-toggle="tooltip" data-placement="top" data-original-title="Deactivate"></i></a>'
          );
        } else if (response.data.patient_enroll_date[0].status == 3) {
          patient_status = "Deceased";
          $(".patient_service_active_id").html(
            '<a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass"data-target="#active-deactive" onClick=ccmcpdcommonJS.onActiveDeactiveClick(' +
              patientId +
              ',3) id="deceased" ><i class="i-Closee i-Close" id="ideceased" data-toggle="tooltip" data-placement="top" data-original-title="Deceased"></i></a>'
          );
        }
        $(".patient_service_status").html(patient_status);
      }
      $(".patient_status_date").text(statusDate);

      var enroll_date = "";
      if (response.data.date_enrolled != "") {
        enroll_date = response.data.date_enrolled;
      } else {
        enroll_date = "";
      }
      $(".patient_enroll_date").text(enroll_date);

      var device_code = "";
      if (response.data.device_code != "") {
        device_code = response.data.device_code;
      }
      $(".patient_device_code").text(device_code);

      var device_status = "";
      if (response.data.device_status != "") {
        shipping_status = response.data.device_status;
        if (shipping_status == 1) {
          device_status = "Dispatched";
        } else if (shipping_status == 2) {
          device_status = "Delivered";
        } else if (shipping_status == 3) {
          device_status = "Pending";
        } else {
          device_status = "";
        }
      }

      $(".device_delivery_status").text(device_status);

      if (response.data.billable_time != "") {
        $("#btime").html(response.data.billable_time);
      }
      if (response.data.non_billabel_time != "") {
        $("#nbtime").html(response.data.non_billabel_time);
      }
      if (response.data.systolichigh != "") {
        var systolichigh = response.data.systolichigh;
      } else {
        var systolichigh = "";
      }
      $("#systolichigh").val(systolichigh);
      if (response.data.systoliclow != "") {
        var systoliclow = response.data.systoliclow;
      } else {
        var systoliclow = "";
      }
      $("#systoliclow").val(systoliclow);
      if (response.data.diastolichigh != "") {
        var diastolichigh = response.data.diastolichigh;
      } else {
        var diastolichigh = "";
      }
      $("#diastolichigh").val(diastolichigh);
      if (response.data.diastoliclow != "") {
        var diastoliclow = response.data.diastoliclow;
      } else {
        var diastoliclow = "";
      }
      $("#diastoliclow").val(diastoliclow);
      if (response.data.bpmhigh != "") {
        var bpmhigh = response.data.bpmhigh;
      } else {
        var bpmhigh = "";
      }
      $("#bpmhigh").val(bpmhigh);
      if (response.data.bpmlow != "") {
        var bpmlow = response.data.bpmlow;
      } else {
        var bpmlow = "";
      }
      $("#bpmlow").val(bpmlow);
      if (response.data.oxsathigh != "") {
        var oxsathigh = response.data.oxsathigh;
      } else {
        var oxsathigh = "";
      }
      $("#oxsathigh").val(oxsathigh);
      if (response.data.oxsatlow != "") {
        var oxsatlow = response.data.oxsatlow;
      } else {
        var oxsatlow = "";
      }
      $("#oxsatlow").val(oxsatlow);
      if (response.data.glucosehigh != "") {
        var glucosehigh = response.data.glucosehigh;
      } else {
        var glucosehigh = "";
      }
      $("#glucosehigh").val(glucosehigh);
      if (response.data.glucoselow != "") {
        var glucoselow = response.data.glucoselow;
      } else {
        var glucoselow = "";
      }
      $("#glucoselow").val(glucoselow);
      if (response.data.temperaturehigh != "") {
        var temperaturehigh = response.data.temperaturehigh;
      } else {
        var temperaturehigh = "";
      }
      $("#temperaturehigh").val(temperaturehigh);
      if (response.data.temperaturelow != "") {
        var temperaturelow = response.data.temperaturelow;
      } else {
        var temperaturelow = "";
      }
      $("#temperaturelow").val(temperaturelow);
      if (response.data.weighthigh != "") {
        var weighthigh = response.data.weighthigh;
      } else {
        var weighthigh = "";
      }
      $("#weighthigh").val(weighthigh);
      if (response.data.weightlow != "") {
        var weightlow = response.data.weightlow;
      } else {
        var weightlow = "";
      }
      $("#weightlow").val(weightlow);
      if (response.data.spirometerfevhigh != "") {
        var spirometerfevhigh = response.data.spirometerfevhigh;
      } else {
        var spirometerfevhigh = "";
      }
      $("#spirometerfevhigh").val(spirometerfevhigh);
      if (response.data.spirometerfevlow != "") {
        var spirometerfevlow = response.data.spirometerfevlow;
      } else {
        var spirometerfevlow = "";
      }
      $("#spirometerfevlow").val(spirometerfevlow);
      if (response.data.spirometerpefhigh != "") {
        var spirometerpefhigh = response.data.spirometerpefhigh;
      } else {
        var spirometerpefhigh = "";
      }
      $("#spirometerpefhigh").val(spirometerpefhigh);
      if (response.data.spirometerpeflow != "") {
        var spirometerpeflow = response.data.spirometerpeflow;
      } else {
        var spirometerpeflow = "";
      }
      $("#spirometerpeflow").val(spirometerpeflow);

      var personal_notes = "";
      if (response.data.personal_notes != "") {
        personal_notes = response.data.personal_notes; //response.data.personal_notes['static']['personal_notes'];
      }
      $("textarea#personal_notes").val(personal_notes);
      var research_study = "";
      if (response.data.research_study != "") {
        var research_study = response.data.research_study; //['static']['research_study'];
      }
      $("textarea#part_of_research_study").val(research_study);

      if ($("#contact_number").length) {
        // to  check id is exist on page(enrollment screen not  sending sms so not exist on screen)
        if (
          response.data.patient[0].mob != "" &&
          response.data.patient[0].mob != null &&
          response.data.patient[0].primary_cell_phone == "1"
        ) {
          var mob = response.data.patient[0].mob;
          var country_code = response.data.patient[0].country_code;
          $("#contact_number").append(
            `<option value="${country_code}${mob}">${mob} (P)</option>`
          );
        }
        if (
          response.data.patient[0].home_number != "" &&
          response.data.patient[0].home_number != null &&
          response.data.patient[0].secondary_cell_phone == "1"
        ) {
          var home_num = response.data.patient[0].home_number;
          var secondary_country_code =
            response.data.patient[0].secondary_country_code;
          $("#contact_number").append(
            `<option value="${secondary_country_code}${home_num}">${home_num} (S)</option>`
          );
        }
        if (
          response.data.patient[0].primary_cell_phone == "0" &&
          response.data.patient[0].secondary_cell_phone == "0"
        ) {
          var txt =
            "<div class='alert alert-warning mt-2 ml-2'>Patient Cell Number is Unavailable. Text Message cannot be sent to this patient.</div> <input type='hidden' name='error' value='Patient Cell Number is Unavailable. Text Message cannot be sent to this patient.'>";
          $("#patient_cell_number_unavailable").html(txt);
        }
      }

      if (
        response.data.patient_enroll_date[0].finalize_cpd == 0 &&
        response.data.billable == 0 &&
        response.data.enroll_in_rpm == 0 &&
        module == "care-plan-development"
      ) {
        $('input[name="billable"]').val(0);
      } else {
        $('input[name="billable"]').val(1);
      }
    })
    .catch(function (error) {
      console.error(error, error.response);
    });
};

export const CompletedCheck = (patient_id) => {
  var finaldata = [];
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  $.ajax({
    type: "POST",
    type: "post",
    url: "/ccm/CheckCompletedCheckbox",
    data: {
      finaldata: finaldata,
      patient_id: patient_id,
    },
    success: function (response) {
      for (var i = 0; i < response.length; i++) {
        if ($.trim(response[i]) == "0") {
          $("#familycheck").prop("checked", true);
        } else if ($.trim(response[i]) == "1") {
          $("#providercheck").prop("checked", true);
        } else if ($.trim(response[i]) == "2") {
          $("#diagnosischeck").prop("checked", true);
        } else if ($.trim(response[i]) == "3") {
          $("#allergycheck").prop("checked", true);
        } else if ($.trim(response[i]) == "4") {
          $("#medicationscheck").prop("checked", true);
        } else if ($.trim(response[i]) == "5") {
          $("#healthcarecheck").prop("checked", true);
        } else if ($.trim(response[i]) == "6") {
          $("#numbers_trackingcheck").prop("checked", true);
        }
      }
    },
  });
};

export let editData = async (url, formName, patientId) => {
  try {
    await populateForm(url, formName, patientId);
    var form = document.getElementById(formName);
    form.scrollIntoView({ behavior: "smooth" });
    var time = document.getElementById("page_landing_times").value;
    $(".timearr").val(time);
  } catch (error) {
    console.error("Error editing allergies:", error);
  }
};

export let deleteRecordDetails = (id, url, formName) => {
  const fields = getFields(formName);
  let formData = fields.values;
  let patientId = fields.values["patient_id"];
  let billable = fields.values["billable"];
  let moduleId = fields.values["module_id"];
  fields.values["id"] = id;

  return new Promise((resolve, reject) => {
    axios({
      method: "POST",
      url: url,
      data: formData,
    })
      .then(async function (response) {
        try {
          var time = response.data.form_start_time;
          document.getElementById("page_landing_times").value = time;
          $(".timearr").val(time);
          await updateTimer(patientId, billable, moduleId);
          resolve(response);
        } catch (error) {
          reject(error);
          console.error("Error updating timer or handling response:", error);
        }
      })
      .catch(function (error) {
        reject(error);
        console.error("Error deleting record:", error);
      });
  });
};
</script>
