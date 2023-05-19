<!-- <form name="monthlyservice"> -->
<div class="form-group" id ="call_section" style="display:none">
            <div class="row  mb-4">
                <div class="col-md-12 mb-4 ">    
                    <div class="card">
                        <div class="card-body text-left">
                            <div class="row">
                                <label class="col-md-2 mb-4 float-left">Call Answered</label>
                                <label class="radio radio-primary col-md-1">
                                    <input type="radio" name="call_status" value="1" formControlName="radio" id="answered" radioLable="Call Answered">
                                    <span></span>
                                    <span class="checkmark"></span>

                                </label>
                                <!-- <input class="col-md-1" type="radio" name="call_radio" value="1" id="answered"> -->
                                <!-- <div class="col-md-4 mb-4 float-left">
                                    <div class="row">
                                        <label></label>Call Answered</b>
                                        <input type="radio" name="call_radio" value="1" id="answered">
                                    </div>    
                                </div> -->
                                <div class="col-md-9 mb-4 float-left" id="call_scripts" style=display:none>
                                    <select class="form-control col-md-4 mb-3 form-control" name="call_content_title" id="call_content_title"  data-toggle="modal" >
                                        <option value="">Choose Script</option>
                                        @foreach($callScripts as $value)
                                            <option value="{{ $value->id }}">{{ $value->content_title }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-2 mb-4 float-left">Call Not Answered</label>
                                <label class="radio radio-primary col-md-1">
                                    <input type="radio" name="call_status" value="2" formControlName="radio" id="not_answered" radioLable="Call Not Answered">
                                    <span></span>
                                    <span class="checkmark"></span>
                                </label>
                                <!-- <input class="col-md-1" type="radio" name="call_radio" value="2" id="not_answered"> -->
                                <!-- <div class="col-md-4 mb-4 float-left">
                                    <label></label>Call Not Answered</b>
                                    <input type="radio" name="call_radio" value="2" id="not_answered">
                                </div> -->
                                <div class="col-md-4 mb-4 float-left" id="answer" style=display:none>
                                    @selectanswer("answer",["id" => "answer", "class" => "form-control form-control"])
                                </div>
                            </div>
                        </div>
                        @include('Rpm::monthlyService.call-script-modal')
                        <div class="card-footer">
                        <div class="mc-footer">
                            <div class="row">
                                <div class="col-lg-12 text-right" id="call_not_answered_save" style="display:none;">
                                    <button type="button" class="btn btn-primary btn-icon btn-lg m-1 summarize-details" >Save</button>
                                    <!-- <button type="button" class="btn  btn-primary m-1 saveMonthlyService" >Save</button> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>                                 
            </div>
</div>  
<!-- </form> -->