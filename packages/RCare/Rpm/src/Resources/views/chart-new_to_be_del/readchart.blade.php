<body>
	<div class="container"></div>
</body>

<script src="https://code.highcharts.com/highcharts.js"></script>

<script type="text/javascript">
	var Observation_Oxymeter = <?php echo "Hello"//json_encode($Observation_Oxymeter)  ?>;
	Highcharts.chart('container',{
		title: {
			text: 'Oxygen Reading'
		},

		subtitle: {
			text: 'Oxy'
		},

		xAxis: {
			categories: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec']
		},		

		yAxis: {
			title: {
				text: 'Patient'
			}
		},

		legend: {
			layout: 'vertical',
			align: 'right',
			vertical: 'middle'
		},

		plotOptions: {
			series: {
				allowPointSelect: true
			}
		},

		series: [
			{
				name: 'ABC'
				data: Observation_Oxymeter
			}
		],

		responsive: {
			rules: [
				{
					condition:{
						maxWidth: 500
					},

					chartOptions: {
						legend: {
						layout: 'horizontal',
						align: 'center',
						verticalAlign: 'bottom'
						}
					}
				}
			]
		}

	});
</script>