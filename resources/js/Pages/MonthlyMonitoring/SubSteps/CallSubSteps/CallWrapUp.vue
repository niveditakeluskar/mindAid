<template>
    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="card"> 
                <div class="card-body">
                    <div class="mb-4 ml-4">
                        <div class="alert alert-success" id="callwrapform-success-alert" style="display: none;">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>Call wrap-up data successfully! </strong><span id="text"></span>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-3"><h6><b>Call Notes for Review and Approval</b></h6></div>
                            <div class="col-md-3">
                                <select class ="custom-select show-tick mr-4" id="rpm-report">
                                    <option>Select Report</option>
                                    <option value="2">Daily History Report</option>
                                </select>
                            </div> 
                            <div class="col-md-3">
                                <a href="/ccm/monthly-monitoring/call-wrap-up-word/" class="btn btn-primary" target="_blank">Care Manager Notes Word Format</a>   <!-- Docs Care Plan -->
                            </div>
                        </div>
                    </div>
                    <div class="row m-1">
                        <div class="col-12">
                            <div class="table-responsive">
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
                    <form id="callwrapup_form" name="callwrapup_form" action="" method="post"> 
                        <input type="hidden" name="uid" />
                        <input type="hidden" name="patient_id" />
                        <input type="hidden" name="start_time" value="00:00:00">
                        <input type="hidden" name="end_time" value="00:00:00">
                        <input type="hidden" name="module_id" />
                        <input type="hidden" name="component_id" />
                        <input type="hidden" name="stage_id" />
                        <input type="hidden" name="step_id" value="0">
                        <input type="hidden" name="form_name" value="callwrapup_form">
                        <div class="row ml-3"> 
                            <div class="col-md-12 form-group">
                                <div class=" forms-element">
                                    <label class="col-md-12">EMR Monthly Summary</label>
                                    <textarea  class="form-control" cols="90"  name="emr_monthly_summary[]" id="callwrap_up_emr_monthly_summary" onfocusout="saveEMR()"></textarea>
                                    <div class="invalid-feedback"></div>  
                                </div>
                            </div>
                            <div class="col-md-12" style="margin-bottom: 40px;">
                                <div class="row">
                                    <div class="col-md-3">
                                        <b><label style="margin-left: 20px; color: #69aac2;">Additional CCM Notes :</label></b>
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
                                        <label for="routine_response" class="checkbox checkbox-primary mr-3">
                                            <input type="checkbox" name="routine_response" id="routine_response" value="1" class="RRclass routine_response" formControlName="checkbox" />  
                                            <span>Routine Response</span>
                                            <span class="checkmark"></span>
                                        </label>
                                        <div class="col-md-12" id="routinediv">
                                            <span class="checkmark"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="urgent_emergent_response" class="checkbox checkbox-primary mr-3">
                                            <input type="checkbox" name="urgent_emergent_response" id="urgent_emergent_response" value="1" class="RRclass urgent_emergent_response" formControlName="checkbox" />  
                                            <span>Urgent/Emergent Response</span>
                                            <span class="checkmark"></span>
                                        </label>
                                        <div class="col-md-12" id="emergentdiv">
                                            <span class="checkmark"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="referral_order_support" class="checkbox checkbox-primary mr-3">
                                            <input type="checkbox" name="referral_order_support" id="referral_order_support" value="1" class="RRclass referral_order_support" formControlName="checkbox" />
                                            <span>Referral/Order Support</span>
                                            <span class="checkmark"></span>
                                        </label>
                                        <div class="col-md-12" id="referraldiv">
                                            <span class="checkmark"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="medication_support" class="checkbox checkbox-primary mr-3">
                                            <input type="checkbox" name="medication_support"  id="medication_support" value="1" class="RRclass medication_support" formControlName="checkbox" />
                                            <span>Medication Support</span>
                                            <span class="checkmark"></span>
                                        </label>
                                        <div class="col-md-12" id="medicationdiv">
                                            <span class="checkmark"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="verbal_education_review_with_patient" class="checkbox checkbox-primary mr-3">
                                            <input type="checkbox" name="verbal_education_review_with_patient"  id="verbal_education_review_with_patient" value="1" class="RRclass verbal_education_review_with_patient" formControlName="checkbox" />
                                            <span>Verbal Education/Review with Patient</span>
                                            <span class="checkmark"></span>
                                        </label>
                                        <div class="col-md-12" id="verbaldiv">
                                            <span class="checkmark"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="mailed_documents" class="checkbox checkbox-primary mr-3">
                                            <input type="checkbox" name="mailed_documents"  id="mailed_documents" value="1" class="RRclass mailed_documents" formControlName="checkbox" />
                                            <span>Mailed Documents</span>
                                            <span class="checkmark"></span>
                                        </label>
                                        <div class="col-md-12" id="maileddiv">
                                            <span class="checkmark"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="resource_support" class="checkbox checkbox-primary mr-3">
                                            <input type="checkbox" name="resource_support"  id="resource_support" value="1" class="RRclass resource_support" formControlName="checkbox" />
                                            <span>Resource Support</span>
                                            <span class="checkmark"></span>
                                        </label>
                                        <div class="col-md-12" id="resourcediv">
                                            <span class="checkmark"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="veterans_services" class="checkbox checkbox-primary mr-3">
                                            <input type="checkbox" name="veterans_services"  id="veterans_services" value="1" class="RRclass veterans_services" formControlName="checkbox" />
                                            <span>Veterans Services</span>
                                            <span class="checkmark"></span>
                                        </label>

                                        <div class="col-md-12" id="veteransdiv">
                                            <span class="checkmark"></span>
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <label for="authorized_cm_only" class="checkbox checkbox-primary mr-3">
                                            <input type="checkbox" name="authorized_cm_only"  id="authorized_cm_only" value="1" class="RRclass authorized_cm_only" formControlName="checkbox" />
                                            <span><b>Authorized CM Only:</b></span>
                                            <span class="checkmark"></span>
                                        </label>

                                        <div class="col-md-12" id="authorizeddiv">
                                            <span class="checkmark"></span>
                                        </div> 
                                    </div>
                                    <div class="col-md-4">
                                        <label for="no_additional_services_provided" class="checkbox checkbox-primary mr-3">
                                            <input type="checkbox" name="no_additional_services_provided"  id="no_additional_services_provided" value="1" class="RRclass no_additional_services_provided" formControlName="checkbox" />
                                            <span>No Additional Services Provided:</span>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                                <div id="checkboxerror" style="display:none; color:red; ">Please choose at least one response </div>
                            </div> 
                            <div class="form-row invalid-feedback"></div>
                        </div>
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
</template>
