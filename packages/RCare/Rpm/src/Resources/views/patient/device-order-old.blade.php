@extends('Theme::layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
@section('main-content')
<div class="breadcrusmb">
   <div class="row">
      <div class="col-md-4">
         <h4 class="card-title mb-3">Device Orders</h4>
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
            <form action="{{route("place.device.orders")}}" method="post" name ="device_order_form"  id="device_order_form">
               @csrf
               <div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>
                     Device Order added successfully! <br> Order No. is :
                     <div id="orderno"></div>
                  </strong>
                  <span id="text"></span>
               </div>
               <div id="error_msg"></div>
               <input type="hidden" name="devicename" id="devicename">
               <?php $addresslocation =""; if(!empty($data1[0]->bill_address)){$addresslocation = $data1[0]->bill_address; } ?>   
               <div class="row">
                  <div class="col-md-6 form-group ">                    
                     <label for="practicename">Practice Name</label>                     
                     @selectrpmpractices("practices", ["id" => "practices", "class" => "select2 "])    
                     <input type="hidden" name="practiceid" id="practiceid">            
                </div>
                  <div class="col-md-6 form-group">
                     <label>Patient Name<span class="error">*</span></label>                     
                     @selectpatientenrolledinrpm("patient_id",["id"=>"patient_id","class"=>"select2"])
                  </div>
               </div>
               <br>
               <div class="card">
                  <div class="card-body" id="hobby">
                     <div class="row">
                        <div class="col-md-4 form-group">                
                           <label class="form-group">First Name<span class="error">*</span></label>
                           @text("fname", ["id" => "fname"])  
                        </div>
                        <div class="col-md-4 form-group">                
                           <label class="form-group">Last Name<span class="error">*</span></label>
                           @text("lname", ["id" => "lname"])  
                        </div>
                        <div class="col-md-4 form-group">                 
                           <label class="form-group">DOB<span class="error">*</span></label>
                           @date("dob", ["id"=>"dob"])   
                        </div>
                        <div class="col-md-4 form-group">
                           <label class="form-group">Phone<span class="error">*</span></label>
                           <!-- <input type="text" id="mob" name="mob" class="form-control"> -->
                           @phone("mob", ["id"=>"mob"])
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="add_1">Address<span class="error">*</span></label>
                              @text("add_1", ["id" => "add_1"])
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="emr">EMR#</label>
                              @text("practice_emr", ["id" => "practice_emr"])
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label>Gender<span class="error">*</span></label>
                              <!-- @select("Gender", "gender", [0 => "Male",1 => "Female"]) -->
                              <select name="gender" id="gender" class="custom-select show-tick">
                                 <option value="">Select Gender</option>
                                 <option value="0" id="gender">Male</option>
                                 <option value="1" id="gender">Female</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label>Office Location<span class="error">*</span></label>
                              <!-- @selectpractices("practice_id", ["id" => "practices"])   -->
                              @text("officelocation", ["id" => "officelocation"])
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label>Provider<span class="error">*</span></label>
                              <?php 
                              // if(isset($patientProvider[0]->provider_id) && $patientProvider[0]->provider_id != ''){ 
                              //    $physician = $patientProvider[0]->provider_id; 
                              //    }else{  
                              //    $physician ='';                      
                              //    }
                              //      echo $physician."testprovider";
                                 ?>
                              @select("Primary Care Provider (PCP)", "provider_id", [], ["id" => "physician"])
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="city">City<span class="error">*</span></label>
                              @text("city", ["id" => "city", "class" => "capitalize"])
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="state">State<span class="error">*</span></label>
                              @selectstate("state",["id" => "state"])
                           </div>
                        </div>
                        <div class="col-md-4">
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
                     Responsible Party 
                  </div>
                  <div class="card-body">
                     <div class="row">
                        <div class="col-md-3">
                           <div class="form-group">
                              <label>First Name<span class="error">*</span></label>
                              @text("family_fname", ["id" => "family_fname"])
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label>Last Name<span class="error">*</span></label>
                              @text("family_lname", ["id" => "family_lname"])
                           </div>
                        </div>
                        <!--<div class="col-md-3">-->
                        <!--  <div class="row"> -->
                        <div class="form-group col-md-3">
                           <label>Phone<span class="error">*</span></label>
                           <!-- <input type="text" id="family_mob" name="family_mob" class="form-control"> -->
                           @phone("family_mob", ["id"=>"family_mob"])
                        </div>
                        <div class="form-group col-md-3">
                           <label>Phone Type<span class="error">*</span></label>
                           @text("phone_type", ["id" => "phone_type"])
                        </div>
                        <!--  </div> -->
                        <!--</div>-->
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="family_add">Address</label>
                              @text("family_add", ["id" => "family_add"])
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label>Email<span class="error">*</span></label>
                              @email("email", ["id" => "email"])
                           </div>
                        </div>
                        <div class="col-md-3">
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
                              <label for="name" class="control-label">Device<span class="error">*</span></label>                
                              <div class="wrapMulDrop">
                                 <button type="button" id="multiDrop" name="multiDrop" class="multiDrop form-control col-md-12">Device Type<i style="float:right;" class="icon ion-android-arrow-dropdown"></i></button>
                                 <ul>
                                    <li>
                                       <label class="forms-element checkbox checkbox-outline-primary"> 
                                       <input class="" name ="device_type[ECG_PROHEALTH_BP]"  id ="ECG_PROHEALTH_BP" value="ECG_PROHEALTH_BP" type="checkbox"><span class="">Blood Pressure Monitor</span><span class="checkmark"></span>             
                                       </label>
                                    </li>
                                    <li>
                                       <label class="forms-element checkbox checkbox-outline-primary"> 
                                       <input class="" name ="device_type[ECG_PROHEALTH_GLUCOSE_N]"  id ="ECG_PROHEALTH_GLUCOSE_N" value="ECG_PROHEALTH_GLUCOSE_N" type="checkbox"><span class="">Glucose Monitor With Monthly Refills (Extra - $25 Monthly)</span><span class="checkmark"></span>             
                                       </label>
                                    </li>
                                    <li>
                                       <label class="forms-element checkbox checkbox-outline-primary"> 
                                       <input class="" name ="device_type[ECG_PROHEALTH_GLUCOSE]"  id ="ECG_PROHEALTH_GLUCOSE" value="ECG_PROHEALTH_GLUCOSE" type="checkbox"><span class="">Glucose Monitor Without Monthly Refills</span><span class="checkmark"></span>             
                                       </label>
                                    </li>
                                    <li>
                                       <label class="forms-element checkbox checkbox-outline-primary">  
                                       <input class="" name ="device_type[ECG_PROHEALTH_PULSE]"  id ="ECG_PROHEALTH_PULSE" value="ECG_PROHEALTH_PULSE" type="checkbox"><span class="">Pulse Oximeter</span><span class="checkmark"></span>              
                                       </label>
                                    </li>
                                    <li>
                                       <label class="forms-element checkbox checkbox-outline-primary"> 
                                       <input class="" name ="device_type[ECG_PROHEALTH_SP]"  id ="ECG_PROHEALTH_SP" value="ECG_PROHEALTH_SP" type="checkbox"><span class="">Spirometer</span><span class="checkmark"></span>             
                                       </label>
                                    </li>
                                    <li>
                                       <label class="forms-element checkbox checkbox-outline-primary"> 
                                       <input class="" name ="device_type[ECG_PROHEALTH_TEMP]"  id ="ECG_PROHEALTH_TEMP" value="ECG_PROHEALTH_TEMP" type="checkbox"><span class="">Thermometer</span><span class="checkmark"></span>             
                                       </label>
                                    </li>
                                    <li>
                                       <label class="forms-element checkbox checkbox-outline-primary"> 
                                       <input class="" name ="device_type[ECG_PROHEALTH_WEIGHT]"  id ="ECG_PROHEALTH_WEIGHT" value="ECG_PROHEALTH_WEIGHT" type="checkbox"><span class="">Weight Scale</span><span class="checkmark"></span>             
                                       </label>
                                    </li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="ordertype">Order Type<span class="error">*</span></label>
                              <select name="ordertype" class="form-control custom-select show-tick">
                                 <option value="">Select Order Type</option>
                                 <option value="renova" >PERS</option>
                                 <option value="renova2" >Non PERS</option>
                              </select>
                              <div class="invalid-feedback"></div>
                           </div>
                        </div>
                        <!--  <div class="col-md-3">
                           <div class="form-group">
                              <label for="package">Package<span class="error">*</span></label>
                              <select name="package" class="custom-select show-tick">
                                 <option value="">Select Package</option>
                                 <option value="ECG_PROHEALTH_PACKAGE">ECG PROHEALTH PACKAGE</option>
                                 <option value="ECG_SERIES_1_PACKAGE">ECG SERIES 1 PACKAGE</option>
                                 <option value="ECG_SERIES_2_PACKAGE">ECG SERIES 2 PACKAGE</option>
                                 <option value="ECG_SERIES_3_PACKAGE ">ECG SERIES 3 PACKAGE </option>
                              </select>
                           </div>
                        </div> -->
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
                              <label>Cuff Sizes<span class="error">*</span></label>
                              <div class="mr-3 d-inline-flex align-self-center">
                                 <label for="small" class="radio radio-primary mr-3">
                                 <input type="radio" name="size" id="small" value="Small" formControlName="radio">
                                 <span>Small</span>
                                 <span class="checkmark"></span>
                                 </label>
                                 <label for="Medium" class="radio radio-primary mr-3">
                                 <input type="radio" name="size" id="Medium"  value="Medium" formControlName="radio">
                                 <span>Medium</span>
                                 <span class="checkmark"></span>
                                 </label>
                                 <label for="Large" class="radio radio-primary mr-3">
                                 <input type="radio" name="size" id="Large"  value="Large" formControlName="radio">
                                 <span>Large</span>
                                 <span class="checkmark"></span>
                                 </label>
                                 <label for="Large" class="radio radio-primary mr-3">
                                 <input type="radio" name="size" id="Extra Large"  value="Extra Large" formControlName="radio">
                                 <span>Extra Large</span>
                                 <span class="checkmark"></span>
                                 </label>
                              </div>
                           </div>
                        </div>
                        <br>
                        <div class="col-md-3" id="Weight_Option" style="display: none">
                           <div class="form-group">
                              <label>Weight Capacity<span class="error">*</span></label><br>  
                              <div class="mr-3 d-inline-flex align-self-center">
                                 <label for="small" class="mr-3">
                                 <input type="radio" name="weight" id="weight150" value="150kg" 
                                    >
                                 <span>150kg -330 lbs</span>
                                 <span class="checkmark"></span>
                                 </label>
                                 <label for="Medium" class="mr-3">
                                 <input type="radio" name="weight" id="weight250"  value="250kg" 
                                    >
                                 <span>250kg - 551 lbs</span>
                                 <span class="checkmark"></span>
                                 </label>                        
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
                              <label>Shipping Speed<span class="error">*</span></label>
                              <select class="form-control custom-select show-tick" id="shipping_option" name="shipping_option">
                                 <option value="">Shipping Method</option>
                                 <!-- <option value="Ground Free">Ground Free</option>
                                    <option value="Standard Free">Standard Free</option>
                                    <option value="3 day">Three-Day Shipping</option>
                                    <option value="2 day">Two-Day Shipping</option>
                                    <option value="1 day">One-Day Shipping</option> -->
                                 <option value="Ground Free">Free: 5 -7 Business Days</option>
                                 <option value="3 day">$25: 3 day express</option>
                                 <option value="2 day">$50: 2 day express</option>
                                 <option value="1 day">$75: overnight</option>
                              </select>
                           </div>
                        </div>
                     </div>                     
                  </div>
               </div>
               <br>
               <!-- Med reminder -->
               <div class="card" >                  
                  <div class="card-header"> 
                    Med Reminder
                  </div>                 
                  <div class="card-body">                     
                     <div class="row">
                        <div class="col-md-3">
                           <div class="form-group">
                              <label>Time</label>
                             @time('time[]',["id"=>"time_0"])
                           </div>
                        </div>
                        <div class="col-md-3 form-group">
                             <label>Message</label>
                               @text("message[]", ["id" => "message_0"])  
                        </div>
                         <div class="col-md-1" style="margin-top: 25px;">
                            <label></label>
                           <i class="plus-icons i-Add"  class="btn btn-sprimary"  id="additionalmedtime" title="Add Time"></i>
                         </div>
                        
                     </div> 
                        
                      <div class="col-md-12 pt-1" id="append_medreminder"></div>                 
                  </div>
               </div>
               <br>
                <div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>
                     Device Order added successfully! <br> Order No. is :
                     <div id="orderno1"></div>
                  </strong>
                  <span id="text"></span>
               </div>
               <div id="error_msg1"></div>
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
<script src="{{asset('assets/js/tooltip.script.js')}}"></script>
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
   console.log(response.status);
   if (response.status == 200) {
     // debugger
       var response_validation=response.data.validation;
          
        // var scrollPos = $(".main-content").offset().top;
        // $(window).scrollTop(scrollPos); 
       if(response_validation=="" || response_validation==undefined)
       {
          var response_data=jQuery.parseJSON(response.data); 
             $("#error_msg").hide();
             $("#error_msg1").hide();
            $('#orderno').html(response_data.sourceId);
             $('#orderno1').html(response_data.sourceId);
            $("form[name='device_order_form']")[0].reset();
             $("form[name='device_order_form'] .alert-success").show();                     
            setTimeout(function () {
              window.location.href = '/rpm/device-order-list';     
            }, 3000);
       }
       else
       {          
               var validation= response_validation.join(", \n");  
               console.log(validation+"checkvalidn") ;          
               if(validation!="")
               {
                    $("#error_msg").show();
                    $("#error_msg1").show();
              var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>'+validation+'</strong></div>';
                $("#error_msg").html(txt);
                $("#error_msg1").html(txt);
             }
       }      
  
   }
   else
   {  
                $("#error_msg").show();
                 $("#error_msg1").show();
              var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill all mandatory field!</strong></div>';
                $("#error_msg").html(txt);
                $("#error_msg1").html(txt);
             
   }
  
   }
   
   //  $('#billing').change(function() {     
   //    if(this.checked) {         
   //       $('#billing_div').css("display", "none");
   //    }      
   //    else
   //    {
   //      $('#billing_div').css("display", "block");
   //    }
   
   // });
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
   
   $(document).on("click", ".remove-icons", function () {

      var button_id = $(this).attr('id');
     var res = button_id.split("_");
      $('#btn_removemedtime_' + res[2]).remove();
   });

   var count=0;
   $('#additionalmedtime').click(function () {
      count++;
      $('#append_medreminder').append('<div class=" row btn_remove" id="btn_removemedtime_' + count + '"><div class="col-md-3"><input id="time_'+count+'" name="time[]" type="time"  autocomplete="off" class="form-control"></div><div class="col-md-3"><input id="message_'+count+'" name="message[]" type="text" autocomplete="off" class="form-control"></div><div class="col-md-1"><i class="remove-icons i-Remove mb-3" id="remove_medtime_' + count + '" title="Remove Med time"></i></div><br><br></div>');

   });
   
   var populateForm = function (data, url) {
     $.get(
        url,
         data,
          function (result) {
            for (var key in result) {     
              // console.log(result);         
              form.dynamicFormPopulate(key, result[key]);
              // console.log(result[key].static.fname); 
              // console.log(key);
              if(key="patient_registration_form")
              {
                // console.log("1"+result[key].static.fname);
                // console.log("2"+result[key].static['fname']);  
                // console.log("3"+result[key].static);   
                // $('#family_fname').val(result[key].static.fname);
                // $('#family_lname').val(result[key].static.lname);
                // $('#family_address').val(result[key].static.address);
                // $('#family_mob').val(result[key].static.mobile);
                // $('#Relationship').val(result[key].static.relationship);
   
                // $('#fname').val(result[key].static.fname);
                var addresslocation = "<?php echo $addresslocation; ?>"
                $('#fname').val(result[key].static.fname);  
                $('#lname').val(result[key].static.lname);
                $('#add_1').val(result[key].static.add_1);
                $('#mob').val(result[key].static.mob);
                $('#dob').val(result[key].static.dob);
                $('#gender').val(result[key].static.gender);
                $('#city').val(result[key].static.city);
                $('#state').val(result[key].static.state); 
                $('#practice_emr').val(result[key].static.practice_emr);
                $('#zip').val(result[key].static.zipcode);
                $('#officelocation').val(addresslocation); 
                $('#physician').val(result[key].static.provider_id);     
                var practice_id=result[key].static.practice_id;
                $('#practiceid').val(practice_id);
                var provider_id=result[key].static.provider_id;
                if (practice_id != '' || practice_id == 0) {
                  
                  util.updatePcpPhysicianList( 
                   practice_id,
                   $("#physician"),
                    provider_id
                  );
                }


             
              }
            
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