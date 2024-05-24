<div class="card">
    <div class="card-body" id="ecp">
        <?php
        $num = 1;
        $queData = json_decode($questionSet1['question']);
        $questionnaire = $queData->question->q;

        $qObj = json_decode($questionSet1);
        if (isset($questionnaire_template_usage_history) && $questionnaire_template_usage_history != null) {
            $submitted_questionnaire = $questionnaire_template_usage_history[0]->template;
            $submited_ans = json_decode($submitted_questionnaire, true);
        } else {
            $submitted_questionnaire = "";
            $submited_ans = array();
        }
        // var_dump($submitted_questionnaire) ;
        //var_dump($submited_ans);
        ?>
        <input type="hidden" id="call_hidden_id" name="call_hidden_id" value="">
        <input type="hidden" id="checklist_stop_hidden_time" name="checklist_stop_hidden_time">
        <input type="hidden" id="checklist_template_type_id" name="template_type_id" value="<?php echo $qObj->template_type_id; ?>">
        <input type="hidden" id="checklist_module_id" name="module_id" value="<?php echo $qObj->module_id; ?>">
        <input type="hidden" id="checklist_component_id" name="component_id" value="<?php echo $qObj->component_id; ?>">
        <input type="hidden" id="checklist_stage_id" name="stage_id" value="<?php echo $qObj->stage_id; ?>">
        <input type="hidden" id="checklist_template_id" name="template_id" value="<?php echo $qObj->id; ?>">

        @foreach($questionnaire as $value)
        <div class="form-row">
            <div class="form-group col-md-4" id="from">
                <strong class="mr-1">{{ $num }} :</strong>
                <?php
                $key_exist = 0;
                $nq = str_replace(' ', '_', $value->questionTitle);
                $nq1 = "'" . $nq . "'";
                if (array_key_exists($nq1, $submited_ans)) {
                    $key_exist = 1;
                } else {
                    $key_exist = 0;
                }
                ?>
                {{$value->questionTitle}}
                <p class="text-muted m-0"></p>
            </div>
            <div class="form-group col-md-5" id="sub">
                <div class="row">
                    @foreach($value->label as $labels)
                    @if($value->answerFormat == 1)
                    <select>
                        <option value="{{$labels}}">{{$labels}}</option>
                    </select>
                    @elseif($value->answerFormat == 2)<input type="text" value="{{$labels}}">
                    @elseif($value->answerFormat == 3)
                    <label class="radio radio-primary col-md-4">
                        <input type="radio" name="questionnaire['{{str_replace(' ','_', trim($value->questionTitle)) }}']" id="questionnaire{{$num}}" value="{{$labels}}" formControlName="radio" onchange="chabgeStepSix('<?php echo $labels; ?>','<?php echo 'questionnaire' . $num; ?>');" <?php if ($key_exist == 1 && $submited_ans[$nq1] == $labels) {
                                                                                                                                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                                                                                                                                } ?>>
                        <span>{{$labels}}</span>
                        <span class="checkmark"></span>
                        <span style="display:none; color:red" id="checkError{{$num}}">Requrired</span>
                    </label>
                    @elseif($value->answerFormat == 4)<input type="checkbox" value="{{$labels}}">
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
                  <button type="button" class="btn btn-secondary m-1" id="back_3"  onclick="backStep(3)"> Back</button>
                  <button type="button" class="btn  btn-primary m-1" id="step_3"> Save</button>
              </div>
          </div>
      </div>
  </div>
  </div>