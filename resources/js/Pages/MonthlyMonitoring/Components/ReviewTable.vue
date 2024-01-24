<template>
    <div class="table-responsive">
        <ag-grid-vue style="width: 100%; height: 100%;" class="ag-theme-quartz-dark" :gridOptions="gridOptions"
            :defaultColDef="defaultColDef" :columnDefs="columnDefs" :rowData="rowData"
            @grid-ready="onGridReady"></ag-grid-vue>
    </div>
</template>
<script>
import axios from 'axios';
import {
    reactive,
    onMounted,
    ref,
    AgGridVue,
    // Add other common imports if needed
} from '../../commonImports';
export default {
    props: {
        patientId: Number,
        deviceID: Number,
        fromDate: Date,
        toDate: Date,
    },
    components: {
        AgGridVue,
    },
    setup(props) {
        const rowData = ref([]);
        const gridApi = ref(null);
        const gridColumnApi = ref(null);
        const popupParent = ref(null);
        const paginationPageSizeSelector = ref(null);
        const paginationNumberFormatter = ref(null);
        const table = ref();
        const onGridReady = (params) => {
            gridApi.value = params.api; // Set the grid API when the grid is ready
            gridColumnApi.value = params.columnApi;
            paginationPageSizeSelector.value = [10, 20, 30, 40, 50, 100];
            paginationNumberFormatter.value = (params) => {
                return '[' + params.value.toLocaleString() + ']';
            };
        };

        let colDefs = [
            {
                headerName: 'TimeStamp',
                field: 'csseffdate',
            },
            {
                headerName: 'Reading',
                children: [
                    { field: 'threshold', width: 180 },
                    { headerName: 'Systolic', field: 'readingone', width: 90 },
                    { headerName: 'Diastolic', field: 'readingtwo', width: 140 },
                    { headerName: 'Threshold', field: 'heartrate_threshold', width: 140 },
                    { headerName: 'Heartrate', field: 'heartratereading', width: 140 },
                ],
            },

        ];

        if (props.deviceID == 1) {
            table.value = 'observationsweight';
            colDefs = [
                {
                    headerName: 'TimeStamp',
                    field: 'csseffdate',
                },
                {
                    headerName: 'Reading',
                    children: [
                        { field: 'threshold', width: 180 },
                        { headerName: 'Weight', field: 'readingone', width: 90 },
                    ],
                },

            ];
        } else if (props.deviceID == 2) {
            table.value = 'observationsoxymeter';
            colDefs = [
                {
                    headerName: 'TimeStamp',
                    field: 'csseffdate',
                },
                {
                    headerName: 'Reading',
                    children: [
                        { field: 'threshold', width: 180 },
                        { headerName: 'SpO2', field: 'readingone', width: 90 },
                        { headerName: 'Perfusion Index', field: 'readingtwo', width: 90 },
                        { headerName: 'Threshold', field: 'heartrate_threshold', width: 140 },
                        { headerName: 'Heartrate', field: 'heartratereading', width: 140 },
                    ],
                },

            ];
        } else if (props.deviceID == 3) {

            table.value = 'observationsbp';
        } else if (props.deviceID == 4) {
            table.value = 'observationstemp';
            colDefs = [
                {
                    headerName: 'TimeStamp',
                    field: 'csseffdate',
                },
                {
                    headerName: 'Reading',
                    children: [
                        { field: 'threshold', width: 180 },
                        { headerName: 'Temperature', field: 'readingone', width: 90 },
                    ],
                },

            ];
        } else if (props.deviceID == 5) {
            table.value = 'observationsspirometer';
            colDefs = [
                {
                    headerName: 'TimeStamp',
                    field: 'csseffdate',
                },
                {
                    headerName: 'Reading',
                    children: [
                        { field: 'threshold', width: 180 },
                        { headerName: 'FEV1 Value', field: 'readingone', width: 90 },
                        { headerName: 'PEF Value', field: 'readingtwo', width: 140 },
                    ],
                },

            ];
        } else if (props.deviceID == 6) {
            table.value = 'observationsglucose';
            colDefs = [
                {
                    headerName: 'TimeStamp',
                    field: 'csseffdate',
                },
                {
                    headerName: 'Reading',
                    children: [
                        { field: 'threshold', width: 180 },
                        { headerName: 'Glucose Level', field: 'readingone', width: 90 },
                    ],
                },

            ];
        } else {
            table.value = 'observationsbp';
        }

        let columnDefs = ref(
            colDefs
        );

        const defaultColDef = ref({
            flex: 1,
            minWidth: 100,
            editable: false,
        });
        const gridOptions = reactive({
            pagination: true,
            paginationPageSize: 10, // Set the number of rows per page
            domLayout: 'autoHeight',
        });

        const fetchFollowupMasterTaskList = async () => {

            try {
                let u = 'observationsbp';
                await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
                const response = await fetch(`/rpm/patient-alert-history-list-device-link/${props.patientId}/${table.value}/${props.fromDate}/${props.toDate}`);
                if (!response.ok) {
                    throw new Error('Failed to fetch followup task list');
                }
                const data = await response.json();
                rowData.value = data.data; // Replace data with the actual fetched data
            } catch (error) {
                console.error('Error fetching followup task list:', error);
            }
        };


        onMounted(async () => {
            try {
                fetchFollowupMasterTaskList();
            } catch (error) {
                console.error('Error on page load:', error);
            }
        });

        return {
            columnDefs,
            rowData,
            defaultColDef,
            gridOptions,
            popupParent,
            gridApi,
            gridColumnApi,
            onGridReady,
            paginationPageSizeSelector,
            paginationNumberFormatter,
            fetchFollowupMasterTaskList,
            table,
            // addNewItem,
            // removeItem,
        };
    }

}
</script>
<style>
@import 'ag-grid-community/styles/ag-grid.css';
@import 'ag-grid-community/styles/ag-theme-quartz.css';
/* Use the theme you prefer */

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

.highcharts-figure,
.highcharts-data-table table {
    min-width: 310px;
    max-width: 800px;
    margin: 1em auto;
}

.highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #EBEBEB;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}

.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}

.highcharts-data-table th {
    font-weight: 600;
    padding: 0.5em;
}

.highcharts-data-table td,
.highcharts-data-table th,
.highcharts-data-table caption {
    padding: 0.5em;
}

.highcharts-data-table thead tr,
.highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}

.highcharts-data-table tr:hover {
    background: #f1f7ff;
}

/*calender css*/
.fc-content {
    color: #fff;
}
</style>