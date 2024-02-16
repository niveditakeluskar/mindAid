@extends('Theme::layouts_2.to-do-master')
@section('page-css')
<style>
    .highcharts-figure, .highcharts-data-table table {
        min-width: 360px; 
        max-width: 800px;
        margin: 1em auto;
    }
</style>
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Device Data Report</h4>
        </div>
    </div>
               
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <div class="form-row ">
                    <div class="col-md-3 form-group mb-3">
                        <label for="practicename">Practice Name</label>
                        @selectpracticespcp("practices", ["id" => "practices", "class" => "select2"])
                        <!-- selectpractices -->
                        <input type="hidden" id="hd_pid" name="hd_pid"> 
                        <input type="hidden" id="hd_p_name" name="hd_p_name"> 
                        <input type="hidden" id="hd_dob" name="hd_dob"> 
                        <input type="hidden" id="hd_mrn" name="hd_mrn"> 
                        <input type="hidden" id="hd_device" name="hd_device">
                       
                    </div>
                    <div class="col-md-3 form-group mb-3 patient-div" >
                        <label for="practicename">Patient Name</label>
                        @select("Patient", "patient_id", [], ["id" => "patient", "class" => "select2"])
                       
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="month">From Month & Year</label>
                        @month('fromdate',["id" => "monthly"]) 
                    </div>
                    <div class="col-md-2">
                            <button type="button" class="btn btn-primary mt-4" id="ddsearch">Search</button>
                    
                        <button type="button"  id="btn" class="btn btn-success mt-4">generate PDF</button>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
</div>
 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">

            <div class="card-body">
                @include('Theme::layouts.flash-message')                
                <div class="table-responsive" id="appendtable">
                   
                    
                </div>                
            </div>
        </div> 
    </div>


</div>

<div class="col hide-graph" id="bp" style="display:none">
    <div class="card">
        <div class="card-body device_box">
            <div id="bpcontainer" style="height: 400px; width: 100%;"></div>                    
        </div>                               
    </div>
</div>
<br>
<div class="col hide-graph" id="hart" style="display:none">
    <div class="card">
        <div class="card-body device_box">
            <div id="hartcontainer" style="height: 400px; width: 100%;"></div>                    
        </div>                               
    </div>
</div>
<br>
<div class="col hide-graph" id="ox" style="display:none">
    <div class="card">
        <div class="card-body device_box">
            <div id="container" style="height: 400px; width: 100%;"></div>                    
        </div>                               
    </div>
</div>
<br>
<div class="col hide-graph" id="wt" style="display:none">
    <div class="card">
        <div class="card-body device_box">
        
            <div id="wtcontainer" style="height: 400px; width: 100%;"></div>  
                        
        </div>                               
    </div>
</div>
<div class="col hide-graph" id="temp" style="display:none">
    <div class="card">
        <div class="card-body device_box">
            <div id="tempcontainer" style="height: 400px; width: 100%;"></div>                    
        </div>                               
    </div>
</div>
<div class="col hide-graph" id="gulcose" style="display:none">
    <div class="card">
        <div class="card-body device_box">
            <div id="gulcontainer" style="height: 400px; width: 100%;"></div>                    
        </div>                               
    </div>
</div>
<div class="col hide-graph" id="spirometer" style="display:none">
    <div class="card">
        <div class="card-body device_box">
            <div id="spirocontainer" style="height: 400px; width: 100%;"></div>                    
        </div>                               
    </div>
</div>
  

<div id="app">
</div>
@endsection

@section('page-js')

    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script> 
   <!--    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.debug.js" integrity="sha384-THVO/sM0mFD9h7dfSndI6TS0PgAGavwKvB5hAxRRvc0o9cPLohB0wb/PTA7LdUHs" crossorigin="anonymous"></script>  
 -->
 <!-- <script src="{{asset('assets/js/jspdf.min.js')}}"></script>
 <script src="{{asset('assets/js/highcharts.js')}}"></script>
  <script src="{{asset('assets/js/exporting.js')}}"></script>
  <script src="{{asset('assets/js/offline-exporting.js')}}"></script> -->

 <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script> 
  <script src="https://code.highcharts.com/8.1.2/highcharts.js"></script> 

  <script src="https://code.highcharts.com/modules/series-label.js"></script> 
 
<script src="https://code.highcharts.com/8.1.2/modules/exporting.js"></script>
 <script src="https://code.highcharts.com/8.1.2/modules/offline-exporting.js"></script> 
 -->


   <script src="{{asset('assets/js/jspdf.min.js')}}"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
   <script src="{{asset(mix('assets/js/laravel/commonHighchart.js'))}}"></script>
   <script type="text/javascript">
        
       var getDeviceDataList = function(devices=null,patient_id=null,month=null) {
        var i=1;
        var flag=0;
        var flag2=0;
        var myArr = devices.split(",");
        var columns =[{data: 'effdatetime',type: 'date-dd-mm-yyyy h:i:s', name: 'effdatetime',"render":function (value) {
                                    if (value === null) return "";
                                        return util.viewsDateFormatWithTime(value);
                                    }
                                }];
        

                               
        for(i=0;i<myArr.length;i++){
            if(myArr[i]==1){ 
              columns.push({data:null,
                    mRender: function(data, type, full, meta){
                    wt = full['weight'];
                        if(full['weight'] == null){
                            wt = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if (full['wt_alert_status'] == 1) {
                                return "<span style='color:red'>" + wt + '<i class="i-Danger" style="color:red"></i>' + "<span>";
                            } else {
                                return wt;
                            }
                        }
                    },
                     orderable: true
                });
            } 
            if(myArr[i]==2){ 
                columns.push({data:null,
                    mRender: function(data, type, full, meta){
                    ox = full['oxy_qty'];
                        if(full['oxy_qty'] == null){
                            ox = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if (full['ox_alert_status'] == 1) {
                                return "<span style='color:red'>" + ox + '<i class="i-Danger" style="color:red"></i>' + "<span>";
                            } else {
                                return ox;
                            }
                        }
                    },
                     orderable: true
                });
            }  
            if(myArr[i]==3){ 
              columns.push({data:null,
                    mRender: function(data, type, full, meta){
                    bp = full['systolic_qty']+' / '+full['diastolic_qty'];
                        if(full['systolic_qty'] == null){
                            bp = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                             if (full['bp_alert_status'] == 1) {
                                return  "<span style='color:red'>" + bp + '<i class="i-Danger" style="color:red"></i>' + "<span>";
                            } else {
                                return bp;
                            }
                        }
                     },
                     orderable: true
                });
            }  
           
            if(myArr[i]==2 || myArr[i]==3){ 
                if(flag2==0){
                     columns.push({data:null,
                    mRender: function(data, type, full, meta){
                    hr = full['resting_heartrate'];
                        if(full['resting_heartrate'] == null){
                            hr = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if (full['hr_alert_status'] == 1) {
                                return "<span style='color:red'>" + hr + '<i class="i-Danger" style="color:red"></i>' + "<span>";
                            } else {
                                return hr;
                            }
                        }
                     },
                     orderable: true
                });
                }
                flag2=1;
            }
            
            if(myArr[i]==4){ 
                columns.push({data:null,
                    mRender: function(data, type, full, meta){
                    temp = full['bodytemp'];
                        if(full['bodytemp'] == null){
                            temp = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if (full['temp_alert_status'] == 1) {
                                return "<span style='color:red'>" + temp + '<i class="i-Danger" style="color:red"></i>' + "<span>";
                            } else {
                                return temp;
                            }
                        }
                            
                     },
                     orderable: true
                });
            }  
            if(myArr[i]==5){ 
               columns.push({data:null,
                    mRender: function(data, type, full, meta){
                    fev = full['fev_value'];
                        if(full['fev_value'] == null){
                            fev = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if (full['spt_alert_status'] == 1) {
                                return "<span style='color:red'>" + fev + '<i class="i-Danger" style="color:red"></i>' + "<span>";
                            }else{
                                return fev;
                            }
                        }
                                 
                     },
                     orderable: true
                },
                {data:null,
                    mRender: function(data, type, full, meta){
                    pef = full['pef_value'];
                        if(full['pef_value'] == null){
                            pef = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if (full['spt_alert_status'] == 1) {
                                return "<span style='color:red'>" + pef + '<i class="i-Danger" style="color:red"></i>' + "<span>";
                            } else {
                                return pef;
                            }
                        }
                                          
                        
                     },
                     orderable: true
                });
            }  
            if(myArr[i]==6){ 
              columns.push( {data:null,
                    mRender: function(data, type, full, meta){
                    gul = full['value'];
                        if(full['value'] == null){
                            gul = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if (full['glc_alert_status'] == 1) {
                                return "<span style='color:red'>" + gul + '<i class="i-Danger" style="color:red"></i>' + "<span>";
                            } else {
                                return gul;
                            }
                        }
                     },
                     orderable: true
                });
            }  
        }



         var columns2 =[{data: 'effdatetime',type: 'date-dd-mm-yyyy h:i:s', name: 'effdatetime',"render":function (value) {
                                    if (value === null) return "";
                                        return util.viewsDateFormatWithTime(value);
                                    }
                                }];
        

                               
        for(i=0;i<myArr.length;i++){
            if(myArr[i]==1){ 
              columns2.push({data:null,
                    mRender: function(data, type, full, meta){
                    wt = full['weight'];
                        if(full['weight'] == null){
                            wt = '-';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if (full['wt_alert_status'] == 1) {
                                return  wt + "-r";
                            } else {
                                return wt;
                            }
                        }
                    },
                     orderable: true
                });
            } 
            if(myArr[i]==2){ 
                columns2.push({data:null,
                    mRender: function(data, type, full, meta){
                    ox = full['oxy_qty'];
                        if(full['oxy_qty'] == null){
                            ox = '-';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if (full['ox_alert_status'] == 1) {
                                return ox + "-r";
                            } else {
                                return ox;
                            }
                        }
                    },
                     orderable: true
                });
            }  
            if(myArr[i]==3){ 
              columns2.push({data:null,
                    mRender: function(data, type, full, meta){
                    bp = full['systolic_qty']+' / '+full['diastolic_qty'];
                        if(full['systolic_qty'] == null){
                            bp = '-';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                             if (full['bp_alert_status'] == 1) {
                                return  bp + "-r";
                            } else {
                                return bp;
                            }
                        }
                     },
                     orderable: true
                });
            }  
           
            if(myArr[i]==2 || myArr[i]==3){ 
                if(flag==0){
                     columns2.push({data:null,
                    mRender: function(data, type, full, meta){
                    hr = full['resting_heartrate'];
                        if(full['resting_heartrate'] == null){
                            hr = '-';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if (full['hr_alert_status'] == 1) {
                                return  hr + "-r";
                            } else {
                                return hr;
                            }
                        }
                     },
                     orderable: true
                });
                }
                flag=1;
            }
            
            if(myArr[i]==4){ 
                columns2.push({data:null,
                    mRender: function(data, type, full, meta){
                    temp = full['bodytemp'];
                        if(full['bodytemp'] == null){
                            temp = '-';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if (full['temp_alert_status'] == 1) {
                                return  temp + "-r";
                            } else {
                                return temp;
                            }
                        }
                            
                     },
                     orderable: true
                });
            }  
            if(myArr[i]==5){ 
               columns2.push({data:null,
                    mRender: function(data, type, full, meta){
                    fev = full['fev_value'];
                        if(full['fev_value'] == null){
                            fev = '-';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if (full['spt_alert_status'] == 1) {
                                return  fev + "-r";
                            }else{
                                return fev;
                            }
                        }
                                 
                     },
                     orderable: true
                },
                {data:null,
                    mRender: function(data, type, full, meta){
                    pef = full['pef_value'];
                        if(full['pef_value'] == null){
                            pef = '-';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if (full['spt_alert_status'] == 1) {
                                return  pef + "-r";
                            } else {
                                return pef;
                            }
                        }
                                          
                        
                     },
                     orderable: true
                });
            }  
            if(myArr[i]==6){ 
              columns2.push( {data:null,
                    mRender: function(data, type, full, meta){
                    gul = full['value'];
                        if(full['value'] == null){
                            gul = '-';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if (full['glc_alert_status'] == 1) {
                                return  gul + "-r";
                            } else {
                                return gul;
                            }
                        }
                     },
                     orderable: true
                });
            }  
        }

       
          
                var url = "/reports/device-data-report/search/"+patient_id+'/'+month+'/'+devices;
              var table1 = util.renderDataTable('Activities-list_1', url, columns, "{{ asset('') }}");
                           util.renderDataTable_pdf('Activities-list_2', url, columns2, "{{ asset('') }}");
             
         
        }

let hd_temp_pd=[];
let store=0;
let start=0;
        function getChartAjax(practice_id,patient_id, month) {
        $.ajax({
            url :'/reports/graphreadingnew/'+practice_id +'/'+ patient_id +'/'+ month +'/graphchart',
            type: 'GET',
            async: true,
            dataType: "json",
                success: function (data) {
                    var ta = JSON.stringify(data.uniArray);
                  // console.log(JSON.stringify(data))
                    if(data.p_name!=null){
                        $("#hd_p_name").val(JSON.parse(JSON.stringify(data.p_name)));
                        $("#hd_dob").val(JSON.parse(JSON.stringify(data.p_dob)));
                        
                    }
                    $("#hd_mrn").val(JSON.parse(JSON.stringify(data.mrn.practice_emr)));
                  // alert(ta)
                    if(ta != '[]'){
                        $("#ox").show();
                        hd_temp_pd.push(2);
                        util.getChartOnclick(data,'container',2);
                    }else{
                        $("#ox").hide();
                    }
                  //  getHartChartAjax(patient_id,month);
                   

                }
        });
    } 

    function getHartChartAjax(practice_id,patient_id, month) { 
        $.ajax({
            url :'/reports/graphreadinghart/'+practice_id +'/'+patient_id +'/'+ month +'/graphchart',
            type: 'GET',
            async: true,
            dataType: "json",
                success: function (data) {
                    var ta = JSON.stringify(data.uniArray);
                    if(data.p_name!=null){
                        $("#hd_p_name").val(JSON.parse(JSON.stringify(data.p_name)));
                        $("#hd_dob").val(JSON.parse(JSON.stringify(data.p_dob)));
                        
                    }
                    $("#hd_mrn").val(JSON.parse(JSON.stringify(data.mrn.practice_emr)));
                    if(ta != '[]'){
                        $("#hart").show();
                        hd_temp_pd.push(0);

                        util.getChartOnclick(data,'hartcontainer',0);
                    }else{
                        $("#hart").hide();
                    }
                //    getBPChartAjax(patient_id,month);
                   

                }
        });
    } 

    function getBPChartAjax(practice_id,patient_id, month) {
        $.ajax({
            url :'/reports/graphreadingbp/'+practice_id +'/'+patient_id +'/'+ month +'/graphchart',
            type: 'GET',
            async: true,
            dataType: "json",
                success: function (data) {
                    var ta = JSON.stringify(data.uniArray);
                    if(data.p_name!=null){
                        $("#hd_p_name").val(JSON.parse(JSON.stringify(data.p_name)));
                        $("#hd_dob").val(JSON.parse(JSON.stringify(data.p_dob)));
                        
                    }
                    $("#hd_mrn").val(JSON.parse(JSON.stringify(data.mrn.practice_emr)));
                    if(ta != '[]'){
                        $("#bp").show();
                        hd_temp_pd.push(3);
                        util.getChartOnclick(data,'bpcontainer',3);
                    }else{
                        

                        $("#bp").hide();
                    }
                //    getWtChartAjax(patient_id,month);
                  
                }
        });
    } 

    function getWtChartAjax(practice_id,patient_id, month) {
        $.ajax({
            url :'/reports/graphreadingwt/'+practice_id +'/'+patient_id +'/'+ month +'/graphchart',
            type: 'GET',
            async: true,
            dataType: "json",
                success: function (data) {
                    var ta = JSON.stringify(data.uniArray);
                   if(data.p_name!=null){
                        $("#hd_p_name").val(JSON.parse(JSON.stringify(data.p_name)));
                        $("#hd_dob").val(JSON.parse(JSON.stringify(data.p_dob)));
                        
                    }
                    $("#hd_mrn").val(JSON.parse(JSON.stringify(data.mrn.practice_emr)));
                    if(ta != '[]'){
                        $("#wt").show();
                        hd_temp_pd.push(1);
                        util.getChartOnclick(data,'wtcontainer',1);
                    }else{
                        $("#wt").hide();
                    }
                //    getTempChartAjax(patient_id,month);
                    
                }
        });
    } 

    function getTempChartAjax(practice_id,patient_id, month) {
        $.ajax({
            url :'/reports/graphreadingtemp/'+practice_id +'/'+ patient_id +'/'+ month +'/graphchart',
            type: 'GET',
            async: true,
            dataType: "json",
                success: function (data) {
                    var ta = JSON.stringify(data.uniArray);
                   if(data.p_name!=null){
                        $("#hd_p_name").val(JSON.parse(JSON.stringify(data.p_name)));
                        $("#hd_dob").val(JSON.parse(JSON.stringify(data.p_dob)));
                        
                    }
                    $("#hd_mrn").val(JSON.parse(JSON.stringify(data.mrn.practice_emr)));
                    if(ta != '[]'){
                        $("#temp").show();
                        hd_temp_pd.push(4);
                        util.getChartOnclick(data,'tempcontainer',4);
                    }else{
                        $("#temp").hide();
                    }
                   getGulChartAjax(patient_id,month);
                    
                }
        });
    } 

    function getGulChartAjax(practice_id,patient_id, month) {
        $.ajax({
            url :'/reports/graphreadinggul/'+practice_id +'/'+ patient_id +'/'+ month +'/graphchart',
            type: 'GET',
            async: true,
            dataType: "json",
                success: function (data) {
                    var ta = JSON.stringify(data.uniArray);
                    if(data.p_name!=null){
                        $("#hd_p_name").val(JSON.parse(JSON.stringify(data.p_name)));
                        $("#hd_dob").val(JSON.parse(JSON.stringify(data.p_dob)));
                        
                    }
                    $("#hd_mrn").val(JSON.parse(JSON.stringify(data.mrn.practice_emr)));
                    if(ta != '[]'){
                        $("#gulcose").show();
                        hd_temp_pd.push(6);
                        util.getChartOnclick(data,'gulcontainer',6);
                    }else{
                        $("#gulcose").hide();
                    }
                 //   getSpiroChartAjax(patient_id,month);
                    
                }
        });
    } 
    function getSpiroChartAjax(practice_id,patient_id, month) {
        $.ajax({
            url :'/reports/graphreadingspiro/'+practice_id +'/'+ patient_id +'/'+ month +'/graphchart',
            type: 'GET',
            async: true,
            dataType: "json",
                success: function (data) {
                    var ta = JSON.stringify(data.uniArray);
                  if(data.p_name!=null){
                        $("#hd_p_name").val(JSON.parse(JSON.stringify(data.p_name)));
                        $("#hd_dob").val(JSON.parse(JSON.stringify(data.p_dob)));
                        
                    }
                    $("#hd_mrn").val(JSON.parse(JSON.stringify(data.mrn.practice_emr)));
                    if(ta != '[]'){
                        $("#spirometer").show();
                        hd_temp_pd.push(5);
                         util.getSpirometerChartOnclick(data,'spirocontainer',5); 
                 
                    }else{
                        $("#spirometer").hide();
                    }
                
                 }
        });
    } 


    
  /*  function getChartOnclick(data,id,deviceid) { 
    //alert(JSON.stringify(data) +'getChartOnclick');
        var patientarraydatetime = JSON.parse(JSON.stringify(data.uniArray));
        var reading =JSON.parse( JSON.stringify(data.arrayreading));
        var label = JSON.parse(JSON.stringify(data.label));
        var reading1 = JSON.parse(JSON.stringify(data.arrayreading1));
        var min_threshold_array = JSON.parse(JSON.stringify(data.min_threshold_array));
        var max_threshold_array = JSON.parse(JSON.stringify(data.max_threshold_array));
        var label1 = JSON.parse(JSON.stringify(data.label1));
        var title_name =JSON.parse( JSON.stringify(data.title_name));
      
        var arrayreading_min =JSON.stringify(data.arrayreading_min);
        var reading_min = JSON.parse(arrayreading_min); 
        var arrayreading_max =JSON.stringify(data.arrayreading_max);
        var reading_max = JSON.parse(arrayreading_max);
        
        var arrayreading_min1 =JSON.stringify(data.arrayreading_min1);
        var reading_min1 = JSON.parse(arrayreading_min1); 
        var arrayreading_max1 =JSON.stringify(data.arrayreading_max1);
        var reading_max1 = JSON.parse(arrayreading_max1);
         
         if(deviceid==3){
            var subtitle2=" / <b>"+ label1+"</b>" +" - [Max:"+ reading_max1 +" ]/[Min: "+ reading_min1 +"]";
         }else{
            var subtitle2="";
         }
         var subtitle1= "<b>"+ label+"</b>" +" - [Max:"+ reading_max +" ]/[Min: "+ reading_min +"]" 
       
   Highcharts.chart(id,{
                    chart: {
                        type: 'spline',
                         events: {
                          load: function() {
                            this.series.forEach(function(s) {
                              s.update({
                                showInLegend: s.points.length
                              });
                            });
                          }
                        }
                    },
                    xAxis: {
                        categories: patientarraydatetime,
                        gridLineWidth: 1,
                        //tickWidth: 1
                    },
                    title: {
                        text: title_name
                    },
                    subtitle: {
                        text: subtitle1+" "+ subtitle2 
                    },
                    yAxis: {
                        title: {
                           text: 'Readings' //'Wind speed (m/s)'
                        },
                        min:0,
                        minorGridLineWidth: 0,
                        // gridLineWidth: 1,
                        // tickInterval: 1,
                        alternateGridColor: null, 
                        plotBands: [
                            { 
                                from:reading_min,
                                to:reading_max,
                                color: 'rgba(68, 170, 213, 0.1)',
                               /* label: {
                                    text: label,
                                    align: 'right',
                                    style: {
                                      color: '#606060',
                                    }

                                }*/
/*                            },
                            { 
                                from:reading_min1, 
                                to:reading_max1,
                                color: 'rgba(243, 248, 157, 1)',//'rgba(269, 70, 213, 0.1)',*/
                                /*label: {
                                    text: label1 ,
                                    align: 'right',
                                    style: {
                                        color: '#606060'
                                    }
                                }*/
                         /*   }
                        ]
                    },
                    
                    tooltip: {
                        shared: true,
                        crosshairs: true
                    },
                    plotOptions: {
                        spline: {
                            lineWidth: 4,
                            states: { 
                                hover: { 
                                  lineWidth: 5
                                }
                            },
                            marker: {
                                enabled: true
                            },
                           
                        } 
                    },
                    series: [{
                                name: label,
                                data: reading
                            }, 
                            {
                                name: label1,
                                data: reading1
                    }],
                    navigation: {
                        menuItemStyle: {  
                            fontSize: '10px'
                        }
                    }
                });
    }*/
        

// function getSpirometerChartOnclick(data,id){

//     var patientarraydatetime = JSON.parse(JSON.stringify(data.uniArray));
    
//    var arrayreading = JSON.stringify(data.arrayreading);
//    var reading=JSON.parse(arrayreading);
//    var label = JSON.stringify(data.label).replace(/\"/g, "");
//    var arrayreading1 = JSON.stringify(data.arrayreading1).replace(/\"/g, "");
//    var reading1 = JSON.parse(arrayreading1);
//    var label1 = JSON.stringify(data.label1).replace(/\"/g, "");

//     var arrayreading_min =JSON.stringify(data.arrayreading_min);
//     var reading_min = JSON.parse(arrayreading_min); 
//     var arrayreading_max =JSON.stringify(data.arrayreading_max);
//     var reading_max = JSON.parse(arrayreading_max);

//     var arrayreading_min1 =JSON.stringify(data.arrayreading_min1);
//     var reading_min1 = JSON.parse(arrayreading_min1); 
//     var arrayreading_max1 =JSON.stringify(data.arrayreading_max1);
//     var reading_max1 = JSON.parse(arrayreading_max1);
//     var chart_text =JSON.stringify(data.title_name);
//     var chart_name = JSON.parse(chart_text); 

//  Highcharts.chart(id, {
//     chart: {
//         zoomType: 'xy'
//     },
//     title: {
//         text: chart_name
//     },
//     subtitle: {
//         text: "<b>"+ label+"</b>" +" - [Max:"+ reading_max +" ]/[Min: "+ reading_min +"]" +" / <b>"+ label1+"</b>" +" - [Max:"+ reading_max1 +" ]/[Min: "+ reading_min1 +"]" 
//     },

    
//     xAxis: [{
//           categories: patientarraydatetime,
//         crosshair: true,
//         index:1,
//         gridLineWidth: 1,
//     }],
//     yAxis: [{ // Primary yAxis
             
//              title: {
//             text: '',
//             },
//     }, { // Secondary yAxis
//         gridLineWidth: 1,
//         title: {
//             text: label,
//             style: {
//                 color: Highcharts.getOptions().colors[0]
//             }
//         },
//         /*labels: {
//             format: label,
//             style: {
//                 color: Highcharts.getOptions().colors[0]
//             }
//         },*/
//         plotBands: [{ 
//               from:reading_min,
//               to:reading_max,
//               color: 'rgba(68, 170, 213, 0.1)',
              
//                label:{
//                         text: label ,
//                         align: 'right',
                        
//                         style: {
//                           color: '#606060',
                          
//                         },

//                     }
//         }]
//         // top:'50%',
//         // height:'50%'
//     }, { // Tertiary yAxis
//         gridLineWidth: 0,
//         title: {
//             text: label1 ,
 
//             style: {
//                 color: Highcharts.getOptions().colors[1]
//             }
//         },/*
//         labels: {
//             format: label1,
//             style: {
//                 color: Highcharts.getOptions().colors[1]
//             }
//         },*/
//         plotBands: [{ 
//               from: reading_min1,
//               to: reading_max1,
//               color:'rgba(243, 248, 157, 1)',
//               /*label: {
//                         text: label1 ,
//                         align: 'right',
//                         style: {
//                           color: '#606060',
//                         },

//                     }*/
//             }],
//         opposite: true,
//         // top:'50%',
//         // height:'50%'
//     }],
//     tooltip: {
//         shared: true
//     },
//     // legend: {
//     //     layout: 'vertical',
//     //     //align: 'left',
//     //    // x: 80,
//     //     verticalAlign: 'top',
//     //     //y: 55,
//     //     //floating: true,
//     //    // backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
//     // },
//     plotOptions: {
//     spline: {
//       lineWidth: 4,
//       states: {
//         hover: { 
//           lineWidth: 5
//         }
//       },
//       marker: {
//         enabled: true
//       },

//       // pointInterval: 3600000, // one hour
//       // pointStart: patientarraydatetime//Date.UTC(2018, 1, 13, 0, 0, 0)
//     } 
//     },
//     series: [{
//         name: label,
//         type: 'spline',
//         yAxis: 1, 
//         data: reading,
//         tooltip: {
//             valueSuffix: ' L/min'
//         }

//     }, {
//         name: label1,
//         type: 'spline',
//         yAxis: 2,
//         data: reading1, 
//         tooltip: {
//             valueSuffix: ' L'
//         }

//     }]
// });


// }





        $(document).ready(function() {                
            util.getToDoListData(0, {{getPageModuleName()}});
            util.getAssignPatientListData(0, 0);
            $(".patient-div").hide();
                   

            $("[name='practices']").on("change", function () {
                $(".patient-div").show(); 

                if($(this).val()==''){
                var practiceId = null;
                util.updatePatientListAssignedDevice(parseInt(practiceId), {{ getPageModuleName() }}, $("#patient"));
            }
            else
            {
                 util.updatePatientListAssignedDevice(parseInt($(this).val()), {{ getPageModuleName() }}, $("#patient"));
            }
            });
            
            /* 
            $("[name='patient_id']").on("change", function () {
                if($(this).val()==''){
                    //getPatientList();
                }
                else
                {
                    getDeviceDataList($(this).val());
                    getChartAjax($(this).val());
                }
            });*/

            function getMonth(date) {
            var month = date.getMonth() + 1;
            return month < 10 ? '0' + month : '' + month; // ('' + month) for string result
            }

            var c_month = (new Date().getMonth() + 1).toString().padStart(2, "0");
            var c_year = new Date().getFullYear();
            var current_MonthYear = c_year+'-'+c_month;
            var fromdate = $("#monthly").val(current_MonthYear);
            var practice_id = $("#practicess").val();
             if(window.location.href.indexOf('device-data-report/')>0)
                {
                    var getUrl=window.location;
                    var patient=getUrl.pathname.split('/')[3];

                    $("#hd_pid").val(patient);
                    $("#patient").val(patient);
                    
                  
                    var fromdate=$("#monthly").val();
                    //var fromdate="2021-09";
                    
                    if(patient!=''){
                        hd_temp_pd=[];
                        store=0;
                            $.ajax({
                            url :'/reports/getassigndevice/'+ patient ,
                            type: 'GET',
                            async: true,
                            dataType: "json",
                                success: function (data) {
                                   // alert("test"+data.patient_assign_deviceid)
                                    if(data.patient_assign_deviceid!=""){
                                        $('#hd_device').val(data.patient_assign_deviceid);
                                        vitaltable(data.patient_assign_deviceid,practice_id,patient,fromdate)
                                    }else{
                                        alert("No Data");
                                    }
                                    getDeviceDataList(data.patient_assign_deviceid,patient,fromdate);
                                }
                            });
                    }

                  
                 
                }

        });

        $('#ddsearch').click(function(){ 
            // $(".hide-graph").hide();
            // $('#appendtable').empty();
            var patient =$('#patient').val();
            var fromdate = $('#monthly').val();
            var practice_id = $('#practices').val();
            $('#hd_device').val('');
            $('#hd_pid').val(patient);
            if(practice_id==''){ 
                alert('select practice.');
            }else if(patient==''){
                alert('select patient.');
            }else if(fromdate==''){ 
                alert('select month.');
            }else{
                hd_temp_pd=[];
                store=0;
                    $.ajax({
                    url :'/reports/getassigndevice/'+ patient ,
                    type: 'GET',
                    async: true,
                    dataType: "json",
                        success: function (data) {
                            if(data.patient_assign_deviceid!=""){
                                $('#hd_device').val(data.patient_assign_deviceid);
                                vitaltable(data.patient_assign_deviceid,practice_id,patient,fromdate);
                            }else{
                                alert("No Data");
                            }
                            
                        }
                    });
               
            }
            
        }); 

        

    function vitaltable(devices,practice_id,patient,fromdate) {
        
        $('#appendtable').empty();
        
        $('#appendtable').append(' <table id="Activities-list_1" class="display table table-striped table-bordered" style="width:100%" ><thead id="vitaltableheader1"></thead><tbody id="vitalstablebody1"></tbody> </table>');
         // $('#vitaltableheader1').html('');
        
        $('#appendtable').append('<table id="Activities-list_2" class="display table table-striped table-bordered" style="width:100%;display:none" ><thead id="vitaltableheader2"></thead><tbody id="vitalstablebody2"></tbody> </table>');
        //$('#vitaltableheader2').html('');

        
        var i=1;
        var flag=0;
        var myArr = devices.split(",");
     
        //getPatientDetails(patient);         
               
        var  head="<tr><th>Timestamp</th>";
        for(i=0;i<myArr.length;i++){
            if(myArr[i]==1){ 
                head+="<th>WT</th>";
                getWtChartAjax(practice_id,patient,fromdate);
            } 
            if(myArr[i]==2){ 
                head+="<th>O2</th>";
                 getChartAjax(practice_id,patient,fromdate);
            }  
            if(myArr[i]==3){ 
               head+="<th>BP</th>";
               getBPChartAjax(practice_id,patient,fromdate);
            }  
           
            if(myArr[i]==2 || myArr[i]==3){ 
               if(flag==0){ 
                    head+="<th>HR</th>";
                     getHartChartAjax(practice_id,patient,fromdate);
                }
                flag=1;
            }
            
            if(myArr[i]==4){ 
                head+="<th>Temp</th>";
                getTempChartAjax(practice_id,patient,fromdate);
            }  
            if(myArr[i]==5){ 
                head+="<th>FEV1</th><th>PEF</th>";
                getSpiroChartAjax(practice_id,patient,fromdate);
            }  
            if(myArr[i]==6){ 
               head+="<th>Glucose</th>";
               getGulChartAjax(practice_id,patient,fromdate);
            }  
        }
        head+="</tr>";
       
        $('#vitaltableheader1').html(head);
        $('#vitaltableheader2').html(head);

        getDeviceDataList(devices,patient,fromdate);
       
        
        
    }


        

  /*  const toDataURL = url => fetch(url)
    .then(response => response.blob())
    .then(blob => new Promise((resolve, reject) => {
        const reader = new FileReader()
        reader.onloadend = () => resolve(reader.result)
        reader.onerror = reject
        reader.readAsDataURL(blob)
    }));

  var specialElementHandlers = {
                    '#editor': function(element, renderer) { return true; }
                };
*/



$('#btn').click(function() {
    var chk_practices = $('#practices').val();
    
    if(window.location.href.indexOf('device-data-report/')>0)
    {   var chk_patient   = $('#hd_pid').val();
        var chk_prac=1;
    }else{
        var chk_patient   = $('#patient').val();
        var chk_prac=chk_practices;
    }
  
    if(chk_prac!="" && chk_patient!=""){
        var charts = Highcharts.charts,
        doc = new jsPDF('p','pt', 'a4', true),

        pageHeight = doc.internal.pageSize.height,
        counter = 0,
        promises = [],
        yDocPos = 0,
        k = 0,
        i,
        j;
        // exportUrl = 'https://export.highcharts.com/',
        //doc = new jsPDF(),
        //imgContainer = $('.main-content-wrap'),
        // pageHeight = doc.internal.pageSize.getHeight(),
        // width = doc.internal.pageSize.width,
        // ajaxCalls = [],
        // chart,
        // imgUrl,
       

        var patient_ids= $("#hd_pid").val();
        var hd_p_name  = $("#hd_p_name").val();
        var hd_dob     = $("#hd_dob").val();
        var hd_mrn     = $("#hd_mrn").val();
        var date_month = $('#monthly').val();
        var get_m_year = date_month.substring(0, 4);
        var get_m_month = date_month.slice(-2);

        var last_date = new Date(get_m_year, get_m_month , 0);
        var last_dt=last_date.getDate();
        var cur_month = (new Date().getMonth() + 1);
        var cur_year = new Date().getFullYear();
        var cur_day =new Date().getDate();
        var cur_MonthYear = cur_month+'-'+cur_day+'-'+cur_year;
        var last = new Date(cur_year, cur_month , 0);
        var lastDay =last.getDate();

        var dt = new Date();
        var h =  dt.getHours(), m = dt.getMinutes();
        var mm; (m<10)?  mm="0"+m:mm=m;
        var hh; (h<10)?  hh="0"+h:hh=h;
        
        var times = (hh > 12) ? (hh-12 + ':' + mm +' PM') : (hh + ':' + mm +' AM');
               
        // var img = new Image();
        // img.src =window.location.origin+"/assets/images/logo.png";
        // alert(window.location.origin);

        var table = tableToJson($('#Activities-list_2').get(0))
        //  var doc = new jsPDF('p','pt', 'a4', true);
            doc.cellInitialize();
          
            doc.setDrawColor(0);
            doc.setFillColor(39, 168, 222);
            doc.rect(10, 10, 575, 45, 'F');
            
            doc.setTextColor(255,255,255);
            doc.setFont("helvetica");
            doc.setFontType("bold");
            doc.text(236, 37, 'Device Data Report');
           // doc.addImage(img, 'PNG', 20, 21);
            
            doc.setTextColor(0);
            doc.setFontSize(12)
            doc.setFontType('normal');
            doc.text(10, 80, hd_p_name);
            doc.setFontSize(10)
            doc.text(10, 93, 'DOB: '+hd_dob);
            doc.text(100, 93, 'MRN: '+hd_mrn);

            doc.text(10, 104, 'Generated on: '+cur_MonthYear +" " +times);
            if(window.location.href.indexOf('device-data-report/')>0)
            {         
                doc.text(10, 116, 'Data from : '+cur_month+"-01-"+cur_year +" to "+ cur_month+"-"+lastDay+'-'+cur_year);
            }else{
                doc.text(10, 116, 'Data from : '+get_m_month+"-01-"+get_m_year +" to "+ get_m_month+"-"+last_dt+'-'+get_m_year);
           
            }
            
            doc.setTextColor(0);
            var z=0;
           // var imgs = new Image(); 
        // imgs.src =window.location.origin+"/assets/images/i-danger.png";
             var toppdf=117;
              var tblcolumn=0;
            $.each(table, function (i, row){
                //console.debug(row);

                z=i;
                var l=0;
                 toppdf+=20;
               
                
                $.each(row, function (j, cells){
                   
                   
               if(j=="timestamp"){
                     tblcolumn=135;
                 }else{
                    tblcolumn+=40;
                 }
                 console.log(tblcolumn+"="+j);
                    doc.setFontSize(12);
                    if(l==0){
                        var cell_width=116;
                    }else{
                        var cell_width=51;
                    }
                    if(i==0){
                        doc.setTextColor(255,255,255);  
                        doc.setDrawColor(0);
                        doc.setFillColor(39, 168, 222);
                        doc.printingHeaderRow = true;
                    }else{
                        doc.setFontSize(10)
                        doc.setTextColor(0);    
                        doc.setFillColor(255,255, 255);
                    }
                      
                    if(cells.includes("-r")){
                        
                        cells=cells.replace('-r', '');
                        doc.setFontType("bolditalic");
                        doc.setTextColor(255,0,0);                        
                        //doc.addImage(imgs,'PNG',168,toppdf,8,8);
                       // alert(toppdf)
                        // doc.line(130, toppdf, 165, toppdf);
                       
                         //doc.line(300, toppdf, 340, toppdf);
                        
                    }else{
                        doc.setFontType("normal"); 
                    }
                  
                    if(cells=="-"){
                       doc.setTextColor(255,255, 255);
                    }

                    doc.cell(10, 123,cell_width, 20, cells, i);  // 1st=left margin,2nd parameter=top margin, 3rd=row cell width 4th=Row height
                   
                    l++;
                })
            });
        

            /* $.when.apply(null, ajaxCalls).done(function() {

                    for (j = 0; j < arguments.length; j++) {
                       
                        imgUrl = exportUrl + arguments[j][0];
                    
                        promises[j] = toDataURL(imgUrl);
                    }
            */
            Highcharts.downloadURL = function(dataURL, filename) {
                if(dataURL.length > 2000000) {
                   
                    dataURL = Highcharts.dataURLtoBlob(dataURL);
                    if (!dataURL) {
                        throw 'Data URL length limit reached';
                    }
                }

                promises.push(dataURL);
                counter++;
            };  
       
            start=hd_temp_pd.length;
            if(store==hd_temp_pd.length){
                var st=0;
            }else{
                var st=store;
            }       
        for(i=st;i<hd_temp_pd.length;i++){

            if(hd_temp_pd[i]==1){ 
                $('#wtcontainer').highcharts().exportChartLocal('image/png+xml');
            } 
            if(hd_temp_pd[i]==2){ 
                $('#container').highcharts().exportChartLocal('image/png+xml');
            }  
            if(hd_temp_pd[i]==3){ 
                $('#bpcontainer').highcharts().exportChartLocal('image/png+xml');
            }  
            if(hd_temp_pd[i]==0){ 
                $('#hartcontainer').highcharts().exportChartLocal('image/png+xml');
            }
            if(hd_temp_pd[i]==4){ 
                $('#tempcontainer').highcharts().exportChartLocal('image/png+xml');
            }  
            if(hd_temp_pd[i]==6){ 
                $('#gulcontainer').highcharts().exportChartLocal('image/png+xml');
            }  
            if(hd_temp_pd[i]==5){ 
                $('#spirocontainer').highcharts().exportChartLocal('image/png+xml');
            }  
        }
        if(hd_temp_pd.length>0){
            store=hd_temp_pd.length;
        }else{
            store=0;
        }
        var addGraphHight=0;
        var interval = setInterval(function() {
        // if (counter === 2) {
            
            clearInterval(interval);
            promises.forEach(function(img, index) { 
                //var page = doc.internal.getCurrentPageInfo();
                var remain_page_hight=pageHeight- [(z*20)+170+addGraphHight+ k * 140] ;
               // alert(remain_page_hight);
              //  if (yDocPos > pageHeight - 440 ) {
                if (yDocPos > remain_page_hight ) {
                    doc.addPage();
                    yDocPos = 40;
                    k = 0;
                    z=0;
                } else {
                    if(index==0){ 
                        yDocPos = (z*20)+170+ k * 140 ;
                         addGraphHight+=440;
                    }else{
                        yDocPos+=440;  
                    }
                }   
               // alert(yDocPos)
                var top=yDocPos-20;
                doc.setDrawColor(0);
                doc.setFillColor(242, 244, 244);
                doc.rect(10, top, 575, 340, 'F');

                doc.addImage(img, 'PNG', 25, yDocPos,540,300);
                k++;
            });
            doc.save(hd_p_name+"-"+patient_ids+"-"+date_month+'.pdf');
         // } 
        }, 100);
    }else{
        alert("Please select proper details");
    }
        
});
//});

function tableToJson(table) {
    var data = [];
    // first row needs to be headers
    var headers = [];
    for (var i=0; i<table.rows[0].cells.length; i++) {
        headers[i] = table.rows[0].cells[i].innerHTML.toLowerCase().replace(/ /gi,'');
    }
    // go through cells  
    for (var i=0; i<table.rows.length; i++) {
        var tableRow = table.rows[i];
        var rowData = {};
        for (var j=0; j<tableRow.cells.length; j++) {
            rowData[ headers[j] ] = tableRow.cells[j].innerHTML;
        }
        data.push(rowData);
    }       
    return data;
}

    </script>
@endsection 