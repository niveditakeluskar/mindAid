<div class="form-group" id="within_guidelines" style="display:none">
    <div class="row  mb-4">
        <div class="col-md-12 mb-4 ">    
            <div class="card">
            <form  name="monthly_within_guideline_form" action="{{ route("ajax.save.monthly.service") }}" method="post"> 
                @csrf
                <?php $stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Within Guidelines'); ?>
                        <input type="hidden"  name="module_id" value="{{ getPageModuleName() }}">
                        <input type="hidden"  name="component_id" value="{{ getPageSubModuleName() }}">
                        <input type="hidden" name="start_time" value="00:00:00">
                        <input type="hidden" name="end_time" value="00:00:00">
                        <input type="hidden" name="patient_id" value="{{$checklist->id}}" >
                        <input type="hidden" name="stage_id" value="{{$stage_id}}">
                        <input type="hidden" name="review_data" value="2">
                        <input type="hidden" name="contact_via" value="within guidelines">
                        <input type="hidden" name="template_type_id" value="2">
                     
                <div class="card-body card text-left">
                <div id="success3"></div>  
                <div id="danger3"></div>  
                    <div class="row">
                        <div class="col-md-12">
                            <label class="checkbox checkbox-outline-primary blood">
                                <input type="checkbox" class="checkbox" id="leave_message_in_emr" name="leave_message_in_emr" target-off="" / value="1"> 
                                <span>Leave message in EMR</span>
                                <span class="checkmark"></span> 
                            </label>
                        </div>
                        <div class="col-md-12">
                            <div class="row"> 
                                <div class="col-md-6 form-group mb-3"><label>Contact No.</label>
                                    <!-- <select class="custom-select contact_number" name="text_contact_number" id="within_guidelines_contact_number"> -->
                                    <select class="custom-select contact_number" name="within_guidelines_contact_number" id="within_guidelines_contact_number">
                                    <option value="">Choose Contact Number</option> 
                                          <?php
                                             if(isset($patient[0]->mob) && ($patient[0]->mob != "") && ($patient[0]->mob != null)){
                                          ?>
                                                <option value="{{$patient[0]->mob}}">{{$patient[0]->mob}}</option>
                                          <?php
                                             }
                                          ?>
                                          <?php
                                             if(isset($patient[0]->home_number) && ($patient[0]->home_number != "") && ($patient[0]->home_number != null)){
                                          ?>
                                                <option value="{{$patient[0]->home_number}}">{{$patient[0]->home_number}}</option>
                                          <?php
                                             }
                                        ?> 
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 form-group mb-3"><label>Template Name</label> 
                                <?php
                                        $text_module_id = getPageModuleName();
                                        $text_submodule_id = getPageSubModuleName();
                                        $text_stage_id = getFormStageId($text_module_id, $text_submodule_id, 'Withinguidelines');
                                        // $text_stage_id = getFormStageId($text_module_id, $text_submodule_id, 'Text');
                                        $text_step_id = getFormStepId($text_module_id, $text_submodule_id, $text_stage_id, '');
                                    ?>
                                    @selectcontentscript("text_content_title",$text_module_id,$text_submodule_id,$text_stage_id,$text_step_id,["id"=>"within_guideline_content_title","class"=>"custom-select"])
                                  
                                </div> 
                            </div>

                            <div class="row" id = "textarea">
                                <div class="col-md-6 offset-6 form-group mb-3" >
                                    <label>Message</label>
                                     <div id="trial" style="display:none"></div>        
                                    <textarea name="text_content_area" class="form-control content_area" id="content_area_msg"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>     
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                <div class="mc-footer">
                    <div class="row">
                        <div class="col-lg-12 text-right">
                            <button type="submit" class="btn btn-primary m-1 summarize-details">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>    
            </div>
        </div>                                 
    </div>
</div>

