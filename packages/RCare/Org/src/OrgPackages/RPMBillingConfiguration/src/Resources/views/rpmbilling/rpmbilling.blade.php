@extends('Theme::layouts.master')
@section('main-content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
	<div id="threshold-success"></div>
	<div class="card-body">
		<div class="row mb-4">
			<div class="col-md-12  mb-4">
				<div class="card mb-4">
					<div class="card-header mb-3">RPM Configuration</div>
            <form action="{{ route('create_rpm_billing') }}" method="POST" name="rpm_billing_form" id="rpm_billing_form">
            {{ csrf_field() }}  
            <!-- Billing Address -->
    					<div class="card-body" id="hobby">                           
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-2 form-group"> 
                      <label for="name">Vitals Review Time<span style="color:red">*</span></label>  
                      </div>
                      <div class="col-md-4 form-group">
                          @timetext('vital_review_time', ['id'=>'vital_review_time']) 
                      </div>
                      <div class="col-md-2 form-group"> 
                      <label for="name">Required Billing days<span style="color:red">*</span></label>  
                      </div>
                      <div class="col-md-4 form-group">
                        @text('required_billing_days',['id'=>'required_billing_days'])
                      </div>
                    </div>
                  </div>
              </div> 
              <!-- Billing Address -->    
            <!--   <div class="card" style="display: none">                 
                <div class="card-body">            
                 <div class="row" id="billing_div"> -->
                    <div class="card">
                    <div class="card-header">Device Billing Address</div>
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
                          @phone("billing_phone", ["id"=>"billing_phone"])                               
                        </div>
                      </div>
                      <div class="col-md-6">
                         <div class="form-group">
                          <label for="family_add">Address<span class="error">*</span></label>
                            @text("billing_address", ["id" => "billing_address"])
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Email</label>
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
                          @text("billing_zip", ["id" => "zip"])
                        </div>
                      </div>
                    </div>                
                   </div>               
                  </div>
                  </div>
              <!--   </div>
               </div>
              </div>           -->
              <!-- Device Address -->
                           
            <!--     <div class="card-body">            
                  <div class="row" id="device_div">-->
                    <div class="card" style="display: none"> 
                      <div class="card-header">Device Billing Address</div>
                      <div class="card-body" >            
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>First Name<span class="error">*</span></label>
                              @text("device_fname", ["id" => "device_fname"])
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Last Name<span class="error">*</span></label>
                              @text("device_lname", ["id" => "device_lname"])
                            </div>
                          </div>
                          <div class="col-md-6">               
                            <div class="form-group">
                              <label>Phone<span class="error">*</span></label>
                              <!-- <input type="text" id="device_phone" name="device_phone" class="form-control">  -->
                              @phone("device_phone", ["id"=>"device_phone"])                    
                            </div>                  

                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="family_address">Address<span class="error">*</span></label>
                              @text("device_address", ["id" => "device_address"])
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Email</label>
                              @email("device_email", ["id" => "device_email"])
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="row">
                              <div class="form-group col-md-4">
                                <label for="city">City<span class="error">*</span></label>
                                @text("device_city", ["id" => "device_city", "class" => "capitalize"])
                              </div>
                              <div class="form-group col-md-4">
                                <label for="state">State<span class="error">*</span></label>
                                @selectstate("device_state", request()->input("state"))
                              </div>
                              <div class="form-group col-md-4">
                                <label for="zip">Zip Code<span class="error">*</span></label>
                                @text("device_zip", ["id" => "device_zip"])
                              </div>
                            </div>
                          </div>              
                        </div>
                      </div>
                 </div>
                  <!--    </div>
                </div> -->
                             
              <!-- Headquaters -->
          <!--     <div class="card" >                 
                <div class="card-body">            
                  <div class="row" id="headquaters_div"> -->
                    <div class="card">                       
                      <div class="card-header">Renova Headquaters Shipping Address</div>
                      <div class="card-body" > 
                      <div class="row">
                        <div class="col-md-6">
                         <div class="form-group">
                        <!-- <label> <span class="error">*</span></label> -->
                           @checkbox("Shipping Address same as the Billing Address.", "headqadd", "headqadd")
                           </div>
                      </div>
                     </div>           
                        <div class="row" id="headquarteradd">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>First Name<span class="error">*</span></label>
                              @text("headquaters_fname", ["id" => "headquaters_fname"])
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Last Name<span class="error">*</span></label>
                              @text("headquaters_lname", ["id" => "headquaters_lname"])
                            </div>
                          </div>
                          <div class="col-md-6">               
                            <div class="form-group">
                              <label>Phone<span class="error">*</span></label>
                              <!-- <input type="text" id="headquaters_phone" name="headquaters_phone" class="form-control">  -->
                              @phone("headquaters_phone", ["id"=>"headquaters_phone"])                    
                            </div>                  

                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="family_address">Address<span class="error">*</span></label>
                              @text("headquaters_address", ["id" => "headquaters_address"])
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Email</label>
                              @email("headquaters_email", ["id" => "headquaters_email"])
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="row">
                              <div class="form-group col-md-4">
                                <label for="city">City<span class="error">*</span></label>
                                @text("headquaters_city", ["id" => "headquaters_city", "class" => "capitalize"])
                              </div>
                              <div class="form-group col-md-4">
                                <label for="state">State<span class="error">*</span></label>
                                @selectstate("headquaters_state", request()->input("state"))
                              </div>
                              <div class="form-group col-md-4">
                                <label for="zip">Zip Code<span class="error">*</span></label>
                                @text("headquaters_zip", ["id" => "headquaters_zip"])
                              </div>
                            </div>
                          </div>              
                        </div>
                      </div>
                     </div>
                <!--  </div>
                </div>
              </div> -->
              <!-- footer button -->
              <div class="card-footer">
                  <div class="mc-footer">
                      <div class="row">
                          <div class="col-lg-12 text-right">
                              <button type="submit"  class="btn  btn-primary m-1 save_rpm_billing" >Save</button>
                              <!-- <button type="button" class="btn btn-info float-left additionalProvider" id="additionalProvider">Add Provider</button> -->
                              <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                          </div>
                      </div>
                  </div>
              </div>	
            </form>
					</div>	
				</div>	

			</div>	
		</div>	
	</div>	

<div id="app"></div>


@endsection
@section('page-js')
<script type="text/javascript">
$(document).ready(function() {
	rpmbillingconfiguration.init();

 });
</script>
@endsection