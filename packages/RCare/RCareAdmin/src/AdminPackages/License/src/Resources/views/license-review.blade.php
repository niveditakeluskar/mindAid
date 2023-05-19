@extends('Theme::layouts.master')
@section('page-css')
 <link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
 <link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">

@endsection

@section('main-content')
   <div class="breadcrumb">
                <h1>Organization Details</h1>                
            </div>
            <div class="separator-breadcrumb border-top"></div>
			 @include('Theme::layouts.flash-message')			
			<div class="alert" id="message" style="display: none"></div>
			<!--  -->
			<?php 
				
				foreach ($license as $user){
					$logo_img = $user->licenses->logo_img;
					$id = $user->licenses->id;
					$name = $user->licenses->name;
					$uid = $user->licenses->uid;
					$phone = $user->licenses->phone;
					$email = $user->licenses->email;
					$contact_person = $user->licenses->contact_person;
					$contact_person_email = $user->licenses->contact_person_email;
					$contact_person_phone = $user->licenses->contact_person_phone;
					$add1 = $user->licenses->add1;
					$add2 = $user->licenses->add2;
					$city = $user->licenses->city;
					$state = $user->licenses->state;
					$zip = $user->licenses->zip;
				}
			?>
  <div class="row">
				<!-- start Solid Bar -->
			<div class="col-lg-4 mb-3">
						<div class="card">
                                     	<div class="row">               
                        				<div class=" form-group col-md-6">
								 		<div class="card-body">
										<div class="card-title mb-3">	<!-- <a href="{{ route('edit',$user->licenses->id)}}" onclick="return confirm('Are you sure you want to Edit ?')" class="btn btn-raised btn-raised-danger m-1">Edit</a></div>
										 <div class="separator-breadcrumb border-top">--></div> 
										<div class="form-row ">
								
                                   <img class="badge-img" src="{{asset ($logo_img)}}" style="border-radius: 100px;">

								 
										</div>

									  	
										</div>
									    </div>

										<div class="col-md-6">
								 		<div class="card-body">
										<div class="card-title mb-3">
											<a href="{{ route('edit',$id)}}" onclick="return confirm('Are you sure you want to Edit ?')" class="btn  btn-primary m-1">Edit</a></div>
                                          <div class="separator-breadcrumb border-top"></div>
										<!-- 	<a href="editLicense/'.$row->org_id.'" title="Edit"><i class=" editform i-Pen-4"></i></a></div> -->
									
										<div class="form-row ">
										<div class="form-group">

									<strong class="mr-1">Organization Name</strong>
										<!-- //<label for="name" class="mr-1">Organization Name</label> -->

											<p class="text-muted m-0">{{ $name }}</p>
										</div>
									 	</div>
										<div class="form-row">
										<div class="form-group">
										<strong class="mr-1">UID</strong>	
										<!-- <label for="uid" >UID</label> -->
											<p class="text-muted m-0">{{ $uid }}</p>
										</div>
									 	</div>
										<div class="form-row">
										<div class="form-group">
										<!-- <strong class="mr-1">Category</strong>	 -->
										<!-- <label for="category">Category</label> -->
											<!-- <p class="text-muted m-0">{{ $user->licenses->category }}</p> -->

											
										</div>
										</div>
										
										<br><br>
										</div>
									    </div>
								
						                </div>
					                    </div>
				                        </div>
					<!-- end Solid Bar -->
		<div class="col-lg-4 mb-3">
				   <div class="card">

							<!--begin::form-->                                
							         	<div class="card-body">
										<div class="card-title mb-3">Contact Details</div>
										<div class="separator-breadcrumb border-top"></div>
										<div class="form-row ">
										<div class="form-group col-md-6">
										<strong class="mr-1">Phone</strong>	
										<!-- <label for="phone">Phone</label> -->
											 <p class="text-muted m-0">{{ $phone}}</p>
										</div>
										<div class="form-group col-md-6">
											<strong class="mr-1">Email</strong>	
										<!-- <label for="email">Email</label> -->
											<p class="text-muted m-0">{{ $email}}</p>
										</div>
									    </div>
									    	<br><br><br>
								       </div>

							<!-- end::form -->
					</div>
		</div>


<div class="col-lg-4 mb-3">
				   <div class="card">

							<!--begin::form-->                                
							         	<div class="card-body">
									    <div class="card-title mb-3">Contact Person</div>
									    <div class="separator-breadcrumb border-top"></div>
									    <div class="form-row mb-4">
										<div class="form-group col-md-4">
									   <!--  <label for="contact_person">Contact Person</label> -->
									   <strong class="mr-1">Name</strong>	
										     <p class="text-muted m-0">{{ $contact_person}}</p>
										</div>

										<div class="form-group col-md-4">
										<strong class="mr-1">Email</strong>		
										<!-- <label for="contact_person_email">Contact Person Email</label> -->
											 <p class="text-muted m-0">{{ $contact_person_email}}</p>
										</div>

										<div class="form-group col-md-4">
										<strong class="mr-1">Phone</strong>		
									    <!-- <label for="contact_person_phone">Contact Person Phone</label> -->
						                   <p class="text-muted m-0">{{ $contact_person_phone}}</p>
										
									   </div>
									   </div>
									
								       </div>

							<!-- end::form -->
					</div>
		</div>
		<div class="col-lg-4 mb-3">
					<div class="card">
									    <div class="card-body">
										<div class="card-title mb-3">Address</div>
										<div class="separator-breadcrumb border-top"></div>
										<div class="form-row ">
										<div class="form-group col-md-6">
										<strong class="mr-1">Address Line 1</strong>	
										<!-- <label for="add1">Address Line 1</label> -->
								             <p class="text-muted m-0">{{ $add1}}</p>		
								        </div>

										<div class="form-group col-md-6">
										<strong class="mr-1">Address Line 2</strong>	
										<!-- <label for="add1">Address Line 2</label> -->
											 <p class="text-muted m-0">{{ $add2}}</p>
												
										</div>
										</div>
											<br>
										<div class="separator-breadcrumb border-top"></div>
										<div class="form-row">
										<div class="form-group col-md-4">
										<strong class="mr-1">City</strong>		
									   <!--  <label for="city">City</label> -->
											<p class="text-muted m-0">{{ $city}}</p>
											
										</div>
										
										<div class="form-group col-md-4">
										<strong class="mr-1">State</strong>		
									    <!-- <label for="state">State</label> -->
											 <p class="text-muted m-0">{{ $state}}</p>
										 
										</div>

										<div class="form-group col-md-4">
										      <strong class="mr-1">Zip Code</strong>		
											  <!-- <label for="zip">Zip Code</label> -->
											 <p class="text-muted m-0">{{ $zip}}</p>

										</div>
										</div>
									    </div>
				   </div>
		</div>
		@foreach ($license as $user)
        <div class="col-lg-8 mb-3">
                  <div class="card">                                
                       <!--begin::form-->                                
                                        <div class="card-body">
                                        <div class="form-row ">
                                        <div class="card-title col-md-3">
                                        	License Details
                                        </div>
                                         <div class="card-title col-md-3">
                                        
                                        </div>
                                        <div class="form-group col-md-3">

                                        </div>
                                        <div class="form-group col-md-3">
                                        	<a href="{{ route('edit_license',$user->id)}}" onclick="return confirm('Are you sure you want to Edit ?')" class="btn  btn-primary m-1">Edit</a>
                                            
                                        	<a href="{{ route('add_license',$user->licenses->id)}}" onclick="return confirm('Are you sure you want to Add ?')" class="btn  btn-primary m-1">Add</a>


                                        	
                                        </div>
                                        </div>	

                       					<div class="card-title mb-3"></div>
                        				<div class="separator-breadcrumb border-top"></div>
                        				<div class="form-row ">

                          				<div class="form-group col-md-6">
                          				<strong class="mr-1">Model</strong>	
                           				<!-- label for="license_model">Model</label> -->
                          				  <p class="text-muted m-0">{{ $user->license_model }}</p>
                         
                         				</div> 
                         				<div class="form-group col-md-6">
                         				<strong class="mr-1">Module</strong>		
                           				<!-- <label for="license_model">Module</label> -->
                           					<p class="text-muted m-0">{{ $user->modules->modules }}</p>
                          
                        				 </div>
                      					 </div>
                       					 <div class="separator-breadcrumb border-top"></div>
                       					 <div class="card-title mb-3"></div>
                       					 <div class="form-row mb-4">
                                         <div class="form-group col-md-4">
                                         <strong class="mr-1">Numbers Of Months</strong>	
                         				<!--  <label for="subscription_in_months">Subscription in Month</label> -->
                        					  <p class="text-muted m-0">{{ $user->subscription_in_months }}</p>
                      
                      					 </div>

                       					 <div class="form-group col-md-4">
                       					  <strong class="mr-1">Start Date</strong>	
                       					 <!-- <label for="start_date">Start Date</label> -->
                        				 <div class='input-group date' id='datetimepicker1'>
                                             <p class="text-muted m-0"> {{date("d-m-Y ", strtotime($user->start_date))}}</td>
                        
                                         </div>
                      					 </div>

                    					 <div class="form-group col-md-4">
                    					 <strong class="mr-1">End Date</strong>	
                        				 <!-- <label for="end_date">End Date</label> -->
                                         <div class='input-group date' id='datetimepicker2'>
                                             <p class="text-muted m-0"> {{date("d-m-Y ", strtotime($user->end_date))}}</td>

                        				 </div>
                     					 </div>

                     					  <div class="form-group col-md-4">
                                         <strong class="mr-1">License Key</strong>	
                         				<!--  <label for="subscription_in_months">Subscription in Month</label> -->
                        					  <p class="text-muted m-0">{{ $user->license_key }}</p>
                      
                      					 </div>

                     					

                      <!-- <div class="form-group col-md-4">
                       <label for="Status">Status</label>
                       @selectactivestatus("status",["id" => "status", "class" => "form-control  "])
                      
                     </div> -->
                                          </div>

                                          </div>

                 <!-- end::form -->
                    </div>
         </div>    
		 @endforeach
					
					
	</div>	
			
			           
    </div>


@endsection

@section('page-js')
<script src="{{asset('assets/js/vendor/pickadate/picker.js')}}"></script>
<script src="{{asset('assets/js/vendor/pickadate/picker.date.js')}}"></script>
<script>
	$(document).ready(function() {
		util.getToDoListData(0, {{getPageModuleName()}});
	});
</script>
@endsection

@section('bottom-js')

@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

