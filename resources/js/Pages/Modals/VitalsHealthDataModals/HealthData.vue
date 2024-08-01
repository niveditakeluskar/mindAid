<template>
  <div
    class="tab-pane fade show active"
    id="healthdata"
    role="tabpanel"
    aria-labelledby="healthdata-icon-pill"
  >
    <loading-spinner :isLoading="isLoading"></loading-spinner>
    <div class="card">
      <div class="card-header"><h4>Health Data</h4></div>
      <form
        id="number_tracking_healthdata_form"
        name="number_tracking_healthdata_form"
        action="/ccm/care-plan-development-numbertracking-healthdata"
        method="post"
        @submit.prevent
      >
        <div
          class="health-data-msg alert"
          :class="formErrors ? 'alert-danger' : 'alert-success'"
          :style="{ display: showHealthDataAlert ? 'block' : 'none' }"
        >
          <button type="button" class="close" data-dismiss="alert">x</button>
          <strong>{{ healthDataAlertMsg }}</strong>
        </div>
        <div class="col-md-12">
          <input type="hidden" name="_token" :value="token" />
          <input type="hidden" name="uid" :value="patientId" />
          <input type="hidden" name="patient_id" :value="patientId" />
          <input type="hidden" name="start_time" value="00:00:00" />
          <input type="hidden" name="end_time" value="00:00:00" />
          <input type="hidden" name="module_id" :value="moduleId" />
          <input type="hidden" name="component_id" :value="componentId" />
          <input type="hidden" name="stage_id" :value="stageId" />
          <input type="hidden" name="step_id" :value="healthDataStepId" />
          <input type="hidden" name="id" />
          <input
            type="hidden"
            name="form_name"
            value="number_tracking_healthdata_form"
          />
          <input type="hidden" name="billable" value="1" />
          <input
            type="hidden"
            name="timearr[form_start_time]"
            class="timearr form_start_time"
          />
          <div
            v-for="(item, index) in healthDataItems"
            :key="index"
            class="form-row"
          >
            <div class="col-md-4">
              <label>Health Data:<span class="error">*</span></label>
              <input
                type="text"
                name="health_data[]"
                placeholder="Enter Health Data"
                v-model="item.health_data"
                class="forms-element form-control"
              />
              <div class="invalid-feedback"></div>
            </div>
            <div class="col-md-4">
              <label>Date<span class="error">*</span> :</label>
              <input
                type="date"
                name="health_date[]"
                v-model="item.healthdata_date"
                class="forms-element form-control"
              />
              <div class="invalid-feedback"></div>
            </div>
            <div class="col-md-1">
              <i
                v-if="index > 0"
                class="remove-icons i-Remove float-right mb-3"
                title="Remove Follow-up Task"
                @click="removeHealthDataItem(index)"
              ></i>
            </div>
          </div>
          <hr />
          <div @click="addHealthDataItem">
            <i
              class="plus-icons i-Add"
              id="add_healthdata"
              title="Add health_data"
            ></i>
          </div>
        </div>
        <div class="card-footer">
          <div class="mc-footer">
            <div class="row">
              <div class="col-lg-12 text-right">
                <button
                  type="submit"
                  class="btn btn-primary m-1"
                  id="save_number_tracking_healthdata_form"
                  @click="submitHealthDataForm"
                >
                  Save
                </button>
              </div>
            </div>
          </div>
        </div>
        <div
          class="health-data-msg alert"
          :class="formErrors ? 'alert-danger' : 'alert-success'"
          :style="{ display: showHealthDataAlert ? 'block' : 'none' }"
        >
          <button type="button" class="close" data-dismiss="alert">x</button>
          <strong>{{ healthDataAlertMsg }}</strong>
        </div>
      </form>
      <div class="separator-breadcrumb border-top"></div>
      <div class="row">
        <div class="col-12">
          <div class="table-responsive">
            <AgGridTable
              :rowData="healthdataRowData"
              :columnDefs="columnDefs"
            />
          </div>
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
import { ajaxForm } from "../../Utility/Form.vue";
import {
  getStepID,
  updateTimer,
  editData,
  deleteRecordDetails,
} from "../../Utility/CommonFunctions.vue";
import moment from "moment";
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
    let showHealthDataAlert = ref(false);
    let healthDataStepId = ref(0);
    let healthdataTime = ref(null);
    let healthdata = ref([]);
    let formErrors = ref(false);
    const healthdataRowData = ref([]);
    let healthDataAlertMsg = ref("");
    let isLoading = ref(false);
    const year = new Date().getFullYear();
    const month = new Date().getMonth() + 1;
    let formName = "number_tracking_healthdata_form";
    let token = document.head.querySelector('meta[name="csrf-token"]').content;
    let healthDataItems = ref([
      {
        health_data: "",
        healthdata_date: "",
      },
    ]);
    let columnDefs = ref([
      {
        headerName: "Sr. No.",
        valueGetter: "node.rowIndex + 1",
        initialWidth: 20,
      },
      {
        headerName: "Health Date",
        field: "health_date",
        filter: true,
        valueFormatter: (params) => {
          const date = new Date(params.value);
          const month = date.getMonth() + 1;
          const day = date.getDate();
          const year = date.getFullYear();

          // Format the date as mm-dd-yyyy
          return `${month.toString().padStart(2, "0")}-${day
            .toString()
            .padStart(2, "0")}-${year}`;
        },
      },
      { headerName: "Health Data", field: "health_data" },
      {
        headerName: "Action",
        field: "action",
        cellRenderer: function (params) {
          const row = params.data;
          return row && row.action ? row.action : "";
        },
      },
    ]);

    const fetchPatientHealthDataList = async () => {
      try {
        isLoading.value = true;
        await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
        const response = await fetch(
          `/ccm/care-plan-development-health-healthlist/${props.patientId}`
        );
        if (!response.ok) {
          throw new Error("Failed to fetch health data list");
        }
        isLoading.value = false;
        const data = await response.json();
        healthdataRowData.value = data.data;
      } catch (error) {
        console.error("Error fetching health data list:", error);
        isLoading.value = false;
      }
    };

    let submitHealthDataForm = async () => {
      isLoading.value = true;
      await ajaxForm(formName, handleResult);
      var form = $("#" + formName)[0];
      form.scrollIntoView({ behavior: "smooth" });
    };

    const handleResult = async (
      form,
      fields,
      response,
      successMessage = "Health Data saved successfully!"
    ) => {
      isLoading.value = false;
      if (response.status === 200) {
        await fetchPatientHealthDataList();
        showHealthDataAlert.value = true;
        formErrors.value = false;
        var form = $("#" + formName)[0];
        form.reset();
        form["id"].value = "";
        healthDataItems.value = [{ health_data: "", health_date: "" }];
        $(form).find(":input").prop("disabled", false);
        healthDataAlertMsg.value = successMessage;
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
          showHealthDataAlert.value = false;
          var time = document.getElementById("page_landing_times").value;
          $(".timearr").val(time);
        }, 3000);
      } else {
        formErrors.value = true;
        showHealthDataAlert.value = true;
        healthDataAlertMsg.value = "Please Fill All mandatory Fields!";
        form.scrollIntoView({ behavior: "smooth" });
      }
    };

    // let editHealthData = async (id) => {
    //   var form = $("#" + formName)[0];
    //   form.reset();
    //   form["id"].value = "";
    //   editData(
    //     "/ccm/get-patient-vital-by-id/" + id + "/patient-vital",
    //     formName,
    //     props.patientId
    //   );
    // };

    const editHealthData = async (id) => {
      try {
        isLoading.value = true;
        const response = await fetch(
          `/ccm/get-patient-healthdata-by-id/${id}/patient-healthdata`
        );
        if (!response.ok) {
          throw new Error("Failed to fetch labs details");
        }
        var form = $("#" + formName)[0];
        isLoading.value = false;
        const data = await response.json();
        const healthdata = data.number_tracking_healthdata_form.static;
        console.log("healthdata==>", healthdata);
        let dt = moment(healthdata.health_date, "YYYY-MM-DD HH:mm:ss").format(
          "YYYY-MM-DD"
        );
        healthDataItems.value = [
          {
            health_data: healthdata.health_data,
            healthdata_date: dt,
          },
        ];
        form["id"].value = healthdata.id;
        form.scrollIntoView({ behavior: "smooth" });
      } catch (error) {
        console.error("Error fetching health data details:", error);
        isLoading.value = false;
      }
    };

    const exposeEditHealthData = () => {
      window.editHealthData = editHealthData;
    };

    let deleteHealthData = async (id, obj) => {
      if (window.confirm("Are you sure you want to delete this Health Data?")) {
        const deleteDataResponse = await deleteRecordDetails(
          id,
          "/ccm/delete-patient-healthdata-by-id",
          formName
        );
        if (deleteDataResponse.status === 200) {
          var msg = "Health data deleted successfully!";
          await handleResult(formName, "", deleteDataResponse, msg);
        } else {
          console.error(
            "Deletion failed with status code:",
            deleteDataResponse.status
          );
        }
      }
    };

    const exposeDeleteHealthData = () => {
      window.deleteHealthData = deleteHealthData;
    };

    const addHealthDataItem = async () => {
      healthDataItems.value.push({
        health_data: "",
        healthdata_date: "",
      });
    };

    const removeHealthDataItem = (index) => {
      if (healthDataItems.value.length > 1) {
        healthDataItems.value.splice(index, 1);
      }
    };

    watch(
      props,
      async (newProps) => {
        healthDataStepId.value = await getStepID(
          newProps.moduleId,
          newProps.componentId,
          newProps.stageId,
          "NumberTracking-Health_Data"
        );
      },
      { immediate: true }
    );

    onBeforeMount(() => {
      fetchPatientHealthDataList();
    });

    onMounted(async () => {
      try {
        var time = document.getElementById("page_landing_times").value;
        $(".timearr").val(time);
        healthDataStepId.value = await getStepID(
          props.moduleId,
          props.componentId,
          props.stageId,
          "NumberTracking-Health_Data"
        );
        exposeEditHealthData();
        exposeDeleteHealthData();
      } catch (error) {
        console.error("Error on page load:", error);
      }
    });

    return {
      fetchPatientHealthDataList,
      healthDataStepId,
      formErrors,
      healthdataTime,
      showHealthDataAlert,
      columnDefs,
      healthdataRowData,
      fetchPatientHealthDataList,
      healthdata,
      healthDataItems,
      addHealthDataItem,
      removeHealthDataItem,
      submitHealthDataForm,
      isLoading,
      token,
      healthDataAlertMsg,
    };
  },
};
</script>
