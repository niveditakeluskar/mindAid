<!-- some labels are changes 
Company Name =>specify
Prescribing Provider =>brand 
-->
<div class="col-md-6 form-group mb-3">
      <label for="lastName1">Type <span class="error">*</span></label>
      @text("type",[])
      <div class="invalid-feedback"></div>
      <!-- <input name="type" type="text" class="form-control"  placeholder=""> -->
</div>

<div class="col-md-6 form-group mb-3">
      <label for="exampleInputEmail1">Purpose <span class="error">*</span></label>
      @text("purpose",[])
      <div class="invalid-feedback"></div>
      <!-- <input name="purpose" type="text" class="form-control"   placeholder=""> -->
</div>
<div class="col-md-6 form-group mb-3">
      <label for="firstName1">Company Name <span class="error">*</span></label> 
      @text("specify",[])
      <div class="invalid-feedback"></div>
      <!-- <input name="specify" type="text" class="form-control"  placeholder=""> -->
</div>

<div class="col-md-6 form-group mb-3">
      <label for="lastName1">Prescribing Provider <span class="error">*</span></label>
      @text("brand",[])
      <div class="invalid-feedback"></div>
      <!-- <input name="brand" type="text" class="form-control"  placeholder=""> -->
</div>

<div class="col-md-12 form-group mb-3">
      <label for="phone">Additional Notes</label>
      @text("notes")
      <!-- <input name="additional_notes" type="text" class="form-control"  placeholder=""> -->
</div>