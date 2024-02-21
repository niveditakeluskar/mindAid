<template>
  <LayoutComponent ref="layoutComponentRef" >
    <div>
      <loading-spinner :isLoading="isLoading"></loading-spinner>
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
                    <select id="practices" class="custom-select show-tick select2" data-live-search="true" v-model="selectedPractice"
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
                    <button type="submit" class="btn btn-primary mt-4 mr-2" @click="handleSearch">Search</button>
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
            <PatientStatus ref="PatientStatusRef" :moduleId="moduleId" :componentId="componentId" :PatientWorkList="patientReloadFn"/>
            </div>
          </div>
        </div>
      </div>
      <div class="row mb-4">
        <div class="col-md-12 mb-4">
          <div class="card text-left">
            <div class="card-body">
                <AgGridTable :rowData="passRowData" :columnDefs="columnDefs"/>
            </div>
          </div> <!--End of card-->
        </div>
      </div>

    </div>

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
  onBeforeMount
} from './commonImports';
import LayoutComponent from './LayoutComponent.vue'; 
import PatientStatus from './Modals/PatientStatus.vue';

export default {
  props: {
      moduleId: Number,
      componentId: Number,
    },
  components: {
    LayoutComponent,
    PatientStatus,
    AgGridTable
},
  setup(props) {
    const { callExternalFunctionWithParams } = PatientStatus.setup();
    const layoutComponentRef = ref(null);
    const passRowData = ref([]);
    const isLoading = ref(false);
    const tableInstance = ref(null); 
    const showModal = ref(false);
    const selectedPractice = ref(null);
    const selectedPatients = ref(null);
    const practices = ref([]);
    const patients = ref([]);
    const selectedOption = ref(null);
    const timeValue = ref('00:20:00');
    const activedeactivestatus = ref(null);
    const patientsmodules = ref('3');
  

    const practice_id = computed(() => selectedPractice.value);
    const patient_id = computed(() => selectedPatients.value);
    const module_id = computed(() => patientsmodules.value);
    const timeoption = computed(() => selectedOption.value);
    const time = computed(() => (timeValue.value === '' ? null : timeValue.value));
    const activedeactivestatusComputed = computed(() =>
      activedeactivestatus.value === '' ? null : activedeactivestatus.value
    );

    const PatientStatusRef = ref();
    
    onBeforeMount(() => {
      document.title = 'Worklist | Renova Healthcare';
       fetchPractices();
        fetchPatients();
    });

    onMounted(async () => {
      fetchUserFilters();
    });


    const customCellRenderer = (params) => {
      const row = params.data;
      if (row && row.action) {
        return row.action; 
      } else {
        return ''; 
      }
    };


    const customCellRendererstatus = (params) => {
      const row = params.data;
      if (row && row.activedeactive) {
        return row.activedeactive; 
      } else {
        return ''; 
      }
    };

    const columnDefs = ref([
      {
        headerName: 'Sr. No.',
        valueGetter: 'node.rowIndex + 1',     flex: 1
      },
      { headerName: 'EMR No.', field: 'pppracticeemr'},
      {
    headerName: 'Patient Name',
    field: 'full_name',
    cellRenderer: function (params) {
        const row = params.data;
        const camelCaseFullName = row.full_name.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(' ');
        return `<div style="display: flex; align-items: center;">
                    <img src="https://mnt1.d-insights.global/assets/images/faces/avatar.png" width="50px" class="user-image">
                    <span style="margin-left: 4px;">${camelCaseFullName}</span>
                </div>`;
    },
    flex: 2
},
      {
        headerName: 'DOB',
        field: 'pdob',
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
      { headerName: 'Practice', field: 'pracpracticename', flex: 2 },
      {
    headerName: 'Last contact Date',
    field: 'csslastdate',
    cellRenderer: function (params) {
        const date = params.data.csslastdate;
        if (!date) return null;
          const formattedDate = new Date(date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
            }).replace(/\//g, '-'); 
          return formattedDate; 
    },
},
      { headerName: 'Total Time Spent', field: 'ptrtotaltime' },
      { headerName: 'Action', field: 'action', cellRenderer: customCellRenderer, },
      {
        headerName: 'Patient Status',
        field: 'activedeactive'
        , cellRenderer: (params) => {

          const link = document.createElement('a');
          const icon = document.createElement('i');
         
          const { data } = params;
          if (data.pstatus === 1) {
            icon.classList.add('text-20', 'i-Yes');
            icon.style.color = 'green';
            
          } else {
            icon.classList.add('text-20', 'i-Close');
            icon.style.color = 'red';
            
          }
          link.appendChild(icon);
          link.classList.add('ActiveDeactiveClass');
          link.href = 'javascript:void(0)';
          link.addEventListener('click', () => {
            callExternalFunctionClick(data.pid, data.pstatus); 
          });
          return link;
        },
      },
      { headerName: 'Call Score', field: 'pssscore' },
    ]);

    watch(selectedPractice, (newPracticeId) => {
      fetchPatients(newPracticeId);
    });

    const callExternalFunctionClick = (pid, pstatus) => {
      PatientStatusRef.value.openModal();
      callExternalFunctionWithParams(pid, pstatus);
    };

    const fetchPractices = async () => {
      try {
        const response = await fetch('../org/practiceslist');
        if (!response.ok) {
          throw new Error('Failed to fetch practices');
        }
        const data = await response.json();
        practices.value = data; 
      } catch (error) {
        console.error('Error fetching practices:', error);
      }
    };

    const fetchPatients = async (practiceId) => {
      try {
        if (practiceId === undefined) {
          return;
        }

        if(!practiceId){
          practiceId = null;
        }
        
        const response = await fetch('/patients/ajax/patientlist/' + practiceId + '/patientlist'); // Call the API endpoint
       if (!response.ok) {
          throw new Error('Failed to fetch patients');
        }
        const data = await response.json();
        patients.value = data; 
        return Promise.resolve(data);
      } catch (error) {
        console.error('Error fetching patients:', error);
        return Promise.reject(error);
      }
    };

    let pratices, patents, patentsmodules, tmeoption, tme, actvedeactivestatus;
    const fetchUserFilters = async () => {
      try {
        const response = await fetch('/patients/getuser-filters');
        const data = await response.json();  
         getPatientList(
          data.practice,
           data.patient,
           patientsmodules.value,
         data.timeoption,
          data.time,
           data.patientstatus
        );
        selectedPractice.value = data.practice;
        patientsmodules.value = patientsmodules.value;
          selectedOption.value = data.timeoption;
          timeValue.value = data.time;
          activedeactivestatus.value = data.patientstatus;
          selectedPatients.value = data.patient;
      } catch (error) {
        console.error('Error fetching user filters:', error);
      }
    };

    const handleChange = async () => {
      if (selectedOption.value === '4') {
        timeValue.value = '';
      } else {
        timeValue.value = '00:20:00'; 
      }
    };

    const handleSubmit = async () => {
      try {
        isLoading.value = true;
         getPatientList(
          selectedPractice.value === '' ? null : selectedPractice.value,
          selectedPatients.value === '' ? null : selectedPatients.value,
          patientsmodules.value === '' ? null : patientsmodules.value,
          selectedOption.value === '' ? null : selectedOption.value,
          timeValue.value === '' ? null : timeValue.value,
          activedeactivestatus.value === '' ? null : activedeactivestatus.value
        );
         saveFilters();

      } catch (error) {
        console.error('Error fetching data:', error);
      }
    };

    const patientReloadFn = async ()=> {
      getPatientList(
          selectedPractice.value,
          selectedPatients.value,
          patientsmodules.value,
          selectedOption.value,
          timeValue.value,
          activedeactivestatus.value
        );
    };

    const saveFilters = async () => {
      try {
        pratices = selectedPractice.value || null;
        patents = selectedPatients.value || null;
        patentsmodules = patientsmodules.value || null;
        tmeoption = selectedOption.value || null;
        tme = timeValue.value || null;
        actvedeactivestatus = activedeactivestatus.value || null;
        if (!patents) {
          
          patents = null;
        }
        if (!pratices) {
          pratices = null;
        }
        if(!actvedeactivestatus){
          actvedeactivestatus = null;
        }
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch(`/patients/worklist/saveuser-filters/${pratices}/${patents}/${patentsmodules}/${tmeoption}/${tme}/${actvedeactivestatus}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json', 
            'X-CSRF-TOKEN': csrfToken, 
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
      selectedPractice.value = "";
      selectedPatients.value = "";
    }

    const getPatientList = async (practice_id, patient_id, module_id, timeoption, time, activedeactivestatus) => {
  try {
 
        if (!patient_id) {
          patient_id = null;
        }
      const response = await fetch(`/patients/worklist/${practice_id}/${patient_id}/${module_id}/${timeoption}/${time}/${activedeactivestatus}`);
      if (!response.ok) {
        throw new Error('Failed to fetch patient list');
      }
      const data = await response.json();
      const processedData = data.data.map(row => ({
    ...row,
    full_name: [row.pfname, row.pmname, row.plname].filter(Boolean).join(' ')
}));

      passRowData.value = processedData || [];
      isLoading.value = false;
  } catch (error) {
    console.error('Error fetching patient list:', error);
    isLoading.value = false;
  }
};


    return {
      patientReloadFn,
      PatientStatusRef,
      columnDefs,
      isLoading,
      tableInstance,
      showModal,
      selectedPractice,
      selectedPatients,
      passRowData,
      practices,
      patients,
      selectedOption,
      timeValue,
      activedeactivestatus,
      patientsmodules,
      layoutComponentRef,
      handleSubmit,
      handleChange,
      handleReset,
    };
  },
};
</script>