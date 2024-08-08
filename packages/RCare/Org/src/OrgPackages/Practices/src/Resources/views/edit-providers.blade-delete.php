<div class="row edit_provider_div">
  <div class="col-md-3  form-group mb-3 ">
    <label for="practicename">Provider Name</label> 
    <!-- @selectprovider("provider_name_append[]") -->
     @text("provider_name_append[]", ["placeholder" => "Enter Provider Name", "class"=> "provider_name"])
  </div>
  <div class="col-md-3  form-group mb-3 ">
    <label for="practicename">Provider Type</label>
    @selectprovidertypes("provider_type_id_append[]")
  </div>
  <div class="col-md-3  form-group mb-3" id="specilist">
    <label for="practicename">Provider Sub Type</label>
    @selectspecialpractices("provider_subtype_id_append[]")
  </div>
  <div class="col-md-3  form-group mb-3">
    <i type="button" id="remove-add-provider" class="btn btn-danger m-1 sm remove_edit_provider">Remove</i> 
  </div>
</div> 
 