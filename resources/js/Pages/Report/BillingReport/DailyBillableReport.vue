<template>
  <LayoutComponent ref="layoutComponentRef">
    <div>
      <loading-spinner :isLoading="isLoading"></loading-spinner>
      <div class="breadcrusmb">
        <div class="row" style="margin-top: 10px">
          <div class="col-md-8">
            <h4 class="card-title mb-3">
              {{ title }}
            </h4>
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
              <form @submit.prevent="fetchMonthlyBillableReportApiData">
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
                      @change="fetchProvider"
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
                    <label for="provider">Provider</label>
                    <select
                      id="physician"
                      class="custom-select show-tick select2"
                      data-live-search="true"
                      v-model="filterState.provider"
                    >
                      <option :value="null">Select Provider</option>
                      <option
                        v-for="physician in physicians"
                        :key="physician.id"
                        :value="physician.id"
                      >
                        {{ physician.name }}
                      </option>
                    </select>
                  </div>
                  <div class="col-md-2 form-group mb-3">
                    <label for="moduleName">Module</label>
                    <select
                      id="moduleName"
                      name="moduleName"
                      class="custom-select show-tick"
                      v-model="filterState.moduleId"
                    >
                      <option value="3">CCM</option>
                      <option value="2">RPM</option>
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
                  <div class="col-md-2 form-group mb-3">
                    <select
                      id="timeoption"
                      class="custom-select show-tick"
                      style="margin-top: 23px"
                      v-model="filterState.timeSpentStatus"
                      @change="handleChange"
                    >
                      <option
                        :value="item.value"
                        v-for="item in timeSpenStatusDropdown"
                      >
                        {{ item.text }}
                      </option>
                    </select>
                  </div>
                  <div class="col-md-2 form-group mb-3">
                    <label for="time">Time Spent</label>
                    <input
                      v-model="filterState.timeSpent"
                      id="time"
                      placeholder="hh:mm:ss"
                      class="form-control"
                      name="time"
                      type="text"
                      value=""
                      autocomplete="off"
                      :disabled="filterState.timeSpentStatus === '4'"
                    />
                  </div>

                  <div class="col-md-2 form-group mb-3">
                    <label for="activedeactivestatus">Patient Status</label>
                    <select
                      id="activedeactivestatus"
                      name="activedeactivestatus"
                      class="custom-select show-tick"
                      v-model="filterState.patientStatus"
                    >
                      <option selected :value="null">
                        All (Active,Suspended,Deactivated,Deceased)
                      </option>
                      <option value="1">Active</option>
                      <option value="0">Suspended</option>
                      <option value="2">Deactivated</option>
                      <option value="3">Deceased</option>
                    </select>
                  </div>

                  <div class="row col-md-3 mb-2">
                    <div class="col-md-5">
                      <button
                        type="button"
                        class="btn btn-primary mt-4"
                        id="month-search"
                        @click="fetchMonthlyBillableReportApiData"
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
import LayoutComponent from "../../LayoutComponent.vue";
import moment from "moment";
import { startCase } from "lodash";
const title = "Daily Billable Report";
const layoutComponentRef = ref(null);
const passRowData = ref([]);
const isLoading = ref(false);
const practices = ref([]);
const practicegroup = ref([]);
const physicians = ref([]);
const currentMonth = moment().format("YYYY-MM");
const currentDate = moment().format("YYYY-MM-DD");
const cMonthStartDate = moment().startOf("month").format("YYYY-MM-DD");
const initialTimeSpent = "00:00:00";
const timeSpenStatusDropdown = [
  { text: "Greater Than", value: "2" },
  { text: "Less Than", value: "1" },
  { text: "Equal To", value: "3" },
  { text: "All", value: "4" },
];
const filterState = reactive({
  fromMonth: currentMonth,
  toMonth: currentMonth,
  patientStatus: null,
  organization: null,
  practiceId: null,
  fromDate: cMonthStartDate,
  toDate: currentDate,
  timeSpent: initialTimeSpent,
  taskStatus: null,
  moduleId: 3,
  timeSpentStatus: "4",
  provider: null,
});

onBeforeMount(() => {
  document.title = `${title} | Renova Healthcare`;
  fetchPracticegroup();
  fetchPractices();
  fetchProvider();
  fetchMonthlyBillableReportApiData();
});
const handleChange = async () => {
  if (filterState.timeSpentStatus === "4") {
    filterState.timeSpent = initialTimeSpent;
  } else {
    filterState.timeSpent = "00:20:00";
  }
};
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
  { headerName: "EMR/EHR ID", field: "pppracticeemr", flex: 2 },
  {
    headerName: "Patient",
    field: "full_name",
    cellRenderer: function (params) {
      const row = params.data;
      const camelCaseFullName = startCase(row.full_name);
      const pImg = row.pprofileimg
        ? row.pprofileimg
        : "/assets/images/faces/avatar.png";
      return `<div style="display: flex; align-items: center; gap: 3px">
                      <img src=${pImg} class='user-image' style="width: 50px; border-radius: 50%" />
                      <span style="margin-left: 4px;">${camelCaseFullName}</span>
                   </div>`;
    },
    flex: 3,
  },
  {
    headerName: "DOB",
    field: "pdob",
    cellRenderer: function (params) {
      const date = params.value;
      const formattedDate = formatDate(date);
      return formattedDate;
    },
  },
  {
    headerName: "Date of service",
    field: "ccsrecdate",
    cellRenderer: function (params) {
      const date = params.value;
      const formattedDate = formatDate(date);
      return formattedDate;
    },
  },
  { headerName: "CPT Code", field: "billingcode" },
  { headerName: "Unit", field: "unit", flex: 2 },

  { headerName: "Conditions", field: "pdcondition", flex: 4 },

  { headerName: "Practice", field: "pracpracticename", flex: 2 },
  { headerName: "Provider", field: "prprovidername" },
  {
    headerName: "Status",
    field: "pstatus",
    cellRenderer: (params) => {
      const value = params.value === 1 ? "Active" : "Deactive";
      return value;
    },
  },
  { headerName: "Minutes Spent", field: "ptrtotaltime", flex: 2 },
]);
const fetchProvider = async () => {
  filterState.provider = null;
  try {
    const response = await fetch(
      "../org/ajax/practice/" + filterState.practiceId + "/physicians"
    );
    if (!response.ok) {
      throw new Error("Failed to fetch practices");
    }
    const data = await response.json();
    physicians.value = data;
  } catch (error) {
    console.error("Error fetching practices:", error);
  }
};
const fetchPractices = async () => {
  filterState.practiceId = null;
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
    // caremanagerid
    if (!response.ok) {
      throw new Error("Failed to fetch practices");
    }
    const data = await response.json();
    practicegroup.value = data;
  } catch (error) {
    console.error("Error fetching practices:", error);
  }
};
const fetchMonthlyBillableReportApiData = async () => {
  try {
    isLoading.value = true;
    const response = await fetch(
      `/reports/daily-report/search/${filterState.organization}/${filterState.practiceId}/${filterState.provider}/${filterState.moduleId}/${filterState.fromDate}/${filterState.toDate}/${filterState.timeSpent}/${filterState.timeSpentStatus}/${filterState.patientStatus}?_=1714115105246`
    );
    if (!response.ok) {
      throw new Error("Failed to fetch patient list");
    }
    const data = await response.json();
    const processedData = data.data.map((row) => ({
      ...row,
      full_name: [row.pfname, row.pmname, row.plname].filter(Boolean).join(" "),
    }));

    passRowData.value = processedData || [];
  } catch (error) {
    console.error("Error fetching data:", error);
  } finally {
    isLoading.value = false;
  }
};

const handleReset = () => {
  filterState.organization = null;
  filterState.practiceId = null;
  filterState.patientStatus = null;
  filterState.fromDate = cMonthStartDate;
  filterState.toDate = currentDate;
  filterState.timeSpentStatus = "4";
  filterState.timeSpent = initialTimeSpent;
  filterState.provider = null;
  filterState.moduleId = 3;
  fetchPractices();
  fetchProvider();
  fetchMonthlyBillableReportApiData();
};
</script>
