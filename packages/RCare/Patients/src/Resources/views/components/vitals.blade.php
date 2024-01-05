<div class="col-md-4 form-group mb-3">
    <label for="height">Height (in)<!-- <span class="error">*</span> --> :</label>
    @text("height", ["id" => "height", "onchange"=>"carePlanDevelopment.onChangeNumberTrackingVitalsWeightOrHeight();"]) 
</div>
<div class="col-md-4 form-group mb-3">
    <label for="weight">Weight (lbs)<!-- <span class="error">*</span> --> :</label> 
    @text("weight", ["id" => "weight", "onchange"=>"carePlanDevelopment.onChangeNumberTrackingVitalsWeightOrHeight();"])
</div>
<div class="col-md-4 form-group mb-3">
    <label for="bmi">BMI<!-- <span class="error">*</span> --> :</label>
     @text("bmi", ["id" => "bmi","readonly"=>"readonly"])
</div>
<div class="col-md-4 form-group mb-3">
    <label for="bp">Blood Pressure<!-- <span class="error">*</span> --> :</label>
    <div class="form-row col-md-12 form-group">
        <span class="col-md-5">
            @text("bp", ["id" => "bp","placeholder"=>"Systolic"])
        </span>
        <span class="mt-1 pl-2 pr-2"> / </span>
        <span class="col-md-6">
            @text("diastolic", ["id" => "diastolic","placeholder"=>"Diastolic"])
        </span>
    </div>
</div>
<div class="col-md-4 form-group mb-3">
    <label for="o2">O2 Saturation<!-- <span class="error">*</span> --> :</label>
     @text("o2", ["id" => "o2"])
</div>
<div class="col-md-2 form-group mb-3">
    <label for="pulse_rate">Pulse Rate<!-- <span class="error">*</span>  -->:</label>
    @text("pulse_rate", ["id" => "pulse_rate"])
</div>
<div class="col-md-2 form-group mb-3">
    <label for="pain_level">Pain Level<!-- <span class="error">*</span>  -->:</label>
    <select name="pain_level" id="pain_level" class="custom-select show-tick" >
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
    </select>
</div>
<div class="col-md-12 form-group mb-3">
    <div class="mr-3 d-inline-flex align-self-center">
      <label class="radio radio-primary mr-3">
         <input type="radio" id ="yes" name="oxygen" value="1" formControlName="radio">
         <span>Room Air</span>
         <span class="checkmark"></span>
      </label> 
      <label class="radio radio-primary mr-3">
         <input type="radio" id="no" name="oxygen" value="0" formControlName="radio">
         <span>Supplemental Oxygen</span>
         <span class="checkmark"></span>
      </label> 
    </div>
</div>  
<div class="col-md-12 mr-3 mb-3" id="Supplemental_notes_div" style="display:none"> 
  <label>Notes</label> 
    <textarea class="form-control forms-element" name="notes"></textarea>
    <div class="invalid-feedback"></div>
</div> 