<body>
	<div id="container"></div>
</body>

<script src="https://code.highcharts.com/highcharts.js"></script>

<script type="text/javascript">

var arraydtetime2 = '<?php echo json_encode($uniArray) ?>';

alert(arraydtetime2);
var patientarraydatetime = JSON.parse(arraydtetime2);

var readingSys = '<?php echo json_encode($arrayreadingSys) ?>';
var patientreadingSys = JSON.parse(readingSys);

var readingDys = '<?php echo json_encode($arrayreadingDys) ?>';
var patientreadingDys = JSON.parse(readingDys);

var readingHrt = '<?php echo json_encode($arrayreadingHrt) ?>';
var patientreadingHrt = JSON.parse(readingHrt);

console.log(patientarraydatetime);

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
                name: 'Systolic',
                data: patientreadingSys
             }, 
            {
                name: 'Diastolic',
                data: patientreadingDys
            }, 
            {
                name: 'Heart-rate',
                data: patientreadingHrt
            }
            ]
        });
    });
</script>