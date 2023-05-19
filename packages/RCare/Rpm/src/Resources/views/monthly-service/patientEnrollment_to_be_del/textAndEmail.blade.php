<div class="row mb-4" id="content-template" style="display: none;">
    <div class="col-md-12 mb-4">
    <div class="success" id="success1"></div>
        <div class="card">
            <div class="card-header"><h4 id="header_text"></h4>
            </div>
            <form id="template_fetch" method="post"> 
                @csrf
            <div class="card-body">
                
                <!-- use Temlate Content -->
                    <div class="form-group" id="template_content">
                        <div class="row">
                            <input type="hidden" name="hidden_id" id="hidden_id" value="{{$checklist->id}}">
                            <input type="hidden" name="practice_id" id="practice_id" value="{{$checklist->practice_id}}">  
                            <input type="hidden" id="template_type_id" name="template_type_id"> 
                            <input type="hidden" id="module_id" name="module_id">
                            <input type="hidden" id="component_id" name="component_id">
                            <input type="hidden" id="stage_id" name="stage_id">
                            <input type="hidden" id="template_id" name="template_id">

                            <div class="col-md-6 form-group mb-3" id="contactlist"><label>Contact No.</label>
                                <select class="custom-select" name="contact_number" id="contact_number">
                                       <option value="0">Choose Contact Number</option>
                                    {{--@foreach($contact_number as $value)--}}
                                        <option value="{{ $checklist->phone_primary }}">{{ $checklist->phone_primary }}</option>
                                        <option value="{{ $checklist->phone_secondary }}">{{ $checklist->phone_secondary }}</option>
                                        <option value="{{ $checklist->other_contact_phone }}">{{ $checklist->other_contact_phone }}</option>
                                    {{--@endforeach--}}
                                </select>
                                <span style="display:none;color:red" id="contnumber">Please Select Number</span>
                            </div>

                            <div class="col-md-6 form-group mb-3" id="emaillist"><label>To</label>
                                <select class="custom-select" name="contact_email" id="contact_email">
                                     <option value="0">Choose Contact Email</option>
                                     {{--@foreach($contact_email as $value)--}}
                                        <option value="{{ $checklist->email }}">{{ $checklist->email }}</option>
                                        <option value="{{ $checklist->poa_email }}">{{ $checklist->poa_email }}</option>
                                        <option value="{{ $checklist->other_contact_email }}">{{ $checklist->other_contact_email }}</option>
                                    {{--@endforeach--}}
                                </select>
                                <span style="display:none;color:red" id="contemail">Please Select Email</span>
                            </div>
                          	<div class="col-md-6 form-group mb-3"><label>Template</label> 
                                  <select class="custom-select" name="content_title" id="content_title">
                                  </select>
                                  <span style="display:none;color:red" id="contitle">Please Choose Title</span>
                          	</div>
                        </div>

                        <div class="row" id = "textarea">
                            <div class="col-md-6 offset-6 form-group mb-3" >
                                <label>Message</label>
                                <textarea name="content_area_msg" class="form-control" id="content_area_msg"> 
                                </textarea>  
                            </div>    
                        </div>
    
                
                        <div class="row" style="display: none" id="email-div">          
                                
                       		  <div class="col-md-12 form-group mb-3">
                            	<label>Subject : </label>
                               <!--  @text("subject", ["id" => "subject", "placeholder" => "Enter Subject", "class" => "form-control"])  -->
                                <input type="text" name="subject" class="form-control" id="subject">
                            </div> 

                            <div class="col-md-12 form-group mb-3"> 
                                <label><h1>Content</h1></label>
                                    <p name="content" class="mail_content" id="editor" cols="30" rows="10">
                                    </p>
                            </div> 
                	     </div>
                    </div>
            </div>
                <div class="card-footer text-right">
                    <div class="">
                        <button type="button" class="btn btn-primary btn-icon btn-lg m-1" id="send">
                            <span class="ul-btn__text">Send</span> 
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
