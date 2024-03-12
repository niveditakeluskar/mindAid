<div class="appendedQuestion" id="question_div">
    <div class="form-group" >
        <div class="row">
                <label class="col-md-2" id="label">Question <span id="questionCounter"></span> </label>
                @text("question", ["id" => "question", "placeholder" => "Enter Question", "class" => "form-control col-md-3"])
                <!-- <i class="i-Add col-md-2" id="addQuestion" style="color: #2cb8ea;  font-size: 22px;"></i> -->
        </div>   
    </div>

    <div class="form-group" id="dropdown">
        <div class="row">
            <div class="col-md-2">
                <label>Answer Type</label>
            </div>
            <!-- @selectanswerformat("question[q][1][answerFormat]",["id" => "answerFormat", "class" => "form-control custom-select col-md-3"]) -->
            <select class="custom-select col-md-3" name="question[q][1][answerFormat]" id="answerFormat"> 
                <option value="0">Select Answer Format</option>
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
        
                    <label class="col-md-1">Label : </label>
                    <div class="col-md-1"> <i id="addQue" type="button" qno="1" onclick="addLabels(this);" class="add i-Add" style="color: #2cb8ea;  font-size: 22px;"></i></div>
                    <div class="new-text-div col-md-10"></div>
                <!-- @text("label", ["id" => "label", "placeholder" => "Enter Label", "class" => "form-control col-md-3"]) -->
           
        </div>  
        <div>
            <i class="queRemove i-Remove" id="removeButton" style="color: #f44336;  font-size: 22px;"></i>
        </div> 
    </div>
</div>


<script>
// var count = 1;
$(function() {
        /*
        $('#addQue').on('click', function( e ) {
            e.preventDefault();
            $('<div/>').addClass( 'que-div' )
            .html( $('<input type="textbox"/></br>').addClass( ' form-control col-md-4' ) )
            .append( $('<button/></br></br>').addClass( 'removeLabel' ).text( 'Remove' ) )
            .insertBefore( this );
    
        });
        $(document).on('click', 'button.removeLabel', function( e ) {
            e.preventDefault();
            $(this).closest( 'que-div' ).remove();
        });
        */
    });
    
    // $('#removeButton').on('click', function( ) {
    //     $( 'div.appendedQuestion' ).remove();
    // });
/*
    $('#removeButton').on('click', function( e ) {
            e.preventDefault();
            $( 'div.appendedQuestion' ).remove();
            $( '#removeButton' ).remove();
        });
  */      
</script>
