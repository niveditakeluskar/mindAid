<div class="alert alert-success" id="success-alert" style="display: none;">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>Text data saved successfully! </strong><span id="text"></span>
</div>
  <div class="twilo-error"></div>
<div class="card-title">Text</div>
<div class="mb-4">
    <div class="form-group" id="template_content" name="template_content">
<?php 

  $assign_message = isset($org[0]->assign_message)? $org[0]->assign_message :'';
  $consent_to_text = isset($patient[0]->consent_to_text)?$patient[0]->consent_to_text:'';
// $configurations =  isset($conf->configurations)? $conf->configurations:'';
  

if($consent_to_text == '1' && isset($conf->configurations) && $assign_message == '1'){?>
        <div class="row">
            <div class="col-md-6 form-group mb-3" id="cntctlist"><label>Contact No.</label>
                <select class="custom-select" name="contact_no" id="text_contact_number">
                    <option value="">Choose Contact Number</option>
                    @if(isset($patient[0]->mob) && ($patient[0]->mob != "") && ($patient[0]->mob != null) && ($patient[0]->primary_cell_phone == "1"))
                    <option value="{{$patient[0]->mob}}">{{$patient[0]->mob}}</option>
                    @endif
                    @if(isset($patient[0]->home_number) && ($patient[0]->home_number != "") && ($patient[0]->home_number != null) && ($patient[0]->secondary_cell_phone == "1"))
                    <option value="{{$patient[0]->home_number}}">{{$patient[0]->home_number}}</option>
                    @endif
                </select>
                <div class="invalid-feedback"></div>  
            </div>
            <div class="col-md-6 form-group mb-3">             
                <label>Template</label> 
                <!-- ?php echo getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Device Trainingawerfy'); ?> -->
                <!-- ?php echo $devices[$i]->id;?> -->
                <!-- @selectdevicecontentscript("template",2,53,0,'',1,["id"=>"text_template_id","class"=>"custom-select"])  -->
              
                @selectcontentscript("template",getPageModuleName(),getPageSubModuleName(),getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Text'), '',["id"=>"text_template_id","class"=>"custom-select reviwe_text"])
            </div>
            <?php if(($patient[0]->primary_cell_phone == "0") && ($patient[0]->secondary_cell_phone == "0")){
                echo "<div class='alert alert-warning mt-2 ml-2'>Patient Phone Number is not marked as cell phone number. Text Message cannot be sent to this patient.</div>";
            }else if(($patient[0]->mob == "") && ($patient[0]->home_number == ""))
            {
               echo "<div class='alert alert-warning mt-2 ml-2'>Patient Cell Number is Unavailable. Text Message cannot be sent to this patient.</div>";
            } ?>
        </div>
        <div class="row" id = "textarea">
            <div class="col-md-12 form-group mb-3" >  
                <label>Message</label>
                <textarea name="message" class="forms-element form-control" id="templatearea_sms"></textarea>  
                <div class="invalid-feedback"></div>
            </div>    
        </div>
       <?php } else if(!isset($conf->configurations)){ 
   echo "<div class='alert alert-warning mt-2 ml-2'>Texting has been disabled for the system</div>"; }
   else if($assign_message !='1'){ 
      echo "<div class='alert alert-warning mt-2 ml-2'>Texting is disabled for Patient's Organization</div>"; }
      else{ 
         echo '<div class="alert alert-warning mt-2 ml-2">Patient has not given consent to send text message</div>'; } ?>

    </div>
</div>