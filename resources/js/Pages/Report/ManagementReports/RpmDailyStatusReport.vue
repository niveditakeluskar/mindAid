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
      <div class="d-flex justify-content-center" style="gap: 25px">
        <div class="mb-4" v-for="item in statusColumn">
          <div class="card text-left" style="height: 130px; width: 350px">
            <div
              class="card-body text-center d-flex align-items-center"
              style="gap: 10px"
            >
              <i _ngcontent-rei-c8="" class="i-style" :class="item.icon"></i>
              <div class="content" style="flex: 70%">
                <h5 class="card-title text-muted">{{ item.text }}</h5>
                <div>
                  <p class="card-text text-center">
                    <a
                      class="text-mutedlead text-primary text-24 mb-2"
                      :href="item.href"
                      target="_blank"
                      >{{ patientSummry?.[item.value1]?.[0]?.count }}
                    </a>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 mb-4">
          <div class="card text-left">
            <div class="card-body">
              <form @submit.prevent="fetchRpmDailyStatusReportApiData">
                <div class="form-row">
                  <div class="col-md-2 form-group mb-2">
                    <label for="practicename">Practice Name</label>
                    <select
                      id="practices"
                      class="custom-select show-tick select2"
                      data-live-search="true"
                      v-model="filterState.practiceId"
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
                    <label for="from_date">Enrolled From</label>
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
                  <div class="row col-md-2 mb-2">
                    <div class="col-md-5">
                      <button
                        type="button"
                        class="btn btn-primary mt-4"
                        id="month-search"
                        @click="fetchRpmDailyStatusReportApiData"
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
  computed,
  watch,
} from "../../commonImports";
import moment from "moment";
import { startCase } from "lodash";
import LayoutComponent from "../../LayoutComponent.vue";
const title = "Rpm Daily Status Report";
const layoutComponentRef = ref(null);
const passRowData = ref([]);
const isLoading = ref(false);
const practices = ref([]);
const currentDate = moment().format("YYYY-MM-DD");
const patientSummry = ref({});
const filterState = reactive({
  practiceId: null,
  fromDate: currentDate,
});

onBeforeMount(() => {
  document.title = `${title} | Renova Healthcare`;
  fetchRpmPatientSummeryApiData();
  fetchPractices();
  fetchRpmDailyStatusReportApiData();
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
    headerName: "Practice",
    field: "practices",
  },
  {
    headerName: "Total Enrolled",
    field: "enrolledcount",
  },
  {
    headerName: "Newly Enrolled in this Month (May)",
    field: "newlyenrolled",
  },
  {
    headerName: "Patient Devices Link",
    field: "patientdeviceslink",
  },

  {
    headerName: "Patients (Readings not received for last 3 days)",
    field: "noreadingforlasthtreedayscount",
    cellRenderer: function (params) {
      const row = params.data;
      const practiceId = row.practiceid;
      const noreadingforlasthtreedayscount = row.noreadingforlasthtreedayscount;
      return `<div style="display: flex; align-items: center; gap: 3px">
                  <p class="mb-0">${noreadingforlasthtreedayscount}</p>
                  <a style="margin-left: 4px;" href='/reports/noreadingslastthreedaysInRPM-patients-details/${practiceId}' target="_blank">Detail</a>
               </div>`;
    },
    flex: 2,
  },
]);
const statusColumn = [
  {
    text: "Total Patients",
    value1: "totalRpmPatient",
    value2: null,
    icon: "i-Myspace",
    href: "/reports/total-rpm-patients-details",
  },
  {
    text: "Enrolled/Suspended",
    value1: "totalnewlyenrolled",
    value2: null,
    icon: "i-Add-UserStar",
    href: "/reports/newenrolled-patients-details",
  },
];
//   Not Sure About API end point
const fetchPractices = async () => {
  try {
    const response = await fetch("../org/practiceslist");
    if (!response.ok) {
      throw new Error("Failed to fetch practices");
    }
    const data = await response.json();
    practices.value = data;
  } catch (error) {
    console.error("Error fetching practices:", error);
  }
};

const fetchRpmDailyStatusReportApiData = async () => {
  try {
    isLoading.value = true;
    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content");
    const response = await fetch(
      `/reports/rpm-daily-status-report/search/${filterState.practiceId}/${filterState.fromDate}?_=1715324427764`,
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
    passRowData.value = data.data || [];
  } catch (error) {
    console.error("Error fetching data:", error);
  } finally {
    isLoading.value = false;
  }
};
const fetchRpmPatientSummeryApiData = async () => {
  try {
    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content");
    const response = await fetch("/reports/rpm-patient-summary", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": csrfToken,
      },
    });
    if (!response.ok) {
      throw new Error("Failed to fetch practices");
    }
    const data = await response.json();
    patientSummry.value = data;
  } catch (error) {
    console.error("Error fetching practices:", error);
  }
};

const handleReset = () => {
  filterState.practiceId = null;
  filterState.fromDate = currentDate;
  fetchPractices();
};
</script>
<style scoped>
.i-style {
  font-size: 4rem;
  color: rgba(44, 184, 234, 0.28);
}
</style>
