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

         <div v-if="loading" class="table-responsive loading-spinner">
              <div class="ag-custom-loading-cell" style="padding-left: 10px; line-height: 25px;">
      <i class="fas fa-spinner fa-pulse"></i> <span>One Moment Please...............</span>
      </div>
              </div>
              <div  v-else  style="height: 100vh;">

                <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="quick-filter"></div>

            <div class="ml-auto"> <!-- This div aligns elements to the right -->
              <div class="oval-search-container">
                <input type="text" id="filter-text-box" placeholder="Search..." v-model="filterText" @input="onFilterTextBoxChanged">
                <img src="/assets/images/search.png" class="search-icon" alt="search">
              </div>
              <img src="/assets/images/excel_icon.png" width="20" v-on:click="onBtnExport()" alt="excel">
              <img src="/assets/images/pdf_icon.png" width="20" @click="exportAsPDF" alt="export pdf" data-toggle="tooltip" data-placement="top" title="pdf" data-original-title="PDF">
              <img src="/assets/images/copy_icon.png" width="20" @click="copySelectedRows" alt="copy" data-toggle="tooltip" data-placement="top" title="copy" data-original-title="Copy">
            </div>
          </div>
              <!--   <table id="patient-list" ref="dataTable" class="display table table-striped table-bordered"
                  style="width:100%">
                </table> -->
                <!-- Ag-Grid Vue component -->
                <ag-grid-vue
      style="width: 100%; height: 95%;"
      class="ag-theme-quartz-dark"
      :gridOptions="gridOptions"
      :defaultColDef="defaultColDef"
      :columnDefs="columnDefs"
      :rowData="rowData"
      @grid-ready="onGridReady"
                :suppressExcelExport="true"
                :popupParent="popupParent"
                ></ag-grid-vue>
              </div>

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
  AgGridVue,
  onBeforeMount,
} from './commonImports';
import LayoutComponent from './LayoutComponent.vue'; // Import your layout component
import axios from 'axios';
import jsPDF from 'jspdf';
import 'jspdf-autotable';


export default {
  components: {
    LayoutComponent,
    AgGridVue,
  },
  setup() {
    const gridApi = ref(null);
    const gridColumnApi = ref(null);
    const filterText = ref('');
    const popupParent = ref(null);
    const rowData = ref();
    const loading = ref(false);
    const tableInstance = ref(null); // Define tableInstance using ref()
    const showModal = ref(false);
    const selectedPractice = ref(null);
    const selectedPatients = ref(null);
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

    onBeforeMount(() => {
      popupParent.value = document.body;
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


    const onBtnExport = () => {
      const fileName = 'Renova Healthcare'; // Replace 'custom_filename' with your desired file name
      const params = {
    fileName: fileName,
    processCellCallback: (params) => {
      // Remove HTML tags from cell values
      if (params.value && typeof params.value === 'string') {
        const div = document.createElement('div');
        div.innerHTML = params.value;
        return div.textContent || div.innerText || '';
      }
      return params.value;
    },
  };
  gridApi.value.exportDataAsCsv(params);
    };
    const onBtnUpdate = () => {
      document.querySelector('#csvResult').value = gridApi.value.getDataAsCsv();
      
    };

    const onGridReady = (params) => {
      gridApi.value = params.api; // Set the grid API when the grid is ready
      gridColumnApi.value = params.columnApi;
    };

    const copySelectedRows = () => {
      if (gridApi.value) {
        const rowData = gridApi.value.getModel().rowsToDisplay.map(row => {
          // Extracting text content from each cell
          const rowDataWithoutHTML = {};
          gridApi.value.getColumnDefs().forEach(colDef => {
            const value = gridApi.value.getValue(colDef.field, row);
            rowDataWithoutHTML[colDef.field] = value ? value.toString().replace(/<[^>]+>/g, '') : '';
          });
          return rowDataWithoutHTML;
        });
        copyDataToClipboard(rowData);
      }
    };

    const copyDataToClipboard = (data) => {
      const textToCopy = data.map(row => Object.values(row).join('\t')).join('\n');
      navigator.clipboard.writeText(textToCopy)
        .then(() => {
          alert('Data copied to clipboard');
          // Optionally, show a success message or perform other actions after copying
        })
        .catch((error) => {
          console.error('Unable to copy data to clipboard:', error);
          // Handle any errors encountered during copying
        });
    };

    function sanitizeDataForPDFExport(data) {
  if (typeof data === 'string') {
    // Remove HTML tags from the string
    return data.replace(/<[^>]*>?/gm, '');
  }
  return data;
};

function exportAsPDF() {
  const doc = new jsPDF();

  // Extracting column headers
  const columns = columnDefs.value.map((columnDef) => columnDef.headerName);

  // Extracting row data in a format compatible with autoTable
  const rows = rowData.value.map((row) => {
    // Generating an array containing values for each column in the row
    const rowDataArray = columns.map((col) => {
      switch (col) {
        case 'Sr. No.':
          return rowData.value.indexOf(row) + 1;
        case 'Patient Name':
          return `${row['pfname']} ${row['plname']}`;
        case 'DOB':
          // Assuming 'pdob' contains a date string
          const date = row['pdob'];
          if (!date) return null;
          return new Date(date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
          });
        // You can add cases for other columns as required
        default:
          // For other columns, sanitize the data and return
          return sanitizeDataForPDFExport(row[col.toLowerCase()]);
      }
    });

    return rowDataArray;
  });

  doc.autoTable({
    head: [columns],
    body: rows,
  });

  doc.save('Renova_Healthcare.pdf');
};




    const onFilterTextBoxChanged = () => {
      if (gridApi.value) {
        gridApi.value.setGridOption(
        'quickFilterText',
        filterText.value
      );
      }
    };

    // Define a custom cell renderer function
const customCellRenderer = (params) => {
  const row = params.data;
  if (row && row.action) {
    return row.action; // Returning the HTML content as provided from the controller
  } else {
    return ''; // Or handle the case where the 'action' value is not available
  }
};

    // Define a custom cell renderer function
    const customCellRendererstatus = (params) => {
  const row = params.data;
  if (row && row.activedeactive) {
    return row.activedeactive; // Returning the HTML content as provided from the controller
  } else {
    return ''; // Or handle the case where the 'action' value is not available
  }
};
    const columnDefs  = ref([
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
      {
    headerName: 'DOB',
    field: 'pdob',
    valueFormatter: function (params) {
      const date = params.value; // Assuming pdob contains a date string
      if (!date) return null;

      const formattedDate = new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
      });

      return formattedDate; // Returns the date in MM-DD-YYYY format
    },
  },
      { headerName: 'Practice', field: 'pracpracticename' },
      { headerName: 'Last contact Date', field: 'csslastdate' },
      { headerName: 'Total Time Spent', field: 'ptrtotaltime' },
      { headerName: 'Action', field: 'action' , cellRenderer: customCellRenderer, },
      {
        headerName: 'Patient Status',
        field: 'activedeactive'
        , cellRenderer: (params) => {
        const link = document.createElement('a');
        const icon = document.createElement('i');
        icon.classList.add('text-20', 'i-Stopwatch');
        const { data } = params;
        if (data.pstatus === 1) {
          icon.style.color = 'green';
        } else {
          icon.style.color = 'red';
        }
        link.appendChild(icon);
        link.classList.add('ActiveDeactiveClass');
        link.href = 'javascript:void(0)';
        link.addEventListener('click', () => {
          callExternalFunctionWithParams(data.pid, data.pstatus);
        });
        return link;
      },
      },
      { headerName: 'Call Score', field: 'pssscore' },
    ]);

    const defaultColDef = ref({
      sortable:true,
      flex:1,
    });
    const gridOptions = ref({
        pagination: true,
        paginationAutoPageSize: true,
});
     

// Watch for changes in selectedPractice
    watch(selectedPractice, (newPracticeId) => {
      fetchPatients(newPracticeId);
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
        gridApi.value.showLoadingOverlay();
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



    const getPatientList = async (practice_id, patient_id, module_id, timeoption, time, activedeactivestatus) => {
  try {
    const response = await fetch(`/patients/worklist/${practice_id}/${patient_id}/${module_id}/${timeoption}/${time}/${activedeactivestatus}`);
    
    if (!response.ok) {
      throw new Error('Failed to fetch patient list');
    }
    
    const reader = response.body.getReader();
    const contentLength = +response.headers.get('content-length');
    let receivedLength = 0;
    let chunks = [];

    while (true) {
      const { done, value } = await reader.read();
      
      if (done) {
        break;
      }

      chunks.push(value);
      receivedLength += value.length;

      // Process chunks if necessary or concatenate them
    }

    const concatenated = new Uint8Array(receivedLength);
    let position = 0;

    for (const chunk of chunks) {
      concatenated.set(chunk, position);
      position += chunk.length;
    }

    // Process or parse the concatenated data
    const data = JSON.parse(new TextDecoder('utf-8').decode(concatenated));

    // Do something with the data (e.g., assign it to rowData)
    rowData.value = data.data || [];

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
 // When the Submit button is clicked within the modal
 $('.submit-active-deactive').on('click', function() {
    // Serialize the form data
    const formData = $('#active_deactive_form').serialize();

    // Make an AJAX POST request to the specified route
    $.ajax({
      type: 'POST',
      url: '/patients/patient-active-deactive',
      data: formData,
      success: function(response) {
        // Display the response message within the modal
        $('#patientalertdiv').html('<div class="alert alert-success">' + response.message + '</div>');

        // Optionally, close the modal after a certain delay
        setTimeout(function() {
          $('#active-deactive').modal('hide');
        }, 3000); // Close the modal after 3 seconds (3000 milliseconds)
      },
      error: function(xhr, status, error) {
        // Display error messages in case of failure
        $('#patientalertdiv').html('<div class="alert alert-danger">Error: ' + error + '</div>');
      }
    });
  });
    return {
      loading,
      tableInstance,
      showModal,
      selectedPractice,
      selectedPatients,
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
      gridApi,
      filterText,
      popupParent,
      gridColumnApi,
      exportAsPDF,
      onBtnExport,
      onBtnUpdate,
      onGridReady,
      onFilterTextBoxChanged,
      handleSubmit,
      callExternalFunctionWithParams,
      handleChange,
      copySelectedRows,
      handleReset,
    };
  },

};



</script>
<style>
@import 'ag-grid-community/styles/ag-grid.css';
@import 'ag-grid-community/styles/ag-theme-quartz.css'; /* Use the theme you prefer */

.ag-theme-quartz,
.ag-theme-quartz-dark {
  --ag-foreground-color: rgb(63, 130, 154);
  --ag-background-color: rgb(238, 238, 238);
  --ag-header-foreground-color: rgb(63, 130, 154);
  --ag-header-background-color: rgb(238, 238, 238);
  --ag-odd-row-background-color: rgb(255, 255, 255);
  --ag-header-column-resize-handle-color: rgb(63, 130, 154);

  --ag-font-size: 17px;
  --ag-font-family: monospace;
}

.loading-spinner {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100px;
  /* Adjust as needed */
}
.quick-filter {
  display: flex;
  align-items: center;
}

.export-button {
  cursor: pointer;
}

.search-container {
  display: inline-block;
  position: relative;
  border-radius: 50px; /* To create an oval shape, use a large value for border-radius */
  overflow: hidden;
  width: 200px; /* Adjust width as needed */
}

.oval-search-container {
  position: relative;
  display: inline-block;
 /*  border: 1px solid #ccc; */ /* Adding a visible border */
  /* border-radius: 20px; */ /* Adjust border-radius for a rounded shape */
  /* width: 200px; */ /* Adjust width as needed */
  margin-right: 10px; /* Adjust margin between the search box and icons */
}

input[type="text"] {
  width: calc(100% - 0px); /* Adjust the input width considering the icon */
 /*  border: none; */
  outline: none;
  border-radius:10px;
}

.search-icon {
  position: absolute;
  top: 50%;
  right: 1px;
  transform: translateY(-50%);
  width: 20px; /* Adjust icon size as needed */
  height: auto;
}

/* Align the export icons properly */
.ml-auto img {
  margin-right: 5px; /* Adjust margin between the export icons */
}

</style>