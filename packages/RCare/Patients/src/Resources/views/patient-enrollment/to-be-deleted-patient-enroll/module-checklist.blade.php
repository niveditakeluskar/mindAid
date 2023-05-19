<div class="card">

<div class="card-body">

   <div class="form-row">
      <div class="form-group col-md-7" id="from">
         <strong class="mr-1">Module</strong>                           
        
         <label _ngcontent-xbc-c9="" class="checkbox checkbox-info col-md-4">
                <input _ngcontent-xbc-c9=""  type="checkbox" value="2" name="enroll_module[2]" id="enroll_rpm" <?php foreach($patientservice as $key => $value){
                    if($value->module_id == 2){echo "checked";}
                } ?> >
                <span _ngcontent-xbc-c9="">RPM</span><span _ngcontent-xbc-c9="" class="checkmark" ></span>
            </label>
            <label _ngcontent-xbc-c9="" class="checkbox checkbox-info col-md-4">
                <input _ngcontent-xbc-c9=""  type="checkbox" value="3" name="enroll_module[3]" id="enroll_ccm" <?php foreach($patientservice as $key => $value){
                    if($value->module_id == 3){echo "checked";}
                } ?> >
                <span _ngcontent-xbc-c9="">CCM</span><span _ngcontent-xbc-c9="" class="checkmark"></span>
            </label>
            <label _ngcontent-xbc-c9="" class="checkbox checkbox-info col-md-4">
                <input _ngcontent-xbc-c9=""  type="checkbox" value="4" name="enroll_module[4]" id="enroll_awv" <?php foreach($patientservice as $key => $value){
                    if($value->module_id == 4){echo "checked";}
                } ?> >
                <span _ngcontent-xbc-c9="">AWV</span><span _ngcontent-xbc-c9="" class="checkmark"></span>
            </label>
            <label _ngcontent-xbc-c9="" class="checkbox checkbox-info col-md-4">
                <input _ngcontent-xbc-c9=""  type="checkbox" value="5" name="enroll_module[5]" id="enroll_tcm" <?php foreach($patientservice as $key => $value){
                    if($value->module_id == 5){echo "checked";}
                } ?> >
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