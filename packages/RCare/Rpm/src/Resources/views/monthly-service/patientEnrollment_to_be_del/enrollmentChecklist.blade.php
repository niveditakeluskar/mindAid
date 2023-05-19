<form name="step_3">
<div class="card-body">
    <?php
    $num = 1;
    $queData = json_decode($questionSet1['question']);      
    $questionnaire = $queData->question->q; 
    //echo "<pre>";
    //print_r($questionnaire);   
     //die;
   // foreach($questionnaire as $value)       
    $qObj = json_decode($questionSet1) ;
  // print_r($qObj->id);
  
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
      <div class="form-group col-md-6" id="from">
         <strong class="mr-1">{{ $num }} :</strong>                              
         {{$value->questionTitle}}
         <p class="text-muted m-0"></p>
      </div>
     <div class="form-group col-md-6" id="sub">
        <div class="row">
            @foreach($value->label as $labels)
            <!-- <span class="col-md-12">
            <label>{{$labels}}</label> -->
            @if($value->answerFormat == 1)
            <select>
                <option value="{{$labels}}">{{$labels}}</option>
            </select>
            @elseif($value->answerFormat == 2)<input type="text" value="{{$labels}}">
            @elseif($value->answerFormat == 3) 
            <label class="radio radio-primary col-md-3">
                <input type="radio" name="questionnaire['{{str_replace(' ','_', trim($value->questionTitle)) }}']" value="{{$labels}}" formControlName="radio">
                <span>{{$labels}}</span>
                <span class="checkmark"></span>
            </label>
            @elseif($value->answerFormat == 4)<input type="checkbox" value="{{$labels}}" >
            @elseif($value->answerFormat == 2)
            <textarea type="text" value="{{$labels}}"></textarea>
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
  <div class="card-footer">
      <div class="mc-footer">
          <div class="row">
              <div class="col-lg-12 text-right">
                  <button type="button" class="btn btn-secondary m-1" id="back"> Back</button>
                  <button type="button" class="btn  btn-primary m-1" id="step_3"> Save</button>
              </div>
          </div>
      </div>
  </div>    
  
</div>
</form>

