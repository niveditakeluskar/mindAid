<style type="text/css">

.highcharts-figure, .highcharts-data-table table {
  min-width: 360px; 
  max-width: 800px;
  margin: 1em auto;
}

.highcharts-data-table table {
  font-family: Verdana, sans-serif;
  border-collapse: collapse;
  border: 1px solid #EBEBEB;
  margin: 10px auto;
  text-align: center;
  width: 100%;
  max-width: 500px;
}
.highcharts-data-table caption {
  padding: 1em 0;
  font-size: 1.2em;
  color: #555;
}
.highcharts-data-table th {
  font-weight: 600;
  padding: 0.5em;
}
.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
  padding: 0.5em;
}
.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
  background: #f8f8f8;
}
.highcharts-data-table tr:hover {
  background: #f1f7ff;
}

</style>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<!-- <script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script> -->
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<figure class="highcharts-figure">
  <div id="container"></div>
  <p class="highcharts-description">
    Testing demo
  </p>
</figure>

<script type="text/javascript">
var m_names = ['January', 'February', 'March', 
               'April', 'May', 'June', 'July', 
               'August', 'September', 'October', 'November', 'December'];

  const d = new Date();
  var month = m_names[d.getMonth()];
  var labels123 = ['0', '54', '70', '80', '140', '180', '200', '240','300'];



Highcharts.addEvent(Highcharts.Point, 'click', function () {
  if (this.series.options.className.indexOf('popup-on-click') !== -1) {
    const chart = this.series.chart;
    const date = Highcharts.dateFormat('%A, %b %e, %Y', this.x);
    const text = `<b>${date}</b><br/>${this.y} ${this.series.name}`;

    const anchorX = this.plotX + this.series.xAxis.pos;
    const anchorY = this.plotY + this.series.yAxis.pos;
    const align = anchorX < chart.chartWidth - 200 ? 'left' : 'right';
    const x = align === 'left' ? anchorX + 10 : anchorX - 10;
    const y = anchorY - 30;
    if (!chart.sticky) {
      chart.sticky = chart.renderer
        .label(text, x, y, 'callout',  anchorX, anchorY)
        .attr({
          align,
          fill: 'rgba(0, 0, 0, 0.75)',
          padding: 10,
          zIndex: 7 // Above series, below tooltip
        })
        .css({
          color: 'white'
        })
        .on('click', function () {
          chart.sticky = chart.sticky.destroy();
        })
        .add();
    } else {
      chart.sticky
        .attr({ align, text })
        .animate({ anchorX, anchorY, x, y }, { duration: 250 });
    }
  }
});


Highcharts.chart('container', {

  chart: {
    scrollablePlotArea: {
      minWidth: 700
    }
  },
// data to fetch from controller
  // data: {
  //   csvURL: 'https://cdn.jsdelivr.net/gh/highcharts/highcharts@v7.0.0/samples/data/analytics.csv',
  //   beforeParse: function (csv) {
  //     return csv.replace(/\n\n/g, '\n');
  //   }
  // },

  title: {
    text: 'Demo'
  },

  subtitle: {
    text: 'Demo : Test'
  },

  xAxis: {
    title: {
      text: d
    },
     min:Date.UTC(2021, 0, 0),
     max:Date.UTC(2021, 12, 1),
    type: 'datetime',
    tickInterval: 24 * 3600 * 1000*30,//7 * 24*3600*1000, // one week
     tickWidth: 0,
     gridLineWidth: 1,
    labels: {
      align: 'left',
      x: 3,
      y: -3
    }
  },

  yAxis: [{ // left y axis
    // linkedTo: 0,
    // gridLineWidth: 0,
    // opposite: true,
    title: {
      text: 'Reading (in units)'
    },
     labels: {
            formatter: function() {
              return labels123[];
            }
        },
        showFirstLabel: false
  }],

  legend: {
    align: 'left',
    verticalAlign: 'top',
    borderWidth: 0
  },

  tooltip: {
    
    shared: true,
    crosshairs: true
  },

  plotOptions: {
    line: {
        dataLabels: {
            enabled: true
        }
    },
    series: {
      cursor: 'pointer',
      className: 'popup-on-click',
      marker: {
        lineWidth: 1
      }
    }
  },

   series: [{
    name: 'Reading',
    data: [25,50,75,100,125,150,175,200]
  }]
});






</script>