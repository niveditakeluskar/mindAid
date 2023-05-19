<!-- <form name="monthlyservice"> -->
<div class="form-group" id="out_of_guidelines" style="display:none">
    <div class="row  mb-4">
        <div class="col-md-12 mb-4 ">    
            <div class="card">
                <div class="card-body card text-left">
                <!-- <div class="col-md-8 text-center" id="questionnaireButtons" style="display:none; margin-top: 22px;" [formGroup]="radioGroup"> -->
                        
                            <div class="row" id="office_range_div">
                                    <?php
                                    $num = 1;
                                    $queData = json_decode($questionSet1['question']);  
                                    //print_r($questionSet1);
                                   
                                    $module_id = $questionSet1->module_id;
                                    $component_id  = $questionSet1->component_id;
                                    $stage_id  = $questionSet1->stage_id;
                                    $template_id = $questionSet1->template_id;

                                    $questionnaire = $queData->question->q;     
                                    // foreach($questionnaire as $value)         
                                    ?>
                                    <!-- <input type="hidden" name="module_id" id="module_id" value="{{--$questionSet1->module_id--}}"> -->
                                    <input type="hidden" name="template_type" id="template_type" value="{{$questionSet1->template_type}}">
                                    <input type="hidden" name="template_type_id" id="template_type_id" value="{{$questionSet1->template_type_id}}">
                                    <input type="hidden" name="template_id" id="template_id" value="{{$questionSet1->template_id}}">
                                    <!-- <input type="hidden" name="component_id" id="component_id" value="{{--$questionSet1->component_id--}}"> -->
                                    <input type="hidden" name="stage_id" id="stage_id" value="{{$questionSet1->stage_id}}">
                                    @foreach($questionnaire as $value)
                                    @php $field_name = str_replace(' ','_', $value->questionTitle) @endphp
                                    <div class="col-md-12 form-group form-row">
                                        <div class=" mr-4" id="from_{{ $num }}">
                                            <strong class="mr-1">{{ $num }} :</strong>	                           
                                            {{$value->questionTitle}}
                                        </div>
                                        <div class="" id="sub_{{ $num }}">
                                            <div class="row">
                                            @foreach($value->label as $labels)
                                                <span>
                                                    @if($value->answerFormat == 1)
                                                        <select  name="office_range_questionnaire[{{ $field_name }}]" class="form-control">
                                                            <option value="{{$labels}}">{{$labels}}</option>
                                                        </select>
                                                    @elseif($value->answerFormat == 2)
                                                        <input type="text" name="office_range_questionnaire[{{ $field_name }}]" value="{{$labels}}" class="form-control">
                                                    @elseif($value->answerFormat == 3)
                                                        <label class="radio radio-primary mr-4">
                                                            <input type="radio" name="office_range_questionnaire[{{ $field_name }}]" value="{{$labels}}" formControlName="radio" class="form-control">
                                                            <span>{{$labels}}</span>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    @elseif($value->answerFormat == 4)
                                                        <label class="checkbox checkbox-outline-primary blood">
                                                            <input type="checkbox" class="checkbox" name="office_range_questionnaire[{{ $field_name }}]" target-off="" value="{{$labels}}"> 
                                                            <span>{{$labels}}</span>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    @elseif($value->answerFormat == 5)
                                                        <textarea type="text" name="office_range_questionnaire[{{ $field_name }}]" class="form-control">{{$labels}}</textarea>
                                                    @endif
                                                </span>
                                            @endforeach
                                            </div>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <?php $num++ ?>
                                    @endforeach
                                <!-- <div class="row">
                                    <button type="button" class="btn  btn-primary m-1 summarize-details">Save</button>
                                    <! -- <button type="button" class="btn  btn-primary m-1 saveMonthlyService">Save</button> - ->
                                </div> -->
                            </div>
                            <div class="col-md-12 row" id="emergency_range_div">
                                    <?php
                                        $num = 1;
                                        $queData = json_decode($questionSet2['question']);     
                                        $questionnaire = $queData->question->q;     
                                        // foreach($questionnaire as $value)         
                                    ?>
                                    @foreach($questionnaire as $value)
                                    @php $field_name = str_replace(' ','_', $value->questionTitle) @endphp
                                    <div class="col-md-12 form-group row">
                                        <div class="mr-4" id="from_{{ $num }}">
                                            <strong class="mr-1">{{ $num }} :</strong>	                           
                                            {{$value->questionTitle}}
                                        </div>
                                        <div id="sub_{{ $num }}">
                                            <div class="row">
                                                @foreach($value->label as $labels)
                                                    <span>
                                                    @if($value->answerFormat == 1)
                                                        <select  name="emergency_range_questionnaire[{{ $field_name }}]" class="form-control">
                                                            <option value="{{$labels}}">{{$labels}}</option>
                                                        </select>
                                                    @elseif($value->answerFormat == 2)
                                                        <input name="emergency_range_questionnaire[{{ $field_name }}]" type="text" value="{{$labels}}" class="form-control">
                                                    @elseif($value->answerFormat == 3)
                                                        <label class="radio radio-primary mr-4">
                                                            <input type="radio" name="emergency_range_questionnaire[{{ $field_name }}]" value="{{$labels}}" formControlName="radio" class="form-control">
                                                            <span>{{$labels}}</span>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    @elseif($value->answerFormat == 4)
                                                        <label class="checkbox checkbox-outline-primary blood">
                                                            <input type="checkbox" class="checkbox" name="emergency_range_questionnaire[{{ $field_name }}]" target-off="" value="{{$labels}}"> 
                                                            <span>{{$labels}}</span>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    @elseif($value->answerFormat == 5)
                                                        <textarea type="text" name="emergency_range_questionnaire[{{ $field_name }}]" class="form-control">{{$labels}}</textarea>
                                                    @endif
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <?php $num++ ?>
                                    @endforeach  

                                    <div class="col-md-12 row" >
                                        <label class="">Record Episode Details :</label>
                                        <textarea name="record_episode_details" class="form-control" id="record_episode_details"></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div> 
                                <!-- <div class="row">
                                    <button type="button" class="btn  btn-primary m-1 summarize-details">Save</button>
                                    <!- - <button type="button" class="btn  btn-primary m-1 saveMonthlyService">Save</button> -- >
                                </div> -->
                            </div>

                            <div id="record_details" style="margin-top: 33px; margin-left: 50px; display:none;">
                                <div class="row" >
                                        <label class="col-md-2 offset-md-4">Record Episode Details :</label>
                                        <textarea class="col-md-6 form-control"></textarea>
                                </div> 
                                <div class="row">
                                    <div class="col-lg-12 text-right mt-3">
                                        <button type="button" class="btn  btn-primary m-1" >dgstryse</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                <!-- </div> -->
                <div class="card-footer">
                <div class="mc-footer">
                    <div class="row">
                        <div class="col-lg-12 text-right">
                            <!-- <button id="" type="button" class="btn  btn-primary m-1 saveMonthlyService" >Save</button> -->
                            <button type="button" class="btn btn-primary m-1 summarize-details">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>                                 
    </div>
</div>
<!-- </form> -->