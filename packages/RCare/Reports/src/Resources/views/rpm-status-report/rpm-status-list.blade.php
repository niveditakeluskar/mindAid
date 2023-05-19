@extends('Theme::layouts_2.to-do-master')
@section('page-css')
@section('page-title')
 Rpm Daily Status Report
@endsection 
@endsection
@section('main-content')

<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Rpm Daily Status Report</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

<div _ngcontent-rei-c8="" class="row">
   <div _ngcontent-rei-c8="" style="width: 30%;margin-left: 20%; margin-right: 1%;" >
      <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Myspace"></i>
            <div _ngcontent-rei-c8="" class="content">
               <p _ngcontent-rei-c8="" class="text-muted" style="width: 90px;height: 30px;">Total RPM Active</p>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" >
                 <a href="/reports/total-rpm-patients-details" id="totalrpmpatient" target="_blank"></a>  
               </p>
            </div>
         </div>
      </div>
   </div>
   <div _ngcontent-rei-c8=""  style="width: 30%;margin-left: 1%;margin-right: 1%;" >
      <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Add-UserStar"></i>
            <div _ngcontent-rei-c8="" class="content">
               <p _ngcontent-rei-c8="" class="text-muted" style="width: 90px;height: 30px;margin-left: -20px;">Enrollments this month</p>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" >
                    <a href="/reports/newenrolled-patients-details" id="totalnewlyenrolledpatient" target="_blank"></a>
               </p>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">     
            <div class="card-body">
                <form id="rpm_daily_status_report_form" name="rpm_daily_status_report_form"  action ="">
                @csrf
                <div class="form-row">
                    
                    <div class="col-md-2 form-group mb-3">
                        <label for="practicename">Practice Name</label>
                        @selectpracticeswithAll("practices", ["id" => "practicesid","class" => "select2"])
                    </div>

                    <div class="col-md-2 form-group mb-2">
                        <label for="date">From Date</label>
                        @date('date',["id" => "fromdate"])                       
                    </div>
                    <div class="col-md-1">
                        <button type="button" id="searchbutton" class="btn btn-primary mt-4">Search</button>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-primary mt-4" id="month-reset">Reset</button>
                    </div>
                </div>
                
                </form>
            </div>
        </div>
    </div>
</div>  

<!-- ?php
 echo date('M Y'); 
?> -->

<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="rpm-patient-list" class="display datatable table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="35px">Sr No.</th>
                            <th width>Practice</th>                          
                            <th>Total Enrolled</th>
                            <th>Newly Enrolled in this Month ( <?php echo date('M'); ?> )</th>
                            <th>Patient Devices Link</th>
                            <th width="75px">Patients (Readings not received for last 3 days)</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
            </div>
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
   
    <script type="text/javascript">

            var getRpmDailyPatientList = function(practice = null,fromdate=null) {
                        var columns =  [
                                            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                                            
                                            {data:null,
                                                mRender: function(data, type, full, meta){
                                                    name = full['practices'];
                                                    if(full['practices'] == null){
                                                        name = '';
                                                    }
                                                    if(data!='' && data!='NULL' && data!=undefined){
                                                        return name;
                                                    }
                                                },
                                                orderable: true
                                            },
                                            {data:null,
                                                mRender: function(data, type, full, meta){
                                                    enrolledcount = full['enrolledcount'];
                                                    if(full['enrolledcount'] == null){
                                                        enrolledcount = '';
                                                    }
                                                    if(data!='' && data!='NULL' && data!=undefined){
                                                        return enrolledcount;
                                                    }
                                                },
                                                orderable: true
                                            },
                                            {data:null,
                                                mRender: function(data, type, full, meta){
                                                    newlyenrolled = full['newlyenrolled'];
                                                    if(full['newlyenrolled'] == null){
                                                        newlyenrolled = '';
                                                    }
                                                    if(data!='' && data!='NULL' && data!=undefined){
                                                        return newlyenrolled;
                                                    }
                                                },
                                                orderable: true
                                            },
                                            {data:null,
                                                mRender: function(data, type, full, meta){
                                                    patientdeviceslinkcount = full['patientdeviceslink'];
                                                    if(full['patientdeviceslink'] == null){
                                                        patientdeviceslinkcount = '';
                                                    }
                                                    if(data!='' && data!='NULL' && data!=undefined){   
                                                        return patientdeviceslinkcount;
                                                    }
                                                },
                                                orderable: true
                                            },
                                            {data:null,
                                                mRender: function(data, type, full, meta){
                                                    count = full['noreadingforlasthtreedayscount'];
                                                    prac  = full['practiceid'];
                                                    if(full['noreadingforlasthtreedayscount'] == null){
                                                        count = '';
                                                    }
                                                    if(count!='' && count!='NULL' && count!=undefined && count!='0'){ 
                                                       
                                                       var c = count+'   <a href="/reports/noreadingslastthreedaysInRPM-patients-details/'+prac+'" id="totalnoreadingsrpmpatient" target="_blank">Detail</a>';
                                                        
                                                    // var c = count+'   <a href="/reports/noreadingslastthreedaysInRPM/search/'+full['practiceid']+'" id="totalnoreadingsrpmpatient" target="_blank">Detail</a>';
                                                    return c;
                                                    }else{
                                                        return 0;
                                                    }
                                                },
                                                orderable: true
                                            }
                                             
                                           
                                        ];
                        
                    // debugger;
                    
                        if(practice==''){
                            practice=null;
                        }
                        if(fromdate==''){ 
                            fromdate=null;
                        }
                       

                            // var url = "/reports/daily-report/search/"+practice+'/'+provider+'/'+modules+'/'+date+'/'+time+'/'+timeoption;
                             var url = "/reports/rpm-daily-status-report/search/"+practice+'/'+fromdate;
                            console.log("url",url);
                            var table1 = util.renderDataTable('rpm-patient-list', url, columns, "{{ asset('') }}");
                        
                    }

                function formatDate() {
                var d = new Date(),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2) 
                month = '0' + month;
            if (day.length < 2) 
                day = '0' + day;

            return [year,month, day].join('-');
            }


            var currentdate = formatDate();

            $(document).ready(function(){

            getRpmPatientData(); 

            $('#fromdate').val(currentdate);
            getRpmDailyPatientList(null,null);  
            
            
               
                
            });

            $('#searchbutton').click(function(){
                var practice=$('#practicesid').val();
                var fromdate=$('#fromdate').val();
                 
                getRpmDailyPatientList(practice,fromdate); 

            });

            $("#month-reset").click(function(){   
               
                $('#practicesid').val('').trigger('change');    
                var practice=$('#practicesid').val();
                $('#fromdate').val(currentdate);
                var fromdate=$('#fromdate').val();
               
                getRpmDailyPatientList(practice,fromdate);  
            });

            var getRpmPatientData=function()
            {
                    
                    $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                type: 'POST',
                                url: '/reports/rpm-patient-summary',
                                //data: data,
                                success: function (data) {
                                    console.log(data);
                                var totalRpmPatient=data.totalRpmPatient[0]['count'];
                                var totalnewlyenrolled=data.totalnewlyenrolled[0]['count'];
                              
                                    $('#totalrpmpatient').html(totalRpmPatient); 
                                    $('#totalnewlyenrolledpatient').html(totalnewlyenrolled);
                                        
                                

                                }
                        });
            }  




    </script>
@endsection