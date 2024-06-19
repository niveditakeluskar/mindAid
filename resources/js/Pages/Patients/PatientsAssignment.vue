<template>
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Assigned Patients</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<loading-spinner :isLoading="isLoading"></loading-spinner>

<div  class="row">
   
   <div  style="width: 22%;margin-left: 1%; margin-right: 2%;" >
   <a href="/task-management/getpatients-assignment/totalpatients" target="blank">
      <div  class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div  class="card-body text-center">
            <i  class="i-Myspace"></i>
            <div  class="content">
               <!-- <p  class="text-muted" data-toggle="modal"  data-target="#mypatientModal" target="allpatient" style="width: 86px;height: 30px;">Patients</p> -->
               <p  class="text-muted" style="width: 86px;height: 30px;">Patients</p>
               <p class="lead text-primary text-24 mb-2" id="totalpatient">{{ totalPatient }}</p>
            </div>
         </div>
      </div>
      </a>
   </div>

   <div   style="width: 22%;margin-right: 2%;" >
   <a href="/task-management/getpatients-assignment/totalcaremanager" target="blank">
      <div  class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div  class="card-body text-center">
            <i  class="i-Add-UserStar"></i>
            <div  class="content">
               <p  class="text-muted"  style="width: 55px;height: 30px;">Care Manager</p> 
               <p  class="lead text-primary text-24 mb-2" id="totalcaremaneger">{{ totalcaremanager }}</p>
            </div>
         </div>
      </div>
      </a>
   </div>
   <div  style="width: 22%;margin-right: 2%;">
   <a href="/task-management/getpatients-assignment/totalassignedpatient" target="blank">
      <div  class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div  class="card-body text-center">
            <i  class="i-Checked-User"></i>
            <div  class="content">
               <p  class="text-muted"  style="width: 99px;height: 30px;">Assigned Patient</p>
               <p  class="lead text-primary text-24 mb-2" id="totalassignedpatient">{{ totalassignedpatient }}</p>
            </div>
         </div>
      </div>
      </a>
   </div> 
   <div  style="width: 22%;margin-right: 2%;">
   <a href="/task-management/getpatients-assignment/totalnonassignedpatient" target="blank">
      <div  class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div  class="card-body text-center">
            <i  class="i-Remove-User"></i>
            <div  class="content">
               <p  class="text-muted" style="width: 99px;height: 30px;">Non Assigned Patient</p>
               <p  class="lead text-primary text-24 mb-2"  id="totalnonassignedpatient">{{ totalnonassignedpatient }}</p>
            </div>
         </div>
      </div>
      </a>
   </div>
  </div>
  

  <div class="row">
        <div class="col-md-12 mb-4">
          <div class="card text-left">
            <div class="card-body">
              <form @submit.prevent="handleSubmit">
                <div class="form-row">

                  <div class="col-md-4 form-group mb-6">
                    <label for="practicename"> Care Manager </label>
                    <v-select
                    v-model="selectedCareManager"
                    :options="careManagers"
                    :getOptionLabel="option => `${option.f_name} ${option.l_name}`"
                    value="id"
                    placeholder="Select Report To"
                    @input="handleCareManagerChange"
                    />
                  </div>

                  <div class="col-md-4 form-group mb-6">
                    <label for="patientsname">Practice</label>
                    <v-select
                    v-model="selectedPractice"
                    :options="practices"
                    :getOptionLabel="option => `${option.name}`"
                    value="id"
                    placeholder="Select Practice"
                    />
                  </div>

                  <div class="col-md-4 form-group mb-6">
                    <label for="patientsname">Provider</label>
                    <v-select
                    v-model="selectedProvider"
                    :options="providers"
                    :getOptionLabel="option => `${option.name}`"
                    value="id"
                    placeholder="Select Provider"
                    />
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
                    <label for="activedeactivestatus">Status</label>
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
          <div class="position-relative">
            <div class="row" v-if="careManagersList">
              <!-- Dropdown box column -->
              <div class="col-md-3 mb-3">
                <v-select
                  v-model="selectedOptionManager"
                  :options="careManagersList"
                  :getOptionLabel="option => `${option.f_name} ${option.l_name}`"
                  value="id"
                  placeholder="Select Care Manager"
                />
              </div>

              <!-- Button column -->
              <div class="col-md-6">
                <button class="btn btn-primary" @click="submit">Assign</button>
              </div>
            </div>

            <!-- AgGrid table -->
            <div class="mt-1"> 
              <span id="assisgnedMessage"></span>
              <AgGridTable :rowData="passRowData" :columnDefs="columnDefs" :onGridReady="onGridReadyWithLoading"/>
            </div>
          </div>
        </div>
      </div> <!--End of card-->
    </div>
  </div>
      
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
    Head
  } from './../commonImports';
  import LayoutComponent from './../LayoutComponent.vue'; 
  import axios from 'axios';
  import vSelect from 'vue-select';
  import 'vue-select/dist/vue-select.css';

export default {
  props: {
    active_pracs: Array,
    inactive_pracs: Array,
    },
  components: {
    LayoutComponent,
    AgGridTable,
    Head,
    vSelect
},
  setup(props) {
    const title = 'Assigned Patients';
    const layoutComponentRef = ref(null);
    const passRowData = ref([]);
    const isLoading = ref(false);
    const tableInstance = ref(null); 
    const showModal = ref(false);

    const selectedPractice = ref('');
    const selectedCareManager = ref('');
    const selectedOption = ref(4);
    const selectedOptionManager = ref('');
    const selectedProvider = ref('');

    const patients = ref([]);
    const providers = ref([]);

    const timeValue = ref('00:00:00');
    const activedeactivestatus = ref('');
    const patientsmodules = ref('3');
    const careManagers = ref([]);
    const careManagersList = ref('');
    const totalPatient = ref(0);
    const totalcaremanager = ref(0);
    const totalassignedpatient = ref(0);
    const totalnonassignedpatient = ref(0);
    const onGridReadyWithLoading = ref(1);

    const practices = ref([]);

    const selectedRows = ref([]);

    const mergePractices = () => {
      const mergedPractices = [...props.active_pracs, ...props.inactive_pracs];
      practices.value = mergedPractices;
    };


    onBeforeMount(() => {
      document.title = 'Assigned Patients | Renova Healthcare';
    });

    onMounted(async () => {
      fetchUsersCount();
      fetchCareManager();
      mergePractices();
      fetchActiveProviders();
    });


    const columnDefs = ref([
      {
        headerName: 'Sr. No.',
        valueGetter: 'node.rowIndex + 1',     flex: 1
      },
   
      { headerName: 'Practive EMR No.', field: 'pracpracticeemr'},
      {
    headerName: 'Patient Name',
    field: 'full_name',
    cellRenderer: function (params) {
        const row = params.data;
        const camelCaseFullName = row.full_name
            .split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
            .join(' ');
            const div = document.createElement('div');
          const input = document.createElement('input');
          const span = document.createElement('span');

          input.setAttribute('type', 'checkbox');
          input.setAttribute('style', 'margin-right: 5px;');
          input.addEventListener('click', (event) => onCheckboxClick(event, row.pid));

          span.setAttribute('style', 'margin-left: 4px;');
          span.textContent = camelCaseFullName;

          div.setAttribute('style', 'display: flex; align-items: center;');
          div.appendChild(input);
          div.appendChild(span);

          return div;
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
      { headerName: 'Practice', field: 'cmname', flex: 2 },
      {
  headerName: 'CM',
  cellRenderer: function (params) {
    const row = params.data;
    const firstName = row.userfname || ''; 
    const lastName = row.userlname || ''; 

    const full_name_comb = firstName + " " +lastName;
    const camelCaseCMName = full_name_comb
      .split(' ')
      .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
      .join(' ');
    if(firstName || lastName){
      return camelCaseCMName + "(" +row.patient_count + ")";
    }
  },
  flex: 2
}
,

      { headerName: 'Provider', field: 'prname', flex: 2 },

      {
    headerName: 'Last Contact Date',
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
      { headerName: 'Enrolled Modules', field: 'module' }
    ]);


   const onCheckboxClick = (event, name) => {
      if (event.target.checked) {
        selectedRows.value.push(name);
      } else {
        const index = selectedRows.value.indexOf(name);
        if (index > -1) {
          selectedRows.value.splice(index, 1);
        }
      }
    };

    const submit = async () => {
      const apiUrl = '../patients/task-management-user-form';
      const token = document.head.querySelector('meta[name="csrf-token"]').content;


      if (!selectedOptionManager) {
        alert('Please select an option manager.');
        return;
      }

      if (selectedRows.value.length === 0) {
        alert('No rows selected');
        return;
      }

      const postData = {
        selectedOptionManager: selectedOptionManager.value.id,
        selectedRows: selectedRows.value
      };

      axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
      try {
        isLoading.value = true;
         const response = await axios.post('../patients/task-management-user-form', postData);
   

         if (response && response.status == 200) {
        $('#assisgnedMessage').html('<div class="alert alert-success"><strong>Patient assigned successfully!</strong></div>');
             selectedOptionManager.value = "";
                         selectedRows.value = [];
               getPatientList(
          selectedPractice.value === '' ? null : selectedPractice.value,
          selectedProvider.value === '' ? null : selectedProvider.value,
          timeValue.value === '' ? null : timeValue.value,
          selectedCareManager.value === '' ? null : selectedCareManager.value,
          selectedOption.value === '' ? null : selectedOption.value,
          activedeactivestatus.value === '' ? null : activedeactivestatus.value
        );
                isLoading.value = false;

        setTimeout(function () {
         $('#assisgnedMessage').html('');
                        }, 3000);
      }

    } catch (error) {
                 isLoading.value = false;
        $('#assisgnedMessage').html('<div class="alert alert-danger"><strong>Failed to assign Patient</strong></div>');
setTimeout(function () {
         $('#assisgnedMessage').html('');
                        }, 3000);
      console.error('Error assisgning Patients:', error);

    }

    };

   

    watch(selectedCareManager, (newcareManagerId) => {
      fetchPractices(newcareManagerId);
    });

    watch(selectedPractice,(newPracticeId) => {
      fetchProviders(newPracticeId);
    }
  ); 

    const fetchCareManager = async () => {
      try {
        const response = await fetch('../patients/activeusers');
        if (!response.ok) {
          throw new Error('Failed to fetch CareManager');
        }
        const data = await response.json();
        careManagers.value = data; 
      } catch (error) {
        console.error('Error fetching CareManager:', error);
      }
    };

    const fetchActiveProviders = async () => {
      try {
        const response = await fetch('../patients/activeproviders');
        if (!response.ok) {
          throw new Error('Failed to fetch CareManager');
        }
        const data = await response.json();
        providers.value = data; 
      } catch (error) {
        console.error('Error fetching CareManager:', error);
      }
    };
    
    const fetchUsersCount = async () => {
      try {
        const token = document.head.querySelector('meta[name="csrf-token"]').content;
        //const tokenResponse = await getCSRFToken();
        //const tokenz = tokenResponse ? tokenResponse.csrf_token : null;
        //console.log(token,"--",tokenResponse,tokenz);
        //const token = tokenResponse.csrf_token;
        const response = await fetch('../reports/patient-summary', {
          method: 'POST', 
          headers: {
            'Content-Type': 'application/json', 
            'X-CSRF-TOKEN': token 
          },
          body: JSON.stringify({})
        });
        if (!response.ok) {
          throw new Error('Failed to fetch CareManager');
        }
        const data = await response.json();
         totalPatient.value  = data.Totalpatient[0].count;
     totalcaremanager.value  = data.TotalCareManeger[0].count;
     totalassignedpatient.value  = data.ToltalAssignedPatient[0].count;
     const totalPatientActive = data.totalPatientActive[0].count;
     totalnonassignedpatient.value = totalPatientActive - totalassignedpatient.value;
      } catch (error) {
        console.error('Error fetching CareManager:', error);
      }
    };

    const getCSRFToken = async () => {
      try {
        const response = await fetch('/csrf-token', {
          credentials: 'same-origin'
        });

        if (!response.ok) {
          throw new Error('Failed to fetch CSRF token');
        }
        const data = await response.text();
        return data;
      } catch (error) {
        console.error('Error fetching CSRF token:', error);
      }
    };

    const fetchPractices = async (careManagerId) => {
      try {
          if(!careManagerId){
            return;
          }
          
          let careManagerIdParam = careManagerId.id
     
        if (careManagerIdParam === undefined || !careManagerIdParam) {
          careManagerIdParam = 0
        }
        
        selectedPractice.value = "";
        selectedProvider.value = "";
        practices.value = []; 
        const response = await fetch('/org/ajax/caremanager/' + careManagerIdParam + '/practice');
       if (!response.ok) {
          throw new Error('Failed to fetch practices');
        }
        const data = await response.json();
        practices.value = data; 
      } catch (error) {
        console.error('Error fetching practices:', error);
      }
    };

   const fetchProviders  = async (practiceId) => {
      try {
        if(!practiceId){
            return;
          }
          
        let practiceIdParam = practiceId.id
          
        if (practiceIdParam === undefined || !practiceIdParam) {
          practiceIdParam = 0
        }

        selectedProvider.value = "";
        providers.value = []; 
      const response = await fetch('/org/ajax/provider/list/' + practiceIdParam + '/Pcpphysicians');
       if (!response.ok) {
          throw new Error('Failed to fetch Providers');
        }
        const data = await response.json();
        providers.value = data; 
      } catch (error) {
        console.error('Error fetching Providers:', error);
      }
    };
    
    const fetchCareManagerList = async () => {
      try {
        const response = await fetch('../patients/activeusers');
       if (!response.ok) {
          throw new Error('Failed to fetch activeusers');
        }
        const data = await response.json();
        careManagersList.value = data; 
      } catch (error) {
        console.error('Error fetching activeusers:', error);
      }
    };

    const handleChange = async () => {
      if (selectedOption.value === '4') {
        timeValue.value = '00:00:00';
      } else {
        timeValue.value = '00:20:00'; 
      }
    };

    const handleSubmit = async () => {
      try {
        isLoading.value = true;
        
         getPatientList(
          selectedPractice.value === '' ? null : selectedPractice.value,
          selectedProvider.value === '' ? null : selectedProvider.value,
          timeValue.value === '' ? null : timeValue.value,
          selectedCareManager.value === '' ? null : selectedCareManager.value,
          selectedOption.value === '' ? null : selectedOption.value,
          activedeactivestatus.value === '' ? null : activedeactivestatus.value
        );
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    };

    const handleReset = async () => {
      selectedPractice.value = "";
      selectedProvider.value = "";
      selectedCareManager.value = "";
      selectedOption.value = 4;
      timeValue.value = '00:00:00';
      activedeactivestatus.value = "";
    }

    const getPatientList = async (practice_id, provider_id,time,manager_Id ,timeoption, activedeactivestatus) => {
  try {

        let practice_idParam= "";

    if (practice_id === undefined || !practice_id) {
          practice_idParam = null;
        }else{
          practice_idParam = practice_id.id;
        }

        let provider_idParam= "";

        if (provider_id === undefined || !provider_id) {
          provider_idParam = null;
      }else{
        provider_idParam = provider_id.id;
      }

      let manager_IdParam= "";

      if (manager_Id === undefined || !manager_Id) {
        manager_IdParam = null;
      }else{
        manager_IdParam = manager_Id.id;
      }

      if (time === undefined || !time) {
        time = "00:00:00";
        }

      if (timeoption === undefined || !manager_Id) {
        timeoption = null;
      }

      if (activedeactivestatus === undefined || !activedeactivestatus) {
        activedeactivestatus = null;
      }

      const response = await fetch(`/patients/patients-assignment/search/${practice_idParam}/${provider_idParam}/${time}/${manager_IdParam}/${timeoption}/${activedeactivestatus}`);
      if (!response.ok) {
        throw new Error('Failed to fetch patient list');
      }
      const data = await response.json();
      const processedData = Array.isArray(data.data) ? data.data.map(row => ({
    ...row,
        full_name: [row.pfname, row.pmname, row.plname].filter(Boolean).join(' ')
    })) : [];

      passRowData.value = processedData || [];
      fetchCareManagerList();

      isLoading.value = false;
  } catch (error) {
    console.error('Error fetching patient list:', error);
    isLoading.value = false;
  }
    };



    return {
      onGridReadyWithLoading,
      submit,
      onCheckboxClick,
      selectedRows,
      selectedProvider,
       totalPatient,
     totalcaremanager,
     totalassignedpatient,
     totalnonassignedpatient,
      fetchUsersCount,
      careManagers,
      columnDefs,
      isLoading,
      tableInstance,
      showModal,
      selectedPractice,
      selectedCareManager,
      passRowData,
      practices,
      patients,
      providers,
      selectedOption,
      selectedOptionManager,
      timeValue,
      activedeactivestatus,
      patientsmodules,
      layoutComponentRef,
      handleSubmit,
      handleChange,
      handleReset,
      title,
      totalPatient,
      careManagersList
    };
  },
};
</script>
