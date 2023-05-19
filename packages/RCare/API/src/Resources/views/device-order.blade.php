@extends('Theme::layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
@section('main-content')
<div class="breadcrusmb">
   <div class="row">
      <div class="col-md-4">
         <h4 class="card-title mb-3">Device Order</h4>
      </div>
       <!-- <div class="col-md-8">
        <h4 id="orderno"></h4>
       </div> -->
   </div>
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row mb-4">
   <div class="col-md-12 mb-4">
      <div id="success"></div>
      <div class="card">
         <div class="card-body" id="hobby">
            <div id="error_msg"></div>
            <form action="http://rcareproto2.d-insights.global/rpm/place-device-orders" method="post" name ="device_order_form"  id="device_order_form">
            @csrf
            <div class="alert alert-success" id="success-alert" style="display: none;">
               <button type="button" class="close" data-dismiss="alert">x</button>
               <strong> Device Order added successfully! <br> Order No. is :<div id="orderno"></div></strong><span id="text"></span>
            </div>
            <input type="hidden" name="devicename" id="devicename">
    

              <div class="row">
                <div class="col-md-6 form-group">
                        <label for="practicename">Practice Name</label>
                        @selectrpmpractices("practices", ["id" => "practices", "class" => "select2"])
                    </div>
                 <div class="col-md-6">
                   <label>Patient Name<span class="error">*</span></label>                     
                     @selectpatientenrolledinrpm("patient_id",["id"=>"patient_id","class"=>"select2"])
                 </div>
               </div>
               <br>
               <div class="card">
               <div class="card-body" id="hobby">            
               <div class="row">
                 <div class="col-md-6">
                  <input type="hidden" name="fname">
                   <input type="hidden" name="lname">
                     <label class="form-group">DOB<span class="error">*</span></label>
                    @date("dob")   
                 </div>
                  <div class="col-md-6">
                      <label class="form-group">Phone<span class="error">*</span></label>
                      <!-- <input type="text" id="mob" name="mob" class="form-control"> -->
                     @phone("mob", ["id"=>"mob"])
                 </div>
                  <div class="col-md-6">
                     <div class="form-group">
                    <label for="add_1">Address<span class="error">*</span></label>
                    @text("add_1", ["id" => "add_1"])
                  </div>
                 </div>
                 <div class="col-md-6">
                   <div class="form-group">
                    <label for="emr">EMR#<span class="error">*</span></label>
                    @text("practice_emr", ["id" => "practice_emr"])
                  </div>
                 </div>
              
                 <div class="col-md-6">
                   <div class="form-group">
                 <label>Gender<span class="error">*</span></label>
                    @select("Gender", "gender", [0 => "Male",1 => "Female"])
                  </div>
                </div>

                <div class="col-md-6">
                   <div class="form-group">
                 <label>Office Location<span class="error">*</span></label>
                  @selectpractices("practice_id", ["id" => "practices"]) 
                  </div>
                </div>
                  <div class="col-md-6">
                   <div class="form-group">
                 <label>Provider<span class="error">*</span></label>
                <?php if(isset($patientProvider[0]->provider_id) && $patientProvider[0]->provider_id != ''){ 
                      $physician = $patientProvider[0]->provider_id; 
                    }else{  
                      $physician ='';                      
                    }
                        echo $physician;
                    ?>
                    @select("Primary Care Provider (PCP)", "provider_id", [], ["id" => "physician", "value" => $physician])
                  </div>
                </div>
                
                <div class="col-md-6">                 
                  <div class="form-group">
                    <label for="city">City<span class="error">*</span></label>
                    @text("city", ["id" => "city", "class" => "capitalize"])
                  </div>
                </div>               
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="state">State<span class="error">*</span></label>
                    @selectstate("state", request()->input("state"))
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="zip">Zip Code<span class="error">*</span></label>
                    @text("zipcode", ["id" => "zip"])
                  </div>
                </div>
              </div>               
              </div>
            </div>
          <br>
                <!-- Responsible party -->
              <div class="card">
                 <div class="card-header"> 
                 Responsible party (Financial/Clinical)
                 </div>
               <div class="card-body">            
               <div class="row">
                 <div class="col-md-6">
                   <div class="form-group">
                 <label>First Name<span class="error">*</span></label>
                 @text("family_fname", ["id" => "family_fname"])
                  </div>
                </div>
                 <div class="col-md-6">
                   <div class="form-group">
                 <label>Last Name<span class="error">*</span></label>
                  @text("family_lname", ["id" => "family_lname"])
                  </div>
                </div>
                 <div class="col-md-6">
                 <div class="row">
                   <div class="form-group col-md-6">
                  <label>Phone<span class="error">*</span></label>
                   <!-- <input type="text" id="family_mob" name="family_mob" class="form-control"> -->
                     @phone("family_mob", ["id"=>"family_mob"])
                   </div>
                   <div class="form-group col-md-6">
                      <label>Phone Type<span class="error">*</span></label>
                   @text("phone_type", ["id" => "phone_type"])
                     </div>
                </div>
              </div>
                
                 <div class="col-md-6">
                   <div class="form-group">
                  <label for="family_add">Address<span class="error">*</span></label>
                    @text("family_add", ["id" => "family_add"])
                  </div>
                </div>
                 <div class="col-md-6">
                   <div class="form-group">
                 <label>Email<span class="error">*</span></label>
                  @email("email", ["id" => "email"])
                  </div>
                </div>
                 <div class="col-md-6">
                   <div class="form-group">
                 <label>Relationship<span class="error">*</span></label>
                 @text("Relationship", ["id" => "Relationship"])
                  </div>
                </div>


               </div>               
              </div>
            </div>

            <br>
                <!-- Responsible party -->
              <div class="card">                 
               <div class="card-body">            
               <div class="row">
                 
                 <div class="col-md-6">
                   <div class="form-group"> 
                    <label for="name" class="control-label">Device</label>              
                   <div class="wrapMulDrop">
                   <button type="button" id="multiDrop" class="multiDrop form-control col-md-12">Device Type<i style="float:right;" class="icon ion-android-arrow-dropdown"></i></button>
                    <ul>
                         <li>
                             <label class="forms-element checkbox checkbox-outline-primary"> 
                               <input class="" name ="device_type[ECG_PROHEALTH_PULSE]"  id ="ECG_PROHEALTH_PULSE" value="ECG_PROHEALTH_PULSE" type="checkbox"><span class="">ECG_PROHEALTH_PULSE</span><span class="checkmark"></span>             
                            </label>
                          </li>
                           <li>
                             <label class="forms-element checkbox checkbox-outline-primary"> 
                               <input class="" name ="device_type[ECG_PROHEALTH_GLUCOSE]"  id ="ECG_PROHEALTH_GLUCOSE" value="ECG_PROHEALTH_GLUCOSE" type="checkbox"><span class="">ECG_PROHEALTH_GLUCOSE</span><span class="checkmark"></span>             
                            </label>
                          </li>
                           <li>
                             <label class="forms-element checkbox checkbox-outline-primary"> 
                               <input class="" name ="device_type[ECG_PROHEALTH_GLUCOSE_N]"  id ="ECG_PROHEALTH_GLUCOSE_N" value="ECG_PROHEALTH_GLUCOSE_N" type="checkbox"><span class="">ECG_PROHEALTH_GLUCOSE_N</span><span class="checkmark"></span>             
                            </label>
                          </li>
                           <li>
                             <label class="forms-element checkbox checkbox-outline-primary"> 
                               <input class="" name ="device_type[ECG_PROHEALTH_SP]"  id ="ECG_PROHEALTH_SP" value="ECG_PROHEALTH_SP" type="checkbox"><span class="">ECG_PROHEALTH_SP</span><span class="checkmark"></span>             
                            </label>
                          </li>
                           <li>
                             <label class="forms-element checkbox checkbox-outline-primary"> 
                               <input class="" name ="device_type[ECG_PROHEALTH_TEMP]"  id ="ECG_PROHEALTH_TEMP" value="ECG_PROHEALTH_TEMP" type="checkbox"><span class="">ECG_PROHEALTH_TEMP</span><span class="checkmark"></span>             
                            </label>
                          </li>
                           <li>
                             <label class="forms-element checkbox checkbox-outline-primary"> 
                               <input class="" name ="device_type[ECG_PROHEALTH_WEIGHT]"  id ="ECG_PROHEALTH_WEIGHT" value="ECG_PROHEALTH_WEIGHT" type="checkbox"><span class="">ECG_PROHEALTH_WEIGHT</span><span class="checkmark"></span>             
                            </label>
                          </li>
                           <li>
                             <label class="forms-element checkbox checkbox-outline-primary"> 
                               <input class="" name ="device_type[ECG_PROHEALTH_BP]"  id ="ECG_PROHEALTH_BP" value="ECG_PROHEALTH_BP" type="checkbox"><span class="">ECG_PROHEALTH_BP</span><span class="checkmark"></span>             
                            </label>
                          </li>                          
                    </ul>
                   </div>
                 </div>
                </div>
                 <div class="col-md-6" style="display: none">
                   <div class="form-group">
                    <label for="family_add">Action Plan<span class="error">*</span></label>
                    @text("action_plan", ["id" => "action_plan"])
                  </div>
                </div>
               <!--<div class="col-md-6">
                   <div class="form-group">
                  <label>Group<span class="error">*</span></label>
                    <select class="form-control show-tick">
                      <option>select Group</option>
                    </select>                     
                     </div>
                </div>-->
                 <div class="col-md-3" id="BP_Option" style="display: none">
                   <div class="form-group">
                 <label>Cuff size<span class="error">*</span></label>
                 <div class="mr-3 d-inline-flex align-self-center">
                        <label for="small" class="radio radio-primary mr-3">
                        <input type="radio" name="size" id="small" value="Small" formControlName="radio"
                           >
                        <span>Small</span>
                        <span class="checkmark"></span>
                        </label>
                        <label for="Medium" class="radio radio-primary mr-3">
                        <input type="radio" name="size" id="Medium"  value="Medium" formControlName="radio"
                           >
                        <span>Medium</span>
                        <span class="checkmark"></span>
                        </label>
                         <label for="Large" class="radio radio-primary mr-3">
                        <input type="radio" name="size" id="Large"  value="Large" formControlName="radio"
                           >
                        <span>Large</span>
                        <span class="checkmark"></span>
                        </label>
                     </div>
                   </div>
                 </div>
                     <br>
                      <div class="col-md-3" id="Weight_Option" style="display: none">
                          <div class="form-group">
                     <label>Device weight<span class="error">*</span></label><br>
                        <div class="mr-3 d-inline-flex align-self-center">
                        <label for="small" class="mr-3">
                        <input type="radio" name="weight" id="weight150" value="150kg" 
                           >
                        <span>150pounds</span>
                        <span class="checkmark"></span>
                        </label>
                        <label for="Medium" class="mr-3">
                        <input type="radio" name="weight" id="weight250"  value="250kg" 
                           >
                        <span>250pounds</span>
                        <span class="checkmark"></span>
                        </label>                        
                     </div>

                   </div>
                  </div>               
                                
               </div>               
              </div>
            </div>
           <!-- billing -->
           <br>
            <div class="card" >                 
               <div class="card-body">            
               <div class="row">
               	  <div class="col-md-6">
                   <div class="form-group">
                  <!-- <label> <span class="error">*</span></label> -->
                     @checkbox("Is the Billing same as the patient details", "billing", "billing")
                     </div>
                </div>
               </div>
                 <div class="row" id="billing_div">
                 	       <div class="card">
                 <div class="card-header"> 
                 Billing
                 </div>
               <div class="card-body">            
               <div class="row">
                 <div class="col-md-6">
                   <div class="form-group">
                 <label>First Name<span class="error">*</span></label>
                 @text("billing_fname", ["id" => "billing_fname"])
                  </div>
                </div>
                 <div class="col-md-6">
                   <div class="form-group">
                 <label>Last Name<span class="error">*</span></label>
                  @text("billing_lname", ["id" => "billing_lname"])
                  </div>
                </div>
                 <div class="col-md-6">               
                   <div class="form-group">
                  <label>Phone<span class="error">*</span></label>
                    <!-- <input type="text" id="billing_mob" name="billing_mob" class="form-control"> -->
                     @phone("billing_mob", ["id"=>"billing_mob"])                               
                </div>
              </div>
                
                 <div class="col-md-6">
                   <div class="form-group">
                  <label for="family_add">Address<span class="error">*</span></label>
                    @text("billing_add", ["id" => "billing_add"])
                  </div>
                </div>
                 <div class="col-md-6">
                   <div class="form-group">
                 <label>Email<span class="error">*</span></label>
                  @email("billing_email", ["id" => "billing_email"])
                  </div>
                </div>
                 <div class="col-md-6">
                 <div class="row">
                   <div class="form-group col-md-4">
                  <label for="city">City<span class="error">*</span></label>
                    @text("billing_city", ["id" => "billing_city", "class" => "capitalize"])
                   </div>
                   <div class="form-group col-md-4">
                       <label for="state">State<span class="error">*</span></label>
                      @selectstate("billing_state", request()->input("state"))
                     </div>
                      <div class="form-group col-md-4">
                      <label for="zip">Zip Code<span class="error">*</span></label>
                      @text("billing_zipcode", ["id" => "zip"])
                     </div>
                </div>
              </div>                
               </div>               
              </div>
               </div>
              </div>
             </div>
            </div>
            <br>
            <!-- Shipping -->
            <div class="card" >                             
               <div class="card-body">            
               <div class="row">
               	  <div class="col-md-6">
                   <div class="form-group">
                  <!-- <label> <span class="error">*</span></label> -->
                     @checkbox("Is the Headquaters Address same as the Device Billing Address", "headqadd", "headqadd")
                     </div>
                </div>
               </div>
                 <div class="row" id="shipping_div">
                 	       <div class="card">
                 <div class="card-header"> 
                 Shipping
                 </div>
               <div class="card-body" >            
               <div class="row">
                 <div class="col-md-6">
                   <div class="form-group">
                 <label>First Name<span class="error">*</span></label>
                 @text("shipping_fname", ["id" => "shipping_fname"])
                  </div>
                </div>
                 <div class="col-md-6">
                   <div class="form-group">
                 <label>Last Name<span class="error">*</span></label>
                  @text("shipping_lname", ["id" => "shipping_lname"])
                  </div>
                </div>
                 <div class="col-md-6">               
                   <div class="form-group">
                  <label>Phone<span class="error">*</span></label>
                    <!-- <input type="text" id="shipping_mob" name="shipping_mob" class="form-control">  -->
                           @phone("shipping_mob", ["id"=>"shipping_mob"])                    
                   </div>                  
                
              </div>
                
                 <div class="col-md-6">
                   <div class="form-group">
                  <label for="family_add">Address<span class="error">*</span></label>
                    @text("shipping_add", ["id" => "shipping_add"])
                  </div>
                </div>
                 <div class="col-md-6">
                   <div class="form-group">
                 <label>Email<span class="error">*</span></label>
                  @email("shipping_email", ["id" => "shipping_email"])
                  </div>
                </div>
                 <div class="col-md-6">
                 <div class="row">
                   <div class="form-group col-md-4">
                  <label for="city">City<span class="error">*</span></label>
                    @text("shipping_city", ["id" => "shipping_city", "class" => "capitalize"])
                   </div>
                   <div class="form-group col-md-4">
                       <label for="state">State<span class="error">*</span></label>
                      @selectstate("shipping_state", request()->input("state"))
                     </div>
                      <div class="form-group col-md-4">
                      <label for="zip">Zip Code<span class="error">*</span></label>
                      @text("shipping_zipcode", ["id" => "shipping_zip"])
                     </div>
                </div>
              </div>
                 <div class="col-md-6">              
                   <div class="form-group">
                  <label>Option<span class="error">*</span></label>
                    <select class="form-control show-tick" id="shipping_option" name="shipping_option">
                    	<option value="">Select Option</option>
                        <option value="Ground Free">Ground Free</option>
                        <option value="Standard Free">Standard Free</option>
                        <option value="3 day">3 day</option>
                        <option value="2 day">2 day</option>
                        <option value="1 day">1 day</option>
                    </select>
                   </div>
               </div>               
              </div>
               </div>
              </div>
             </div>
            </div>
          </div>
              
             <br>
            <div class="row">
               <div class="col-12 text-right form-group mb-4">
                  <button type="submit" class="btn btn-primary">Place Order</button>
               </div>
            </div>
            </form>
         </div>
      </div>
   </div>
</div>


<div id="app"></div>
@endsection
@section('page-js')
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script>
     $(document).ready(function() {
        

           $("[name='practices']").on("change", function () {  
                util.updatePatientList(parseInt($(this).val()),'2', $("#patient_id"));   
            });         
             

           $("[name='patient_id']").on("change", function () { 
                var patient_id = $(this).val(); 
                 var data = "";
                var formpopulateurl = '/rpm/getPatientDetails/'+patient_id;               
                        populateForm(data, formpopulateurl);     
            });

          form.ajaxForm("device_order_form", onDeviceOrderForm, function () {   
            return true;
         });

  });

     var onDeviceOrderForm = function (formObj, fields, response) {
 
  if (response.status == 200) {
    var response_data=jQuery.parseJSON(response.data);
    // console.log(response_data.sourceId+" testdata "+response.data[0].sourceId+"      data test ");
    
   $('#orderno').html(response_data.sourceId);
    $("form[name='device_order_form']")[0].reset();
      $("form[name='device_order_form'] .alert-success").show();
      var scrollPos = $(".main-content").offset().top;
      $(window).scrollTop(scrollPos);
    //goToNextStep("call_step_1_id");
    // setTimeout(function () {
    //   $('.alert').fadeOut('fast');//goToNextStep("drug-icon-pill"); 
    // }, 3000);
  }
}

      $('#billing').change(function() {     
        if(this.checked) {         
           $('#billing_div').css("display", "none");
        }      
        else
        {
          $('#billing_div').css("display", "block");
        }

    });
       $('#shipping').change(function() {
     
        if(this.checked) {        
          $('#shipping_div').hide();        
          // $('#Shipping_div').css("display", "none");
        }      
        else
        {
          $('#shipping_div').css("display", "block");
        }

    });

    

     var populateForm = function (data, url) {
       $.get(
          url,
           data,
            function (result) {
              for (var key in result) {              
                form.dynamicFormPopulate(key, result[key]);
                if(key="device_order_form")
                {

                  $('#family_fname').val(result[key].patient_Responsibility.fname);
                  $('#family_lname').val(result[key].patient_Responsibility.lname);
                  $('#family_address').val(result[key].patient_Responsibility.address);
                  $('#family_mob').val(result[key].patient_Responsibility.mobile);
                  $('#Relationship').val(result[key].patient_Responsibility.relationship);

                }
                util.updatePcpPhysicianList( 
                 parseInt($("[name='practice_id']").val()),
                  $("#physician"),
                   result[key].static.provider_id
                    );
            }
         }
  ).fail(function (result) {
    console.error("Population Error:", result);
  });

};

     //Multiple Dropdown Select
    $('.multiDrop').on('click', function (event) { 
        event.stopPropagation();
        $(this).next('ul').slideToggle();
       });

    $(document).on('click', function () {
        if (!$(event.target).closest('.wrapMulDrop').length) {
            $('.wrapMulDrop ul').slideUp();
        }

    }); 
    
    $('.wrapMulDrop ul li input[type="checkbox"]').on('change', function () {
      
        var x = $('.wrapMulDrop ul li input[type="checkbox"]:checked').length;       
      //'input[name^="device_type"]'
       $('.wrapMulDrop ul li input[type="checkbox"]').each(function() {    
              if (this.checked) {
              	if($(this).val()=='ECG_PROHEALTH_WEIGHT')
              	{
                    $('#Weight_Option').css("display", "block");
              	}
              	if($(this).val()=='ECG_PROHEALTH_BP')
              	{
              	    $('#BP_Option').css("display", "block");
              	}
              }
              else
              {
              	if($(this).val()=='ECG_PROHEALTH_WEIGHT')
              	{
                    $('#Weight_Option').css("display", "none");
              	}
              	if($(this).val()=='ECG_PROHEALTH_BP')
              	{
              	    $('#BP_Option').css("display", "none");
              	}
              }
       
            });       
      
        if (x != "") {
            $('.multiDrop').html(x + " " + "selected");
        } else if (x < 1) {
            
            $('.multiDrop').html('Select Responsibility<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
        }        
    });



     
    
</script>
@endsection