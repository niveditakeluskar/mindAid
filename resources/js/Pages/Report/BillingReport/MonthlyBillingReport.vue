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
              <form @submit.prevent="fetchMonthlyBillingReportApiData">
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
                    <label for="activedeactivestatus">Status</label>
                    <select
                      id="activedeactivestatus"
                      name="activedeactivestatus"
                      class="custom-select show-tick"
                      v-model="filterState.status"
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
                  <div class="col-md-2 form-group mb-3">
                    <label for="activedeactivestatus"
                      >Call Answered Status</label
                    >
                    <select
                      id="activedeactivestatus"
                      name="activedeactivestatus"
                      class="custom-select show-tick"
                      v-model="filterState.callAnsweredStatus"
                    >
                      <option :value="null" selected="">All</option>
                      <option value="1">Call Answered</option>
                      <option value="0">
                        Call answered - not good time to call
                      </option>
                      <option value="2">Call Not Answered</option>
                    </select>
                  </div>
                  <div class="row col-md-2 mb-2">
                    <div class="col-md-5">
                      <button
                        type="button"
                        class="btn btn-primary mt-4"
                        id="month-search"
                        @click="fetchMonthlyBillingReportApiData"
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
const title = "Monthly Billable Report";
const layoutComponentRef = ref(null);
const passRowData = ref([]);
const isLoading = ref(false);
const practices = ref([]);
const practicegroup = ref([]);
const physicians = ref([]);
const currentDate = moment().format("YYYY-MM-DD");
const cMonthStartDate = moment().startOf("month").format("YYYY-MM-DD");
const filterState = reactive({
  organization: null,
  provider: null,
  practiceId: null,
  patientId: null,
  fromDate: cMonthStartDate,
  toDate: currentDate,
  status: null,
  callAnsweredStatus: null,
  moduleId: 3,
});

onBeforeMount(() => {
  document.title = `${title} | Renova Healthcare`;
  fetchPracticegroup();
  fetchPractices();
  fetchProvider();
  fetchMonthlyBillingReportApiData();
});
const columnDefs = ref([]);
const fetchPractices = async () => {
  try {
    const response = await fetch(
      "../org/ajax/practicegrp/" + filterState.organization + "/practice"
    );
    if (!response.ok) {
      throw new Error("Failed to fetch practices");
    }
    filterState.provider = null;
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
    filterState.provider = null;
    filterState.practiceId = null;
    const data = await response.json();
    practicegroup.value = data;
  } catch (error) {
    console.error("Error fetching practices:", error);
  }
};
const fetchProvider = async () => {
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

const fetchMonthlyBillingReportApiData = async () => {
  try {
    isLoading.value = true;
    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content");
    const response = await fetch(
      `/reports/monthly-billing-report/search/${filterState.organization}/${filterState.practiceId}/${filterState.provider}/${filterState.moduleId}/${filterState.fromDate}/${filterState.toDate}/${filterState.status}/${filterState.callAnsweredStatus}`,
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
    columnDefs.value = data.COLUMNS.map((col) => ({
      headerName: col.title,
      field: col.title,
      minWidth: 250,
    }));
    const processedData = data.DATA?.map((dataRow) => {
      const row = {};
      data.COLUMNS.forEach((col, index) => {
        row[col.title] = dataRow[index];
      });
      return row;
    });

    passRowData.value = processedData || [];
  } catch (error) {
    console.error("Error fetching data:", error);
  } finally {
    isLoading.value = false;
  }
};

const handleReset = () => {
  filterState.provider = null;
  filterState.organization = null;
  filterState.practiceId = null;
  filterState.status = null;
  filterState.callAnsweredStatus = null;
  filterState.fromDate = cMonthStartDate;
  filterState.toDate = currentDate;
  filterState.moduleId = 3;
  fetchPractices();
  fetchProvider();
  fetchMonthlyBillingReportApiData();
};
</script>
