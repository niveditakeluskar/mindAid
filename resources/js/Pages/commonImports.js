import { ref, onMounted, computed, watch } from 'vue';
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net';
import 'jszip/dist/jszip.js';
import 'pdfmake/build/pdfmake.js';
import { vfs } from 'pdfmake/build/pdfmake';
import Buttons from 'datatables.net-buttons';
import 'datatables.net-dt/css/jquery.dataTables.min.css';
import 'datatables.net-plugins/api/processing().mjs';
import 'datatables.net-buttons/js/dataTables.buttons.min.js';
import 'datatables.net-buttons/js/buttons.html5.min.js';
import 'datatables.net-buttons/js/buttons.print.min.js';
import 'datatables.net-buttons/js/buttons.colVis.min.js';


// Load the fonts dynamically using URLs
const fonts = {
  Roboto: {
    normal: '/fonts/Roboto-Regular.ttf',
    bold: '/fonts/Roboto-Medium.ttf',
    italics: '/fonts/Roboto-Italic.ttf',
    bolditalics: '/fonts/Roboto-MediumItalic.ttf',
  },
};

// Set the font data
pdfMake.vfs = vfs;
pdfMake.fonts = fonts;

DataTable.use(DataTablesCore);
DataTable.use(Buttons);

export {
  ref,
  onMounted,
  computed,
  watch,
  DataTable,
  // Add other common imports if needed
};
