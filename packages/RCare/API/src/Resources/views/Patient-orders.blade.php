@extends('Theme::layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
@section('main-content')
<div class="breadcrusmb">
   <div class="row">
      <div class="col-md-11">
         <h4 class="card-title mb-3">Device Order</h4>
      </div>
   </div>
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row mb-4">
   <div class="col-md-12 mb-4">
      <div id="success"></div>
      <div class="card">
         <div class="card-body" id="hobby">
            <div id="error_msg"></div>
            <form action="http://rcareproto2.d-insights.global/rpm/patientorder" method="post" name ="patient_order_form"  id="patient_order_form">
            @csrf
            <div class="alert alert-success" id="success-alert" style="display: none;">
               <button type="button" class="close" data-dismiss="alert">x</button>
               <strong> Patient Order added successfully! </strong><span id="text"></span>
            </div>
            <input type="hidden" name="devicename" id="devicename">
              <div class="row">
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
                     <label>DOB<span class="error">*</span></label>
                    @date("dob")   
                 </div>
                  <div class="col-md-6">
                      <label>Phone<span class="error">*</span></label>
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
                  @select("Provider", "provider_id", [], ["id" => "physician"])
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
                 Responsible party 
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
                 <label>System Type<span class="error">*</span></label>                 
                    <select name="system_type" class="form-control show-tick">
                      <option value="ECG_PROHEALTH_PACKAGE" selected>ECG_PROHEALTH_PACKAGE</option>                   
                    </select>
                  </div>
                </div>
                 <div class="col-md-6">
                   <div class="form-group">
                  <label>Neck Pendant<span class="error">*</span></label>
                     @checkbox("None", "neck_pendant", "neck_pendant")
                     </div>
                </div>
                 <div class="col-md-6">
                   <div class="form-group">
                  <label>Device Type<span class="error">*</span></label>                 
                    <select name="device_type" class="form-control show-tick">
                      <option value="" >Select Device Type</option>    
                      <option value="ECG_PROHEALTH_PULSE" >ECG_PROHEALTH_PULSE</option>                   
                      <option value="ECG_PROHEALTH_GLUCOSE" >ECG_PROHEALTH_GLUCOSE</option>                   
                      <option value="ECG_PROHEALTH_GLUCOSE_N" >ECG_PROHEALTH_GLUCOSE_N</option>                   
                      <option value="ECG_PROHEALTH_SP" >ECG_PROHEALTH_SP</option>                   
                      <option value="ECG_PROHEALTH_TEMP" >ECG_PROHEALTH_TEMP</option>                   
                      <option value="ECG_PROHEALTH_WEIGHT" >ECG_PROHEALTH_WEIGHT</option> 
                      <option value="ECG_PROHEALTH_BP" >ECG_PROHEALTH_BP</option>                       
                    </select>
                   </div>
                </div>
                 <div class="col-md-6">
                   <div class="form-group">
                  <label for="family_add">Action Plan<span class="error">*</span></label>
                    @text("action_plan", ["id" => "action_plan"])
                  </div>
                </div>
               <!--   <div class="col-md-6">
                   <div class="form-group">
                  <label>Group<span class="error">*</span></label>
                    <select class="form-control show-tick">
                      <option>select Group</option>
                    </select>                      
                     </div>
                </div> -->
                 <div class="col-md-6">
                   <div class="form-group">
                 <label>Device Size<span class="error">*</span></label>
                 <div class="mr-3 d-inline-flex align-self-center">
                        <label for="small" class="radio radio-primary mr-3">
                        <input type="radio" name="small" id="small" value="Y" formControlName="radio"
                           >
                        <span>Small</span>
                        <span class="checkmark"></span>
                        </label>
                        <label for="Medium" class="radio radio-primary mr-3">
                        <input type="radio" name="Medium" id="Medium"  value="N" formControlName="radio"
                           >
                        <span>Medium</span>
                        <span class="checkmark"></span>
                        </label>
                         <label for="Large" class="radio radio-primary mr-3">
                        <input type="radio" name="Large" id="Large"  value="N" formControlName="radio"
                           >
                        <span>Large</span>
                        <span class="checkmark"></span>
                        </label>
                     </div><br>
                     <label>Device weight<span class="error">*</span></label>
                 <div class="mr-3 d-inline-flex align-self-center">
                        <label for="kg" class="radio radio-primary mr-3">
                        <input type="radio" name="kg" id="kg" value="Y" formControlName="radio"
                           >
                        <span>150kg</span>
                        <span class="checkmark"></span>
                        </label>
                        <label for="kg" class="radio radio-primary mr-3">
                        <input type="radio" name="kg" id="kg"  value="N" formControlName="radio"
                           >
                        <span>250kg</span>
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
            <div class="card">                 
               <div class="card-body">            
               <div class="row">
               	  <div class="col-md-6">
                   <div class="form-group">
                  <!-- <label> <span class="error">*</span></label> -->
                     @checkbox("Is the Billing same as the patient details", "billing", "billing")
                     </div>
                </div>
               </div>
                 <div class="row">
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
                     @phone("billing_mob", ["id"=>"billing_mob"])                               
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
                 <div class="row">
                   <div class="form-group col-md-4">
                  <label for="city">City<span class="error">*</span></label>
                    @text("city", ["id" => "city", "class" => "capitalize"])
                   </div>
                   <div class="form-group col-md-4">
                       <label for="state">State<span class="error">*</span></label>
                      @selectstate("state", request()->input("state"))
                     </div>
                      <div class="form-group col-md-4">
                      <label for="zip">Zip Code<span class="error">*</span></label>
                      @text("zipcode", ["id" => "zip"])
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
            <div class="card">                 
               <div class="card-body">            
               <div class="row">
               	  <div class="col-md-6">
                   <div class="form-group">
                  <!-- <label> <span class="error">*</span></label> -->
                     @checkbox("Is the Shipping same as the patient details", "shipping", "shipping")
                     </div>
                </div>
               </div>
                 <div class="row">
                 	       <div class="card">
                 <div class="card-header"> 
                 Shipping
                 </div>
               <div class="card-body">            
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
                    <select class="form-control show-tick" id="shipping_option">
                    	<option value="">Select Option</option>
                        <option value="Ground Free">Ground Free</option>
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
              
             
            <div class="row">
               <div class="col-12 text-right form-group mb-4">
                  <button type="submit" class="btn btn-primary">Submit</button>
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
   
     
    
</script>
@endsection