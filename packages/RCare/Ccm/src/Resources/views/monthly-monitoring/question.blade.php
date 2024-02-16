<?php
					$off = 1;
					foreach ($genQuestion as $key => $value) {
						$editGq = json_decode($value['template']);
						$javaObj = json_encode($editGq);
					?>
						<button type="button" id="RenderGeneralQuestion{{$key}}" onclick='checkQuestion(<?php echo $javaObj; ?>,"<?php echo $off ?>")' style="display: none"></button>
						<?php
						$off++;
					}
					function renderTree($treeObj, $lab, $val, $tree_key)
					{
						$optCount = count((array) $treeObj);
						$javaObj = json_encode($treeObj);
						$i = 1;
						for ($i = 1; $i <= $optCount; $i++) {
							$id = $val . '' . $i;
							$label_str = str_replace("[q]", "", $lab);
							$label = $label_str . "[opt][" . $i . "][val]";
						?>
							<label class="radio radio-primary mr-3">
								<input type="radio" name="{{$label}}" id="<?php echo $id . '' . $tree_key.''.$i; ?>" value="<?php echo ($treeObj->$i->val ? $treeObj->$i->val : '' ); ?>" onchange='ajaxRenderTree(<?php echo $javaObj; ?>,"<?php echo $label; ?>","<?php echo $id; ?>","<?php echo $i; ?>","<?php echo $tree_key; ?>")'>
								<span><?php echo ($treeObj->$i->val ? $treeObj->$i->val : '' ); ?></span>
								<span class="checkmark"></span>
							</label>
						<?php	}
					}
					$last_key = 0;
					foreach ($decisionTree as $key => $value) {
						$queData = json_decode($value['question']);
						?>
						<div class="mb-4 radioVal" id="{{$key}}general_question11">
							<input type="hidden" name="module_id[{{$key}}]" value="{{$value['module_id']}}">
							<input type="hidden" name="component_id[{{$key}}]" value="{{$value['component_id']}}">
							<input type="hidden" name="template_id[{{$key}}]" value="{{$value['id']}}">
							<input type="hidden" name="stage_id[{{$key}}]" value="{{$value['stage_id']}}">
							<input type="hidden" name="stage_code[{{$key}}]" value="{{$value['stage_code']}}">
							<label for="are-you-in-pain" class="col-md-12"><input type="hidden" name="DT{{$key}}[qs][q]" value="<?php echo $queData->question->qs->q; ?>"><?php echo $queData->question->qs->q; ?><span class="error">*</span></label>
							<div class="d-inline-flex mb-2 col-md-12">
								<?php
								renderTree($queData->question->qs->opt, 'DT' . $key . '[qs][q]', '1', $key);
								?>
							</div>
							<p class="message" style="color:red"></p>
						</div>
						<div id="question{{$key}}"></div>
						<div id="in-pain">
							<label for="" class="mr-3">Current Monthly Notes:<span class="error">*</span></label>
							<textarea class="form-control" placeholder="Monthly Notes" name="monthly_notes[{{$key}}]"><?php foreach($genQuestion as $key => $val){if($val->template_id == $value['id']){echo $val->monthly_notes;}}?></textarea>
						</div>

						<hr>
					<?php 
} ?>

<script>
$(document).ready(function() {
		<?php
		$i = 0;
		foreach ($genQuestion as $values) {
			$editGq = json_decode($value['template']);		
			echo "$('#RenderGeneralQuestion$i').click();";	
			$i++;
		}		
		?>
		//$('#RenderGeneralQuestion0').click();
		//$('#RenderGeneralQuestion1').click();
		
	});
</script>
					