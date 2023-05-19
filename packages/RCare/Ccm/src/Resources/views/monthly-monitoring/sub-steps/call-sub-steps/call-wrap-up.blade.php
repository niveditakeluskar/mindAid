<div class="row">
    <div class="col-lg-12 mb-3">
        <!-- <div class="mb-3" ><b>Call Wrap up</b></div> -->
        <?php $segment =  Request::segment(1);?>
        <div class="card"> 
            <div class="card-body">
                <div class="mb-4 ml-4">
                    <div class="alert alert-success" id="callwrapform-success-alert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>Call wrap-up data successfully! </strong><span id="text"></span>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-3"><h6><b>Call Notes for Review and Approval</b></h6></div>
                        <?php if($segment=='rpm'){?>
                        <div class="col-md-3">
                            <select class ="custom-select show-tick mr-4" id="rpm-report">
                                <option>Select Report</option>
                                <!-- <option value="1">Summary Report</option> -->
                                <option value="2">Daily History Report</option>
                               <!-- <option value="3">Alert History Report</option>-->
                            </select>
                        </div> 
                        <?php }?>
                        <div class="col-md-3">
                            <a href="/ccm/monthly-monitoring/call-wrap-up-word/{{$patient[0]->id}}" class="btn btn-primary" target="_blank">Care Manager Notes Word Format</a>   <!-- Docs Care Plan -->
                        </div>
                    </div>
                </div>
                <div class="row m-1">
                    <div class="col-12">
                        <div class="table-responsive">
                            @csrf     
                            <table id="callwrap-list" class="display table table-striped table-bordered" style="width: 100%; border: 1px solid #00000029;">
                                <thead>
                                    <tr> 
                                        <th>Seq.</th>
                                        <th>Topic</th>
                                        <th>CareManager Notes</th>
                                        <th>Action Taken</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody> 
                            </table>
						</div>
                    </div>
                </div>
                    
                <form id="callwrapup_form" name="callwrapup_form" action="{{ route("monthly.monitoring.call.callwrapup") }}" method="post"> 
                    @csrf 
                    <?php
                        $module_id = getPageModuleName();
                        $submodule_id = getPageSubModuleName();
                        $stage_id = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Call Wrap Up'); //Call
                        // $step_id = getFormStepId($module_id , $submodule_id, $stage_id, 'Call Wrap Up');
                    ?>
                    <input type="hidden" name="uid" value="{{$patient[0]->id}}" />
                    <input type="hidden" name="patient_id" value="{{$patient[0]->id}}" />
                    <input type="hidden" name="start_time" value="00:00:00">
                    <input type="hidden" name="end_time" value="00:00:00">
                    <input type="hidden" name="module_id" value="{{ getPageModuleName() }}" />
                    <input type="hidden" name="component_id" value="{{ getPageSubModuleName() }}" />
                    <input type="hidden" name="stage_id" value="{{$stage_id}}" />
                    <input type="hidden" name="step_id" value="0">
                    <input type="hidden" name="form_name" value="callwrapup_form">
                    <div class="row ml-3"> 
                        <div class="col-md-12 form-group">
                            <div class=" forms-element">
                                <label class="col-md-12">EMR Monthly Summary</label>
                                <!-- <textarea class="form-control" id="emr_monthly_summary1"></textarea> -->
                                <!-- @text("emr_monthly_summary", ["id"=>"emr_monthly_summary"]) aria-label="With textarea"-->
                                <textarea  class="form-control" cols="90"  name="emr_monthly_summary[]" id="callwrap_up_emr_monthly_summary"></textarea>
                                <div class="invalid-feedback"></div>  
                            </div>
                        </div> 

                   
                        

                        <div class="col-md-12" style="margin-bottom: 40px;">
                            <div class="row">
                                <div class="col-md-3">
                                   <b><label style="margin-left: 20px; color: #69aac2;">Additional CCM Notes :</label></b>
                                   <!-- <i id="addnotes" type="button" qno="1" class="add i-Add" style="color: rgb(44, 184, 234); font-size: 25px;"></i> -->

                                </div>
                                <div class="col-md-1">
                                    <i id="addnotes" type="button" qno="1" class="add i-Add" style="color: rgb(44, 184, 234); font-size: 25px;float: left;"></i>
                                </div>
                            </div>
                            <div class="row" id="additional_monthly_notes" style="margin-left: 0.05rem !important;"></div>
                        </div>

                        <div class="col-md-12 forms-element">  
                            <div class="row">
                            <div class="col-md-4">
                                <label for="emr_entry_completed" class="checkbox checkbox-primary mr-3">
                                    <input type="checkbox" name="emr_entry_completed" id="emr_entry_completed" value="1" class="RRclass emr_entry_completed" formControlName="checkbox" />
                                    <span>EMR system entry completed</span>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            </div>
                        </div>  
                        
                        <hr style="width:100%">
                        


                        <div class="col-md-12 forms-element">  
                            <div class="row">
                            <div class="col-md-12"><b><label style="margin-left: 20px; color: #69aac2;">Additional Services :</label></b></div>
                         

                            <div class="col-md-4">
                                <label for="schedule_office_appointment" class="checkbox checkbox-primary mr-3">
                                    <input type="checkbox" name="schedule_office_appointment" id="schedule_office_appointment" value="1" class="RRclass schedule_office_appointment" formControlName="checkbox" />
                                    <span>Scheduled or requested an office appointment</span>
                                    <span class="checkmark"></span>
                                </label>
                        </div>
                        <div class="col-md-4">
                                <label for="resources_for_medication" class="checkbox checkbox-primary mr-3">
                                    <input type="checkbox" name="resources_for_medication" id="resources_for_medication" value="1" class="RRclass resources_for_medication" formControlName="checkbox" />
                                    <span>Provided resources for medication or social needs</span>
                                    <span class="checkmark"></span>
                                </label>
                        </div>

                        <div class="col-md-4">
                                <label for="medical_renewal" class="checkbox checkbox-primary mr-3">
                                    <input type="checkbox" name="medical_renewal" id="medical_renewal" value="1" class="RRclass medical_renewal" formControlName="checkbox" />
                                    <span>Researched or requested medication renewal</span>
                                    <span class="checkmark"></span>
                                </label>
                        </div>
                        <div class="col-md-4">
                                <label for="called_office_patientbehalf" class="checkbox checkbox-primary mr-3">
                                    <input type="checkbox" name="called_office_patientbehalf"  id="called_office_patientbehalf" value="1" class="RRclass called_office_patientbehalf" formControlName="checkbox" />
                                    <span>Called the office on behalf of patient</span>
                                    <span class="checkmark"></span>
                                </label>
                        </div>
                        <div class="col-md-4">
                                <label for="referral_support" class="checkbox checkbox-primary mr-3">
                                    <input type="checkbox" name="referral_support"  id="referral_support" value="1" class="RRclass referral_support" formControlName="checkbox" />
                                    <span>Referral support for Home Health, Oxygen or DME</span>
                                    <span class="checkmark"></span>
                                </label>
                        </div>
                        <div class="col-md-4">
                                <label for="no_other_services" class="checkbox checkbox-primary mr-3">
                                    <input type="checkbox" name="no_other_services"  id="no_other_services" value="1" class="RRclass no_other_services" formControlName="checkbox" />
                                    <span>No other services provided</span>
                                    <span class="checkmark"></span>
                                </label>
                        </div>

                            </div>

                            <div id="checkboxerror" style="display:none; color:red; ">Please choose at least one response </div>
                        </div> 
                      
                        <div class="form-row invalid-feedback"></div>
                    </div>
                    <!-- </div> -->
                    <div class="card-footer">
                        <div class="mc-footer">
                            <div class="row"> 
                                <div class="col-lg-12 text-right">
                                    <button type="submit" class="btn btn-primary m-1" id="submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>