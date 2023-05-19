<!-- <form name="monthlyservice"> -->
<div class="form-group" id="within_guidelines" style="display:none">
    <div class="row  mb-4">
        <div class="col-md-12 mb-4 ">    
            <div class="card">
                <div class="card-body card text-left">
                    <div class="row">

                    	 
                        <div class="col-md-12">
                            <!-- <label>Step 1</label></b> -->
                            <label class="checkbox checkbox-outline-primary blood">
                                <input type="checkbox" class="checkbox" id="leave_message_in_emr" name="leave_message_in_emr" target-off="" / value="1"> 
                                <span>Leave message in EMR</span>
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="col-md-12">
                            <!-- <p>Contact patient </p> -->
                            <div class="row">
                                <div class="col-md-6 form-group mb-3"><label>Contact No.</label>
                                    <select class="custom-select contact_number" name="within_guidelines_contact_number" id="within_guidelines_contact_number">
                                            <option value="">Select Contact No.</option>
                                            @foreach($contact_number as $value)
                                                @isset($value->phone_primary)
                                                    <option value="{{ $value->phone_primary }}">{{ $value->phone_primary }}</option>
                                                @endisset
                                                @isset($value->phone_secondary)
                                                    <option value="{{ $value->phone_secondary }}">{{ $value->phone_secondary }}</option>
                                                @endisset
                                                @isset($value->other_contact_phone)
                                                    <option value="{{ $value->other_contact_phone }}">{{ $value->other_contact_phone }}</option>
                                                @endisset
                                            @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 form-group mb-3"><label>Template Name</label> 
                                    @selecttemplates("within_guideline_content_title","2",["id"=>"within_guideline_content_title","class"=>"custom-select content_title"])
                                    
                                    <!-- <select class="custom-select content_title" name="within_guideline_content_title" id="within_guideline_content_title">
                                    </select> -->
                                    <!-- <input type="hidden" id="template_id" name="template_id"> -->
                                </div>
    <!--                             <div class="col-md-4" >
                                    <input type="text" style="margin-top: 23px;" class="form-control" id="content_title_text" name="content_title_text" placeholder="Enter Content Name" style="display: none; margin-top:10px">
                                </div> --> 
                                <!-- <div class="col-md-4 form-group mb-3" style="display:none;"><label>Content Type</label>
                                    <select class="custom-select" name="template_type_id" id="template_type_id" disabled="disabled">
                                        @foreach($type as $value)
                                            <option value="{{ $value->id}}">{{ $value->template_type }}</option>
                                        @endforeach
                                    </select>
                                </div> -->
                            </div>

                            <div class="row" id = "textarea">
                                <div class="col-md-6 offset-6 form-group mb-3" >
                                    <label>Message</label>
                                    <textarea name="within_guideline_content_area_msg" class="form-control content_area" id="content_area_msg"></textarea>
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
                            <!-- <button id="" type="button" class="btn  btn-primary m-1 saveMonthlyService" >Save</button> -->
                            <button type="button" class="btn btn-primary m-1 summarize-details">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>                                 
    </div>
</div>
<!-- </form> -->