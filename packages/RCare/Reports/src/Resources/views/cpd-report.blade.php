@extends('Theme::layouts_2.to-do-master')
@section('page-css')
  
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">CPD Report</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                <form id="daily_report_form" name="daily_report_form"  action ="">
                @csrf
                <div class="form-row">
                    <div class="col-md-2 form-group mb-3"> 
                        <label for="billable">CPD Time</label> 
                        <select id="billable" name="billable" class="custom-select show-tick" >
                            <option value="0" selected>Non Billable With MM</option> 
                            <option value="1">Billable With MM</option>                           
                        </select>                          
                    </div>
                     <div class="col-md-2 form-group mb-2">
                        <label for="practicegrp">{{config('global.practice_group')}}</label>
                         @selectgrppractices("practicesgrp", ["class" => "select2","id" => "practicesgrp"]) 
                    </div>
                    <div class="col-md-2 form-group mb-2">
                        <label for="practicename">Practice Name</label>
                          @selectGroupedPractices("practices",["id" => "practices", "class" => "form-control select2"]) 
                    </div>                  
                    <div class="col-md-2 form-group mb-3"> 
                        <label for="careplanstatuas">Care Plan Status</label> 
                        <select id="careplanstatuas" name="careplanstatuas" class="custom-select show-tick" >
                            <option value="0" selected>Not Finalized</option> 
                            <option value="1">Finalized</option>
                            <option value="" >Null</option>                           
                        </select>                          
                    </div>
                     
                    <div class="col-md-2 form-group mb-2">
                        <label for="date">From Date</label>
                        @date('date',["id" => "fromdate"])
                                               
                    </div>
                     <div class="col-md-2 form-group mb-3">
                        <label for="date">To Date</label>
                        @date('date',["id" => "todate"])
                                              
                    </div>
                    <div class="col-md-2 form-group mb-3">  
                          <label for="activedeactivestatus">Status</label> 
                          <select id="activedeactivestatus" name="activedeactivestatus" class="custom-select show-tick" >
                            <option value="" selected>All (Activated,Suspendend,Deactivated,Deceased)</option> 
                            <option value="1">Active</option>
                            <option value="0">Suspended</option>
                            <option value="2" >Deactivated</option>                           
                            <option value="3" >Deceased</option>
                          </select>                          
                    </div>
                    <div class="col-md-2 form-group mb-3">
                       <button type="button" id="searchbutton" class="btn btn-primary mt-4">Search</button>                   
                       <button type="button" id="resetbutton" class="btn btn-primary mt-4">Reset</button>
                    </div>
                  
                </div>
                </form>
            </div>
        </div>

    </div>
</div>


 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">

            <div class="card-body">
                @include('Theme::layouts.flash-message')                
           
                <div class="table-responsive">
                    <table id="Activities-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="">Sr No.</th>                            
                            <th width="">Patient</th>
                            <th width="">DOB</th>
                            <th width="">Practice</th>
                            <th width="">CPT Code</th>
                            <th width="">Care Plan Status</th>
                            <th width="">Finalized Date</th>
                            <th width="">Total Time Spent(CPD)</th>
                            <th width="">Billable Time(CPD)</th>  
                            <th width="">Non Billable Time(CPD)</th>    
                            <th width="">MM Billable Time</th>
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
        
       var getAdditionalActivitiesList = function(practicesgrp=null,practice = null,billable=null,careplanstatuas=null,fromdate1=null,todate1=null,activedeactivestatus=null) {
            var columns =  [   
                                {data: 'DT_RowIndex', name: 'DT_RowIndex'},                            
                                {data: null, mRender: function(data, type, full, meta){
                                    m_Name = full['mname'];
                                    if(full['mname'] == null){
                                        m_Name = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                        if(full['profile_image']=='' || full['profile_image']==null) {
                                            return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                        } 
                                        else 
                                        {
                                            return ["<img src='"+full['profile_image']+"' width='40px' height='25px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                        }                                       
                                    }
                                },
                                orderable: true
                                },
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        dob = full['dob'];
                                        if(full['dob'] == null){
                                            dob = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                             return moment(dob).format('MM-DD-YYYY');      
                                        }
                                    },
                                    orderable: true
                                },
                               {data:null,
                                    mRender: function(data, type, full, meta){
                                        name = full['practicename'];
                                        if(full['practicename'] == null){
                                            name = '';
                                        }
                                          
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return name;
                                        }
                                    },
                                    orderable: true
                                },
                                {data: null,
                                    mRender: function(data, type, full, meta){
                                        if(full['nonbillabeltime'] == null || full['nonbillabeltime'] == '00:00:00'){
                                            if(full['totaltime'] >= '00:20:00' && full['totaltime'] < '00:40:00' ){
                                                cptcode = '99490' ;
                                            }else if(full['totaltime'] >= '00:40:00' && full['totaltime'] < '01:00:00' ){
                                                cptcode = '99490,99439';
                                            }else if(full['totaltime'] >= '01:00:00' && full['totaltime'] < '01:30:00' ){
                                                cptcode = '99490,99439';
                                            }else if(full['totaltime'] >= '01:30:00'){
                                                cptcode = '99487,99489';
                                            }else{
                                                cptcode = '';
                                            }
                                            
                                        }else{
                                            if(full['cpdstautus'] == 'Finalized'){
                                                cptcode = 'G0506';
                                            }else{
                                                cptcode = '';   
                                            }
                                        }
                                        return cptcode;
                                    },
                                    orderable: true
                                },
                                {data: 'cpdstautus', name: 'cpdstautus'},
                                {data: 'finalizedate', name: 'finalizedate'},
                                
                                 {data: null,
                                    mRender: function(data, type, full, meta){
                                        totaltimecpd = full['totaltimecpd'];
                                        if(full['totaltimecpd'] == null){
                                            totaltimecpd = '00:00:00';
                                        }

                                        if(data!='' && data!='NULL' && data!=undefined){ 
                                            return totaltimecpd;
                                        }
                                    },
                                    orderable: true
                                },
                                {data: null,
                                    mRender: function(data, type, full, meta){
                                        billabeltime = full['billabeltime'];
                                        if(full['billabeltime'] == null){
                                            billabeltime = '00:00:00';
                                        }

                                        if(data!='' && data!='NULL' && data!=undefined){ 
                                            return billabeltime;
                                        }
                                    },
                                    orderable: true
                                },
                                {data: null,
                                    mRender: function(data, type, full, meta){
                                        nonbillabeltime = full['nonbillabeltime'];
                                        if(full['nonbillabeltime'] == null){
                                            nonbillabeltime = '00:00:00';
                                        }

                                        if(data!='' && data!='NULL' && data!=undefined){ 
                                            return nonbillabeltime;
                                        }
                                    },
                                    orderable: true
                                },
                                {data: null,
                                    mRender: function(data, type, full, meta){
                                        totaltime = full['totaltime'];
                                        if(full['totaltime'] == null){
                                            totaltime = '00:00:00';
                                        }

                                        if(data!='' && data!='NULL' && data!=undefined){ 
                                            return totaltime;
                                        }
                                    },
                                    orderable: true
                                },
                            ];
            
             // debugger;
             if(practicesgrp==''){practicesgrp=null;}
             if(practice==''){practice=null;}                        
             if(billable==''){ billable=null; }
             if(careplanstatuas=='')  { careplanstatuas=null; }
             if(fromdate1==''){ fromdate1=null; }
             if(todate1=='')  { todate1=null; }
             if(activedeactivestatus==''){activedeactivestatus=null;}

         var url = "/reports/cpd-report/search/"+practicesgrp+"/"+practice+'/'+billable+'/'+careplanstatuas+'/'+fromdate1+'/'+todate1+'/'+activedeactivestatus;
         table1 = util.renderDataTable('Activities-list', url, columns, "{{ asset('') }}");
              
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
            var date = new Date(); 

           var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);         
           var getmnth=("0" +(date.getMonth() + 1)).slice(-2);
           var firstDayWithSlashes = date.getFullYear()+ '-' + getmnth + '-' +('0' +(firstDay.getDate())).slice(-2);
        

        $(document).ready(function() {                
            
            $('#fromdate').val(firstDayWithSlashes);                      
            $('#todate').val(currentdate);
            util.getToDoListData(0, {{getPageModuleName()}});

            $("[name='practicesgrp']").on("change", function () { 
                var practicegrp_id = $(this).val(); 
                if(practicegrp_id==0){
                   // getPatientData();  
                }
                if(practicegrp_id!=''){
                    util.getPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#practices")); 
                }
                else{
                     util.updatePracticeListWithoutOther(001, $("#practices")) ;
                }   
            });
    

             
           
        }); 

   
    


     $('#resetbutton').click(function(){
            $('#practicesgrp').val('').trigger('change');
            $('#practices').val('').trigger('change');
           
            $('#billable').val(0);
            $('#careplanstatuas').val('0');
             
            
            var practicesgrp =$('#practicesgrp').val();
            var practice=$('#practices').val();
            var careplanstatuas=$('#careplanstatuas').val();
            var billable=$('#billable').val(); 
            var fromdate1=$('#fromdate').val();
            var todate1=$('#todate').val();
            var activedeactivestatus = $('#activedeactivestatus').val();
            $('#fromdate').val(firstDayWithSlashes);                      
            $('#todate').val(currentdate); 
            $('#activedeactivestatus').val('');
        
      
            
            getAdditionalActivitiesList(practicesgrp,practice,billable,careplanstatuas,fromdate1,todate1,activedeactivestatus);

  });

$('#searchbutton').click(function(){
   
   
    var practicesgrp =$('#practicesgrp').val(); 
    var practice=$('#practices').val();     
    var careplanstatuas=$('#careplanstatuas').val();
    var billable=$('#billable').val();    
    var fromdate1=$('#fromdate').val();
    var todate1=$('#todate').val();   
    var activedeactivestatus = $('#activedeactivestatus').val();      
    var eDate = new Date(todate1);
           var sDate = new Date(fromdate1);
           if(fromdate1!= '' && todate1!= '' && sDate> eDate)
             {
             alert("Please ensure that the To Date is greater than or equal to the From Date.");
             return false;
             }
             else
             {
            getAdditionalActivitiesList(practicesgrp,practice,billable,careplanstatuas,fromdate1,todate1,activedeactivestatus);
             }
  });

   
         
    
 
    </script>
@endsection