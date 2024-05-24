<div class="alert alert-success" id="success-alert" style="display: none;">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>Email data saved successfully! </strong><span id="email"></span>
</div>
<div class="card-title">Email</div>
<div class="mb-4">
    <div class="form-group" id="template_content">
        <div class="row">
            <div class="col-md-6 form-group mb-3" id="cntctlist"><label>To <span class="error">*</span></label>
                <select class="custom-select" name="contact_email" id="contact_email">
                    <option value="0">Choose Contact Email</option>
                        @if(isset($patient[0]->email) && ($patient[0]->email !="") && ($patient[0]->email != null ))
                        <option value="{{ $patient[0]->email }}">{{ $patient[0]->email }}</option>
                        @endif
                        @if(isset($patient[0]->poa_email) && ($patient[0]->poa_email !="") && ($patient[0]->poa_email != null ))
                        <option value="{{ $patient[0]->poa_email }}">{{ $patient[0]->poa_email }}</option>
                        @endif
                        @if(isset($patient[0]->other_contact_email) && ($patient[0]->other_contact_email !="") && ($patient[0]->other_contact_email != null ))
                        <option value="{{ $patient[0]->other_contact_email }}">{{ $patient[0]->other_contact_email }}</option>
                        @endif
                </select>
                <div class="invalid-feedback"></div>  
            </div>
            <div class="col-md-6 form-group mb-3">
                <label>Template <span class="error">*</span></label> 
                @selectcontentscript("email_template",getPageModuleName(),getPageSubModuleName(),getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Email'), '',["id"=>"email_template_id","class"=>"custom-select"])
            </div>
        </div>
        <div class="row" id = "textarea">
            <div class="col-md-12 form-group mb-3">
                <label>Subject <span class="error">*</span></label>
                @text("subject", ["id" => "subject", "placeholder" => "Enter Subject", "class" => "form-control"])
                >
            </div> 
            <div class="col-md-12 form-group mb-3" >
                <label>Message <span class="error">*</span></label>
                <textarea name="message" class="forms-element form-control" id="email_template_area" rows="10"></textarea>  
                <div class="invalid-feedback"></div>
            </div>    
        </div>
    </div>
</div>