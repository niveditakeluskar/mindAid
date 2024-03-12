@extends('Theme::layouts_2.to-do-master')
@section('main-content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<div id="app">
	<div id="success">
	</div>	

	<div class="card-body">
		<div class="row mb-4">
			<div class="col-md-12  mb-4">
				<div class="card mb-4">
					<div class="card-header mb-3">PATIENT REGISTRATION</div>
						<div class="card-body" id="hobby">
							<form action="{{ route("ajax.patient.registration")}}" method="post"  id="patient_registration_form">
							<!-- <form action="" method="post" autocomplete="off" name="patient_registration_form" id="patient_registration_form"> -->
								@csrf
								<div class="row">
									<div class="col-6">
										<div class="form-group">
											<label>Select Practice</label>
											@selectpractices("practice_id", ["id" => "practices"])
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<label>Select Physician</label>
											@select("Physician", "physician_id", [], ["id" => "physician"])
										</div>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-4">
										<div class="form-group">
											<label for="lname">Last Name</label>
											@text("lname", ["id" => "lname", "class" => "capitalize"])
										</div>
									</div>
									<div class="col-4">
										<div class="form-group">
											<label for="fname">First Name</label>
											@text("fname", ["id" => "fname", "class" => "capitalize"])
										</div>
									</div>
									<div class="col-4">
										<div class="form-group">
											<label for="mname">Middle Name</label>
											@text("mname", ["id" => "mname", "class" => "capitalize"])
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-4 col-md-2">
										<div class="form-group">
											<label>Gender</label>
											@select("Gender", "gender", [
											0 => "Male",
											1 => "Female"
											])
										</div>
									</div>
									<div class="col-md-4 form-group">
										<label>Marital Status</label>
										@select("Marital Status", "marital_status", [
										"single" => "Single",
										"partnered" => "Partnered",
										"married" => "Married",
										"separated" => "Separated",
										"divorced" => "Divorced",
										"widowed" => "Widowed"
										])
									</div>
									<div class="col-8 col-md-4">
										<div class="form-group">
											<label>Date of Birth</label>
											@date("dob")
										</div>
									</div>
									<div class="col-4 col-md-2">
										<label>Age</label>
										<input type="number" class="form-control" id="age" name="age" readonly>
									</div>
								</div>
								<div class="row">
									<div class="col-12">
										<div class="form-group">
											<label for="addr1">Address Line 1</label>
											@text("addr1", ["id" => "addr1"])
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-12">
										<div class="form-group">
											<label for="addr2">Address Line 2</label>
											@text("addr2", ["id" => "addr2"])
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="city">City</label>
											@text("city", ["id" => "city", "class" => "capitalize"])
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="state">State</label>
											@selectstate("state", request()->input("state"))
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="zip">Zip Code</label>
											@text("zip", ["id" => "zip"])
										</div>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-4">
										<label>Ethnicity</label>
										@select("Ethnicity", "ethnicity1", config("form.ethnicities"))
									</div>
									<div class="form-group col-md-4">
										<label>Other Ethnicity (Optional)</label>
										@select("Ethnicity", "ethnicity2", config("form.ethnicities"))
									</div>
									<div class="col-md-4 form-group">
										<label>Education</label>
										@select("Ecucation", "education", config("form.education"))
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-3">
										<label>Occupation Status</label>
										@select("Occupation Status", "occupation_status", config("form.occupation_status"))
									</div>
									<div class="form-group col-md-5">
										<label for="occupation_description">Occupation Description</label>
										@text("occupation_description")
									</div>
									<div class="col-md-4 form-group">
										<label>Have you ever served in the Military?</label>
										@select("Military served status", "military", [
										0 => "Yes",
										1 => "No",
										2 => "Unknown"
										], [ "id" => "military" ])
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 form-group">
										<label for="email">Email</label>
										@checkbox("None", "no_email", "no_email")
										<!-- <div class="col-md-12">
											<input class="form-check-input" type="checkbox"  id="no_email" name="no_email" >
											<label class="form-check-label" for="no-email">No Email</label>
											</div> -->
									</div>
								</div>
								<div data-toggle="buttons">
									<div class="row">
										<div class="col-md-12">
											<div class="input-group form-group">
												<div class="input-group-prepend btn-group btn-group-toggle" >
													<label class="btn btn-outline-primary" for="email-preferred">
													Preferred
													@input("radio", "preferred_contact", ["id" => "email-preferred", "value" => "2", "data-feedback" => "contact-preferred-feedback"])
													</label>
												</div>
												@email("email", ["id" => "email"])
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label for="phone_primary">Primary Phone Number</label>
											<div class="input-group form-group">
												<div class="input-group-prepend btn-group btn-group-toggle" >
													<label class="btn btn-outline-primary" for="phone-primary-preferred">
													Preferred
													@input("radio", "preferred_contact", ["id" => "phone-primary-preferred", "value" => "0", "data-feedback" => "contact-preferred-feedback"])
													</label>
												</div>
												@phone("phone_primary")
											</div>
										</div>
										<div class="col-md-6">
											<label for="phone_secondary">Secondary Phone Number</label>
											<div class="input-group form-group">
												<div class="input-group-prepend btn-group btn-group-toggle" >
													<label class="btn btn-outline-primary" for="phone-secondary-preferred">
													Preferred
													@input("radio", "preferred_contact", ["id" => "phone-secondary-preferred", "value" => "1", "data-feedback" => "contact-preferred-feedback"])
													</label>
												</div>
												@phone("phone_secondary")
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-12">
											<label>Other Contact</label>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-4 col-md-6 form-group">
											<label>Name</label>
											@text("other_contact_name")
										</div>
										<div class="col-lg-2 col-md-6 form-group">
											<label>Relationship</label>
											@text("other_contact_relationship")
										</div>
										<div class="col-lg-2 col-md-4 form-group">
											<label>Phone Number</label>
											@phone("other_contact_phone")
										</div>
										<div class="col-lg-4 col-md-8 form-group">
											<label>Email</label>
											@email("other_contact_email")
										</div>
									</div>
									<div class="row">
										<div class="col-12 text-right">
											<span class="invalid-feedback visible" style="display: inline;" data-feedback-area="contact-preferred-feedback"></span>
											<div class="btn-group btn-group-toggle">
												<label class="btn btn-outline-primary" for="preferred_contact">
												Preferred
												@input("radio", "preferred_contact", ["id" => "other-preferred", "value" => "3", "data-feedback" => "contact-preferred-feedback"])
												</label>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-12">
											<div class="invalid-feedback visible" data-feedback-area="other-preferred-feedback"></div>
										</div>
									</div>
								</div>
								<hr>
								@header("Best Time to Contact")
								<contact-time></contact-time>
								<!-- <yes-no name="discharge_instruct">Discharge Instruction</yes-no> -->
								<hr>
								<div class="row">
									<div class="col-6 div form-group">
										<label for="insurance_primary">Primary Insurance</label>
										@text("insurance_type[1]")
									</div>
									<div class="col-6 div form-group">
										<label for="insurance_primary_idnum">ID#</label>
										@text("insurance_id[1]")
										<input type="hidden" name="insurance_primary_idnum_check" id="insurance_primary_idnum_check" value="0">
									</div>
								</div>
								<div class="row">
									<div class="col-6 div form-group">
										<label for="insurance_secondary">Secondary Insurance</label>
										@text("insurance_type[2]")
									</div>
									<div class="col-6 div form-group">
										<label for="insurance_secondary_idnum">ID#</label>
										@text("insurance_id[2]")
										<input type="hidden" name="insurance_secondary_idnum_check" id="insurance_secondary_idnum_check" value="0">
									</div>
								</div>
								<div class="row">
									<div class="col-12">
										<div class="form-group">
											<label for="emr">EMR#</label>
											@text("emr", ["id" => "emr"])
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-12 form-group">
										@checkbox("Has power of attorney", "poa", "poa")
									</div>
								</div>
								<div class="row">
									<div class="col-md-3 form-group">
										<label>Name</label>
										@text("poa_name")
									</div>
									<div class="col-md-3 form-group">
										<label>Relationship</label>
										@text("poa_relationship")
									</div>
									<div class="col-md-3 form-group">
										<label>Phone Number</label>
										@phone("poa_phone")
									</div>
									<div class="col-md-3 form-group">
										<label>Email</label>
										@email("poa_email")
									</div>
								</div>
								
								<hr>
								<div class="row">
										<div class="col-md-12">
											@header("Communication Vehicle")
										</div>
									</div>
									<div class="row">
										<div class="col-md-3 form-group">
											@checkbox("Calling", "calling_preference", "calling_preference")
										</div>
								
								
										<div class="col-md-3 form-group">
											@checkbox("SMS", "sms_preference", "sms_preference")
										</div>
							
							
										<div class="col-md-3 form-group">
											@checkbox("Email", "email_preference", "email_preference")
										</div>
							
								
										<div class="col-md-3 form-group">
											@checkbox("Letter", "letter_preference", "letter_preference")
										</div>
									</div>
								<hr>

								<div class="row">
									<div class="col-md-12 form-group text-right">
									@checkbox("Enroll in CCM", "enroll_ccm", "enroll_ccm", "1")
										<!-- <div class="form-check text-right custom-control custom-checkbox">
											<input class="form-check-input" type="checkbox" value="1" id="enroll-ccm" name="enroll_ccm">
											<label class="form-check-label" for="enroll-ccm">Enroll in CCM</label>
										</div> -->
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 form-group text-right">
									@checkbox("Enroll in AWV", "enroll_awv", "enroll_awv", "2")
										<!-- <div class="form-check text-right">
											<input class="form-check-input" type="checkbox" value="2" id="enroll-awv" name="enroll_awv">
											<label class="form-check-label" for="enroll-awv">Enroll in AWV</label>
										</div> -->
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 form-group text-right">
									@checkbox("Enroll in RPM", "enroll_rpm", "enroll_rpm", "3")
										<!-- <div class="form-check text-right">
											<input class="form-check-input" type="checkbox" value="3" id="enroll-rpm" name="enroll_rpm">
											<label class="form-check-label" for="enroll-other">Enroll in RPM</label>
										</div> -->
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 form-group text-right">
									@checkbox("Enroll in TCM", "enroll_tcm", "enroll_tcm", "4")
										<!-- <div class="form-check text-right">
											<input class="form-check-input" type="checkbox" value="4" id="enroll-tcm" name="enroll_tcm">
											<label class="form-check-label" for="enroll-other">Enroll in TCM</label>
										</div> -->
									</div>
								</div>

								
								<div class="row">
									<!-- <div class="col-6 text-left form-group"><a href="http://awvprod.d-insights.global" class="btn btn-primary">Cancel</a></div>  -->
									<div class="col-12 text-right form-group mb-4"><button type="submit" class="btn btn-primary" id="submit">Submit</button></div>
								</div>
							</form>
						</div>	
					</div>	
				</div>	
			</div>	
		</div>	
	</div>	

</div>

@endsection
@section('bottom-js')
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script>
	/*! jQuery Validation Plugin - v1.11.0 - 2/4/2013
	* https://github.com/jzaefferer/jquery-validation
	* Copyright (c) 2013 JÃ¶rn Zaefferer; Licensed MIT */

	/*!
	* jQuery Validation Plugin 1.11.0
	*
	* http://bassistance.de/jquery-plugins/jquery-plugin-validation/
	* http://docs.jquery.com/Plugins/Validation
	*
	* Copyright 2013 JÃ¶rn Zaefferer
	* Released under the MIT license:
	*   http://www.opensource.org/licenses/mit-license.php
	*/

	(function() {

	function stripHtml(value) {
		// remove html tags and space chars
		return value.replace(/<.[^<>]*?>/g, ' ').replace(/&nbsp;|&#160;/gi, ' ')
		// remove punctuation
		.replace(/[.(),;:!?%#$'"_+=\/\-]*/g,'');
	}
	jQuery.validator.addMethod("maxWords", function(value, element, params) {
		return this.optional(element) || stripHtml(value).match(/\b\w+\b/g).length <= params;
	}, jQuery.validator.format("Please enter {0} words or less."));

	jQuery.validator.addMethod("minWords", function(value, element, params) {
		return this.optional(element) || stripHtml(value).match(/\b\w+\b/g).length >= params;
	}, jQuery.validator.format("Please enter at least {0} words."));

	jQuery.validator.addMethod("rangeWords", function(value, element, params) {
		var valueStripped = stripHtml(value);
		var regex = /\b\w+\b/g;
		return this.optional(element) || valueStripped.match(regex).length >= params[0] && valueStripped.match(regex).length <= params[1];
	}, jQuery.validator.format("Please enter between {0} and {1} words."));

	}());

	jQuery.validator.addMethod("letterswithbasicpunc", function(value, element) {
	return this.optional(element) || /^[a-z\-.,()'"\s]+$/i.test(value);
	}, "Letters or punctuation only please");

	jQuery.validator.addMethod("alphanumeric", function(value, element) {
	return this.optional(element) || /^\w+$/i.test(value);
	}, "Letters, numbers, and underscores only please");

	jQuery.validator.addMethod("lettersonly", function(value, element) {
	return this.optional(element) || /^[a-z]+$/i.test(value);
	}, "Letters only please");

	jQuery.validator.addMethod("nowhitespace", function(value, element) {
	return this.optional(element) || /^\S+$/i.test(value);
	}, "No white space please");

	jQuery.validator.addMethod("ziprange", function(value, element) {
	return this.optional(element) || /^90[2-5]\d\{2\}-\d{4}$/.test(value);
	}, "Your ZIP-code must be in the range 902xx-xxxx to 905-xx-xxxx");

	jQuery.validator.addMethod("zipcodeUS", function(value, element) {
	return this.optional(element) || /\d{5}-\d{4}$|^\d{5}$/.test(value);
	}, "The specified US ZIP Code is invalid");

	jQuery.validator.addMethod("integer", function(value, element) {
	return this.optional(element) || /^-?\d+$/.test(value);
	}, "A positive or negative non-decimal number please");

	/**
	* Return true, if the value is a valid vehicle identification number (VIN).
	*
	* Works with all kind of text inputs.
	*
	* @example <input type="text" size="20" name="VehicleID" class="{required:true,vinUS:true}" />
	* @desc Declares a required input element whose value must be a valid vehicle identification number.
	*
	* @name jQuery.validator.methods.vinUS
	* @type Boolean
	* @cat Plugins/Validate/Methods
	*/
	jQuery.validator.addMethod("vinUS", function(v) {
	if (v.length !== 17) {
		return false;
	}
	var i, n, d, f, cd, cdv;
	var LL = ["A","B","C","D","E","F","G","H","J","K","L","M","N","P","R","S","T","U","V","W","X","Y","Z"];
	var VL = [1,2,3,4,5,6,7,8,1,2,3,4,5,7,9,2,3,4,5,6,7,8,9];
	var FL = [8,7,6,5,4,3,2,10,0,9,8,7,6,5,4,3,2];
	var rs = 0;
	for(i = 0; i < 17; i++){
		f = FL[i];
		d = v.slice(i,i+1);
		if (i === 8) {
			cdv = d;
		}
		if (!isNaN(d)) {
			d *= f;
		} else {
			for (n = 0; n < LL.length; n++) {
				if (d.toUpperCase() === LL[n]) {
					d = VL[n];
					d *= f;
					if (isNaN(cdv) && n === 8) {
						cdv = LL[n];
					}
					break;
				}
			}
		}
		rs += d;
	}
	cd = rs % 11;
	if (cd === 10) {
		cd = "X";
	}
	if (cd === cdv) {
		return true;
	}
	return false;
	}, "The specified vehicle identification number (VIN) is invalid.");

	/**
	* Return true, if the value is a valid date, also making this formal check dd/mm/yyyy.
	*
	* @example jQuery.validator.methods.date("01/01/1900")
	* @result true
	*
	* @example jQuery.validator.methods.date("01/13/1990")
	* @result false
	*
	* @example jQuery.validator.methods.date("01.01.1900")
	* @result false
	*
	* @example <input name="pippo" class="{dateITA:true}" />
	* @desc Declares an optional input element whose value must be a valid date.
	*
	* @name jQuery.validator.methods.dateITA
	* @type Boolean
	* @cat Plugins/Validate/Methods
	*/
	jQuery.validator.addMethod("dateITA", function(value, element) {
	var check = false;
	var re = /^\d{1,2}\/\d{1,2}\/\d{4}$/;
	if( re.test(value)) {
		var adata = value.split('/');
		var gg = parseInt(adata[0],10);
		var mm = parseInt(adata[1],10);
		var aaaa = parseInt(adata[2],10);
		var xdata = new Date(aaaa,mm-1,gg);
		if ( ( xdata.getFullYear() === aaaa ) && ( xdata.getMonth() === mm - 1 ) && ( xdata.getDate() === gg ) ){
			check = true;
		} else {
			check = false;
		}
	} else {
		check = false;
	}
	return this.optional(element) || check;
	}, "Please enter a correct date");

	jQuery.validator.addMethod("dateNL", function(value, element) {
	return this.optional(element) || /^(0?[1-9]|[12]\d|3[01])[\.\/\-](0?[1-9]|1[012])[\.\/\-]([12]\d)?(\d\d)$/.test(value);
	}, "Vul hier een geldige datum in.");

	jQuery.validator.addMethod("time", function(value, element) {
	return this.optional(element) || /^([01]\d|2[0-3])(:[0-5]\d){1,2}$/.test(value);
	}, "Please enter a valid time, between 00:00 and 23:59");
	jQuery.validator.addMethod("time12h", function(value, element) {
	return this.optional(element) || /^((0?[1-9]|1[012])(:[0-5]\d){1,2}( ?[AP]M))$/i.test(value);
	}, "Please enter a valid time in 12-hour format");

	/**
	* matches US phone number format
	*
	* where the area code may not start with 1 and the prefix may not start with 1
	* allows '-' or ' ' as a separator and allows parens around area code
	* some people may want to put a '1' in front of their number
	*
	* 1(212)-999-2345 or
	* 212 999 2344 or
	* 212-999-0983
	*
	* but not
	* 111-123-5434
	* and not
	* 212 123 4567
	*/
	jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
	phone_number = phone_number.replace(/\s+/g, "");
	return this.optional(element) || phone_number.length > 9 &&
		phone_number.match(/^(\+?1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
	}, "Please specify a valid phone number");

	jQuery.validator.addMethod('phoneUK', function(phone_number, element) {
	phone_number = phone_number.replace(/\(|\)|\s+|-/g,'');
	return this.optional(element) || phone_number.length > 9 &&
		phone_number.match(/^(?:(?:(?:00\s?|\+)44\s?)|(?:\(?0))(?:(?:\d{5}\)?\s?\d{4,5})|(?:\d{4}\)?\s?(?:\d{5}|\d{3}\s?\d{3}))|(?:\d{3}\)?\s?\d{3}\s?\d{3,4})|(?:\d{2}\)?\s?\d{4}\s?\d{4}))$/);
	}, 'Please specify a valid phone number');

	jQuery.validator.addMethod('mobileUK', function(phone_number, element) {
	phone_number = phone_number.replace(/\s+|-/g,'');
	return this.optional(element) || phone_number.length > 9 &&
		phone_number.match(/^(?:(?:(?:00\s?|\+)44\s?|0)7(?:[45789]\d{2}|624)\s?\d{3}\s?\d{3})$/);
	}, 'Please specify a valid mobile number');

	//Matches UK landline + mobile, accepting only 01-3 for landline or 07 for mobile to exclude many premium numbers
	jQuery.validator.addMethod('phonesUK', function(phone_number, element) {
	phone_number = phone_number.replace(/\s+|-/g,'');
	return this.optional(element) || phone_number.length > 9 &&
		phone_number.match(/^(?:(?:(?:00\s?|\+)44\s?|0)(?:1\d{8,9}|[23]\d{9}|7(?:[45789]\d{8}|624\d{6})))$/);
	}, 'Please specify a valid uk phone number');
	// On the above three UK functions, do the following server side processing:
	//  Compare with ^((?:00\s?|\+)(44)\s?)?\(?0?(?:\)\s?)?([1-9]\d{1,4}\)?[\d\s]+)
	//  Extract $2 and set $prefix to '+44<space>' if $2 is '44' otherwise set $prefix to '0'
	//  Extract $3 and remove spaces and parentheses. Phone number is combined $2 and $3.
	// A number of very detailed GB telephone number RegEx patterns can also be found at:
	// http://www.aa-asterisk.org.uk/index.php/Regular_Expressions_for_Validating_and_Formatting_UK_Telephone_Numbers

	//Matches UK postcode. based on http://snipplr.com/view/3152/postcode-validation/
	jQuery.validator.addMethod('postcodeUK', function(postcode, element) {
	postcode = (postcode.toUpperCase()).replace(/\s+/g,'');
	return this.optional(element) || postcode.match(/^([^QZ][^IJZ]{0,1}\d{1,2})(\d[^CIKMOV]{2})$/) || postcode.match(/^([^QV]\d[ABCDEFGHJKSTUW])(\d[^CIKMOV]{2})$/) || postcode.match(/^([^QV][^IJZ]\d[ABEHMNPRVWXY])(\d[^CIKMOV]{2})$/) || postcode.match(/^(GIR)(0AA)$/) || postcode.match(/^(BFPO)(\d{1,4})$/) || postcode.match(/^(BFPO)(C\/O\d{1,3})$/);
	}, 'Please specify a valid postcode');

	// TODO check if value starts with <, otherwise don't try stripping anything
	jQuery.validator.addMethod("strippedminlength", function(value, element, param) {
	return jQuery(value).text().length >= param;
	}, jQuery.validator.format("Please enter at least {0} characters"));

	// same as email, but TLD is optional
	jQuery.validator.addMethod("email2", function(value, element, param) {
	return this.optional(element) || /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)*(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(value);
	}, jQuery.validator.messages.email);

	// same as url, but TLD is optional
	jQuery.validator.addMethod("url2", function(value, element, param) {
	return this.optional(element) || /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)*(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);
	}, jQuery.validator.messages.url);

	// NOTICE: Modified version of Castle.Components.Validator.CreditCardValidator
	// Redistributed under the the Apache License 2.0 at http://www.apache.org/licenses/LICENSE-2.0
	// Valid Types: mastercard, visa, amex, dinersclub, enroute, discover, jcb, unknown, all (overrides all other settings)
	jQuery.validator.addMethod("creditcardtypes", function(value, element, param) {
	if (/[^0-9\-]+/.test(value)) {
		return false;
	}

	value = value.replace(/\D/g, "");

	var validTypes = 0x0000;

	if (param.mastercard) {
		validTypes |= 0x0001;
	}
	if (param.visa) {
		validTypes |= 0x0002;
	}
	if (param.amex) {
		validTypes |= 0x0004;
	}
	if (param.dinersclub) {
		validTypes |= 0x0008;
	}
	if (param.enroute) {
		validTypes |= 0x0010;
	}
	if (param.discover) {
		validTypes |= 0x0020;
	}
	if (param.jcb) {
		validTypes |= 0x0040;
	}
	if (param.unknown) {
		validTypes |= 0x0080;
	}
	if (param.all) {
		validTypes = 0x0001 | 0x0002 | 0x0004 | 0x0008 | 0x0010 | 0x0020 | 0x0040 | 0x0080;
	}
	if (validTypes & 0x0001 && /^(5[12345])/.test(value)) { //mastercard
		return value.length === 16;
	}
	if (validTypes & 0x0002 && /^(4)/.test(value)) { //visa
		return value.length === 16;
	}
	if (validTypes & 0x0004 && /^(3[47])/.test(value)) { //amex
		return value.length === 15;
	}
	if (validTypes & 0x0008 && /^(3(0[012345]|[68]))/.test(value)) { //dinersclub
		return value.length === 14;
	}
	if (validTypes & 0x0010 && /^(2(014|149))/.test(value)) { //enroute
		return value.length === 15;
	}
	if (validTypes & 0x0020 && /^(6011)/.test(value)) { //discover
		return value.length === 16;
	}
	if (validTypes & 0x0040 && /^(3)/.test(value)) { //jcb
		return value.length === 16;
	}
	if (validTypes & 0x0040 && /^(2131|1800)/.test(value)) { //jcb
		return value.length === 15;
	}
	if (validTypes & 0x0080) { //unknown
		return true;
	}
	return false;
	}, "Please enter a valid credit card number.");

	jQuery.validator.addMethod("ipv4", function(value, element, param) {
	return this.optional(element) || /^(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)$/i.test(value);
	}, "Please enter a valid IP v4 address.");

	jQuery.validator.addMethod("ipv6", function(value, element, param) {
	return this.optional(element) || /^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9A-Fa-f]{1,4}:){0,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$/i.test(value);
	}, "Please enter a valid IP v6 address.");

	/**
	* Return true if the field value matches the given format RegExp
	*
	* @example jQuery.validator.methods.pattern("AR1004",element,/^AR\d{4}$/)
	* @result true
	*
	* @example jQuery.validator.methods.pattern("BR1004",element,/^AR\d{4}$/)
	* @result false
	*
	* @name jQuery.validator.methods.pattern
	* @type Boolean
	* @cat Plugins/Validate/Methods
	*/
	jQuery.validator.addMethod("pattern", function(value, element, param) {
	if (this.optional(element)) {
		return true;
	}
	if (typeof param === 'string') {
		param = new RegExp('^(?:' + param + ')$');
	}
	return param.test(value);
	}, "Invalid format.");


	/*
	* Lets you say "at least X inputs that match selector Y must be filled."
	*
	* The end result is that neither of these inputs:
	*
	*  <input class="productinfo" name="partnumber">
	*  <input class="productinfo" name="description">
	*
	*  ...will validate unless at least one of them is filled.
	*
	* partnumber:  {require_from_group: [1,".productinfo"]},
	* description: {require_from_group: [1,".productinfo"]}
	*
	*/
	jQuery.validator.addMethod("require_from_group", function(value, element, options) {
	var validator = this;
	var selector = options[1];
	var validOrNot = $(selector, element.form).filter(function() {
		return validator.elementValue(this);
	}).length >= options[0];

	if(!$(element).data('being_validated')) {
		var fields = $(selector, element.form);
		fields.data('being_validated', true);
		fields.valid();
		fields.data('being_validated', false);
	}
	return validOrNot;
	}, jQuery.format("Please fill at least {0} of these fields."));

	/*
	* Lets you say "either at least X inputs that match selector Y must be filled,
	* OR they must all be skipped (left blank)."
	*
	* The end result, is that none of these inputs:
	*
	*  <input class="productinfo" name="partnumber">
	*  <input class="productinfo" name="description">
	*  <input class="productinfo" name="color">
	*
	*  ...will validate unless either at least two of them are filled,
	*  OR none of them are.
	*
	* partnumber:  {skip_or_fill_minimum: [2,".productinfo"]},
	*  description: {skip_or_fill_minimum: [2,".productinfo"]},
	* color:       {skip_or_fill_minimum: [2,".productinfo"]}
	*
	*/
	jQuery.validator.addMethod("skip_or_fill_minimum", function(value, element, options) {
	var validator = this;
	var numberRequired = options[0];
	var selector = options[1];
	var numberFilled = $(selector, element.form).filter(function() {
		return validator.elementValue(this);
	}).length;
	var valid = numberFilled >= numberRequired || numberFilled === 0;

	if(!$(element).data('being_validated')) {
		var fields = $(selector, element.form);
		fields.data('being_validated', true);
		fields.valid();
		fields.data('being_validated', false);
	}
	return valid;
	}, jQuery.format("Please either skip these fields or fill at least {0} of them."));

	// Accept a value from a file input based on a required mimetype
	jQuery.validator.addMethod("accept", function(value, element, param) {
	// Split mime on commas in case we have multiple types we can accept
	var typeParam = typeof param === "string" ? param.replace(/\s/g, '').replace(/,/g, '|') : "image/*",
	optionalValue = this.optional(element),
	i, file;

	// Element is optional
	if (optionalValue) {
		return optionalValue;
	}

	if ($(element).attr("type") === "file") {
		// If we are using a wildcard, make it regex friendly
		typeParam = typeParam.replace(/\*/g, ".*");

		// Check if the element has a FileList before checking each file
		if (element.files && element.files.length) {
			for (i = 0; i < element.files.length; i++) {
				file = element.files[i];

				// Grab the mimtype from the loaded file, verify it matches
				if (!file.type.match(new RegExp( ".?(" + typeParam + ")$", "i"))) {
					return false;
				}
			}
		}
	}

	// Either return true because we've validated each file, or because the
	// browser does not support element.files and the FileList feature
	return true;
	}, jQuery.format("Please enter a value with a valid mimetype."));

	// Older "accept" file extension method. Old docs: http://docs.jquery.com/Plugins/Validation/Methods/accept
	jQuery.validator.addMethod("extension", function(value, element, param) {
	param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
	return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
	}, jQuery.format("Please enter a value with a valid extension."));


	$.validator.addMethod("unique", function(value, element) {
		var parentForm = $(element).closest('form');
		var timeRepeated = 0;
		if (value != '') {
			$(parentForm.find(':text')).each(function () {
				if ($(this).val() === value) {
					timeRepeated++;
				}
			});
		}
		return timeRepeated === 1 || timeRepeated === 0;

	}, "* Duplicate");

	$.validator.addMethod("roles", function(value, elem, param) {
	return $(".roles:checkbox:checked").length > 0;
	},"You must select at least one!");

	$.validator.addMethod("valueNotEquals", function(value, element, arg){
	return arg !== value;
	}, "Value must not equal arg.");
</script>
<script>

  // Initialize form validation on the registration form.
  // It has the name attribute "registration"
  $('#submit').on('click', function() {
	  debugger;
	$("#patient_registration_form").validate({
		// Specify validation rules
		rules: {
		// The key name on the left side is the name attribute
		// of an input field. Validation rules are defined
		// on the right side
		practice_id: "required",
		physician_id: {
			required : true,
			number: true,
		},
		fname: {
			required : true,
			lettersonly: true,
		},
		lname: {
			required : true,
			lettersonly: true,
		},
		mname: {
			required : true,
			lettersonly: true,
		},
		gender: "required",
		marital_status: "required",
		dob: "required",
		addr1: "required",
		//   addr2: "required",
		city: "required",
		state: "required",
		zip:{
			required : true,
			number: true,
			minlength: 5,
			maxlength: 5,
		},
		ethnicity1: "required", 
		//   ethnicity2: "required", 
		education: "required", 
		occupation_status: "required",
		occupation_description: "required",
		military: "required",

		//   no_email : {required : "#poa:not(:checked)"}
		
		//   preferred_contact: "required",
		//   email: "required",
		email: {
			required : "#no_email:not(:checked)",
			// unique: true,
		},
		phone_primary: {
			required : true,
			phoneUS: 10,
			unique: true,
		},
		phone_secondary: {
			phoneUS: 10,
			unique: true,
		},
		other_contact_name: "required",
		other_contact_relationship: "required",
		other_contact_phone: {
			required : true,
			unique: true,
		},
		other_contact_email: {
			required : true,
			// remote: true,
		},
		//   insurance_type[]: "required",
		//   insurance_id[]: "required",
		//   insurance_type[]: "required",
		//   insurance_id[]: "required",
		emr: "required",
		//   poa: "required",
		poa_name: {required : "#poa:checked"},
		poa_relationship: {required : "#poa:checked"},
		poa_phone: {required : "#poa:checked",
						unique: true,},
		poa_email: {
				required : "#poa:checked", 
				// unique: true,
		},
		age: {required : true
				},
		'insurance_type[1]':{
			required : true,
			unique: true,
		},
		'insurance_id[1]':{
			required : true,
			unique: true,
		} ,
		'insurance_type[2]':{
			unique: true,
		} ,
		'insurance_id[2]': {
			required: function(element){
					return $("insurance_type[2]").val()!="";
				},
			unique: true,
		},
		// 'calling_preference': {
		// 	// required : true,
		// 	roles: true,
		// }
		
		},
		// Specify validation error messages
		messages: {
			practice_id: "* Please select Practice",
			physician_id: 
					{
						required: "* Please select Physician",
						number: "* Please select Physician",
					},
			fname: {
					required: "* Please enter your First Name",
					lettersonly: "* Special characters/Numbers are not allowed",
					},
			lname: {
					required: "* Please enter your Last Name",
					lettersonly: "* Special characters/Numbers are not allowed",
					},
			mname: {
					required: "* Please enter your Last Name",
					lettersonly: "* Special characters/Numbers are not allowed",
					},
			gender: "* Please select Gender",
			marital_status: "* Please select Marital Status",
			dob: "* Please select Date Of Birth",
			age:  {
					required: "* Please enter your Age",
				},
			addr1: "* Please enter your Address",
			// addr2: "Please enter your Address",
			city: "* Please enter your City",
			state: "* Please enter your State",
			zip: {
					required: "* Please enter your Zip",
					number: "* Please enter numbers only",
					minLength: "* Minimum length must be 5",
					maxlength: 	"* Maximum length must be 5",
				},
			ethnicity1: "* Please Select Ethnicity",
			// ethnicity2: "Please Select Ethnicity",
			education: "* Please Select Education",
			occupation_status: "* Please Select occupation",
			occupation_description: "* Please enter Occupation Description",
			military: "* Please Select Military status",
			
			// preferred_contact: "Please enter Preferred Contact",
			email: 
				{
					required:"* Please enter Email Id",
					// unique: "* Email Id must be unique",
				},
			phone_primary:
				{
					required:  "* Please enter Primary Phone Number",
					phoneUS: "* Minimum length must be 10",
					unique: "Primary phone number must be unique",
				},
			phone_secondary: 		
				{
					phoneUS: "* Minimum length must be 10",
					unique: "Secondary phone number must be unique",
				},
			other_contact_name: "* Please enter Other Contact Name",
			other_contact_relationship: "* Please enter Other Contact Relationship",
			other_contact_phone: 
						{
							required:  "* Please enter Other Contact Phone Number",
							unique: "* Phone number must be unique",
						},
			other_contact_email:
			{
					required: "* Please enter Other Contact Email Id",
					// unique: "* Email Id must be unique",
				},

			// insurance_id[]: "Please enter Primary Phone Number",
			// insurance_type[]: "Please enter Primary Phone Number",
			// insurance_id[]: "Please enter Primary Phone Number",
			emr: "* Please enter EMR",
			// poa: "Please enter Name",
			poa_name: "* Please enter Name",
			poa_relationship: "* Please enter Relationship",
			poa_phone:
					{
						required:  "* Please enter Other Contact Phone Number",
						unique: "* Phone number must be unique",
					},
			poa_email:
			{
					required: "* Please enter Email id",
					// unique: "* Email Id must be unique",
				},
			// phone_primary: "Please enter Email id",
			'insurance_type[1]':{
				required: "* Please enter Primary Insurance",
				unique: "* Insurance must be unique",
			} ,
			'insurance_id[1]':{
				required: "* Please enter Primary Insurance ID",
				unique: "* Insurance ID must be unique",
			} ,
			'insurance_type[2]':{
				unique: "* Insurance must be unique",
			} ,
			'insurance_id[2]':{
				required: "* Please enter Secondary Insurance ID",
				unique: "* Insurance ID must be unique",
			} ,	
		},
		// Make sure the form is submitted to the destination defined
		// in the "action" attribute of the form when valid
		submitHandler: function(form) {
		debugger;
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				type: 'post',
				url : '/rpm/ajax/submitRegistration',
				data : $(form).serialize(),
				success: function (response) {
					if(response != ""){
						$('#success').delay(6000).fadeOut('slow').html('<div class="alert alert-success col-ssm-12" >  <button class="close" data-dismiss="alert"></button> Patient Registered Successfully  </div>');
						$("html, body").animate({ scrollTop: 0 }, "slow");
					}
					// $('#msg').delay(5000).fadeOut('slow');
					$("#patient_registration_form").trigger("reset");
					//  sectionId = sectionId.replace("_form", '');
					//  renderAllergiesTable(patient_id, sectionId);
					//  $("#"+sectionId+"_form").trigger('reset');
				
				},
				
				error: function (data) {
					alert(JSON.stringify(data));
					console.log('Error:', data);
				}

				// error: function (request, status, error) {
				//     alert(JSON.stringify(request.responseJSON.errors));
				//     console.log(request.responseJSON.errors);
					// if(request.responseJSON.errors.content_title) {
					// $('[name="content_title"]').addClass("is-invalid");
					// $('[name="content_title"]').next(".invalid-feedback").html(request.responseJSON.errors.content_title);
					// } else {
					// $('[name="content_title"]').removeClass("is-invalid");
					// $('[name="content_title"]').next(".invalid-feedback").html('');
					// }
			// }
			});
		//   form.submit();
		// patientRegistration.init();
		}
	});
});

$(document).ready(function() {
patientRegistration.init();
});


    </script>
@endsection