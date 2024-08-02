<template>
  <div
    class="tab-pane fade show active"
    id="labs"
    role="tabpanel"
    aria-labelledby="labs-icon-pill"
  >
    <loading-spinner :isLoading="isLoading"></loading-spinner>
    <div class="card">
      <div class="card-header"><h4>Labs</h4></div>
      <form
        id="number_tracking_labs_form"
        name="number_tracking_labs_form"
        action="/ccm/care-plan-development-numbertracking-labs"
        method="post"
        @submit.prevent
      >
        <div
          class="labs-msg alert"
          :class="formErrors ? 'alert-danger' : 'alert-success'"
          :style="{ display: showLabsAlert ? 'block' : 'none' }"
        >
          <button type="button" class="close" data-dismiss="alert">x</button>
          <strong>{{ alertMsg }}</strong>
        </div>
        <div class="col-md-12">
          <input type="hidden" name="_token" :value="token" />
          <input type="hidden" name="uid" :value="patientId" />
          <input type="hidden" name="patient_id" :value="patientId" />
          <input type="hidden" name="start_time" value="00:00:00" />
          <input type="hidden" name="end_time" value="00:00:00" />
          <input type="hidden" name="module_id" :value="moduleId" />
          <input type="hidden" name="module_name" :value="module_name" />
          <input type="hidden" name="component_id" :value="componentId" />
          <input type="hidden" name="component_name" :value="component_name" />
          <input type="hidden" name="stage_id" :value="stageId" />
          <input type="hidden" name="step_id" :value="labsStepId" />
          <input
            type="hidden"
            name="form_name"
            value="number_tracking_labs_form"
          />
          <input type="hidden" name="billable" value="1" />
          <input
            type="hidden"
            name="timearr[form_start_time]"
            class="timearr form_start_time"
          />
          <input
            type="hidden"
            name="editform"
            id="editform"
            :value="editform"
          />
          <input type="hidden" name="olddate" id="olddate" :value="olddate" />
          <input type="hidden" name="oldlab" id="oldlab" :value="oldlab" />
          <input
            type="hidden"
            name="labdateexist"
            id="labdateexist"
            :value="labdateexist"
          />
          <div class="form-row">
            <div class="col-md-4 form-group mb-3">
              <label>Labs<span class="error">*</span> :</label><br />
              <select
                name="lab[]"
                class="custom-select show-tick select2 col-md-10"
                id="lab"
                @change="onLabchange"
                v-model="selectedLabs"
              >
                <option value="">Select Lab</option>
                <option :key="0" :value="0">Other</option>
                <option v-for="lab in labs" :key="lab.id" :value="lab.id">
                  {{ lab.description }}
                </option>
              </select>
              <div class="invalid-feedback"></div>
            </div>
            <div class="col-md-4 form-group mb-3">
              <label for="labdate">Date<span class="error">*</span> :</label>
              <input
                type="date"
                name="labdate[]"
                id="labdate"
                class="form-control"
                v-model="labDate"
                data-date-format="MM/DD/YYYY"
              />
              <div class="invalid-feedback"></div>
            </div>
          </div>
          <div v-html="labParams" class="form-row"></div>
        </div>
        <div class="card-footer">
          <div class="mc-footer">
            <div class="row">
              <div class="col-lg-12 text-right">
                <button
                  type="submit"
                  class="btn btn-primary m-1"
                  id="save_number_tracking_labs_form"
                  @click="submiLabsHealthDataForm"
                >
                  Save
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="alert alert-danger" id="danger-alert" style="display: none">
          <button type="button" class="close" data-dismiss="alert">x</button>
          <strong> Please Fill All mandatory Fields! </strong
          ><span id="text"></span>
        </div>
        <div
          class="labs-msg alert"
          :class="formErrors ? 'alert-danger' : 'alert-success'"
          :style="{ display: showLabsAlert ? 'block' : 'none' }"
        >
          <button type="button" class="close" data-dismiss="alert">x</button>
          <strong>{{ alertMsg }}</strong>
        </div>
      </form>

      <div class="separator-breadcrumb border-top"></div>
      <div class="row">
        <div class="col-12">
          <AgGridTable :rowData="labsRowData" :columnDefs="columnDefs" />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import {
  ref,
  watch,
  onBeforeMount,
  onMounted,
  AgGridTable,
} from "../../commonImports";
import axios from "axios";
import moment from "moment";
import { ajaxForm } from "../../Utility/Form.vue";
import {
  getStepID,
  // updateTimer,
  editData,
  deleteRecordDetails,
} from "../../Utility/CommonFunctions.vue";
export default {
  props: {
    patientId: Number,
    moduleId: Number,
    componentId: Number,
    stageId: Number,
  },
  components: {
    AgGridTable,
  },
  setup(props) {
    const showLabsAlert = ref(false);
    const labsStepId = ref(0);
    const labsTime = ref(null);
    const labs = ref([]);
    const labParams = ref("");
    const formErrors = ref(false);
    let alertMsg = ref("");
    const selectedLabs = ref("");
    const labDate = ref("");
    const editform = ref("");
    const olddate = ref("");
    const oldlab = ref("");
    const labdateexist = ref("");
    const labsRowData = ref([]);
    const year = new Date().getFullYear();
    const month = new Date().getMonth() + 1;
    const formName = "number_tracking_labs_form";
    let isLoading = ref(false);
    const module_name = ref("");
    const component_name = ref("");
    let token = document.head.querySelector('meta[name="csrf-token"]').content;
    const columnDefs = ref([
      {
        headerName: "Sr. No.",
        valueGetter: "node.rowIndex + 1",
        initialWidth: 20,
      },
      { headerName: "Lab", field: "description", filter: true },
      {
        headerName: "Lab Date",
        field: "lab_date",
        cellRenderer: (params) => {
            const dateStr = params.value;
            if (!dateStr) return null;
            const date = new Date(dateStr);
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
            const day = String(date.getDate()).padStart(2, '0');
            const year = date.getFullYear();
            return `${month}-${day}-${year}`;      
        }
      },
      { headerName: "Reading", field: "labparameter" },
      { headerName: "Notes", field: "notes" },
      {
        headerName: "Action",
        field: "action",
        cellRenderer: function (params) {
          const row = params.data;
          return row && row.action ? row.action : "";
        },
      },
    ]);

    const fetchPatientLabsList = async () => {
      try {
        isLoading.value = true;
        const sPageURL = window.location.pathname;
        const parts = sPageURL.split("/");
        const mm = parts[parts.length - 2];
        const response = await fetch(
          `/ccm/care-plan-development-labs-labslist/${props.patientId}?mm=${mm}`
        );
        if (!response.ok) {
          throw new Error("Failed to fetch labs list");
        }
        isLoading.value = false;
        const data = await response.json();
        labsRowData.value = data.data;
        
        module_name.value = parts[parts.length - 3];
        component_name.value = parts[parts.length - 2];
      } catch (error) {
        console.error("Error fetching labs list:", error);
        isLoading.value = false;
      }
    };

    let submiLabsHealthDataForm = async () => {
      isLoading.value = true;
      await ajaxForm(formName, handleResult);
      var form = $("#" + formName)[0];
      form.scrollIntoView({ behavior: "smooth" });
    };

    const handleResult = async (
      form,
      fields,
      response,
      successMessage = "Labs data saved successfully!"
    ) => {
      isLoading.value = false;
      if (response.status === 200) {
        await fetchPatientLabsList();
        showLabsAlert.value = true;
        formErrors.value = false;
        var form = $("#" + formName)[0];
        form.reset();
        $(form).find(":input").prop("disabled", false);
        labParams.value = "";
        labDate.value = "";
        selectedLabs.value = "";
        alertMsg.value = successMessage;
        form.scrollIntoView({ behavior: "smooth" });
        updateTimer(props.patientId, "1", props.moduleId);
        getPatientCareplanNotes(props.patientId, props.moduleId);
        getPatientPreviousMonthNotes(
          props.patientId,
          props.moduleId,
          month,
          year
        );
        $(".form_start_time").val(response.data.form_start_time);
        setTimeout(() => {
          showLabsAlert.value = false;
          var time = document.getElementById("page_landing_times").value;
          $(".timearr").val(time);
        }, 3000);
      } else if (response.status && response.status === 422) {
        let formValidationError = response.data.errors;
        for (const field in formValidationError) {
          if (
            Object.prototype.hasOwnProperty.call(formValidationError, field)
          ) {
            const errorMessages = formValidationError[field];
            if (field.includes("reading")) {
              $("#" + field.replaceAll(".", "-")).html(errorMessages);
              $("#" + field.replaceAll(".", "-")).show();
            }
            if (field.includes("high_val")) {
              $("#" + field.replaceAll(".", "-")).html(errorMessages);
              $("#" + field.replaceAll(".", "-")).show();
            }
          }
        }
      } else {
        formErrors.value = true;
        showLabsAlert.value = true;
        alertMsg.value = "Please Fill All mandatory Fields!";
        form.scrollIntoView({ behavior: "smooth" });
      }
    };

    const onLabchange = async () => {
      const labId = selectedLabs.value;
      axios.defaults.headers.common["X-CSRF-TOKEN"] = document.querySelector(
        'meta[name="csrf-token"]'
      ).content;
      try {
        const getLabResponse = await axios.post("/ccm/lab-param", {
          lab: labId,
        });
        labParams.value = getLabResponse.data;
        if (labId == "" || labId == null) {
          $("#labdate").attr("name", "labdate[]");
        } else {
          $("#labdate").attr("name", "labdate[" + labId + "][]");
        }
      } catch (error) {
        if (error.status && error.status === 422) {
          formErrors.value = error.responseJSON.errors;
        } else {
          console.error("Error getting lab params:", error);
        }
      }
    };

    const fetchLabs = async () => {
      try {
        await new Promise((resolve) => setTimeout(resolve, 2000));
        const response = await fetch(`/org/active-labs`);
        if (!response.ok) {
          throw new Error("Failed to fetch lab list");
        }
        const labsData = await response.json();
        labs.value = labsData;
      } catch (error) {
        console.error("Error fetching labs list:", error);
        isLoading.value = false;
      }
    };

    // let deleteLabs = async (date, patient_id, lab_test_id, labdateexist) => {
    //   labDate.value = date;
    //   if (window.confirm("Are you sure you want to delete this Health Data?")) {
    //     const deleteDataResponse = await deleteRecordDetails(
    //       lab_test_id,
    //       "/ccm/delete-lab",
    //       formName
    //     );
    //     if (deleteDataResponse.status === 200) {
    //       var msg = "Labs data deleted successfully!";
    //       await handleResult(formName, "", deleteDataResponse, msg);
    //     } else {
    //       console.error(
    //         "Deletion failed with status code:",
    //         deleteDataResponse.status
    //       );
    //     }
    //   }
    // };
    const deleteLabs = async (date, patient_id, lab_test_id, labdateexist) => {
      if (window.confirm("Are you sure you want to delete this Lab data?")) {
        const formData = {
          labid: lab_test_id,
          uid: props.patientId,
          patient_id: props.patientId,
          module_id: props.moduleId,
          component_id: props.componentId,
          stage_id: props.stageId,
          step_id: labsStepId.value,
          form_name: "number_tracking_labs_form",
          billable: 1,
          start_time: "00:00:00",
          end_time: "00:00:00",
          form_start_time: document.getElementById("page_landing_times").value,
          labdate: date,
          patientid: patient_id,
          labdateexist: labdateexist,
        };
        try {
          axios.defaults.headers.common["X-CSRF-TOKEN"] =
            document.querySelector('meta[name="csrf-token"]').content;
          const deleteServicesResponse = await axios.post(`/ccm/delete-lab`,formData);
          updateTimer(props.patientId, "1", props.moduleId);
          getPatientCareplanNotes(props.patientId, props.moduleId);
          getPatientPreviousMonthNotes(
            props.patientId,
            props.moduleId,
            month,
            year
          );
          $(".form_start_time").val(
            deleteServicesResponse.data.form_start_time
          );
          await fetchPatientLabsList();
          formErrors.value = false;
          const successMessage = "Labs data delete successfully!";
          alertMsg.value = successMessage;
          var form = $("#" + formName)[0];
          form.scrollIntoView({ behavior: "smooth" });
          showLabsAlert.value = true;
          document.getElementById("number_tracking_labs_form").reset();
          setTimeout(() => {
            showLabsAlert.value = false;
            var time = document.getElementById("page_landing_times").value;
            $(".timearr").val(time);
          }, 3000);
        } catch (error) {
          console.error("Error deletting record:", error);
        }
      }
    };

    const exposeDeleteLab = () => {
      window.deleteLabs = deleteLabs;
    };

    // let editlabsformnew = async (
    //   lab_date,
    //   patient_id,
    //   lab_test_id,
    //   lab_date_exist
    // ) => {
    //   editData(
    //     `/ccm/care-plan-development-populateLabs/${patient_id}/${lab_date}/${lab_test_id}/${lab_date_exist}`,
    //     formName,
    //     props.patientId
    //   );
    // };

    const editlabsformnew = async (
      lab_date,
      patient_id,
      lab_test_id,
      lab_date_exist
    ) => {
      try {
        isLoading.value = true;
        const response = await fetch(
          `/ccm/care-plan-development-populateLabs/${patient_id}/${lab_date}/${lab_test_id}/${lab_date_exist}`
        );
        if (!response.ok) {
          throw new Error("Failed to fetch labs details");
        }
        isLoading.value = false;
        const data = await response.json();
        const labs = data.number_tracking_labs_details.dynamic.lab;
        selectedLabs.value = lab_test_id;
        let dt = moment(lab_date, "MM-DD-YYYY").format("YYYY-MM-DD");
        labDate.value = dt;
        labParams.value = generateLabParams(lab_test_id, labs);
        editform.value = "edit";
        olddate.value = dt;
        oldlab.value = lab_test_id;
        labdateexist.value = dt;
        $("#labdate").attr("name", "labdate[" + lab_test_id + "][]");
      } catch (error) {
        console.error("Error fetching labs details:", error);
        isLoading.value = false;
      }
    };

    const generateLabParams = (lab, labParams) => {
      let params = "";
      let labNotes = "";
      labParams.forEach((value) => {
        if (value.parameter === null) {
          params += `<div class='col-md-6 mb-3'>`;
          // params += `<label>${value.parameter} <span class='error'>*</span></label>`;
          params += `<input type='hidden' name='lab_test_id[${lab}][]'  value='${value.lab_test_id}'>`;
          params += `<input type='hidden' name='lab_params_id[${lab}][]' value='${value.lab_test_parameter_id}'>`;
        } else if (value.parameter === "COVID-19") {
          params += `<div class='col-md-6 mb-3'>`;
          params += `<label>${value.parameter} <span class='error'>*</span></label>`;
          params += `<input type='hidden' name='lab_test_id[${lab}][]'  value='${value.lab_test_id}'>`;
          params += `<input type='hidden' name='lab_params_id[${lab}][]' value='${value.lab_test_parameter_id}'>`;

          params += `<div class='form-row'><div class='col-md-5'>`;
          params += `<select class='forms-element form-control mr-1 pl-3' name='reading[${lab}][]'>`;
          params += `<option value=''>Select Reading</option>`;
          params += `<option value='positive' ${
            value.reading === "positive" ? "selected" : ""
          }>Positive</option>`;
          params += `<option value='negative' ${
            value.reading === "negative" ? "selected" : ""
          }>Negative</option></select>`;
          params += `<div class='invalid-feedback'></div></div>`;
        } else {
          params += `<div class='col-md-6 mb-3'>`;
          params += `<label>${value.parameter} <span class='error'>*</span></label>`;
          params += `<input type='hidden' name='lab_test_id[${lab}][]'  value='${value.lab_test_id}'>`;
          params += `<input type='hidden' name='lab_params_id[${lab}][]' value='${value.lab_test_parameter_id}'>`;

          params += `<div class='form-row'><div class='col-md-5'>`;
          params += `<select class='forms-element form-control mr-1 pl-3 labreadingclass' name='reading[${lab}][]'>`;
          params += `<option value=''>Select Reading</option>`;
          params += `<option value='high' ${
            value.reading === "high" ? "selected" : ""
          }>High</option>`;
          params += `<option value='normal' ${
            value.reading === "normal" ? "selected" : ""
          }>Normal</option>`;
          params += `<option value='low' ${
            value.reading === "low" ? "selected" : ""
          }>Low</option>`;
          params += `<option value='test_not_performed' ${
            value.reading === "test_not_performed" ? "selected" : ""
          }>Test not performed</option></select>`;
          params += `<div class='invalid-feedback'></div></div>`;
          params += `<div class='col-md-6'>`;
          params += `<input type='text' class='forms-element form-control' name='high_val[${lab}][]' value='${value.high_val}' />`;
          params += `<div class='invalid-feedback'></div></div>`;
        }
        labNotes = value.notes;
        params += `</div></div>`;
      });

      params += `<div class="col-md-12 mb-3"><label>Notes:</label>`;
      params += `<textarea class="forms-element form-control" name="notes[${lab}]">${labNotes}</textarea>`;
      params += `<div class="invalid-feedback"></div></div>`;
      return params;
    };

    const exposeEditLab = () => {
      window.editlabsformnew = editlabsformnew;
    };

    watch(
      props,
      async (newProps) => {
        labsStepId.value = await getStepID(
          newProps.moduleId,
          newProps.componentId,
          newProps.stageId,
          "NumberTracking-Labs"
        );
      },
      { immediate: true }
    );

    onBeforeMount(async () => {
      await fetchLabs();
      await fetchPatientLabsList();
    });

    onMounted(async () => {
      try {
        var time = document.getElementById("page_landing_times").value;
        $(".timearr").val(time);
        labsStepId.value = await getStepID(
          props.moduleId,
          props.componentId,
          props.stageId,
          "NumberTracking-Labs"
        );
        exposeDeleteLab();
        exposeEditLab();
      } catch (error) {
        console.error("Error on page load:", error);
      }
    });

    return {
      submiLabsHealthDataForm,
      labsStepId,
      formErrors,
      labsTime,
      showLabsAlert,
      alertMsg,
      columnDefs,
      labsRowData,
      selectedLabs,
      labDate,
      fetchPatientLabsList,
      deleteServices,
      editService,
      fetchLabs,
      labs,
      onLabchange,
      labParams,
      editform,
      olddate,
      oldlab,
      labdateexist,
      token,
      isLoading,
      module_name,
      component_name,
    };
  },
};
</script>
