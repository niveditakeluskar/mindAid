@extends('Theme::layouts.master')
<style>
    .disabled {
        pointer-events: none;
        cursor: default;
        display: none;
    }
</style>
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-10">
            <h4 class="card-title mb-3">Update Questionnaire Template</h4>
        </div>
        <div class="col-md-1">
            <?php
            $module_name = \Request::segment(1);
            if ($module_name == 'rpm') {
            ?>
                <!-- <a class="btn btn-success btn-sm" href="{{ route("rpm-questionnaire-template") }}">Questionnaire Template</a> -->
                <button name="questionnaire_print" id="questionnaire-printpdf" class="btn btn-danger btn-lg btn-block">Print</button>
            <?php
            } else if ($module_name == 'ccm') {
            ?>
                <!-- <a class="btn btn-success btn-sm " href="{{ route("ccm-questionnaire-template") }}">Questionnaire Template</a> -->
                <button name="questionnaire_print" id="questionnaire-printpdf" class="btn btn-danger btn-lg btn-block">Print</button>
            <?php
            } else if ($module_name == 'patients') {
            ?>
                <!-- <a class="btn btn-success btn-sm " href="{{ route("patients-questionnaire-template") }}">Questionnaire Template</a> -->
                <button name="questionnaire_print" id="questionnaire-printpdf" class="btn btn-danger btn-lg btn-block">Print</button>
            <?php
            }
            ?>
        </div>
    </div>
    <!-- ss -->
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <div class="alert" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong class="user-msg"></strong><span id="text"></span>
                </div>
                <!--   <form class="" method="post" action="{{ route('save-template') }}"> -->
                <form class="" method="post" action="" id="updatetemplate-form" name="updatetemplate-form">
                    @csrf
                    @hidden("edit",["id"=>"edit", "value"=>"edit"])
                    @hidden("temp_id",["id"=>"temp_id", "value"=>"$data->id"])
                            <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="">Content Title <span class="error">*</span></label>
                           
                            @text("content_title", ["id"=>"content_title", "class"=>"custom-select", "value"=>"$data->content_title"])
                                  </div>
                            <div class="col-md-6 form-group mb-3">
                                <label class="">Content Type <span class="error">*</span></label>
                            
                            <!--@selectContentType("template_type",["id"=>"template_type", "class"=>"col-md-3"])-->
                            <select name="template_type" id="template_type" class="custom-select">
                                <option value="5">Questionnaire</option>
                            </select>
                            <div class="invalid-feedback"></div>
                              </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="loginuser" class="">Module <span class="error">*</span></label>
                            
                            @selectMasterModule("module",["id"=>"module","disabled"=>"disabled"])
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="sub_module" class="">Sub Module <span class="error">*</span></label>
                           
                            @select("Sub Module", "sub_module", [], ["id" => "sub_module", "class"=>"custom-select"])
                                </div>
                            <div class="col-md-6 form-group mb-3">
                                <label>Stage</label>
                           
                            @select("Stage", "stages", [], ["id" => "stages"])
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label>Step</label>
                           
                            @select("Step", "stage_code", [], ["id" => "stage_code", "class"=>"custom-select capital-first"])
                             </div>
                            <div class="col-md-2 form-group mt-2">
                                <div class="custom-control custom-checkbox">
                                    <input id="add_to_patient_status" name="add_to_patient_status" type="checkbox" value="1" class="custom-control-input" <?php if($data->add_to_patient_status == 1){echo 'checked';} ?>>
                                    <label for="add_to_patient_status" class="custom-control-label">Add To Patients Status</label>
                                </div>
                            </div>
                            <div class="col-md-2 form-group mt-2">
                                <div class="custom-control custom-checkbox">
                                    <input id="one_time_entry" name="one_time_entry" type="checkbox" value="1" class="custom-control-input" <?php if($data->one_time_entry == 1){echo 'checked';} ?>>
                                    <label for="one_time_entry" class="custom-control-label">One Time Entry</label>
                                </div>
                            </div>   
                            <?php $months = json_decode($data->display_months); ?>
                            <div class="wrapMulDrop col-md-2">
                                <button type="button" id="multiDrop" class="multiDrop form-control col-md-12">Select month<i style="float:right;" class="icon ion-android-arrow-dropdown"></i></button>
                                    <ul>    
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[January]"  type="checkbox" <?php if(!empty($months))if(in_array("January", $months)){echo "checked"; }?> ><span class="">Jan</span><span class="checkmark"></span>            
                                            </label>
                                        </li>  
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[February]"  type="checkbox" <?php if(!empty($months))if(in_array("February", $months)){echo "checked"; }?>><span class="">Feb</span><span class="checkmark"></span>             
                                            </label>
                                        </li> 
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[March]"  type="checkbox" <?php if(!empty($months))if(in_array("March", $months)){echo "checked"; }?>><span class="">Mar</span><span class="checkmark"></span>             
                                            </label>
                                        </li> 
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[April]"  type="checkbox" <?php if(!empty($months))if(in_array("April", $months)){echo "checked"; }?>><span class="">Apr</span><span class="checkmark"></span>             
                                            </label>
                                        </li> 
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[May]"  type="checkbox" <?php if(!empty($months))if(in_array("May", $months)){echo "checked"; }?>><span class="">May</span><span class="checkmark"></span>             
                                            </label>
                                        </li> 
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[June]"  type="checkbox" <?php if(!empty($months))if(in_array("June", $months)){echo "checked"; }?>><span class="">Jun</span><span class="checkmark"></span>             
                                            </label>
                                        </li> 
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[July]"  type="checkbox" <?php if(!empty($months))if(in_array("July", $months)){echo "checked"; }?>><span class="">Jul</span><span class="checkmark"></span>             
                                            </label>
                                        </li> 
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[August]"  type="checkbox" <?php if(!empty($months))if(in_array("August", $months)){echo "checked"; }?>><span class="">Aug</span><span class="checkmark"></span>             
                                            </label>
                                        </li> 
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[September]"  type="checkbox" <?php if(!empty($months))if(in_array("September", $months)){echo "checked"; }?>><span class="">Sep</span><span class="checkmark"></span>             
                                            </label>
                                        </li> 
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[October]"  type="checkbox" <?php if(!empty($months))if(in_array("October", $months)){echo "checked"; }?>><span class="">Oct</span><span class="checkmark"></span>             
                                            </label>
                                        </li> 
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[November]"  type="checkbox" <?php if(!empty($months))if(in_array("November", $months)){echo "checked"; }?>><span class="">Nov</span><span class="checkmark"></span>             
                                            </label>
                                        </li> 
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[December]"  type="checkbox" <?php if(!empty($months))if(in_array("December", $months)){echo "checked"; }?>><span class="">Dec</span><span class="checkmark"></span>             
                                            </label>
                                        </li> 
                                    </ul>  
                            </div>    
                            <div class="col-md-2 form-group mt-2">
                                <div class="custom-control custom-checkbox">
                                    <input id="add_score" name="add_score" type="checkbox" value="1" <?php if($data->score == 1){echo 'checked';} ?> class="custom-control-input">
                                    <label for="add_score" class="custom-control-label">Score</label>
                                </div>
                            </div>
                            <label class="col-md-1 mt-2" >Sequence</label>
                            <input type="number" class="form-control col-md-2" value="<?php echo $data->sequence ?>" name="sequence" id="sequence"> 
                        </div>
                    </div>
                    <div class="separator-breadcrumb border-top"></div>
                    <?php
                    $number = 1;
                    $queData = json_decode($data['question']);
                    ?>
                    @if($number==1) <!--i class="i-Add col-md-2" id="addQuestion" style="color: #2cb8ea;  font-size: 22px;margin-left: 635px"></i-->@endif
                    <?php
                    // if($data->template_type_id == 5) {  
                    $questionnaire = $queData->question->q;
                    ?>
                <div class="abc" id="append_div">
                    @foreach($questionnaire as $value)
                    <div class="appendedQuestion question_div" id="question_div{{$number}}">
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
                                    
                                    <div class="invalid-feedback"></div>
                                </div>
                                <?php if($exist == 1 && ((isset($value->answerFormat) && ($value->answerFormat == 2)) || (isset($value->answerFormat) && ($value->answerFormat == 5)))){?>
                                    <div class="row sco">
                                            <input type="text" name="question[q][<?php echo $number; ?>][placeholder][]" class="form-control offset-md-2 col-md-4 mt-2"  placeholder="placeholder" value="<?php if (isset($value->placeholder)){ echo $value->placeholder[0]; }?>" > 
                                            <?php if($exist == 1){?>
                                                <input type="textbox" required name="question[q][<?php echo $number; ?>][score][]"  
                                                class="form-control col-md-1 mt-2" style= "margin-left:10px" value="<?php echo $value->score[0]; ?>" /> 
                                            <?php } ?>
                                    </div>
                                <?php } ?>
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
                </div>
            
            <?php
            // } 
            ?>
            
            <input type="hidden" value="{{$number-1}}" id="counter">
            <br>
            <div class="col-md-4 mb-3"><button type="button" class="btn btn-success btn-sm " id="addQuestion" style="">Add Question</button></div>
            <button name="edit" id="update" class="btn btn-info btn-lg btn-block">Update</button>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
@section('page-js')
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script>
     $( init );
    function init() {
    $( ".abc" ).sortable({
        connectWith: ".appendedQuestion",
        stack: '.appendedQuestion div'
        }).disableSelection();
    }

    $(document).ready(function() {
        var module_id = {{$data->module_id}};
        var submodule_id = {{$data->component_id}};
        var stage_id = {{($data->stage_id != "") ? $data->stage_id: 0}};
        var stage_code = {{($data->stage_code != "") ? $data->stage_code: 0}}
        var template_type = {{$data->template_type_id}};
        //$('#template_type').val(template_type);
        $('#module').val(module_id);
        // $('#add_score').click(function(){
        //     if($(this).is(':checked')){
        //        $('.tx').hide();
        //     } else {
        //         $('.tx').show();
        //     }
        // });
        util.updateSubModuleList(parseInt(module_id), $("#sub_module"), parseInt(submodule_id));
        util.updateStageList(parseInt(submodule_id), $("#stages"), parseInt(stage_id));
        util.updateStageCodeList(parseInt(stage_id), $("#stage_code"), parseInt(stage_code));
        util.getToDoListData(0, {{getPageModuleName()}});
       // util.getToDoListCalendarData(0, {{getPageModuleName()}});
        
        $("[name='module']").on("change", function() {
            util.updateSubModuleList(parseInt($(this).val()), $("#sub_module"));
        });

        $("[name='sub_module']").on("change", function() {
            util.updateStageList(parseInt($(this).val()), $("#stages"));
        });

        $("[name='stages']").on("change", function() {
            util.updateStageCodeList(parseInt($(this).val()), $("#stage_code"));
        });

        
        // pdf print 
        $('#questionnaire-printpdf').click(function(){
            var id=$('#temp_id').val();
            var url = window.location.pathname;
            var secondLevelLocation = url.split('/').reverse()[2];
            if(secondLevelLocation == 'rpm') {
                window.open('/rpm/print-questionnaire-template/'+id, '_blank');
            } else if(secondLevelLocation == 'ccm'){
                window.open('/ccm/print-questionnaire-template/'+id, '_blank');
            } else if(secondLevelLocation == 'patients'){
                window.open('/patients/print-questionnaire-template/'+id, '_blank');
            }
            // window.open('/ccm/print-content-template/'+id, '_blank');
        });

        $('#update').click(function() {
            var pathArray = window.location.pathname.split('/');
            var url = window.location.protocol + "//" + window.location.host + "/" + pathArray[1] + "/save-qtemplate";
            var module_id = '{{$data->module_id}}';
            var sub_module = $("#sub_module").val();
            if (sub_module == "Select Sub Module") {
                sub_module = "";
            }
            var data = $('#updatetemplate-form').serialize() + "&module=" + module_id + "&sub_module=" + sub_module;
            // console.log("data"+data);
            $.ajax({
                type: 'post',
                url: url,
                data: data, // getting filed value in serialize form
                success: function(response) {
                    $(".alert").removeClass('alert-danger');
                    console.log(response);
                    if ($.trim(response) == 'success') {
                        $("input").removeClass("is-invalid");
                        $("select").removeClass("is-invalid");
                        $('.invalid-feedback').html('');
                        $(".alert").addClass('alert-success');
                        $(".user-msg").text("Template data updated successfully!");
                        $(".alert").show();
                        var url = $(location).attr('href');
                        var secondLevelLocation = url.split('/').reverse()[2];
                        console.log("secondLevelLocation" + secondLevelLocation);
                        if (secondLevelLocation == 'rpm') {
                            var redirect_url = "{{ route('rpm-questionnaire-template') }}";
                            window.location = redirect_url;
                        } else if (secondLevelLocation == 'ccm') {
                            var redirect_url = "{{ route('ccm-questionnaire-template') }}";
                            window.location = redirect_url;
                        }
                    }
                    // document.getElementById("updatetemplate-form").reset();
                },
                error: function(request, status, error) {
                    $(".alert").removeClass('alert-success');
                    console.log(request.responseJSON.errors);
                    if (request.responseJSON.errors !== undefined) {
                        if (request.responseJSON.errors.content_title) {
                            $('[name="content_title"]').addClass("is-invalid");
                            $('[name="content_title"]').next(".invalid-feedback").html(request.responseJSON.errors.content_title);
                        } else {
                            $('[name="content_title"]').removeClass("is-invalid");
                            $('[name="content_title"]').next(".invalid-feedback").html('');
                        }

                        if (request.responseJSON.errors.template_type) {
                            $('[name="template_type"]').addClass("is-invalid");
                            $('[name="template_type"]').next(".invalid-feedback").html(request.responseJSON.errors.template_type);
                        } else {
                            $('[name="template_type"]').removeClass("is-invalid");
                            $('[name="template_type"]').next(".invalid-feedback").html('');
                        }

                        if (request.responseJSON.errors.module) {
                            $('[name="module"]').addClass("is-invalid");
                            $('[name="module"]').next(".invalid-feedback").html(request.responseJSON.errors.module);
                        } else {
                            $('[name="module"]').removeClass("is-invalid");
                            $('[name="module"]').next(".invalid-feedback").html('');
                        }

                        if (request.responseJSON.errors.stages) {
                            $('[name="stages"]').addClass("is-invalid");
                            $('[name="stages"]').next(".invalid-feedback").html(request.responseJSON.errors.stages);
                        } else {
                            $('[name="stages"]').removeClass("is-invalid");
                            $('[name="stages"]').next(".invalid-feedback").html('');
                        }

                        if (request.responseJSON.errors.from) {
                            $('[name="from"]').addClass("is-invalid");
                            $('[name="from"]').next(".invalid-feedback").html(request.responseJSON.errors.from);
                        } else {
                            $('[name="from"]').removeClass("is-invalid");
                            $('[name="from"]').next(".invalid-feedback").html('');
                        }

                        if (request.responseJSON.errors.subject) {
                            $('[name="subject"]').addClass("is-invalid");
                            $('[name="subject"]').next(".invalid-feedback").html(request.responseJSON.errors.subject);
                        } else {
                            $('[name="subject"]').removeClass("is-invalid");
                            $('[name="subject"]').next(".invalid-feedback").html('');
                        }

                        if (request.responseJSON.errors.sub_module) {
                            $('[name="sub_module"]').addClass("is-invalid");
                            $('[name="sub_module"]').next(".invalid-feedback").html(request.responseJSON.errors.sub_module);
                        } else {
                            $('[name="sub_module"]').removeClass("is-invalid");
                            $('[name="sub_module"]').next(".invalid-feedback").html('');
                        }

                        //dynamic fields validation
                        //dynamic fields validation
                        for (var i = 1; i <= $("#counter").val(); i++) {
                            var element_id = 'question.q.' + i + '.questionTitle';
                            if (request.responseJSON.errors.hasOwnProperty(element_id)) {
                                $('[name="question[q][' + i + '][questionTitle]"]').addClass("is-invalid");
                                $('[name="question[q][' + i + '][questionTitle]"]').next(".invalid-feedback").html("The question title field is required.");
                            } else {
                                $('[name="question[q][' + i + '][questionTitle]"]').removeClass("is-invalid");
                                $('[name="question[q][' + i + '][questionTitle]"]').next(".invalid-feedback").html('');
                            }
                        }

                        for (var j = 1; j <= $("#counter").val(); j++) {
                            var element_id = 'question.q.' + j + '.answerFormat';
                            if (request.responseJSON.errors.hasOwnProperty(element_id)) {
                                $('[name="question[q][' + j + '][answerFormat]"]').addClass("is-invalid");
                                $('[name="question[q][' + j + '][answerFormat]"]').next(".invalid-feedback").html("The answer format field is required.");
                            } else {
                                $('[name="question[q][' + j + '][answerFormat]"]').removeClass("is-invalid");
                                $('[name="question[q][' + j + '][answerFormat]"]').next(".invalid-feedback").html('');
                            }
                        }

                        $(".alert").addClass('alert-danger');
                        $(".user-msg").text("Please fill mandatory fields!");
                        $(".alert").show();
                    }
                }
            });
            // to prevent refreshing the whole page page
            return false;
        });

        $(document).on('click', 'button.queRemove, i.queRemove', function(e) {
            e.preventDefault();
            $(this).parents('div.appendedQuestion').remove();
            $(this).parents('div.question_div').remove();
        });

        $(document).on('click', 'i.remove', function(e) {
            e.preventDefault();
            $(this).parents('div.rm').remove();
        });

        $('#addQuestion').on('click', function(e) {
            var pathArray = window.location.pathname.split('/');
            var url = window.location.protocol + "//" + window.location.host + "/" + pathArray[1] + "/dynamic-template-question";
            $.ajax({
                url: url,
                success: function(data) {
                    template = $(data);
                    var count = parseInt($('#counter').val()) + 1;
                    $('#counter').val(count);
                    //template.find('.appendedQuestion').attr('id', 'appendedQuestion_'+count);           
                    template.find('#question').attr('name', "question[q][" + count + "][questionTitle]");
                    template.find('#question').attr('id', 'question_' + count);
                    template.find('#answerFormat').attr('name', "question[q][" + count + "][answerFormat]");
                    template.find('#answerFormat').attr('id', 'answerFormat_' + count);
                    template.find('#addQue').attr('qno', count);
                    template.find('.labels_container').attr('id', 'labels_container_' + count);
                    template.find('.add').attr('id', 'add' + count);
                    template.find('.add').attr('id', 'add' + count);
                    template.find('#questionCounter').text(count);
                    $("#append_div").append('<div class="appendedQuestion question_div" id="question_div' + count + '"></div>')
                    $("#question_div" + count).append(template);
                    $('#removeButton').show();
                    // if($("#add_score").is(':checked')){
                    //     $('.tx').hide();
                    // } else {
                    //     $('.tx').show();
                    // }
                },
                dataType: 'html'
            });
        });
    });

    function addLabels(obj) {
        var parentId = $(obj).parents(".labels_container").attr("id");
        var question_no = $(obj).attr("qno");
        var count = parseInt($('#counter').val());
        var sc = '';
        if($('#add_score').is(":checked")){
            sc ='<input type="textbox" name="question[q]['+ question_no + '][score][]" style="margin-left:10px" placeholder="score"  class="form-control col-md-1 lab"/> ';
        }
        $("#" + parentId).children(".new-text-div").append($('<div class="row form-group rm"><input type="textbox" name="question[q][' + question_no + '][label][]" id="label" class="form-control col-md-4 lab"/>'+sc+'<i type="button" class="remove col-md-1 offset-md-1 i-Remove" style="color: #f44336;  font-size: 22px;"></i></div>'));
        var otherValue = $("#other_amount").val();
        $('.lab').attr('id', 'label' + count);
    }

    function removeQuestion(obj) {
        var div = document.getElementById('question_div');
        if (div) {
            div.parentNode.removeChild(div);
        }
    }

    function getChild(obj) {
        var parentId = $(obj).parents(".question_div").attr("id");
        var labelParent = $('#' + parentId).find('.labels_container').attr("id");
        var disabledAddLabel = $('#' + labelParent).find('.add').attr('id');
        var name = (obj.name).replace('[answerFormat]','[score][]');
        var name_label = (obj.name).replace('[answerFormat]','[placeholder][]');
        if (obj.value == 2 || obj.value == 5) {
            $("#" + disabledAddLabel).addClass("disabled");
            var sc = '';
            if($('#add_score').is(":checked")){
                sc = '<input type="textbox" name="'+ name + '" id="score" style="margin-left:10px" value="0" class="form-control col-md-1  mt-2"/>';
            }
            $(obj).after('<div class="row form-group sco" style="margin-left: 0px"><input type="text" name="'+name_label+'" class="form-control col-md-6 mt-2"  placeholder="placeholder">'+sc+'</div>    ');
        } else {
            $("#" + disabledAddLabel).removeClass("disabled");
            $(obj).next('.sco').remove();
        }

    }

     //Multiple Dropdown Select
     $('.multiDrop').on('click', function (event) { 
        event.stopPropagation();
        $(this).next('ul').slideToggle();
    });

    $(document).on('click', function () {
        if (!$(event.target).closest('.wrapMulDrop').length) {
            $('.wrapMulDrop ul').slideUp();
            // $('#drpdwn_task_notes').hide(); //hide notes 
        }
    }); 
    
    $('.wrapMulDrop ul li input[type="checkbox"]').on('change', function () {
        var x = $('.wrapMulDrop ul li input[type="checkbox"]:checked').length;
        // alert(x);
        if (x != "") {
            $('.multiDrop').html(x + " " + "selected");
        } else if (x < 1) {
            $('.multiDrop').html('Select Month<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
        }
    });
</script>
@endsection