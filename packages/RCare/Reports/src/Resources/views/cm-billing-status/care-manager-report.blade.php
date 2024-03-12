@extends('Theme::layouts_2.to-do-master')
@section('page-css')
@section('page-title')
  Care Manager Billing Status
@endsection
@endsection 
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Care Manager Billing Status</h4>
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
                    
                   

                    <div class="col-md-2 form-group mb-2">
                        <label for="caremanagerid">Users</label>
                       @selectorguser("caremanagerid", ["id" => "caremanagerid", "placeholder" => "Select Users","class" => "select2"])
                       <!-- selectAllexceptadmin -->
                    </div>
                    
                    <div class="col-md-2 form-group mb-3">
                        <label for="practicegrp">{{config('global.practice_group')}}</label>
                         @selectgrppractices("practicesgrp", ["class" => "select2","id" => "practicesgrp"]) 
                    </div>

                    <div class="col-md-2 form-group mb-3">
                        <label for="practicename">Practice Name</label>
                         @selectGroupedPractices("practices",["id" => "practices", "class" => "form-control select2"])  
                       
                    </div>

                    <div class="col-md-2 form-group mb-3">
                        <label for="provider">Provider Name</label>
                        @selectpracticesphysicianother("provider",["class" => "select2","id" => "physician"])
                    </div>

                   <div class="col-md-2 form-group mb-2">
                        <label for="module">Module Name</label>
                            <select id="modules" class="custom-select show-tick" name="modules">
                            <option value="0">None</option>
                            <option value="3" selected>CCM</option>
                            <option value="2">RPM</option>
                            </select>
                    </div>

                    <div class="col-md-2 form-group mb-3">
                        <label for="date">From</label>
                        @date('date',["id" => "fromdate"])
                                               
                    </div>

                     <div class="col-md-2 form-group mb-3">
                        <label for="date">To</label>
                        @date('date',["id" => "todate"])
                                              
                    </div>

                     <div class="col-md-2 form-group mb-2"> 
                          <select id="timeoption" name="" class="custom-select show-tick mt-4" style="margin-t op: 23px;">
                            <option value="4">All</option>
                            <option value="2">Greater than</option>
                            <option value="1" selected>Less than</option>
                            <option value="3">Equal to</option>
                          </select>                          
                    </div>

                     <div class="col-md-2 form-group mb-2">                        
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
                    
                    <div class="col-md-1 form-group mb-3">
                       <button type="button" id="searchbutton" class="btn btn-primary mt-4">Search</button>
                    </div>
                    <div class="col-md-1 form-group mb-3">
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
             <button type="button" id="billupdate" class="btn btn-primary mt-4" style="margin-left: 1110px;margin-bottom: 9px;">Add Bill</button>
                <div class="table-responsive">
                    <table id="patient-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="35px">Sr No.</th>
                            <th width="10px">EMR/EHR ID</th>
                            <th width="205px">Patient</th>
                            <th width="97px">DOB</th>
                            <th width="97px">Care Manager</th>
                            <th width="">Provider</th>                         
                            <th>Last Contact</th>
                            <th width="75px">Minutes Spent</th>
                            <th width="75px">Billing</th>
                            <th>Status</th>
                            <th width="75px">Mark As Billable<br/><input name="select_all" value="1" id="example-select-all" type="checkbox" /></th>
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

        

        var table1='';
        var getCareManagerList = function(practicesgrp = null,practice = null,provider=null,modules=null,time=null,caremanagerid=null,fromdate1=null,todate1=null,timeoption=null,activedeactivestatus=null) {
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
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        dob = full['date'];
                                        if(full['date'] == null){
                                            dob = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                             return moment(dob).format('MM-DD-YYYY');      
                                        }
                                    },
                                    orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){                                    
                                   
                                    if(data!='' && data!='NULL' && data!=undefined){
                                           if(full['f_name']==null || full['l_name']==null) {
                                            return '';
                                        }
                                        else
                                        {
                                             return  full['f_name']+' '+full['l_name']; 
                                        }
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

                                {data: 'ccsrecdate',type: 'date-dd-mm-yyyy', name: 'ccsrecdate', "render":function (value) {
                                    if (value === null) return "";
                                        return util.viewsDateFormat(value);
                                    }
                                },                                
                                
                                
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        totaltime = full['ptrtotaltime'];
                                        if(full['ptrtotaltime'] == null){
                                            totaltime = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return totaltime;
                                        }
                                    },
                                    orderable: true
                                },
                                  {data:null,
                                    mRender: function(data, type, full, meta){
                                        status = '';
                                        
                                        if(data!='' && data!='NULL' && data!=undefined){
                                             if(full['pbstatus'] == null || full['pbstatus']==0){
                                                return status='Pending';
                                             }
                                             else
                                             {                                                
                                                return status='Done';
                                               
                                             }
                                            
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
                                {data: null, 'render': function (data, type, full, meta){
                                    if(full['pbstatus'] == null || full['pbstatus']==0){
                                        check = '';
                                    }else{
                                        check = 'checked';
                                    }
                                     return '<input type="checkbox" class="test" name="patientbill" value="'+full['pid']+'" '+check+'>';
                                 },
                                 orderable: false
                               
                            }
                            ];
            
           // debugger;
            if(practicesgrp==''){practicesgrp=null;}
            if(practice==''){practice=null;} 
            if(provider==''){provider=null;}            
            if(time==''){ time=null;}
            if(fromdate1==''){ fromdate1=null; }
            if(todate1=='')  { todate1=null; }
            if(timeoption=='')  { timeoption=null; }
            if(caremanagerid=='')
            {
                caremanagerid=null;
            }
            if(modules==''){
                modules = 3;
            }

            if(activedeactivestatus==''){activedeactivestatus=null;}
            var url = "/reports/care-manager-report/search/"+practicesgrp+"/"+practice+'/'+provider+'/'+modules+'/'+time+'/'+caremanagerid+'/'+fromdate1+'/'+todate1+'/'+timeoption+'/'+activedeactivestatus;
                // console.log(url);
				 table1 = util.renderDataTable('patient-list', url, columns, "{{ asset('') }}");
              
        }
        $(document).ready(function() {

            $('#time').val('00:20:00');
             // var activedeactivestatus = $('#activedeactivestatus').val('');

            function convert(str) {
            var date = new Date(str),
            mnth = ("0" + (date.getMonth() + 1)).slice(-2),
            day = ("0" + date.getDate()).slice(-2);
            return [date.getFullYear(), mnth, day].join("-");
            }
 
            var date = new Date(), y = date.getFullYear(), m = date.getMonth();
            var firstDay = new Date(y, m, 1);
            var lastDay = new Date();


            $("#fromdate").attr("value", (convert(firstDay)));
            $("#todate").attr("value", (convert(lastDay)));
             var fromdate1 = $('#fromdate').val();
             var todate1 = $('#todate').val();
            // getCareManagerList();
            getCareManagerList(null,null,null,null,null,null,fromdate1,todate1,null,null);

            $("[name='practicesgrp']").on("change", function () { 
                var practicegrp_id = $(this).val(); 
                if(practicegrp_id==0){
                   // getCareManagerList();  
                }
                if(practicegrp_id!=''){
                    util.getPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#practices")); 
                }
                else{
                    //getCareManagerList();    
                }   
            });
            // $("[name='caremanagerid']").on("change",function(){
            //     $('#practicesgrp').val('').trigger('change');
            // });

            $("[name='practices']").on("change", function () {
                util.updatePhysicianListWithoutOther(parseInt($(this).val()), $("#physician"))

            });

            $("[name='modules']").val(3).attr("selected", "selected").change();          
            util.getToDoListData(0, {{getPageModuleName()}});
            util.getAssignPatientListData(0, 0);
           
        }); 

        $('#resetbutton').click(function(){ 
            
            function convert(str) {
            var date = new Date(str), 
                mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                day = ("0" + date.getDate()).slice(-2);
                return [date.getFullYear(), mnth, day].join("-");
            }

            var date = new Date(), y = date.getFullYear(), m = date.getMonth();
            var firstDay = new Date(y, m, 1);
            var lastDay = new Date();

            $("#fromdate").val(convert(firstDay));
            $("#todate").val(convert(lastDay));
            $('#caremanagerid').val('').trigger('change');
            $('#practices').val('').trigger('change');
            $('#physician').val('').trigger('change');
            $('#time').val('00:20:00'); 
            $('#timeoption').val("1");
            $('#modules').val("3");
            $('#practicesgrp').val('').trigger('change');
            $('#activedeactivestatus').val('').trigger('change');
            var practicesgrp =  $('#practicesgrp').val();
            var practice=$('#practices').val();
            var provider=$('#physician').val();
            var modules=$('#modules').val();
            var fromdate1=$('#fromdate').val();
            var todate1=$('#todate').val(); 
            var timeoption=$('#timeoption').val();  
            var caremanagerid=$('#caremanagerid').val(); 
            var time=$('#time').val();  
            var activedeactivestatus = $('#activedeactivestatus').val();  
            getCareManagerList(practicesgrp,practice,provider,modules,time,caremanagerid,fromdate1,todate1,timeoption,activedeactivestatus);
            
        });


   
        $('#example-select-all').on('click', function(){                     
            var rows = table1.rows().nodes();                   
            $('input[type="checkbox"]',rows).prop('checked', this.checked);                     
        });

               
      $('#billupdate').click(function(){  
            var checkbox_value=[];
            var notcheckbox_value=[];
            var rowcollection =  table1.$("input[type='checkbox']:checked", {"page": "all"});
            var rowcollection1 =  table1.$("input:checkbox:not(:checked)",{"page": "all"})
            rowcollection.each(function(index,elem){
                checkbox_value.push($(elem).val());              
                
            }); 
            rowcollection1.each(function(index,elem){
                notcheckbox_value.push($(elem).val());              
                
            }); 
           // var data=
            var modules=$('#modules').val();
          //  console.log(checkbox_value);
             var data = {
            patient_id: checkbox_value,
            uncheckedpatient_id: notcheckbox_value,
            module_id: modules         

        }
             $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: '/reports/billupdate',
            data: data,
            success: function (data) {
                console.log("save cuccess");
                getCareManagerList();

            }
        });
      
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
  //debugger
    $('#time').removeClass("is-invalid");
    var practicesgrp = $('#practicesgrp').val(); 
    var practice=$('#practices').val();
    var provider=$('#physician').val();
    var modules=$('#modules').val();
    var fromdate1=$('#fromdate').val();
    var todate1=$('#todate').val(); 
    var timeoption=$('#timeoption').val();  
    var caremanagerid=$('#caremanagerid').val();
    var activedeactivestatus=$('#activedeactivestatus').val();
    var time=$('#time').val(); 

       if(todate1 < fromdate1)
            {
                $('#todate').addClass("is-invalid");
                $('#todate').next(".invalid-feedback").html("Please select to-date properly .");
                $('#fromdate').addClass("is-invalid");
                $('#fromdate').next(".invalid-feedback").html("Please select from-date properly .");   
            } 
            
            else{ 
                $('#todate').removeClass("is-invalid");
                $('#todate').removeClass("invalid-feedback");
                $('#fromdate').removeClass("is-invalid");
                $('#fromdate').removeClass("invalid-feedback");
                //getCareManagerList(practicesgrp,practice,provider,modules,time,caremanagerid,fromdate1,todate1,timeoption);
            }

        var time_regex = new RegExp(/^(((([0-1][0-9])|(2[0-3])):?[0-5][0-9]:?[0-5][0-9]+$))/g);
        
                    if(time!="")  
                    {
                        if(time.length!=8) 
                        {
                            // alert("test time");
                            $('#time').addClass("is-invalid"); 
                            $('#time').next(".invalid-feedback").html("Please enter time in HH:MM:SS format.");
                        }
                        else{

                        
                        if(time_regex.test(time))
                        { 
                            // alert("iftime");
                            $('#time').removeClass("is-invalid"); 
                            $('#time').removeClass("invalid-feedback");     
                            getCareManagerList(practicesgrp,practice,provider,modules,time,caremanagerid,fromdate1,todate1,timeoption,activedeactivestatus); 
                        }
                        else 
                        {
                        // alert('else');
                            $('#time').addClass("is-invalid");
                            $('#time').next(".invalid-feedback").html("Please enter time in HH:MM:SS format.");
                        }
                        }
                    }
                    else 
                    {  
                        // alert('lastelse');  
                        $('#time').removeClass("is-invalid"); 
                        $('#time').removeClass("invalid-feedback");     
                        getCareManagerList(practicesgrp,practice,provider,modules,time,caremanagerid,fromdate1,todate1,timeoption,activedeactivestatus);   
                    }
  
});


 
    </script>
@endsection