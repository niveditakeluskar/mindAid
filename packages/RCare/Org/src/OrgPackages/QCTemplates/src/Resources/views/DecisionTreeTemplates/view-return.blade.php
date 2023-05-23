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
