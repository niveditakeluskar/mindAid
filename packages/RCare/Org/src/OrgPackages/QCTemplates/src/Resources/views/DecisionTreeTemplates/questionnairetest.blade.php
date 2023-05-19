<!--li class="question_tree_list" id="tree"-->
<a href="#" onclick="return false;"><button type="button" class="btn btn-info mb-4" data-toggle="collapse" data-target="#question_div">Question 1</button>
<div class="appendedQuestion" id="question_div" >
    <div class="form-group" >
        <div class="row">
                <input type="hidden" value="0" id="lbcount">
                <label class="col-md-4" id="label">Question   <span class="error">*</span></label>
                <!--@text("question", ["id" => "question", "placeholder" => "Enter Question", "class" => "form-control col-md-7 qt"])-->
                <textarea name="DT[qs][q]" class="form-control col-md-7 qt" placeholder="Enter Question" id="question"></textarea>
                <!-- <i class="i-Add col-md-2" id="addQuestion" style="color: #2cb8ea;  font-size: 22px;"></i> -->
        </div>  
        <div class="row"><span id="error" class="col-md-12" style="display:none;color:red">Please enter question</span></div> 
    </div>

    <div class="form-group" id="dropdown">
        <div class="row">
            <div class="col-md-4">
                <label>Answer Type  <span class="error">*</span></label>
            </div>
            <!-- @selectanswerformat("question[q][1][answerFormat]",["id" => "answerFormat", "class" => "form-control custom-select col-md-3"]) -->
            <select class="custom-select col-md-7" name="question[q][1][answerFormat]" id="answerFormat" onchange="getChild(this)"> 
                <option value="{{ 3 }}">Radio</option>
                <option value="{{ 2 }}">Textbox</option>
                <option value="{{ 5 }}">Textarea</option>
                <option value="{{ 1 }}">Checkbox</option>
            </select>
        </div> 
    </div>

    <div class="form-group" id="labels">
        <div class="row labels_container" id="labels_container_1">
        
                    <label class="col-md-4">Response Content : </label>
                    <div class="col-md-1"> <i id="addQue" type="button" onclick="addLabels(this);" class="add i-Add" style="color: #2cb8ea;  font-size: 22px;"></i></div>
                    <div class="col-md-1">
                        <i class="queSubRemove i-Remove" id="removeButton" style="color: #f44336;  font-size: 22px;" onclick="questionRemove(this);"></i>
                    </div> 
                    <!--div class="new-text-div col-md-10"></div-->
                <!-- @text("label", ["id" => "label", "placeholder" => "Enter Label", "class" => "form-control col-md-3"]) -->
           
        </div>  
        
    </div>
</div>
</a>
<ul  class="new-text-div">
</ul>  
<!--/li-->

