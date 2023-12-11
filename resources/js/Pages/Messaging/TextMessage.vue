<template>
   <div class="mb-3" id ="txt-msg">
      <div class="row">
         <div class="col-md-6"><label>Contact No. <span class="error">*</span></label>
            <select class="forms-element custom-select" name="phone_no" id="contact_number">
               <option value="">Choose Contact Number</option>
            </select>
            <div class="invalid-feedback"></div> 
         </div>
         <div class="col-md-6"><span>Template Name <span class="error">*</span></span></div>
         <div id=""></div>
         <div class='alert alert-warning mt-2 ml-2'>Patient Cell Number is Unavailable. Text Message cannot be sent to this patient.</div> <input type='hidden' name='error' value='Patient Cell Number is Unavailable. Text Message cannot be sent to this patient.'>
      </div>
      <div class="row" id="textarea">
         <div class="col-md-12 form-group mb-3">
            <label><b>Content</b><span class="error">*</span></label>
            <textarea name="text_msg" class="form-control" id="ccm_content_area" style="padding: 5px;width: 47em;min-height: 5em;overflow: auto;height: 87px;}"></textarea>
         </div>
      </div>
      <div class='alert alert-warning mt-2 ml-2'>Texting has been disabled for the system</div> <input type='hidden' name='error' value='Texting has been disabled for the system.' />
      <div class='alert alert-warning mt-2 ml-2'>Texting is disabled for Patient's Organization</div> <input type='hidden' name='error' value="Texting is disabled for Patient's Organization." />
      <div class='alert alert-warning mt-2 ml-2'>Patient has not given consent to send text message</div> <input type='hidden' name='error' value='Patient has not given consent to send text message.' />
   </div> 
</template>