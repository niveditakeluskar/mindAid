const URL_POPULATE = "/org/ajax/partner_populate";


var populateForm = function (id, url) {
	$.get(url, id,
		//data,
		function (result) {
			// var address = $("form[name='family_patient_data_form'] #patient_address").val();
			for (var key in result) {
				

				form.dynamicFormPopulate(key, result[key]);  

				$("#append_devicediv").empty();
				$("#append_devicediv").html('');    
				$("#append_partnerapidiv").empty();
				$("#append_partnerapidiv").html(''); 


				var partnerdeviceparameters = result[key].static['paramdata'];
				var partnerapiparamdata = result[key].static['partnerapiparamdata'];
				console.log(partnerdeviceparameters);
				console.log(partnerapiparamdata);
				var len = partnerdeviceparameters.length;
				// var newlen = $('#editpartnerapidiv').length;
				var newlen = partnerapiparamdata.length;

	

				

				for (i = 0; i < partnerdeviceparameters.length; i++) {
				console.log("partnerdeviceparameters",partnerdeviceparameters.length);

					var a =$('#editdevicediv').html();  
					$("#append_devicediv").append(a);  
					$("#append_devicediv").show();
					$('#p1').attr("id","p1_"+i); 

					$("#editdevices").attr("id","editdevices_"+(i));
					// $("#editdevices").attr("name","editdevices_"+(i));

					$("#editpartner_device_name").attr("id","editpartner_device_name_"+(i)); 
					// $("#editpartner_device_name").attr("name","editdevices_"+(i)); 

					$("#addicon").attr("id","addicon_"+(i)); 
					// $("#addicon").attr("name","addicon_"+(i)); 

					$("#removeicon").attr("id","removeicon_"+(i));
					// $("#removeicon").attr("name","removeicon_"+(i));
					// $("#remove_editparameter").attr("id","remove_editparameter_"+(i));

					$("#editdevices_"+(i)).select2().val(partnerdeviceparameters[i]['device_id']).trigger('change');
					$("#editpartner_device_name_"+(i)).val(partnerdeviceparameters[i]['device_name_api']);

					if(i==0){ 
                  
						$("#addicon_0").show(); 
						$("#removeicon_0").hide();  
						// $("#remove_editparameter_0").hide();

					 }

					 

				}

				$("#addicon_0").click(function(){

					$(".invalid-feedback").text("");
					$(".form-control").removeClass("is-invalid");

					len++;
				   var a =$('#editdevicediv').html();  
				   $("#append_devicediv").append(a);  
				   $("#append_devicediv").show();
				   $('#p1').attr("id","p1_"+len); 

				   $("#editdevices").attr("id","editdevices_"+(len));
				   // $("#editdevices").attr("name","editdevices_"+(i));

				   $("#editpartner_device_name").attr("id","editpartner_device_name_"+(len)); 
				   // $("#editpartner_device_name").attr("name","editdevices_"+(i)); 

				   $("#addicon").attr("id","addicon_"+(len)); 
				   // $("#addicon").attr("name","addicon_"+(i)); 

				   $("#removeicon").attr("id","removeicon_"+(len));  
				   // $("#removeicon").attr("name","removeicon_"+(i));
				//    $("#remove_editparameter").attr("id","remove_editparameter_"+(len));
				});
				


				for(j=0; j< partnerapiparamdata.length; j++){
					var a =$('#editpartnerapidiv').html();  
					$("#append_partnerapidiv").append(a);  
					$("#append_partnerapidiv").show();
					$('#p2').attr("id","p2_"+j); 
					 

					$("#editurl").attr("id","editurl_"+(j));					
					$("#editusername").attr("id","editusername_"+(j)); 
					$("#editpassword").attr("id","editpassword_"+(j));
					$("#editstatus").attr("id","editstatus_"+(j));
					$("#editenv").attr("id","editenv_"+(j));
					 

					$("#addiconpartnerapi").attr("id","addiconpartnerapi_"+(j)); 
					// $("#addicon").attr("name","addicon_"+(i)); 

					$("#removeiconpartnerapi").attr("id","removeiconpartnerapi_"+(j));
					// $("#removeicon").attr("name","removeicon_"+(i));

					$("#editstatus_"+(j)).select2().val(partnerapiparamdata[j]['status']).trigger('change');
					$("#editenv_"+(j)).select2().val(partnerapiparamdata[j]['env']).trigger('change');
					$("#editusername_"+(j)).val(partnerapiparamdata[j]['username']);
					$("#editpassword_"+(j)).val(partnerapiparamdata[j]['password']);
					$("#editurl_"+(j)).val(partnerapiparamdata[j]['url']);

					if(j==0){ 
                  
						$("#addiconpartnerapi_0").show(); 
						$("#removeiconpartnerapi_0").hide();     
					 }

				}

				
				$('#addiconpartnerapi_0').click(function(){
					 
					$(".invalid-feedback").text("");
					$(".form-control").removeClass("is-invalid");

					newlen++;
					var a =$('#editpartnerapidiv').html();  
					$("#append_partnerapidiv").append(a);  
					$("#append_partnerapidiv").show();
					$('#p2').attr("id","p2_"+newlen); 
					//    var k = $('#editdevicediv').length;
					//    alert(k);
					
					$("#editurl").attr("id","editurl_"+(newlen));					
					$("#editusername").attr("id","editusername_"+(newlen)); 
					$("#editpassword").attr("id","editpassword_"+(newlen));
					$("#editstatus").attr("id","editstatus_"+(newlen));
					$("#editenv").attr("id","editenv_"+(newlen));
					 

					$("#addiconpartnerapi").attr("id","addiconpartnerapi_"+(newlen)); 					 
					$("#removeiconpartnerapi").attr("id","removeiconpartnerapi_"+(newlen));
					  
				 });

			
				  
		
					// $('.remove-icons').on('click', function () { 
						
					// 	removepractice($(this).attr('id'));
					// 	}); 

				
			}
		}
	).fail(function (result) {
		console.error("Population Error:", result);
	});
};



var onAddPartnerData = function (formObj, fields, response) {
	//console.log(response+"testresponse");
	if (response.status == 200) {
		$("#success").show();
		$("#AddPartnerForm")[0].reset();
		renderPartnerTable();
		//$("form[name='AddPartnerForm'] .alert").show();
		$('#add_partner_modal').modal('hide');
		var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Partner Added Successfully!</strong></div>';
		$("#success").html(txt);
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		//goToNextStep("call_step_1_id");
		setTimeout(function () {
			$("#success").hide();
		}, 3000);

	 } 
	//else {
    //     var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill mandatory fields!</strong></div>';
    //     $("#success").html(txt);
    //     $("#success").show();
    //     setTimeout(function () {
    //         $("#success").hide();
    //     }, 3000);
    //}
};

var onEditPartnerData = function (formObj, fields, response) {
	if (response.status == 200) {
		$("#success").show();
		renderPartnerTable();		
		$('#edit_partner_modal').modal('hide');
		$("#editPartnerForm")[0].reset();
		var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Partner Updated Successfully!</strong></div>';
		$("#success").html(txt);
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		//goToNextStep("call_step_1_id");
		setTimeout(function () {
			$("#success").hide();
		}, 3000);
	} 
	
};

$('#addPartner').click(function () {
	$("#AddPartnerForm")[0].reset();
	$(".invalid-feedback").text("");
	$(".form-control").removeClass("is-invalid");	
	$('#add_partner_modal').modal('show');
});



var init = function () {
	form.ajaxForm("AddPartnerForm", onAddPartnerData, function () {
		return true;
	});

	$('body').on('click', '.edit', function () {
		var id = $(this).data('id');
		$(".invalid-feedback").text("");
		$(".form-control").removeClass("is-invalid");		
		$('#edit_partner_modal').modal('show');
		var url = URL_POPULATE + "/" + id + "/populate";
		populateForm(id, url);
	});

	$('body').on('click', '.change_partnerstatus_active', function () {
		var id = $(this).data('id');
		if (confirm("Are you sure you want to Deactivate this Partner")) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				type: 'post',
				url: '/org/PartnerStatus/' + id,
				// data: {"_token": "{{ csrf_token() }}","id": id},
				data: { "id": id },
				success: function (response) {
					renderPartnerTable();
					$("#success").show();
					var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Partner Deactivated Successfully!</strong></div>';
					$("#success").html(txt);
					var scrollPos = $(".main-content").offset().top;
					$(window).scrollTop(scrollPos);
					//goToNextStep("call_step_1_id");
					setTimeout(function () {
						$("#success").hide();
					}, 3000);
				}
			});
		} else { return false; }
	});

	$('body').on('click', '.change_partnerstatus_deactive', function () {
		var id = $(this).data('id');

		if (confirm("Are you sure you want to Activate this Partner")) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				type: 'post',
				url: '/org/PartnerStatus/' + id,
				// data: {"_token": "{{ csrf_token() }}","id": id},
				data: { "id": id },
				success: function (response) {
					renderPartnerTable();
					$("#msg").show();
					var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Partner  Activated Successfully!</strong></div>';
					$("#msg").html(txt);
					var scrollPos = $(".main-content").offset().top;
					$(window).scrollTop(scrollPos);
					//goToNextStep("call_step_1_id");
					setTimeout(function () {
						$("#msg").hide();
					}, 3000);
				}
			});
		} else { return false; }
	});


	// $('body').on('click', '.medstatus', function () {
	//    //  alert("test"+);
	// 	var id = $(this).data('id');	
	// 	var url = "PartnerStatus/" + id;		
	// 	// alert("test"+id);		

	// 	  $.ajax({
	//               type:'get',
	//               url:url, 
	//               id:"id="+id,             
	//               success:function(data) {
	//                // var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Partner Updated Successfully!</strong></div>';
	//                // $("#success").html(txt); 
	//               // console.log("test"+trueOrFalse(data));
	//                renderPartnerTable();
	//               }
	//            });
	// });

	form.ajaxForm("editPartnerForm", onEditPartnerData, function () {
		return true;
	});
};




window.partner = {
	init: init
	//onResult: onResult
};