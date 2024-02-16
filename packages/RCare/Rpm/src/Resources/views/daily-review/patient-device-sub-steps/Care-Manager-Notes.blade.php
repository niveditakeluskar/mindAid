
<div class="modal fade" id="rpm_cm_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="
    width: 800px!important;
    margin-left: 280px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Care Manager Notes</h4>
                <button type="button" class="close modalcancel" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <form action="{{route('save.rpm.notes')}}" name="rpm_cm_form" id="rpm_cm_form" method="POST" class=" form-horizontal">
                    {{ csrf_field() }}
                     <?php 
                            $module_id    = getPageModuleName();
                            $submodule_id = getPageSubModuleName();
                            
                        ?>
                    <div>
                        <div class="row">
                            <div class="col-md-12 form-group mb-3">
                                <label for="patientname" id="patientname"></label>&nbsp; &nbsp;
                                <label for="patientvital" id="patientvital"></label><br>
                                <input type="hidden" name="hd_timer_start" id="hd_timer_start">
                                <input type="hidden" name="rpm_observation_id" id="rpm_observation_id" />
                                
                                <input type="hidden" name="care_patient_id" id="care_patient_id" />
                                <input type="hidden" name="p_id" id="p_id" />
                                <input type="hidden" name="vital" id="vital" />  
                                <input type="hidden" name="unit" id="unit" />  
                                <input type="hidden" name="csseffdate" id="csseffdate" />   
                                <input type="hidden" id="module_id" name="module_id" value="{{$module_id}}">
                                <input type="hidden" id="component_id" name="component_id" value="{{$submodule_id}}">
                                <input type="hidden" name="table" id="table" />
                                <input type="hidden" name="formname" id="formname" /> 
                                <input type="hidden" name="hd_chk_this" id="hd_chk_this" />
                                
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
                                <div class="col-lg-12 text-right">
                                    <button type="submit" dataid="rpm_cm_form" class="btn btn-primary m-1">Submit</button>
                                    <button type="button" class="btn btn-outline-secondary m-1 modalcancel" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>