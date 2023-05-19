@extends('Theme::layouts_2.to-do-master')
@section('page-css')
 @section('page-title')
   Daily Billable Report
@endsection 
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Daily Billable Report</h4>
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
                    <div class="col-md-3 form-group mb-3">  
                        <label for="practicegrp">{{config('global.practice_group')}}</label>
                         @selectgrppractices("practicesgrp", ["class" => "select2","id" => "practicesgrp"]) 
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="practicename">Practice</label>                  
                         @selectGroupedPractices("practices",["id" => "practices", "class" => "form-control show-tick select2"])   
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="provider">Provider</label>
                        @selectpracticesphysician("provider",["id" => "physician","class"=>"custom-select show-tick select2"])
                    </div>
                   
                    <div class="col-md-2 form-group mb-3">
                        <label for="module">Module</label>
                        <select name="modules" id="modules" class="custom-select show-tick select2">
                            <option value="3">CCM</option>
                            <option value="2">RPM</option>  
                        </select>  
                    </div>

                    <div class="col-md-2 form-group mb-2">
                        <label for="date">From Date</label>
                        @date('date',["id" => "fromdate"])
                                               
                    </div>
                    
                    
                    </div>
                    <div class="form-row"> 
                        <div class="col-md-2 form-group mb-3">
                            <label for="date">To Date</label>
                            @date('date',["id" => "todate"])
                                                
                        </div>  
                   
                        <div class="col-md-2 form-group mb-2"> 
                            <select id="timeoption" class="custom-select show-tick" style="margin-top: 23px;">
                            <option value="4">All</option>
                                <option value="2">Greater than</option>
                                <option value="1" selected>Less than</option>
                                <option value="3">Equal to</option>                             
                            </select>                         
                        </div>
                        <div class="col-md-2 form-group mb-3">                      
                            <label for="time">Time</label> 
                                @text("time", ["id" => "time", "placeholder" => "hh:mm:ss"])
                        </div>
                        <div class="col-md-2 form-group mb-3">  
                          <label for="activedeactivestatus">Status</label> 
                          <select id="activedeactivestatus" name="activedeactivestatus" class="custom-select show-tick" >
                            <option value="" selected>All (Active,Suspended,Deactivated,Deceased)</option> 
                            <option value="1">Active</option>
                            <option value="0">Suspended</option>
                            <option value="2" >Deactivated</option>                           
                            <option value="3" >Deceased</option>
                          </select>                          
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



 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="patient-list" class="display datatable table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="35px">Sr No.</th>
                            <th width="10px">EMR/EHR ID</th>
                            <th width="205px">Patient</th>
                            <th width="97px">DOB</th>
                            <th width="97px">Date of service</th>
                            <th width="">CPT Code</th>
                            <th width="">Unit</th>
                            <th width="220px">Conditions</th> 
                            <th >Practice</th>                          
                            <th >Provider</th>
                            <th>Status</th>
                            <th width="75px">Minutes Spent</th>
                            
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

        
        


            var getDailyPatientList = function(practicesgrp = null,practice = null,provider=null,modules=null,fromdate=null,todate=null,time=null,timeoption=null,activedeactivestatus=null) {
            var columns =  [
                                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                                {data: null,
                                     mRender: function(data, type, full, meta){
                                        practice_emr = full['pppracticeemr'];
                                        if(full['pppracticeemr'] == null){
                                            practice_emr = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return practice_emr;
                                        }
                                    },
                                    orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    m_Name = full['pmname'];
                                    if(full['pmname'] == null){
                                        m_Name = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                        if(full['profile_img']=='' || full['profile_img']==null) {
                                            return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['pfname']+' '+m_Name+' '+full['plname'];
                                        } else {
                                            return ["<img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' '+full['pfname']+' '+m_Name+' '+full['plname'];
                                        }
                                        // return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                    }
                                },
                                orderable: true
                                },
                                // {data: null, type: 'date-dd-mmm-yyyy', "render":function (value) {
                                //     if (value === null) return "";
                                //         return util.viewsDateFormat(value);
                                //     }
                                // },
                                {data: 'pdob', type: 'date-dd-mmm-yyyy', name: 'pdob', "render":function (value) {
                                if (value === null) return "";
                                    return moment(value).format('MM-DD-YYYY');
                                }
                                },   

                            {data: 'ccsrecdate', type: 'date-dd-mmm-yyyy', name: 'ccsrecdate', "render":function (value) {
                                if (value === null) return "";
                                    return moment(value).format('MM-DD-YYYY');
                                }
                            },  

                            {data:null,
                                    mRender: function(data, type, full, meta){
                                        billingcode = full['billingcode'];
                                        if(full['billingcode'] == null){
                                            billingcode = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return billingcode;
                                        }
                                    },
                                    orderable: true  
                                }, 

                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        unit = full['unit'];
                                        if(full['unit'] == null){
                                            unit = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return unit;
                                        }
                                    },
                                    orderable: true
                                },

                                                            
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        condition = full['pdcondition'];
                                        if(full['pdcondition'] == null){
                                            condition = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return condition;
                                        }
                                    },
                                    orderable: true
                                }, 
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        name = full['pracpracticename'];
                                        if(full['pracpracticename'] == null){
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
                                        name = full['prprovidername'];
                                        if(full['prprovidername'] == null){
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
                                    status = full['pstatus'];
                                      if(full['pstatus'] == 1){
                                          status = 'Active';
                                      } 
                                      if(full['pstatus'] == 0){
                                          status = 'Suspended';
                                      }
                                      if(full['pstatus'] == 2){ 
                                          status = 'Deactived';
                                      }
                                      if(full['pstatus'] == 3){ 
                                          status = 'Deceased';
                                      }
                                      if(data!='' && data!='NULL' && data!=undefined){
                                        return status;
                                      }
                                    },
                                    orderable: true, searchable: false
                                }, 

                                {data: 'ptrtotaltime', name: 'ptrtotaltime', orderable: true}
                            ];
            
           // debugger;
           if(practicesgrp=='')
            {
                practicesgrp=null;
            } 
            if(practice=='')
            {
                practice=null;
            } if(provider=='')
            {
                provider=null;
            }
             if(modules=='')
            {
                modules=null;
            } 
            if(fromdate==''){ fromdate=null; }
            if(todate=='')  { todate=null; }
             if(time=='')
            {
                time=null; 
            }
            if(time=='00:00:00')  
                {
                   
                    time='00:00:00';
                }
            if(timeoption=='')
            {
                timeoption=null;
            }
            if(activedeactivestatus==''){activedeactivestatus=null;}

                // var url = "/reports/daily-report/search/"+practice+'/'+provider+'/'+modules+'/'+date+'/'+time+'/'+timeoption;
                var url = "/reports/daily-report/search/"+practicesgrp+'/'+practice+'/'+provider+'/'+modules+'/'+fromdate+'/'+todate+'/'+time+'/'+timeoption+'/'+activedeactivestatus;
				var table1 = util.renderDataTable('patient-list', url, columns, "{{ asset('') }}");
              
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
            





        
$(document).ready(function(){

    $('#fromdate').val(firstDayWithSlashes);                      
    $('#todate').val(currentdate);
    var fromdate = $('#fromdate').val();
    var todate = $('#todate').val();

    $('#time').val('00:20:00');           
    // var d =  $('#date').val();        
    // getDailyPatientList(); 
    getDailyPatientList(null,null,null,null,fromdate,todate,null,null,null); 
    
    
    $("[name='practicesgrp']").on("change", function () { 
        var practicegrp_id = $(this).val(); 
        if(practicegrp_id==0){
            // getDailyPatientList();  
        }
        if(practicegrp_id!=''){
            util.getPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#practices")); 
        }
        else{
                util.updatePracticeListWithoutOther(001, $("#practices"));    
        }   
    });

    $("[name='practices']").on("change", function () {
      util.updatePhysicianListWithoutOther(parseInt($(this).val()), $("#physician"))
    });


    $("[name='modules']").val(3).attr("selected", "selected").change();
    util.getToDoListData(0, {{getPageModuleName()}});
    $("#timeoption").val('4').trigger('change');
}); 

$('#timeoption').change(function(){
   $checkvalue=$('#timeoption').val();
   if($checkvalue=='4')
   {
    $('#time').val('00:00:00'); 
    $('#time').prop( "disabled", true);
   }
   else
   {
    $('#time').val('00:20:00');    
    $('#time').prop( "disabled", false);
   }
});

$('#searchbutton').click(function(){
	$('#time').removeClass("is-invalid");
    $('#time').removeClass(".invalid-feedback");
    var practice=$('#practices').val();
     var provider=$('#physician').val();
     var modules=$('#modules').val();
     var fromdate=$('#fromdate').val();
     var todate=$('#todate').val();  
     var timeoption=$('#timeoption').val(); 
     var time=$('#time').val(); 
     var practicesgrp = $('#practicesgrp').val();
     var activedeactivestatus =$('#activedeactivestatus').val();
     var eDate = new Date(todate);
     var sDate = new Date(fromdate);


           if(fromdate!= '' && todate!= '' && sDate> eDate)
             {
             alert("Please ensure that the Enrolled To Date is greater than or equal to the Enrolled From Date.");
             return false;
             }
             else
             {
                if(time == '00:00:00')
                {
                    time = '00:00:00';
                }    
  
                var time_regex = new RegExp(/^(((([0-1][0-9])|(2[0-3])):?[0-5][0-9]:?[0-5][0-9]+$))/g);
                
                if(time!="") 
                        {
                            if(time.length!=8)
                            {
                                $('#time').addClass("is-invalid");
                                $('#time').next(".invalid-feedback").html("Please enter time in HH:MM:SS format.");
                            }
                            else{

                            
                            if(time_regex.test(time))
                            { 
                                $('#time').removeClass("is-invalid"); 
                                $('#time').removeClass("invalid-feedback");     
                                getDailyPatientList(practicesgrp,practice,provider,modules,fromdate,todate,time,timeoption,activedeactivestatus);
                            }
                            else 
                            {
                        
                                $('#time').addClass("is-invalid");
                                $('#time').next(".invalid-feedback").html("Please enter time in HH:MM:SS format.");
                            }
                            }
                        }
                        else
                        {  
                            $('#time').removeClass("is-invalid"); 
                            $('#time').removeClass("invalid-feedback");     
                            getDailyPatientList(practicesgrp,practice,provider,modules,fromdate,todate,time,timeoption,activedeactivestatus);
                        }
                    getDailyPatientList(practicesgrp,practice,provider,modules,fromdate,todate,time,timeoption,activedeactivestatus);
            }


     

});

$("#month-reset").click(function(){
    $('#time').removeClass("is-invalid"); 
    $('#time').removeClass("invalid-feedback");
    var practice = null
    var modules = 3;
    var provider = null;
    var timeoption = null;
    var time = null;
    var date = null;
    var practicesgrp = null;
    var activedeactivestatus = 3; 
    var currentdate = formatDate(); 
    
     $('#fromdate').val(firstDayWithSlashes);  
     $('#todate').val(currentdate);
     $('#time').val('00:20:00');
     $('#practicesgrp').val('').trigger('change');
     $('#practices').val('').trigger('change');
     $('#physician').val('').trigger('change');
     $('#timeoption').val('1').trigger('change'); 
     $('#modules').val('3').trigger('change'); 
     $('#activedeactivestatus').val('').trigger('change');

     var fromdate1=$('#fromdate').val();
     var todate1=$('#todate').val();
     getDailyPatientList(practicesgrp,practice,provider,modules,fromdate1,todate1,time,timeoption,activedeactivestatus); 
  
});




 
    </script>
@endsection