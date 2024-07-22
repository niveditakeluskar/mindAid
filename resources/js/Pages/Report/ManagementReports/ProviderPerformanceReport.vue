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
              <form @submit.prevent="fetchProviderPerformanceReportApiData">
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
                      @change="fetchProvider(filterState.practiceId)"
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
                  <div class="row col-md-2 mb-2">
                    <div class="col-md-5">
                      <button
                        type="button"
                        class="btn btn-primary mt-4"
                        id="month-search"
                        @click="fetchProviderPerformanceReportApiData"
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
const title = "Provider Performance Report";
const layoutComponentRef = ref(null);
const passRowData = ref([]);
const isLoading = ref(false);
const practices = ref([]);
const practicegroup = ref([]);
const physicians = ref([]);
const filterState = reactive({
  organization: null,
  provider: null,
  practiceId: null,
  status: null,
});

onBeforeMount(() => {
  document.title = `${title} | Renova Healthcare`;
  fetchPracticegroup();
  fetchPractices();
  fetchProviderPerformanceReportApiData();
});
const columnDefs = ref([
  {
    headerName: "Sr. No.",
    valueGetter: "node.rowIndex + 1",
    flex: 1,
  },
  {
    headerName: "Practice",
    field: "practice",
    flex: 2,
  },
  {
    headerName: "Provider",
    field: "provider",
  },
  {
    headerName: "Total No. of Patients In CCM",
    field: "ccmview",
    flex: 1,
    cellRenderer: function (params) {
      const row = params.data;
      const ccmcount = row.ccmcount;
      const ccmview = row.ccmview;
      return `<div style="display: flex; align-items: center; gap: 3px">
                    <p class="mb-0">${ccmcount}</p>
                    ${ccmview}
                 </div>`;
    },
  },
  {
    headerName: "Total No. of Patients In RPM",
    field: "rpmview",
    cellRenderer: function (params) {
      const row = params.data;
      const rpmcount = row.rpmcount;
      const rpmview = row.rpmview;
      return `<div style="display: flex; align-items: center; gap: 3px">
                    <p class="mb-0">${rpmcount}</p>
                    ${rpmview}
                 </div>`;
    },
    flex: 1,
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

const fetchProviderPerformanceReportApiData = async () => {
  try {
    isLoading.value = true;
    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content");
    const response = await fetch(
      `/reports/provider-performance-report-search/${filterState.organization}/${filterState.practiceId}/${filterState.provider}/${filterState.status}?_=1715324427764`,
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
  filterState.provider = null;
  filterState.organization = null;
  filterState.practiceId = null;
  physicians.value = [];
  fetchPracticegroup();
  fetchPractices();
};
</script>
