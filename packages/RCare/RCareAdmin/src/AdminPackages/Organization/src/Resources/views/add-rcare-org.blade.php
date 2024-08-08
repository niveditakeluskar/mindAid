@extends('Theme::layouts.master')
@section('page-css')
 <link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
 <link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">
 <style>
	 .error{
		 color:red !important;
	 }
 </style>
@endsection

@section('main-content')
  
            <div class="separator-breadcrumb border-top"></div>
						
			<div class="alert" id="message" style="display: none"></div>
		
		   <form action="{{ route('create_org') }}" name="create_org" method="POST" enctype="multipart/form-data" id="upload_form">
			{{ csrf_field() }}
            <div class="row">
				<!-- start Solid Bar -->
					<div class="col-lg-4 mb-3">
						<div class="card">
								 <div class="card-body">
									<div class="card-title mb-3"><b>Organization Details</b></div>
									<div class="form-row ">
										<div class="form-group col-md-12">
											<label for="name">Organization Name</label>
											@text("name", ["id" => "name", "class" => "form-control capital-first  ", "placeholder" => "Enter name"])
										</div>
									 </div>
									<div class="form-row">
										<div class="form-group col-md-12">
											<label for="uid">UID</label>
											@text("uid", ["id" => "uid", "class" => "form-control capital-first ", "placeholder" => "Enter your uid"])
										</div>
									 </div>
									<div class="form-row">
										<div class="form-group col-md-12">
											<label for="category">Category</label>

												@selectorgcategory("category", ["id" => "category", "class" => "form-control  "])
											<!-- @selectorgcategory("category", ["id" => "category", "class" => "form-control  "]) -->
										</div>
									</div>
									<!-- <div class="form-row">
										<div class="form-group col-md-12">
				                            <div class="input-group mb-3">
				                                <div class="custom-file">
				                                    <input type="file" class="custom-file-input" id="select_file" name="select_file" aria-describedby="inputGroupFileAddon01">
				                                    <label class="custom-file-label" for="inputGroupFile01">Choose Logo Image</label>
				                                </div>
				                            </div>
				                            <span id="logo_img_url_name"></span>
										</div>
									</div> -->
									<!-- <input type="text" name="logo_img" id="logo_img" value=""> -->
								<div class="form-row">
									<div class="form-group col-md-12">
										<label for="select_file">Logo</label>
										 <br>
                      <div  class="custom-file"><input  class="custom-file-input" id="select_file"  name="select_file" type="file"><label  aria-describedby="inputGroupFileAddon02" class="custom-file-label" for="inputGroupFile02">Choose file</label></div>
<!-- 										<input type="file" name="select_file" id="select_file" /> -->
									</div>
									<!-- <div class="form-group col-md-6">
										<input type="submit" name="upload" id="upload" class="btn btn-primary" value="upload">
									</div> -->
								</div> 
								 
								</div>
							<!-- end::form -->
						</div>
					</div>
					<!-- end Solid Bar -->
					<div class="col-lg-8 mb-3">
						<div class="card">                                
							<!--begin::form-->                                
								<div class="card-body">
								<div class="card-title mb-3">Contact Details</div>
									<div class="form-row ">
										<div class="form-group col-md-6">
											 <label for="phone">Phone</label>
											 @number("phone", ["id" => "phone", "class" => "form-control  ", "placeholder" => "Enter org phone number"])
										</div>
										<div class="form-group col-md-6">
										   <label for="email">Email</label>
											 @email("email", ["id" => "email", "class" => "form-control  ", "placeholder" => "Enter org email id"])
										</div>
									</div>
									<div class="card-title mb-3">Contact Person</div>
									<div class="form-row mb-4">
										<div class="form-group col-md-4">
											 <label for="contact_person">Contact Person</label>
											 @text("contact_person", ["id" => "contact_person", "class" => "form-control capital-first ", "placeholder" => "Enter contact person name"])
										</div>

										<div class="form-group col-md-4">
											<label for="contact_person_email">Contact Person Email</label>
											@email("contact_person_email", ["id" => "contact_person_email", "class" => "form-control  ","placeholder" => "Enter contact person email id "]) 
										</div>

										<div class="form-group col-md-4">
											  <label for="contact_person_phone">Contact Person Phone</label>
											 @text("contact_person_phone", ["id" => "contact_person_phone", "class" => "form-control  capital-first ","placeholder" => "Enter contact person phone number "])
										</div>
									</div>
									
								</div>
                              <p></p>
                              <p></p>
                              <br>

                              
							<!-- end::form -->
						</div>
					</div>
					<div class="col-lg-12 mb-3">
						<div class="card">
							<div class="card-body">
								<div class="card-title mb-3">Address</div>
								<div class="form-row ">
									<div class="form-group col-md-6">
										  <label for="add1">Address Line 1</label>
										<!--  @textarea("add1", ["id" => "add1", "class" => "form-control  ", "placeholder" => "Enter your address"]) -->
										 <textarea class="form-control capital-first" aria-label="With textarea" name="add1" placeholder="Enter address line 1 "></textarea>
									</div>

									 <div class="form-group col-md-6">
									 <label for="add2">Address Line 2</label>
										 <!-- @text("add2", ["id" => "add2", "class" => "form-control  ", "placeholder" => "Enter your address"]) -->
										 <textarea class="form-control capital-first" aria-label="With textarea" name="add2" placeholder="Enter address line 2 "></textarea>
									</div>
								</div>
								<div class="form-row">
								<div class="form-group col-md-4">
									  <label for="city">City</label>
									 @text("city", ["id" => "city", "class" => "form-control capital-first", "placeholder" => "Enter city"])
								</div>

								 <div class="form-group col-md-4">
									<label for="state">State</label>
									 @text("state", ["id" => "state", "class" => "form-control  ", "placeholder" => "Enter state"])
								</div>

								<div class="form-group col-md-4">
									  <label for="zip">Zip Code</label>
									  @text("zip", ["id" => "zip", "class" => "form-control  ", "placeholder" => "Enter zip code"])
								</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-lg-12 mb-3">
							<div class="card">
                                <div class="card-header ">
                                    <div class="col-lg-12 text-right">
										<button type="submit" class="btn  btn-primary m-1">Submit</button>
										<button type="button" class="btn btn-outline-secondary m-1">Cancel</button>
									</div>
                                </div>								
							</div>
						</div>
			</div>	
			</form> 
			          
            </div>
@endsection

@section('page-js')
<script src="{{asset('assets/js/vendor/pickadate/picker.js')}}"></script>
<script src="{{asset('assets/js/vendor/pickadate/picker.date.js')}}"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

@endsection

@section('bottom-js')
<script src="{{asset('assets/js/form.basic.script.js')}}"></script>
<script>
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">

$(function () {
	$("form[name='create_org']").validate({
                rules: {
                    name: "required",
                    uid: "required",
                    phone: {minlength:10,
  							maxlength:10,
  							required: true
					},
					category: "required",
					select_file: {
						required: true
					},
					email: {
      					required: true,
      					email: true
    				},
					contact_person: "required",
					contact_person_email: "email",
					contact_person_phone:{
						minlength:10,
  						maxlength:10,
						number: true
					},
					add1: "required",
					city: "required",
					state: "required",
					zip: {
						required: true,
						number:true,
						minlength:6,
  						maxlength:6
					}
					
                },
                messages: {
                    name: "Please enter Organization  Name",
                    uid: "Please enter Uid",
                    phone: {
						required: "Please enter phone number!",
						minlength: "Please Enter 10 digit!"
					},	
					category: "Please select category!",
					select_file: {
						require: "Please upload logo!"
					},
					email: {
     				required: "We need your email address to contact you",
      				email: "Your email address must be in the format of name@domain.com"
   					 },
					contact_person: "Please enter contact person name!",
					contact_person_email: "Your email address must be in the format of name@domain.com"	,
					contact_person_phone: {
						minlength: "Please Enter 10 digit!",
						number: "Enter number only"
					},
					add1: "This field is required.",
					city: "This field is required.",
					state: "This field is required."

                },
                submitHandler: function(form) {
                     form.submit();
                 }
 
            });
});


   

</script>
