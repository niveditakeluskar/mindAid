<template>
  <div
    class="tab-pane fade show active"
    id="imaging"
    role="tabpanel"
    aria-labelledby="imaging-icon-pill"
  >
    <loading-spinner :isLoading="isLoading"></loading-spinner>
    <div class="card">
      <div class="card-header"><h4>Imaging</h4></div>
      <form
        id="number_tracking_imaging_form"
        name="number_tracking_imaging_form"
        action="/ccm/care-plan-development-numbertracking-imaging"
        method="post"
        @submit.prevent
      >
        <div
          class="imaging-msg alert"
          :class="formErrors ? 'alert-danger' : 'alert-success'"
          :style="{ display: showImagingAlert ? 'block' : 'none' }"
        >
          <button type="button" class="close" data-dismiss="alert">x</button>
          <strong>{{ imagingAlertMsg }}</strong>
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
          <input type="hidden" name="step_id" :value="imagingStepId" />
          <input type="hidden" name="id" />
          <input
            type="hidden"
            name="form_name"
            value="number_tracking_imaging_form"
          />
          <input type="hidden" name="billable" value="1" />
          <input
            type="hidden"
            name="timearr[form_start_time]"
            class="timearr form_start_time"
          />
          <div
            v-for="(item, index) in imagingItems"
            :key="index"
            class="form-row"
          >
            <input type="hidden" class="imaging_id" id="imaging_id" />
            <div class="col-md-4">
              <label>Imaging : <span class="error">*</span></label>
              <input
                type="text"
                name="imaging[]"
                v-model="item.imaging"
                placeholder="Enter Imaging"
                class="forms-element form-control"
              />
              <div class="invalid-feedback"></div>
            </div>
            <div class="col-md-4">
              <label>Date<span class="error">*</span> :</label>
              <input
                type="date"
                name="imaging_date[]"
                v-model="item.imaging_date"
                class="forms-element form-control"
              />
              <div class="invalid-feedback"></div>
            </div>
            <div class="col-md-1">
              <i
                v-if="index > 0"
                class="remove-icons i-Remove float-right mb-3"
                title="Remove Follow-up Task"
                @click="removeImagingItem(index)"
              ></i>
            </div>
          </div>
          <hr />
          <div @click="addImagingItem">
            <i
              class="plus-icons i-Add"
              id="add_imaging"
              title="Add imaging"
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
                  id="save_number_tracking_imaging_form"
                  @click="submiImagingHealthDataForm"
                >
                  Save
                </button>
              </div>
            </div>
          </div>
        </div>
        <div
          class="imaging-msg alert"
          :class="formErrors ? 'alert-danger' : 'alert-success'"
          :style="{ display: showImagingAlert ? 'block' : 'none' }"
        >
          <button type="button" class="close" data-dismiss="alert">x</button>
          <strong>{{ imagingAlertMsg }}</strong>
        </div>
      </form>
      <div class="separator-breadcrumb border-top"></div>
      <div class="row">
        <div class="col-12">
          <div class="table-responsive">
            <AgGridTable
              :rowData="imagingRowData"
              :columnDefs="imagingColumnDefs"
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
    let showImagingAlert = ref(false);
    let imagingStepId = ref(0);
    let imagingTime = ref(null);
    let imaging = ref([]);
    let formErrors = ref(false);
    let isLoading = ref(false);
    let imagingAlertMsg = ref("");
    const year = new Date().getFullYear();
    const month = new Date().getMonth() + 1;
    let token = document.head.querySelector('meta[name="csrf-token"]').content;
    const imagingRowData = ref([]);
    let formName = "number_tracking_imaging_form";
    let imagingItems = ref([
      {
        imaging: "",
        imaging_date: "",
      },
    ]);
    let imagingColumnDefs = ref([
      {
        headerName: "Sr. No.",
        valueGetter: "node.rowIndex + 1",
        initialWidth: 20,
      },
      {
        headerName: "Imaging Date",
        field: "imaging_date",
        filter: true,
        valueFormatter: (params) => {
          const date = new Date(params.value);
          const month = date.getMonth() + 1;
          const day = date.getDate();
          const year = date.getFullYear();
          return `${month.toString().padStart(2, "0")}-${day
            .toString()
            .padStart(2, "0")}-${year}`;
        },
      },
      { headerName: "Imaging", field: "imaging_details" },
      {
        headerName: "Action",
        field: "action",
        cellRenderer: function (params) {
          const row = params.data;
          return row && row.action ? row.action : "";
        },
      },
    ]);

    const fetchPatientImagingList = async () => {
      try {
        isLoading.value = true;
        await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
        const response = await fetch(
          `/ccm/care-plan-development-imaging-imaginglist/${props.patientId}`
        );
        if (!response.ok) {
          throw new Error("Failed to fetch imaging list");
        }
        isLoading.value = false;
        const data = await response.json();
        imagingRowData.value = data.data;
      } catch (error) {
        console.error("Error fetching imaging list:", error);
        isLoading.value = false;
      }
    };

    let submiImagingHealthDataForm = async () => {
      isLoading.value = true;
      await ajaxForm(formName, handleResult);
      var form = $("#" + formName)[0];
      form.scrollIntoView({ behavior: "smooth" });
    };

    const handleResult = async (
      form,
      fields,
      response,
      successMessage = "Imaging data saved successfully!"
    ) => {
      isLoading.value = false;
      if (response.status === 200) {
        await fetchPatientImagingList();
        showImagingAlert.value = true;
        formErrors.value = false;
        var form = $("#" + formName)[0];
        form.reset();
        form["id"].value = "";
        imagingItems.value = [{ imaging: "", imaging_date: "" }];
        $(form).find(":input").prop("disabled", false);
        imagingAlertMsg.value = successMessage;
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
          showImagingAlert.value = false;
          var time = document.getElementById("page_landing_times").value;
          $(".timearr").val(time);
        }, 3000);
      } else {
        formErrors.value = true;
        showImagingAlert.value = true;
        imagingAlertMsg.value = "Please Fill All mandatory Fields!";
        form.scrollIntoView({ behavior: "smooth" });
      }
    };

    // let editImagingData = async (id) => {
    //   var form = $("#" + formName)[0];
    //   form.reset();
    //   form["id"].value = "";
    //   editData(
    //     "/ccm/get-patient-imaging-by-id/" + id + "/patient-imaging",
    //     formName,
    //     props.patientId
    //   );
    // };

    const editImagingData = async (id) => {
      try {
        isLoading.value = true;
        const response = await fetch(
          `/ccm/get-patient-imaging-by-id/${id}/patient-imaging`
        );
        if (!response.ok) {
          throw new Error("Failed to fetch labs details");
        }
        var form = $("#" + formName)[0];
        isLoading.value = false;
        const data = await response.json();
        const imaging = data.number_tracking_imaging_form.static;
        let dt = moment(imaging.imaging_date, "MM-DD-YYYY HH:mm:ss").format(
          "YYYY-MM-DD"
        );
        imagingItems.value = [
          {
            imaging: imaging.imaging_details,
            imaging_date: dt,
          },
        ];
        form["id"].value = imaging.id;
        form.scrollIntoView({ behavior: "smooth" });
      } catch (error) {
        console.error("Error fetching labs details:", error);
        isLoading.value = false;
      }
    };

    const exposeEditImagingData = () => {
      window.editImagingData = editImagingData;
    };

    let deleteImagingData = async (id, obj) => {
      if (
        window.confirm("Are you sure you want to delete this Imaging Data?")
      ) {
        const deleteDataResponse = await deleteRecordDetails(
          id,
          "/ccm/delete-patient-imaging-by-id",
          formName
        );
        if (deleteDataResponse.status === 200) {
          var msg = "Imaging Data data deleted successfully!";
          await handleResult(formName, "", deleteDataResponse, msg);
        } else {
          console.error(
            "Deletion failed with status code:",
            deleteDataResponse.status
          );
        }
      }
    };

    const exposeDeleteImagingData = () => {
      window.deleteImagingData = deleteImagingData;
    };

    const addImagingItem = async () => {
      imagingItems.value.push({
        imaging: "",
        imaging_date: "",
      });
    };

    const removeImagingItem = (index) => {
      if (imagingItems.value.length > 1) {
        imagingItems.value.splice(index, 1);
      }
    };

    watch(
      props,
      async (newProps) => {
        imagingStepId.value = await getStepID(
          newProps.moduleId,
          newProps.componentId,
          newProps.stageId,
          "NumberTracking-Imaging"
        );
      },
      { immediate: true }
    );

    onBeforeMount(() => {
      fetchPatientImagingList();
    });

    onMounted(async () => {
      try {
        var time = document.getElementById("page_landing_times").value;
        $(".timearr").val(time);
        imagingStepId.value = await getStepID(
          props.moduleId,
          props.componentId,
          props.stageId,
          "NumberTracking-Imaging"
        );
        exposeEditImagingData();
        exposeDeleteImagingData();
      } catch (error) {
        console.error("Error on page load:", error);
      }
    });

    return {
      submiImagingHealthDataForm,
      imagingStepId,
      formErrors,
      imagingTime,
      showImagingAlert,
      imagingColumnDefs,
      imagingRowData,
      fetchPatientImagingList,
      imaging,
      imagingItems,
      addImagingItem,
      removeImagingItem,
      isLoading,
      token,
      imagingAlertMsg,
    };
  },
};
</script>
