<template>
	<div class="card">
		<button type="button" id="RenderGeneralQuestion" onclick='checkQuestion()' style="display: none"></button>
		<label class="checkbox checkbox-primary mr-3">
			<input type="checkbox" name="ajaxRenderTree">
			<span></span>
			<span class="checkmark"></span>
		</label>
			<input type="text" class='form-control col-md-5' >
			<input type="hidden" class='form-control col-md-5 firsttbox'  style="display:none" >
		<label class="radio radio-primary mr-3">
			<input type="radio" >
			<span></span>
			<span class="checkmark"></span>
		</label>
		<div class="row" style="margin-bottom:5px;">
			<div class="col-lg-12 mb-3">
				@selectGQ("genquestionselection",$dmodule_id,$stage_id,["id" => "genquestionselection", "class" => "mb-3 select2 capital-first" ])	
			</div>
		</div>
		<div class="alert alert-success general_success" id="success-alert" style="display: none;">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong> General question data saved successfully! </strong><span id="text"></span>
		</div>
		<form name="general_question_form_" id="general_question_form_" style="display:none">
			<input type="hidden" name="uid" >	
			<input type="hidden" name="start_time" value="00:00:00">
			<input type="hidden" name="end_time" value="00:00:00">
			<div class="row">
				<div class="col-lg-12 mb-3">
					<div class="card">
						<div class="card-body">
							<div class="card-title"></div>				
							<input type="hidden" name="uid" >
							<input type="hidden" name="patient_id" >
							<input type="hidden" name="m_id">
							<input type="hidden" name="c_id">
							<input type="hidden" name="module_id1" value="1">
							<input type="hidden" name="component_id1" value="1">
							<input type="hidden" id="stage_id" name="stage_id1" value="">
							<input type="hidden" name="stage_code1" value="">
							<input type="hidden" name="step_id" value="">
							<input type="hidden" name="form_name" value="general_question_form">
							<input type="hidden" name="template_id1" value="">
							<div class="mb-4 radioVal" id="1general_question11">
								<label for="are-you-in-pain" class="col-md-12"><input type="hidden" name="DT_" value="dt">sdfsdf</label>
								<input type="hidden" name="sq" value="0">
								<div class="d-inline-flex mb-2 col-md-12">
								</div>
								<p class="message" style="color:red"></p>
							</div>
							<div id="question1"></div>
							<div id="in-pain">
								<label for="" class="mr-3">Current Monthly Notes:</label>
								<input type="hidden" name="monthly_topic" value=" Related Monthly Notes">	 
								<textarea class="form-control" placeholder="Monthly Notes" name="monthly_notes">dc</textarea>
								<p class="txtmsg" style="color:red"></p>
							</div>
							<hr>
						</div>
						<div class="card-footer"> 
							<div class="mc-footer"> 
								<div class="row">
									<div class="col-lg-12 text-right">
										<button type="button" class="btn  btn-primary m-1 office-visit-save" id="generalQue1" onclick="saveGeneralQuestions()">Save</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> 
		</form>
		<div class="alert alert-success general_success" id="success-alert" style="display: none;">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong> General question data saved successfully! </strong><span id="text"></span>
		</div>
		@selectGQ("genquestionselection",$dmodule_id,$stage_id,["id" => "genquestionselection1", "class" => "mb-3 bottom select2"])						
		<div style="padding-left: 20px; color:red; font-size:13px;"><b>Select additional applicable questions</b></div>
		<button type="button" class="btn  btn-primary m-1 nexttab" onclick="nexttab()" style='display:none;'>Next</button>
    </div>
</template>