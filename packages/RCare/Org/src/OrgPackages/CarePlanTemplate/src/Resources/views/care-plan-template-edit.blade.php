@extends('Theme::layouts.master')
<link rel="stylesheet" type="text/css" href="https://rcare.d-insights.global/assets/styles/vendor/multiselect/bootstrap-multiselect.css">
@section('main-content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

<div class="breadcrusmb">

    <div class="row" style="margin-top: 14px;">
		<div class="col-md-10">
		   <h4 class="card-title mb-3">Edit Care Plan Template</h4>
		</div>
		<div class="col-md-2">
		  <input type="button" class="btn btn-primary" style="float: right" id="CarePlanPdf" value="Print Care Plan">
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
			<form action="{{ route("ajax.save.careplantemplate")}}" method="post" name ="main_edit_care_plan_template_form"  id="main_edit_care_plan_template_form">
				@csrf
				<div class="alert alert-success" id="success-alert" style="display: none;">
					<button type="button" class="close" data-dismiss="alert">x</button>
					<strong> Care Plan Template Updated successfully! </strong><span id="text"></span>
				</div>
				<input type="hidden" name="id" id="id">
				<div class="row">	
								<div class="col-6">
									<div class="form-group">
										<label>Condition<span class="error">*</span></label>
										<div  id="editcodition">
										 <input type="text" name="condition" id="conditionname" class="form-control"   readonly="">
										</div> 								
										 <input type="hidden" name="drop_condition" value="1">
							          
							          </div>

									</div>
								

								<div class="col-6">
									<div class="form-group">			
										<label>Code <!-- <span class='error'>*</span> --></label>
										<div id="editcode" >
										 <input type="text" name="code" id="codenm" class="form-control" readonly="">		
									  </div>
									   <input type="hidden" name="drop_code" value="12">

									</div>
									<!-- <i class="plus-icons i-Add"  class="btn btn-sprimary float-right"  id="addcode" title="Add New Code"></i> -->
								</div>

							</div>
				@include('CarePlanTemplate::care-plan-template')
			</form>
		</div>	
	</div>	
	</div>	
</div>	

@endsection
@section('page-js')
<script src="https://rcare.d-insights.global/assets/js/vendor/multiselect/bootstrap-multiselect.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			carePlanTemplate.init();
			util.getToDoListData(0, {{getPageModuleName()}});
		});

		$('#CarePlanPdf').click(function(){
			var id=$('#id').val();	
            window.open('/org/care-plan-template-pdf/'+id, '_blank');
		})
		

		// var addnewmedication=function(val){  
		// var medid=val.id;   
		//  var count = medid.split("_");      
		// if(val.value=='other')
		// {   if(medid=='medication_med_id_0')
		// 	{
		//   $('.addtext').append('<input type="text" class="form-control col-md-3" id="'+medid+'" name="newmedications[]" style="margin-left: 17px;"><div class="invalid-feedback" style="margin-left: 331px;"></div>');
		// 	}
		// 	else
		// 	{				
             
		//   $('#newmed'+count[3]).show().append('<input type="text" class="form-control col-md-12" id="'+medid+'" name="newmedications[]"><div class="invalid-feedback"></div>');

		// 	}
		// }else{
		  
		//   $('input[id="'+medid+'"]').remove();
		//     $('#newmed'+count[3]).hide();
		// }
		// };


		// $('.multiDrop').on('click', function (event) {
  //               event.stopPropagation();
  //               $(this).next('ul').slideToggle();
  //           });

		// $(document).on('click', function () {
  //           if (!$(event.target).closest('.wrapMulDrop').length) {
  //               $('.wrapMulDrop ul').slideUp();
  //           }
		// });
		
		// $('.wrapMulDrop ul li input[type="checkbox"]').on('change', function () {
  //           var x = $('.wrapMulDrop ul li input[type="checkbox"]:checked').length;
  //           if (x != "") {
  //               $('.multiDrop').html(x + "" + " " + "selected");
  //           } else if (x < 1) {
  //               $('.multiDrop').html('Select <i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
  //           }
  //       });	
	</script>
@endsection