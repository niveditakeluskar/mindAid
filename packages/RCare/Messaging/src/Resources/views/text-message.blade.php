<div class="mb-3" id ="txt-msg" style="display:none">
<?php 
//echo $org[0]->assign_message;
//echo $patient[0]->primary_cell_phone;
$assign_message = isset($org[0]->assign_message)? $org[0]->assign_message :'';
$consent_to_text = isset($patient[0]->consent_to_text)?$patient[0]->consent_to_text:'';
// $configurations =  isset($conf->configurations)? $conf->configurations:'';
if($consent_to_text == '1' && isset($conf->configurations) && $assign_message == '1'){?>
<!-- <div class="form-group" id="template_content"> -->
<div class="row">
   <div class="col-md-6"><label>Contact No. <span class="error">*</span></label>
      <?php  //echo $patient[0]->secondary_cell_phone; ?>
      <select class="forms-element custom-select" name="phone_no" id="contact_number">
         <option value="">Choose Contact Number</option>
         <?php
         // if(isset($patient[0]->mob) && ($patient[0]->mob != "") && ($patient[0]->mob != null) && ($patient[0]->primary_cell_phone == "1")){
            ?>
            <!-- <option value="{{--$patient[0]->country_code--}}{{--$patient[0]->mob--}}">{{--$patient[0]->mob--}} (P)<i class="i-Telephone"></i></option> -->
            <?php
         // }
         ?>
         <?php

         // if(isset($patient[0]->home_number) && ($patient[0]->home_number != "") && ($patient[0]->home_number != null) && ($patient[0]->secondary_cell_phone == "1")){
            ?>
            <!-- <option value="{{--$patient[0]->secondary_country_code--}}{{--$patient[0]->home_number--}}">{{--$patient[0]->home_number--}} (S)</option> -->
            <?php
         // }
         ?>
      </select>
      <div class="invalid-feedback"></div> 

   </div>
   <div class="col-md-6"><label>Template Name <span class="error">*</span></label>
      <?php
      if (isset($callstatus->call_action_template) && ($callstatus->call_action_template != '')) {
         $content_scripts_select = json_decode($callstatus->call_action_template);
         $template_id = $content_scripts_select->template_id;
      } else {
         $template_id = 0;
      }
      $call_not_answered_step_id = getFormStepId($module_id, $submodule_id, $stage_id, 'Call Not Answered');
      ?>
      @selectcontentscript("call_not_answer_template_id",$module_id,$submodule_id,$stage_id,$call_not_answered_step_id,["id"=>"ccm_content_title","class"=>"custom-select", "value" =>$template_id])
   </div>
   <div id="">
   </div>
   <?php if(($patient[0]->primary_cell_phone == "0") && ($patient[0]->secondary_cell_phone == "0")){
      echo "<div class='alert alert-warning mt-2 ml-2'>Patient Cell Number is Unavailable. Text Message cannot be sent to this patient.</div> <input type='hidden' name='error' value='Patient Cell Number is Unavailable. Text Message cannot be sent to this patient.'>";
   } ?>
</div>
<div class="row" id="textarea">
   <div class="col-md-12 form-group mb-3">
      <label><b>Content</b><span class="error">*</span></label>
      <textarea name="text_msg" class="form-control" id="ccm_content_area" style="padding: 5px;width: 47em;min-height: 5em;overflow: auto;height: 87px;}"></textarea>
   </div>
</div>
<!--div class="row">
   <div class="col-lg-12 text-right">
      <button type="submit" name="sent_text_message" value="text_send" class="btn btn-primary m-1">Send Text Message</button>
   </div>
</div-->
<!--/div>--> 
<?php }else if(!isset($conf->configurations)){ 
   echo "<div class='alert alert-warning mt-2 ml-2'>Texting has been disabled for the system</div> <input type='hidden' name='error' value='Texting has been disabled for the system.'>"; }
   else if($assign_message !='1'){ 
      echo "<div class='alert alert-warning mt-2 ml-2'>Texting is disabled for Patient's Organization</div> <input type='hidden' name='error' value='Texting is disabled for Patient's Organization.'>"; }
      else{ 
         echo "<div class='alert alert-warning mt-2 ml-2'>Patient has not given consent to send text message</div> <input type='hidden' name='error' value='Patient has not given consent to send text message.'>"; } ?>
      </div> 