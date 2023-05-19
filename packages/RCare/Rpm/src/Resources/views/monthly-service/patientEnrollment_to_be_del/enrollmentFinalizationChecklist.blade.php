<form name="step_4">
<div class="card-body">
    <?php
    $num = 1;
    $queData = json_decode($questionSet1['question']);     
    $questionnaire = $queData->question->q;
    $qObj = json_decode($questionSet1) ;     
    foreach($questionnaire as $value)         
      ?>
  <input type="text"   id="call_hidden_id_finalize" name="call_hidden_id">
  <input type="hidden" id="finilization_template_type_id" name="template_type_id" value="<?php echo $qObj->template_type_id; ?>"> 
  <input type="hidden" id="finilization_module_id" name="module_id" value="<?php echo $qObj->module_id; ?>">
  <input type="hidden" id="finilization_component_id" name="component_id" value="<?php echo $qObj->component_id; ?>">
  <input type="hidden" id="finilization_stage_id" name="stage_id" value="<?php echo $qObj->stage_id; ?>">
  <input type="hidden" id="finilization_template_id" name="template_id" value="<?php echo $qObj->id; ?>">
   @foreach($questionnaire as $value)
   <div class="form-row">
      <div class="form-group col-md-6" id="from">
         <strong class="mr-1"> {{ $num }} :</strong>                              
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
                  <!-- <input type="radio" name="radio{{ $num }}" [value]="" formControlName="radio"> -->
                  <span>{{$labels}}</span>
                  <span class="checkmark"></span>
              </label>
              @elseif($value->answerFormat == 4)<input type="checkbox" value="{{$labels}}" >
              @elseif($value->answerFormat == 2)
              <textarea type="text" value="{{$labels}}"></  </textarea>
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
                <button type="button" id="back" class="btn btn-secondary m-1" >Back</button>
                <button type="button" id="step_4" class="btn  btn-primary m-1">Finalize Enrollment</button>
            </div>
        </div>
    </div>
</div>

</form>
