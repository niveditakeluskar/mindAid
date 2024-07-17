<template>
  <div
    class="tab-pane fade show active"
    id="vitals"
    role="tabpanel"
    aria-labelledby="vitals-icon-pill"
  >
    <loading-spinner :isLoading="isLoading"></loading-spinner>
    <div class="card">
      <div class="card-header">
        <h4>Vitals Data</h4>
      </div>
      <form
        id="number_tracking_vitals_form"
        name="number_tracking_vitals_form"
        action="/ccm/care-plan-development-numbertracking-vitals"
        method="post"
        @submit.prevent
      >
        <div
          class="vitals-msg alert"
          :class="formErrors ? 'alert-danger' : 'alert-success'"
          :style="{ display: showVitalsAlert ? 'block' : 'none' }"
        >
          <button type="button" class="close" data-dismiss="alert">x</button>
          <strong>{{ vitalsAlertMsg }}</strong>
        </div>
        <div class="form-row col-md-12">
          <input type="hidden" name="_token" :value="token" />
          <input type="hidden" name="uid" :value="patientId" />
          <input type="hidden" name="patient_id" :value="patientId" />
          <input type="hidden" name="start_time" value="00:00:00" />
          <input type="hidden" name="end_time" value="00:00:00" />
          <input type="hidden" name="module_id" :value="moduleId" />
          <input type="hidden" name="component_id" :value="componentId" />
          <input type="hidden" name="stage_id" :value="stageId" />
          <input type="hidden" name="step_id" :value="vitalsStepId" />
          <input type="hidden" name="id" />
          <input
            type="hidden"
            name="form_name"
            value="number_tracking_vitals_form"
          />
          <input type="hidden" name="billable" value="1" />
          <input
            type="hidden"
            name="timearr[form_start_time]"
            class="timearr form_start_time"
          />
          <div class="col-md-4 form-group mb-3">
            <label for="height"
              >Height (in)<!-- <span class="error">*</span> -->
              :</label
            >
            <input
              type="text"
              name="height"
              id="height"
              class="form-control"
              v-model="height"
              @blur="calculateBMI"
            />
            <div class="invalid-feedback"></div>
          </div>
          <div class="col-md-4 form-group mb-3">
            <label for="weight"
              >Weight (lbs)<!-- <span class="error">*</span> -->
              :</label
            >
            <input
              type="text"
              name="weight"
              id="weight"
              class="form-control"
              v-model="weight"
              @blur="calculateBMI"
            />
            <div class="invalid-feedback"></div>
          </div>
          <div class="col-md-4 form-group mb-3">
            <label for="bmi"
              >BMI<!-- <span class="error">*</span> -->
              :</label
            >
            <input
              type="text"
              name="bmi"
              id="bmi"
              class="form-control"
              v-model="bmi"
              readonly="readonly"
            />
            <div class="invalid-feedback"></div>
          </div>
          <div class="col-md-4 form-group mb-3">
            <label for="bp"
              >Blood Pressure<!-- <span class="error">*</span> -->
              :</label
            >
            <div class="form-row col-md-12 form-group">
              <span class="col-md-5">
                <input
                  type="text"
                  name="bp"
                  id="bp"
                  class="form-control"
                  placeholder="Systolic"
                />
                <div class="invalid-feedback"></div>
              </span>
              <span class="mt-1 pl-2 pr-2"> / </span>
              <span class="col-md-6">
                <input
                  type="text"
                  name="diastolic"
                  id="diastolic"
                  class="form-control"
                  placeholder="Diastolic"
                />
                <div class="invalid-feedback"></div>
              </span>
            </div>
          </div>
          <div class="col-md-4 form-group mb-3">
            <label for="o2"
              >O2 Saturation<!-- <span class="error">*</span> -->
              :</label
            >
            <input type="text" name="o2" id="o2" class="form-control" />
            <div class="invalid-feedback"></div>
          </div>
          <div class="col-md-2 form-group mb-3">
            <label for="pulse_rate"
              >Pulse Rate<!-- <span class="error">*</span>  -->:</label
            >
            <input
              type="text"
              name="pulse_rate"
              id="pulse_rate"
              class="form-control"
            />
            <div class="invalid-feedback"></div>
          </div>
          <div class="col-md-2 form-group mb-3">
            <label for="pain_level"
              >Pain Level<!-- <span class="error">*</span>  -->:</label
            >
            <select
              name="pain_level"
              id="pain_level"
              class="custom-select show-tick"
            >
              <option value="0">0</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
            </select>
          </div>
          <div class="col-md-12 form-group mb-3">
            <div class="mr-3 d-inline-flex align-self-center">
              <label class="radio radio-primary mr-3">
                <input
                  type="radio"
                  name="oxygen"
                  :value="1"
                  v-model="oxygenRadio"
                  formControlName="radio"
                />
                <span>Room Air</span>
                <span class="checkmark"></span>
              </label>
              <label class="radio radio-primary mr-3">
                <input
                  type="radio"
                  name="oxygen"
                  :value="0"
                  v-model="oxygenRadio"
                  formControlName="radio"
                />
                <span>Supplemental Oxygen</span>
                <span class="checkmark"></span>
              </label>
            </div>
          </div>
          <div
            class="col-md-12 form-group mb-3"
            :class="{ 'd-none': oxygenRadio !== 0 }"
          >
            <label>Notes</label>
            <textarea
              class="form-control forms-element"
              name="notes"
            ></textarea>
            <div class="invalid-feedback"></div>
          </div>
        </div>
        <div class="card-footer">
          <div class="mc-footer">
            <div class="row">
              <div class="col-lg-12 text-right">
                <button
                  type="submit"
                  class="btn btn-primary m-1"
                  id="save_number_tracking_vitals_form"
                  @click="submiVitalsHealthDataForm"
                >
                  Save
                </button>
              </div>
            </div>
          </div>
        </div>
        <div
          class="vitals-msg alert"
          :class="formErrors ? 'alert-danger' : 'alert-success'"
          :style="{ display: showVitalsAlert ? 'block' : 'none' }"
        >
          <button type="button" class="close" data-dismiss="alert">x</button>
          <strong>{{ vitalsAlertMsg }}</strong>
        </div>
      </form>
      <div class="separator-breadcrumb border-top"></div>
      <div class="row">
        <div class="col-12">
          <div class="table-responsive">
            <AgGridTable :rowData="vitalsRowData" :columnDefs="columnDefs" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { ref, watch, onBeforeMount, onMounted } from "../../commonImports";
import AgGridTable from "../../components/AgGridTable.vue";
import { ajaxForm } from "../../Utility/Form.vue";
import {
  getStepID,
  updateTimer,
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
    let showVitalsAlert = ref(false);
    let vitalsStepId = ref(0);
    let vitalsTime = ref(null);
    let formErrors = ref(false);
    let formName = "number_tracking_vitals_form";
    let vitalsAlertMsg = ref("");
    let token = document.head.querySelector('meta[name="csrf-token"]').content;
    const height = ref(null);
    const weight = ref(null);
    const bmi = ref(null);
    const oxygenRadio = ref(null);
    const vitalsRowData = ref([]);
    const year = new Date().getFullYear();
    const month = new Date().getMonth() + 1;
    let isLoading = ref(false);
    let columnDefs = ref([
      {
        headerName: "Sr. No.",
        valueGetter: "node.rowIndex + 1",
        initialWidth: 20,
      },
      {
        headerName: "Rec Date",
        field: "rec_date",
        filter: true,
        valueFormatter: (params) => {
          const date = new Date(params.value);
          const day = date.getDate();
          // Format the date as mm-dd-yyyy
          return `${month.toString().padStart(2, "0")}-${day
            .toString()
            .padStart(2, "0")}-${year}`;
        },
      },
      { headerName: "Height (in)", field: "height" },
      { headerName: "Weight (lbs)", field: "weight" },
      { headerName: "BMI", field: "bmi" },
      { headerName: "Systolic", field: "bp" },
      { headerName: "Diastolic", field: "diastolic" },
      { headerName: "O2 Saturation", field: "o2" },
      { headerName: "Pulse Rate", field: "pulse_rate" },
      { headerName: "Pain Level", field: "pain_level" },
      { headerName: "Oxygen", field: "oxygen" },
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

    const fetchPatientVitalsList = async () => {
      try {
        isLoading.value = true;
        await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
        const response = await fetch(
          `/ccm/care-plan-development-vital-vitallist/${props.patientId}`
        );
        if (!response.ok) {
          throw new Error("Failed to fetch vitals list");
        }
        isLoading.value = false;
        const data = await response.json();
        vitalsRowData.value = data.data;
      } catch (error) {
        console.error("Error fetching vitals list:", error);
        isLoading.value = false;
      }
    };

    let submiVitalsHealthDataForm = async () => {
      isLoading.value = true;
      await ajaxForm(formName, handleResult);
      var form = $("#" + formName)[0];
      form.scrollIntoView({ behavior: "smooth" });
    };

    const handleResult = async (
      form,
      fields,
      response,
      successMessage = "Vitals data saved successfully!"
    ) => {
      isLoading.value = false;
      if (response.status === 200) {
        await fetchPatientVitalsList();
        showVitalsAlert.value = true;
        formErrors.value = false;
        var form = $("#" + formName)[0];
        form.reset();
        height.value = "";
        weight.value = "";
        bmi.value = "";
        oxygenRadio.value = "";
        $(form).find(":input").prop("disabled", false);
        vitalsAlertMsg.value = successMessage;
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
          showVitalsAlert.value = false;
          var time = document.getElementById("page_landing_times").value;
          $(".timearr").val(time);
        }, 3000);
      } else {
        formErrors.value = true;
        showVitalsAlert.value = true;
        vitalsAlertMsg.value = "Please Fill All mandatory Fields!";
        form.scrollIntoView({ behavior: "smooth" });
      }
    };

    let editVitals = async (id) => {
      oxygenRadio.value = "";
      var form = $("#" + formName)[0];
      form.reset();
      form["id"].value = "";
      await editData(
        "/ccm/get-patient-vital-by-id/" + id + "/patient-vital",
        formName,
        props.patientId
      );
    };

    const exposeEditVitals = () => {
      window.editVitals = editVitals;
    };

    let deleteVitals = async (id, obj) => {
      if (window.confirm("Are you sure you want to delete this Vitals?")) {
        const deleteDataResponse = await deleteRecordDetails(
          id,
          "/ccm/delete-patient-vital-by-id",
          formName
        );
        if (deleteDataResponse.status === 200) {
          var msg = "Vitals data deleted successfully!";
          await handleResult(formName, "", deleteDataResponse, msg);
        } else {
          console.error(
            "Deletion failed with status code:",
            deleteDataResponse.status
          );
        }
      }
    };

    const exposeDeleteVitals = () => {
      window.deleteVitals = deleteVitals;
    };

    watch(
      props,
      async (newProps) => {
        vitalsStepId.value = await getStepID(
          newProps.moduleId,
          newProps.componentId,
          newProps.stageId,
          "NumberTracking-Vitals_Data"
        );
      },
      { immediate: true }
    );

    onBeforeMount(() => {
      fetchPatientVitalsList();
    });

    onMounted(async () => {
      try {
        var time = document.getElementById("page_landing_times").value;
        $(".timearr").val(time);
        vitalsStepId.value = await getStepID(
          props.moduleId,
          props.componentId,
          props.stageId,
          "NumberTracking-Vitals_Data"
        );
        exposeEditVitals();
        exposeDeleteVitals();
      } catch (error) {
        console.error("Error on page load:", error);
      }
    });

    const calculateBMI = async () => {
      if (height.value != "" && weight.value != "") {
        const heightInInches = parseFloat(height.value);
        const weightInLbs = parseFloat(weight.value);
        const bmiValue =
          (weightInLbs / (heightInInches * heightInInches)) * 703;
        bmi.value = bmiValue.toFixed(1);
      } else {
        bmi.value = null;
      }
    };

    return {
      submiVitalsHealthDataForm,
      vitalsStepId,
      formErrors,
      vitalsTime,
      showVitalsAlert,
      vitalsAlertMsg,
      columnDefs,
      vitalsRowData,
      fetchPatientVitalsList,
      calculateBMI,
      weight,
      height,
      bmi,
      oxygenRadio,
      isLoading,
      token,
    };
  },
};
</script>
<style>
.d-none {
  display: none !important; /* Use !important to override Vue conditional display */
}
</style>
