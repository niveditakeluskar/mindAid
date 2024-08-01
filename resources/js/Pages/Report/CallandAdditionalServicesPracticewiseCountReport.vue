<template>
    <LayoutComponent>
      <loading-spinner :isLoading="isLoading"></loading-spinner>
      <div class="breadcrusmb">
        <div class="row">
          <div class="col-md-11">
            <h4 class="card-title mb-3">Call And Additional Services Report</h4>
          </div>
        </div>
      </div>
      <div class="separator-breadcrumb border-top"></div>
  
      <div class="row">
        <div class="col-md-12 mb-4">
          <div class="card text-left">
            <div class="card-body">
              <form id="report_form" name="report_form" method="post" action="">
                <div class="form-row">
                  <div class="col-md-2 form-group mb-2">
                    <label for="practicename">Organization</label>
                    <select id="practicesgrp" name="practicesgrp" class="custom-select show-tick select2" data-live-search="true"
                      v-model="selectedGroupid" @change="fetchPractices(selectedGroupid)">
                      <option value="">Select Organization</option>
                      <option v-for="practicegp in practicegroup" :key="practicegp.id" :value="practicegp.id">
                        {{ practicegp.practice_name }}
                      </option>
                    </select>
                  </div>
                  <div class="col-md-2 form-group mb-2">
                    <label for="practicename">Practice</label>
                    <select id="practices" name="practices" class="custom-select show-tick select2" data-live-search="true"
                      v-model="selectedPractice" @change="fetchProvider(selectedPractice)">
                      <option value="">Select Practices</option>
                      <option v-for="practice in practices" :key="practice.id" :value="practice.id">
                        {{ practice.name }}
                      </option>
                    </select>
                  </div>
  
                  <div class="col-md-2 form-group mb-3">
                    <label for="provider">Provider</label>
                    <select id="physician" name="physician" class="custom-select show-tick select2" data-live-search="true"
                      v-model="selectedProvider">
                      <option value="">Select Provider</option>
                      <option v-for="physician in physicians" :key="physician.id" :value="physician.id">
                        {{ physician.name }}
                      </option>
                    </select>
                  </div>
            
  
                  <div class="col-md-2 form-group mb-3">
                    <label for="month"> From Date </label>
                    <input id="fromdate" class="form-control" type="date"
                      v-model="fromdate">
                  </div>

                    <div class="col-md-2 form-group mb-3">
                    <label for="month"> To Date </label>
                    <input id="todate" class="form-control" type="date"  autocomplete="off"
                      v-model="todate">
                  </div>
  
                
                  <div class="row col-md-2 mb-2">
                    <div class="col-md-5">
                      <button type="button" class="btn btn-primary mt-4" id="month-search"
                        @click="fetchFilters">Search</button>
                    </div>
                    <div class="col-md-5">
                      <button type="button" class="btn btn-primary mt-4" id="month-reset">Reset</button>
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
        </div>
      </div>
      <CallAndAdditionalServicesReportModal  ref="CallAndAdditionalServicesReportModalRef"/>
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
  } from './../commonImports';
  import LayoutComponent from './../LayoutComponent.vue';
  import CallAndAdditionalServicesReportModal from './../Modals/CallAndAdditionalServicesReportModal.vue';

  export default {
    components: {
      LayoutComponent,
      AgGridTable,
      CallAndAdditionalServicesReportModal
    },
    setup() {
      const passRowData = ref([]);
      const CallAndAdditionalServicesReportModalRef = ref();
      const practices = ref([]);
      const practicegroup = ref([]);
      const physicians = ref([]);
      const selectedGroupid = ref('');
      const todate = ref('');
      const fromdate = ref('');

      const selectedPractice = ref('');
      const selectedProvider = ref('');
      const activedeactivestatus = ref('');
      const callstatus = ref('');
      const isLoading = ref(false);
      const onlycode = true;
      const patientsmodules = ref('3')
      let c_month = (new Date().getMonth() + 1).toString().padStart(2, "0");
      let c_year = new Date().getFullYear();
      const frommonth = c_year + '-' + c_month;

      
     

      const getStartOfMonth = (date) => {
      const d = new Date(date);
      let month = '' + (d.getMonth() + 1);
      let day = '' + d.getDate();
      const year = d.getFullYear();

      if (month.length < 2) month = '0' + month;
      if (day.length < 2) day = '0' + day;

      return [year, month, day].join('-');
    };

    const callExternalFunctionClick = (parampracticeid) => {
      const paramProvider = selectedProvider.value || null ;
        const paramGroupid = selectedGroupid.value || null ;
        const paramPatientId = null;
        CallAndAdditionalServicesReportModalRef.value.openModal(parampracticeid,paramProvider,paramGroupid,paramPatientId,fromdate.value, todate.value);
           };
      let columnDefs = ref([
    {
        headerName: 'Sr. No.',
        valueGetter: 'node.rowIndex + 1',     flex: 1
      },
{ headerName: "Patient Count",field:"1" , cellRenderer: (params) => {


      const button = document.createElement('button');
      button.type = 'button';
      button.id = 'patientdetails';
      button.className = 'your-button-class'; // Add any necessary classes
      button.innerText = params.data[1].patientCount;
      // Bind the click event with the necessary parameters
      button.onclick = () => {
  
  callExternalFunctionClick(params.data[1].practiceId);
    
};

return button;
}
 },
{ headerName: "Call Answered",field:"2" },
{ headerName: "Call Not Answered",field:"3" },
{ headerName: "Practice Name",field:"4" },
{ headerName: "No Additional Services Provided",field:"5",flex: 2 },
{ headerName: "Authorized CM Only-Renewal RX transmission to the Pharmacy",field:"6" },
{ headerName: "Authorized CM Only-New RX transmission to the Pharmacy",field:"7" },
{ headerName: "Mailed Documents-Health Education Material",field:"8" },
{ headerName: "Mailed Documents-Letters",field:"9" },
{ headerName: "Mailed Documents-Care Plans",field:"10" },
{ headerName: "Mailed Documents-Lab/Test Results",field:"11" },
 { headerName: "Mailed Documents-Resource Support Material",field:"12" },

 { headerName: "Medication Support-Medication Renewal Research",field:"13" },

 { headerName: "Medication Support-Medication Change Request",field:"14" },

 { headerName: "Medication Support-Medication Sample Request",field:"15" },

 { headerName: "Medication Support-Medication Prior Authorization",field:"16" },

 { headerName: "Medication Support-Medication Cost Assistance",field:"17" },

 { headerName: "Referral/Order Support-Social Services",field:"18" },

 { headerName: "Referral/Order Support-Home Health Physical Therapy",field:"19" },

 { headerName: "Referral/Order Support-Home Health Skilled Nursing",field:"20" },

 { headerName: "Referral/Order Support-Home Health Occupational Therapy",field:"21" },

 { headerName: "Referral/Order Support-DME",field:"22" },

 { headerName: "Referral/Order Support-Specialist",field:"23" },

 { headerName: "Referral/Order Support-Mental Health",field:"24" },

 { headerName: "Referral/Order Support-Hospice",field:"25" },

 { headerName: "Referral/Order Support-Oxygen",field:"26" },

 { headerName: "Referral/Order Support-Medical Supplies",field:"27" },

 { headerName: "Resource Support-Hospice",field:"28" },

 { headerName: "Resource Support-Mental Health",field:"29" },

 { headerName: "Resource Support-Community and Social Groups",field:"30" },

 { headerName: "Resource Support-Veterinary Care Assistance",field:"31" },

 { headerName: "Resource Support-Food",field:"32" },

 { headerName: "Resource Support-Housing",field:"33" },

 { headerName: "Resource Support-Transportation",field:"34" },

 { headerName: "Resource Support-Utilities",field:"35" },

 { headerName: "Resource Support-ADLs",field:"36" },

 { headerName: "Resource Support-Home Health",field:"37" },

 { headerName: "Resource Support-Housekeeping and errands",field:"38" },

 { headerName: "Routine Response-Interaction with Office Staff",field:"39" },

 { headerName: "Routine Response-Vision or Dental appointment scheduled",field:"40" },

 { headerName: "Routine Response-Interaction with PCP",field:"41" },

 { headerName: "Routine Response-PCP appointment scheduled",field:"42" },

 { headerName: "Routine Response-Specialist appointment scheduled",field:"43" },

 { headerName: "Routine Response-AWV appointment scheduled",field:"44" },

 { headerName: "Routine Response-Prior Authorization for Labs or Diagnostics",field:"45" },

 { headerName: "Routine Response-Medical Records Request",field:"46" },

 { headerName: "Urgent/Emergent Response-Patient told to call 911",field:"47" },

 { headerName: "Urgent/Emergent Response-Care Manager called 911",field:"48" },

{ headerName: "Urgent/Emergent Response-Patient instructed to go to Urgent Care or ED",field:"49" },

 { headerName: "Urgent/Emergent Response-Same Day appointment scheduled",field:"50" },

 { headerName: "Urgent/Emergent Response-Interaction with Office Staff",field:"51" },

 { headerName: "Urgent/Emergent Response-Interaction with PCP",field:"52" },

 { headerName: "Urgent/Emergent Response-Next Day appointment scheduled",field:"53" },

 { headerName: "Verbal Education/Review with Patient-Health Education Material",field:"54" },

 { headerName: "Verbal Education/Review with Patient-Lab/Test Results",field:"55" },

 { headerName: "Verbal Education/Review with Patient-Resource Support Material",field:"56" },

 { headerName: "Verbal Education/Review with Patient-RPM Device Education",field:"57" },

 { headerName: "Veterans Services-Resource Support",field:"58" },

 { headerName: "Veterans Services-Referral Support",field:"59" },

 { headerName: "Veterans Services-Scheduled appointment with VA",field:"60" },

 { headerName: "Veterans Services-Mailed VA forms",field:"61" },

 { headerName: "Veterans Services-Mailed Resource Material",field:"62" },

 { headerName: "Veterans Services-Mailed Health Education Material",field:"63" },

 { headerName: "Veterans Services-Research on behalf of the patient",field:"64" },

 { headerName: "Veterans Services-Medical Records Request",field:"65" },
     ]);
  
  
      const fetchFilters = async () => {
        isLoading.value = true;
        fetchData();
      }
  
      const fetchData = async () => {
        try {
         
          let selectedGroupids = selectedGroupid.value;
          let selectedPractices = selectedPractice.value;
          let selectedProviders = selectedProvider.value;

          if (selectedGroupid.value == '') {
            selectedGroupids = 'null';
          }
          if (selectedPractice.value == '') {
            selectedPractices = 'null';
          }
          if (selectedProvider.value == '') {
            selectedProviders = 'null';
          }
      
          const response = await fetch(`/reports/countcallAdditionalServiceListSearch/${selectedGroupids}/${selectedPractices}/${selectedProviders}/${fromdate.value}/${todate.value}`);
          const data = await response.json();
           
          passRowData.value = data.DATA;
          isLoading.value = false;
        } catch (error) {
          console.error('Error fetching user filters:', error);
        }
      };
  
      const fetchPractices = async (id) => {
        try {
          const response = await fetch('../org/ajax/practicegrp/' + id + '/practice');
          if (!response.ok) {
            throw new Error('Failed to fetch practices');
          }
          const data = await response.json();
          practices.value = data;
        } catch (error) {
          console.error('Error fetching practices:', error);
        }
      };
  
      const fetchPracticegroup = async () => {
        try {
          const response = await fetch('../org/practicesgroup');
          if (!response.ok) {
            throw new Error('Failed to fetch practices');
          }
          const data = await response.json();
          practicegroup.value = data;
        } catch (error) {
          console.error('Error fetching practices:', error);
        }
      };
  
      const fetchProvider = async (practiceId) => {
        try {
          const response = await fetch('../org/ajax/practice/' + practiceId + '/physicians');
          if (!response.ok) {
            throw new Error('Failed to fetch practices');
          }
          const data = await response.json();
          physicians.value = data;
        } catch (error) {
          console.error('Error fetching practices:', error);
        }
      };
  
      onMounted(async () => {
      const currentDate = new Date();
      const startDate = new Date();
      startDate.setDate(1);
        fromdate.value = getStartOfMonth(startDate);
              todate.value = getStartOfMonth(currentDate);

        fetchPracticegroup();
        fetchPractices(null);
        fetchProvider(null);
        fetchData();
      });
  
  
      return {
        CallAndAdditionalServicesReportModalRef,
        fetchData,
        columnDefs,
        passRowData,
        fetchFilters,
        fetchPractices,
        practices,
        practicegroup,
        fetchPracticegroup,
        fetchProvider,
        physicians,
        selectedGroupid,
        fromdate,
        todate,
        selectedPractice,
        selectedProvider,
        activedeactivestatus,
        callstatus,
        onlycode,
        frommonth,
        isLoading,
        patientsmodules
      };
    },
  };
  </script>