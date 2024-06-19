<template>
    <div>
      <AgGridTable :rowData="rowData" :columnDefs="columnDefs" />
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { ref, onMounted } from 'vue';
  import { AgGridTable } from '../../commonImports';
  import jsPDF from 'jspdf';
  import 'jspdf-autotable';
  
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
      const loading = ref(true);
  
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
            { headerName: 'Heartrate Threshold', field: 'heartrate_threshold', width: 140 },
            { headerName: 'Heartrate', field: 'heartratereading', width: 140 },
          ],
        },
      ];
  
      switch (props.deviceID) {
        case 1:
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
          break;
        case 2:
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
          break;
        case 3:
          table.value = 'observationsbp';
          break;
        case 4:
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
          break;
        case 5:
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
          break;
        case 6:
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
          break;
        default:
          table.value = 'observationsbp';
      }
  
      const columnDefs = ref(colDefs);
  
      const fetchTableDeviceData = async () => {
        try {
          const response = await fetch(
            `/rpm/patient-alert-history-list-device-link/${props.patientId}/${table.value}/${props.fromDate}/${props.toDate}`
          );
          if (!response.ok) {
            throw new Error('Failed to fetch device data');
          }
          const data = await response.json();
          rowData.value = data.data;
        } catch (error) {
          console.error('Error fetching device data:', error);
        } finally {
          loading.value = false;
        }
      };
  

      onMounted(async () => {
        try {
          await fetchTableDeviceData();
        } catch (error) {
          console.error('Error on page load:', error);
        }
      });
  
      return {
        columnDefs,
        rowData,
        fetchTableDeviceData,
        table,
        loading,
      };
    },
  };
  </script>
  
  <style>
  /* Add your styles here */
  </style>
  