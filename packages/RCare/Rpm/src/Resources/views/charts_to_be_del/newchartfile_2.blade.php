<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-header">
            Data REading Chart
            </div>  
            <div class="card-body">
                <div>
                    @include('Rpm::charts.vitals-tab')
                </div> 
                   
                @include('Theme::layouts.flash-message')
                
                
            </div>
        </div>
    </div>
</div>

<div id="container"></div>


<script src="https://code.highcharts.com/highcharts.js"></script>

<script type="text/javascript">

// var device = $('input[name="deviceid"]').val();
// alert(device);

var arraydtetime = '<?php echo json_encode($uniArray) ?>';
alert(arraydtetime2);
var patientarraydatetime = JSON.parse(arraydtetime2);

var reading1 = '<?php echo json_encode($arrayreading1) ?>';
var patientreading1 = JSON.parse(reading1);

var reading2 = '<?php echo json_encode($arrayreading2) ?>';
var patientreading2 = JSON.parse(reading2);

// var reading3 = '<?php echo json_encode($arrayreading3) ?>';
// var patientreading3 = JSON.parse(reading3);

var label1 = '<?php echo json_encode($label1) ?>';
var label11 = JSON.parse(label1);

var label2 = '<?php echo json_encode($label2) ?>';
var label22 = JSON.parse(label2);

// var label3 = '<?php echo json_encode($label3) ?>';
// var label33 = JSON.parse(label3);

// if(patientreadingOxy){
//     var patientarraydatetime = JSON.parse(arraydtetime1);
// }else if(patientreadingHrt){
//     var patientarraydatetime = JSON.parse(arraydtetime2);
// }
console.log(patientarraydatetime);

var m_names = ['January', 'February', 'March', 
               'April', 'May', 'June', 'July', 
               'August', 'September', 'October', 'November', 'December'];

const d = new Date();
var month = m_names[d.getMonth()];

document.addEventListener('DOMContentLoaded', function () {
        const chart = Highcharts.chart('container', {

            chart: {
                scrollablePlotArea: {
      			minWidth: 700
    			}
            },
            title: {
                text: 'Reading Chart'
            },
            xAxis: {
            	title: {
			      text: 'Date-Time'
			    },
			    // min:Date.UTC(2021, 0, 0),
			    // max:Date.UTC(2021, 12, 1),
			    // type: 'datetime',
			    // tickInterval: 24 * 3600 * 1000*30,
			    categories: patientarraydatetime,
                // categories: ['2021-06-18 12:00:00','2021-06-18 15:00:00','2021-06-18 18:00:00','2021-06-18 21:00:00','2021-06-18 23:00:00'],//7*24*3600*1000,//24 * 3600 * 1000*30,
                labels: {
      				align: 'left',
				      x: 3,
				      y: -3
    			}
            },
            yAxis: {
                title: {
                    text: 'Readings'
                }
            },
             series: [{
                name: label11,
                data: patientreading1
             }, 
            {
                name: label22,
                data: patientreading2
            }

            ]
        });
    });
</script>