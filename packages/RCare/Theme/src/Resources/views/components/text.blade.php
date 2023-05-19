<div class="alert alert-success" id="success-alert" style="display: none;">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>Text data saved successfully! </strong><span id="text"></span>
</div>
<div class="card-title">Text</div>
<div class="mb-4">
    <div class="form-group" id="template_content">
        <div class="twilo-error"></div>
        <div class="row">
            <div class="col-md-4 form-group mb-3" id="cntctlist"><label>Contact No.<span class="error">*</span></label>
                <select class="custom-select" name="contact_no" id="text_contact_number">
                    <option value="">Choose Contact Number</option>
                    @if(isset($patient[0]->mob) && ($patient[0]->mob != "") && ($patient[0]->mob != null) && ($patient[0]->primary_cell_phone == "1"))
                    <option value="{{$patient[0]->country_code}}{{$patient[0]->mob}}">{{$patient[0]->mob}}</option>
                    @endif
                    @if(isset($patient[0]->home_number) && ($patient[0]->home_number != "") && ($patient[0]->home_number != null) && ($patient[0]->secondary_cell_phone == "1"))
                    <option value="{{$patient[0]->secondary_country_code}}{{$patient[0]->home_number}}">{{$patient[0]->home_number}}</option>
                    @endif
                </select>
                <div class="invalid-feedback"></div>  
            </div>
            <div class="col-md-8 form-group mb-3">
                <label>Template <span class="error">*</span></label> 
                @selectcontentscript("template",getPageModuleName(),getPageSubModuleName(),getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Text'), '',["id"=>"text_template_id","class"=>"custom-select"])
            </div>
            <?php 
            if(($patient[0]->primary_cell_phone == "0") && ($patient[0]->secondary_cell_phone == "0")){
                echo "<div class='alert alert-warning mt-2 ml-2'>Patient Cell Number is Unavailable. Text Message cannot be sent to this patient.</div>";
            } 
            ?>
        </div>
        <div class="row" id = "textarea">
            <div class="col-md-8 offset-4 form-group mb-3" >
                <label>Message <span class="error">*</span></label>
                <textarea name="message" class="forms-element form-control" id="templatearea_sms"></textarea>  
                <div class="invalid-feedback"></div>
            </div>    
        </div>
    </div>
</div>