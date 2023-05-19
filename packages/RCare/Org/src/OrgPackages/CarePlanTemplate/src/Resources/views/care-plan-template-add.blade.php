@extends('Theme::layouts.master')
<!-- <link rel="stylesheet" href="https://davidstutz.de/bootstrap-multiselect/dist/css/bootstrap-multiselect.css">   -->
<link rel="stylesheet" type="text/css" href="https://rcare.d-insights.global/assets/styles/vendor/multiselect/bootstrap-multiselect.css">
<link href="//rawgithub.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.css" rel="stylesheet">
@section('main-content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
	<div id="success"></div>
	<div class="card-body">
		<div class="row mb-4">

			<div class="col-md-12  mb-4">
				<div class="card mb-4">
					<div class="card-header mb-3">Add Care Plan Template</div>
					<div class="card-body" id="hobby">
						<div id="error_msg"></div>
						<form action="{{ route("ajax.save.careplantemplate")}}" method="post" name ="main_care_plan_template_form"  id="main_care_plan_template_form">
							@csrf
							<div class="alert alert-success" id="success-alert" style="display: none;">
								<button type="button" class="close" data-dismiss="alert">x</button>
								<strong> Care Plan Template Added successfully! </strong><span id="text"></span>
							</div>
							<div class="row">	
								<div class="col-6">
									<div class="form-group">
										<label>Condition<span class="error">*</span></label>
										
										<div id="addcondition">
											<select name="drop_condition" id="condition"  class="custom-select show-tick forms-element">
												<option value="">Select Condition</option>
												@foreach($condition as $key => $value)                
												<option value="{{ $value->id}}">{{ $value->condition}}</option>     
												@endforeach
											</select>
											<div class='invalid-feedback'></div>
										</div>

									</div>

								</div>

								<div class="col-6">
									<div class="form-group">	

										<label>Code <!-- <span class='error'>*</span> --></label>
										
										<div id="addcode">
											<select name="drop_code" id="code" class="custom-select show-tick forms-element" onchange="codevailable();">
												<option value="">Select Code</option>             
											</select>
											<div class='invalid-feedback'></div></div>

										</div>
										<!-- <i class="plus-icons i-Add"  class="btn btn-sprimary float-right"  id="addcode" title="Add New Code"></i> -->
									</div>

								</div>
								@include('CarePlanTemplate::care-plan-template')
							</form>
							<div id="error_msg"></div>
						</div>	
					</div>	
				</div>	

			</div>	
		</div>	
  </div>	
<div id="app"></div> 
@endsection
@section('page-js')
<script src="//rawgithub.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.js"></script>
<!--<script type="text/javascript" src="https://davidstutz.de/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>-->
<script src="https://rcare.d-insights.global/assets/js/vendor/multiselect/bootstrap-multiselect.js"></script>
<!-- (Optional) Latest compiled and minified JavaScript translation files -->

<script type="text/javascript">
$(document).ready(function() {
	//main_diagnosis_form.init();
	carePlanTemplate.init();
	util.getToDoListData(0, {{getPageModuleName()}});
    //page js

  var codevailable=function(){
    var codeval=$("#codeid").val();
    var codeval1=$("#code").val();
    var diagnosis_id = $("#condition").val();
             // console.log(codeval1+"*******"+codeval);
  if(codeval1!=''){
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: 'post',
      url: '/org/code-availabel',
      data: 'code=' + codeval1 + '&diagnosis_id=' + diagnosis_id,
      success: function(response) {
      //  console.log(response+"test10");
      if(response == 0){
        if(confirm("Are you sure you want to change the code?"))
        {
          $("#code").val(codeval1);
        }
        else
        {  
          $("#code").val(codeval);
        }
      }
    },
    });
  }else{
    alert("select code or enter new code");
  }
  };

    // var addnewmedication=function(val){
    //     var medid=val.id;   
    //     var count = medid.split("_");      
    //     if(val.value=='other')
    //       {   if(medid=='medication_med_id_0')
    //     {
    //       $('.addtext').append('<input type="text" class="form-control col-md-3" id="'+medid+'" name="newmedications[]" style="margin-left: 17px;"><div class="invalid-feedback" style="margin-left: 331px;"></div>');
    //     }
    //     else
    //     {       

    //       $('#newmed'+count[3]).show().append('<input type="text" class="form-control col-md-12" id="'+medid+'" name="newmedications[]"><div class="invalid-feedback"></div>');

    //     }
    //   }else{

    //     $('input[id="'+medid+'"]').remove();
    //     $('#newmed'+count[3]).hide();
    //   }
    // };

    // $(".dropdown dt a").on('click', function() {
    //   $(".dropdown dd ul").slideToggle('fast');
    // });

    // $(".dropdown dd ul li a").on('click', function() {
    //   $(".dropdown dd ul").hide();
    // });

    // function getSelectedValue(id) {
    //   return $("#" + id).find("dt a span.value").html();
    // }

    // $('.multiDrop').on('click', function (event) {
    //   event.stopPropagation();
    //   $(this).next('ul').slideToggle();
    // });

    // $(document).on('click', function () {  
    //   if (!$(event.target).closest('.wrapMulDrop').length) {
    //     $('.wrapMulDrop ul').slideUp();
    //   }
    // }); 

    // $('.wrapMulDrop ul li input[type="checkbox"]').on('change', function () {
    //   var x = $('.wrapMulDrop ul li input[type="checkbox"]:checked').length;
    //   if (x != "") {
    //     $('.multiDrop').html(x + "" + " " + "selected");
    //   } else if (x < 1) {
    //     $('.multiDrop').html('Select <i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
    //   }
    // }); 
    //end page js
});
</script>
@endsection