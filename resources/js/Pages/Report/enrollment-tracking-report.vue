<template>
  <LayoutComponent>
    <loading-spinner :isLoading="isLoading"></loading-spinner>
    <div class="breadcrusmb">
      <div class="row">
        <div class="col-md-11">
          <h4 class="card-title mb-3">Enrollment Tracking Report</h4>
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
                <div class="col-md-2 form-group mb-3">
                  <label for="month">Month & Year</label>
                  <input id="from_month" class="form-control" name="from_month" type="month" value="" autocomplete="off" v-model="frommonth">
                </div>
                <div class="row col-md-2 mb-2">
                  <div class="col-md-5">
                    <button type="button" class="btn btn-primary mt-4" id="month-search" @click="fetchFilters">Search</button>
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
              <AgGridTable
                class="ag-theme-alpine"
                style="width: 100%; height: 600px;"
                :rowData="passRowData"
                :columnDefs="columnDefs"
                :defaultColDef="defaultColDef"
                :domLayout="'autoHeight'"
                :getRowHeight="getRowHeight"
                :components="components"
              ></AgGridTable>
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
  //AgGridTable,
  onBeforeMount,
} from './../commonImports';
import LayoutComponent from './../LayoutComponent.vue';
import AgGridTable from './../components/AgGridTable.vue';
import LoadingSpinner from './../LoadingSpinner.vue';

export default {
  props: {},
  components: {
    LayoutComponent,
    AgGridTable,
    LoadingSpinner
  },
  setup(props) {
    const passRowData = ref([]);
    let test = [];
    const activedeactivestatus = ref('');
    const callstatus = ref('');
    const isLoading = ref(false);
    let c_month = (new Date().getMonth() + 1).toString().padStart(2, "0");
    let c_year = new Date().getFullYear();
    const frommonth = c_year + '-' + c_month;
    const columnDefs = ref([]);

    const fetchFilters = async () => {
      await fetchData();
    }

     const addDynamicColumns = (columns, rowData) => {
      columnDefs.value = columns.map((col, index) => ({
        headerName: col.title,
        field: `col${index}`,
        cellClass: (params) => {
          return params.data && params.data.isTotal && index === 0 ? 'total-cell' : '';
        },
        cellStyle: (params) => {
          if (params.data && params.data.isTotal) {
            return {
              'font-weight': 'bold',
              'background-color': '#e0e0e0',
            };
          }
          return null; // No additional styles for other cells
        },
        colSpan: (params) => {
          if (params.data && params.data.isTotal && index === 0) {
            return 2;
          }
          return 1;
        },
      }));

      const groupedRows = {};
      rowData.forEach((row) => {
        const groupKey = row[0];
        if (!groupedRows[groupKey]) {
          groupedRows[groupKey] = [];
        }
        groupedRows[groupKey].push(row);
      });

      passRowData.value = [];
      Object.keys(groupedRows).forEach((groupKey) => {
        const totals = new Array(columns.length).fill(0);
        groupedRows[groupKey].forEach((row) => {
          row.forEach((value, index) => {
            if (index > 1 && typeof value === 'number') {
              totals[index] += value;
            }
          });
        });

        const totalRow = { col0: `Total for ${groupKey}`, isTotal: true };
        totals.forEach((total, index) => {
          if (index > 1) {
            totalRow[`col${index}`] = total;
          }
        });
        passRowData.value.push(totalRow);

        groupedRows[groupKey].forEach((row) => {
          const mappedRow = {};
          row.forEach((value, index) => {
            mappedRow[`col${index}`] = value;
          });
          mappedRow['desiredValue'] = groupKey;
          passRowData.value.push(mappedRow);
        });
      });
    };

    const fetchData = async () => { 
      try {
        isLoading.value = true;
        let m = document.getElementById("from_month").value;
        const response = await fetch(`/reports/enrollment-tracking-report/search/${m}`);
        const data = await response.json();
        const columns = data.COLUMNS;
        const rowData = data.DATA;

        console.log('Fetched Data:', data);
        addDynamicColumns(columns, rowData);
        
        isLoading.value = false;
      } catch (error) {
        console.error('Error fetching user filters:', error);
      }
    };

    onMounted(async () => {
      fetchData();
    });

    return {
      fetchData,
      columnDefs,
      passRowData,
      fetchFilters,
      activedeactivestatus,
      callstatus,
      frommonth,
      isLoading,
    };
  },
};
</script>

