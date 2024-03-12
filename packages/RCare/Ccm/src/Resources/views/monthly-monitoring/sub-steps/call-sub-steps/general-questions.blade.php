<?php
$module_id = getPageModuleName();
$submodule_id = getPageSubModuleName();
$stage_id = getFormStageId($module_id, $submodule_id, 'General Question');


if($enrollCount > 1){
	$stage_id = getFormStageId($ccmModule, $ccmSubModule, 'General Question');
	$dmodule_id = $ccmModule;
	$dsubmodule_id = $ccmSubModule;
}else{
	$stage_id = $stage_id;
	$dmodule_id = $module_id;
	$dsubmodule_id = $submodule_id;
}
$off = 1;
foreach ($genQuestion as $key => $value) {
	$editGq = json_decode($value['template']);
	//$javaObj = json_encode($editGq);
	$javaObj = str_replace("'", "&#39;",json_encode($editGq));

	?>
	<button type="button" id="RenderGeneralQuestion{{$key}}" onclick='checkQuestion(<?php echo $javaObj; ?>,"<?php echo $off ?>","<?php echo $value["stage_code"]; ?>")' style="display: none"></button>
	<?php
	$off++;
}
function renderTree($treeObj, $lab, $val, $tree_key, $answarFormet, $seq, $tempid)
{
	$optCount = count((array) $treeObj);
	$javaObj = json_encode($treeObj);
						//echo $javaObj;
						//echo "lab".$lab;
	$i = 1;
	for ($i = 1; $i <= 25; $i++) {   
		if(property_exists($treeObj,$i)){
			$id = $val . '' . $i;
			$label_str = str_replace("[q]", "", $lab);
			$label = $label_str . "[opt][" . $i . "][val]";
							//echo $javaObj;
			$jobj =  str_replace("''", "&apos;",$javaObj);
			$jobj =  str_replace("'", "&apos;",$jobj);

							//echo $treeObj->$i->val;
			$treeobjval =  str_replace("'", "&#39;",$treeObj->$i->val);
							//echo "treeobj".$treeobjval;
							//$treeobjval = htmlspecialchars($treeObj->$i->val, ENT_QUOTES, 'UTF-8');

			if($answarFormet == '1'){
			?>
			<label class="checkbox  checkbox-primary mr-3">
				<input type="checkbox" name="{{$label}}" id="<?php echo $id . '_' . $tree_key.'_'.$i; ?>" value="<?php echo ($treeobjval ? $treeobjval : '' ); ?>" onchange='ajaxRenderTree(<?php echo $jobj; ?>,"<?php echo $label; ?>","<?php echo $id; ?>","<?php echo $i; ?>","<?php echo $tree_key; ?>",<?php echo $seq; ?>,<?php echo $tempid; ?>)'>
				<span><?php echo ($treeObj->$i->val ? $treeObj->$i->val : '' ); ?></span>
				<span class="checkmark"></span>
			</label>
			<?php }else if($answarFormet == '2' || $answarFormet == '5'){ ?>
				<input type="text" class='form-control col-md-5' name="{{$label}}" id="<?php echo $id . '_' . $tree_key.'_'.$i; ?>" value="" onkeyup='ajaxRenderTree(<?php echo $jobj; ?>,"<?php echo $label; ?>","<?php echo $id; ?>","<?php echo $i; ?>","<?php echo $tree_key; ?>",<?php echo $seq; ?>,<?php echo $tempid; ?>)'>
				<input type="hidden" class='form-control col-md-5 firsttbox'  style="display:none" onclick='ajaxRenderTree(<?php echo $jobj; ?>,"<?php echo $label; ?>","<?php echo $id; ?>","<?php echo $i; ?>","<?php echo $tree_key; ?>",<?php echo $seq; ?>,<?php echo $tempid; ?>)'>
			<?php }else{ ?>
			<label class="radio radio-primary mr-3">
				<input type="radio" name="{{$label}}" id="<?php echo $id . '_' . $tree_key.'_'.$i; ?>" value="<?php echo ($treeobjval ? $treeobjval : '' ); ?>" onchange='ajaxRenderTree(<?php echo $jobj; ?>,"<?php echo $label; ?>","<?php echo $id; ?>","<?php echo $i; ?>","<?php echo $tree_key; ?>",<?php echo $seq; ?>,<?php echo $tempid; ?>)'>
				<span><?php echo ($treeObj->$i->val ? $treeObj->$i->val : '' ); ?></span>
				<span class="checkmark"></span>

			</label>
			<?php 	
			}
		}
	}
}?>
<div class="row" style="margin-bottom:5px;">
<div class="col-lg-12 mb-3">
@selectGQ("genquestionselection",$dmodule_id,$stage_id,["id" => "genquestionselection", "class" => "mb-3 select2 capital-first" ])	
</div>
</div>
<div class="alert alert-success general_success" id="success-alert" style="display: none;">
	<button type="button" class="close" data-dismiss="alert">x</button>
	<strong> General question data saved successfully! </strong><span id="text"></span>
</div>
<?php 
$last_key = 0;

foreach($stepWiseDecisionTree as $skey => $stepvalue){ ?>
<form name="general_question_form_{{$skey}}" id="general_question_form_{{$skey}}" style="display:none">
	@csrf
	<input type="hidden" name="uid" value="{{$patient_id}}">	
	<input type="hidden" name="start_time" value="00:00:00">
	<input type="hidden" name="end_time" value="00:00:00">

	
	<div class="row">
		<div class="col-lg-12 mb-3">
			<div class="card">
				<div class="card-body">
					<div class="card-title"><?php foreach($dtsteps as $val){ if($skey == $val['id']){ echo $val['description'];} } ?></div>				
					<input type="hidden" name="uid" value="{{$patient_id}}">
					<input type="hidden" name="patient_id" value="{{$patient_id}}">
					<input type="hidden" name="m_id" value="{{$dmodule_id}}">
					<input type="hidden" name="c_id" value="{{$dsubmodule_id}}">
					<?php
					//echo $last_key;
					foreach ($stepvalue as $key => $value) { 
						$months = json_decode($value['display_months']);
						$queData = json_decode($value['question']);

						if(empty($months)){
							$months = array("All");
						}
							
						if (in_array(date('F'), $months) || in_array("All", $months)){
							
						?>
							<input type="hidden" name="module_id[{{$last_key}}]" value="{{getPageModuleName()}}">
							<input type="hidden" name="component_id[{{$last_key}}]" value="{{getPageSubModuleName()}}">
							
							<input type="hidden"  id ="stage_id" name="stage_id[{{$last_key}}]" value="{{$value['stage_id']}}">
							<input type="hidden" name="stage_code[{{$last_key}}]" value="{{$value['stage_code']}}">
							<input type="hidden" name="step_id" value="{{$value['stage_code']}}">
							<input type="hidden" name="form_name" value="general_question_form">
						<?php if($value['template_type_id'] == 6){ ?>
							<input type="hidden" name="template_id[{{$last_key}}]" value="{{$value['id']}}">
						<div class="mb-4 radioVal" id="{{$last_key}}general_question11">
							
							<?php $que_val = trim(preg_replace('/\s+/', ' ', $queData->question->qs->q)); ?>
							<label for="are-you-in-pain" class="col-md-12"><input type="hidden" name="DT{{$last_key}}[qs][q]" value="<?php echo $que_val; ?>"><?php echo $queData->question->qs->q; ?></label>
							<input type="hidden" name="sq[{{$value['id']}}][0]" value="0">
							<div class="d-inline-flex mb-2 col-md-12">
								<?php
								if(property_exists($queData->question->qs, 'opt')){ 
									renderTree($queData->question->qs->opt, 'DT' . $last_key . '[qs][q]', '1', $last_key, $queData->question->qs->AF, 0, $value['id']); 
								}
								?>
								
							</div>
							<p class="message" style="color:red"></p>
						</div>
						<div id="question{{$last_key}}"></div>
						<div id="in-pain">
							<label for="" class="mr-3">Current Monthly Notes:</label>
							<input type="hidden" name="monthly_topic[{{$last_key}}]" value="{{$value['content_title']}} Related Monthly Notes">	 
							<textarea class="form-control" placeholder="Monthly Notes" name="monthly_notes[{{$last_key}}]"><?php foreach($genQuestion as $key => $val){if($val->template_id == $value['id']){echo $val->monthly_notes;}}?></textarea>
							<p class="txtmsg" style="color:red"></p>
						</div>

						<hr>
						<?php 
						$last_key++;

					 }}} $last_key = $last_key; 	getRelationshipQ($patient_id,$skey,$dmodule_id,$dsubmodule_id,$stage_id)  ?>
				</div>
				<div class="card-footer"> 
					<div class="mc-footer"> 
						<div class="row">
							<div class="col-lg-12 text-right">
								<button type="button" class="btn  btn-primary m-1 office-visit-save" id="generalQue{{$skey}}" onclick="saveGeneralQuestions(<?php echo $skey; ?>)">Save</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div> 
</form>
<?php } ?>
<div class="alert alert-success general_success" id="success-alert" style="display: none;">
	<button type="button" class="close" data-dismiss="alert">x</button>
	<strong> General question data saved successfully! </strong><span id="text"></span>
</div>
@selectGQ("genquestionselection",$dmodule_id,$stage_id,["id" => "genquestionselection1", "class" => "mb-3 bottom select2"])						
<div style="padding-left: 20px; color:red; font-size:13px;"><b>Select additional applicable questions</b></div>
<button type="button" class="btn  btn-primary m-1 nexttab" onclick="nexttab()" style='display:none;'>Next</button>