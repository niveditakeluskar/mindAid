<!DOCTYPE html>
<html>
<head>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<!-- optional -->
<script src="http://code.highcharts.com/modules/offline-exporting.js"></script>
<script src="http://code.highcharts.com/modules/export-data.js"></script>

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

<style type="text/css">

body{
    font-family:verdana;    
}
table, th {
 /* border: 0px solid black; */
 text-align:justify ;
 /*text-align:left;*/
 /*padding-left:3px;*/ 
}
table,td{
vertical-align: middle;
 
text-align:justify;
/* text-align:left; */ 
}

/* table, tr{ 
    margin-right: 10px; 
} */
/* 
table, th, td {
  border: 1px solid black;
} */
#cpecialisttbl {
  /*font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;*/
  border-collapse: collapse;
  width: 100%;
}

#cpecialisttbl td, #cpecialisttbl th {
  border: 1px solid #ddd;
   text-align: left;
  padding: 6px;
}

#cpecialisttbl tr:nth-child(even){background-color: #f2f2f2;}

#cpecialisttbl tr:hover {background-color: #ddd;}

#cpecialisttbl th {
 padding-top:2px;
  padding-bottom: 2px;
  text-align: center;
  background-color: #1474a0;
  color: white;

}
b{
    color:#000033;

}
h4
{
   background-color:#cdebf9;
   color:#000033;
   padding-top: 8px;
    padding-bottom: 8px;
    padding-left: 7px;
    /*width: 330px;*/
}
h3
{
   padding-top: 5px;
    padding-bottom: 5px;
    padding-left: 7px;
    color:white;
}
#cpid
{
    float: right;
    color: white;
    font-size: 36px;
    font-family: -webkit-pictograph;   
    margin-top: -11px;
    margin-right: 10px;
}
.header {
    top: 0px;
}
/*pie chart*/
.pie-chart {
            width: 600px;
            height: 400px;
            margin: 0 auto;
}
.text-center{
    text-align: center;
}
</style>
 <script src="https://www.google.com/jsapi"></script>
    <title>CARE PLAN</title>
</head>
<body>
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <input type="hidden" name="hidden_id" id="hidden_id" value=""> <!-- $patient[0]->id -->
                <div class="card-body">             
                    <div class="form-row">
                        <div class="row">
                            <label class="col-md-2" id="ID11"><h4>Device Data Report:</h4></label>
                            <div class="col-md-10"id="ID12">
                            <table id="cpecialisttbl">
                                <thead>
                                    <tr>                            
                                        <th width="30%">Timestamp</th>
                                        <th width="20%">BP</th>
                                        <th width="20%">HR</th>
                                        <th width="15%">WT</th>
                                        <th width="15%">O2</th>
                                        <th width="20%">Temp</th>
                                        <th width="15%">Gulcose</th>
                                        <th width="20%">FEV1</th>  
                                        <th width="20%">PEF</th>    
                                    </tr>
                                </thead> 
                                <tbody>
                                    @foreach ($data as $rec)
                                    <tr>
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
                            </table>
                            <div>
                        </div>
                        <!-- graph1 -->
                        <div class="row">
                            <label  class="col-md-2"><h4>Graph [heartrate]:</h4></label>
                                <div class="col-md-10">
                                    {{-- @include('Rpm::chart-new.newchartfile_2_daily')   --}}
                                    <div id="container"></div>     
                                </div>                               
                        </div>
                        <div class="row">
                            <button class="btn btn-primary" id="export2pdf">Download</button>
                        </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>
<script type="text/javascript">
    window.onload = function() {
        
    };

  
  var chart = Highcharts.chart('container', {

    xAxis: {
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },

    series: [{
      data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]
    }],
    exporting: { 
        enabled: true
    }  

  });

  $("#export2pdf").click(function() {
     Highcharts.exportCharts([chart], {
        type: 'application/pdf'
    });
  });

</script>
  
</body>
</html>
