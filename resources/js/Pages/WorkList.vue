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
            <div v-if="dataTableLoaded" class="table-responsive">
              <table id="patient-list" class="display table table-striped table-bordered" style="width:100%">
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

<!-- use the modal component, pass in the prop -->
<modal :show="showModal" @close="showModal = false">
   <template #header>
     <h3>custom header</h3>
   </template>
 </modal>

  

  </div>

</template>

<script>
import $ from 'jquery';
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net';
import 'jszip/dist/jszip.js';
import 'pdfmake/build/pdfmake.js';
import pdfMake from 'pdfmake/build/pdfmake';
import { vfs } from 'pdfmake/build/pdfmake'; // Import the vfs directly
import Buttons from 'datatables.net-buttons';
import 'datatables.net-dt/css/jquery.dataTables.min.css'; // Import DataTables CSS
import 'datatables.net-plugins/api/processing().mjs';
import 'datatables.net-buttons/js/dataTables.buttons.min.js';
import 'datatables.net-buttons/js/buttons.html5.min.js';
import 'datatables.net-buttons/js/buttons.print.min.js';
import 'datatables.net-buttons/js/buttons.colVis.min.js';
import Modal from './Modal.vue';
import { ref } from 'vue';

// Load the fonts dynamically using URLs
const fonts = {
  Roboto: {
    normal: '/fonts/Roboto-Regular.ttf',
    bold: '/fonts/Roboto-Medium.ttf',
    italics: '/fonts/Roboto-Italic.ttf',
    bolditalics: '/fonts/Roboto-MediumItalic.ttf',
  },
};
// Set the font data
pdfMake.vfs = vfs; // Use the vfs directly
pdfMake.fonts = fonts;
DataTable.use(DataTablesCore);
DataTable.use(Buttons);

export default {
  components: {
    DataTable,
    Modal
  },
  setup() {
    const showModal = ref(false);
    const selectedPractice = ref(null);
    const dataTableLoaded = ref(false);
    const dataTable = ref([]);
    const practices = ref([]);
    const selectedPatient = ref(null);
    const patients = ref([]);
    const selectedOption = ref('1');
    const timeValue = ref('00:20:00');
    const activedeactivestatus = ref(null);
    const patientsmodules = ref('3');

    return {
      showModal,
      selectedPractice,
      dataTableLoaded,
      dataTable,
      practices,
      selectedPatient,
      patients,
      selectedOption,
      timeValue,
      activedeactivestatus,
      patientsmodules
    };
  },
  watch: {
    selectedPractice(newValue) {
      // Fetch patients based on the selected practice ID
      this.fetchPatients(newValue);
    },
  },
  async mounted() {
    // Fetch practices data (replace this with your actual data retrieval method)
    this.fetchPractices();
    this.fetchPatients();
    await this.getPatientList();
    $('#patient-list').DataTable();
    // After DataTable initialization, attach click event listeners to the buttons
  $('#patient-list').on('click', '.ActiveDeactiveClass', (event) => {
    // Extract the row data using DataTable API
    const table = $('#patient-list').DataTable();
    const rowData = table.row($(event.target).closest('tr')).data();
    // Call the Vue method with the extracted parameters from the row
    this.callExternalFunctionWithParams(rowData.pid, rowData.pstatus);
  });
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
        // Update form fields with API data
        this.selectedPractice = data.practice;
        this.selectedPatients = data.patient;
        this.selectedOption = data.timeoption;
        this.timeValue = data.time;
        this.activedeactivestatus = data.patientstatus;
      } catch (error) {
        console.error('Error fetching user filters:', error);
      }
    },
    async savefilters() {
      try {

        const practices = this.selectedPractice;
        const patient = this.selectedPatients;
        const modules = this.patientsmodules;
        const timeoption = this.selectedOption;
        const time = this.timeValue || null;
        const activedeactivestatus = this.activedeactivestatus || null;
          // Retrieve the CSRF token from your application's state or meta tags
          const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

          // Send data to the server using fetch with the POST method
          const response = await fetch(`/patients/worklist/saveuser-filters/${practices}/${patient}/${modules}/${timeoption}/${time}/${activedeactivestatus}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json', // Specify the content type
              'X-CSRF-TOKEN': csrfToken, // Include the CSRF token in the headers
            },
           
          });  
    
                    // Handle the response as needed
          if (response.ok) {
            //const data = await response.json(); // If the server returns JSON data
            // Handle the response data here
          } else {
            console.error('Failed to save user filters:', response.statusText);
          }

      } catch (error) {
        console.error('Error saving user filters:', error);
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
          //console.error('Practice ID is empty or invalid.');
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
        await this.savefilters();

      } catch (error) {
        console.error('Error fetching data:', error);
      }
    },
    handleReset() {
      // Reset form fields and table data
      this.selectedPractice = null;
      this.selectedPatients = [];
      this.dataTable = [];
    },
    async getPatientList() {
      try {
        const practice_id = this.selectedPractice;
        const patient_id = this.selectedPatient;
        const module_id = this.patientsmodules;
        const timeoption = this.selectedOption;
        
        if(this.timeValue == ''){
          var time = null; 
        }else{
          var time = this.timeValue;
        }

        if(this.activedeactivestatus == ''){
          var activedeactivestatus = null; 
        }else{
          var activedeactivestatus = this.activedeactivestatus;
        }

        const response = await fetch(`/patients/worklist/${practice_id}/${patient_id}/${module_id}/${timeoption}/${time}/${activedeactivestatus}`);
        if (!response.ok) {
          throw new Error('Failed to fetch patient list');
        }

        const data = await response.json();
        this.dataTable = data.data; // Replace data with the actual fetched data
        this.initDataTable();
      } catch (error) {
        console.error('Error fetching patient list:', error);
      }
    },
    initDataTable() {
      if ($.fn.DataTable.isDataTable('#patient-list')) {
    //$('#patient-list').DataTable().destroy();
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
  { title: 'Patient Status', data: 'activedeactive',
  render: function (data, type, row) {
    // Assuming 'activedeactive' contains a button to trigger the function
    // Dynamically generate the button with the function call and parameters
    if(row.pstatus == 1 && row.pstatus!=0 && row.pstatus!=2 && row.pstatus!=3){
      return `<a href="javascript:void(0)" class="ActiveDeactiveClass" data-toggle="modal" data-target="#active-deactive"  id="active_deactive" @click="callExternalFunctionWithParams('${row.pid}','${row.pstatus}')"><i class="i-Yess i-Yes"  title="Patient Status"></i></a>`;
    }else{
      return `<a href="javascript:void(0)" class="ActiveDeactiveClass" data-toggle="modal" data-target="#active-deactive"  id="active_deactive" @click="callExternalFunctionWithParams('${row.pid}','${row.pstatus}')"><i class="text-20 i-Stopwatch" style="color: #2cb8ea;"></a>`;
    }

  }
 },
  { title: 'Call Score', data: 'pssscore' },
];
        // Initialize DataTable with your options
        var tableloading =   $('#patient-list').DataTable({
        columns: columns,
        data: this.dataTable,
        destroy: true,
        paging: true,
        searching: true,
        processing: true,
        dom: 'Bfrtip',
        buttons: [
    {
      extend: 'copyHtml5',
      text: '<img src="/assets/images/copy_icon.png" width="20" alt="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Copy">',
      titleAttr: 'Copy',
    },
    {
      extend: 'excelHtml5',
      text: '<img src="/assets/images/excel_icon.png" width="20" alt="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Excel">',
      titleAttr: 'Excel',
      customize: function (xlsx) {
        var sheet = xlsx.xl.worksheets['sheet1.xml'];
      },
    },
    {
      extend: 'csvHtml5',
      text: '<img src="/assets/images/csv_icon.png" width="20" alt="" data-toggle="tooltip" data-placement="top" title="" data-original-title="CSV">',
      titleAttr: 'CSV',
    },
    {
      extend: 'pdfHtml5',
      text: '<img src="/assets/images/pdf_icon.png" width="20" alt="" data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF">',
      titleAttr: 'PDF',
    },
  ],
        });
        tableloading.processing( true );
        setTimeout( function () {
          tableloading.processing( false );
  }, 5000 );
  this.dataTableLoaded = true; 
        $('#patient-list').DataTable().clear().rows.add(this.dataTable).draw();
    },
    callExternalFunctionWithParams() {
      console.log("button clicked ");
      // Call the imported function with the dynamic parameters
      this.showModal = true;
      //onActiveDeactiveClick(param1, param2);
    },
  },
  created() {
    // Fetch user filters when the component is created
    this.fetchUserFilters();
    // Initialize DataTable on component creation
    this.initDataTable();
  },
  // Ensure to call initDataTable() when the component is updated or mounted
  updated() {
    this.initDataTable();
  },
};


</script>

<style>

</style>
