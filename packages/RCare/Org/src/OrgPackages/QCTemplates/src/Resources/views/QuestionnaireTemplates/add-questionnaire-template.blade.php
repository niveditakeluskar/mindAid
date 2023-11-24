@extends('Theme::layouts.master')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.css">
<style>
    .disabled {
    pointer-events: none;
    cursor: default;
    display: none;
}
.badtag {
  border: solid 1px red !important;
  background-color: #d24a4a !important;
  color: white !important;
}

.badtag a {
  color: #ad2b2b !important;
}
.badtag span{
    background:none!important;
}
    </style>
@section('main-content')
    <div class="breadcrusmb">
        <div class="row">
            <div class="col-md-10">
                <h4 class="card-title mb-3">Add Questionnaire Template</h4>
            </div>
            <div class="col-md-1">
                <?php 
                    $module_name = \Request::segment(1);
                    if($module_name == 'rpm') {
                ?>
                        <a class="btn btn-success btn-sm" href="{{ route("rpm-questionnaire-template") }}" >Questionnaire Template</a>
                <?php
                    } else if($module_name == 'ccm') {
                ?>
                        <a class="btn btn-success btn-sm " href="{{ route("ccm-questionnaire-template") }}" >Questionnaire Template</a>
                <?php  
                    } else if($module_name == 'patients') {
                ?>
                        <a class="btn btn-success btn-sm " href="{{ route("patients-questionnaire-template") }}" >Questionnaire Template</a>
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
                    <!-- <form class="" method="post" action="{{-- route('save-template') --}}">  -->
                    <form class="" method="post" action="{{-- route('save-template') --}}" name="saveccmtemplate-form" id="saveccmtemplate-form"> 
                    <!-- <form action="{{ route("save-template") }}" method="post" name="add_ccm_content_template_form"> -->
                        {{ csrf_field() }}
                        @hidden("add",["id"=>"add", "value"=>"add"])
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label class="">Content Title <span class="error">*</span></label>
                                 
                                    @text("content_title", ["id"=>"content_title"])
                                  </div>  
                                <div class="col-md-6 form-group mb-3">
                                    <label class="">Content Type <span class="error">*</span></label>
                               
                                    <!--@selectContentType("template_type",["id"=>"template_type", "class"=>"col-md-6"])-->
                                    <select name="template_type" id="template_type" class="custom-select">
                                        <option value="5">Questionnaire</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                   </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="loginuser" class="">Module <span class="error">*</span></label>
                                     
                                    @selectMasterModule("module",["id"=>"module", "disabled"=>"disabled"])
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="loginuser" class="">Sub Module <span class="error">*</span></label>
                                   
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
                                    <input id="add_to_patient_status" name="add_to_patient_status" type="checkbox" value="1" class="custom-control-input">
                                    <label for="add_to_patient_status" class="custom-control-label">Add To Patients Status</label>
                                </div>
                            </div>
                            <div class="col-md-2 form-group mt-2">
                                <div class="custom-control custom-checkbox">
                                    <input id="one_time_entry" name="one_time_entry" type="checkbox" value="1" class="custom-control-input">
                                    <label for="one_time_entry" class="custom-control-label">One Time Entry</label>
                                </div>
                            </div>
                            <div class="wrapMulDrop col-md-2">
                                <button type="button" id="multiDrop" class="multiDrop form-control col-md-12">Select month<i style="float:right;" class="icon ion-android-arrow-dropdown"></i></button>
                                    <ul>    
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[January]"  type="checkbox"><span class="">Jan</span><span class="checkmark"></span>            
                                            </label>
                                        </li>  
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[February]"  type="checkbox"><span class="">Feb</span><span class="checkmark"></span>             
                                            </label>
                                        </li> 
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[March]"  type="checkbox"><span class="">Mar</span><span class="checkmark"></span>             
                                            </label>
                                        </li> 
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[April]"  type="checkbox"><span class="">Apr</span><span class="checkmark"></span>             
                                            </label>
                                        </li> 
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[May]"  type="checkbox"><span class="">May</span><span class="checkmark"></span>             
                                            </label>
                                        </li> 
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[June]"  type="checkbox"><span class="">Jun</span><span class="checkmark"></span>             
                                            </label>
                                        </li> 
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[July]"  type="checkbox"><span class="">Jul</span><span class="checkmark"></span>             
                                            </label>
                                        </li> 
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[August]"  type="checkbox"><span class="">Aug</span><span class="checkmark"></span>             
                                            </label>
                                        </li> 
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[September]"  type="checkbox"><span class="">Sep</span><span class="checkmark"></span>             
                                            </label>
                                        </li> 
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[October]"  type="checkbox"><span class="">Oct</span><span class="checkmark"></span>             
                                            </label>
                                        </li> 
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[November]"  type="checkbox"><span class="">Nov</span><span class="checkmark"></span>             
                                            </label>
                                        </li> 
                                        <li>
                                            <label class="forms-element checkbox checkbox-outline-primary"> 
                                                <input class="" name ="months[December]"  type="checkbox"><span class="">Dec</span><span class="checkmark"></span>             
                                            </label>
                                        </li> 
                                    </ul>  
                            </div> 
                            <div class="col-md-2 form-group mt-2">
                                <div class="custom-control custom-checkbox">
                                    <input id="add_score" name="add_score" type="checkbox" value="1" class="custom-control-input">
                                    <label for="add_score" class="custom-control-label">Score</label>
                                </div>
                            </div>
                            <label class="col-md-1 mt-2" >Sequence</label>
                            <input type="number" class="form-control col-md-2" name="sequence" id="sequence"> 
            
                            <!--<div class="col-md-6 form-group mt-2 team-invite">
                                <label for="tags" class="">Enter Tags</label>
                                <input type="text" id='tags' name='tags' placeholder='enter tags here' size="30">
                                <br>
                                <p id='subtext'>You can enter multiple tags with comma</p>
                            </div>-->
                            </div>
                            
                        </div>
                        <div class="separator-breadcrumb border-top"></div>
                        <div class="form-group">  
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="copy_from" class="">Copy Questionnaire Template From </label>
                                    <select class="custom-select select2" name="copy_from" id="copy_from"  >

                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div id="question_content">
                        <div class="question_div" id="first_question_div">
                            <div class="form-group" id = "question">
                                <div class="row">
                                <input type="hidden" value="1" id="counter">
                                <input type="hidden" value="0" id="subcounter">
                                    <label class="col-md-2">Question 1 <span class="error">*</span></label>
                                    <div class="col-md-4 mb-3">
                                    @text("question", ["name" => "question[q][1][questionTitle]", "placeholder" => "Enter Question", "required", "class" => "form-control"])
                                  
                                </div>
                                </div>   
                            </div>

                            <div class="form-group" id="dropdown">
                                <div class="row" style="margin-top: -20px;">
                                    <div class="col-md-2">
                                        <label>Answer Format <span class="error">*</span></label>
                                    </div>
                                     <div class="col-md-4">
                                    <!-- @selectanswerformat("question[q][1][answerFormat]",["id" => "answerFormat", "class" => "form-control custom-select col-md-3"]) -->
                                    <select class="custom-select" name="question[q][1][answerFormat]" id="answerFormat" onchange="getChild(this)" required > 
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
                            </div>

                            <div class="form-group" id="labels">
                                <div class="row labels_container" id="labels_container_1">
                                            <label class="col-md-2">Response Content </label>
                                            <div class="col-md-1"><i id="add_1" type="button"qno="1" onclick="addLabels(this);" class="add i-Add" style="color: #2cb8ea;  font-size: 25px;"></i></div>
                                            <div class="new-text-div col-md-9"></div>
                                        <!-- @text("label", ["id" => "label", "placeholder" => "Enter Label", "class" => "form-control col-md-3"]) -->
                                
                                </div>   
                            </div>
                            <div class="separator-breadcrumb border-top"></div>
                        </div>
                        <div class="abc" id="append_div"></div>
                </div>
                        <div class="col-md-4 mb-3"><button type="button" class="btn btn-success btn-sm " id="addQuestion" style="">Add Question</button></div>
                        <button type="" id="save-template" class="btn btn-info btn-lg btn-block">Save</button> 
                    </form>
                </div>
            </div>
        </div>
    </div>             
@endsection
@section('page-js')


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <script>
        $( init );

        function init() {
        $( ".abc" ).sortable({
            connectWith: ".appendedQuestion",
            stack: '.appendedQuestion div'
            }).disableSelection();
        }
       $(document).ready(function(){        
        var module_id    = '{{ getPageModuleName() }}';
        $("#module").val(module_id);
       
        // $('#add_score').click(function(){
        //     if($(this).is(':checked')){
        //        $('.tx').hide();
        //     } else {
        //         $('.tx').show();
        //     }
        // });
        util.updateSubModuleList(parseInt(module_id), $("#sub_module"));
        util.getToDoListData(0, {{getPageModuleName()}});
        //util.getToDoListCalendarData(0, {{getPageModuleName()}});
        $('#save-template').click(function(e){
            var pathArray = window.location.pathname.split('/');
            var url = window.location.protocol + "//" + window.location.host + "/" + pathArray[1] + "/save-qtemplate";
            var module_id    = '{{ getPageModuleName() }}';
            var sub_module = $("#sub_module").val();
            if(sub_module=="Select Sub Module"){
                sub_module="";
            }
            var data = $('#saveccmtemplate-form').serialize()+"&module="+module_id+"&sub_module="+sub_module;
            // console.log("data"+data);
            $.ajax({
                type: 'post',
                url: url,
                data: data, // getting filed value in serialize form
                success: function(response){
                    if($.trim(response) == 'success') {
                        $(".alert").removeClass('alert-danger');
                        $(".is-invalid").hide();
                        $("input").removeClass("is-invalid");
                        $("select").removeClass("is-invalid");
                        $('.invalid-feedback').html('');
                        $(".alert").addClass('alert-success');
                        $(".user-msg").text("Template data added successfully!");
                        $(".alert").show();
                        document.getElementById("saveccmtemplate-form").reset();
                        var url = $(location).attr('href');
                        var secondLevelLocation = url.split('/').reverse()[1];
                        if(secondLevelLocation == 'rpm') {
                            var redirect_url = "{{ route('rpm-questionnaire-template') }}";  
                            window.location = redirect_url;
                        } else if(secondLevelLocation == 'ccm'){
                            var redirect_url = "{{ route('ccm-questionnaire-template') }}"; 
                            window.location = redirect_url;
                        } 
                    }
                },
                error: function (request, status, error) {
                    if(request.responseJSON.errors !== undefined) {
                        if(request.responseJSON.errors.content_title) {
                            $('[name="content_title"]').addClass("is-invalid");
                            $('[name="content_title"]').next(".invalid-feedback").html(request.responseJSON.errors.content_title);
                        } else {
                            $('[name="content_title"]').removeClass("is-invalid");
                            $('[name="content_title"]').next(".invalid-feedback").html('');
                        }

                        if(request.responseJSON.errors.template_type) {
                            $('[name="template_type"]').addClass("is-invalid");
                            $('[name="template_type"]').next(".invalid-feedback").html(request.responseJSON.errors.template_type);
                        } else {
                            $('[name="template_type"]').removeClass("is-invalid");
                            $('[name="template_type"]').next(".invalid-feedback").html('');
                        }

                        if(request.responseJSON.errors.module) {
                            $('[name="module"]').addClass("is-invalid");
                            $('[name="module"]').next(".invalid-feedback").html(request.responseJSON.errors.module);
                        } else {
                            $('[name="module"]').removeClass("is-invalid");
                            $('[name="module"]').next(".invalid-feedback").html('');
                        }

                        // if(request.responseJSON.errors.stages) {
                        //     $('[name="stages"]').addClass("is-invalid");
                        //     $('[name="stages"]').next(".invalid-feedback").html(request.responseJSON.errors.stages);
                        // } else {
                        //     $('[name="stages"]').removeClass("is-invalid");
                        //     $('[name="stages"]').next(".invalid-feedback").html('');
                        // }

                        if(request.responseJSON.errors.sub_module) {
                            $('[name="sub_module"]').addClass("is-invalid");
                            $('[name="sub_module"]').next(".invalid-feedback").html(request.responseJSON.errors.sub_module);
                        } else {
                            $('[name="sub_module"]').removeClass("is-invalid");
                            $('[name="sub_module"]').next(".invalid-feedback").html('');
                        }

                        //dynamic fields validation
                        for(var i=1; i<= $("#counter").val(); i++){
                            var element_id = 'question.q.'+i+'.questionTitle';
                            if(request.responseJSON.errors.hasOwnProperty(element_id)) {
                                $('[name="question[q]['+i+'][questionTitle]"]').addClass("is-invalid");
                                $('[name="question[q]['+i+'][questionTitle]"]').next(".invalid-feedback").html("The question title field is required.");
                            } else {
                                $('[name="question[q]['+i+'][questionTitle]"]').removeClass("is-invalid");
                                $('[name="question[q]['+i+'][questionTitle]"]').next(".invalid-feedback").html('');
                            }
                        }

                        for(var j=1; j<= $("#counter").val(); j++){
                            var element_id = 'question.q.'+j+'.answerFormat';
                            if(request.responseJSON.errors.hasOwnProperty(element_id)) {
                                $('[name="question[q]['+j+'][answerFormat]"]').addClass("is-invalid");
                                $('[name="question[q]['+j+'][answerFormat]"]').next(".invalid-feedback").html("The answer format field is required.");
                            } else {
                                $('[name="question[q]['+j+'][answerFormat]"]').removeClass("is-invalid");
                                $('[name="question[q]['+j+'][answerFormat]"]').next(".invalid-feedback").html('');
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

        $("[name='module']").on("change", function () {
            util.updateSubModuleList(parseInt($(this).val()), $("#sub_module"));
        });

        $("[name='sub_module']").on("change", function () {
            util.updateStageList(parseInt($(this).val()), $("#stages"));
            util.updateTemplateLists(module_id, parseInt($(this).val()), $("#copy_from"), 5);
        });

        $("[name='stages']").on("change", function () {
            util.updateStageCodeList(parseInt($(this).val()), $("#stage_code"));
        });

        $("[name='copy_from']").on("change", function () {
        var templateid = parseInt($(this).val());
        var templatetype = $("#template_type").val();
        $('#question_content').html('');
        var url = '/ccm/render-template';
        $.ajax({
					url: url, 
					type: 'POST',
					data:{"_token": "{{ csrf_token() }}", id:templateid, type:templatetype},
					dataType:"JSON",
					success : function(response) {
                        $('#question_content').append(response.html);
					}
				});
    });

        $(document).on('click', 'button.queRemove', function( e ) {
            e.preventDefault();
            $(this).parents( 'div.appendedQuestion' ).remove();
            $(this).parents( 'div.question_div' ).remove();
        });

        $(document).on('click', 'i.remove', function( e ) {
            e.preventDefault();
            $(this).parents( 'div.rm' ).remove();
        });

        $('#addQuestion').on('click', function( e ) {
            var pathArray = window.location.pathname.split('/');
            var url = window.location.protocol + "//" + window.location.host + "/" + pathArray[1] + "/dynamic-template-question";
            $.ajax({
                url: url,
                success: function (data) {  
                    template = $(data);
                    var count=parseInt($('#counter').val()) + 1;
                    $('#counter').val(count);
                    //template.find('.appendedQuestion').attr('id', 'appendedQuestion_'+count);           
                    template.find('#question').attr('name', "question[q]["+count+"][questionTitle]");
                    template.find('#question').attr('id', 'question_'+count);
                    template.find('#answerFormat').attr('name', "question[q]["+count+"][answerFormat]");
                    template.find('#answerFormat').attr('id', 'answerFormat_'+count);
                    template.find('#addQue').attr('qno', count);
                    template.find('.labels_container').attr('id', 'labels_container_'+count);
                    template.find('.add').attr('id', 'add'+count);
                    template.find('.add').attr('id', 'add'+count);
                    template.find('#questionCounter').text(count);
                    $("#append_div").append('<div class="appendedQuestion question_div" id="question_div'+count+'"></div>')
                    $("#question_div"+count).append(template);
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
        $('#tags').tagsInput({
  'width': 'auto',
  'delimiter': ',',
  'defaultText': 'Enter Tag',
  onAddTag: function(item) {
    $($(".tagsinput").get(0)).find(".tag").each(function() {
      if (!ValidateEmail($(this).text().trim().split(/(\s+)/)[0])) {
        $(this).addClass("badtag");
      }
    });
  },
  'onChange': function(item) {
		$($(".tagsinput").get(0)).find(".tag").each(function() {
      if (!ValidateEmail($(this).text().trim().split(/(\s+)/)[0])) {
        $(this).addClass("badtag");
      }
    });
  }

});
    });

    function ValidateEmail(mail) {
  if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
    return (true)
  }
  return (false)
}


    function addLabels(obj){   

        var parentId = $(obj).parents(".labels_container").attr("id");
        var question_no = $(obj).attr("qno");
       
        var count=parseInt($('#counter').val());
        var sc = '';
        if($('#add_score').is(":checked")){
            sc ='<input type="textbox" name="question[q]['+ question_no + '][score][]" style="margin-left:10px" placeholder="score"  class="form-control col-md-1 lab"/> ';
        }
        $("#"+parentId).children(".new-text-div").append( $('<div class="row form-group rm"><input type="textbox" name="question[q]['+ question_no + '][label][]" id="label" class="form-control col-md-4 lab"/>'+sc+'<i type="button" class="remove col-md-1 offset-md-1 i-Remove" style="color: #f44336;  font-size: 22px;"></i></div>'));
        var otherValue = $("#other_amount").val();
        $('.lab').attr('id', 'label'+count);
    }

    function removeQuestion(obj){  
        var div = document.getElementById('question_div');
        if (div) {
            div.parentNode.removeChild(div);
        }
    }

    function getChild(obj){
        var parentId = $(obj).parents(".question_div").attr("id");
        var labelParent = $('#'+parentId).find('.labels_container').attr("id");
        var disabledAddLabel = $('#'+labelParent).find('.add').attr('id');
        var name = (obj.name).replace('[answerFormat]','[score][]');
        var name_label = (obj.name).replace('[answerFormat]','[placeholder][]');
        if(obj.value == 2 || obj.value == 5){
            $("#"+disabledAddLabel).addClass("disabled");
            var sc = '';
            if($('#add_score').is(":checked")){
                sc = '<input type="textbox" name="'+ name + '" id="score" style="margin-left:10px" value="0" class="form-control col-md-1  mt-2"/>';
            }
            $(obj).after('<div class="row form-group sco" style="margin-left: 0px"><input type="text" name="'+name_label+'" class="form-control col-md-6 mt-2"  placeholder="placeholder">'+sc+'</div>    ');
            
        }else{
            $("#"+disabledAddLabel).removeClass("disabled");
            $(obj).next('.sco').remove();
            //alert($(obj).closest('.sco').attr('name'));
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