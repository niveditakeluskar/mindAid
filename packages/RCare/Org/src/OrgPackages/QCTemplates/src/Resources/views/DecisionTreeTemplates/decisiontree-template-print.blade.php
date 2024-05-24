<!DOCTYPE html>
<html>
<head>
<!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"-->
<style type="text/css">
body{
    font-family:verdana;    
}



table, th {
 /* border: 0px solid black; */
 text-align:justify ;
 /*text-align:left;*/
 /*padding-left:3px;*/ 
}
table,td{
vertical-align: middle;
 
text-align:justify;
}

/*table, tr{ 
    margin-right: 10px; 
}
*/
b{
    color:#000033;

}
h4
{
   background-color:#cdebf9;
   color:#000033;
   padding-top: 8px;
    padding-bottom: 8px;
    padding-left: 7px;
    /*width: 330px;*/
}
h3
{
   padding-top: 5px;
    padding-bottom: 5px;
    padding-left: 7px;
    color:white;
}
#cpid
{
    float: right;
    color: white;
    font-size: 36px;
    font-family: -webkit-pictograph;   
    margin-top: -11px;
    margin-right: 10px;
}
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

</style>
    <title>Decision Tree Template</title>
</head>
<body>
<div class="card">
   <div class="card-body"> 
        <div class="form-row" style="background-color:#27a8de;height: 100px;">
             <div class="form-row" margin-top="20px">
                <img src="http://rcareproto2.d-insights.global/assets/images/logo.png" alt="" height="40px" width="150px" style="padding-top: 30px;padding-left: 17px;">
             </div>
            <label id="cpid">Decision Tree Template</label>
        </div>
        <div class="form-row" style="background-color:#1474a0;height: 10px;"></div>
        <div class="form-row" style="padding-top:20px;">
            <table width=100%>
                <tr>
                    <td><b>Content Name:</b> {{$data->content_title }}</td>
                    <td><b>Content Type:</b> {{ $type[0]->template_type }}</td>
                </tr>
                <tr>
                    <td><b>Modules:</b> {{$module[0]->module }}</td>
                    <td><b>Step:</b> @if(isset($stageCode[0]->description) && $stageCode[0]->description != "") {{ $stageCode[0]->description }} @endif</td>
                </tr>
                <tr>
                    <td><b>Sub Modules:</b> {{ $components[0]->components }}</td>
                    <td><b>Stage:</b> @if(isset($stage[0]->description) && $stage[0]->description != "") {{ $stage[0]->description }} @endif</td>
                </tr>
                <tr>
                    <td><b>Sequence:</b> {{$data->sequence }}</td>
                </tr>
            </table>
            <hr>
            <div class="separator-breadcrumb border-top"></div>
            

            <?php
            
            function renderTree($treeObj,$lab,$val,$tree_id){
                $optCount = count((array)$treeObj);
                $i=1;
                for($i=1; $i<= 5 ; $i++) {
                    if(property_exists($treeObj,$i)){
                    $id = $val.''.$i;
                    if(strlen($id)>16){
                        $id = $id.'t';
                    }
                    //echo "<li><a href='#'>";
                    $label_str = str_replace("[q]","",$lab);
                    $label = $label_str."[opt][".$i."][val]";
                    ?>
                    <li class="lrm<?php echo $id; ?>">
                    <a href="#" onclick="return false;"><div class="row form-group rm">
                    <input type="<?php if($treeObj->$i->val == 'default yes'){echo 'hidden';}else{echo 'textbox';} ?>" name="{{$label}}" id="label" class="form-control col-md-7 offset-md-1" value="<?php  echo $treeObj->$i->val; ?>" >  
                     </div></a>
                    <?php 
                    
                    if(property_exists($treeObj->$i, 'qs') ){
    
                        $qtncount = count((array)$treeObj->$i->qs);
                        $j = 1;
                        echo "<ul class='abc' id='appendsub_div".$id."'>";
                        for($j=1; $j<= 5; $j++) {
                            if(property_exists($treeObj->$i->qs,$j)){
                            $ids = $id.''.$j; 
                            $qs_label = str_replace("[val]","",$label);
                            $question_label = $qs_label."[qs][".$j."][q]";
                            $af_label = $qs_label."[qs][".$j."][AF]";
                            echo "<li class='question_tree' id='subquestion_tree".$ids."'><a href='#' onclick='return false;'>" ; ?>
                            <div class="appendedQuestion" id="appendedSubQuestion_<?php echo $ids; ?>" >
                                <div class="form-group" >
                                    <div class="row">
                                           
                                            <label class="col-md-4" id="label">Question <span id="questionCounter"></span> </label>
                                            <textarea name="{{$question_label}}" class="form-control col-md-7 qt" >{{$treeObj->$i->qs->$j->q}}</textarea>
                                    </div>   
                                </div>
                                <div class="form-group" id="dropdown">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Answer Type </label>
                                        </div>
                                        <!-- @selectanswerformat("question[q][1][answerFormat]",["id" => "answerFormat", "class" => "form-control custom-select col-md-3"]) -->
                                        <select class="custom-select col-md-7" name="{{$af_label}}" id="answerSubFormat_<?php echo $ids; ?>" onchange="getChild(this)" disabled> 
                                            <option value="{{ 3 }}" <?php if($treeObj->$i->qs->$j->AF==3){echo 'selected';} ?>>Radio</option>
                                            <option value="{{ 2 }}" <?php if($treeObj->$i->qs->$j->AF==2){echo 'selected';} ?>>Textbox</option>
                                            <option value="{{ 5 }}" <?php if($treeObj->$i->qs->$j->AF==5){echo 'selected';} ?>>Textarea</option>
                                        </select>
                                    </div> 
                                </div>
                            </div>
                          
                          <?php  
                          
                          echo "</a>";
                          echo "<ul class='new-text-div'>";
                          if(property_exists($treeObj->$i->qs->$j, 'opt')){ renderTree($treeObj->$i->qs->$j->opt,$question_label,$ids,'subquestion_tree'); }
                          echo "</ul>";
                          echo "</li>";
                        }
                    }
                        echo "</ul>";
                    }else{ echo "<ul class='abc' id='appendsub_div".$id."'></ul>";}
                    echo "</li>";
                   
                 }
                }    
                    
                   
            }               
            $queData = json_decode($data['question']); ?>




            <div class="tree" id="decision_tree_content">
                <ul>
                    <li class="question_tree" id="question_tree_1">
                        <a href="#" onclick="return false;"><button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo">Question 1</button>
                        <div class="question_div" id="demo">
                                    <div class="form-group" id = "question">
                                        <div class="row">
                                                <label class="col-md-2">Question 1 <span class="error">*</span></label>
                                                <textarea  class="form-control col-md-3 qt" >{{$queData->question->qs->q}}</textarea>
                                        </div>   
                                    </div>
                                    <div class="form-group" id="dropdown">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label>Answer Format <span class="error">*</span></label>
                                            </div>
                                            <select class="custom-select col-md-3" name="DT[qs][AF]" id="answerFormat" onchange="getChild(this)" disabled> 
                                                <option value="{{ 3 }}" <?php if ($queData->question->qs->AF==3){echo 'selected';} ?>>Radio</option>
                                                <option value="{{ 2 }}" <?php if ($queData->question->qs->AF==2){echo 'selected';} ?>>Textbox</option>
                                                
                                                <option value="{{ 5 }}" <?php if ($queData->question->qs->AF==5){echo 'selected';} ?>>Textarea</option>
                                            </select>
                                        </div> 
                                    </div>
            
                                    <!-- div class="form-group" id="labels">
                                        <div class="row labels_container" id="labels_container_1">
                                                    <label class="col-md-1">Label </label>
                                                    <div class="col-md-1"><i id="add1" type="button" onclick="addLabels(this);" class="add i-Add"  style="color: #2cb8ea;  font-size: 22px;"></i></div>
                                        </div>   
                                    </div>-->
                                </div>
                                </a>   
                        <ul  class="new-text-div">
                        <?php
                        if(property_exists($queData->question->qs, 'opt')){ renderTree($queData->question->qs->opt,'DT[qs][q]','1','question_tree_');}         
                        ?> 
                            
                        </ul>
                    </li>
                </ul>
            </div>
            
                
        </div>
            <br><br><br>
     </div>
    <div class="card-footer"><hr></div>
</div>

    <!--div class="form-row" style="background-color:#1474a0;height: 10px;"></div>
    <div class="form-row" style="background-color:#27a8de;height: 80px;"></div-->

</body>
</html>
<script>
$('#decision_tree_content').on( 'change keyup keydown paste cut', 'textarea' , function (){
        $(this).height(0).height(this.scrollHeight);
    }).find( 'textarea' ).change();
</script>
                                            