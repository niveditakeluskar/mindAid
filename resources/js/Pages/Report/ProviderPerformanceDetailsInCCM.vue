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
        <div class="col-md-6 mb-4">
          <div class="card text-left">
            <div class="card-body">
              <div class="form-row">
                <h6 class="card-title mb-3">Practice :</h6>
                &nbsp;&nbsp;&nbsp; {{ first(practiceName)?.name }}
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="card text-left">
            <div class="card-body">
              <div class="form-row">
                <h6 class="card-title mb-3">Provider :</h6>
                &nbsp;&nbsp;&nbsp; {{ first(providerName)?.name }}
              </div>
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
import { first } from "lodash";
import { startCase } from "lodash";
const title = "Provider Performance Patients Details In CCM";
const layoutComponentRef = ref(null);
const passRowData = ref([]);
const isLoading = ref(false);

const props = defineProps({
  practiceId: {
    type: String,
    required: true,
  },
  practiceName: {
    type: Array,
    required: true,
  },
  providerId: {
    type: String,
    required: true,
  },
  providerName: {
    type: Array,
    required: true,
  },
});

onBeforeMount(() => {
  document.title = `${title} | Renova Healthcare`;
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
    field: "pppracticeemr",
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
    headerName: "Email",
    field: "pemail",
  },
  {
    headerName: "Action",
    field: "editPatient",
    cellRenderer: function (params) {
      const data = params.value;
      return data;
    },
  },
]);

const fetchRpmPatientDetailsApiData = async () => {
  console.log(props.practiceId, props.providerId, "ddfasf");
  try {
    isLoading.value = true;
    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content");
    const response = await fetch(
      `/reports/get-data-patient-details-in-ccm/${props.practiceId}/${props.providerId}?_=1715324427764`,
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
