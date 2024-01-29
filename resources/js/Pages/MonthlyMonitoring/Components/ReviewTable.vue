<template>
                      <AgGridTable :rowData="rowData" :columnDefs="columnDefs"/>

</template>
<script>
import axios from 'axios';
import {
    reactive,
    onMounted,
    ref,
    AgGridTable,
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
        AgGridTable,
    },
    setup(props) {
        const rowData = ref([]);
      
        const table = ref();
      

        const colDefs = [
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

        const columnDefs = ref(
            colDefs
        );

       

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
            colDefs,
            rowData,           
            fetchFollowupMasterTaskList,
            table,
            // addNewItem,
            // removeItem,
        };
    }

}
</script>
<style>

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