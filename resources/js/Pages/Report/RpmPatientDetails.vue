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
                <form @submit.prevent="fetchRpmPatientDetailsApiData">
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
                    <div class="row col-md-2 mb-2">
                      <div class="col-md-5">
                        <button
                          type="button"
                          class="btn btn-primary mt-4"
                          id="month-search"
                          @click="fetchRpmPatientDetailsApiData"
                        >
                          Search
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
  } from "../commonImports";
  import moment from "moment";
  import LayoutComponent from "../LayoutComponent.vue";
  import { startCase } from "lodash";
  const title = "Readings Not Received Last Three Days In RPM Details";
  const layoutComponentRef = ref(null);
  const passRowData = ref([]);
  const isLoading = ref(false);
  const practices = ref([]);
  const filterState = reactive({
    practiceId: null,
  });
  const props = defineProps({
    practiceId: {
      type: String,
      required: true,
    },
  });
  
  onBeforeMount(() => {
    document.title = `${title} | Renova Healthcare`;
    filterState.practiceId = props.practiceId;
    fetchPractices();
    fetchRpmPatientDetailsApiData();
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
      headerName: "EMR/EHR ID",
      field: "pracpracticeemr",
    },
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
      headerName: "Practice",
      field: "prapracticename",
    },
  ]);
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
  
  const fetchRpmPatientDetailsApiData = async () => {
    try {
      isLoading.value = true;
      const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
      const response = await fetch(
        `reports/noreadingslastthreedaysInRPM/search/${filterState.practiceId}?_=1715324427764`,
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
      }));
  
      passRowData.value = processedData || [];
    } catch (error) {
      console.error("Error fetching data:", error);
    } finally {
      isLoading.value = false;
    }
  };
  </script>
  