<?php
                    $number = 1;
                    $queData = json_decode($data['question']);
                    ?>
                    @if($number==1) <!--i class="i-Add col-md-2" id="addQuestion" style="color: #2cb8ea;  font-size: 22px;margin-left: 635px"></i-->@endif
                    <?php
                    // if($data->template_type_id == 5) {  
                    $questionnaire = $queData->question->q;
                    ?>
                    @foreach($questionnaire as $value)
                    <div class="question_div" id="question_div{{$number}}">
                        <div class="form-group" id="question">
                            <div class="row">
                                <label class="col-md-2">Question {{$number}} : <span class="error">*</span></label>
                                <input type="text" class="form-control col-md-4" name="question[q][<?php echo $number; ?>][questionTitle]" required value="<?php (isset($value->questionTitle) && ($value->questionTitle != '')) ? print($value->questionTitle) : ''; ?>">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <?php
                       
                        $exist = 0;
                        if(isset($value->score)){ 
                            $exist = 1;
                            $score = $value->score;
                        } 
                        if (isset($value->answerFormat)) {
                        ?>
                            <div class="form-group" id="dropdown">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Answer Format <span class="error">*</span></label>
                                    </div>
                                    <select class="custom-select col-md-4" name="question[q][<?php echo $number; ?>][answerFormat]" id="answerFormat" onchange="getChild(this);" >
                                        <option value="" <?php if (!isset($value->answerFormat) && $value->answerFormat == null) {
                                                                echo 'selected';
                                                            } ?>>Select Answer Format</option>
                                        <option value="1" <?php if (isset($value->answerFormat) && ($value->answerFormat == 1)) {
                                                                echo 'selected';
                                                            } ?>>Dropdown</option>
                                        <option value="2" <?php if (isset($value->answerFormat) && ($value->answerFormat == 2)) {
                                                                echo 'selected';
                                                            } ?>>Textbox</option>
                                        <option value="3" <?php if (isset($value->answerFormat) && ($value->answerFormat == 3)) {
                                                                echo 'selected';
                                                            } ?>>Radio</option>
                                        <option value="4" <?php if (isset($value->answerFormat) && ($value->answerFormat == 4)) {
                                                                echo 'selected';
                                                            } ?>>Checkbox</option>
                                        <option value="5" <?php if (isset($value->answerFormat) && ($value->answerFormat == 5)) {
                                                                echo 'selected';
                                                            } ?>>Textarea</option>
                                    </select>
                                    <?php if($exist == 1 && ((isset($value->answerFormat) && ($value->answerFormat == 2)) || (isset($value->answerFormat) && ($value->answerFormat == 5)))){?>
                                        <input type="textbox" required name="question[q][<?php echo $number; ?>][score][]"  
                                        class="form-control col-md-1 sco" style= "margin-left:10px" value="<?php echo $value->score[0]; ?>" /> 
                                    <?php } ?>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="form-group" id="dropdown">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Answer Format</label>
                                    </div>
                                    <select class="custom-select col-md-4" name="question[q][<?php echo $number; ?>][answerFormat]" onchange="getChild(this);" id="answerFormat">
                                        <option value="">Select Answer Format</option>
                                        <option value="1">Dropdown</option>
                                        <option value="2" class="tx">Textbox</option>
                                        <option value="3">Radio</option>
                                        <option value="4">Checkbox</option>
                                        <option value="5" class="tx">Textarea</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>

                        <div class="form-group" id="labels">
                            <div class="row labels_container" id="labels_container_{{$number}}">
                                <label class="col-md-2">Response Content : </label>
                                <div class="col-md-1 "> <i id="add{{$number}}" type="button" qno="{{$number}}" onclick="addLabels(this);" class="add i-Add <?php if ($value->answerFormat == 2 || $value->answerFormat == 5) {
                                                                                                                                                                echo 'disabled';
                                                                                                                                                            } ?>" style="color: #2cb8ea;  font-size: 22px;"></i></div>
                                <div class="new-text-div col-md-9">
                                    
                                    @if(isset($value->label))
                                    <?php $k = 0; ?>
                                    @foreach($value->label as $labels)
                                    <div class="row form-group rm">
                                        <input type="textbox" required name="question[q][<?php echo $number; ?>][label][]" id="label" class="form-control col-md-4" value="<?php echo $labels; ?>" /><?php if($exist == 1){?><input type="textbox" required name="question[q][<?php echo $number; ?>][score][]"  style="margin-left:10px" class="form-control col-md-1" value="<?php echo $score[$k]; ?>" /> <?php } ?><i class="remove col-md-1 offset-md-1 i-Remove" style="color: #f44336;  font-size: 22px;"></i> <br>
                                    </div>
                                    <?php $k++; ?>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                            @if($number!=1)
                        <div class="mt-2">
                            <button type="button" class="btn btn-danger btn-sm queRemove" id="removeButton" style="">Remove Question</button>
                         </div> 
                        @endif
                        </div>
                        
                        <?php $number++; ?>
                        <div class="separator-breadcrumb border-top"></div>
                    </div>
                    @endforeach
            
            <?php
            // } 
            ?>
            <div class="abc" id="append_div"></div>
            <input type="hidden" value="{{$number-1}}" id="counter">
            <br>
            
           