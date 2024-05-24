<template>
  <div v-if="isOpen" class="modal fade show" >
      <loading-spinner :isLoading="isLoading"></loading-spinner>
<div class="modal-dialog modal-xl"> 
      <div class="modal-content"> 
          <div class="modal-header">
              <h4 class="modal-title">Patient Time Log Report</h4>  
              <button type="button" class="close" @click="closeModal">×</button>
          </div>
          <!-- {{  componentConstProps }} -->
          <div class="modal-body">
              <div class="row"> 
                  <div class="col-md-12">
                      <div class="form-group">
                          <AgGridTable :rowData="childRowData" :columnDefs="childcolumnDefs"/>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  </div>
</template>

<script>
import {
ref,
onMounted,
AgGridTable,
  // Add other common imports if needed
} from '../../commonImports';
import LayoutComponent from '../../LayoutComponent.vue';

export default {
  props: {
      componentConstProps: Object,
    //  patient :String,
    //  practiceId :String,
    //  caremanager :String,  
    //  module :String, 
    //  fromdate :Date,
    //  todate :Date,
    //  activedeactivestatus :String,
  },
setup(props) {
    const loading = ref(false);
    let isLoading = ref(false);
    const isOpen = ref(false); 
      const openModal = (id) => {
          isOpen.value = true;
          fetchPatientChildList(id);
      };
      
      let closeModal = () => {
          isOpen.value = false;
          document.body.classList.remove('modal-open');
      };
    const childRowData = ref([]);
    const childcolumnDefs = ref([
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
          
        { headerName: 'Module', field: 'module_id' },
        { headerName: 'Sub Module', field: 'component_id' },

        { headerName: 'Stage', field: 'stage_code' },
        { headerName: 'Step', field: 'stage_id' },
        { headerName: 'Form Name', field: 'form_name', minWidth: 400,
        maxWidth: 550,flex:2 },   
                  {
              headerName: 'Record Date',
              field: 'record_date',
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
          {
    headerName: 'Timer On',
    field: 'timer_on',
    cellRenderer: function (params) {
        const timerOn = params.value;
        const recordDate = params.data.record_date;
        if (!timerOn || !recordDate) return null;
        const [hours, minutes, seconds] = timerOn.split(':');
        const [datePart, timePart] = recordDate.split(' ');
        const [year, month, day] = datePart.split('-');
        let combinedDateTimeString = `${year}-${month}-${day}T${hours}:${minutes}:${seconds}Z`;
        const combinedDateTime = new Date(combinedDateTimeString);
        const userLocale = navigator.language || navigator.userLanguage;
        const formattedTime = combinedDateTime.toLocaleTimeString(userLocale, { hour12: false });
        return formattedTime;
    },
},
        {
    headerName: 'Timer Offf',
    field: 'timer_off',
    cellRenderer: function (params) {
        const timerOff = params.value;
        const recordDate = params.data.record_date;
          if (!timerOff || !recordDate) return null;
        const [hours, minutes, seconds] = timerOff.split(':');
        const [datePart, timePart] = recordDate.split(' ');
        const [year, month, day] = datePart.split('-');
        let combinedDateTimeString = `${year}-${month}-${day}T${hours}:${minutes}:${seconds}Z`;
        const combinedDateTime = new Date(combinedDateTimeString);
        const userLocale = navigator.language || navigator.userLanguage;
        const formattedTime = combinedDateTime.toLocaleTimeString(userLocale, { hour12: false });
        return formattedTime;
    },
},
        { headerName: 'Net Time', field: 'net_time' },

        {headerName: 'Billable', field: 'billable',
          cellRenderer: function (params) {
                const row = params.data;
                if (row && row.billable == 1) {
                    return 'Yes'; // Added a check for l_name as well
                } else {
                    return 'No';
                } 
          },
        },

        {headerName: 'CM', field: 'created_by_user'},
        { headerName: 'Activity', field: 'activity' },
        { headerName: 'Comments', field: 'comments' },

      
    ]); 
    

    const fetchPatientChildList = async (id) => {
        try { //debugger;
          // alert(id)
            loading.value = true;
            let patient =   id;//props.patient;
            let practiceId = props.componentConstProps.practiceId;
            let emr =null;
            let caremanager = props.componentConstProps.caremanager; 
            let module = props.componentConstProps.module;
            let sub_module =null; 
            let activedeactivestatus = props.componentConstProps.activedeactivestatus;
            const fromdate = props.componentConstProps.fromdate;
            const todate = props.componentConstProps.todate;
            
            if(patient == '') {
              patient = 'null';
            }
            if(practiceId == '') {
              practiceId = 'null';
            } 
            
            if(emr == '' || emr == undefined ) {
              emr = 'null';
            }
            
            if (sub_module == '' || sub_module == undefined ) {
              sub_module = 'null';
            }
            if (caremanager == '' ) {
              caremanager = 'null';
            }
            if (module == '') {
              module = 'null';
            }
            if (activedeactivestatus == '') {
                activedeactivestatus = 'null';
            }
          //   await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
            const response = await fetch('/reports/patient-time-Log/' + patient + '/' + practiceId + '/' + emr + '/' +caremanager+'/'+module+'/'+sub_module+'/'+fromdate+'/'+todate+'/'+activedeactivestatus);
            if (!response.ok) {
                throw new Error('Failed to fetch child list');
            } 
            loading.value = false;
            const data = await response.json();
            childRowData.value = data.data;
        } catch (error) {
            console.error('Error fetching child list:', error);
            loading.value = false;
        }
    }; 

    onMounted(() => {
      //  fetchPatientChildList(props.id);
    });

    return {
        loading,
        childcolumnDefs,
        childRowData,
        fetchPatientChildList,
        isLoading,
        isOpen,
        openModal, 
        closeModal,

    }; 
},
components: {
  AgGridTable,
  LayoutComponent,
},
};
</script>