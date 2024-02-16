<body>
	<div id="container"></div>
</body>

<script src="https://code.highcharts.com/highcharts.js"></script>

<script type="text/javascript">

var arraydtetime = '<?php echo json_encode($uniArray) ?>';
var patientarraydatetime = JSON.parse(arraydtetime);

var readingOxy = '<?php echo json_encode($arrayreading1) ?>';
var patientreading1 = JSON.parse(readingOxy);


var readingThreshold = '<?php echo json_encode($arrayreading2) ?>';
var patientthreshold = JSON.parse(readingThreshold);

alert(patientthreshold); 

var label1 = '<?php echo json_encode($label1) ?>';
var label11 = JSON.parse(label1);

var label2 = '<?php echo json_encode($label2) ?>';
var label22 = JSON.parse(label2);


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
			    categories: patientarraydatetime,                
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
             }
            ]
        });
    });
</script>