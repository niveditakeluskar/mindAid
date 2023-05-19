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

.dataset {
    float: left;
    vertical-align: top;


}

.widget {
    display: inline-block;
    background-color: white;
    font-size: 14px !important;
    line-height: 20px !important;

    margin: 5px;
    vertical-align: top;
    color: #333;

    border-radius: 5px;
    margin: 10px;
    padding-bottom: 20px;
    border: 1px solid lightgray;


    border-radius:5px;
    -webkit-border-radius: 5px;


    display: inline-block;
    page-break-after: always;

}

.widget .header p {
   padding: 10px;
   border-bottom: 1px solid lightgrey;
   max-width: 360px;
}

.widget .header .title {
    font-weight: bold;
    vertical-align: middle;
    min-height: 36px;
    padding: 5px 10px 5px 10px;

}

.widget .header:hover {
    background-color: #f4f4f4;
}

.widget .header  .title.selected {
    border-color: cornflowerblue;
    background-color: #EEF;

}

.widget .content {
    padding: 5px;
    overflow-y: auto;
    max-height: 400px;
}

.autolayout {
    display: inline-block;
}

.element {
    width: 360px;
}

.compact .content {
    display: table;
    width: 100%;
}

.compact .row {
    display: table-row;
    width: 100%;
}

.compact .cell {
    display: table-cell;
}

.compact .row.selected {
    background-color: #eee;
}



.toolbar {
    display: block;
    vertical-align: top;
    margin: 10px;
}

.toolbar .basis {
    min-width: 100px;
}

.btn {
    /*min-width: 60px;*/
}


.cell.value {
    overflow: hidden;
    text-wrap: none;
    white-space: nowrap;
    text-overflow: ellipsis;
    text-align: right;
    padding-right: 10px;
}

.cell.freq {
    width: 60px;
}

.cell.glyph {
    vertical-align: middle;
    width: 100px;
}


.element {

}
.element table {
    table-layout: fixed;
    width: 100%;
}

.element td {
    padding: 0px;

}

.element .selectable:hover {
    background-color: #f4f4f4;
}


.element .stat {
    text-align: right;
    padding-right: 20px;
    font-weigth: bold;
    color: darkgray;
}



.element .bar {
    height: 18px;
    display: inline-block;
    float: left;
}
.bar-both {
    background-color: #0a67a3 !important;

}

.bar-fg {
    background-color: #3e97d1 !important;
}

.bar-bg {
    background-color: #ddd !important;
}

.selected .bar-fg {
    background-color: #FC0;
}

.selected .bar-both  {
    background-color:#FA0;
}

tr.selected {
    background-color: #eee;
}

.crosstab .selectable:hover {
    background-color: #f4f4f4;
}

.crosstab tr.selected {
    background-color: #eee;
}

.crosstab .header p {
    max-width: 600px;
}


.crosstab td {
  padding: 0 5px 0 5px;
  text-align: right;
}

.crosstab td.value {
  min-width: 60px;
  max-width: 240px;
  text-align: left;
}

.crosstab .cell {
    vertical-align: top;
}



.crosstab th.cell {
    max-width: 120px;
    overflow: hidden;
    white-space: normal;
    text-overflow: ellipsis;
    text-align: right;
    padding-right: 10px;
    vertical-align: bottom;
}

.crosstab .n {
    color: darkgray;
}
.fieldlist {
}

.constraints {
    min-width:300px;
    padding: 10px;

    border-radius:5px;
    -webkit-border-radius: 5px;


}
.constraints table {
    width: 100%;

}

.sidenote {
  max-width:300px;
  padding: 0 10px 0px 10px;
  display: inline-block;
  vertical-align: top;
}

.headnote {
    max-width: 600px;
    padding: 10px;
    margin: 10px;
    display: inline-block;
}

.info-block {
    /*border: 1px solid lightgrey;*/
    background-color: #eee;
    vertical-align: top;
    margin: 10px;
    padding: 10px;
    display: block;

    /*box-shadow: 0 0 0 0px #9bc0cf, 0 0 0 3px #e0ebf0;*/
}

.menu-item {
}

.menu-item-value {
    text-align: right;
    float: right;
}

.gradient-blue {
    background: #b8e1fc; /* Old browsers */
    /* IE9 SVG, needs conditional override of 'filter' to 'none' */
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2I4ZTFmYyIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwJSIgc3RvcC1jb2xvcj0iI2E5ZDJmMyIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjI1JSIgc3RvcC1jb2xvcj0iIzkwYmFlNCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjM3JSIgc3RvcC1jb2xvcj0iIzkwYmNlYSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjUwJSIgc3RvcC1jb2xvcj0iIzkwYmZmMCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjUxJSIgc3RvcC1jb2xvcj0iIzZiYThlNSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjgzJSIgc3RvcC1jb2xvcj0iI2EyZGFmNSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNiZGYzZmQiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
    background: -moz-linear-gradient(top, #b8e1fc 0%, #a9d2f3 10%, #90bae4 25%, #90bcea 37%, #90bff0 50%, #6ba8e5 51%, #a2daf5 83%, #bdf3fd 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #b8e1fc), color-stop(10%, #a9d2f3), color-stop(25%, #90bae4), color-stop(37%, #90bcea), color-stop(50%, #90bff0), color-stop(51%, #6ba8e5), color-stop(83%, #a2daf5), color-stop(100%, #bdf3fd)); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(top, #b8e1fc 0%, #a9d2f3 10%, #90bae4 25%, #90bcea 37%, #90bff0 50%, #6ba8e5 51%, #a2daf5 83%, #bdf3fd 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(top, #b8e1fc 0%, #a9d2f3 10%, #90bae4 25%, #90bcea 37%, #90bff0 50%, #6ba8e5 51%, #a2daf5 83%, #bdf3fd 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(top, #b8e1fc 0%, #a9d2f3 10%, #90bae4 25%, #90bcea 37%, #90bff0 50%, #6ba8e5 51%, #a2daf5 83%, #bdf3fd 100%); /* IE10+ */
    background: linear-gradient(to bottom, #b8e1fc 0%, #a9d2f3 10%, #90bae4 25%, #90bcea 37%, #90bff0 50%, #6ba8e5 51%, #a2daf5 83%, #bdf3fd 100%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#b8e1fc', endColorstr='#bdf3fd', GradientType=0); /* IE6-8 */
}
.tree li a {
    background: #c8e4f8;
}

/*Thats all. I hope you enjoyed it.
Thanks :)*/
</style>
@endsection
@section('main-content')
<div class="breadcrusmb">
   <div class="row">
      <div class="col-md-10">
         <h4 class="card-title mb-3">Update Decision Tree Template</h4>
      </div>
      <div class="col-md-2">
      <a href="<?php echo '/patientProfileImage/'.$data->id.'_DT_tree.jpg'?>" target="_blank" rel="noopener noreferrer"><button name="content_print" class="btn btn-danger btn-lg btn-block">View</button></a>
      </div>
   </div>
   <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row mb-4">
<div class="col-md-12 mb-4">
   <div class="card text-left">
      <div class="card-body" id="widget" style="background:white;">
         <form class="" method="post" action="{{ route('edit-Decision-Tree') }}">
            @csrf
            <input type="hidden" id="temp_id" name="question_id" value="{{ $data->id }}">
            <div class="form-group">
               <div class="row">
                    <div class="col-md-2"><label>Content Name <span class="error">*</span></label></div>
                    <input type="text" class="form-control col-md-3" name="content_title" value="{{ $data->content_title }}">
                    <div class="col-md-2 offset-md-1"><label>Content Type <span class="error">*</span></label></div>

                    <select class="custom-select col-md-3" name="template_type" id="template_type">
                         <option value="{{ 6 }}">Decision Tree</option>
                    </select>
               </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label class="col-md-2">Module<span class="error">*</span></label>
                    @selectOrgModule("module",["id"=>"module", "class"=>"col-md-3"])
                     <label class="col-md-2 offset-md-1" id="stage_label" >Sub Module <span class="error">*</span></label>
                     @select("Sub Module", "sub_module", [], ["id" => "sub_module", "class"=>"custom-select col-md-3"])
                 </div>  
            </div>  
            <div class="form-group">
                <div class="row">
                    <label class="col-md-2">Stage</label>
                    @select("Stage", "stages", [], ["id" => "stages", "class"=>"col-md-3"])
                    <label class="col-md-2 offset-md-1" id="stage_label" >Step</label>
                    @select("Step", "stage_code", [], ["id" => "stage_code", "class"=>"custom-select col-md-3"])    
                 </div>  
            </div>
            <div class="form-group">
                <div class="row">
                    <label class="col-md-2" >Sequence</label>
                    <input type="number" class="form-control col-md-3" name="sequence" id="sequence" value="{{ $data->sequence }}"> 
                    <?php 
                        $months = json_decode($data->display_months);
                    ?>
                    <label class="col-md-2 offset-md-1" >Months</label>
                            <div class="wrapMulDrop col-md-3">
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
                </div>
            </div>          
            <?php            
            function renderTree($treeObj,$lab,$val,$tree_id){
                $optCount = count((array)$treeObj);
                $allKeys = array_keys((array)$treeObj);
               //print_r(array_keys($allKeysOfEmployee));
                    foreach($allKeys as &$i){
                    //for($i=1; $i<= $optCount ; $i++) {
                    if(property_exists($treeObj,$i)){
                    $id = $val.''.$i;
                    if(strlen($id)>16){
                        $id = $id.'t';
                    }
                    //echo "<li><a href='#'>";
                    $label_str = str_replace("[q]","",$lab);
                    $label = $label_str."[opt][".$i."][val]";
					if(property_exists($treeObj->$i, 'qs') ){
						$c = count((array)$treeObj->$i->qs);
						$qtkey = array_keys((array)$treeObj->$i->qs);
						$co = $qtkey[$c-1];
						}
                    ?>
                    <li class="lrm<?php if($treeObj->$i->val != 'default yes'){ echo $id; } ?>"><a href="#" onclick="return false;"><div class="row form-group rm"><input type="hidden" value="<?php if(property_exists($treeObj->$i, 'qs') ){echo $co;}else{echo 0;}  ?>" id="qscount<?php echo $id; ?>">
                    <?php if($treeObj->$i->val == 'default yes'){  ?> <input type="hidden" name="{{$label}}" id="label" class="form-control col-md-7 offset-md-1" value="<?php  echo $treeObj->$i->val; ?>" >
                    <?php }else{ ?>
                        <textarea name="{{$label}}" id="label" class="form-control col-md-7 offset-md-1" ><?php  echo $treeObj->$i->val; ?></textarea> 
                    <?php } ?>
            <?php if($treeObj->$i->val != 'default yes'){ ?>
            <i type="button" style="color: #f44336;  font-size: 22px;" class="remove col-md-1 i-Remove" id="<?php echo $id; ?>"></i>
            <i class="col-md-1 offset-md-1 i-Add  as" id="addSubQuestion" onclick='addSubQuestion("<?php echo $id ; ?>","<?php echo $label ;?>","<?php echo $tree_id."".$val ; ?>")'    style="color: #2cb8ea;  font-size: 22px;" ></i><?php } ?> </div></a>
                    <?php 
                    //echo "<br>";
                    if(property_exists($treeObj->$i, 'qs') ){
                        //echo "<b>qs starts here </b><br>";  
                        $qtncount = count((array)$treeObj->$i->qs);
						$qtnkey = array_keys((array)$treeObj->$i->qs);
                        //echo "QtnCount". $qtncount;
                        $j = 1;
                        echo "<ul class='abc' id='appendsub_div".$id."'>";
						foreach($qtnkey as &$j){
                        //for($j=1; $j<= $qtncount; $j++) {
                            if(property_exists($treeObj->$i->qs,$j)){
                            $ids = $id.''.$j; 
                            $qs_label = str_replace("[val]","",$label);
                            $question_label = $qs_label."[qs][".$j."][q]";
                            $af_label = $qs_label."[qs][".$j."][AF]";
                            echo "<li class='question_tree' id='subquestion_tree".$ids."'><a href='#' onclick='return false;'>" ; ?>
                            <div class="appendedQuestion" id="appendedSubQuestion_<?php echo $ids; ?>" > 
                                <div class="form-group" >
                                    <div class="row">	
                                        <?php if(property_exists($treeObj->$i->qs->$j, 'opt')){
										$qoptc = count((array)$treeObj->$i->qs->$j->opt);
										$optkeys = array_keys((array)$treeObj->$i->qs->$j->opt);
										$qoptcount = $optkeys[$qoptc-1];
										} else{ $qoptcount=0;} ?>
                                            <input type="hidden" value="<?php echo  $qoptcount; ?>" id="lbcount">
                                            <label class="col-md-4" id="label">Question <span id="questionCounter"></span> </label>
                                        
                                            <!--input type="text" name="}" class="form-control col-md-3 qt" value=""-->
                                            <textarea name="{{$question_label}}" class="form-control col-md-7 qt" >{{$treeObj->$i->qs->$j->q}}</textarea>
                                            <!-- <i class="i-Add col-md-2" id="addQuestion" style="color: #2cb8ea;  font-size: 22px;"></i> -->
                                    </div>   
                                </div>
                                <div class="form-group" id="dropdown">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Answer Type </label>
                                        </div>
                                        <!-- @selectanswerformat("question[q][1][answerFormat]",["id" => "answerFormat", "class" => "form-control custom-select col-md-3"]) -->
                                        <select class="custom-select col-md-7" name="{{$af_label}}" id="answerSubFormat_<?php echo $ids; ?>" onchange="getChild(this)"> 
                                            <option value="{{ 3 }}" <?php if($treeObj->$i->qs->$j->AF==3){echo 'selected';} ?>>Radio</option>
                                            <option value="{{ 2 }}" <?php if($treeObj->$i->qs->$j->AF==2){echo 'selected';} ?>>Textbox</option>
                                            <option value="{{ 5 }}" <?php if($treeObj->$i->qs->$j->AF==5){echo 'selected';} ?>>Textarea</option>
                                            <option value="{{ 1 }}" <?php if ($treeObj->$i->qs->$j->AF==1){echo 'selected';} ?>>Checkbox</option>
                                        </select>
                                    </div> 
                                </div>

                                <div class="form-group" id="labels">
                                    <div class="row labels_container" id="labels_subcontainer_<?php echo $ids; ?>">
                                    
                                                <label class="col-md-4">Response Content : </label> 
                                                <div class="col-md-1"> <i id="add<?php echo $ids; ?>" type="button" <?php if($treeObj->$i->qs->$j->AF==2 || $treeObj->$i->qs->$j->AF==5){ ?> onclick="addLabelsQue(this);" <?php }else{ ?> onclick="addLabels(this);" <?php } ?> class="add i-Add" style="color: #2cb8ea;  font-size: 22px;"></i></div>
                                                
                                    </div>  
                                    <div>
                                        <i class="queSubRemove i-Remove" id="{{$ids}}" onclick="questionRemove(this)" style="color: #f44336;  font-size: 22px;"></i>
                                    </div> 
                                </div>
                            </div>
                          
                          <?php  
                          
                          echo "</a>";
                          echo "<ul class='new-text-div'>";
                          if(property_exists($treeObj->$i->qs->$j, 'opt')){ renderTree($treeObj->$i->qs->$j->opt,$question_label,$ids,'subquestion_tree'); }
                          echo "</ul>";
                          echo "</li>";
                           // echo "<br>";
                           // echo "question ".$j. " AF: ".$treeObj->$i->qs->$j->AF ;                        
                           //echo "<br>";
                          // echo "===============================================================<br>";
                           
                        }
                    }
                        echo "</ul>";
                    }else{ echo "<ul class='abc' id='appendsub_div".$id."'></ul>";}
                    echo "</li>";
                   // echo "===============================================================<br>";
                   
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
							<?php $c = count((array)$queData->question->qs->opt) ; 
								$allKeys = array_keys((array)$queData->question->qs->opt);
								$co = $allKeys[$c-1];
							?>
                            <input type="hidden" value="<?php if(property_exists($queData->question->qs, 'opt')){ echo $co ; }else{echo 0;} ?>" id="lbcount">    
                            <input type="hidden" value="0" id="count">
                            <input type="hidden" value="0" id="subcounter">
                                    <label class="col-md-2">Question 1 <span class="error">*</span></label>
                                    
                                    <!--<input type="text" name="DT[qs][q]" class="form-control col-md-3 qt" value="">-->
                                    <textarea name="DT[qs][q]" class="form-control col-md-3 qt" >{{$queData->question->qs->q}}</textarea>
                                    <!--i class="i-Add col-md-2" id="addQuestion" style="color: #2cb8ea;  font-size: 22px;"></i-->
                            </div>   
                        </div>

                        <div class="form-group" id="dropdown">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Answer Format <span class="error">*</span></label>
                                </div>
                                <!-- @selectanswerformat("question[q][1][answerFormat]",["id" => "answerFormat", "class" => "form-control custom-select col-md-3"]) -->
                                <select class="custom-select col-md-3" name="DT[qs][AF]" id="answerFormat" onchange="getChild(this)"> 
                                    <option value="{{ 3 }}" <?php if ($queData->question->qs->AF==3){echo 'selected';} ?>>Radio</option>
                                    <option value="{{ 2 }}" <?php if ($queData->question->qs->AF==2){echo 'selected';} ?>>Textbox</option>
                                    
                                    <option value="{{ 5 }}" <?php if ($queData->question->qs->AF==5){echo 'selected';} ?>>Textarea</option>
                                    <option value="{{ 1 }}" <?php if ($queData->question->qs->AF==1){echo 'selected';} ?>>Checkbox</option>
                                </select>
                            </div> 
                        </div>

                        <div class="form-group" id="labels">
                            <div class="row labels_container" id="labels_container_1">
                                        <label class="col-md-2">Response Content </label>
                                        <div class="col-md-1"><i id="add1" type="button" <?php if($queData->question->qs->AF==2 || $queData->question->qs->AF==5){ ?> onclick="addLabelsQue(this);" <?php }else{ ?> onclick="addLabels(this);" <?php } ?> class="add i-Add"  style="color: #2cb8ea;  font-size: 22px;"></i></div>
                                        <!--div class="new-text-div col-md-10"></div-->
                                    <!-- @text("label", ["id" => "label", "placeholder" => "Enter Label", "class" => "form-control col-md-3"]) -->
                               
                            </div>   
                        </div>
                    </div>
                    </a>   
			<ul  class="new-text-div">
            <?php
                           //dd($queData);
                          // echo $queData->question->qs->q; 
                           //echo "<br>";                          
                           //echo $queData->question->qs->AF; 
                           //echo "<br>";  
                           
                           //echo "<pre>";
                           if(property_exists($queData->question->qs, 'opt')){ renderTree($queData->question->qs->opt,'DT[qs][q]','1','question_tree_');}
                           //print_r($queData->question->qs);
                         
                                
                           ?> 
				
			</ul>
		</li>
	</ul>
</div>

                           
<div class="card-footer">
    <div class="mc-footer">
        <div class="row">
            <div class="col-lg-12 text-right">
                <button type="submit" class="btn  btn-primary m-1" name="edit" onClick="return empty()">Update</button>
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
<script src="{{asset('assets/js/html2canvas.js')}}"></script>
<script src="{{asset('assets/js/base64js.min.js')}}"></script>
<script src="{{asset('assets/js/canvas2image.js')}}"></script>
<script>

function getChild(id){
        var parentId = $(id).parents(".question_tree").attr("id");
        //alert(id.value);
        if(id.value==2 || id.value==5){
            $("#"+parentId).find(".add").attr("onclick", 'addLabelsQue(this);');
            $("#"+parentId).find("li").remove();
            $("#"+parentId).find(".add").click();
           
        }else{
            $("#"+parentId).find(".add").attr("onclick", 'addLabels(this);');
            $("#"+parentId).find(".lrm").remove();
        }
        

    }

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
                sub_module:val
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
           var name = $("#"+parentId).find("textarea.qt").prop("name");
          
           var lbel =  name.replace('[q]','');
           
           // $('<div/>').addClass( 'new-text-div' )
          // var count=parseInt($('#count').val());
           //var subcount=parseInt($('#subcounter').val()) + 1;
           //$('#subcounter').val(subcount);
           //$('#count').val(0);
          
           var myid1 =  parseInt($("#"+parentId).find("#lbcount").attr("value"))+1;
           $("#"+parentId).find("#lbcount").attr("value", myid1);
           
           var myid = res+''+myid1;
           if(myid.length > 16){
               myid = myid+'t';
           };
           var label_name = lbel+'[opt]['+myid1+'][val]';

           $("#"+parentId).children(".new-text-div").append( $('<li class="lrm'+myid+'"><a href="#" onclick="return false;"><div class="row form-group rm"><input type="hidden" value="0" id="qscount'+myid+'"><input type="textbox" name="'+label_name+'" id="label" class="form-control col-md-7 offset-md-1"/> <i type="button" style="color: #f44336;  font-size: 22px;" class="remove col-md-1 i-Remove" id='+myid+'></i><i class="col-md-1 offset-md-1 i-Add  as" id="addSubQuestion" onclick=addSubQuestion("'+myid+'","'+label_name+'","'+parentId+'")  style="color: #2cb8ea;  font-size: 22px;" ></i> </div></a><ul class="abc" id="appendsub_div'+myid+'""></ul></li>'));
            var otherValue = $("#other_amount").val();
            // count++;
}
function addLabelsQue(obj){   
    
   
    var res = (obj.id).replace("add", ""); 
    
   
           // e.preventDefault();
           var parentId = $(obj).parents(".question_tree").attr("id");
           var name = $("#"+parentId).find("textarea.qt").prop("name");
          
           var lbel =  name.replace('[q]','');
           
           // $('<div/>').addClass( 'new-text-div' )
          // var count=parseInt($('#count').val());
           //var subcount=parseInt($('#subcounter').val()) + 1;
           //$('#subcounter').val(subcount);
           //$('#count').val(0);
          
           var myid1 =  parseInt($("#"+parentId).find("#lbcount").attr("value"))+1;
           $("#"+parentId).find("#lbcount").attr("value", myid1);
           var myid = res+''+myid1;
           if(myid.length > 16){
               myid = myid+'t';
           };
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
    if($('#sub_module').val()=='Select Sub Module'){
        $('.smerror').show();
        return false;
    }else{ $('.smerror').hide();}
    if($('#stages').val()=='Select Stage'){
        $('.stageerror').show();
        return false;
    }else{ $('.stageerror').hide();}
    if($('#stage_code').val()=='Select Stage Code'){
        $('.steperror').show();
        return false;
    }else{ $('.steperror').hide();}
    
    var valid = true;
    $(".qt").each(function(){
        var parentId = $(this).parents(".question_tree").attr("id");
        if(this.value==''  && $('#'+parentId).parents(".lrm").find('#label').attr("value") != 'default yes'){
            $("#"+parentId).find("#error").show();
            valid = false;
        }else{  $("#"+parentId).find("#error").hide();}
   })
   $("form input[type='textbox']").each(function(){
        var pId = $(this).parents("li").attr("class");
       if(this.value==''){
        $("."+pId).find("#lberror").show();
        valid = false;
       }else{$("."+pId).find("#lberror").hide();}
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
function questionRemove(obj){
    if($('li#subquestion_tree'+obj.id).parents('li.lrm').find('#label').attr('value')=='default yes'){
        $('li#subquestion_tree'+obj.id).parents('li.lrm').remove();
    }else{
        $(obj).parents( 'li#subquestion_tree'+obj.id ).remove(); 
    }
}

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
function addSubQuestion(valOfCount,labelname,lbid) {
    //alert($("#question_tree_1").find('#qscount').attr('value'));
    if($("#"+lbid).find('select').val() == '1')
    {
        return false;
    }  
        $.ajax({
        url: "/rpm/dynamic-template-decision",
        success: function (data) {  
            template = $(data);
            var count=parseInt($("#"+lbid).find('#qscount'+valOfCount).attr('value')) + 1;
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
            //template.find('#questionCounter').text(valOfCount+''+count);
            // template.find('#label').attr('value', ''+count);
            var valOfdiv = valOfCount;
            $("#appendsub_div"+valOfdiv ).append('<li class="question_tree" id="subquestion_tree'+valOfCount+''+count+'"></li>');
            $("#subquestion_tree"+valOfCount+''+count).append(template);
            $('#removeButton').show(); },
        dataType: 'html'
        });
        
       
}

function base64ToBlob(base64, mime) 
{
    mime = mime || '';
    var sliceSize = 1024;
    var byteChars = window.atob(base64);
    var byteArrays = [];

    for (var offset = 0, len = byteChars.length; offset < len; offset += sliceSize) {
        var slice = byteChars.slice(offset, offset + sliceSize);

        var byteNumbers = new Array(slice.length);
        for (var i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
        }

        var byteArray = new Uint8Array(byteNumbers);

        byteArrays.push(byteArray);
    }

    return new Blob(byteArrays, {type: mime});
} 

$(document).ready(function(){
	
	$('#decision_tree_content').on( 'change keyup keydown paste cut', 'textarea', function (){
    $(this).height(0).height(this.scrollHeight);
}).find( 'textarea' ).change();


	
    var module_id = {{$data->module_id}};
    var submodule_id = {{$data->component_id}};
    var stage_id = {{($data->stage_id != "") ? $data->stage_id : 0 }};
    var stage_code = {{ ($data->stage_code != "") ? $data->stage_code : 0 }}
    var template_type = {{$data->template_type_id}};
    $('#template_type').val(template_type);
    $('#module').val(module_id);
    util.updateSubModuleList(parseInt(module_id), $("#sub_module"), parseInt(submodule_id));
    util.updateStageList(parseInt(submodule_id), $("#stages"), parseInt(stage_id));
    util.updateStageCodeList(parseInt(stage_id), $("#stage_code"), parseInt(stage_code));
    $("[name='module']").on("change", function () {
        util.updateSubModuleList(parseInt($(this).val()), $("#sub_module"));
    });

    $("[name='sub_module']").on("change", function () {
        util.updateStageList(parseInt($(this).val()), $("#stages"));
    });

    $("[name='stages']").on("change", function () {
        util.updateStageCodeList(parseInt($(this).val()), $("#stage_code"));
    });
    util.getToDoListData(0, {{getPageModuleName()}});

    setTimeout(function () {
       
       html2canvas($("#widget"), {
           onrendered: function(canvas) {
               theCanvas = canvas;
               //document.body.appendChild(canvas);
               var imgsrc = canvas.toDataURL("image/png");
               var image = imgsrc;
               var base64ImageContent = image.replace(/^data:image\/(png|jpg);base64,/, "");
               var blob = base64ToBlob(base64ImageContent, 'image/png');  
               var temp = $('#temp_id').val();             
               var formData = new FormData();
               formData.append('picture', blob);
               formData.append('temp', temp);
               
               $.ajax({
               url: '/ccm/ajax/uploadDTImage',
               type: 'POST',
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               data: formData,
               //enctype: 'multipart/form-data',
               success: function(data) {
                    //console.log(data + "testingggg");    
                   $('#image_path').val(data);
               },
               cache: false,
               contentType: false,
               processData: false
           });

           }
       });
   
  
}, 1000);
});   

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
            $('.multiDrop').html('Select month<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
        }
    });
</script>
@endsection