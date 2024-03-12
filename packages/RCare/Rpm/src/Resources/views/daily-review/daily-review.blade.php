@extends('Theme::layouts_2.to-do-master')
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
           <h4 class="card-title mb-3">RPM Worklist</h4>  
        </div>
         <div class="col-md-1">
        </div>
    </div>           
</div>
<div class="separator-breadcrumb border-top"></div>
<div id="success"></div>
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">  
             
        <?php 
         $component_id = getPageSubModuleName(); 
         $module_id = getPageModuleName();
         $stage_id =  getFormStageId($module_id , $component_id, 'Worklist');
         $step_id =  getFormStepId($module_id , $component_id, $stage_id, 'Worklist-Reviewed'); 
        ?> 
         
            <div class="card-body">
                <form>
                @csrf
                <div class="form-row">
                        <div class="col-md-3 form-group mb-3">  
                            <label for="practicegrp">{{config('global.practice_group')}}</label>
                            @selectrpmpracticesgrouprolebased("practicesgrp", ["class" => "select2","id" => "practicesgrp"]) 
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label for="practicename">Practice</label>                  
                            @selectrpmpracticesrolebased("practices",["id" => "practices", "class" => "form-control show-tick select2"])   
                        </div>
                        
                        <div class="col-md-3 form-group mb-3">
                            <label for="provider">Provider</label>
                            @selectpracticesphysician("provider",["id" => "physician","class"=>"custom-select show-tick select2"])
                        </div>
                        <div class="col-md-3 form-group mb-3" id="patientdiv" style="display:none">
                            <label for="patient">Patient</label>
                            @selectpatient("patient",["id" => "patient","class"=>"custom-select show-tick select2"])
                        </div>
                        
                    </div>
                    <div class="form-row"> 
                        <div class="col-md-3 form-group mb-3" id="cmdiv">
                            <label for="caremanager">Care Manager</label>
                            @selectorguser("caremanagerid", ["class" => "select2","id" => "caremanagerid"])
                            <!-- selectcaremanagerNone -->
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label for="month">From Date</label>
                            @date('fromdate',["id" => "fromdate"]) 
                       </div> 
                       <div class="col-md-3 form-group mb-3">
                            <label for="month">To Date</label>
                            @date('todate',["id" => "todate"]) 
                       </div> 
                       <div class="col-md-3 form-group mb-3">
                            <label for="status">Review Status</label>
                            
                                <select id="reviewedstatus" name="review" class="custom-select show-tick select2">
                                    <option value="0" selected>Not Reviewed</option>
                                    <option value="1">Reviewed</option>  
                                </select> 
                       </div>
                        <div class="col-md-1">
                            <button type="button" id="searchbutton" class="btn btn-primary mt-4">Search</button>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-primary mt-4" id="resetbutton">Reset</button>
                        </div>
                    </div>
                
                </form>
            </div>
        </div>
    </div>
</div>


<div id="app"></div>
 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">           
                @include('Theme::layouts.flash-message')
                <div>
                   <a href="#"  title="Addressed" ><button type="button" id="addressbutton" class="btn btn-primary float-right mr-3">Addressed</button></a>
              </div>
                <div class="table-responsive">
                    <table id="dailyreviewlist" class="display datatable table-striped table-bordered nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th >Sr No.</th>
                            <th >Patient</th>
                            <th >Daily Review</th>
                            <th>DOB</th>
                            <th >Clinic</th>
                            <th >Vital</th>
                            <th >Range</th>
                            <th >Reading</th> 
                            <th width="15%">Date</th> 
                            <th width="15%">Time</th>
                            <th>Reviewed<br/><input name="select_all" value="1" id="example-select-all" type="checkbox" /></th>
                            <th>Addressed</th>
                            <th>Last contact Date</th>
                            <th width="10%">Provider</th>                            
                            <th width="10%">Care Manager</th> 
                            <th width="5%">View Details</th>  
                            <th></th>
                            <!-- <th width="75px">Minutes Spent</th> -->  
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
<div>

  <input type="hidden"  value="{{$mineffdate}}" id="mineffdate"/>
  <input type="hidden"  value="{{$maxeffdate}}" id="maxeffdate"/>
  <input type="hidden"  value="{{$roleid}}" id="roleid"/>
  <input type="hidden"  value="{{$component_id}}" id="component_id"/>
  <input type="hidden"  value="{{$module_id}}" id="module_id"/>
  <input type="hidden" id="stage_id" value="{{$stage_id }}" /> 
  <input type="hidden" id="step_id" value="{{$step_id }}" /> 
</div>

<div class="modal fade" id="rpm_cm_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style=" width: 800px!important; margin-left: 280px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Care Manager Notes</h4>
                <button type="button" class="close modalcancel" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <form action="{{route('save.alert.rpm.notes')}}" name="rpm_cm_form" id="rpm_cm_form" method="POST" class="form-horizontal">
                <!-- <form action="{{route('save.address.rpm.notes')}}"  name="rpm_cm_form" id="rpm_cm_form" method="POST" class="form-horizontal"> -->
                    {{ csrf_field() }}
                        <?php 
                             $module_id    = getPageModuleName();
                             $submodule_id = getPageSubModuleName();
                             $stage_id = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Worklist');
                             $add_step_id = getFormStepId(getPageModuleName(), getPageSubModuleName(),$stage_id, 'Worklist-Addressed'); 
                        ?>
                    
                        <div class="row">
                            <div class="col-md-12 form-group mb-3">
                            <label for="patientname" id="patientname"></label>&nbsp; &nbsp;
                                <label for="patientvital" id="patientvital"></label><br>
                                <!-- <input type="hidden" name="start_time" value="00:00:00">
                                <input type="hidden" name="end_time" value="00:00:00"> -->
                                <!-- <input type="hidden" name="hd_timer_start" id="hd_timer_start"> -->
                                
                                <input type="hidden" name="rpm_observation_id" id="rpm_observation_id" />
                                <input type="hidden" name="care_patient_id" id="care_patient_id" />
                                <input type="hidden" name="p_id" id="p_id" />

                                <input type="hidden" name="vital" id="vital" />  
                                <input type="hidden" name="unit" id="unit" />  
                                <input type="hidden" name="csseffdate" id="csseffdate" />   
                                <input type="text" id= "module_id" name="module_id" value="{{$module_id}}">
                                <input type="text" id= "component_id" name="component_id" value="{{$submodule_id}}">
                                <input type="text" id= "stage_id" name="stage_id" value="{{$stage_id}}">
                                <input type="text" id= "step_id" name="add_step_id" value="{{$add_step_id}}">
                                <input type="text" name="table" id="table" />
                                <input type="hidden" name="formname" id="formname" /> 
 
                                <input type="hidden" name="hd_timer_start" id="hd_timer_start" value="00:00:00"/>

                                <label for="Notes">Notes<span style="color: red">*</span></label>
                                <div class="forms-element">
                                  @text("notes",["id"=>"notes"])
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>    
                         </div>  
                    <div class="card-footer">  
                        <div class="mc-footer">
                            <div class="row">
                                <div class="col-lg-6 text-left" id="gotomm"></div>
                                <div class="col-lg-6 text-right">
                                     <button type="submit" id="addresssubmit" class="btn  btn-primary m-1">Submit</button>
                                    <button type="button" class="btn btn-outline-secondary m-1 modalcancel" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div> 


@endsection
@section('page-js')   
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>  
    <script src="{{asset(mix('assets/js/laravel/rpmworklist.js'))}}"></script>
    <script src="{{asset(mix('assets/js/laravel/rpmlist.js'))}}"></script>

    <!--==== Comment by anand 25-Nov-21 ====-->
    <!--<link href="https://nightly.datatables.net/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
    <script src="https://nightly.datatables.net/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.js"></script>
    <link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" /> 
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.colVis.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>-->
  
   
   <script src="{{asset('assets/js/external-js/export-child-table/buttons.html5.js')}}"></script> 
   <script src="{{asset('assets/js/external-js/export-child-table/dataTables.buttons.min.js')}}"></script> 
   <script src="{{asset('assets/js/external-js/export-child-table/buttons.colVis.min.js')}}"></script> 
   <script src="{{asset('assets/js/external-js/export-child-table/jszip.min.js')}}"></script> 
     

    <script type="text/javascript">  
   
        $(document).ready(function() {
             rpmlist.init();
             rpmworklist.init();
            util.getToDoListData(0, {{getPageModuleName()}});    
            util.getAssignPatientListData(0, 0);
    
        });
    </script>  
@endsection