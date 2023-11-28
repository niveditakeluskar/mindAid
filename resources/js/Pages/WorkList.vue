<template>
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
                  <select id="practices" class="custom-select show-tick select2" v-model="selectedPractice">
                    <option v-for="practice in practices" :key="practice.id" :value="practice.id">
                      {{ practice.name }}
                    </option>
                  </select>
                </div>
                <div class="col-md-6 form-group mb-6">
                  <label for="patientsname">Patient Name</label>
                  <!-- Your selectallworklistccmpatient component -->
                  <select id="patients" class="custom-select show-tick select2" v-model="selectedPatients">
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
                  <input v-model="timeValue" id="time" placeholder="hh:mm:ss" class="form-control" name="time" type="text"
                    value="" autocomplete="off">
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

            <div class="table-responsive">
              <table id="patient-list" class="display table table-striped table-bordered" style="width:100%">
                <thead>
                  <tr>
                    <th width="35px">Sr No.</th>
                    <th>EMR No.</th>
                    <th>Patient Name</th>
                    <th>DOB</th>
                    <th>Practice</th>
                    <th width="120px">Last contact Date</th>
                    <th width="115px">Total Time Spent</th>
                    <th>Action</th>
                    <th width="35px">Patient Status</th>
                    <!-- <th width="35px">Add'l Act</th> -->
                    <th width="35px">Call Score</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="app"></div>
    <!-- Start Modal -->
    <div id="add-activities" class="modal fade" role="dialog">
      <!-- Modal Content -->
    </div>
    <!-- End Modal -->
  </div>
</template>
<script>
import $ from 'jquery';
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net';
import 'datatables.net-dt/css/jquery.dataTables.min.css'; // Import DataTables CSS

DataTable.use(DataTablesCore);
export default {
  components: {
    DataTable,
  },
  data() {
    return {
      dataTable: [],
      selectedPractice: null, // To store the selected practice ID
      practices: [], // Array to hold the fetched practices
      selectedPatient: null, // To store the selected Patient ID
      patients: [], // Array to hold the fetched Patients
      selectedOption: '1', // Default selected option
      timeValue: '00:20:00', // Default time value
      activedeactivestatus: null,
      patientsmodules: null,
    };
  },
  watch: {
    selectedPractice(newValue) {
      // Fetch patients based on the selected practice ID
      this.fetchPatients(newValue);
    },
  },
  mounted() {
    // Fetch practices data (replace this with your actual data retrieval method)
    this.fetchPractices();
    this.fetchPatients();
    this.getPatientList();

  },
  computed: {
    placeholderText() {
      return this.selectedOption === '4' ? '' : 'hh:mm:ss';
    },
    isDisabled() {
      return this.selectedOption === '4';
    },
  },
  methods: {
    async fetchUserFilters() {
      try {
        const response = await fetch('/patients/getuser-filters');
        const data = await response.json();
        console.log(data);
        // Update form fields with API data
        this.selectedPractice = data.practice;
        this.selectedPatients = data.patient;
        this.selectedOption = data.timeoption;
        this.timeValue = data.time;
      } catch (error) {
        console.error('Error fetching user filters:', error);
      }
    },
    async fetchPractices() {
      try {
        const response = await fetch('../org/practiceslist');
        if (!response.ok) {
          throw new Error('Failed to fetch practices');
        }
        const data = await response.json();
        this.practices = data; // Assuming the API response returns an array of practices
      } catch (error) {
        console.error('Error fetching practices:', error);
        // Handle the error appropriately (show a message, retry, etc.)
      }
    },

    async fetchPatients(practiceId) {
      try {
        if (practiceId === undefined || practiceId === null) {
          console.error('Practice ID is empty or invalid.');
          return; // Don't proceed with the fetch if practiceId is empty or invalid
        }

        const response = await fetch('/patients/ajax/rpmpatientlist/' + practiceId + '/patientlist'); // Call the API endpoint
        if (!response.ok) {
          throw new Error('Failed to fetch patients');
        }
        const data = await response.json();
        this.patients = data; // Set the fetched patients to the component data
      } catch (error) {
        console.error('Error fetching patients:', error);
        // Handle the error appropriately (show a message, retry, etc.)
      }
    },

    async fetchTime() {
      try {
        const response = await fetch('./gettime'); // Call the API endpoint
        if (!response.ok) {
          throw new Error('Failed to fetch Time');
        }
        const data = await response.json();
        console.log(data);
        //this.patients = data; // Set the fetched patients to the component data
      } catch (error) {
        console.error('Error fetching patients:', error);
        // Handle the error appropriately (show a message, retry, etc.)
      }
    },

    handleChange() {
      if (this.selectedOption === '4') {
        this.timeValue = '';
      } else {
        this.timeValue = '00:20:00'; // Set a default time value for other options
      }
    },
    async handleSubmit() {
      try {
        await this.getPatientList();
        // Destroy and reinitialize DataTable on form submission


      } catch (error) {
        console.error('Error fetching data:', error);
      }
    },
    handleReset() {
      // Reset form fields and table data
      this.selectedPractice = '';
      this.selectedPatients = [];
      this.dataTable = [];
    },
    async getPatientList() {
      try {
        // Perform an API call to fetch patient list
        // Replace this with your actual API call

        const practice_id = this.selectedPractice;
        const patient_id = this.selectedPatient;
        const module_id = this.patientsmodules;
        const timeoption = this.selectedOption;
        const time = this.timeValue;
        const activedeactivestatus = this.activedeactivestatus;

        // Make an API call using fetch or Axios, passing the required parameters
        // Example using fetch:
        const response = await fetch(`/patients/worklist/${practice_id}/${patient_id}/${module_id}/${timeoption}/${time}/${activedeactivestatus}`);
        if (!response.ok) {
          throw new Error('Failed to fetch patient list');
        }
        const data = await response.json();
        console.log(data);
        // Update dataTable with the fetched data
        console.log(data.data);
        this.dataTable = data.data; // Replace data with the actual fetched data
        this.initDataTable();
      } catch (error) {
        console.error('Error fetching patient list:', error);
      }
    },
    initDataTable() {
      if ($.fn.DataTable.isDataTable('#patient-list')) {
    $('#patient-list').DataTable().destroy();
  }
  const columns = [
  { title: 'Sr. No.', render: function(data, type, row, meta) {
      // Use meta.row to get the index of the row and add 1 for the serial number
      return meta.row + 1;
    }
  },
  { title: 'EMR No.', data: 'pppracticeemr' },
  { title: 'Patient Name', data: 'pfname', render: function(data, type, row) {
      // Customize the display of the patient's name (first name and last name)
      return row.pfname + ' ' + row.plname;
    }
  },
  { title: 'DOB', data: 'pdob' },
  { title: 'Practice', data: 'pracpracticename' },
  { title: 'Last contact Date', data: 'csslastdate' },
  { title: 'Total Time Spent', data: 'ptrtotaltime' },
  { title: 'Action', data: 'action' },
  { title: 'Patient Status', data: 'activedeactive' },
  { title: 'Call Score', data: 'pssscore' },
];
        // Initialize DataTable with your options
         $('#patient-list').DataTable({
        columns: columns,
        data: this.dataTable, // Assign the data directly here
        paging: true,
        searching: true,
   
        });

        $('#patient-list').DataTable().clear().rows.add(this.dataTable).draw();

    },
  },
  created() {
    // Fetch user filters when the component is created
    this.fetchUserFilters();
    // Initialize DataTable on component creation
  },
  // Ensure to call initDataTable() when the component is updated or mounted
  updated() {
    this.initDataTable();
  },
};
</script>

<style>@import 'datatables.net-dt';
@import 'datatables.net-dt/css/jquery.dataTables.min.css'; /* Import DataTables CSS */
</style>