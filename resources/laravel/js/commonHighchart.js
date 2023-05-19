try {

window.Highcharts = require('highcharts');
require('highcharts/modules/exporting')(Highcharts);
require('highcharts-export-data')(Highcharts);
//require('highcharts/modules/exporting.js.map')(Highcharts);
//window.exporting=require('highcharts/modules/exporting');
require('highcharts-offline-exporting')(Highcharts);
require('highcharts-series-label')(Highcharts);

} catch (e) {}