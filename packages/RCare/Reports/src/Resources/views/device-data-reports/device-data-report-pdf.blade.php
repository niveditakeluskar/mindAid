 <style>
     .highcharts-figure,
     .highcharts-data-table table {
         min-width: 360px;
         max-width: 800px;
         margin: 1em auto;
     }
 </style>
 <?php $i = 1; ?>
 <div class="breadcrusmb">
     <div class="row">
         <div class="col-md-11">
             <h4 class="card-title mb-3">Device Data Report print</h4>
         </div>
     </div>
     <!-- ss -->
 </div>
 <div class="separator-breadcrumb border-top"></div>
 <div class="row mb-4">
     <div class="col-md-12 mb-4">
         <div class="card text-left">

             <div class="card-body">

                 <div class="table-responsive">
                     <table id="Activities-list" class="display table table-striped table-bordered" style="width:100%">
                         <thead>
                             <tr>
                                 <th width="">Sr No.</th>
                                 <th width="">Timestamp</th>
                                 <th width="">BP</th>
                                 <th width="">HR</th>
                                 <th width="">WT</th>
                                 <th width="">O2</th>
                                 <th width="">Temp</th>
                                 <th width="">Gulcose</th>
                                 <th width="">FEV1</th>
                                 <th width="">PEF</th>
                             </tr>
                         </thead>
                         <tbody>
                             @foreach ($data as $rec)
                             <tr>
                                 <td>{{$i++}}</td>
                                 <td>{{empty($rec->effdatetime )?'':date("m-d-Y  h:i:s A", strtotime($rec->effdatetime)) }}</td>
                                 <td>{{ $rec->systolic_qty}} / {{$rec->diastolic_qty }}</td>
                                 <td>{{ $rec->resting_heartrate }}</td>
                                 <td>{{ $rec->weight }}</td>
                                 <td>{{ $rec->oxy_qty }}</td>
                                 <td>{{ $rec->bodytemp }}</td>
                                 <td>{{ $rec->value }}</td>
                                 <td>{{ $rec->fev_value }}</td>
                                 <td>{{ $rec->pef_value }}</td>
                             </tr>
                             @endforeach
                         </tbody>
                         <tbody>
                         </tbody>
                     </table>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <div class="col" id="bp" style="display:none">
     <div class="card">
         <div class="card-body device_box">
             <div id="bpcontainer" style="height: 400px; width: 100%;"></div>
         </div>
     </div>
 </div>
 <br>
 <div class="col" id="hart" style="display:none">
     <div class="card">
         <div class="card-body device_box">
             <div id="hartcontainer" style="height: 400px; width: 100%;"></div>
         </div>
     </div>
 </div>
 <br>
 <div class="col" id="ox" style="display:none">
     <div class="card">
         <div class="card-body device_box">
             <div id="container" style="height: 400px; width: 100%;"></div>
         </div>
     </div>
 </div>
 <br>
 <div class="col" id="wt" style="display:none">
     <div class="card">
         <div class="card-body device_box">
             <figure class="highcharts-figure">
                 <div id="wtcontainer" style="height: 400px; width: 100%;"></div>
             </figure>
         </div>
     </div>
 </div>
 <div class="col" id="temp" style="display:none">
     <div class="card">
         <div class="card-body device_box">
             <div id="tempcontainer" style="height: 400px; width: 100%;"></div>
         </div>
     </div>
 </div>
 <div class="col" id="gulcose" style="display:none">
     <div class="card">
         <div class="card-body device_box">
             <div id="gulcontainer" style="height: 400px; width: 100%;"></div>
         </div>
     </div>
 </div>
 <div class="col" id="spirometer" style="display:none">
     <div class="card">
         <div class="card-body device_box">
             <div id="spirocontainer" style="height: 400px; width: 100%;"></div>
         </div>
     </div>
 </div>

 <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
 <script src="{{asset('assets/js/datatables.script.js')}}"></script>
 <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
 <script src="https://code.highcharts.com/highcharts.js"></script>
 <script src="https://code.highcharts.com/modules/exporting.js"></script>
 <script src="https://code.highcharts.com/modules/export-data.js"></script>
 <script type="text/javascript">
     function getHartChartAjax(patient_id, month) {
         $.ajax({
             url: '/reports/graphreadinghart/' + patient_id + '/' + month + '/graphchart',
             type: 'GET',
             async: true,
             dataType: "json",
             success: function(data) {
                 var ta = JSON.stringify(data.uniArray);

                 if (ta != '[]') {
                     $("#hart").show();
                     getChartOnclick(data, 'hartcontainer');
                 } else {
                     $("#hart").hide();
                 }


             }
         });
     }

     function getBPChartAjax(patient_id, month) {
         $.ajax({
             url: '/reports/graphreadingbp/' + patient_id + '/' + month + '/graphchart',
             type: 'GET',
             async: true,
             dataType: "json",
             success: function(data) {
                 var ta = JSON.stringify(data.uniArray);
                 if (ta != '[]') {
                     $("#bp").show();
                     getChartOnclick(data, 'bpcontainer');
                 } else {
                     $("#bp").hide();
                 }

             }
         });
     }

     function getWtChartAjax(patient_id, month) {
         $.ajax({
             url: '/reports/graphreadingwt/' + patient_id + '/' + month + '/graphchart',
             type: 'GET',
             async: true,
             dataType: "json",
             success: function(data) {
                 var ta = JSON.stringify(data.uniArray);
                 if (ta != '[]') {
                     $("#wt").show();
                     getChartOnclick(data, 'wtcontainer');
                 } else {
                     $("#wt").hide();
                 }

             }
         });
     }

     function getTempChartAjax(patient_id, month) {
         $.ajax({
             url: '/reports/graphreadingtemp/' + patient_id + '/' + month + '/graphchart',
             type: 'GET',
             async: true,
             dataType: "json",
             success: function(data) {
                 var ta = JSON.stringify(data.uniArray);
                 if (ta != '[]') {
                     $("#temp").show();
                     getChartOnclick(data, 'tempcontainer');
                 } else {
                     $("#temp").hide();
                 }

             }
         });
     }

     function getGulChartAjax(patient_id, month) {
         $.ajax({
             url: '/reports/graphreadinggul/' + patient_id + '/' + month + '/graphchart',
             type: 'GET',
             async: true,
             dataType: "json",
             success: function(data) {
                 var ta = JSON.stringify(data.uniArray);
                 if (ta != '[]') {
                     $("#gulcose").show();
                     getChartOnclick(data, 'gulcontainer');
                 } else {
                     $("#gulcose").hide();
                 }

             }
         });
     }

     function getSpiroChartAjax(patient_id, month) {
         $.ajax({
             url: '/reports/graphreadingspiro/' + patient_id + '/' + month + '/graphchart',
             type: 'GET',
             async: true,
             dataType: "json",
             success: function(data) {
                 var ta = JSON.stringify(data.uniArray);
                 if (ta != '[]') {
                     $("#spirometer").show();
                     getChartOnclick(data, 'spirocontainer');
                 } else {
                     $("#spirometer").hide();
                 }

             }
         });
     }

     function getPatientDetails(patient_id) {
         /* $.ajax({
              url :'/reports/patientdetails/'+ patient_id +'/grtpatient',
              type: 'GET',
              async: true,
              dataType: "json",
                  success: function (data) {
                      var ta = JSON.stringify(data.uniArray);
                      if(ta != '[]'){
                          $("#spirometer").show();
                          getChartOnclick(data,'spirocontainer');
                      }else{
                          $("#spirometer").hide();
                      }

                  }
          });*/
     }


     function getChartOnclick(data, id) {
         alert(JSON.stringify(data) + 'getChartOnclick');
         var arraydate = JSON.stringify(data.uniArray);
         var patientarraydatetime = JSON.parse(arraydate);
         var arrayreading = JSON.stringify(data.arrayreading);
         var reading = JSON.parse(arrayreading);
         var label = JSON.stringify(data.label);
         var arrayreading1 = JSON.stringify(data.arrayreading1);
         var reading1 = JSON.parse(arrayreading1);
         var min_threshold = JSON.stringify(data.min_threshold_array);
         var min_threshold_array = JSON.parse(min_threshold);
         var max_threshold = JSON.stringify(data.max_threshold_array);
         var max_threshold_array = JSON.parse(max_threshold);
         var label1 = JSON.stringify(data.label1);
         // var min=140;
         // var max=160;  
         //alert(min_threshold_array.slice(1));
         //alert(max_threshold_array.slice(1));
         var title = $('.fc-center').html();
         //var date = arraydate.replace(/['"]+/g, ''); //"[apple,orange,pear]"
         // var patientreading = reading.replace(/['"]+/g, '');//[50,100];
         //alert(patientarraydatetime);
         Highcharts.chart(id, {

             // yAxis: {
             //   title: {
             //     text: 'Readings'
             //   },
             // },

             xAxis: {

                 labels: {
                     rotation: -33,
                 },
                 tickInterval: 1,
                 categories: patientarraydatetime
             },
             yAxis: {
                 //softMax: max,
                 allowDecimals: true,
                 // min: 0,
                 title: {
                     text: 'Readings'
                 },
                 plotLines: [{
                     value: min_threshold_array,
                     width: 1,
                     color: 'rgba(204,0,0,0.75)'
                 }, {
                     value: max_threshold_array,
                     width: 1,
                     color: 'rgba(204,0,0,0.75)'
                 }]
             },
             title: {
                 text: title
             },
             legend: {
                 align: 'left',
                 verticalAlign: 'bottom',
                 borderWidth: 0
             },

             tooltip: {
                 shared: true,
                 crosshairs: true
             },

             plotOptions: {
                 series: {
                     cursor: 'pointer',
                     className: 'popup-on-click',
                     marker: {
                         lineWidth: 1
                     }
                 }
             },
             series: [{
                     name: label,
                     data: reading
                 },
                 {
                     name: label1,
                     data: reading1
                 }
             ],

             responsive: {
                 rules: [{
                     condition: {
                         //maxWidth: 500
                     },
                     chartOptions: {
                         legend: {
                             layout: 'horizontal',
                             align: 'center',
                             verticalAlign: 'bottom'
                         }
                     }
                 }]
             }

         });
     }


     $(document).ready(function() {
         getSpiroChartAjax('1248555320', '2021-09');
     });
     $('#ddserch').click(function() {
         var patient = $('#patient').val();
         var fromdate = $('#monthly').val();
         if (patient == '') {

         } else {
             getChartAjax(patient, fromdate);
             getHartChartAjax(patient, fromdate);
             getBPChartAjax(patient, fromdate);
             getWtChartAjax(patient, fromdate);
             getTempChartAjax(patient, fromdate);
             getGulChartAjax(patient, fromdate);
             getSpiroChartAjax(patient, fromdate);
         }

     });
 </script>