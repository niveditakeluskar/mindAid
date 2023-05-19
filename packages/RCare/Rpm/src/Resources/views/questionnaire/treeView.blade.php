@extends('Theme::layouts.master')
@section('page-css')

<script src="https://cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>
<style>
/*Now the CSS Created by R.S*/
* {margin: 0; padding: 0;}

.tree ul {
    padding-top: 20px; position: relative;
	
	transition: all 0.5s;
	-webkit-transition: all 0.5s;
	-moz-transition: all 0.5s;
}

.tree li {
	float: left; text-align: center;
	list-style-type: none;
	position: relative;
	padding: 20px 5px 0 5px;
	
	transition: all 0.5s;
	-webkit-transition: all 0.5s;
	-moz-transition: all 0.5s;
}

/*We will use ::before and ::after to draw the connectors*/

.tree li::before, .tree li::after{
	content: '';
	position: absolute; top: 0; right: 50%;
	border-top: 1px solid #ccc;
	width: 50%; height: 20px;
}
.tree li::after{
	right: auto; left: 50%;
	border-left: 1px solid #ccc;
}

/*We need to remove left-right connectors from elements without 
any siblings*/
.tree li:only-child::after, .tree li:only-child::before {
	display: none;
}

/*Remove space from the top of single children*/
.tree li:only-child{ padding-top: 0;width: 100%;}

/*Remove left connector from first child and 
right connector from last child*/
.tree li:first-child::before, .tree li:last-child::after{
	border: 0 none;
}
/*Adding back the vertical connector to the last nodes*/
.tree li:last-child::before{
	border-right: 1px solid #ccc;
	border-radius: 0 5px 0 0;
	-webkit-border-radius: 0 5px 0 0;
	-moz-border-radius: 0 5px 0 0;
}
.tree li:first-child::after{
	border-radius: 5px 0 0 0;
	-webkit-border-radius: 5px 0 0 0;
	-moz-border-radius: 5px 0 0 0;
}

/*Time to add downward connectors from parents*/
.tree ul ul::before{
	content: '';
	position: absolute; top: 0; left: 50%;
	border-left: 1px solid #ccc;
	width: 0; height: 20px;
}

.tree li a{
	border: 1px solid #ccc;
	padding: 5px 10px;
	text-decoration: none;
	color: #666;
	font-family: arial, verdana, tahoma;
	font-size: 11px;
	display: inline-block;
	
	border-radius: 5px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	
	transition: all 0.5s;
	-webkit-transition: all 0.5s;
	-moz-transition: all 0.5s;
    width: 100%;
}

/*Time for some hover effects*/
/*We will apply the hover effect the the lineage of the element also*/
.tree li a:hover, .tree li a:hover+ul li a {
	background: #c8e4f8; color: #000; border: 1px solid #94a0b4;
}
/*Connector styles on hover*/
.tree li a:hover+ul li::after, 
.tree li a:hover+ul li::before, 
.tree li a:hover+ul::before, 
.tree li a:hover+ul ul::before{
	border-color:  #94a0b4;
}

/*Thats all. I hope you enjoyed it.
Thanks :)*/
</style>
@endsection

@section('main-content')

	<!--
We will create a family tree using just.
The markup will be simple nested lists
-->
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
          
                <form class="" method="post" action="{{ route('save-rpm-questionnaire') }}"> 
                    @csrf
                    <div class="form-group">
                        
                            <input type="hidden" name="stage_code" value="0">
                            <div class="row"><div class="col-md-2"><label>Content Name</label> </div>
                            <input type="text" class="form-control col-md-3" name="content_title" id="content_title">
                            
                            <div class="col-md-2 offset-md-1"><label>Content Type</label></div>
                            <select class="custom-select col-md-3" name="template_type" id="template_type">
                            
                                    <option value="{{ 6 }}">Decision Tree</option>
                    
                            </select>
                            </div>
                    </div>  
                    <div class="form-group" style="text-align: end;">  
                        <div class="col-md-2 offset-md-3"><span class="cerror" style="display:none;color:red">Content Name Requred</span></div>
                    </div>  
                    <div class="form-group">
                        <div class="row"><label class="col-md-2">Modules</label>
                        <select class="custom-select col-md-3" name="module" id="module"  onchange="get_subservice(this.value);">
                            <option value="0" selected>Choose One...</option>
                            @foreach($service as $value)
                            <option value="{{ $value->id }}">{{ $value->module }}</option>
                            @endforeach
                        </select>
                        
                        <label class="col-md-2 offset-md-1">Sub Modules</label>
                        
                        <select class="custom-select col-md-3" name="sub_module" id="sub_module" onchange="get_stages(this.value);">
                             <option selected>Choose One...</option>
                        
                        </select></div>
                    </div>
                    <div class="form-group">  
                        <div class="col-md-3 offset-md-4"><span class="merror" style="display:none;color:red">Select module</span></div>
                        <div class="col-md-2 offset-md-10"><span class="smerror" style="display:none;color:red">Select Sub module</span></div>
                    </div> 

                    <div class="form-group">
                        <div class="row">
                        <label class="col-md-2" id="stage_label" >Stages</label>
                            <select class="custom-select col-md-3" name="stage_id" id="stages"  >
            
                            </select>
                        </div>
                    </div> 
                    <hr>
<div class="tree">
    <ul>
		<li class="question_tree" id="question_tree_1">
			<a href="#" onclick="return false;"><button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo">Question 1</button>
            <div class="question_div" id="demo">
                        <div class="form-group" id = "question">
                            <div class="row">
                            <input type="hidden" value="0" id="lbcount">    
                            <input type="hidden" value="0" id="count">
                            <input type="hidden" value="0" id="subcounter">
                                    <label class="col-md-2">Question 1</label>
                                    @text("question", ["name" => "DT[qs][q]", "placeholder" => "Enter Question", "class" => "form-control col-md-3 qt"])
                                    <!--i class="i-Add col-md-2" id="addQuestion" style="color: #2cb8ea;  font-size: 22px;"></i-->
                                    
                            </div>   
                            <div class="row"><span id="error" class="col-md-9" style="display:none;color:red">Please enter question</span></div>
                        </div>

                        <div class="form-group" id="dropdown">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Answer Format</label>
                                </div>
                                <!-- @selectanswerformat("question[q][1][answerFormat]",["id" => "answerFormat", "class" => "form-control custom-select col-md-3"]) -->
                                <select class="custom-select col-md-3" name="DT[qs][AF]" id="answerFormat" onchange="getChild(this)"> 
                                    <option value="{{ 1 }}">Dropdown</option>
                                    <option value="{{ 2 }}">Textbox</option>
                                    <option value="{{ 3 }}">Radio</option>
                                    <option value="{{ 5 }}">Textarea</option>
                                </select>
                            </div> 
                        </div>

                        <div class="form-group" id="labels">
                            <div class="row labels_container" id="labels_container_1">
                                        <label class="col-md-1">Label </label>
                                        <div class="col-md-1"><i id="add1" type="button" onclick="addLabels(this);" class="add i-Add"  style="color: #2cb8ea;  font-size: 22px;"></i></div>
                                        <!--div class="new-text-div col-md-10"></div-->
                                    <!-- @text("label", ["id" => "label", "placeholder" => "Enter Label", "class" => "form-control col-md-3"]) -->
                               
                            </div>   
                        </div>
                    </div>
                    </a>   
			<ul  class="new-text-div">
                
				<!--li>
					<a href="#">Child</a>
					<ul>
						<li>
							<a href="#">Grand Child</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="#">Child</a>
					<ul>
						<li><a href="#">Grand Child</a></li>
						<li>
							<a href="#">Grand Child</a>
							<ul>
								<li>
									<a href="#">Great Grand Child</a>
								</li>
								<li>
									<a href="#">Great Grand Child</a>
								</li>
								<li>
									<a href="#">Great Grand Child</a>
								</li>
							</ul>
						</li>
						<li><a href="#">Grand Child</a></li>
					</ul>
				</li-->
			</ul>
		</li>
	</ul>
</div>
<div class="tree" id="append_div">
</div>
<div class="card-footer">
    <div class="mc-footer">
        <div class="row">
            <div class="col-lg-12 text-right">
                <button type="submit" name="tree" class="btn  btn-primary m-1" onClick="return empty()">Save</button>
            </div>
        </div>
    </div>
</div>
</form>
            </div>
        </div>
    </div>
</div>           
	
@endsection
@section('page-js')
<script src="{{asset('assets/js/tooltip.script.js')}}"></script>
<script>
    function getChild(id){
        var parentId = $(id).parents(".question_tree").attr("id");
        if(id.value==2 || id.value==5){
            $("#"+parentId).find(".add").attr("onclick", 'addLabelsQue(this);');
        }else{
            $("#"+parentId).find(".add").attr("onclick", 'addLabels(this);');
            $("#"+parentId).find(".lrm").remove();
        }
        

    }
    function get_subservice(val){
        $.ajax({
            type: 'post',
            url: '/rpm/SaveRpmQuestionnaire',
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
            url: '/rpm/SaveRpmQuestionnaire',
            data: {
                _token: '{!! csrf_token() !!}',
                sub_module_ids:val
            },
            success: function (response) {
                document.getElementById("stages").innerHTML=response; 
            }
        });
    }
    var counters = 0;
function addLabels(obj){   
    
   
    var res = (obj.id).replace("add", ""); 
    
   
           // e.preventDefault();
           var parentId = $(obj).parents(".question_tree").attr("id");
           var name = $("#"+parentId).find("input.qt").prop("name");
          
           var lbel =  name.replace('[q]','');
           
           // $('<div/>').addClass( 'new-text-div' )
          // var count=parseInt($('#count').val());
           //var subcount=parseInt($('#subcounter').val()) + 1;
           //$('#subcounter').val(subcount);
           //$('#count').val(0);
           var QuestionVal =  $("#"+parentId).find(".qt").val();
           if(QuestionVal == '' || QuestionVal ==' '){
            $("#"+parentId).find("#error").show();
               return false;
           }else{
            $("#"+parentId).find("#error").hide();
           }
          
           var myid1 =  parseInt($("#"+parentId).find("#lbcount").attr("value"))+1;
           $("#"+parentId).find("#lbcount").attr("value", myid1);
           var myid = res+''+myid1;
           var label_name = lbel+'[opt]['+myid1+'][val]';

           $("#"+parentId).children(".new-text-div").append( $('<li class="lrm'+myid+'"><a href="#" onclick="return false;"><div class="row form-group rm"><input type="hidden" value="0" id="qscount'+myid+'"><input type="textbox" name="'+label_name+'" id="label" class="form-control col-md-7 offset-md-1"/> <i type="button" style="color: #f44336;  font-size: 22px;" class="remove col-md-1 i-Remove" id='+myid+'></i><i class="col-md-1 offset-md-1 i-Add  as" id="addSubQuestion" onclick=addSubQuestion('+myid+',"'+label_name+'","'+parentId+'")  style="color: #2cb8ea;  font-size: 22px;" ></i> </div><div class="row"><span id="lberror" class="col-md-12" style="display:none;color:red">Please enter label</span></div></a><ul class="abc" id="appendsub_div'+myid+'""></ul></li>'));
            var otherValue = $("#other_amount").val();
            // count++;
}
function addLabelsQue(obj){   
    
   
    var res = (obj.id).replace("add", ""); 
    
   
           // e.preventDefault();
           var parentId = $(obj).parents(".question_tree").attr("id");
           var name = $("#"+parentId).find("input.qt").prop("name");
          
           var lbel =  name.replace('[q]','');
           
           // $('<div/>').addClass( 'new-text-div' )
          // var count=parseInt($('#count').val());
           //var subcount=parseInt($('#subcounter').val()) + 1;
           //$('#subcounter').val(subcount);
           //$('#count').val(0);
          
           var myid1 =  parseInt($("#"+parentId).find("#lbcount").attr("value"))+1;
           $("#"+parentId).find("#lbcount").attr("value", myid1);
           var myid = res+''+myid1;
           var label_name = lbel+'[opt]['+myid1+'][val]';

           $("#"+parentId).children(".new-text-div").append( $('<li class="lrm"><a href="#" onclick="return false;"><div class="row form-group rm"><input type="hidden" value="0" id="qscount'+myid+'"><input type="hidden" name="'+label_name+'" value="default yes" id="label" class="form-control col-md-7 offset-md-1"/> </div><div class="row"><span id="lberror" class="col-md-12" style="display:none;color:red">Please enter question</span></div></a><ul class="abc" id="appendsub_div'+myid+'""></ul></li>'));
            var otherValue = $("#other_amount").val();
        addSubQuestion(myid,label_name,parentId);
            // count++;
}

function empty(){
    if($("#content_title").val()=='' || $("#content_title").val()==' '){
       $('.cerror').show();
        return false;
    }else{
        $('.cerror').hide();
    }
    if($('#module').val()==0){
        $('.merror').show();
        return false;
    }else{ $('.merror').hide();}
    if($('#sub_module').val()==0){
        $('.smerror').show();
        return false;
    }else{ $('.smerror').hide();}

    
    var valid = true;
    $(".qt").each(function(){
        var parentId = $(this).parents(".question_tree").attr("id");
        if(this.value==''){
            $("#"+parentId).find("#error").show();
            valid = false;
        }else{  $("#"+parentId).find("#error").hide();}
   })
   $("#label").each(function(){
       if(this.value==''){
        var pId = $(this).parents("li").attr("class");
        valid = false;
       }
   })
   return valid;
    
}
/*
function addSubLabels(obj){   
           // e.preventDefault();
           var parentId = $(obj).parents(".question_tree").attr("id");
           var res = (obj.id).replace("add", ""); 
          // alert(parentId);
           // $('<div/>').addClass( 'new-text-div' )
           var count=parseInt($('#subcounter').val());
            $("#"+parentId).children(".new-text-div").append( $('<li class="lrm"><a href="#"><div class="row form-group rm"><input type="textbox" name="question[q]['+ count + '][label][]" id="label" class="form-control col-md-7 offset-md-1"/> <i type="button" style="color: #f44336;  font-size: 22px;" class="remove col-md-1 i-Remove"></i><i class="col-md-1 offset-md-1 i-Add  as" id="addSubQuestion" onclick="addSubQuestion('+count+');";  style="color: #2cb8ea;  font-size: 22px;" ></i> </div></a><ul class="class="abc" id="appendsub_div'+count+'""></ul></li>'));
            var otherValue = $("#other_amount").val();
            // count++;
         }  */

/*
$(document).on('click', 'i.queRemove', function( e ) {
        var count=parseInt($('#counter').val());
        var id = $(this).parents( 'div.appendedQuestion' ).find('.labels_container').attr("id");
        var c =  id.substring(id.length- 1);
       if(count==c){
        var countvalue=parseInt($('#counter').val()) - 1;
            $('#counter').val(countvalue);
        e.preventDefault();
            $(this).parents( 'ul.appendedul' ).remove();
       }else{
           alert('Cant remove intermediate question');
       }
           
    });   */   
   // $(".queSubRemove").click(function(){
  //alert("The paragraph was clicked.");
//});


$(document).on('click', 'i.remove', function( e ) {
        e.preventDefault();
        $(this).parents( 'li.lrm'+this.id ).remove();
});    
    


/*
$('#addQuestion').on('click', function( e ) {
        $.ajax({
        url: "get-form",
        success: function (data) {  
            template = $(data);
            var count=parseInt($('#counter').val()) + 1;
            $('#counter').val(count);

            template.find('.question_tree').attr('id', 'question_tree_'+count);
            template.find('.btn-info').attr('data-target', '#appendedQuestion_'+count);
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
        
    });*/
   
function questionRemove(obj){

    if($('li#subquestion_tree'+obj.id).parents('li.lrm').find('#label').attr('value')=='default yes'){
        $('li#subquestion_tree'+obj.id).parents('li.lrm').remove();
    }else{
        $(obj).parents( 'li#subquestion_tree'+obj.id ).remove();
    }
    
}

function addSubQuestion(valOfCount,labelname,lbid) {
    //alert($("#question_tree_1").find('#qscount').attr('value'));
        $.ajax({
        url: "get-subform",
        success: function (data) {  
            template = $(data);
            var count=parseInt($("#"+lbid).find('#qscount'+valOfCount).attr('value')) + 1;
           if($(".lrm"+valOfCount).find('#label').val()=='' || $(".lrm"+valOfCount).find('#label').val()==' '){
                $(".lrm"+valOfCount).find('#lberror').show();
                return false;
            }else{
                $(".lrm"+valOfCount).find('#lberror').hide();
            }
            $("#"+lbid).find('#qscount'+valOfCount).attr('value', count);
            //$('#count').val(count);
            //alert(count);
            var label  =  labelname.replace('[val]','');
            var label_question = label+'[qs]['+count+'][q]';
            var label_af = label+'[qs]['+count+'][AF]';
            //alert($('#addSubQuestion').prev("input").attr("name"));
            //var count=parseInt($('#count').val()) + 1;
            //var subcount
            //template.find('.question_tree_list').attr('id', 'addtree_'+valOfCount);
            
            template.find('.btn-info').attr('data-target', '#appendedSubQuestion_'+valOfCount+''+count);
            template.find('.appendedQuestion').attr('id', 'appendedSubQuestion_'+valOfCount+''+count);           
            template.find('#question').attr('name', label_question);
            template.find('#question').attr('id', 'question_'+valOfCount);
            template.find('#answerFormat').attr('name', label_af);
            template.find('#answerFormat').attr('id', 'answerSubFormat_'+valOfCount+''+count);
            // $('#label').attr('name', "question[q]["+count+"][label]");
            // $('#label').attr('id', 'label_'+count);

            template.find('.labels_container').attr('id', 'labels_subcontainer_'+valOfCount+''+count);
            template.find('.add').attr('id', 'add'+valOfCount+''+count);
            template.find('.queSubRemove ').attr('id', valOfCount+''+count);
            // template.find('#questionCounter').text(valOfCount+''+count);
            // template.find('#label').attr('value', ''+count);
            var valOfdiv = valOfCount;
            $("#appendsub_div"+valOfdiv ).append('<li class="question_tree" id="subquestion_tree'+valOfCount+''+count+'"></li>');
            $("#subquestion_tree"+valOfCount+''+count).append(template);
            $('#removeButton').show(); },
        dataType: 'html'
        });
        
       
}
</script>
@endsection