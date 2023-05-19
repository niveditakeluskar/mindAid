<div class="card">
<div class="card-body" id="fap">
    <?php
    $num = 1;
    $queData = json_decode($questionSet1['question']);     
    $questionnaire = $queData->question->q;     
    $qObj = json_decode($questionSet1) ;  
    if(isset($questionnaire_template_usage_history2) && $questionnaire_template_usage_history2 != null) {
        $submitted_questionnaire = $questionnaire_template_usage_history2[0]->template;
        $submited_ans = json_decode($submitted_questionnaire, true);
    } else {
        $submitted_questionnaire = "";
        $submited_ans = array();
    }
    //foreach($questionnaire as $value) 
   // var_dump($submited_ans) ;        
      ?>
      <input type="hidden"   id="call_hidden_id_finalize" name="call_hidden_id">
  <input type="hidden" id="finilization_template_type_id" name="template_type_id" value="<?php echo $qObj->template_type_id; ?>"> 
  <input type="hidden" id="finilization_module_id" name="module_id" value="<?php echo $qObj->module_id; ?>">
  <input type="hidden" id="finilization_component_id" name="component_id" value="<?php echo $qObj->component_id; ?>">
  <input type="hidden" id="finilization_stage_id" name="stage_id" value="<?php echo $qObj->stage_id; ?>">
  <input type="hidden" id="finilization_template_id" name="template_id" value="<?php echo $qObj->id; ?>">
   @foreach($questionnaire as $value)
   <div class="form-row">
      <div class="form-group col-md-4" id="from">
         <strong class="mr-1"> {{ $num }} :</strong>   
         <?php
            $key_exist = 0;
            $nq = str_replace(' ', '_', $value->questionTitle);
            $nq1 = "'".$nq."'";
            if(array_key_exists($nq1 , $submited_ans)) {
                $key_exist = 1;
            } else {
                $key_exist = 0;
            }
        ?>                           
         {{$value->questionTitle}}
         <p class="text-muted m-0"></p>
      </div>
        <div class="form-group col-md-5" id="sub1">
          <div class="row" >
              @foreach($value->label as $labels)
              <!-- <span class="col-md-12">
              <label>{{$labels}}</label> -->
              @if($value->answerFormat == 1)
              <select>
                  <option value="{{$labels}}">{{$labels}}</option>
              </select>
              @elseif($value->answerFormat == 2)<input type="text" value="{{$labels}}">
              @elseif($value->answerFormat == 3) 
              <label class="radio radio-primary col-md-4">
                 <input type="radio" name="Fquestionnaire['{{str_replace(' ','_', trim($value->questionTitle)) }}']"  value="{{$labels}}"  onchange="chabgeStepSix('<?php echo $labels; ?>','<?php echo 'Fquestionnaire'.$num; ?>');" <?php if($key_exist == 1 && $submited_ans[$nq1] == $labels) { echo "checked";}?> formControlName="radio">
                  <span>{{$labels}}</span>
                  <span class="checkmark"></span>
                  <span style="display:none; color:red" id="FcheckError{{$num}}">Requrired</span>
              </label>
              @elseif($value->answerFormat == 4)<input type="checkbox" value="{{$labels}}" >
              @elseif($value->answerFormat == 2)
              <textarea type="text" value="{{$labels}}"></textare>
              @endif
              </span>
              @endforeach
              <p class="text-muted m-0"></p>
              <p class="text-muted m-0"></p>
          </div>
        </div>
   </div>
   <?php $num++ ?>
   @endforeach
        
</div>
<div class="card-footer">
        <div class="mc-footer">
            <div class="row">
                <div class="col-lg-12 text-right">
                    <button type="button" id="back_4" class="btn btn-secondary m-1"  onclick="backStep(4)">Back</button>
                    <button type="button" id="step_4" class="btn  btn-primary m-1">Finalize Enrollment</button>
                </div>
            </div>
        </div>
    </div>
</div>    