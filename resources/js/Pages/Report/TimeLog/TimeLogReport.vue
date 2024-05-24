<template>
  <LayoutComponent>
      <loading-spinner :isLoading="isLoading"></loading-spinner>
        <h4 class="card-title">Time Log Report</h4>  
        <div class="separator-breadcrumb border-top"></div>
      <div class="card-body">
        <form name ="report_form">
        <div class="form-row">
            <div class="col-md-6 form-group mb-6">
            <label for="practicename">Practice Name</label>
            <select id="practices" class="custom-select show-tick select2" data-live-search="true" v-model="selectedPractice" @change="updatedfilterechanges">
                <option value="">All Practices</option>
                <option v-for="practice in practices" :key="practice.id" :value="practice.id">
                {{ practice.name }}
                </option>
            </select>
            </div>
            <div class="col-md-6 form-group mb-6">
            <label for="patientsname">Patient Name</label>
            <select id="patients" class="custom-select show-tick select2" data-live-search="true" v-model="selectedPatient" @change="updatedfilterechanges">
                <option value="">All Patients</option>
                <option v-for="patient in patients" :key="patient.id" :value="patient.id">
                {{ patient.fname }} {{ patient.mname }} {{ patient.lname }}
                </option>
            </select>  
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-2 form-group mb-3">   
            <label for="module">Caremanager</label>
            <select name="caremanager" id="caremanager" class="custom-select show-tick" v-model="selectedCaremanager" @change="updatedfilterechanges">
              <option value="">All Caremanagers</option>
                <option v-for="caremanager in caremanagers" :key="caremanager.id" :value="caremanager.id" @change="updatedfilterechanges">
                {{ caremanager.f_name }} {{ caremanager.l_name }}
                </option>
            </select>
            </div>
            <!-- <div class="col-md-2 form-group mb-3">
            <label for="module">Module</label>
            <select name="modules" id="modules" class="custom-select show-tick" v-model="selectedmodules" @change="updatedfilterechanges">
                <option value="3">CCM</option>
                <option value="2">RPM</option>
                <option value="8">Patient</option>
            </select>
            </div> -->
            <div class="col-md-2 form-group mb-3">
                <label for ="from_date">From Date </label>
                    <input name="from_date" id="from_date" type="date" class="form-control" placeholder="" v-model="selectedFromdate" @change="updatedfilterechanges">

            </div>
            <div class="col-md-2 form-group mb-3">
                <label for ="to_date">To Date </label>
                    <input name="to_date" id="to_date" type="date" class="form-control"  placeholder="" v-model='selectedTodate'@change="updatedfilterechanges">
            </div>
            <div class="col-md-2 form-group mb-3">
            <label for="activedeactivestatus">Patient Status</label>
            <select id="activedeactivestatus" name="activedeactivestatus"
                class="custom-select show-tick" v-model="selectedActiveDeactivestatus">
                <option value="">All (Active,Suspended,Deactivated,Deceased)</option>
                <option value="1">Active</option>
                <option value="0">Suspended</option>  
                <option value="2">Deactivated</option>
                <option value="3">Deceased</option>
            </select>
            </div>
            <div class="col-md-2 form-group mb-3">
              <button type="button" class="btn btn-primary mt-4 mr-2" @click="fetchTimeLog">Search</button>
              <button type="button" class="btn btn-primary mt-4" @click="handleReset">Reset</button>
            </div>
        </div>
        </form>
      </div>
      <div class="separator-breadcrumb border-top"></div>
          <div class="row">
              <div class="col-12">
                  <div class="table-responsive">
                      <AgGridTable :rowData="drugAllergiesRowData" :columnDefs="drugAllergiescolumnDefs"/>
                  </div>
              </div>
          </div>
          <PatientTimeLogReport ref="PatientTimeLogReportRef" :componentConstProps="componentConstProps"/>
          <!-- v-bind="componentConstProps" -->
  </LayoutComponent>
</template>

<script>
import {
  reactive,
  ref,
  onMounted,
  computed,
  watch, 
  AgGridTable,
  onBeforeMount,
} from '../../commonImports';
import PatientTimeLogReport from '../../Report/TimeLog/PatientTimeLogReport.vue';
import LayoutComponent from '../../LayoutComponent.vue';

export default {
  setup(props) {
      const loading = ref(false);
      let isLoading = ref(false);
      const PatientTimeLogReportRef = ref();
      const practices = ref([]);
      const selectedPractice = ref(''); 
      const selectedPatient = ref('');
      const selectedmodules = ref(''); //ref(3);
      const selectedCaremanager = ref('');
      const patients = ref([]);
      const caremanagers = ref([]);
      const selectedFromdate = ref(new Date().toISOString().substr(0, 10));
      const selectedTodate = ref(new Date().toISOString().substr(0, 10));
      const selectedActiveDeactivestatus = ref('');
      const drugAllergiesRowData = ref([]);
      const drugAllergiescolumnDefs = ref([
          {
              headerName: 'Sr. No.',
              valueGetter: 'node.rowIndex + 1',
              initialWidth: 20,
          },
          { headerName: 'Practice', field: 'pracname', filter: true },
          { headerName: 'EMR', field: 'pppracticeemr' },
          {
              headerName: 'Patient Name',
              field: 'pfname',
              cellRenderer: function (params) {
                  const row = params.data;
                  if (row && row.pfname) {
                      return row.pfname + ' '+ (row.pmame || '')+ ' ' + (row.plname || ''); // Added a check for l_name as well
                  } else {
                      return 'N/A';
                  } 
              },
          }, 
          { headerName: 'DOB', field: 'pdob',
            cellRenderer: function (params) {
              const date = params.value; 
              if (!date) return null;
              const formattedDate = new Date(date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                }).replace(/\//g, '-'); 

              return formattedDate; 
            },
          },
          
          // { headerName: 'Module', field: 'module_id' },
          
          { headerName: 'Net Time', field: 'net_time' },

          { headerName: 'Record Date', field: 'record_date',
            cellRenderer: function (params) {
              const date = params.value; 
              if (!date) return null;
              const formattedDate = new Date(date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                }).replace(/\//g, '-'); 

              return formattedDate; 
            },
          }, 
     
          {headerName: 'CM', field: 'created_by_user'},
        
          { 
              headerName: 'Action',  
              field: 'action',
              cellRenderer: function (params) {
                const { data } = params;
                console.log(data,'params');
                const link = document.createElement('a');
                const icon = document.createElement('i');
                icon.classList.add('text-20', 'i-Eye');
                icon.style.color = 'green';
                link.appendChild(icon);
                link.classList.add('ActiveDeactiveClass');
                link.href = 'javascript:void(0)';
                link.addEventListener('click', () => {
                    callExternalFunctionClick(data.pid); 
                });
                return link;
              },
          },
      ]); 
      const callExternalFunctionClick = (id) => {
        PatientTimeLogReportRef.value.openModal(id);
        // alert(id);
      };
      
      watch(selectedPractice, (newPracticeId) => {
        fetchPatients(newPracticeId);
      });


      watch(selectedPatient, (newPatientId) => {
        fetchCaremanager(newPatientId);
      });

    const fetchPractices = async () => {
      try {
        const response = await fetch('../org/practiceslist');
        if (!response.ok) {
          throw new Error('Failed to fetch practices');
        }
        const data = await response.json();
        practices.value = data; 
        // console.log('practo',data);

      } catch (error) {
        console.error('Error fetching practices:', error);
      }
    };
    
   
    const fetchPatients = async (practiceId) => {
      try {
       

        if (practiceId === undefined) {
          practiceId = null;
        }

        if(!practiceId){
          practiceId = null;
        }

        const response = await fetch('../patients/ajax/patientlist/' + practiceId + '/patientlist');
       if (!response.ok) {
          throw new Error('Failed to fetch patients');
        }
        const data = await response.json();
        // console.log(practiceId,'practiceId',data);
        patients.value = data; 
      } catch (error) {
        console.error('Error fetching patients:', error);
      }
    };

    const fetchCaremanager = async(patientId) => {
      try {
        if (patientId === undefined) {
          patientId = null;
        }

        if(!patientId){
          patientId = null;
        }
        
      const response = await fetch('../patients/ajax/caremanagerlist/' + patientId + '/caremanagerlist');
        if (!response.ok) {
          throw new Error('Failed to fetch patients');
        }
        const data = await response.json();
        caremanagers.value = data; 
      } catch (error) { 
        console.error('Error fetching caremanagers:', error);
      }
    };
    
   
      const fetchTimeLog = async() => {
          try { //debugger;
              loading.value = true;
              let patient = selectedPatient.value;
              let practiceId = selectedPractice.value;
              let caremanager =selectedCaremanager.value;
              let module = null;//selectedmodules.value;
              let emr =null;
              let sub_module =null; 
              let activedeactivestatus = selectedActiveDeactivestatus.value;
              let fromdate = selectedFromdate.value;
              let todate = selectedTodate.value;
              
              if (selectedPatient.value == '') {
                patient = 'null';
              }
              if (selectedPractice.value == '') {
                practiceId = 'null';
              } 
              if (selectedCaremanager.value == '') {
                caremanager = 'null';
              }
              if (selectedmodules.value == '') {
                module = 'null';
              }
              if(emr == '' || emr == undefined ) {
                emr = 'null';
              } 
              
              if (sub_module == '' || sub_module == undefined ) {
                sub_module = 'null';
              }
              if (selectedActiveDeactivestatus.value == '') {
                activedeactivestatus = 'null';
              }
              
              if (selectedFromdate.value == '') {
                fromdate = 'null';
              }
              if (selectedTodate.value == '') {
                todate = 'null';
              }
              
              //await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
              // const response = await fetch(`/reports/patient-time-logs-report/${patient}/${practiceId}/${caremanager}/${module}/${fromdate.value}/${todate.value}/${activedeactivestatus}`);
              const response = await fetch('/reports/total-patient-time-Log/' +patient + '/' + practiceId + '/' + emr + '/' +caremanager+'/'+module+'/'+sub_module+'/'+fromdate+'/'+todate+'/'+activedeactivestatus);
              if (!response.ok) {
                  throw new Error('Failed to fetch followup task list');
              }
              loading.value = false; 
              const data = await response.json();
              drugAllergiesRowData.value = data.data;
          } catch (error) {
              console.error('Error fetching followup task list:', error);
              loading.value = false;
          }
      };


        let componentConstProps = ref({
          patient :  selectedPatient.value,
          practiceId :  selectedPractice.value,
          caremanager : selectedCaremanager.value,
          module : selectedmodules.value,
          activedeactivestatus : selectedActiveDeactivestatus.value,
          fromdate : selectedFromdate.value,
          todate: selectedTodate.value,
        });

        const updatedfilterechanges = () => {
          // Update the componentConstProps object with new date values
          // patient :  selectedPatient.value,
          componentConstProps.value.practiceId =  selectedPractice.value,
          componentConstProps.value.caremanager = selectedCaremanager.value,
          componentConstProps.value.module = selectedmodules.value,
          componentConstProps.value.activedeactivestatus = selectedActiveDeactivestatus.value,
          componentConstProps.value.fromdate = selectedFromdate.value;
          componentConstProps.value.todate = selectedTodate.value;
        };

        
    const handleReset = async () => {
      selectedPractice.value = "";
      selectedPatient.value = "";
      selectedCaremanager.value = "";
      selectedmodules.value = "";//3;
      selectedActiveDeactivestatus.value = "";
      selectedFromdate.value = new Date().toISOString().substr(0, 10);
      selectedTodate.value = new Date().toISOString().substr(0, 10);
    }

      onBeforeMount(() => {
        // fetchPractices();
        // fetchPatients();
        // fetchCaremanager();
      });

      onMounted(()=>{
        fetchPractices();
        fetchPatients();
        fetchCaremanager();
        fetchTimeLog();
      });
      return {
          loading,
          drugAllergiescolumnDefs,
          drugAllergiesRowData,
          fetchTimeLog,
          PatientTimeLogReportRef,
          componentConstProps,
          isLoading,
          practices,
          selectedPractice,
          patients,
          caremanagers,
          selectedFromdate,
          selectedTodate,
          selectedCaremanager,
          selectedPatient,
          selectedmodules,
          selectedActiveDeactivestatus,
          updatedfilterechanges,
          handleReset,
          
      };
     
  },
  components: {
    AgGridTable,
    LayoutComponent,
    PatientTimeLogReport,
  },
};
</script>

<style>
.fade {
    transition: opacity .15s linear!important;
}
.modal {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background-color: rgba(0, 0, 0, 0.5); /* Adjust the last value (0.5) for the desired opacity */
    z-index: 1040; /* Adjust the z-index to be above the modal */
    display: none;
  }

.modal.open, .modal.show{
	display: block;
    overflow-x: hidden;
    overflow-y: auto;
}
.modal.open .modal-dialog,  .modal.show .modal-dialog{
    -webkit-transform: translate(0);
    transform: translate(0);
}
.modal-md{
	width:60%;
}
.modal-lg{
	width:80%;
}
.modal-xl{
	width:95%;
	max-width: 95%; 
}
.goal-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 5px;
}
.model-open{
  padding-right: 17px;
  overflow: hidden;
}
</style>
