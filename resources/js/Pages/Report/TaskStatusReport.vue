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
              <form @submit.prevent="fetchTaskStatusReportApiData">
                <div class="form-row">
                  <div class="col-md-3 form-group mb-3">
                    <label for="user">Users</label>
                    <select
                      id="user"
                      class="custom-select show-tick select2"
                      data-live-search="true"
                      v-model="filterState.userId"
                    >
                      <option :value="null">Select User</option>
                      <option
                        v-for="user in users"
                        :key="user.id"
                        :value="user.id"
                      >
                        {{ user.name }}
                      </option>
                    </select>
                  </div>
                  <div class="col-md-3 form-group mb-3">
                    <label for="taskStatus">Task Status</label>
                    <select
                      id="taskStatus"
                      name="taskStatus"
                      class="custom-select show-tick"
                      v-model="filterState.taskStatus"
                    >
                      <option selected :value="null">
                        All (Completed,Pending)
                      </option>
                      <option value="1">Completed</option>
                      <option value="0">Pending</option>
                    </select>
                  </div>
                  <div class="col-md-3 form-group mb-2">
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
                  <div class="col-md-3 form-group mb-2">
                    <label for="practicename">Practice</label>
                    <select
                      id="practices"
                      class="custom-select show-tick select2"
                      data-live-search="true"
                      v-model="filterState.practiceId"
                      @change="fetchPatientList(filterState.practiceId)"
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
                  <div
                    class="col-md-3 form-group mb-2"
                    v-if="filterState.practiceId"
                  >
                    <label for="patient">Patient</label>
                    <select
                      id="patient"
                      class="custom-select show-tick select2"
                      data-live-search="true"
                      v-model="filterState.patientId"
                    >
                      <option :value="null">Select Patient</option>
                      <option
                        v-for="patient in patinetList"
                        :key="patient.id"
                        :value="patient.id"
                      >
                        {{
                          `${patient.fname} ${patient.mname} ${patient.lname}`
                        }}
                      </option>
                    </select>
                  </div>
                  <div class="col-md-3 form-group mb-3">
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
                  <div class="col-md-3 form-group mb-3">
                    <label for="score">Score</label>
                    <select
                      id="score"
                      name="score"
                      class="custom-select show-tick"
                      v-model="filterState.score"
                    >
                      <option :value="null">Select Score</option>
                      <option
                        :value="item"
                        v-for="item in Array.from({ length: 8 }, (_, i) => i)"
                      >
                        {{ item }}
                      </option>
                    </select>
                  </div>
                  <div class="col-md-3 form-group mb-3">
                    <label for="from_date">Task Start Date</label>
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
                  <div class="col-md-3 form-group mb-3">
                    <label for="to_date">Task End Date</label>
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
                  <div class="col-md-3 form-group mb-3">
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
                  <div class="col-md-3 form-group mb-3">
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
                    />
                  </div>

                  <div class="row col-md-3 mb-2">
                    <div class="col-md-5">
                      <button
                        type="button"
                        class="btn btn-primary mt-4"
                        id="month-search"
                        @click="fetchTaskStatusReportApiData"
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
} from "../commonImports";
import LayoutComponent from "../LayoutComponent.vue";
import moment from "moment";
import axios from "axios";
import { startCase } from "lodash";
const title = "Task Status Report";
const layoutComponentRef = ref(null);
const passRowData = ref([]);
const isLoading = ref(false);
const practices = ref([]);
const practicegroup = ref([]);
const patinetList = ref([]);
const users = ref([]);
const currentMonth = moment().format("YYYY-MM");
const currentDate = moment().format("YYYY-MM-DD");
const cMonthStartDate = moment().startOf("month").format("YYYY-MM-DD");
const initialTimeSpent = "00:20:00";
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
  userId: null,
  practiceId: null,
  fromDate: cMonthStartDate,
  toDate: currentDate,
  activity: null,
  timeSpent: initialTimeSpent,
  taskStatus: null,
  score: null,
  timeSpentStatus: "2",
  patientId: null,
});

onBeforeMount(() => {
  document.title = `${title} | Renova Healthcare`;
  fetchPracticegroup();
  fetchPractices();
  fetchUser();
  fetchTaskStatusReportApiData();
});
const handleChange = async () => {
  if (filterState.timeSpentStatus === "4") {
    filterState.timeSpent = "00:00:00";
  } else {
    filterState.timeSpent = initialTimeSpent;
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
  { headerName: "Assigned Care Manager", field: "cm", flex: 2 },
  { headerName: "Practice Name", field: "pracpracticename", flex: 2 },
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
                    <img :src=${pImg} class='user-image' style="width: 50px; border-radius: 50%" />
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
  { headerName: "Task", field: "tmtask", flex: 2 },
  {
    headerName: "Task Date",
    field: "tmtaskdate",
    cellRenderer: function (params) {
      const date = params.value;
      const formattedDate = formatDate(date);
      return formattedDate;
    },
  },
  { headerName: "Task Status", field: "tmtaskstatus" },
  {
    headerName: "Task Completion Date",
    field: "tmtaskcompletiondate",
    cellRenderer: function (params) {
      const date = params.value;
      const formattedDate = formatDate(date);
      return formattedDate;
    },
  },
  { headerName: "Task Created By", field: "task_created_by" },
  { headerName: "Patient Score", field: "tmscore" },
  { headerName: "Module", field: "mmodulename" },
  { headerName: "Component", field: "mccomponentsname", flex: 2 },
  { headerName: "Task Type", field: "fufollowuptaskcategory", flex: 2 },
  { headerName: "Task Notes", field: "tmtasknotes", flex: 2 },
  {
    headerName: "Reassign Care Manager",
    field: "action",
    cellRenderer: (params) => {
      const row = params.data;
      const taskId = row.tmtaskid;
      const pid = row.pid;
      const action = row.action;
      const parser = new DOMParser();
      const doc = parser.parseFromString(action, "text/html");
      const selectElement = doc.querySelector("select");
      if (!selectElement) {
        return "Error parsing HTML"; // Return an error message if parsing fails
      }
      const link = document.createElement("select");
      const options = Array.from(selectElement.options).map((option) => {
        const optionElement = document.createElement("option");
        optionElement.value = option.value ?? null;
        optionElement.text = option.text;
        if (option.selected) {
          optionElement.selected = true;
        }
        return optionElement;
      });
      options.forEach((option) => link.appendChild(option));
      // Attach event listener to the select element
      link.addEventListener("change", (event) => {
        const user = event.target.value;
        reassignPatientandTask(pid, taskId, user); // Call the method
      });
      return link;
    },
    flex: 2,
  },
  {
    headerName: "Status",
    field: "action2",
    cellRenderer: (params) => {
      const row = params.data;
      const taskId = row.tmtaskid;
      const action = row.action2;
      const parser = new DOMParser();
      const doc = parser.parseFromString(action, "text/html");
      const selectElement = doc.querySelector("select");
      if (!selectElement) {
        return "Error parsing HTML"; // Return an error message if parsing fails
      }
      const link = document.createElement("select");
      const options = Array.from(selectElement.options).map((option) => {
        const optionElement = document.createElement("option");
        optionElement.value = option.value ?? null;
        optionElement.text = option.text;
        if (option.selected) {
          optionElement.selected = true;
        }
        return optionElement;
      });
      options.forEach((option) => link.appendChild(option));
      // Attach event listener to the select element
      link.addEventListener("change", (event) => {
        const status = event.target.value;
        todoliststatus(taskId, status); // Call the method
      });
      return link;
    },
    flex: 2,
  },
]);

const todoliststatus = async (taskid, status_flag) => {
  try {
    isLoading.value = true;
    const obj = new URLSearchParams({
      taskid: taskid,
      status_flag: status_flag,
    });
    axios.defaults.headers.common["X-CSRF-TOKEN"] = document.querySelector(
      'meta[name="csrf-token"]'
    ).content;
    await axios.post(`/reports/task-status-report-statuschange`, obj);
  } catch (error) {
    console.error("Error fetching data:", error);
  } finally {
    isLoading.value = false;
  }
};

const reassignPatientandTask = async (patient, taskid, user) => {
  try {
    isLoading.value = true;
    const obj = new URLSearchParams({
      patient: patient,
      user: user,
      taskid: taskid,
    });
    axios.defaults.headers.common["X-CSRF-TOKEN"] = document.querySelector(
      'meta[name="csrf-token"]'
    ).content;
    await axios.post(`/reports/task-status-report-user-form`, obj);
  } catch (error) {
    console.error("Error fetching data:", error);
  } finally {
    isLoading.value = false;
  }
};
const fetchPatientList = async (id) => {
  try {
    const response = await fetch(
      "../patients/ajax/patientlist/" + filterState.practiceId + "/patientlist"
    );
    if (!response.ok) {
      throw new Error("Failed to fetch practices");
    }
    const data = await response.json();
    patinetList.value = data;
  } catch (error) {
    console.error("Error fetching practices:", error);
  }
};
const fetchPractices = async (id) => {
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
const fetchUser = async () => {
  try {
    const response = await fetch("/reports/users");
    if (!response.ok) {
      throw new Error("Failed to fetch practices");
    }
    const data = await response.json();
    const rows = Object.entries(data);
    const processData = rows?.map((item) => {
      return {
        id: item[0],
        name: item[1],
      };
    });
    users.value = processData || [];
  } catch (error) {
    console.error("Error fetching practices:", error);
  }
};
const fetchTaskStatusReportApiData = async () => {
  try {
    isLoading.value = true;
    const response = await fetch(
      `/reports/task-status-report-search/${filterState.userId}/${filterState.organization}/${filterState.practiceId}/${filterState.patientId}/${filterState.taskStatus}/${filterState.fromDate}/${filterState.toDate}/${filterState.patientStatus}/${filterState.score}/${filterState.timeSpentStatus}/${filterState.timeSpent}?_=1714115105246`
    );
    if (!response.ok) {
      throw new Error("Failed to fetch patient list");
    }
    const data = await response.json();
    const processedData = data.data.map((row) => ({
      ...row,
      full_name: [row.pfname, row.pmname, row.plname].filter(Boolean).join(" "),
      cm: [row.caremanagerfname, row.caremanagerlname]
        .filter(Boolean)
        .join(" "),
      task_created_by: [row.createdbyfname, row.createdbylname]
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
  filterState.userId = null;
  filterState.organization = null;
  filterState.practiceId = null;
  filterState.patientStatus = null;
  filterState.fromDate = cMonthStartDate;
  filterState.toDate = currentDate;
  filterState.timeSpentStatus = "2";
  filterState.timeSpent = initialTimeSpent;
  filterState.patientId = null;
  filterState.score = null;
  filterState.taskStatus = null;
  fetchPracticegroup();
  fetchPractices();
  fetchTaskStatusReportApiData();
};
</script>
