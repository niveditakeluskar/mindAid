@extends('Theme::layouts.master')
<!-- <link rel="stylesheet" href="https://davidstutz.de/bootstrap-multiselect/dist/css/bootstrap-multiselect.css">   -->

<link rel="stylesheet" type="text/css" href="https://rcare.d-insights.global/assets/styles/vendor/multiselect/bootstrap-multiselect.css">
@section('main-content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<div id="app">
	<div id="threshold-success"></div>
	<div class="card-body">
		<div class="row mb-4">

			<div class="col-md-12  mb-4">
				<div class="card mb-4">
					<div class="card-header mb-3">Renova Standard Threshold</div>
					<div class="card-body" id="hobby">
						<div id="error_msg"></div>
            <form action="{{ route('create_threshold_group') }}" method="POST" name="group_threshold_form" id="group_threshold_form">
                {{ csrf_field() }}
                <input type="hidden" name="practice_id" id="prid">       
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12 form-group mb-3 " style="display: none">
                                <label for="practicename">Goup Code  <span style="color:red">*</span></label>
                                @text("group_code", ["placeholder" => ""])
                            </div>
                            <div class="col-md-3 form-group mb-3 ">
                                <label for="practicename">Systolic High <span style="color:red">*</span></label>
                                @text("systolichigh",["placeholder" => ""])
                            </div>
                            <div class="col-md-3 form-group mb-3 ">
                                <label for="practicename">Systolic Low <span style="color:red">*</span></label>
                                @text("systoliclow",["placeholder" => ""])
                            </div>
                             <div class="col-md-3 form-group mb-3 ">
                                <label for="practicename">Diastolic High <span style="color:red">*</span></label>
                                @text("diastolichigh",["placeholder" => ""])
                            </div>
                            <div class="col-md-3 form-group mb-3 "> 
                                <label for="practicename">Diastolic Low <span style="color:red">*</span></label>
                                @text("diastoliclow",["placeholder" => ""])
                            </div>
                            <div class="col-md-3 form-group mb-3 ">
                                <label for="practicename">Heart Rate High <span style="color:red">*</span></label>
                                @text("bpmhigh",["placeholder" => ""])
                            </div>
                            <div class="col-md-3 form-group mb-3 ">
                                <label for="practicename">Heart Rate Low <span style="color:red">*</span></label>
                                @text("bpmlow",["placeholder" => ""])
                            </div>
                           <div class="col-md-3 form-group mb-3 ">
                                <label for="practicename">Oxygen Saturation High <span style="color:red">*</span></label>
                                @text("oxsathigh",["placeholder" => ""])
                            </div>
                            <div class="col-md-3 form-group mb-3 ">
                                <label for="practicename">Oxygen Saturation Low <span style="color:red">*</span></label>
                                @text("oxsatlow",["placeholder" => ""])
                            </div>
                            <div class="col-md-3 form-group mb-3 ">
                                <label for="practicename">Glucose High <span style="color:red">*</span></label>
                                @text("glucosehigh",["placeholder" => ""])
                            </div>
                            <div class="col-md-3 form-group mb-3 ">
                                <label for="practicename">Glucose Low <span style="color:red">*</span></label>
                                @text("glucoselow",["placeholder" => ""])
                            </div>
                            <div class="col-md-3 form-group mb-3 ">
                                <label for="practicename">Temperature High <span style="color:red">*</span></label>
                                @text("temperaturehigh",["placeholder" => ""])
                            </div>
                            <div class="col-md-3 form-group mb-3 ">
                                <label for="practicename">Temperature Low <span style="color:red">*</span></label>
                                @text("temperaturelow",["placeholder" => ""])
                            </div>

                           <!--  <div class="col-md-3 form-group mb-3 ">
                                <label for="practicename">Weight High<span style="color:red">*</span></label>
                                @text("weighthigh",["placeholder" => ""])
                            </div>
                            <div class="col-md-3 form-group mb-3 ">
                                <label for="practicename">Weight low<span style="color:red">*</span></label>
                                @text("weightlow",["placeholder" => ""])
                            </div>
                            
                            <div class="col-md-3 form-group mb-3 ">
                                <label for="practicename">Spirometer-FEV High <span style="color:red">*</span></label>
                                @text("spirometerfevhigh",["placeholder" => ""])
                            </div>
                            <div class="col-md-3 form-group mb-3 ">
                                <label for="practicename">Spirometer-FEV low<span style="color:red">*</span></label>
                                @text("spirometerfevlow",["placeholder" => ""])
                            </div>
                            <div class="col-md-3 form-group mb-3 ">
                                <label for="practicename">Spirometer-PEF High  <span style="color:red">*</span></label>
                                @text("spirometerpefhigh",["placeholder" => ""])
                            </div>
                            <div class="col-md-3 form-group mb-3 ">
                                <label for="practicename">Spirometer-PEF low <span style="color:red">*</span></label>
                                @text("spirometerpeflow",["placeholder" => ""])
                            </div> -->
                                            
                        </div>
                        
                        
                    </div>
               
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit"  class="btn  btn-primary m-1 save_group_threshold" >Save</button>
                                <!-- <button type="button" class="btn btn-info float-left additionalProvider" id="additionalProvider">Add Provider</button> -->
                                <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
                        <div id="error_msg"></div>
						</div>	
					</div>	
				</div>	

			</div>	
		</div>	
	</div>	

</div>


@endsection
@section('page-js')
<!--Andy22Nov21-->
<!-- <script src="//rawgithub.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.js"></script>
<link href="//rawgithub.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.css" rel="stylesheet"> -->

<!--<script type="text/javascript" src="https://davidstutz.de/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>-->
<script src="https://rcare.d-insights.global/assets/js/vendor/multiselect/bootstrap-multiselect.js"></script>
<!-- (Optional) Latest compiled and minified JavaScript translation files -->

<script type="text/javascript">
$(document).ready(function() {

		//main_diagnosis_form.init();
		threshold.init();
		util.getToDoListData(0, {{getPageModuleName()}});
	});



   </script>
   @endsection