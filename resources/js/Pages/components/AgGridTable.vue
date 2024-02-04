<!-- AgGridTable.vue -->
<template>
    <div class="table-responsive">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="quick-filter"></div>
            <div class="ml-auto"> <!-- This div aligns elements to the right -->
                <div class="oval-search-container">
                    <input type="text" id="filter-text-box" placeholder="Search..." v-model="filterText"
                        @input="onFilterTextBoxChanged">
                    <img src="/assets/images/search.png" class="search-icon" alt="search">
                </div>
                <img src="/assets/images/excel_icon.png" width="20" v-on:click="onBtnExport()" alt="excel">

                <img src="/assets/images/pdf_icon.png" width="20" @click="exportAsPDF" alt="export pdf"
                    data-toggle="tooltip" data-placement="top" title="pdf" data-original-title="PDF">

                <img src="/assets/images/copy_icon.png" width="20" @click="copySelectedRows" alt="copy"
                    data-toggle="tooltip" data-placement="top" title="copy" data-original-title="Copy">
            </div>
        </div>
        <ag-grid-vue class="ag-theme-quartz-dark" :gridOptions="gridOptions" :defaultColDef="defaultColDef"
            :columnDefs="columnDefs" :rowData="rowData" @grid-ready="onGridReady" :suppressExcelExport="true"
            :paginationPageSizeSelector="paginationPageSizeSelector" :headerHeight="headerHeight"
            :popupParent="popupParent"></ag-grid-vue>

    </div>
</template>
  
<script>
import { AgGridVue } from 'ag-grid-vue3';
import { onBeforeMount, reactive, ref, onMounted, computed, watch } from 'vue';
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
        const paginationPageSizeSelector = ref(null);
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
            gridApi.value.showLoadingOverlay();
            gridColumnApi.value = params.columnApi;
            paginationPageSizeSelector.value = [10, 20, 30, 40, 50, 100];
          /*   params.api.sizeColumnsToFit();  */
            
            // Pass gridApi to the parent component
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
      flex:1
        });

        const gridOptions = ref({
            pagination: true,
            paginationPageSize: 10, // Set the number of rows per page
            domLayout: 'autoHeight',
        });

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
        // Check if it's a date string and convert it to a Date object
        const dateObject = new Date(data);
        if (!isNaN(dateObject.getTime())) {
            // If it's a valid date, format it as MM-DD-YYYY
            return dateObject.toLocaleDateString('en-US', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
            });
        }

        // Check for complex HTML string and handle it
        const htmlElement = document.createElement('div');
        htmlElement.innerHTML = data;

        if (htmlElement.children.length > 0) {
            // If it's an HTML element, convert to text
            return htmlElement.textContent || htmlElement.innerText || '';
        }

        // If it's a regular string, remove HTML tags
        return data.replace(/<[^>]*>?/gm, '');
    } else if (data instanceof Date) {
        // Format date as MM-DD-YYYY if it's a valid Date object
        return data.toLocaleDateString('en-US', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
        });
    } else if (typeof data === 'object' && data !== null) {
        if ('href' in data) {
            // Handle case where data is an object with an 'href' property
            return sanitizeDataForPDFExport(data.href);
        }
    }

    // Handle other cases
    return data ? data.toString() : '';
};




        function exportAsPDF() {
    const doc = new jsPDF();

    // Extracting column headers
    const columns = props.columnDefs.map((columnDef) => columnDef.headerName);
    
    // Extracting row data in a format compatible with autoTable
    const rows = props.rowData.map((row) => {
        // Generating an array containing values for each column in the row
        const rowDataArray = props.columnDefs.map((columnDef) => {
            const col = columnDef.field; // Assuming there's a 'field' property in each column definition
            switch (col) {
                case 'Sr. No.':
                return index + 1;
                // Add more cases for specific columns as needed
                default:
                    // For other columns, sanitize the data and return
                    return sanitizeDataForPDFExport(row[col]);
            }
        });

        return rowDataArray;
    });

    doc.autoTable({
        head: [columns],
        body: rows,
    });

    doc.save('Renova_Healthcare.pdf');
}
        const onFilterTextBoxChanged = () => {
            if (gridApi.value) {
                gridApi.value.setGridOption(
                    'quickFilterText',
                    filterText.value
                );
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
    /* Border color for regular cells */
    --ag-borders-critical: solid 1px rgb(255, 255, 255);
    --ag-borders-secondary: solid 1px rgb(255, 255, 255);
    --ag-secondary-border-color: rgb(255, 255, 255);
    --ag-row-border-style: solid 1px rgb(255, 255, 255);
    /* Border color for rows */
    --ag-row-border-color: blue;
    --ag-cell-horizontal-border: solid 1px #ddd;
    /* Border color for cells in the header */
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

/* Borders for regular cells */
.ag-cell {
    border: var(--ag-borders);
}

/* Borders for header cells */
.ag-header-cell {
    border: var(--ag-borders);
}

.ag-cell-value {
  line-height: 20px !important;
  word-break: normal; /* prevent words from breaking */
  padding-top: 5px; /* space top */
  padding-bottom: 5px; /* space bottom */
}

/* Borders for rows */
.ag-row {
    border: var(--ag-row-border-style) var(--ag-row-border-color);
}

/* Borders for cells in the header */
.ag-header-cell-label {
    border-bottom: var(--ag-cell-horizontal-border);
}

/* Borders for column headers */
.ag-header-row {
    border-bottom: var(--ag-cell-horizontal-border);
}

/* Optional: Borders for the entire table */
.ag-root-wrapper {
    border: var(--ag-borders);
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
	/* To create an oval shape, use a large value for border-radius */
	overflow: hidden;
	width: 200px;
	/* Adjust width as needed */
}

.oval-search-container {
	position: relative;
	display: inline-block;
	/*  border: 1px solid #ccc; */
	/* Adding a visible border */
	/* border-radius: 20px; */
	/* Adjust border-radius for a rounded shape */
	/* width: 200px; */
	/* Adjust width as needed */
	margin-right: 10px;
	/* Adjust margin between the search box and icons */
}

.oval-search-container input[type="text"] {
	width: calc(100% - 0px);
	/* Adjust the input width considering the icon */
	/*  border: none; */
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
	/* Adjust icon size as needed */
	height: auto;
}

/* Align the export icons properly */
.ml-auto img {
	margin-right: 5px;
	/* Adjust margin between the export icons */
}
</style>
  