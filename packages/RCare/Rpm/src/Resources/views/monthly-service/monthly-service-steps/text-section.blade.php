<div class="row" id="content-template" >
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">Send Text</div>
            <form  name="monthlyservice" action="{{ route("ajax.save.monthly.service") }}" method="post"> 
             @csrf
                <?php $stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Not Recorded'); ?>
                <input type="hidden"  name="module_id" value="{{ getPageModuleName() }}">
		        <input type="hidden"  name="component_id" value="{{ getPageSubModuleName() }}">
                <input type="hidden" name="start_time" value="00:00:00">
		        <input type="hidden" name="end_time" value="00:00:00">
                <input type="hidden" name="patient_id" value="{{$checklist->id}}" >
                <input type="hidden" name="contact_via" id="text_contact_via"  value="text">
                <input type="hidden" name="temp_id" id="text_temp_id" value="">
                <input type="hidden" name="stage_id" value="{{$stage_id}}">
                <input type="hidden" name="review_data" value="1">
            <div class="card-body">
            <div id="success1"></div>  
            <div id="danger1"></div> 
                    <div class="form-group" id="template_content">
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label>Contact No.</label>
                                <select class="custom-select contact_number" name="text_contact_number" id="text_contact_number">
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
                                        // $text_stage_id = getFormStageId($text_module_id, $text_submodule_id, 'Text');
                                        $text_stage_id = getFormStageId($text_module_id, $text_submodule_id,'RPMtext');
                                        $text_step_id = getFormStepId($text_module_id, $text_submodule_id, $text_stage_id, '');
                                    ?>
                                    @selectcontentscript("text_content_title",$text_module_id,$text_submodule_id,$text_stage_id,$text_step_id,["id"=>"content_title","class"=>"custom-select content_title"])
                        	</div>
                            <div class="col-md-4 form-group mb-3" style="display:none;"><label>Content Type</label>
                                @selectsmstemplates("template_type_id",["id"=>"template_type_id", "class"=>"abcd"])
                        	</div>
                        </div>

                        <div class="row" id = "textarea">
                            <div class="col-md-6 offset-6 form-group mb-3" >
                                <div id="trialtext"></div>
                                <label>Message</label>
                                <textarea name="text_content_area" class="form-control content_area" id="content_area"></textarea> 
                            </div>    
                        </div>
                        <div class="row" id = "schedule_for_later_text">
                            <div class="col-md-6 form-group mb-3">
                                
                            </div>

                        	<div class="col-md-6 form-group mb-3" id="monthly_services_nxt_txt_date_div">
                                <label>Schedule Next Date</label> 
                                @date("monthly_services_nxt_txt_date",["id"=>"monthly_services_nxt_txt_date"])
                        	</div>
                        </div>
                    </div>
            </div>
            <div class="card-footer text-right">
                <div class="">
                    <button type="submit" class="btn btn-primary btn-icon btn-lg m-1 summarize-details" id="summarize-details-text" >Send</button>
                </div>
            </div>
            </form>    
        </div>
    </div>
</div>
