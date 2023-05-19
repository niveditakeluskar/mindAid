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
                <!-- <div class="col-md-1">
                 <a class="float-right" href="listQuestionnaire" ><i class="add-icons i-Blinklist" data-toggle="tooltip" data-placement="top" title="Questionnaire Template"></i>&nbsp;<i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Questionnaire Template"></i></a>  
                </div>  -->
              </div>
   <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
          
                <form class="" method="post" action="{{ route('SaveQuestionnaire') }}"> 
                    @csrf
                    <div class="form-group">
                            <div class="row"><div class="col-md-2"><label>Content Name</label> </div>
                            <input type="text" class="form-control col-md-3" name="content_title">
                            <div class="col-md-2 offset-md-1"><label>Content Type</label></div>
                            <select class="custom-select col-md-3" name="template_type" id="template_type">
                            
                                    <option value="{{ 5 }}">Questionnaire</option>
                    
                            </select>
                            </div>
                    </div>    
                      <div class="form-group">
                        <div class="row"><label class="col-md-2">Modules</label>
                        <select class="custom-select col-md-3" name="module"  onchange="get_subservice(this.value);">
                            <option selected>Choose One...</option>
                            @foreach($service as $value)
                            <option value="{{ $value->id }}">{{ $value->service_name }}</option>
                            @endforeach
                        </select>
                        <label class="col-md-2 offset-md-1">Sub Modules</label>
                        
                        <select class="custom-select col-md-3" name="sub_module" id="sub_module" onchange="get_stages(this.value);">
                             <option selected>Choose One...</option>
                        @foreach($subModule as $value)
                        <option value="{{ $value->id }}">{{ $value->sub_services }}</option>
                        @endforeach
                        </select></div>
                    </div>
                    <input type="hidden" name="stage_code" value="0">
                     <div class="form-group">
                        <div class="row">
                        <label class="col-md-2" id="stage_label" >Stages</label>
                            <select class="custom-select col-md-3" name="stages" id="stages"  >
            
                            </select>
                        </div>
                    </div>  
                    <hr>
                    <div class="question_div">
                        <div class="form-group" id = "question">
                            <div class="row">
                            <input type="hidden" value="1" id="counter">
                            <input type="hidden" value="0" id="subcounter">
                                    <label class="col-md-2">Question 1</label>
                                    @text("question", ["name" => "question[q][1][questionTitle]", "placeholder" => "Enter Question", "class" => "form-control col-md-3"])
                                    <i class="i-Add col-md-2" id="addQuestion" style="color: #2cb8ea;  font-size: 22px;"></i>
                            </div>   
                        </div>

                        <div class="form-group" id="dropdown">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Answer Format</label>
                                </div>
                                <!-- @selectanswerformat("question[q][1][answerFormat]",["id" => "answerFormat", "class" => "form-control custom-select col-md-3"]) -->
                                <select class="custom-select col-md-3" name="question[q][1][answerFormat]" id="answerFormat"> 
                                    <option value="{{ 1 }}">Dropdown</option>
                                    <option value="{{ 2 }}">Textbox</option>
                                    <option value="{{ 3 }}">Radio</option>
                                    <option value="{{ 4 }}">Checkbox</option>
                                    <option value="{{ 5 }}">Textarea</option>
                                </select>
                            </div> 
                        </div>

                        <div class="form-group" id="labels">
                            <div class="row labels_container" id="labels_container_1">
                                        <label class="col-md-1">Label </label>
                                        <div class="col-md-1"><i id="add" type="button" onclick="addLabels(this);" class="add i-Add" style="color: #2cb8ea;  font-size: 22px;"></i></div>
                                        <div class="new-text-div col-md-10"></div>
                                    <!-- @text("label", ["id" => "label", "placeholder" => "Enter Label", "class" => "form-control col-md-3"]) -->
                               
                            </div>   
                        </div>
                    </div>

                    <div class="abc" id="append_div">
                    </div>
                    <div class="card-footer">
                        <div class="mc-footer">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" class="btn  btn-primary m-1" >Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-md-2">
                        <button type="submit" class="btn btn-success btn-sm">Save</button> 
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
           // e.preventDefault();
           var parentId = $(obj).parents(".labels_container").attr("id");
        //    alert(parentId);
           // $('<div/>').addClass( 'new-text-div' )
           var count=parseInt($('#counter').val());
           var subcount=parseInt($('#subcounter').val());
            $("#"+parentId).children(".new-text-div").append( $('<div class="row form-group rm"><input type="textbox" name="question[q]['+ count + '][label][]" id="label" class="form-control col-md-4"/> <i type="button" style="color: #f44336;  font-size: 22px;" class="remove col-md-1 i-Remove"></i></div><div class="abc" id="appendsub_div'+subcount+'"></div>'));
            var otherValue = $("#other_amount").val();
            // count++;
         }
       /* function addSubLabels(obj){   
           // e.preventDefault();
           var parentId = $(obj).parents(".labels_container").attr("id");
          // alert(parentId);
           // $('<div/>').addClass( 'new-text-div' )
           var count=parseInt($('#subcounter').val());
            $("#"+parentId).children(".new-text-div").append( $('<div class="row form-group rm"><input type="textbox" name="question[q]['+ count + '][label][]" id="label" class="form-control col-md-4"/> <i type="button" style="color: #f44336;  font-size: 22px;" class="remove col-md-1 i-Remove"></i><i class="col-md-1 i-Add  as" id="addSubQuestion" onclick="addSubQuestion('+count+');";  style="color: #2cb8ea;  font-size: 22px;" ></i> </div><div class="abc" id="appendsub_div'+count+'"></div>'));
            var otherValue = $("#other_amount").val();
            // count++;
         }    */ 

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
        var count=parseInt($('#counter').val());
        var id = $(this).parents( 'div.appendedQuestion' ).find('.labels_container').attr("id");
        var c =  id.substring(id.length- 1);
       if(count==c){
        var countvalue=parseInt($('#counter').val()) - 1;
            $('#counter').val(countvalue);
        e.preventDefault();
            $(this).parents( 'div.appendedQuestion' ).remove();
       }else{
           alert('Cant remove intermediate question');
       }
           
        });

        /*$(document).on('click', 'i.queSubRemove', function( e ) {
        var count=parseInt($('#subcounter').val());
        var id = $(this).parents( 'div.appendedQuestion' ).find('.labels_container').attr("id");
        var c =  id.substring(id.length- 1);
       if(count==c){
        var countvalue=parseInt($('#subcounter').val()) - 1;
            $('#subcounter').val(countvalue);
        e.preventDefault();
            $(this).parents( 'div.appendedQuestion' ).remove();
       }else{
           alert('Cant remove intermediate question');
       }
           
        });*/


        

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
        url: "get-form",
        success: function (data) {  
            template = $(data);
            var count=parseInt($('#counter').val()) + 1;
            $('#counter').val(count);
            template.find('.appendedQuestion').attr('id', 'appendedQuestion_'+count);           
            template.find('#question').attr('name', "question[q]["+count+"][questionTitle]");
            template.find('#question').attr('id', 'question_'+count);
            template.find('#answerFormat').attr('name', "question[q]["+count+"][answerFormat]");
            template.find('#answerFormat').attr('id', 'answerFormat_'+count);
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

    /*function addSubQuestion(valOfCount) {
        $.ajax({
        url: "get-subform",
        success: function (data) {  
            template = $(data);
            var count=parseInt($('#subcounter').val()) + 1;
            $('#subcounter').val(count);
            template.find('.appendedQuestion').attr('id', 'appendedSubQuestion_'+count);           
            template.find('#question').attr('name', "question[q]["+count+"][questionSubTitle]");
            template.find('#question').attr('id', 'question_'+count);
            template.find('#answerFormat').attr('name', "question[q]["+count+"][answerSubFormat]");
            template.find('#answerFormat').attr('id', 'answerSubFormat_'+count);
            // $('#label').attr('name', "question[q]["+count+"][label]");
            // $('#label').attr('id', 'label_'+count);

            template.find('.labels_container').attr('id', 'labels_subcontainer_'+count);
            template.find('.add').attr('id', 'addSub'+count);
            template.find('.add').attr('id', 'addSub'+count);
            template.find('#questionCounter').text(count);
            // template.find('#label').attr('value', ''+count);
            var valOfdiv = valOfCount;
            $("#appendsub_div"+valOfdiv ).append(template);
            $('#removeButton').show(); },
        dataType: 'html'
        });
        
       
    }*/

    // $('#removeButton').on('click', function( ) {
    //     $(this).closest( 'div.abc' ).remove();
    // });
    
</script>
@endsection