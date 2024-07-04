<template>
  <LayoutComponent ref="layoutComponentRef">
    <div>
      <loading-spinner :isLoading="isLoading"></loading-spinner>
      <div class="breadcrusmb">
        <div class="row" style="margin-top: 10px">
          <div class="col-md-8">
            <h4 class="card-title mb-3">{{ title }}</h4>
          </div>
          <div class="form-group col-md-4"></div>
        </div>
      </div>
      <div class="separator-breadcrumb border-top"></div>
      <div id="success"></div>
      <div class="row">
        <div class="col-md-12 mb-4">
          <div class="card text-left">
            <div class="card-body">
              <form @submit.prevent="fetchPatientVitalReportApiData">
                <div class="form-row">
                  <div class="col-md-2 form-group mb-2">
                    <label for="practicename">Organization</label>
                    <select
                      id="practicesgrp"
                      class="custom-select show-tick select2"
                      data-live-search="true"
                      v-model="filterState.organization"
                      @change="fetchPractices(filterState.organization)"
                    >
                      <option :value="null">Select Organization</option>
                      <option
                        v-for="practicegp in practicegroup"
                        :key="practicegp.id"
                        :value="practicegp.id"
                      >
                        {{ practicegp.practice_name }}
                      </option>
                    </select>
                  </div>
                  <div class="col-md-2 form-group mb-2">
                    <label for="practicename">Practice</label>
                    <select
                      id="practices"
                      class="custom-select show-tick select2"
                      data-live-search="true"
                      v-model="filterState.practiceId"
                      @change="fetchPatients"
                    >
                      <option :value="null">Select Practices</option>
                      <option
                        v-for="practice in practices"
                        :key="practice.id"
                        :value="practice.id"
                      >
                        {{ practice.name }}
                      </option>
                    </select>
                  </div>
                  <div class="col-md-2 form-group mb-3">
                    <label for="patient">Patient Name</label>
                    <select
                      id="patient"
                      class="custom-select show-tick select2"
                      data-live-search="true"
                      v-model="filterState.patientId"
                    >
                      <option :value="null">Select Report To</option>
                      <option
                        v-for="patient in patientList"
                        :key="patient.id"
                        :value="patient.id"
                      >
                        {{
                          patient.fname +
                          " " +
                          (patient.mname ? `${patient.mname} ` : "") +
                          patient.lname
                        }}
                      </option>
                    </select>
                  </div>
                  <div class="col-md-2 form-group mb-3">
                    <label for="from_date">From Date</label>
                    <input
                      id="from_date"
                      class="form-control"
                      name="from_date"
                      type="date"
                      value=""
                      autocomplete="off"
                      v-model="filterState.fromDate"
                    />
                  </div>
                  <div class="col-md-2 form-group mb-3">
                    <label for="to_date">To Date</label>
                    <input
                      id="to_date"
                      class="form-control"
                      name="to_date"
                      type="date"
                      value=""
                      autocomplete="off"
                      v-model="filterState.toDate"
                    />
                  </div>
                  <div class="row col-md-2 mb-2">
                    <div class="col-md-5">
                      <button
                        type="button"
                        class="btn btn-primary mt-4"
                        id="month-search"
                        @click="fetchPatientVitalReportApiData"
                      >
                        Search
                      </button>
                    </div>
                    <div class="col-md-5">
                      <button
                        type="button"
                        class="btn btn-primary mt-4"
                        id="month-reset"
                        @click="handleReset"
                      >
                        Reset
                      </button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="row mb-4">
        <div class="col-md-12 mb-4">
          <div class="card text-left">
            <div class="card-body">
              <AgGridTable :rowData="passRowData" :columnDefs="columnDefs" />
            </div>
          </div>
          <!--End of card-->
        </div>
      </div>
    </div>
  </LayoutComponent>

  <Head>
    <title>{{ title }}</title>
    <meta name="description" content="Worklist listing page" />
  </Head>
</template>

<script setup>
import {
  reactive,
  ref,
  AgGridTable,
  onBeforeMount,
  Head,
} from "../../commonImports";
import moment from "moment";
import LayoutComponent from "../../LayoutComponent.vue";
const title = "Patient Vitals Report";
const layoutComponentRef = ref(null);
const passRowData = ref([]);
const isLoading = ref(false);
const practices = ref([]);
const practicegroup = ref([]);
const physicians = ref([]);
const patientList = ref([]);
const currentDate = moment().format("YYYY-MM-DD");
const cMonthStartDate = moment().startOf("month").format("YYYY-MM-DD");
const filterState = reactive({
  organization: null,
  patientId: null,
  practiceId: null,
  fromDate: cMonthStartDate,
  toDate: currentDate,
});

onBeforeMount(() => {
  document.title = `${title} | Renova Healthcare`;
  fetchPracticegroup();
  fetchPractices();
  fetchPatients();
  fetchPatientVitalReportApiData();
});
const formatDate = (date) => {
  if (!date) return null;
  return moment(date).format("MM-DD-YYYY");
};
const columnDefs = ref([
  {
    headerName: "Sr. No.",
    valueGetter: "node.rowIndex + 1",
    flex: 1,
  },
  {
    headerName: "Organization",
    field: "practicegrp",
  },
  {
    headerName: "Practice",
    field: "practice",
  },
  {
    headerName: "Patient First Name",
    field: "fname",
  },
  {
    headerName: "Patient Last Name",
    field: "lname",
  },
  {
    headerName: "DOB",
    field: "dob",
    cellRenderer: function (params) {
      const date = params.value;
      const formattedDate = formatDate(date);
      return formattedDate;
    },
  },
  {
    headerName: "BP & BMI Date",
    field: "bp_bmi_date",
  },
  {
    headerName: "BP Value",
    field: "bp",
  },
  {
    headerName: "BMI Value",
    field: "bmi",
  },
  {
    headerName: "HgA1c Date",
    field: "hga1c_date",
  },
  {
    headerName: "HgA1c Value",
    field: "hga1c_val",
  },
]);
const fetchPractices = async () => {
  try {
    const response = await fetch(
      "../org/ajax/practicegrp/" + filterState.organization + "/practice"
    );
    if (!response.ok) {
      throw new Error("Failed to fetch practices");
    }
    const data = await response.json();
    practices.value = data;
  } catch (error) {
    console.error("Error fetching practices:", error);
  }
};

const fetchPracticegroup = async () => {
  try {
    const response = await fetch("../org/practicesgroup");
    if (!response.ok) {
      throw new Error("Failed to fetch practices");
    }
    const data = await response.json();
    practicegroup.value = data;
  } catch (error) {
    console.error("Error fetching practices:", error);
  }
};

const fetchPatients = async () => {
  try {
    const response = await fetch(
      "/patients/ajax/patientlist/" + filterState.practiceId + "/patientlist"
    );
    if (!response.ok) {
      throw new Error("Failed to fetch practices");
    }
    const data = await response.json();
    patientList.value = data;
  } catch (error) {
    console.error("Error fetching practices:", error);
  }
};

const fetchPatientVitalReportApiData = async () => {
  try {
    isLoading.value = true;
    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content");
    const response = await fetch(
      `/reports/patient-vitals-report-search/${filterState.organization}/${filterState.practiceId}/${filterState.patientId}/${filterState.fromDate}/${filterState.toDate}?_=1715324427764`,
      {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrfToken,
        },
      }
    );
    if (!response.ok) {
      throw new Error("Failed to fetch patient list");
    }
    const data = await response.json();
    const processedData = data.data.map((row) => ({
      ...row,
      full_name: [row.pfname, row.pmname, row.plname].filter(Boolean).join(" "),
      CM: [row.userfname, row.userlname, `(${row.tptotalpatient})`]
        .filter(Boolean)
        .join(" "),
    }));

    passRowData.value = processedData || [];
  } catch (error) {
    console.error("Error fetching data:", error);
  } finally {
    isLoading.value = false;
  }
};

const handleReset = () => {
  filterState.patientId = null;
  filterState.organization = null;
  filterState.steps = null;
  filterState.practiceId = null;
  filterState.fromDate = cMonthStartDate;
  filterState.toDate = currentDate;
  fetchPractices();
  fetchPatients();
};
</script>
