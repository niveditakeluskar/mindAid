<template>
  <LayoutComponent>
    <div>
      <div class="breadcrusmb">
        <div class="row" style="margin-top: 10px">
          <div class="col-md-8">
            <h4 class="card-title mb-3">Work List</h4>
          </div>
          <div class="form-group col-md-4"></div>
        </div>
      </div>
      <div class="separator-breadcrumb border-top"></div>
      <div id='success'></div>
      <div class="row">
        <div class="col-md-12 mb-4">
          <div class="card text-left">
            <div class="card-body">
              <form @submit.prevent="handleSubmit">
                <div class="form-row">
                  <div class="col-md-6 form-group mb-6">
                    <label for="practicename">Practice Name</label>
                    <!-- Your selectworklistpractices component -->
                    <select id="practices" class="custom-select show-tick select2" v-model="selectedPractice"
                      @change="handlePracticeChange">
                      <option v-for="practice in practices" :key="practice.id" :value="practice.id">
                        {{ practice.name }}
                      </option>
                    </select>
                  </div>
                  <div class="col-md-6 form-group mb-6">
                    <label for="patientsname">Patient Name</label>
                    <!-- Your selectallworklistccmpatient component -->
                    <select id="patients" class="custom-select show-tick select2" v-model="selectedPatients">
                      <option value="" selected>All Patients</option>
                      <option v-for="patient in patients" :key="patient.id" :value="patient.id">
                        {{ patient.fname }} {{ patient.mname }} {{ patient.lname }}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-3 form-group mb-3">
                    <select id="timeoption" class="custom-select show-tick" style="margin-top: 23px;"
                      v-model="selectedOption" @change="handleChange">
                      <option value="2">Greater than</option>
                      <option value="1" selected>Less than</option>
                      <option value="3">Equal to</option>
                      <option value="4">All</option>
                    </select>
                  </div>
                  <div class="col-md-3 form-group mb-3">
                    <label for="time">Time Spent</label>
                    <input v-model="timeValue" id="time" placeholder="hh:mm:ss" class="form-control" name="time"
                      type="text" value="" autocomplete="off">
                  </div>
                  <div class="col-md-3 form-group mb-3">
                    <label for="module">Module</label>
                    <select name="modules" id="modules" v-model="patientsmodules" class="custom-select show-tick">
                      <option value="3" selected>CCM</option>
                      <option value="2">RPM</option>
                    </select>
                  </div>
                  <div class="col-md-3 form-group mb-3">
                    <label for="activedeactivestatus">Patient Status</label>
                    <select id="activedeactivestatus" v-model="activedeactivestatus" name="activedeactivestatus"
                      class="custom-select show-tick">
                      <option value="" selected>All (Active,Suspended,Deactivated,Deceased)</option>
                      <option value="1">Active</option>
                      <option value="0">Suspended</option>
                      <option value="2">Deactivated</option>
                      <option value="3">Deceased</option>
                    </select>
                  </div>
                  <div class="col-md-3 form-group mb-3">
                    <button type="submit" class="btn btn-primary mt-4" @click="handleSearch">Search</button>
                    <button type="button" class="btn btn-primary mt-4" @click="handleReset">Reset</button>
                  </div>
                  <div class="col-md-8 form-group">
                    <h4 style="float: right;">
                      <label float-right="">Total Minutes Spent</label>
                      <label for="programs" class="cmtotaltimespent" data-toggle="tooltip" data-placement="right" title=""
                        data-original-title="Total minutes spent/Total No. of patients"
                        style="margin-left: 2px;margin-top: 10px;font-size: 16px;"></label>
                    </h4>

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
            
              <div class="table-responsive" style="height: 100vh;">
              <!--   <table id="patient-list" ref="dataTable" class="display table table-striped table-bordered"
                  style="width:100%">
                </table> -->
                <!-- Ag-Grid Vue component -->
                <ag-grid-vue
      style="width: 100%; height: 100%;"
      class="ag-theme-alpine"
      :columnDefs="columnDefs.value"
      :rowData="rowData.value"
      :defaultColDef="defaultColDef"
      :gridOptions="gridOptions"
      rowGroupPanelShow="always"
    ></ag-grid-vue>
              </div>

            </div>
          </div>
        </div>
      </div>
 
    </div>
 <!-- use the modal component, pass in the prop -->
 <modal :show="showModal" @close="showModal = false">
        <template #header>
          <h3>custom header</h3>
        </template>
      </modal>
  </LayoutComponent>
</template>

<script>
import {
  reactive,
  ref,
  onMounted,
  computed,
  watch,
  AgGridVue,
  // Add other common imports if needed
} from './commonImports';
import LayoutComponent from './LayoutComponent.vue'; // Import your layout component
import axios from 'axios';

export default {
  components: {
    LayoutComponent,
    AgGridVue,
  },
  setup() {
    const rowData = reactive({value:[]}); // Initialize rowData as an empty array
    const loading = ref(false);
    const tableInstance = ref(null); // Define tableInstance using ref()
    const showModal = ref(false);
    const selectedPractice = ref(null);
    const selectedPatients = ref(null);
    const dataTableLoaded = ref(false);
    const dataTable = ref([]);
    const practices = ref([]);
    const patients = ref([]);
    const selectedOption = ref(null);
    const timeValue = ref('00:20:00');
    const activedeactivestatus = ref(null);
    const patientsmodules = ref('3');
    // Compute the values with checks
    const practice_id = computed(() => selectedPractice.value);
    const patient_id = computed(() => selectedPatients.value);
    const module_id = computed(() => patientsmodules.value);
    const timeoption = computed(() => selectedOption.value);
    const time = computed(() => (timeValue.value === '' ? null : timeValue.value));
    const activedeactivestatusComputed = computed(() =>
      activedeactivestatus.value === '' ? null : activedeactivestatus.value
    );

    let columnDefs = reactive({value:[
      {
      headerName: 'Sr. No.',
      valueGetter: 'node.rowIndex + 1',
    },
      { headerName: 'EMR No.', field: 'pppracticeemr',filter: true },
      {
        headerName: 'Patient Name',
        field: 'pfname',
        valueGetter: function (params) {
          const row = params.data;
          return row && row.plname ? row.pfname + ' ' + row.plname : 'N/A';
        },
      },
      { headerName: 'DOB', field: 'pdob' },
      { headerName: 'Practice', field: 'pracpracticename' },
      { headerName: 'Last contact Date', field: 'csslastdate' },
      { headerName: 'Total Time Spent', field: 'ptrtotaltime' },
      { headerName: 'Action', field: 'action' },
      {
        headerName: 'Patient Status',
        field: 'activedeactive',
        cellRenderer: function (params) {
          const row = params.data;
          if (row.pstatus === 1 && row.pstatus !== undefined) {
            return `<a href="#" class="ActiveDeactiveClass" @click="callExternalFunctionWithParams('${row.pid}','${row.pstatus}')"><i class="i-Yess i-Yes" title="Patient Status"></i></a>`;
          } else {
            return `<a href="#" class="ActiveDeactiveClass" @click="callExternalFunctionWithParams('${row.pid}','${row.pstatus}')"><i class="text-20 i-Stopwatch" style="color: red;"></i></a>`;
          }
        },
      },
      { headerName: 'Call Score', field: 'pssscore' },
    ]});


    const defaultColDef = ref({
      sortable:true,
      flex:2,
    });

    const gridOptions = reactive({
  // other properties...
  pagination: true,
  paginationPageSize: 20, // Set the number of rows per page
  domLayout: 'autoHeight', // Adjust the layout as needed
});
    // Watch for changes in selectedPractice
    watch(selectedPractice, (newPracticeId) => {
      fetchPatients(newPracticeId);
    });

    onMounted(async () => {
      try {
        fetchPractices();
        fetchPatients();
        await fetchUserFilters();
        await getPatientList(
          selectedPractice.value === '' ? null : selectedPractice.value,
          selectedPatients.value === '' ? null : selectedPatients.value,
          patientsmodules.value === '' ? null : patientsmodules.value,
          selectedOption.value === '' ? null : selectedOption.value,
          timeValue.value === '' ? null : timeValue.value,
          activedeactivestatus.value === '' ? null : activedeactivestatus.value
        );
       

      } catch (error) {
        console.error('Error on page load:', error);
      }
    });



    // Similarly, define other methods like fetchPractices, fetchPatients, etc.

    const fetchPractices = async () => {
      try {
        const response = await fetch('../org/practiceslist');
        if (!response.ok) {
          throw new Error('Failed to fetch practices');
        }
        const data = await response.json();
        practices.value = data; // Assuming the API response returns an array of practices
      } catch (error) {
        console.error('Error fetching practices:', error);
        // Handle the error appropriately (show a message, retry, etc.)
      }
    };

    const fetchPatients = async (practiceId) => {
      try {
        if (practiceId === undefined || practiceId === null) {
          //console.error('Practice ID is empty or invalid.');
          return; // Don't proceed with the fetch if practiceId is empty or invalid
        }
        const response = await fetch('/patients/ajax/rpmpatientlist/' + practiceId + '/patientlist'); // Call the API endpoint
        if (!response.ok) {
          throw new Error('Failed to fetch patients');
        }
        const data = await response.json();
        patients.value = data; // Set the fetched patients to the component data
        return Promise.resolve(data);
      } catch (error) {
        console.error('Error fetching patients:', error);
        // Handle the error appropriately (show a message, retry, etc.)
        return Promise.reject(error);
      }
    };

    const fetchUserFilters = async () => {
      try {
        const response = await fetch('/patients/getuser-filters');
        const data = await response.json();
        // Fetch patients and wait for the data before updating selectedPatients
        //const fetchedPatients = await fetchPatients(data.practice);

        selectedPractice.value = data.practice;
        /*  if (fetchedPatients && fetchedPatients.length > 0) {
           selectedPatients.value = data.patient;
         } */
        selectedPatients.value = data.patient;
        selectedOption.value = data.timeoption;
        timeValue.value = data.time;
        activedeactivestatus.value = data.patientstatus;
      } catch (error) {
        console.error('Error fetching user filters:', error);
      }
    };

    const handleChange = async () => {
      if (selectedOption.value === '4') {
        timeValue.value = '';
      } else {
        timeValue.value = '00:20:00'; // Set a default time value for other options
      }
    };

    const handleSubmit = async () => {
      try {

        await getPatientList(
          selectedPractice.value === '' ? null : selectedPractice.value,
          selectedPatients.value === '' ? null : selectedPatients.value,
          patientsmodules.value === '' ? null : patientsmodules.value,
          selectedOption.value === '' ? null : selectedOption.value,
          timeValue.value === '' ? null : timeValue.value,
          activedeactivestatus.value === '' ? null : activedeactivestatus.value
        );
        // Destroy and reinitialize DataTable on form submission
        await saveFilters();

      } catch (error) {
        console.error('Error fetching data:', error);
      }
    };

    let pratices, patents, patentsmodules, tmeoption, tme, actvedeactivestatus;
    const saveFilters = async () => {
      try {
        pratices = selectedPractice.value || null;
        patents = selectedPatients.value || null;
        patentsmodules = patientsmodules.value || null;
        tmeoption = selectedOption.value || null;
        tme = timeValue.value || null;
        actvedeactivestatus = activedeactivestatus.value || null;
        console
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch(`/patients/worklist/saveuser-filters/${pratices}/${patents}/${patentsmodules}/${tmeoption}/${tme}/${actvedeactivestatus}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json', // Specify the content type
            'X-CSRF-TOKEN': csrfToken, // Include the CSRF token in the headers
          },
        });
        if (response.ok) {
        } else {
          console.error('Failed to save user filters:', response.statusText);
        }
      } catch (error) {
        console.error('Error saving user filters:', error);
      }
    };

    const handleReset = async () => {
      // Reset form fields and table data
      selectedPractice.value = null;
      selectedPatients.value = [];
    }

    const initDataTable = async () => {

      
    };

    const getPatientList = async (practice_id,
      patient_id,
      module_id,
      timeoption,
      time,
      activedeactivestatus) => {
      try {
        loading.value = true;
        await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
        const response = await fetch(`/patients/worklist/${practice_id}/${patient_id}/${module_id}/${timeoption}/${time}/${activedeactivestatus}`);
        if (!response.ok) {
          throw new Error('Failed to fetch patient list');
        }
        loading.value = false;
        const data = await response.json();
        rowData.value = data.data || []; // Replace data with the actual fetched data
        //initDataTable();
        console.log(rowData.value);
      } catch (error) {
        console.error('Error fetching patient list:', error);
        loading.value = false;
      }
    };

    const callExternalFunctionWithParams = (param1, param2) => {
      const activeDeactiveModal = document.getElementById('active-deactive');
      if (activeDeactiveModal) {
        $(activeDeactiveModal).modal('show'); // Use jQuery to show the modal
      } else {
        console.error('Modal element not found or jQuery/Bootstrap not properly loaded');
      }

      var sPageURL = window.location.pathname;
      parts = sPageURL.split("/"),
        patientId = parts[parts.length - 1];
      if ($.isNumeric(patientId) == true) {
        //patient list
        var patientId = $("#hidden_id").val();
        var module = $("input[name='module_id']").val();
        var status = $("#service_status").val();
        $('#enrolledservice_modules').val(module).trigger('change');
        $('#enrolledservice_modules').change();
      } else {
        //worklist 
        var patientId = param1;
        var selmoduleId = $("#modules").val();
        axios({
        method: "GET",
        url: `/patients/patient-module/${patientId}/patient-module`,
    }).then(function (response) {
        $('.enrolledservice_modules').html('');
      const  enr = response.data;
      var count_enroll = enr.length;
        for (var i = 0; i < count_enroll; i++) {
            $('.enrolledservice_modules').append(`<option value="${response.data[i].module_id}">${response.data[i].module.module}</option>`);
        }
        $("#enrolledservice_modules").val(selmoduleId).trigger('change');
        // $("#patient-Ajaxdetails-model").html(response.data);
    }).catch(function (error) {
        console.error(error, error.response);
    });
        var status = param2;
        $("form[name='active_deactive_form'] #worklistclick").val("1");
        $("form[name='active_deactive_form'] #patientid").val(patientId);
        $("form[name='active_deactive_form'] #date_value").hide();
        $("form[name='active_deactive_form'] #fromdate").hide();
        $("form[name='active_deactive_form'] #todate").hide();
        if (status == 0) {
          $("form[name='active_deactive_form'] #role1").show();
          $("form[name='active_deactive_form'] #role0").hide();
          $("form[name='active_deactive_form'] #role2").show();
          $("form[name='active_deactive_form'] #role3").show();
        }
        if (status == 1) {
          $("form[name='active_deactive_form'] #role1").hide();
          $("form[name='active_deactive_form'] #role0").show();
          $("form[name='active_deactive_form'] #role2").show();
          $("form[name='active_deactive_form'] #role3").show();
        }
        if (status == 2) {
          // $("form[name='active_deactive_form'] #status-title").text('Activate/Suspend Or Deceased Patient');
          $("form[name='active_deactive_form'] #role1").show();
          $("form[name='active_deactive_form'] #role0").show();
          $("form[name='active_deactive_form'] #role2").hide();
          $("form[name='active_deactive_form'] #role3").show();
        }
        if (status == 3) {
          $("form[name='active_deactive_form'] #role1").show();
          $("form[name='active_deactive_form'] #role0").show();
          $("form[name='active_deactive_form'] #role2").show();
          $("form[name='active_deactive_form'] #role3").hide();
        }
      }
    };

    return {
      loading,
      tableInstance,
      showModal,
      selectedPractice,
      selectedPatients,
      dataTableLoaded,
      columnDefs,
      rowData,
      defaultColDef,
      gridOptions,
      practices,
      patients,
      selectedOption,
      timeValue,
      activedeactivestatus,
      patientsmodules,
      handleSubmit,
      callExternalFunctionWithParams,
      handleChange,
    };
  },

};
</script>
<style>
@import 'ag-grid-community/styles/ag-grid.css';
@import 'ag-grid-community/styles/ag-theme-alpine.css'; /* Use the theme you prefer */

.loading-spinner {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100px;
  /* Adjust as needed */
}

</style>