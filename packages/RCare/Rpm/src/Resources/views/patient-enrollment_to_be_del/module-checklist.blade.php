<div class="card">

<div class="card-body">

   <div class="form-row">
      <div class="form-group col-md-7" id="from">
         <strong class="mr-1">Module</strong>   
         <?php //echo print_r($PatientServices); ?>                          
        
         <label _ngcontent-xbc-c9="" class="checkbox checkbox-info col-md-4">
                <input _ngcontent-xbc-c9=""  type="checkbox" value="1" name="enroll_module[]" id="enroll_rpm">
                <span _ngcontent-xbc-c9="">RPM</span><span _ngcontent-xbc-c9="" class="checkmark" ></span>
            </label>
            <label _ngcontent-xbc-c9="" class="checkbox checkbox-info col-md-4">
                <input _ngcontent-xbc-c9=""  type="checkbox" value="2" name="enroll_module[]" id="enroll_ccm">
                <span _ngcontent-xbc-c9="">CCM</span><span _ngcontent-xbc-c9="" class="checkmark"></span>
            </label>
            <label _ngcontent-xbc-c9="" class="checkbox checkbox-info col-md-4">
                <input _ngcontent-xbc-c9=""  type="checkbox" value="3" name="enroll_module[]" id="enroll_awv">
                <span _ngcontent-xbc-c9="">AWV</span><span _ngcontent-xbc-c9="" class="checkmark"></span>
            </label>
            <label _ngcontent-xbc-c9="" class="checkbox checkbox-info col-md-4">
                <input _ngcontent-xbc-c9=""  type="checkbox" value="4" name="enroll_module[]" id="enroll_tcm">
                <span _ngcontent-xbc-c9="">TCM</span><span _ngcontent-xbc-c9="" class="checkmark"></span>
            </label>
            <p class="text-muted m-0"></p>
            <p class="text-muted m-0"></p>
      </div>
 
          
        
   </div>
   
   

  
</div>
<div class="card-footer">
      <div class="mc-footer">
          <div class="row">
              <div class="col-lg-12 text-right">
                  <button type="button" class="btn btn-secondary m-1" id="module_back"  onclick="backStep(2)"> Back</button>
                  <button type="button" class="btn  btn-primary m-1" id="module_next"> Save</button>
              </div>
          </div>
      </div>
  </div>
</div>