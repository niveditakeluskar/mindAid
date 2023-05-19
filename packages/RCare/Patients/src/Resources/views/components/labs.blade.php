<div class="card-body">
    <div class="col-md-12 form-group mb-3">
        <div class="form-row">
         <div class="col-md-4 form-group mb-3">
        <label>Labs<span class="error">*</span> :</label><br>
         @selectlab("lab[]",["class"=>"col-md-10", "onchange"=>"carePlanDevelopment.getLabParamsOnLabChange(this)", "id"=>"lab"])
        </div>
        <div class="col-md-4 form-group mb-3">   
         <label>Date<span class="error">*</span> :</label>     
        @date("labdate[]",["id"=>"labdate"])         
     </div>
      <!-- <i class="plus-icons i-Add"  class="btn btn-sprimary float-left"  id="additionallab" title="Add Lab"></i> -->
  </div>
      <div class="form-row mt-1 mb-3" id="append_labs_params_lab"></div>   
        
    </div>
    <hr/>
    <div id="append_labs"></div>  
</div>


<!-- <div class="row">
    <div class="col-md-4  mb-3  form-group"  >
        <label for="Status">Select Labs</label>
        <select class="form-control" id="labs_drop_down">
            <option >Select Lab</option>
            <option value="1" target="cbc">CBC</option>
            <option value="2">BMP</option>
            <option value="3">Lipid Panel</option>
            <option value="4">CMP</option>
            <option value="5">Thyroid Function</option>
            <option value="6">Vitamin</option>
            <option value="7">HgA1C</option>
            <option value="8">BNP</option>
        </select>
    </div>
</div>
<div id="cbc" class="lab1 labsForm" style="display:none;">
    @include('Patients::components.labs.cbc')
</div>
<div id="bmp" class="lab2 labsForm" style="display:none;">
    @include('Patients::components.labs.bmp')
</div>
<div id="lipid_panel" class="lab3 labsForm" style="display:none;">
    @include('Patients::components.labs.lipid')
</div>
<div id="cmp" class="lab4 labsForm" style="display:none;">
    @include('Patients::components.labs.cmp') 
</div>
<div id="thyroid_function" class="lab5 labsForm" style="display:none;">
    @include('Patients::components.labs.thyroid')
</div>
<div id="vitamin" class="lab6 labsForm" style="display:none;">
    @include('Patients::components.labs.vitamin') 
</div>
<div id="hga1c" class="lab7 labsForm" style="display:none;">
    @include('Patients::components.labs.hga1c')
</div>
<div id="bnp" class="lab8 labsForm" style="display:none;">
    @include('Patients::components.labs.bnp')
</div>    -->