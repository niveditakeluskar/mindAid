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
              <form @submit.prevent="fetchQuestionaireReportApiData">
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
                    <label for="activedeactivestatus">Steps</label>
                    <select
                      id="genquestionselection"
                      class="custom-select show-tick mb-3 bottom"
                      name="genquestionselection"
                      v-model="filterState.steps"
                    >
                      <option :value="null">Select General Question</option>
                      <option value="144">CCM-Test</option>
                      <option value="142">CCM-UpdateQ</option>
                      <option value="38">General Questions</option>
                      <option value="49">Alcohol</option>
                      <option value="51">Smoking/Tobacco/Nicotine</option>
                      <option value="48">Sleep</option>
                      <option value="39">Hypertensive Emergency</option>
                      <option value="40">Grant Related Question</option>
                      <option value="43">Family Questionnaire</option>
                      <option value="132">PHQ 2/9</option>
                      <option value="137">(Research Only) Diabete</option>
                      <option value="159">Quality Measures</option>
                      <option value="138">(Research Only) Hypertension</option>
                      <option value="134">
                        (Research Only) Diabetes Emergency
                      </option>
                      <option value="131">(Research Only) Diabetes</option>
                      <option value="139">
                        (Research Only) Diabetes Build a Healthy Plate
                      </option>
                      <option value="140">
                        (Research Only) Diabetes Physical Activity Game Plan
                      </option>
                      <option value="141">
                        (Research Only) Diabetes Social Fitness Game Plan
                      </option>
                      <option value="136">Game Plan 1</option>
                      <option value="146">
                        (Research Only) COPD Build A Healthy Plate
                      </option>
                    </select>
                  </div>
                  <div class="row col-md-2 mb-2">
                    <div class="col-md-5">
                      <button
                        type="button"
                        class="btn btn-primary mt-4"
                        id="month-search"
                        @click="fetchQuestionaireReportApiData"
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
const title = "Questionaire Report";
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
  fromDate: cMonthStartDate,
  toDate: currentDate,
  steps: null,
});

onBeforeMount(() => {
  document.title = `${title} | Renova Healthcare`;
  fetchPracticegroup();
  fetchPractices();
  fetchQuestionaireReportApiData();
});
const columnDefs = ref([
  {
    headerName: "Sr. No.",
    valueGetter: "node.rowIndex + 1",
    flex: 1,
  },
  {
    headerName: "Year Month",
    field: "month_year",
  },
  {
    headerName: "BMI Count",
    field: "bmicount",
  },
  {
    headerName: "BP Count",
    field: "bpcount",
  },
  { headerName: "BMI Greater Than 25", field: "bmi_greater_25", flex: 2, minWidth: 200 },
  { headerName: "BMI Less Than 18", field: "bmi_less_18", flex: 2, minWidth: 200 },
  {
    headerName: "BP Systolic 140 And Diastolic 90 & Greater",
    field: "bp_140_90",
    flex: 2,
    minWidth: 200
  },
  {
    headerName: "BP Systolic 140 And Diastolic 110 & Greater",
    field: "bp_180_110",
    flex: 2,
    minWidth: 200
  },
  {
    headerName: "HgA1c Greater Than 7",
    field: "hga1c_greater_7",
    flex: 2,
    minWidth: 200
  },
  {
    headerName: "HgA1c Less Than 6.9",
    field: "hga1c_less_7",
    flex: 2,
    minWidth: 200
  },

  {
    headerName: "Questions",
    field: "topic",
  },
  {
    headerName: "Answers",
    field: "option",
  },
  { headerName: "Patient Count", field: "count", minWidth: 150 },
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

const fetchQuestionaireReportApiData = async () => {
  try {
    isLoading.value = true;
    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content");
    const response = await fetch(
      `/reports/questionaire_list/search/${filterState.organization}/${filterState.practiceId}/${filterState.provider}/${filterState.fromDate}/${filterState.toDate}/${filterState.steps}?_=1715324427764`,
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
  filterState.steps = null;
  filterState.practiceId = null;
  filterState.fromDate = cMonthStartDate;
  filterState.toDate = currentDate;
  physicians.value = [];
  fetchPracticegroup();
  fetchPractices();
};
</script>
