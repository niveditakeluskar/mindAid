@extends('Theme::layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
@section('main-content')
<!-- <style type="text/css">
   #state{
   pointer-events: none;
   }
</style> -->
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
                <div class="alert alert-danger" id="danger-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>
                     Please ask Admin to configure the Billing Address.
                  </strong>
                  <span id="text"></span>
               </div>
               <div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>
                     Device Order added successfully! <br> Order No. is :
                     <div id="orderno"></div>
                  </strong>
                  <span id="text"></span>
               </div>
               <?php
                        $m_id           = getPageModuleName();
                        $c_id           = getPageSubModuleName();
                        $stage_id       = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Device Order');
                        $step_id        = 0; 
                        $enroll_service = (Request::segment(4)) ? Request::segment(4) : 0;
                        $default_img_path = asset('assets/images/faces/avatar.png');   
                        
                         echo $m_id;
                            echo  $c_id;
                            echo  $stage_id;
                        ?>
                   <input type="hidden" name="mid" id="mid" value="{{$m_id}}">  
                   <input type="hidden" name="cid" id="cid" value="{{$c_id}}">
                   <input type="hidden" name="stage_id" id="stage_id" value="{{$stage_id}}">
                   <input type="hidden" name="step_id" id="step_id" value="{{$step_id}}">  
                   <input type="hidden" name="form_name" value="device_order_form">
                   <input type="hidden" name="enrollservice" id="enrollservice" value="{{$enroll_service}}">  
                   <input type="hidden" name="partnerid" id="partnerid">
                   <input type="hidden" name="hd_timer_start" id="hd_timer_start" value="00:00:00"/>

               <div id="error_msg"></div>
               <input type="hidden" name="devicename" id="devicename">
               <?php $addresslocation =""; if(!empty($data1[0]->bill_address)){$addresslocation = $data1[0]->bill_address; } ?>   
               <div class="row">
                  <div class="col-md-6 form-group ">                    
                     <label for="practicename">Practice Name</label>                     
                     @selectrpmpractices("practices", ["id" => "practices", "class" => "select2 "])    

                     <input type="hidden" name="practiceid" id="practiceid">            
                </div>
                  <div class="col-md-6 form-group" id="patientdiv" style="display:none">
                     <label>Patient Name<span class="error">*</span></label>                     
                     @selectpatientenrolledinrpm("patient_id",["id"=>"patient_id","class"=>"select2"])
                      <div id="patienterror" style="color: red;font-size: 80%;display: none">Patient Name is required.</div>
                  </div>
               </div>
               <br>
               <div class="card">
                  <div class="card-body" id="hobby">
                    <!--   <button class="button" id="editpatient" style="border: 0px none;background: #f7f7f7;outline: none;float: right;"></button> -->
                      <div id="editpatient" style="float: right;"></div>
                     <div class="row">
                        <div class="col-md-3 form-group">                
                           <label class="form-group">First Name<span class="error">*</span></label>
                           @text("fname", ["id" => "fname",'readonly' => 'true'])  
                        </div>
                        <div class="col-md-3 form-group">                
                           <label class="form-group">Last Name<span class="error">*</span></label>
                           @text("lname", ["id" => "lname",'readonly' => 'true'])  
                        </div>
                        <div class="col-md-3 form-group">                 
                           <label class="form-group">DOB<span class="error">*</span></label>
                           @date("dob", ["id"=>"dob",'readonly' => 'true'])   
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label>Gender<span class="error">*</span></label>
                              <!-- @select("Gender", "gender", [0 => "Male",1 => "Female"]) style="pointer-events:none"-->
                              <select name="gender" id="gender" class="custom-select show-tick" disabled="true">
                                 <option value="">Select Gender</option>
                                 <option value="0" id="gender">Male</option>
                                 <option value="1" id="gender">Female</option>
                              </select>
                              <div class="invalid-feedback"></div>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label for="add_1">Address<span class="error">*</span></label>
                              @text("add_1", ["id" => "add_1",'readonly' => 'true'])
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label for="city">City<span class="error">*</span></label>
                              @text("city", ["id" => "city", "class" => "capitalize",'readonly' => 'true'])
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label for="state">State<span class="error">*</span></label>
                              @selectstate("state",["id" => "state",'disabled' => 'true'])
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label for="zip">Zip Code<span class="error">*</span></label>
                              @text("zipcode", ["id" => "zip",'readonly' => 'true'])
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label><span class="error">*</span>Email</label>
                              @email("email", ["id" => "email"])
                           </div>
                        </div>
                        <div class="col-md-3 form-group">
                           <label class="form-group">Phone<span class="error">*</span></label>
                           <!-- <input type="text" id="mob" name="mob" class="form-control"> -->
                           @phone("mob", ["id"=>"mob",'readonly' => 'true'])
                        </div>
                        
                        <div class="col-md-3">
                           <div class="form-group">
                              <label for="emr">EMR#</label>
                              @text("practice_emr", ["id" => "practice_emr",'readonly' => 'true'])
                           </div>
                        </div>
                        <div class="col-md-3">
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
                              @select("Primary Care Provider (PCP)", "provider_id", [], ["id" => "physician",'disabled' => 'true'])
                           </div>
                        </div>
                        <div class="col-md-3" >
                           <div class="form-group" style="display: none;">
                              <label>Office Location<span class="error">*</span></label>
                              <!-- @selectpractices("practice_id", ["id" => "practices"])   -->
                              @text("officelocation", ["id" => "officelocation",'readonly' => 'true'])
                           </div>
                        </div>
                        
                        
                     </div>
                  </div>
               </div>
               <br>
               <!-- Responsible party -->
               <div class="card">
                  <div class="card-header"> 
                     <div class="form-group">
                      <label>Emergency Contact</label>
                   </div>
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
                           <select class="form-control show-tick" id="phone_type" name="phone_type">
                                 <option value="">Phone Type</option>                                
                                 <option value="Home">Home</option>
                                 <option value="Mobile">Mobile</option>
                           </select>
                           <div class="invalid-feedback"></div>
                           <!-- @text("phone_type", ["id" => "phone_type"]) -->
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
                              <label><span class="error">*</span>Email</label>
                              @email("email", ["id" => "email"])
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label>Relationship<span class="error">*</span></label>
                              @text("Relationship", ["id" => "Relationship"])
                           </div>
                        </div>
                        <div class="col-md-6">
                       <div class="row">
                         <div class="form-group col-md-4">
                        <label for="city">City</label>
                          @text("respcity", ["id" => "respcity", "class" => "capitalize"])
                         </div>
                         <div class="form-group col-md-4">
                             <label for="state">State</label>
                            @selectstate("respstate",["id" => "respstate"])
                           </div>
                            <div class="form-group col-md-4">
                            <label for="zip">Zip Code</label>
                            @text("respzipcode", ["id" => "respzip"])
                           </div>
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
                       
                          <div class="col-md-12">
                           <div class="form-group">
                              <label>Device From<span class="error">*</span></label>
                              <div class="mr-3 d-inline-flex align-self-center">
                                 <label for="Inventory" class="radio radio-primary mr-3">
                                 <input type="radio" name="systemid" id="Inventory" value="Inventory" formControlName="radio">
                                 <span>Inventory</span>
                                 <span class="checkmark"></span>
                                 </label>
                                 <label for="ECG" class="radio radio-primary mr-3">
                                 <input type="radio" name="systemid" id="ECG"  value="ECG" formControlName="radio" checked>
                                 <span>ECG</span>
                                 <span class="checkmark"></span>
                                 </label>                                 
                              </div>
                           </div>
                        </div>
                         <div class="col-md-6" id="devicecode" style="display: none;">                            
                           <div class="form-group">
                              <label for="device_code">Device Code<span class="error">*</span></label>
                              @text("device_code", ["id" => "device_code"])                         
                        </div>                       
                         </div>
                          <div class="col-md-6" id="extradiv" style="display: none;"></div>
                        <div class="col-md-6">  
                           <div class="form-group">                              
                              <label for="name" class="control-label">Device<span class="error">*</span></label>                
                              <div class="wrapMulDrop">
                                 <button type="button" id="multiDrop" name="multiDrop" class="multiDrop form-control col-md-12">Device Type<i style="float:right;" class="icon ion-android-arrow-dropdown"></i></button>
                                 <ul>
                                  <?php 
                                      if(isset($devices)){                      
                                       for($i=0;$i<count($devices);$i++)
                                       {
                                          $device_name_api=$devices[$i]->device_name_api;
                                          $devicename=$devices[$i]->device['device_name'];
                                          ?>
                                    <li>
                                       <label class="forms-element checkbox checkbox-outline-primary"> 
                                       <input class="" name ="device_type[{{$device_name_api.'-'.$devices[$i]->device['id'].'-'.$devices[$i]->id.'-'.$devices[$i]->partner_id}}]"  id ="{{$device_name_api}}" value="{{$devices[$i]->device['id']}}" type="checkbox" ><span class="">{{$devicename}}</span><span class="checkmark"></span>             
                                       </label> <!-- onclick="GetSelectedItem(this);" -->
                                    </li>
                                          <?php
                                       }
                                    }                                   
                                    ?>                                  
                                 </ul>
                              </div>
                             <div id="deviceerror" style="color: red;font-size: 80%;display: none">Please select at least one device.</div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="ordertype">Console Type<span class="error">*</span></label>
                              <select name="ordertype" class="custom-select show-tick">
                                 <option value="">Select Console Type</option>
                                 <option value="renovaDemo" >PERS</option>
                                 <option value="renova2Demo" >Non PERS</option>
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
                        <div class="col-md-12" id="seldev" style="display: none"></div>
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
                        <div class="col-md-4" id="BP_Option" style="display: none">
                           <div class="form-group">
                              <label>Cuff Sizes<span class="error">*</span></label>
                              <div class="mr-3 d-inline-flex align-self-center">
                                 <label for="small" class="radio radio-primary mr-3">
                                 <input type="radio" name="size" id="small" value="Small" formControlName="radio" checked>
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
                                 <label for="XLarge" class="radio radio-primary mr-3">
                                 <input type="radio" name="size" id="XLarge"  value="XLarge" formControlName="radio">
                                 <span>Extra Large</span>
                                 <span class="checkmark"></span>
                                 </label>
                               <!--   <label for="ExtraLarge" class="radio radio-primary mr-3">
                                 <input type="radio" name="size" id="Extra_Large"  value="Extra-Large" formControlName="radio">
                                 <span>Extra Large</span>
                                 <span class="checkmark"></span>
                                 </label> -->
                              </div>
                           </div>
                        </div>
                        <br>
                        <div class="col-md-3" id="Weight_Option" style="display: none; ">
                           <div class="form-group">
                              <label>Weight Capacity<span class="error">*</span></label><br>  
                              <div class="mr-3 d-inline-flex align-self-center">
                                 <label for="weight150" class="radio radio-primary mr-3">
                                 <input type="radio" name="weight" id="weight150" value="150kg" 
                                     checked>
                                 <span>150kg -330 lbs</span>
                                 <span class="checkmark"></span>
                                 </label>
                                 <label for="weight250" class="radio radio-primary mr-3">
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
               <div class="card" style="display: none">
                  <div class="card-body">                     
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label>Shipping Speed<span class="error">*</span></label>
                              <select class="form-control show-tick" id="shipping_option" name="shipping_option">
                                 <!-- <option value="">Shipping Method</option>                                 -->
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

                        <!-- Shipping -->
            <div class="card" >      
                 <div class="card-header"> 
                 Shipping
                 </div>
               <div class="card-body" >  
               <div class="row">
                      <div class="col-md-12">
                           <div class="form-group">
                              <label>Shipping Address <span class="error">*</span></label>
                              <div class="mr-3 d-inline-flex align-self-center">
                                 <label for="shipping_patient_add" class="radio radio-primary mr-3">
                                 <input type="radio" name="shippingdata" id="shipping_patient_add" value="shipping_patient_add" formControlName="radio" checked>
                                 <span>Patient Address</span>
                                 <span class="checkmark"></span>
                                 </label>
                                 <label for="shipping_renova_headq" class="radio radio-primary mr-3">
                                 <input type="radio" name="shippingdata" id="shipping_renova_headq" value="shipping_renova_headq" formControlName="radio" >
                                 <span>Renova Headquarters</span>
                                 <span class="checkmark"></span>
                                 </label>  
                                  <label for="shipping_custom" class="radio radio-primary mr-3">
                                 <input type="radio" name="shippingdata" id="shipping_custom" value="shipping_custom" formControlName="radio" >
                                 <span>Custom</span>
                                 <span class="checkmark"></span>
                                 </label>                                
                              </div>
                           </div>
                        </div>
               </div>          
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
                 <label>Email</label>
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
                      @selectstate("shipping_state",["id" => "shipping_state"])
                     </div>
                      <div class="form-group col-md-4">
                      <label for="zip">Zip Code<span class="error">*</span></label>
                      @text("shipping_zipcode", ["id" => "shipping_zip"])
                     </div>
                </div>
              </div>
                 <div class="col-md-6">              
                   <div class="form-group">
                              <label>Shipping Speed<span class="error">*</span></label>
                              <select class="form-control show-tick" id="shipping_option" name="shipping_option">
                                 <!-- <option value="">Shipping Method</option>                                 -->
                                 <option value="Ground Free">Free: 5 -7 Business Days</option>
                                 <option value="3 day">$25: 3 day express</option>
                                 <option value="2 day">$50: 2 day express</option>
                                 <option value="1 day">$75: overnight</option>
                              </select>
                           </div>
                     </div>               
                 </div>
               </div>
             <!--  </div>
             </div>
            </div> -->
          </div>
               <br>
               <!-- Med reminder -->
               <div class="card" >                  
                  <div class="card-header"> 
                     <div class="form-group">
                        <label>Reminders and Messages</label>
                     </div>
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
<!-- <div id="app"></div> -->
@endsection
@section('page-js')
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script src="{{asset('assets/js/tooltip.script.js')}}"></script>
<script src="{{asset(mix('assets/js/laravel/deviceOrder.js'))}}"></script>
<script>
   $(document).ready(function() {
       deviceOrder.init();
     
   });

 
</script>
@endsection