@extends('Theme::layouts_2.to-do-master')
@section('page-title')
    Assigned Patients - 
@endsection
@section('page-css')
  
@endsection 
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Assigned Patients</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

<div _ngcontent-rei-c8="" class="row">
   
   <div _ngcontent-rei-c8="" style="width: 22%;margin-left: 1%; margin-right: 2%;" >
   <a href="{{ route('patients.assignment.totalpatients') }}" target="blank">
      <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Myspace"></i>
            <div _ngcontent-rei-c8="" class="content">
               <!-- <p _ngcontent-rei-c8="" class="text-muted" data-toggle="modal"  data-target="#mypatientModal" target="allpatient" style="width: 86px;height: 30px;">Patients</p> -->
               <p _ngcontent-rei-c8="" class="text-muted" style="width: 86px;height: 30px;">Patients</p>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" id="totalpatient"></p>
            </div>
         </div>
      </div>
      </a>
   </div>

   <div _ngcontent-rei-c8=""  style="width: 22%;margin-right: 2%;" >
   <a href="{{ route('patients.assignment.totalcaremanager') }}" target="blank">
      <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Add-UserStar"></i>
            <div _ngcontent-rei-c8="" class="content">
               <p _ngcontent-rei-c8="" class="text-muted"  style="width: 55px;height: 30px;">Care Manager</p> 
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" id="totalcaremaneger"></p>
            </div>
         </div>
      </div>
      </a>
   </div>
   <div _ngcontent-rei-c8="" style="width: 22%;margin-right: 2%;">
   <a href="{{ route('patients.assignment.totalassignedpatient') }}" target="blank">
      <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Checked-User"></i>
            <div _ngcontent-rei-c8="" class="content">
               <p _ngcontent-rei-c8="" class="text-muted"  style="width: 99px;height: 30px;">Assigned Patient</p>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" id="totalassignedpatient"></p>
            </div>
         </div>
      </div>
      </a>
   </div> 
   <div _ngcontent-rei-c8="" style="width: 22%;margin-right: 2%;">
   <a href="{{ route('patients.assignment.totalnonassignedpatient') }}" target="blank">
      <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
         <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Remove-User"></i>
            <div _ngcontent-rei-c8="" class="content">
               <p _ngcontent-rei-c8="" class="text-muted" style="width: 99px;height: 30px;">Non Assigned Patient</p>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2"  id="totalnonassignedpatient"></p>
            </div>
         </div>
      </div>
      </a>
   </div>
  </div>
  
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                <form id="daily_report_form" name="daily_report_form"  action ="">
                @csrf
                <div class="form-row">
                
                    <div class="col-md-3 form-group mb-2">
                        <label for="care_manager_id">Care Manager</label>
                       @selectorguser("care_manager_id", ["id" => "care_manager_id", "placeholder" => "Select Care Manager","class" => "select2"])
                       <!-- selectcaremanagerwithAll -->
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="practicename">Practice</label>                        
                        <!-- @selectpracticeswithAll("practices", ["class" => "select2","id" => "practices"]) -->
                        <select class="custom-select show-tick select2 classpractices" name="practices" id="practices">
                          <option value="">Select Practice</option> 
                          <optgroup label="Active Practices">
                          <?php foreach ($active_pracs as $active){?>
                          <option value="{{ $active->id }}">{{ $active->name }}</option>
                          <?php }?>
                          </optgroup>
                          <optgroup label="Inactive Practices">
                          <?php foreach ($inative_pracs as $inactive){?>
                          <option value="{{ $inactive->id }}">{{ $inactive->name }}</option>
                          <?php }?> 
                          </optgroup>
                        </select>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="provider">Provider</label>
                        @selectpracticesphysician("provider",["class" => "select2","id" => "physician"])
                    </div>
                    <div class="col-md-2 form-group mb-3">  
                          <label for="patientStatus">Status</label> 
                          <select id="patientStatus" name="patientStatus" class="custom-select show-tick" >
                            <option value="" selected>All (Activated,Suspendend,Deactivated)</option> 
                            <option value="1">Active</option>
                            <option value="0">Suspended</option> 
                            <option value="2" >Deactivated</option>                           
                            <!-- <option value="3" >Deceased</option> -->
                          </select>                          
                    </div> 
                    </div>
                   <div class="form-row">
                   
                    <div class="col-md-3 form-group mb-3"> 
                    <!-- <label for="timeoption">Time option</label> -->
                          <select id="timeoption"  class="custom-select show-tick" style="margin-top: 23px;">
                            <option value="2">Greater than</option>
                            <option value="1" selected>Less than</option>
                            <option value="3">Equal to</option>
                            <option value="4">All</option>
                          </select>                          
                    </div>
                     <div class="col-md-2 form-group mb-2">                        
                          <label for="time">Time</label>
                               @text("time", ["id" => "time", "placeholder" => "hh:mm:ss"])                       
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
                <div class="alert alert-success" id="assign-success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>Patient assigned successfully! </strong><span id="text"></span>
                </div>
                <div class="table-responsive">
                  <table id="patient-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="35px">Sr No.</th>                        
                            <th >Practice EMR</th>    
                            <th width="205px">Patient</th>
                            <th width="97px">DOB</th>
                            <th width="97px">Practice</th>
                            <th width="97px">CM</th>
                            <th width="">Provider</th>                  
                            <th >Last Contact</th>
                            <th width="75px">Time Spent</th>
                            <th width="75px">Enrolled Modules</th>
                            <th width="75px">Assign CM</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
            </div>
            <!--div class="card-footer">
               <div class="mc-footer">
                  <div class="row"> 
                     <div class="col-lg-12 text-right">
                        <button type="submit" class="btn btn-primary m-1 save_user_patients" id="user_patients_save" >Save</button>
                     </div>
                  </div>
               </div>
            </div-->
        </div>
    </div>

</div>
<div id="app">
</div>

<div class="container">
  <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">
        <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                 
                    <!-- TEXT-2 -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body "> 
                                    <div class="row">
                                        <div class="col-md-12">  
                                            <div class="form-group" id="nonassignpatient" name="nonassignpatient" > 
                                               <h1>Non-Assigned Patients</h1>
                                                
                                               <div class="card-body">
                                                    @include('Theme::layouts.flash-message')

                                                    <div class="form-row">
                                                        <div class="col-md-6 form-group">  
                                                        <label for="practicename">Practice</label>                        
                                                        @selectpracticeswithAll("practices", ["class" => "select2","id" => "practicesone"])
                                                        </div>

                                                        <div class="col-md-1 form-group mb-3">
                                                        <button type="button" id="searchbuttonone" class="btn btn-primary mt-4">Search</button>
                                                        </div>
                                                    </div>
                                                 
                                                    <div class="table-responsive">
                                                        <table id="nonassigned-patient-list" class="display table table-striped table-bordered" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="35px">Sr No.</th>                        
                                                                <!-- <th >Practice EMR</th>      -->
                                                                <th width="205px">Patient</th>
                                                                <th width="97px">DOB</th>
                                                                <th width="97px">Practice</th>
                                                               
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div> 

<div class="container">
  <!-- Modal -->
    <div class="modal fade" id="myassignedpatientModal" role="dialog">
        <div class="modal-dialog modal-lg">
        <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                 
                    <!-- TEXT-2 -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body "> 
                                    <div class="row">
                                        <div class="col-md-12">  
                                            <div class="form-group" id="assignpatient" name="assignpatient" > 
                                               <h1>Assigned Patients</h1>
                                               
                                               <div class="card-body">
                                                    @include('Theme::layouts.flash-message')

                                                    <div class="form-row">
                                                        <div class="col-md-6 form-group">  
                                                        <label for="practicename">Practice</label>                        
                                                        @selectpracticeswithAll("practices", ["class" => "select2","id" => "practicestwo"])
                                                        </div>

                                                        <div class="col-md-1 form-group mb-3">
                                                        <button type="button" id="searchbuttontwo" class="btn btn-primary mt-4">Search</button>
                                                        </div>
                                                    </div>
                                                
                                                    <div class="table-responsive">
                                                        <table id="assigned-patient-list" class="display table table-striped table-bordered" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="35px">Sr No.</th>                        
                                                                <!-- <th >Practice EMR</th>      -->
                                                                <th width="205px">Patient</th>
                                                                <th width="97px">DOB</th>
                                                                <th width="97px">Practice</th>
                                                               
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
  <!-- Modal -->
    <div class="modal fade" id="mycaremangerModal" role="dialog">
        <div class="modal-dialog modal-lg">
        <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                 
                    <!-- TEXT-2 -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body "> 
                                    <div class="row">
                                        <div class="col-md-12">  
                                            <div class="form-group" id="caremanager" name="caremanager" > 
                                               <h1>CareManager</h1>
                                               
                                               <div class="card-body">
                                                    @include('Theme::layouts.flash-message')

                                                    <div class="form-row">
                                                        <div class="col-md-6 form-group">  
                                                        <label for="practicename">Practice</label>                        
                                                        @selectpracticeswithAll("practices", ["class" => "select2","id" => "practicesthree"])
                                                        </div>

                                                        <div class="col-md-1 form-group mb-3">
                                                        <button type="button" id="searchbuttonthree" class="btn btn-primary mt-4">Search</button>
                                                        </div>
                                                    </div>
                                                
                                                    <div class="table-responsive">
                                                        <table id="cm-list" class="display table table-striped table-bordered" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="35px">Sr No.</th>                        
                                                                <th width="205px">CareManager</th>
                                                                <th width="97px">Contact No</th>
                                                                <th width="97px">No of Patients</th>
                                                                  
                                                               
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
  <!-- Modal -->
    <div class="modal fade" id="mypatientModal" role="dialog">
        <div class="modal-dialog modal-lg">
        <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                 
                    <!-- TEXT-2 -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body "> 
                                    <div class="row">
                                        <div class="col-md-12">  
                                            <div class="form-group" id="allpatient" name="allpatient" > 
                                               <h1>Patients</h1>
                                               
                                               <div class="card-body">
                                                    @include('Theme::layouts.flash-message')

                                                    <div class="form-row">
                                                        <div class="col-md-6 form-group">  
                                                        <label for="practicename">Practice</label>                        
                                                        @selectpracticeswithAll("practices", ["class" => "select2","id" => "practicesfour"])
                                                        </div>

                                                        <div class="col-md-1 form-group mb-3">
                                                        <button type="button" id="searchbuttonfour" class="btn btn-primary mt-4">Search</button>
                                                        </div>
                                                    </div>
                                                
                                                    <div class="table-responsive">
                                                        <table id="all-patient-list" class="display table table-striped table-bordered" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="35px">Sr No.</th>                        
                                                                <!-- <th >Practice EMR</th>      -->
                                                                <th width="205px">Patient</th>
                                                                <th width="97px">DOB</th>
                                                                <th width="97px">Practice</th>
                                                               
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection 

@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    <script src="{{asset(mix('assets/js/laravel/taskManage.js'))}}"></script>
    <script type="text/javascript">
        var getCareManagerList = function(practice = null,provider=null,time=null,care_manager_id=null,timeoption=null,patientstatus=null) {
            var columns =  [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: null,
                     mRender: function(data, type, full, meta){
                         practice_emr = full['pracpracticeemr'];
                         if(full['pracpracticeemr'] == null){
                             practice_emr = '';
                         }
                         if(data!='' && data!='NULL' && data!=undefined){
                             return practice_emr;
                         }
                     },
                     orderable: true
                 },
                { data: null, 
                    mRender: function(data, type, full, meta){
                        m_Name = full['pmname'];
                        if(full['pmname'] == null){
                            m_Name = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['pprofileimg']=='' || full['pprofileimg']==null) {
                                return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['pfname']+' '+m_Name+' '+full['plname'];
                            } else {
                                return ["<img src='"+full['pprofileimg']+"' width='40px' height='25px' class='user-image' />"]+' '+full['pfname']+' '+m_Name+' '+full['plname'];
                            }
                        }
                    },
                    orderable: true
                },
                { data: 'pdob', type: 'date-dd-mmm-yyyy', name: 'pdob', 
                    "render":function (value) {
                        if (value === null) return "";
                        return  moment(value).format('MM-DD-YYYY');
                    }
                },
                { data:null,
                    mRender: function(data, type, full, meta){
                        name = full['cmname'];
                        if(full['cmname'] == null){
                            name = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return name;
                        }
                    },
                    orderable: true
                },
               
                { data: null, 
                    mRender: function(data, type, full, meta){
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['userfname']==null || full['userfname']==null) {  
                                return '';
                            } else {
                                // return  full['userfname']+' '+full['userfname'] ; 
                                return  full['userfname']+' '+full['userlname']+' ('+full['patient_count']+')'; 
                            }
                        }
                    },
                    orderable: true
                },
                { data:null,
                    mRender: function(data, type, full, meta){
                        name = full['prname'];
                        if(full['prname'] == null){
                            name = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return name;
                        }
                    },
                    orderable: true
                },
                { data: 'csslastdate',type: 'date-dd-mm-yyyy', name: 'csslastdate'
                // ,
                //     "render":function (value) {
                //         if (value === null) return "";
                //         return util.viewsDateFormat(value);
                //     }
                },  
                { data:null,
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
                { data:null,
                    mRender: function(data, type, full, meta){
                        module = full['module'];
                        if(full['module'] == null){
                            module = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return module;
                        }
                    },
                    orderable: true
                },  
                {data: 'action', name: 'action', orderable: false, searchable: false},   
            ];
            // debugger;
            if(practice==''){practice=null;} 
            if(provider==''){provider=null;}            
            if(time==''){ time=null;}
            if(time=='00:00:00'){ time='00:00:00';}
            if(timeoption=='')  { timeoption=null; }
            if(care_manager_id=='')
            {
                care_manager_id=null;
            }
            if(patientstatus=='')
            {
                patientstatus=null;
            }
            var url = "/patients/patients-assignment/search/"+practice+'/'+provider+'/'+time+'/'+care_manager_id+'/'+timeoption+'/'+patientstatus;
            // console.log(url);
            util.renderDataTable('patient-list', url, columns, "{{ asset('') }}");
              
        }

        var getNonassignedpatientlist =function(practice = null) {
            var columns =  [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: null, 
                    mRender: function(data, type, full, meta){
                        m_Name = full['mname'];
                        if(full['mname'] == null){
                            m_Name = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['profile_img']=='' || full['profile_img']==null) {
                                return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                            } else {
                                return ["<img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                            }
                        }
                    },
                    orderable: true
                },
                { data: 'dob', type: 'date-dd-mmm-yyyy', name: 'dob', 
                    "render":function (value) {
                        if (value === null) return "";
                        return util.viewsDateFormat(value);
                    }
                },
                { data:null,
                    mRender: function(data, type, full, meta){
                        name = full['name'];
                        if(full['name'] == null){
                            name = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return name;
                        }
                    },
                    orderable: true
                },
               

            ];
            // debugger;
            if(practice==''){practice=null;} 
           
            // var url = "/patients/patients-assignment/search/"+practice+'/'+provider+'/'+time+'/'+care_manager_id+'/'+timeoption;
            //var url = "/patients/patients-assignment/nonassignedpatients/"+practice;
            var url ="/task-management/patients-assignment/nonassignedpatients/"+practice;
            // console.log(url);
            util.renderDataTable('nonassigned-patient-list', url, columns, "{{ asset('') }}");
        }

        var getassignedpatientlist =function(practice = null) {
            var columns =  [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: null, 
                    mRender: function(data, type, full, meta){
                        m_Name = full['mname'];
                        if(full['mname'] == null){
                            m_Name = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['profile_img']=='' || full['profile_img']==null) {
                                return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                            } else {
                                return ["<img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                            }
                        }
                    },
                    orderable: true
                },
                { data: 'dob', type: 'date-dd-mmm-yyyy', name: 'dob', 
                    "render":function (value) {
                        if (value === null) return "";
                        return util.viewsDateFormat(value);
                    }
                },
                { data:null,
                    mRender: function(data, type, full, meta){
                        name = full['name'];
                        if(full['name'] == null){
                            name = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return name;
                        }
                    },
                    orderable: true
                },
               

            ];
            // debugger;
            if(practice==''){practice=null;} 
           
            // var url = "/patients/patients-assignment/search/"+practice+'/'+provider+'/'+time+'/'+care_manager_id+'/'+timeoption;
            //var url = "/patients/patients-assignment/nonassignedpatients/"+practice;
            var url ="/task-management/patients-assignment/assignedpatients/"+practice;
            
            // console.log(url);
            util.renderDataTable('assigned-patient-list', url, columns, "{{ asset('') }}");
        }

        var getallpatientlist =function(practice = null) {
            var columns =  [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: null, 
                    mRender: function(data, type, full, meta){
                        m_Name = full['mname'];
                        if(full['mname'] == null){
                            m_Name = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['profile_img']=='' || full['profile_img']==null) {
                                return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                            } else {
                                return ["<img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                            }
                        }
                    },
                    orderable: true
                },
                { data: 'dob', type: 'date-dd-mmm-yyyy', name: 'dob', 
                    "render":function (value) {
                        if (value === null) return "";
                        return util.viewsDateFormat(value);
                    }
                },
                { data:null,
                    mRender: function(data, type, full, meta){
                        name = full['name'];
                        if(full['name'] == null){
                            name = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return name;
                        }
                    },
                    orderable: true
                },
               

            ];
            // debugger;
            if(practice==''){practice=null;} 
           
            // var url = "/patients/patients-assignment/search/"+practice+'/'+provider+'/'+time+'/'+care_manager_id+'/'+timeoption;
            //var url = "/patients/patients-assignment/nonassignedpatients/"+practice;
            var url ="/task-management/patients-assignment/allpatients/"+practice;
            
            // console.log(url);
            util.renderDataTable('all-patient-list', url, columns, "{{ asset('') }}");
        }


        var getCMlist=function(practice=null)
        {
            var columns =  [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: null, 
                    mRender: function(data, type, full, meta){
                        m_Name ="";
                       
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['profile_img']=='' || full['profile_img']==null) {
                                return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['f_name']+' '+m_Name+' '+full['l_name'];
                            } else {
                                return ["<img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' '+full['f_name']+' '+m_Name+' '+full['l_name'];
                            }
                        }
                    },
                    orderable: true
                },
                { data:null,
                    mRender: function(data, type, full, meta){
                        name = '-';
                       return name;
                    },
                    orderable: true  
                },
                { data:null,
                    mRender: function(data, type, full, meta){
                        name = full['count'];
                        if(full['count'] == null){
                            name = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return name;
                        }
                    },
                    orderable: true
                },
               

            ];
            // debugger;
            if(practice==''){practice=null;} 
           
            // var url = "/patients/patients-assignment/search/"+practice+'/'+provider+'/'+time+'/'+care_manager_id+'/'+timeoption;
            //var url = "/patients/patients-assignment/nonassignedpatients/"+practice;
            var url ="/task-management/patients-assignment/cmlist/"+practice;
            
            // console.log(url);
            util.renderDataTable('cm-list', url, columns, "{{ asset('') }}"); 
        }

       



        $(document).ready(function() {
           // getallpatientlist();
            //getassignedpatientlist();
            //getNonassignedpatientlist(); 
            //getCMlist();
            taskManage.init();
            $('form[name="daily_report_form"] #practices').on('change', function () {
		        util.updatePcpPhysicianList(parseInt($(this).val()), $("form[name='daily_report_form'] #physician")); //added by priya 25feb2021 for remove other option
	        });
          $('#time').val('00:20:00');
          getPatientData();
            //getCareManagerList();
            // $("[name='practices']").on("change", function () { ===cmnt on 17th jun 22 priya
            //     if($(this).val() == '' || $(this).val() == 0){
            //         util.updatePhysicianListWithoutOther(001, $("#physician"))
            //     }else{
            //         util.updatePhysicianListWithoutOther(parseInt($(this).val()), $("#physician"))
            //     }                 
            // });
            $("[name='care_manager_id']").on("change", function () {
                if($(this).val() == '' || $(this).val() == 0){
                    util.updatePracticeListWithoutOther(001, $("#practices"))
                }else{
                    util.updatePracticeListWithoutOther(parseInt($(this).val()), $("#practices"))
                }
                
            });

           $("#timeoption").val('4').trigger('change');
            //$("[name='modules']").val(3).attr("selected", "selected").change();          
           // util.getToDoListData(0, {{getPageModuleName()}});
          //  $(".patient-div").hide(); // to hide patient search select        
        }); 

   
       $('#example-select-all').on('click', function(){                     
         var rows = table1.rows().nodes();                   
          $('input[type="checkbox"]',rows).prop('checked', this.checked);                     
       });

       function assignPatient(patient,user){
           var data = {
                patient: patient,
                user: user         
            }
            $.ajax({
            type: 'POST',
            url: '/patients/task-management-user-form',
            data: data,
            success: function (data) {
                $("#assign-success-alert").show();
                var scrollPos = $(".main-content").offset().top;
                $(window).scrollTop(scrollPos);
                getPatientData();
                //setTimeout($("#assign-success-alert").hide(), 30000);
            }
        });

       }            

               
    $('#billupdate').click(function(){  
   var checkbox_value=[];
              var rowcollection =  table1.$("input[type='checkbox']:checked", {"page": "all"});
            rowcollection.each(function(index,elem){
                checkbox_value.push($(elem).val());              
                
            }); 
           // var data=
            var modules=$('#modules').val();
          //  console.log(checkbox_value);
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
                getCareManagerList();

            }
        });
      
      });    

 $('#resetbutton').click(function(){ 	
 $('#care_manager_id').val('').trigger('change');
 $('#practices').val('').trigger('change');
 $('#physician').val('').trigger('change');
 $('#time').val('00:00:00'); 
 $('#timeoption').val("4");
 $('#patientStatus').val('').trigger('change'); 
 //$('#patientStatus option[value=""]').attr("selected","selected");
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
    var practice=$('#practices').val();
   // alert(practice+"testpractice");
     var provider=$('#physician').val();
      //var modules=$('#modules').val();
       //var fromdate1=$('#fromdate').val();
         var patientstatus=$('#patientStatus').val();
         var timeoption=$('#timeoption').val();
         //console.log(fromdate1+"   "+todate1);
       var care_manager_id=$('#care_manager_id').val();
       var time=$('#time').val();
         if(time == '')
            {
                time = '00:00:00';
            }
        var time_regex = new RegExp(/(?:[01]\d|2[0123]):(?:[012345]\d):(?:[012345]\d)/);
      
            if(time!="")
            {
              if(time_regex.test(time)){
                console.log(time.length);
                  if(time.length==8)
                        {
            
                getCareManagerList(practice,provider,time,care_manager_id,timeoption,patientstatus);
               }
               else
               {
                $('#time').addClass("is-invalid");
                $('#time').next(".invalid-feedback").html("Please enter time in HH:MM:SS formate.");
               }
             }
             else
             {
                $('#time').addClass("is-invalid");
                $('#time').next(".invalid-feedback").html("Please enter time in HH:MM:SS formate.");
             }
         }
         else
         {        getCareManagerList(practice,provider,time,care_manager_id,timeoption,patientstatus);
}


 
});

$('#searchbuttonone').click(function(){

    var practice=$('#practicesone').val();
    getNonassignedpatientlist(practice);  
               
});

$('#searchbuttontwo').click(function(){
    
    var practice=$('#practicestwo').val();
    getassignedpatientlist(practice);  
               
});

$('#searchbuttonthree').click(function(){
    
    var practice=$('#practicesthree').val();
    getCMlist(practice);  
               
});

$('#searchbuttonfour').click(function(){
    
    var practice=$('#practicesfour').val();
    getallpatientlist(practice);  
               
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
               var Totalpatient=data.Totalpatient[0]['count'];
                var TotalCareManeger=data.TotalCareManeger[0]['count'];
                 var ToltalAssignedPatient=data.ToltalAssignedPatient[0]['count'];
                 var totalPatientActive=data.totalPatientActive[0]['count'];
                  var ToltalNonAssignedPatient=totalPatientActive-ToltalAssignedPatient;
                 
                 $('#totalpatient').html(Totalpatient);
                   $('#totalcaremaneger').html(TotalCareManeger);
                     $('#totalassignedpatient').html(ToltalAssignedPatient);
                       $('#totalnonassignedpatient').html(ToltalNonAssignedPatient);
                         

               // console.log("save cuccess"+data.TotalEnreolledPatient[0]['count']);
               

            }
        });
}
</script>
@endsection  