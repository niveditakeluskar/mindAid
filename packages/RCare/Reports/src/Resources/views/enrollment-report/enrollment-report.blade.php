@extends('Theme::layouts_2.to-do-master')
@section('page-css')
  
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Enrollment Report</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>



<div _ngcontent-rei-c8="" class="row">
   <div _ngcontent-rei-c8="" style="width: 19%;margin-left: 1%; margin-right: 1%;" >
      <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Myspace"></i>
            <div _ngcontent-rei-c8="" class="content">
               <p _ngcontent-rei-c8="" class="text-muted" style="width: 86px;height: 30px;">Total Patients</p>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" >
                 <a href="/reports/total-patients-details" id="totalpatient" target="_blank"></a>
               </p>
            </div>
         </div>
      </div>
   </div>
   <div _ngcontent-rei-c8=""  style="width: 19%;margin-right: 1%;" >
      <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Add-UserStar"></i>
            <div _ngcontent-rei-c8="" class="content">
               <p _ngcontent-rei-c8="" class="text-muted" style="width: 130px;height: 30px;margin-left: -20px;">Enrolled/Suspended</p>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" ><!-- <i type="button" class="editform click_id" data-toggle="modal" data-target="#DetailsModal" target="Enrolled" id="totalenrolledpatient"></i> -->
                    <a href="/reports/enrolled-patients-details" id="totalenrolledpatient" target="_blank"></a>
               </p>
            </div>
         </div>
      </div>
   </div>
   <div _ngcontent-rei-c8="" style="width: 19%;margin-right: 1%;">
      <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Checked-User"></i>
            <div _ngcontent-rei-c8="" class="content">
               <p _ngcontent-rei-c8="" class="text-muted" style="width: 172px;height: 30px;margin-left: -50px;">CCM <br>Enrolled/Suspended</p>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2"><!-- <i type="button" class="editform click_id" data-toggle="modal" data-target="#DetailsModal" target="EnrolledInCCM" id="totalenrolledpatientCCM"></i> -->
                  <a href="/reports/enrolled-In-CCM-details" id="totalenrolledpatientCCM" target="_blank"></a>
               </p>
            </div>
         </div> 
      </div>
   </div>
   <div _ngcontent-rei-c8="" style="width: 19%;margin-right: 1%;">
      <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Checked-User"></i>
            <div _ngcontent-rei-c8="" class="content">
               <p _ngcontent-rei-c8="" class="text-muted" style="width: 172px; height: 30px;margin-left: -50px;">RPM <br>Enrolled /Suspended</p>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" ><!-- <i type="button" class="editform click_id" data-toggle="modal" data-target="#DetailsModal" target="EnrolledInRPM" id="totalenrolledpatientRPM"></i> -->
                    <a href="/reports/enrolled-In-RPM-details" id="totalenrolledpatientRPM" target="_blank"></a>
               </p>
            </div>
         </div>
      </div>
   </div> 
    <div _ngcontent-rei-c8="" style="width: 18%;margin-right: 1%;">
      <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Remove-User"></i>
            <div _ngcontent-rei-c8="" class="content">
               <p _ngcontent-rei-c8="" class="text-muted" style="width: 125px;height: 30px;">Awaiting Enrollment</p>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" ><!-- <i type="button" class="editform click_id" data-toggle="modal" data-target="#DetailsModal" target="Non-Enrolled" id="totalunenrolledpatient"></i> -->
                   <a href="/reports/non-enrolled-patients-details" id="totalunenrolledpatient" target="_blank"></a>
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
                <form id="daily_report_form" name="daily_report_form"  action ="">
                @csrf
                <div class="form-row">
                   
                     <div class="col-md-2 form-group mb-2">
                        <label for="practicegrp">{{config('global.practice_group')}}</label>
                         @selectgrppractices("practicesgrp", ["class" => "select2","id" => "practicesgrp"]) 
                     </div>

                    <div class="col-md-2 form-group mb-2">
                        <label for="caremanagerid">Users</label>
                       @selectorguser("caremanagerid", ["id" => "caremanagerid", "placeholder" => "Select Users","class" => "select2"]) 
                       <!-- selectAllexceptadmin -->
                    </div>
   

                    <div class="col-md-2 form-group mb-2">
                        <label for="practicename">Practice Name</label>                       
                     @selectGroupedPractices("practices",["id" => "practices", "class" => "form-control select2"])   
                    </div>
                     <div class="col-md-3 form-group mb-3">
                        <label for="provider">Provider Name</label>
                        @selectpracticesphysicianother("provider",["class" => "select2","id" => "physician"])
                    </div>
                  
                   <div class="col-md-2 form-group mb-2">
                        <label for="module">Module Name</label>
                        @selectOrgModule("modules",["id" => "modules"])
                    </div>
                   
                    <div class="col-md-2 form-group mb-2">
                        <label for="date">Enrolled From</label>
                        @date('date',["id" => "fromdate"])
                                               
                    </div>
                     <div class="col-md-2 form-group mb-3">
                        <label for="date">Enrolled To</label>
                        @date('date',["id" => "todate"])
                                              
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
                    <table id="patient-list" class="display datatable table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="35px">Sr No.</th>   
                            <th width="60px">EMR/EHR ID</th>                        
                            <th width="205px">Patient</th>
                            <th width="15px">Address</th>
                            <th width="97px">DOB</th>
                            <th width="97px">CM</th>
                            <th width="">Practice</th> 
                            <th width="">Provider</th> 
                            <th >Enrolled Date</th>
                            <th width="">Status</th>  
                            <th width="">Enrolled Modules</th>   
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
       var getEnrollmentList = function(practicesgrp=null,practice = null,caremanagerid=null,fromdate1=null,todate1=null,modules=null,provider=null,activedeactivestatus=null) {
            var columns =  [
                                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                                
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        name = full['pracpracticeemr'];
                                        if(full['pracpracticeemr'] == null){
                                            name = '';
                                        }
                                          
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return name;
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
                                        if(full['pprofileimage']=='' || full['pprofileimage']==null) {
                                            return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['pfname']+' '+m_Name+' '+full['plname'];
                                        } else {
                                            return ["<img src='"+full['pprofileimage']+"' width='40px' height='25px' class='user-image' />"]+' '+full['pfname']+' '+m_Name+' '+full['plname'];
                                        }
                                       
                                    }
                                },
                                orderable: true
                                },
                                   
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        name = full['address'];
                                        if(full['address'] == null){
                                            name = '';
                                        }
                                          
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return name;
                                        }
                                    },
                                    orderable: true
                                },

                                {data: 'pdob', type: 'date-dd-mmm-yyyy', name: 'pdob', "render":function (value) {
                                    if (value === null) return "";
                                        return moment(value).format('MM-DD-YYYY');
                                    }
                                }, 
                                {data: null, mRender: function(data, type, full, meta){                                    
                                   
                                    if(data!='' && data!='NULL' && data!=undefined){
                                      totalpatient='';
                                        if(full['tptotalpatient'] == null){
                                            totalpatient = '';
                                        }
                                        else
                                        {
                                            totalpatient='('+full['tptotalpatient']+')';
                                        }
                                           if(full['userfname']==null || full['userlname']==null) {
                                            return '';
                                        }
                                        else
                                        {
                                             return  full['userfname']+' '+full['userlname']+' '+totalpatient; 
                                        }
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

                               
                                {data: 'pscreatedat', type: 'date-dd-mmm-yyyy', name: 'pscreatedat', "render":function (value) {
                                if (value === null) return "";
                                    return moment(value).format('MM-DD-YYYY');
                                }
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
                                              status = 'Deactivated';
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
                                 {data: null,
                                    mRender: function(data, type, full, meta){
                                        modules = full['mmodule'];
                                        if(full['mmodule'] == null){
                                            modules = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){ 
                                            return modules;
                                        }
                                    },
                                    orderable: true
                                },
                                                           
                              
                            ];
            
           // debugger;
             if(practicesgrp==''){practicesgrp=null;}
             if(practice==''){practice=null;} 
             if(modules==''){modules=null;}             
             if(fromdate1==''){ fromdate1=null; }
             if(todate1=='')  { todate1=null; }
             if(caremanagerid==''){caremanagerid=null;}
             if(provider==''){provider=null;}
            if(activedeactivestatus==''){activedeactivestatus=null;}

         var url = "/reports/enrollment-report/search/"+practicesgrp+"/"+practice+'/'+caremanagerid+'/'+fromdate1+'/'+todate1+'/'+modules+'/'+provider+'/'+activedeactivestatus;
         table1 = util.renderDataTable('patient-list', url, columns, "{{ asset('') }}");
              
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
      
         getPatientData();
          
          // console.log(currentdate+" test date "+firstDayWithSlashes);
            $('#fromdate').val(firstDayWithSlashes);                      
            $('#todate').val(currentdate);
            var fromdate1 = $('#fromdate').val();
            var todate1 = $('#todate').val();
            getEnrollmentList(null,null,null,fromdate1,todate1,null,null,null);      
          

          // $("[name='modules']").val(3).attr("selected", "selected").change();         
            util.getToDoListData(0, {{getPageModuleName()}});

            $("[name='practicesgrp']").on("change", function () { 
                var practicegrp_id = $(this).val(); 
                if(practicegrp_id==0){
                    //getPatientData();  
                }
                if(practicegrp_id!=''){
                    util.getPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#practices")); 
                }
                else{
                    //getPatientData();    
                }   
            });
        
           $("[name='caremanagerid']").on("change", function () {          
            $('#practicesgrp').val('').trigger('change');         
                if($(this).val() == '' || $(this).val() == '0'){
                    util.updatePracticeListWithoutOther(001, $("#practices"))
                }else{
                    util.updatePracticeListWithoutOther(parseInt($(this).val()), $("#practices"))
                }    
            });

               $("[name='practices']").on("change", function () {
                util.updatePhysicianListWithoutOther(parseInt($(this).val()), $("#physician"))

            });
                
           
        }); 

   
        $('#example-select-all').on('click', function(){                     
            var rows = table1.rows().nodes();                   
            $('input[type="checkbox"]',rows).prop('checked', this.checked);                     
        });

               
        $('#billupdate').click(function(){  
              var checkbox_value=[];
              var rowcollection =  table1.$("input[type='checkbox']:checked", {"page": "all"});
              rowcollection.each(function(index,elem){
              checkbox_value.push($(elem).val());              
                
            }); 
           
             var modules=$('#modules').val();
             var data = {
             patient_id: checkbox_value,
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
                getEnrollmentList();

            }
        });
      
      });    


    var getPatientData=function()
    {
      $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '/reports/patient-summary',
                //data: data,
                success: function (data) {
                  var TotalActiveEnreolledPatient=data.TotalActiveEnreolledPatient[0]['count'];
                  var TotalSuspendedEnrolledPatient=data.TotalSuspendedEnrolledPatient[0]['count'];
                   var Totalpatient=data.Totalpatient[0]['count'];
                    var TotalUnEnrolledPatient=data.TotalUnEnrolledPatient[0]['count'];
                     var TotalActiveEnrolledInCCM=data.TotalActiveEnrolledInCCM[0]['count'];
                     var TotalSuspendedEnrolledInCCM=data.TotalSuspendedEnrolledInCCM[0]['count'];
                      var TotalActiveEnrolledInRPM=data.TotalActiveEnrolledInRPM[0]['count'];
                      var TotalSuspendedEnrolledInRPM=data.TotalSuspendedEnrolledInRPM[0]['count'];
                     $('#totalpatient').html(Totalpatient); 
                       $('#totalenrolledpatient').html(TotalActiveEnreolledPatient + '/' +TotalSuspendedEnrolledPatient);
                         $('#totalenrolledpatientCCM').html(TotalActiveEnrolledInCCM +'/'+TotalSuspendedEnrolledInCCM);
                           $('#totalenrolledpatientRPM').html(TotalActiveEnrolledInRPM +'/'+TotalSuspendedEnrolledInRPM);
                             $('#totalunenrolledpatient').html(TotalUnEnrolledPatient);

                   // console.log("save cuccess"+data.TotalEnreolledPatient[0]['count']);
                   

                }
        });
      }

      $('#modules').change(function(){          
         var moduleid= $(this).val();
         if(moduleid=='0')
         {
            $('#fromdate').val('');
            $('#todate').val('');
            $('#fromdate').prop( "disabled", true);
            $('#todate').prop( "disabled", true);
         }
         else
         {
            $('#fromdate').prop( "disabled", false);
            $('#todate').prop( "disabled", false);
              
            $('#fromdate').val(firstDayWithSlashes);                      
            $('#todate').val(currentdate);    
         }
      });


   $('#resetbutton').click(function(){
    // console.log("test"); 
   
            $('#caremanagerid').val('').trigger('change');
            $('#practicesgrp').val('').trigger('change');
            $('#practices').val('').trigger('change');
            $('#modules').val('').trigger('change');
            $('#physician').val('').trigger('change');
            $('#fromdate').val('');
            $('#todate').val('');
            $('#modules').val();

            $('#activedeactivestatus').val('').trigger('change'); 
            $('#fromdate').val(firstDayWithSlashes);  
            $('#todate').val(currentdate);  
             
            var practicesgrp =$('#practicesgrp').val();
            var practice=$('#practices').val();
            var provider=$('#physician').val();
            var modules=$('#modules').val();
            var fromdate1=$('#fromdate').val();
            var todate1=$('#todate').val();
            var timeoption=$('#timeoption').val();
            var caremanagerid=$('#caremanagerid').val();
            var time=$('#time').val();
            var activedeactivestatus = $('#activedeactivestatus').val();

 
            //  getEnrollmentList(); 
            getEnrollmentList(practicesgrp,practice,caremanagerid,fromdate1,todate1,modules,provider,activedeactivestatus);
  });

$('#searchbutton').click(function(){

    // $('#time').removeClass("is-invalid");
    var practicesgrp =$('#practicesgrp').val();
    var practice=$('#practices').val();   
       var modules=$('#modules').val();
        var provider=$('#physician').val();
        var fromdate1=$('#fromdate').val();
         var todate1=$('#todate').val();        
         var caremanagerid=$('#caremanagerid').val();    
         var activedeactivestatus =$("#activedeactivestatus").val(); 
           var eDate = new Date(todate1);
           var sDate = new Date(fromdate1);
           if(fromdate1!= '' && todate1!= '' && sDate> eDate)
             {
             alert("Please ensure that the Enrolled To Date is greater than or equal to the Enrolled From Date.");
             return false;
             }
             else
             {
            getEnrollmentList(practicesgrp,practice,caremanagerid,fromdate1,todate1,modules,provider,activedeactivestatus);
            }

  
});

   
            jQuery('.click_id').click(function(){
                //alert($(this).attr('target'));               
                jQuery('#'+$(this).attr('target')).show();
                if($(this).attr('target')!='Enrolled')
                {
                         jQuery('#Enrolled').hide();
                }
                else
                {
                    jQuery('#Enrolled').show();
                }
              
            });
           
    
 
    </script>
@endsection