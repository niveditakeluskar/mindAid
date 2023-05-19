<?php
	$module_id    = Route::input('module_id');
	$submodule_id = Route::input('submodule_id'); 
	$stage_id     = getFormStageId($module_id, $submodule_id, 'Review Data');
	$patient_id = Route::input('id');
	$billable = Route::input('billable');
	$content = Route::input('content');
	if(isset($content) && $content == 'services'){ 
?>		
		@include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.service')
<?php 
	} elseif(isset($content) && $content == 'allergies'){ 
?>
		@include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.allergy')
<?php 
	} elseif(isset($content) && $content == 'medications'){ 
?>	
		@include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.medications')
<?php 
	} elseif(isset($content) && $content == 'travel'){ 
?>
		@include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.travel')
<?php 
	} elseif(isset($content) && $content == 'hobbies'){ 
?>
		@include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.hobbies')
<?php 
	} elseif(isset($content) && $content == 'pets'){ 
?>
		@include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.pets')
<?php 
	} elseif(isset($content) && $content == 'diagnosis-code'){ 
?>
		@include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.codes-info-for-medical')
<?php 
	} elseif(isset($content) && $content == 'provider'){ 
?>
		@include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.provider')
<?php 
	} elseif(isset($content) && $content == 'grandchildren-info'){ 
?>
		@include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.grandchildren')
<?php 
	} elseif(isset($content) && $content == 'children-info'){ 
?>
		@include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.children')
<?php 
	} elseif(isset($content) && $content == 'sibiling-info'){ 
?>
		@include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.sibiling')
<?php 
	} elseif(isset($content) && $content == 'live-with-info'){ 
?>
		@include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.do-you-live-with-anyone')
<?php 
	} elseif(isset($content) && $content == 'family-info'){ 
?>
		@include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.family')
<?php 
	} 
?>