<div class="form-group" >
    <div class="row">
        <label class="col-md-2" id="label">Question <span id="questionCounter"></span> <span class="error">*</span></label>
        @text("question", ["id" => "question", "placeholder" => "Enter Question", "class" => "form-control col-md-4"])
    </div>   
</div>

<div class="form-group" id="dropdown">
    <div class="row">
        <div class="col-md-2">
            <label>Answer Type <span class="error">*</span></label>
        </div>
        <!-- @selectanswerformat("question[q][1][answerFormat]",["id" => "answerFormat", "class" => "form-control custom-select col-md-3"]) -->
        <div class="col-md-4">
            <select class="custom-select" name="question[q][1][answerFormat]" id="answerFormat" onchange="getChild(this)"> 
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
        <label class="col-md-2">Response Content : </label>
        <div class="col-md-1"> <i id="addQue" type="button" qno="1" onclick="addLabels(this);" class="add i-Add" style="color: #2cb8ea;  font-size: 22px;"></i></div>
        <div class="new-text-div col-md-9"></div>
    <!-- @text("label", ["id" => "label", "placeholder" => "Enter Label", "class" => "form-control col-md-3"]) -->
    </div>  
    <div class="mt-2">
        <button type="button" class="btn btn-danger btn-sm queRemove" id="removeButton" style="">Remove Question</button>
    </div> 
</div>
<div class="separator-breadcrumb border-top"></div>
