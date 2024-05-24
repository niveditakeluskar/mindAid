<template>
  <LayoutComponent>
    <loading-spinner :isLoading="isLoading"></loading-spinner>
    <div class="breadcrusmb">
      <div class="row">
        <div class="col-md-11">
          <h4 class="card-title mb-3">Monthly Billable Report</h4>
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
                  <select id="practicesgrp" class="custom-select show-tick select2" data-live-search="true"
                    v-model="selectedGroupid" @change="fetchPractices(selectedGroupid)">
                    <option value="">Select Organization</option>
                    <option v-for="practicegp in practicegroup" :key="practicegp.id" :value="practicegp.id">
                      {{ practicegp.practice_name }}
                    </option>
                  </select>
                </div>
                <div class="col-md-2 form-group mb-2">
                  <label for="practicename">Practice</label>
                  <select id="practices" class="custom-select show-tick select2" data-live-search="true"
                    v-model="selectedPractice" @change="fetchProvider(selectedPractice)">
                    <option value="">Select Practices</option>
                    <option v-for="practice in practices" :key="practice.id" :value="practice.id">
                      {{ practice.name }}
                    </option>
                  </select>
                </div>

                <div class="col-md-2 form-group mb-3">
                  <label for="provider">Provider</label>
                  <select id="physician" class="custom-select show-tick select2" data-live-search="true"
                    v-model="selectedProvider">
                    <option value="">Select Provider</option>
                    <option v-for="physician in physicians" :key="physician.id" :value="physician.id">
                      {{ physician.name }}
                    </option>
                  </select>
                </div>
                <div class="col-md-2 form-group mb-3">
                    <label for="module">Module</label>
                    <select name="modules" id="modules" v-model="patientsmodules" class="custom-select show-tick">
                      <option value="3" selected>CCM</option>
                      <option value="2">RPM</option>
                    </select>
                  </div>

                <div class="col-md-2 form-group mb-3">
                  <label for="month">Month & Year</label>
                  <input id="from_month" class="form-control" name="from_month" type="month" value="" autocomplete="off"
                    v-model="frommonth">
                </div>
                <div class="col-md-2 form-group mb-3">
                  <label for="activedeactivestatus">Status</label>
                  <select id="activedeactivestatus" name="activedeactivestatus" class="custom-select show-tick"
                    v-model="activedeactivestatus">
                    <option value="" selected>All (Active,Suspended,Deactivated,Deceased)</option>
                    <option value="1">Active</option>
                    <option value="0">Suspended</option>
                    <option value="2">Deactivated</option>
                    <option value="3">Deceased</option>
                  </select>
                </div>

                <div class="col-md-2 form-group mb-3">
                  <label for="callstatus">Call Answered Status</label>
                  <select id="callstatus" name="callstatus" class="custom-select show-tick" v-model="callstatus">
                    <option value="" selected>All</option>
                    <option value="1">Call Answered </option>
                    <option value="0">Call answered - not good time to call</option>
                    <option value="2">Call Not Answered</option>
                  </select>
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
export default {
  props: {

  },
  components: {
    LayoutComponent,
    AgGridTable,
  },
  setup(props) {
    const passRowData = ref([]);
    let test = [];
    const practices = ref([]);
    const practicegroup = ref([]);
    const physicians = ref([]);
    const selectedGroupid = ref('');
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
    for (var inc = 0; inc < 18; inc++) {
      test.push({ headerName: 'Diagnosis Condition ' + inc, field: 'condition' + inc });
    }
    
    let columnDefs = ref([
      {
        headerName: 'Sr. No.',
        valueGetter: 'node.rowIndex + 1', flex: 1
      },
      { headerName: 'Provider', field: 'prprovidername' },
      { headerName: 'EMR', field: 'pppracticeemr' },
      {
        headerName: 'Patient First Name',
        field: 'pfname',
      },
      {
        headerName: 'Patient Middle Name',
        field: 'pmname',
      },
      {
        headerName: 'Patient Last Name',
        field: 'plname',
      },
      {
        headerName: 'Patient Fin Number',
        field: 'pfin_number',
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
    {
        headerName: 'DOS', field: 'dos',
        cellRenderer: function (params) {
          const date = params.data.dos;
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
        headerName: 'CPT Code', field: 'billingcode',
        cellRenderer: function (params) {
          let billingcode = params.data.billingcode;
          if (billingcode == null || billingcode == 'null') {
            billingcode = '';
          }
          return billingcode;
        },
      },
      { headerName: 'Units', field: '' ,
      cellRenderer: function (params) {
          let unitz = "";
          if ((params.data.ptrtotaltime >= '00:20:00') && (params.data.ptrtotaltime < '00:40:00')) {
            unitz = 1;
          }
          if ((params.data.ptrtotaltime >= '00:40:00') && (params.data.ptrtotaltime < '00:60:00')) {
            unitz = 1;
          }
          if ((params.data.ptrtotaltime >= '00:60:00') && (params.data.ptrtotaltime < '01:30:00')) {
            unitz = 2;
          }
          if ((params.data.ptrtotaltime >= '01:30:00')) {
            unitz = 1;
          }
          if (params.data.billingcode == '99490') {
            unitz = 1;
          }
          return unitz;
        },
    
    },
      {
        headerName: 'Status', field: 'pstatus',
        cellRenderer: function (params) {
          let pstatus = params.data.pstatus;
          if (pstatus == 1) {
            pstatus = 'Active';
          }
          if (pstatus == 0) {
            pstatus = 'Suspended';
          }
          if (pstatus == 2) {
            pstatus = 'Deactived';
          }
          if (pstatus == 3) {
            pstatus = 'Deceased';
          }
          return pstatus;

        },
      },
      {
        headerName: 'Assigned Care Manager', field: 'userfname',
        cellRenderer: function (params) {
          let assigncm = params.data.userfname + " " + params.data.userlname;
          if (params.data.userfname == 'null' || params.data.userfname == null) {
            assigncm = '';
          }
          return assigncm;
        },
      },
      {
        headerName: 'Call Status', field: 'call_conti_status',
        cellRenderer: function (params) {
          let call_conti_status = params.data.call_conti_status;
          if (call_conti_status == '000') {
            call_conti_status = '';
          }
          return call_conti_status;
        },
      },
      {
        headerName: 'CPD Status', field: 'finalize_cpd',
        cellRenderer: function (params) {
          let finalize_cpd = params.data.finalize_cpd;
          if (finalize_cpd == 1) {
            finalize_cpd = 'Yes';
          }
          if (finalize_cpd == 0) {
            finalize_cpd = 'No';
          }
          return finalize_cpd;
        },
      },

        { headerName: 'Billable', field: '',
          cellRenderer: function (params) {
            let qualified = params.data.qualified;
            let unit = params.data.unit;
            if (unit != '' && qualified >= 2) {
              return 'yes';
            }else{
              return 'no';
            }
            
          },
        },
      { headerName: 'Qualifying Conditions', field: '' },
      { headerName: 'Practices', field: 'pracpracticename' },

      { headerName: 'Total Time Spent', field: 'ptrtotaltime' },
      {
        headerName: 'Diagnosis Condition',
        children: test
      },

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
        let activedeactivestatuse = activedeactivestatus.value;
        let callstatuse = callstatus.value;
        let m = $("#from_month").val();
        if (selectedGroupid.value == '') {
          selectedGroupids = 'null';
        }
        if (selectedPractice.value == '') {
          selectedPractices = 'null';
        }
        if (selectedProvider.value == '') {
          selectedProviders = 'null';
        }
        if (activedeactivestatus.value == '') {
          activedeactivestatuse = 'null';
        }
        if (callstatus.value == '') {
          callstatuse = 'null';
        }
        const response = await fetch('/reports/monthlybilling-searh-data/' + selectedGroupids + '/' + selectedPractices + '/' + selectedProviders + '/' +patientsmodules.value+ '/' + m + '/' + activedeactivestatuse + '/' + callstatuse );
        const data = await response.json();
        passRowData.value = data.data;

        passRowData.value.forEach((value, index) => {

          for (var i = 0; i < 18; i++) {
            let label = "condition" + '' + i;
            if (value.pdcode != null && value.pdcode != 'null') {
              //console.log(index);
              passRowData.value[index]['pcode'] = value.pdcode.replaceAll("|", ",");
              var streetaddress = new Array();
              var pdcode = passRowData.value[index]['pcode'];
              streetaddress = pdcode.split(',')
              if (typeof streetaddress[i] === 'undefined') {
                passRowData.value[index][label] = '';
              }
              else {
                passRowData.value[index][label] = streetaddress[i];
              }
            } else {
              passRowData.value[index][label] = '';
            }
          }
        });

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
      fetchPracticegroup();
      fetchPractices(null);
      fetchProvider(null);
      fetchData();
    });


    return {
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