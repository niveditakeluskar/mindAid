<!-- some labels are changes 

Company or Facility Name  =>specify
Prescribing Provider =>brand
Service Start Date => From Whom 
Service End Date =>  From Where
--> 
<div class="col-md-6 form-group mb-3">
      <label for="lastName1">Type <span class="error">*</span></label>
      @text("type",[])
      <div class="invalid-feedback"></div>
      <!-- <input name="type" type="text" class="form-control"  placeholder=""> -->
</div>

<div class="col-md-6 form-group mb-3">
    <label for="exampleInputEmail1">Purpose <span class="error">*</span></label>
    <!-- <input name="purpose" type="text" class="form-control"   placeholder=""> -->
    <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
    @text("purpose",[])
     <div class='invalid-feedback'></div>
</div>

<div class="col-md-6 form-group mb-3">
    <label for="firstName1">Company or Facility Name <span class="error">*</span></label> 
   <!--  <input name="specify" type="text" class="form-control"  placeholder=""> -->
    @text("specify",[])
    <div class='invalid-feedback'></div>
</div>

<div class="col-md-6 form-group mb-3">
      <label for="lastName1">Prescribing Provider <span class="error">*</span></label>
      @text("brand",[])
      <div class="invalid-feedback"></div>
      <!-- <input name="brand" type="text" class="form-control"  placeholder=""> -->
</div>

<!-- 
<div class="col-md-6 form-group mb-3">
    <label for="exampleInputEmail1"> Duration <span class="error">*</span></label>
    @text("duration",[])
     <div class='invalid-feedback'></div>
</div> -->

<div class="col-md-6 form-group mb-3">
    <label for="lastName1">Service Start Date <span class="error">*</span></label>
    <!-- <input name="from_whom" type="text" class="form-control" placeholder=""> -->
    @date("service_start_date",[])
    <div class='invalid-feedback'></div>
</div>

<div class="col-md-6 form-group mb-3">
    <label for="exampleInputEmail1"> Frequency <span class="error">*</span></label>
    @text("frequency",[])
     <div class='invalid-feedback'></div>
    <!-- <input name="frequency" type="text" class="form-control" placeholder=""> -->
    <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
</div>

<div class="col-md-6 form-group mb-3">
    <label for="lastName1">Service End Date </label> <!-- <span class="error">*</span> -->
    <!-- <input name="from_where" type="text" class="form-control"  placeholder=""> -->
    @date("service_end_date",[])
     <div class='invalid-feedback'></div>
</div>

<div class="col-md-6 form-group mb-3">
    <label for="phone">Additional Notes</label>
    @text("notes")
    <!-- <input name="notes" type="text" class="form-control" placeholder=""> -->
</div>