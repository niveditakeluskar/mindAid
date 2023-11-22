<?php 
    $module_id    = getPageModuleName();
    $submodule_id = getPageSubModuleName();

    $stage_id     = getFormStageId($module_id , $submodule_id, 'Worklist');

   // $stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'daily-review'); 
    $step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'Daily Review Details'); 

?>

            <div class="card-body"> 
                <div class="row"> 
                    <div class="col-md-12" id ="time_add_success_msg"></div>
                    <div class="col-md-12 steps_section">
                    <div class="title">
                        <input type="hidden" id="patient_id" value="<?php echo $patient[0]->id; ?>">
                        <input type="hidden" id="module_id" name="module_id" value="{{$module_id}}">
                        <input type="hidden" id= "component_id" name="sub_module_id" value="{{$submodule_id}}">
                        <input type="hidden" id="step_id" name="step_id" value="{{$step_id}}">              
                         <input type="hidden" id="stage_id" name="stage_id" value="{{$stage_id}}">
                               
                        <h5 class="text-center">Today's Vitals</h5>
                    </div>
                    <div class="row justify-content-center">
                        <div class="card">
                            <div class="card-body device_box">
                               
                                <span id="reading_{{$devices[$i]->id}}_0" class="day"></span>
                                <span id="unit_{{$devices[$i]->id}}_0" class="unit"></span>
                                 <i id="addressed_{{$devices[$i]->id}}_0" style="color:red"></i>
                                 <input class="checkbox chkbox_review review_{{$devices[$i]->id}}_0" type="checkbox" id="review_0"  onclick="rpmPatientDeviceReading.checkreviewclick(this,'{{$devices[$i]->id}}')"  style="top: 1px;">
                                          <input type="hidden" id="id_{{$devices[$i]->id}}_0" >
                                           <input type="hidden" id="csseffdt_{{$devices[$i]->id}}_0" >
                            </div>                               
                        </div>                        
                    </div>
                    </div>                   
                </div>
                <div class="align-self-start pt-3">
                    <div class="d-flex justify-content-center">
                         <!--  <?php //for($dayDecr=7;$dayDecr>0;$dayDecr--){  }?>-->
                                
                            <div class="mr-2">
                                <div class="card">
                                    <div class="card-header device_header">Yesterday</div>
                                    <div class="card-body device_box">
                                        
                                        <span id="reading_{{$devices[$i]->id}}_1" class="day"></span>
                                        <span id="unit_{{$devices[$i]->id}}_1" class="unit"></span>
                                        <i id="addressed_{{$devices[$i]->id}}_1"  style="color:red"></i>
                                         <input class="checkbox chkbox_review review_{{$devices[$i]->id}}_1" type="checkbox" id="review_1"  onclick="rpmPatientDeviceReading.checkreviewclick(this,'{{$devices[$i]->id}}')" >
                                          <input type="hidden" id="id_{{$devices[$i]->id}}_1">  
                                           <input type="hidden" id="csseffdt_{{$devices[$i]->id}}_1" >
                                    </div>                               
                                </div>  
                            </div>
                            <div class="mr-2" >
                                <div class="card">
                                    <div class="card-header device_header">2 Days Ago</div>
                                    <div class="card-body device_box">
                                        
                                        <span id="reading_{{$devices[$i]->id}}_2" class="day"></span>
                                        <span id="unit_{{$devices[$i]->id}}_2" class="unit"></span>
                                        <i id="addressed_{{$devices[$i]->id}}_2" style="color:red"></i>
                                           <input class="checkbox chkbox_review review_{{$devices[$i]->id}}_2" type="checkbox" id="review_2"  onclick="rpmPatientDeviceReading.checkreviewclick(this,'{{$devices[$i]->id}}')" >
                                          <input type="hidden" id="id_{{$devices[$i]->id}}_2">
                                           <input type="hidden" id="csseffdt_{{$devices[$i]->id}}_2" >
                                    </div>
                                </div>  
                            </div>    
                            <div class="mr-2" >
                                <div class="card">
                                    <div class="card-header device_header">3 Days Ago</div>
                                    <div class="card-body device_box">
                                        <span id="reading_{{$devices[$i]->id}}_3" class="day"></span>
                                        <span id="unit_{{$devices[$i]->id}}_3" class="unit"></span>
                                        <i id="addressed_{{$devices[$i]->id}}_3" style="color:red"></i>
                                        
                                           <input class="checkbox chkbox_review review_{{$devices[$i]->id}}_3" type="checkbox" id="review_3"  onclick="rpmPatientDeviceReading.checkreviewclick(this,'{{$devices[$i]->id}}')" >
                                          <input type="hidden" id="id_{{$devices[$i]->id}}_3">
                                           <input type="hidden" id="csseffdt_{{$devices[$i]->id}}_3" >
                                    </div>
                                </div>
                            </div>
                                <div class="mr-2" >
                                <div class="card">
                                    <div class="card-header device_header">4 Days Ago</div>
                                    <div class="card-body device_box">
                                          <span id="reading_{{$devices[$i]->id}}_4" class="day"></span>
                                          <span id="unit_{{$devices[$i]->id}}_4" class="unit"></span>
                                          <i id="addressed_{{$devices[$i]->id}}_4" style="color:red"></i>
                                        
                                           <input class="checkbox chkbox_review review_{{$devices[$i]->id}}_4" type="checkbox" id="review_4"  onclick="rpmPatientDeviceReading.checkreviewclick(this,'{{$devices[$i]->id}}')" >
                                          <input type="hidden" id="id_{{$devices[$i]->id}}_4">
                                           <input type="hidden" id="csseffdt_{{$devices[$i]->id}}_4" >
                                    </div>
                                </div>
                                </div>
                                <div class="mr-2" >
                                <div class="card">
                                    <div class="card-header device_header">5 Days Ago</div>
                                    <div class="card-body device_box">
                                        <span id="reading_{{$devices[$i]->id}}_5" class="day"></span>
                                        <span id="unit_{{$devices[$i]->id}}_5" class="unit"></span>
                                        <i id="addressed_{{$devices[$i]->id}}_5" style="color:red"></i>
                                       
                                           <input class="checkbox chkbox_review review_{{$devices[$i]->id}}_5" type="checkbox" id="review_5"  onclick="rpmPatientDeviceReading.checkreviewclick(this,'{{$devices[$i]->id}}')" >
                                          <input type="hidden" id="id_{{$devices[$i]->id}}_5">
                                           <input type="hidden" id="csseffdt_{{$devices[$i]->id}}_5" >
                                    </div>
                                </div>
                            </div>
                            <div class="mr-2" >
                                <div class="card">
                                    <div class="card-header device_header">6 Days Ago</div>
                                    <div class="card-body device_box">
                                        <span id="reading_{{$devices[$i]->id}}_6" class="day"></span>
                                        <span id="unit_{{$devices[$i]->id}}_6" class="unit"></span>
                                        <i id="addressed_{{$devices[$i]->id}}_6" style="color:red"></i>
                                        
                                           <input class="checkbox chkbox_review review_{{$devices[$i]->id}}_6" type="checkbox" id="review_6"  onclick="rpmPatientDeviceReading.checkreviewclick(this,'{{$devices[$i]->id}}')" >
                                          <input type="hidden" id="id_{{$devices[$i]->id}}_6">
                                           <input type="hidden" id="csseffdt_{{$devices[$i]->id}}_6" >
                                    </div>
                                </div>
                            </div>
                            <div class="mr-2" >
                                <div class="card">
                                    <div class="card-header device_header">7 Days Ago</div>
                                    <div class="card-body device_box">
                                        
                                        <span id ="reading_{{$devices[$i]->id}}_7" class="day"></span>
                                            <span id="unit_{{$devices[$i]->id}}_7" class="unit"></span>
                                            <i id="addressed_{{$devices[$i]->id}}_7" style="color:red"></i>
                                           <input class="checkbox chkbox_review review_{{$devices[$i]->id}}_7" type="checkbox" id="review_7"  onclick="rpmPatientDeviceReading.checkreviewclick(this,'{{$devices[$i]->id}}')" >
                                          <input type="hidden" id="id_{{$devices[$i]->id}}_7">
                                           <input type="hidden" id="csseffdt_{{$devices[$i]->id}}_7" >
                                    </div>
                                </div>
                            </div>
                            

                            
                            
                    </div>
                </div>
                <!-- <div class="col-md-6 steps_section" id="select-activity-2" style=""></div> -->
                <div class="align-self-start pt-3">
                    <div class="row justify-content-center">
                        <div class="col-md-4 text-center">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary btn-lg textbtn" id="textbtn_{{$devices[$i]->id}}">Text Patient</button>
                            </div> 
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary btn-lg" disabled>Message Care Manager</button>
                            </div> 
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="form-group">
                                <button  type="button" onclick="rpmPatientDeviceReading.showtable('{{$devices[$i]->id}}')" class="btn btn-primary btn-lg">View Vitals</button>
                            </div> 
                        </div>
                    </div>
                </div>
                 
                  @include('Rpm::daily-review.notes')

                <div  id="textcard_{{$devices[$i]->id}}" style="display: none;/*width: 79%;*/">               
                 @include('Rpm::daily-review.text')
                 </div>
                <!-- <div class="col-md-6 steps_section" id="select-activity-2" style=""></div> -->
                 
       
            <div class="row mb-4" id="review-table_{{$devices[$i]->id}}" style="display: none;">
                <div class="col-md-12 mb-4" id="test3">                  
                    <div class="card text-left" id="test2">                        
                        <div class="card-body"> 
                       <div class="review-table_{{$devices[$i]->id}}">
                        <button type="button" id="tableclose" class="close" style="font-size: 35px;">×</button>  
                        </div>

                          @include('Rpm::daily-review.patient-device-vitals-filter')
                              
                            @include('Theme::layouts.flash-message')
                            <div class="table-responsive">
                               
                                <div id="AddressSuccess_{{$devices[$i]->id}}"></div> 
                                <div id="datatableSuccess_{{$devices[$i]->id}}"></div>
                                <table id="monthlyreviewlist_{{$devices[$i]->id}}" class="display table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th width="35px">Sr No.</th>
                                        <th width="35px">View Details</th> 
                                        <th>Vitals</th>
                                        <th>Range</th>
                                        <th>Reading</th>
                                        <th width="97px">Date</th> 
                                        <th width="97px">Time</th>
                                        <th>Reviewed</th> 
                                        <th>Addressed</th> 
                                        <th></th>                                        
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
 
