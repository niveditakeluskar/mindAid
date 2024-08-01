<template>
    <div class="table-responsive">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="quick-filter"></div>
        <div class="ml-auto">
          <div class="oval-search-container">
            <input type="text" id="filter-text-box" placeholder="Search..." v-model="filterText" @input="onFilterTextBoxChanged">
            <img src="/assets/images/search.png" class="search-icon" alt="search" />
          </div>
          <img src="/assets/images/excel_icon.png" width="20" @click="onBtnExport" alt="excel" />
          <img src="/assets/images/pdf_icon.png" width="20" @click="exportAsPDF" alt="export pdf" data-toggle="tooltip" data-placement="top" title="pdf" data-original-title="PDF" />
          <img src="/assets/images/copy_icon.png" width="20" @click="copySelectedRows" alt="copy" data-toggle="tooltip" data-placement="top" title="copy" data-original-title="Copy" />
        </div>
      </div>
      <ag-grid-vue class="ag-theme-quartz-dark" :gridOptions="gridOptions" :defaultColDef="defaultColDef" :columnDefs="columnDefs" :rowData="rowData" @grid-ready="onGridReady" :suppressExcelExport="true" :paginationPageSizeSelector="paginationPageSizeSelector" :paginationPageSize="paginationPageSize" :headerHeight="headerHeight" :popupParent="popupParent" :stopEditingWhenCellsLoseFocus="true"></ag-grid-vue>
    </div>
  </template>
  
  <script>
  import { AgGridVue } from 'ag-grid-vue3';
  import { ref, onBeforeMount } from 'vue';
  import jsPDF from 'jspdf';
  import 'jspdf-autotable';
  
  export default {
    props: {
      gridOptions: Object,
      defaultColDef: Object,
      columnDefs: Array,
      rowData: Array,
      popupParent: Object,
      onGridReady: Function,
    },
    components: {
      AgGridVue,
    },
    setup(props) {
      const paginationPageSizeSelector = ref([10, 20, 30, 40, 50, 100]);
      const paginationPageSize = ref(null);
      const filterText = ref('');
      const popupParent = ref(null);
      const gridApi = ref(null);
      const gridColumnApi = ref(null);
      const headerHeight = ref(null);
  
      onBeforeMount(() => {
        headerHeight.value = 70;
        if (!props.popupParent) {
          props.popupParent = document.body;
        }
      });
  
      const onBtnExport = () => {
        const fileName = 'Renova Healthcare';
        const params = {
          fileName: fileName,
          processCellCallback: (params) => {
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
  
      const onGridReady = (params) => {
        gridApi.value = params.api;
        gridColumnApi.value = params.columnApi;
        paginationPageSize.value = 10;
        paginationPageSizeSelector.value = [10, 20, 30, 40, 50, 100];
        if (props.onGridReady) {
          props.onGridReady(params);
        }
      };
  
      const defaultColDef = ref({
        sortable: true,
        editable: false,
        resizable: false,
        wrapText: true,
        autoHeight: true,
        autoHeaderHeight: true,
        flex: 1,
      });
  
      const gridOptions = ref({
        pagination: true,
        paginationPageSize: 20,
        domLayout: 'autoHeight',
      });
  
      const copySelectedRows = () => {
        if (gridApi.value) {
          const rowData = gridApi.value.getModel().rowsToDisplay.map((row) => {
            const rowDataWithoutHTML = {};
            gridApi.value.getColumnDefs().forEach((colDef) => {
              const value = gridApi.value.getValue(colDef.field, row);
              rowDataWithoutHTML[colDef.field] = value ? value.toString().replace(/<[^>]+>/g, '') : '';
            });
            return rowDataWithoutHTML;
          });
          copyDataToClipboard(rowData);
        }
      };
  
      const copyDataToClipboard = (data) => {
        const textToCopy = data.map((row) => Object.values(row).join('\t')).join('\n');
        navigator.clipboard.writeText(textToCopy).then(() => {
          alert('Data copied to clipboard');
        }).catch((error) => {
          console.error('Unable to copy data to clipboard:', error);
        });
      };
  
      const sanitizeDataForPDFExport = (data) => {
        if (typeof data === 'string') {
          const dateObject = new Date(data);
          if (!isNaN(dateObject.getTime())) {
            return dateObject.toLocaleDateString('en-US', {
              year: 'numeric',
              month: '2-digit',
              day: '2-digit',
            });
          }
  
          const htmlElement = document.createElement('div');
          htmlElement.innerHTML = data;
  
          if (htmlElement.children.length > 0) {
            return htmlElement.textContent || htmlElement.innerText || '';
          }
  
          return data.replace(/<[^>]*>?/gm, '');
        } else if (data instanceof Date) {
          return data.toLocaleDateString('en-US', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
          });
        } else if (typeof data === 'object' && data !== null) {
          if ('href' in data) {
            return sanitizeDataForPDFExport(data.href);
          }
        }
  
        return data ? data.toString() : '';
      };
  
      const exportAsPDF = () => {
        const doc = new jsPDF();
       
        const columns = (props.columnDefs || []).map(colDef => 
          colDef.children ? colDef.children.map(child => child.headerName || child.field) : [colDef.headerName || colDef.field]
        ).flat();
  
        const rows = (props.rowData || []).map(row => 
          (props.columnDefs || []).map(colDef => 
            colDef.children ? colDef.children.map(child => sanitizeDataForPDFExport(row[child.field])) : [sanitizeDataForPDFExport(row[colDef.field])]
          ).flat()
        );

        // const rows = (props.rowData || []).map((row, index) => {
        //     const rowData = [
        //     { content: String(index + 1)}, // Add serial number as the first column
        //         (props.columnDefs || []).flatMap(colDef => 
        //         colDef.children ? colDef.children.map(child => sanitizeDataForPDFExport(row[child.field])) : [sanitizeDataForPDFExport(row[colDef.field])]
        //     )
        //     ];
        //     return rowData;
        // });


  
        doc.autoTable({
          head: [columns],
          body: rows,
        //   styles: { cellPadding: 5 }, // Adding padding between cells for gap
        });
  
        doc.save('Renova_Healthcare.pdf');
      };
  
      const onFilterTextBoxChanged = () => {
        if (gridApi.value) {
          gridApi.value.setQuickFilter(filterText.value);
        }
      };
  
      return {
        onGridReady,
        onBtnExport,
        onFilterTextBoxChanged,
        filterText,
        gridApi,
        gridColumnApi,
        gridOptions,
        defaultColDef,
        popupParent,
        copySelectedRows,
        exportAsPDF,
        paginationPageSizeSelector,
        paginationPageSize,
        headerHeight
      };
    },
  };
  </script>
  
  <style>
  @import 'ag-grid-community/styles/ag-grid.css';
  @import 'ag-grid-community/styles/ag-theme-quartz.css';
  
  .ag-theme-quartz,
  .ag-theme-quartz-dark {
      --ag-foreground-color: rgb(63, 130, 154);
      --ag-background-color: rgb(243, 243, 243);
      --ag-header-foreground-color: rgb(63, 130, 154);
      --ag-header-background-color: rgb(238, 238, 238);
      --ag-odd-row-background-color: rgb(255, 255, 255);
      --ag-header-column-resize-handle-color: rgb(63, 130, 154);
      --ag-borders: solid 1px rgb(255, 255, 255);
      --ag-borders-critical: solid 1px rgb(255, 255, 255);
      --ag-borders-secondary: solid 1px rgb(255, 255, 255);
      --ag-secondary-border-color: rgb(255, 255, 255);
      --ag-row-border-style: solid 1px rgb(255, 255, 255);
      --ag-row-border-color: blue;
      --ag-cell-horizontal-border: solid 1px #ddd;
      --ag-border-color: rgb(255, 255, 255);
      --ag-font-size: 0.813rem;
      --ag-font-family: Ubuntu, sans-serif;
      --ag-font-color: #3f829a;
  
      --ag-grid-size: 10px;
      --ag-list-item-height: 20px;
  }
  
  .ag-theme-quartz .ag-header-cell,
  .ag-theme-quartz-dark .ag-header-cell {
      font-weight: bold;
      letter-spacing: 0.3px;
      line-height: 1.6;
      width: 120px;
  }
  
  .ag-cell {
      border: var(--ag-borders);
  }
  
  .ag-header-cell {
      border: var(--ag-borders);
  }
  
  .ag-cell-value {
      line-height: 20px !important;
      word-break: normal;
      padding-top: 5px;
      padding-bottom: 5px;
  }
  
  .ag-row {
      border: var(--ag-row-border-style) var(--ag-row-border-color);
  }
  
  .ag-header-cell-label {
      border-bottom: var(--ag-cell-horizontal-border);
  }
  
  .ag-header-row {
      border-bottom: var(--ag-cell-horizontal-border);
  }
  
  .ag-root-wrapper {
      border: var(--ag-borders);
  }
  
  .ag-popup-editor .ag-large-text,
  .ag-autocomplete-list-popup {
    background-color: rgb(208, 206, 206);
  }
  
  .loading-spinner {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100px;
  }
  
  .quick-filter {
      display: flex;
      align-items: center;
  }
  
  .ag-header-cell-label .ag-header-cell-text {
      word-break: break-word !important;
      white-space: normal !important;
      text-overflow: clip;
      overflow: visible;
  }
  
  .search-container {
      display: inline-block;
      position: relative;
      border-radius: 50px;
      overflow: hidden;
      width: 200px;
  }
  
  .oval-search-container {
      position: relative;
      display: inline-block;
      margin-right: 10px;
  }
  
  .oval-search-container input[type="text"] {
      width: calc(100% - 0px);
      outline: none;
      border-radius: 10px;
      border-width: 1px;
  }
  
  .search-icon {
      position: absolute;
      top: 50%;
      right: 1px;
      transform: translateY(-50%);
      width: 20px;
      height: auto;
  }
  
  .ml-auto img {
      margin-right: 5px;
  }
  </style>
  