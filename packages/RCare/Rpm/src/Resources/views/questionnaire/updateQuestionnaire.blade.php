@extends('Theme::layouts.master')
@section('page-css')
<script src="https://cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>
@endsection
@section('main-content')
<div class="breadcrusmb">
   <div class="row">
      <div class="col-md-10">
         <h4 class="card-title mb-3">Add Questions Template</h4>
      </div>
      <div class="col-md-1">
         <!-- <a class="btn btn-success btn-sm " href="listQuestionnaire" >Questionnaire Template</a>   -->
      </div>
   </div>
   <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row mb-4">
<div class="col-md-12 mb-4">
   <div class="card text-left">
      <div class="card-body">
         <form class="" method="post" action="{{ route('save-rpm-questionnaire') }}" name="rpm-questionnaire-edit" id="rpm-questionnaire-edit">
            @csrf
            <input type="hidden" name="question_id" value="{{ $data->id }}">
            <div class="form-group">
               <div class="row">
                    <div class="col-md-2"><label>Content Name</label></div>
                    <input type="text" class="form-control col-md-3" name="content_title" required value="{{ $data->content_title }}">
                    <div class="col-md-2 offset-md-1"><label>Content Type</label></div>

                    <select class="custom-select col-md-3" name="template_type" id="template_type" required>
                         <option value="{{ 5 }}">Questionnaire</option>
                    </select>
               </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label class="col-md-2">Sub Modules</label>
                    <select class="custom-select col-md-3" name="sub_module" id="sub_module" required>
                         @foreach($sub_service as $key)
                            <option value="{{ $key->id }}" <?php if($data->component_id == $key->id){ echo 'selected'; } ?>>{{ $key->components }}</option>
                         @endforeach
                    </select>
                     <label class="col-md-2 offset-md-1" id="stage_label" >Stages</label>
                        <select class="custom-select col-md-3" name="stages" id="stages" required >
                            <option value="0" selected>Choose One...</option>
                            @foreach($stage as $key)
                            <option value="{{ $key->id }}" <?php if($data->stage_id == $key->id){ echo 'selected'; } ?>>{{ $key->description }}</option>
                            @endforeach
                        </select>
                 </div>  
            </div>        
			<?php
                           $queData = json_decode($data['question']);     
                           $questionnaire = $queData->question->q;     
                           ?> 
                            <?php $number=1; ?>
                            @foreach($queData->question->q as $value)
            <hr>
            <div class="question_div">
                <div class="form-group" id = "question">
                    <div class="row">
                        
                            <label class="col-md-2">Question {{$number}} : </label>
                            <input type="text" class="form-control col-md-3" name="question[q][<?php echo $number; ?>][questionTitle]" required value="{{$value->questionTitle}}">
                        @if($number==1) <i class="i-Add col-md-2" id="addQuestion" style="color: #2cb8ea;  font-size: 22px;"></i>@endif
                    </div>
                </div>

                <?php 
                    if(isset($value->answerFormat)) {
                ?>
                    <div class="form-group" id="dropdown">
                        <div class="row">
                            <div class="col-md-2">
                            <label>Answer Format</label>
                            </div>
                            <select class="custom-select col-md-3" name="question[q][<?php echo $number; ?>][answerFormat]" id="answerFormat">
                            <option value="0"<?php if (!isset($value->answerFormat) && $value->answerFormat==null){echo 'selected';} ?>>Select Answer Format</option>
                            <option value="1"<?php if (isset($value->answerFormat) && ($value->answerFormat==1)){echo 'selected';} ?>>Dropdown</option>
                            <option value="2"<?php if (isset($value->answerFormat) && ($value->answerFormat==2)){echo 'selected';} ?>>Textbox</option>
                            <option value="3"<?php if (isset($value->answerFormat) && ($value->answerFormat==3)){echo 'selected';} ?>>Radio</option>
                            <option value="4"<?php if (isset($value->answerFormat) && ($value->answerFormat==4)){echo 'selected';} ?>>Checkbox</option>
                            <option value="5"<?php if (isset($value->answerFormat) && ($value->answerFormat==5)){echo 'selected';} ?>>Textarea</option>
                            </select>
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
                            <select class="custom-select col-md-3" name="question[q][<?php echo $number; ?>][answerFormat]" id="answerFormat">
                                <option value="0">Select Answer Format</option>
                                <option value="1">Dropdown</option>
                                <option value="2">Textbox</option>
                                <option value="3">Radio</option>
                                <option value="4">Checkbox</option>
                                <option value="5">Textarea</option>
                            </select>
                        </div>
                    </div>
                <?php
                    }
                ?>

                <div class="form-group" id="labels">
                    <div class="row labels_container" id="labels_container_{{$number}}">
                        <label class="col-md-1">Label : </label>
                        <div class="col-md-1 "> <i id="add{{$number}}" type="button" qno="{{$number}}" onclick="addLabels(this);" class="add i-Add" style="color: #2cb8ea;  font-size: 22px;"></i></div>
                        <div class="new-text-div col-md-10">
                        @if(isset($value->label))
                            @foreach($value->label as $labels)
                            <div class="row form-group rm">
                                <input type="textbox" required name="question[q][<?php echo $number; ?>][label][]" id="label" class="form-control col-md-4" value="<?php echo $labels; ?>"/><i class="remove col-md-1 offset-md-1 i-Remove" style="color: #f44336;  font-size: 22px;"></i> <br>                    
                            </div>
                            @endforeach
                        @endif
                        
						</div>
                    
                    </div>
                </div>
                @if($number!=1) 
                    <div>
                        <i class="queRemove i-Remove" style="color: #f44336;  font-size: 22px;" id="removeButton" ></i><br>
                    </div> 
                @endif
                  <?php $number++; ?>
                  
			</div>	  
			@endforeach
                  <input type="hidden" value="{{$number-1}}" id="counter">

               <div class="abc" id="append_div">
               </div>
               <div class="card-footer">
                        <div class="mc-footer">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" class="btn  btn-primary m-1" name="edit">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
               <!-- <div class="row">
                  <div class="col-md-2">
                     <button type="submit" name="edit" class="btn btn-success btn-sm">Update</button>
                  </div>
               </div> -->
               <!-- <h1>Content</h1>
                  <textarea name="content" id="editor" cols="30" rows="10">
                      &lt;p&gt;This is some sample content.&lt;/p&gt;
                  </textarea>
                  <br>
                  <button type="submit" class="btn btn-info btn-lg btn-block">Save</button>  -->
         </form>
         </div>
      </div>
   </div>
</div>
@endsection
@section('page-js')
<script>
   // CKEDITOR.replace( 'editor');
</script>
<script>

   function get_subservice(val){
       $.ajax({
           type: 'post',
           url: '/rpm/SaveTemplate',
           data: {
               _token: '{!! csrf_token() !!}',
               service:val
           },
           success: function (response) {
               document.getElementById("sub_module").innerHTML=response; 
           }
       });
   }
   function get_stages(val){
        $.ajax({
            type: 'post',
            url: '/rpm/SaveTemplate',
            data: {
                _token: '{!! csrf_token() !!}',
                stages_id:val
            },
            success: function (response) {
                document.getElementById("stages").innerHTML=response; 
            }
        });
    }

   
   function addLabels(obj){   
       // alert(obj);
          // e.preventDefault();
          var parentId = $(obj).parents(".labels_container").attr("id");
          var question_no = $(obj).attr("qno");
       //    alert(parentId);
          // $('<div/>').addClass( 'new-text-div' )
          var count=parseInt($('#counter').val());
           $("#"+parentId).children(".new-text-div").append( $('<div class="row form-group rm"><input type="textbox" name="question[q]['+ question_no + '][label][]" id="label" class="form-control col-md-4 lab"/> <i type="button" class="remove col-md-1 offset-md-1 i-Remove" style="color: #f44336;  font-size: 22px;"></i></div>'));
           var otherValue = $("#other_amount").val();
           $('.lab').attr('id', 'label'+count);
           // count++;
        }
   
   function removeQuestion(obj){   
   
       // e.preventDefault();
   
       var div = document.getElementById('question_div');
       if (div) {
           div.parentNode.removeChild(div);
       }
   }
   
   
   // $(function() {
   //   //  $('.add').on('click', function( e ) {
        
   
   //     // });
   
   // });
   
   $(document).on('click', 'i.queRemove', function( e ) {
           e.preventDefault();
           $(this).parents( 'div.appendedQuestion' ).remove();
           $(this).parents( 'div.question_div' ).remove();
       });

    $(document).on('click', 'i.remove', function( e ) {
        e.preventDefault();
        $(this).parents( 'div.rm' ).remove();
    });
   // var count = 2;
   // $(function() {
   //     $('#addQuestion').on('click', function(){
   //     $('<div/>').addClass( 'autoIncrement' )
   //     $( "#append_div" ).append('<div><label>Question '+ count + ' :</label></div>  @text("question", ["id" => "question", "placeholder" => "Enter Question", "class" => "form-control col-md-3"])').addClass( 'form-group' )
   //     .append( $("#dropdown").html())
   //     .append( $("#labels").html())  
   //     .append( $('<button type="button"/></br></br>').addClass( 'removeQuestion' ).text( 'Remove' ) )
   //     count++;
   // });
   // $(document).on('click', 'button.removeQuestion', function() {
   //         $(this).closest( 'div.autoIncrement' ).remove();
   //     });
   // });
   $('#addQuestion').on('click', function( e ) {
       $.ajax({
       url: "/rpm/get-form",
       success: function (data) {  
           template = $(data);
           var count=parseInt($('#counter').val()) + 1;
           $('#counter').val(count);
           template.find('.appendedQuestion').attr('id', 'appendedQuestion_'+count);           
           template.find('#question').attr('name', "question[q]["+count+"][questionTitle]");
           template.find('#question').attr('id', 'question_'+count);
           template.find('#answerFormat').attr('name', "question[q]["+count+"][answerFormat]");
           template.find('#answerFormat').attr('id', 'answerFormat_'+count);
           template.find('#addQue').attr('qno', count);
           // $('#label').attr('name', "question[q]["+count+"][label]");
           // $('#label').attr('id', 'label_'+count);
   
           template.find('.labels_container').attr('id', 'labels_container_'+count);
           template.find('.add').attr('id', 'add'+count);
           template.find('.add').attr('id', 'add'+count);
           template.find('#questionCounter').text(count);
           // template.find('#label').attr('value', ''+count);
           // alert((template).html());
           $("#append_div" ).append(template);
           $('#removeButton').show(); },
       dataType: 'html'
       });
       
   });
   
   // $('#removeButton').on('click', function( ) {
   //     $(this).closest( 'div.abc' ).remove();
   // });
   
</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
    <script type="text/javascript">
        if ($("#rpm-questionnaire-edit").length > 0) {
            $("#rpm-questionnaire-add").validate({
                rules: {
                    content_title: {
                        required: true,
                    },
                    template_type: {
                        required: true,
                    },
                    module: {
                        required: true,
                    },
                    sub_module: {
                        required: true,
                    },
                    stages: {
                        required: true,
                    },
                    answerFormat: {
                        required: true
                    }
                },
                messages: {
                    content_title: {
                        required: "Please enter content title",
                    },
                    template_type: {
                        required: "Please enter template type",
                    },
                    module: {
                        required: "Please enter module",
                    },
                    sub_module: {
                        required: "Please enter sub module",
                    },
                    stages: {
                        required: "Please enter stage",
                    },
                    answerFormat: {
                        required: "Please enter answer format",
                    }
                },
            });
        }
    </script>
@endsection