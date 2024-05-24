<!-- <form name="monthlyservice"> -->
<div class="row" id="content-template" >
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">Send Text</div>
            <div class="card-body">
                <!-- <form id="template_fetch" method="post">  -->
                <!-- @csrf -->
                <!-- use Temlate Content -->
                    <div class="form-group" id="template_content">
                        <div class="row">
                            <!-- <input type="hidden" id="template_type_id" name="template_type_id"> -->
                            <!-- <input type="hidden" id="template_id" name="template_id"> -->
                            <!-- <input type="hidden" name="hidden_id" id="hidden_id" value="{{$checklist->id}}"> -->
                            <!-- <input type="hidden" name="practice_id" id="practice_id" value="{{$checklist->practice_id}}">   -->
                            <div class="col-md-6 form-group mb-3">
                                <label>Contact No.</label>
                                <select class="custom-select contact_number" name="text_contact_number" id="text_contact_number">
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
                            @selecttemplates("text_content_title","2",["id"=>"content_title","class"=>"custom-select content_title"])
                              
                                <!-- <select class="custom-select content_title" name="content_title" id="content_title">
                                </select> -->
                        	</div>
<!--                             <div class="col-md-4" >
                                <input type="text" style="margin-top: 23px;" class="form-control" id="content_title_text" name="content_title_text" placeholder="Enter Content Name" style="display: none; margin-top:10px">
                            </div> --> 
                            <div class="col-md-4 form-group mb-3" style="display:none;"><label>Content Type</label>
                                @selectsmstemplates("template_type_id",["id"=>"template_type_id", "class"=>"abcd"])
                        		<!-- <select class="custom-select" name="template_type_id" id="template_type_id" disabled="disabled">
                            		{{-- @foreach($type as $value)
                               			<option value="{{ $value->id}}">{{ $value->template_type }}</option>
                            		@endforeach --}}
                        		</select> -->
                        	</div>
                        </div>

                        <div class="row" id = "textarea">
                            <div class="col-md-6 offset-6 form-group mb-3" >
                                <label>Message</label>
                                <textarea name="text_content_area" class="form-control content_area" id="content_area"></textarea>
                                <div class="invalid-feedback"></div>
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
                <!-- </form> -->
            </div>
            <div class="card-footer text-right">
                <div class="">
                    <button type="button" class="btn btn-primary btn-icon btn-lg m-1 summarize-details" id="summarize-details-text" >Send</button>
                    <!-- <button type="button" id="" class="btn btn-primary btn-icon btn-lg m-1 saveMonthlyService" >Send
                        <!- - <span class="ul-btn__icon"><i class="i-"></i></span> - ->
                        <!- - <span class="ul-btn__text">Send</span>  - ->
                    </button> -->
                    <!-- <button class="btn btn-primary btn-icon btn-lg m-1" id="monthly_services_nxt_txt_date_btn"> -->
                        <!-- <span class="ul-btn__icon"><i class="i-"></i></span> -->
                        <!-- <span class="ul-btn__text">Schedule For Later</span> 
                    </button> -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- </form> -->