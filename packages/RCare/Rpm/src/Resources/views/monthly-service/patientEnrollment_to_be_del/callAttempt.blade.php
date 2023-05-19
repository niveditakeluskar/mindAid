<div class="card">
    <div class="card-body text-left">
        <div class="row">
            <label class="col-md-2 mb-4 float-left">Call Answered</label>
            <label class="radio radio-primary col-md-1">
                <input type="radio" name="call_radio" value="1" formControlName="radio" id="answered">
                <span></span>
                <span class="checkmark"></span> 
            </label>
             <input type="hidden" name="hidden_id" id="hidden_id" value="{{$checklist->id}}">
              <input type="hidden" name="practice_id" id="practice_id" value="{{$checklist->practice_id}}">
              <input type="hidden" id="time_stop1" value="" name="">
              <input type="hidden" id="time_stop2" value="" name=""> 
            <div class="col-md-4 mb-4 float-left" id="answer" style= "display:none">
                <select class="form-control" name="content_title" id="call_scripts"  data-toggle="modal" >
                    <option>Choose Script</option>
                    @foreach($callScripts as $value)
                        <option value="{{ $value->id }}">{{ $value->content_title }}</option>
                    @endforeach
                </select>
            </div>


        </div>
        <div class="row">
            <label class="col-md-2 mb-4 float-left">Call Not Answered</label>
            <label class="radio radio-primary col-md-1">
                <input type="radio" name="call_radio" value="2" formControlName="radio" id="not_answered">
                <span></span>
                <span class="checkmark"></span>
            </label>

            <div class="col-md-4 mb-4 float-left" id="not_answer" style= "display:none">
                @selectanswer("not_answer",["id" => "not_answer", "class" => "form-control form-control"])
                <div class="text-left">
                    <button type="button" class="btn btn-primary btn-icon m-1" id="finish">
                        <span class="ul-btn__text">Submit</span> 
                    </button>
                </div>
            </div>
        </div>
    </div>
<!--     <div class="card-footer">
        <div class="row">
            <div class="col-lg-12 text-right">
                <button type="submit" class="btn  btn-primary m-1" >Save</button>
            </div>
        </div>
    </div> -->
</div>

<!-- Modal -->
<div class="modal fade script_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" id="script_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">script</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="script_template_type_id" name="template_type_id"> 
                <input type="hidden" id="script_module_id" name="module_id">
                <input type="hidden" id="script_component_id" name="component_id">
                <input type="hidden" id="script_stage_id" name="stage_id">
                <input type="hidden" id="script_template_id" name="template_id">
                <p id="script_content" name="script_content"></p>
                @include('Rpm::patientEnrollment.callAction')
            </div>
            <div class="modal-footer">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <!-- --> 
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"> Back </button>
                        <button type="button" class="btn btn-primary" id="step"> submit </button> 
                        <button type="button" class="btn btn-primary" id="next_step" style="display: none">Next </button> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

