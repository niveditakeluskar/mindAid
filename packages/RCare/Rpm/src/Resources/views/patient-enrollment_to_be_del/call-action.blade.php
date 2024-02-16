<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
            <div class="col-md-12 steps_section"> 
					<div class="form-group ">
                    <input type="hidden" name="hidden_id" id="hidden_id" value="{{$checklist->id}}">
                    <input type="hidden" name="practice_id" id="practice_id" value="{{$checklist->practice_id}}">
                    <div class="row">
                        <div class="col-md-8 mb-4 float-left" id="answer">
                            <select class="custom-select show-tick custom-select" name="content_title" id="call_scripts"  data-toggle="modal" >
                                <option value='0'>Choose Script</option>
                                @foreach($callScripts as $value)
                                <option value="{{ $value->id }}">{{ $value->content_title }}</option>
                                @endforeach
                            </select>
                            <span style="display:none;color:red" id="CallError">Select the Call Script</span>
                        </div>
                        <div class="col-md-12">
						    <p id="script"></p>
                        </div>
                    </div> 

<div class="separator-breadcrumb border-top"></div>
<div class="row pl-3" id="action">
    <label class="radio radio-primary col-md-2">
        <input type="radio" id="role1" name="role" value="1" formcontrolname="radio">
        <span>Agreed to Enroll</span>
        <span class="checkmark"></span>
    </label>
    <label class="radio radio-primary col-md-4">
        <input type="radio" id="role2" name="role" value="2" formcontrolname="radio">
        <span>Asked to Be Called Back to Delibrate & Decide</span>
        <span class="checkmark"></span>
    </label>
    <label class="radio radio-primary col-md-4">
        <input type="radio" id="role3" name="role" value="3" formcontrolname="radio" onchange="stepSecond(7);">
        <span>Refused</span> 
        <span class="checkmark"></span> 
    </label>
    <span style="display:none;color:red" id="CheckError">Select the Checkbox</span>
</div>
<div class="row" id="date-time" style="display: none">
    <div class="col-md-6"> 
        <label>Select Date</label>
        <input type="date" class="form-control" id="date" name="date">
    </div>
    <div class="col-md-6 float-left">     
        <label>Enter Time</label>  
        <input type="time" class="form-control" id="time" name="time">
    </div>
</div>


</div>
</div>				
</div>
<div class="modal-footer">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-secondary" onclick="backStep(1)"> Back </button>
                        <button type="button" class="btn btn-primary" id="next_step" > Next </button>
                        <button type="button" class="btn btn-primary" id="step" style="display:none"> Next </button>
                    </div>
                </div>
            </div>
           
</div>
</div>
</div>

<script type="text/javascript">
   $(document).ready(function(){
        $('#action input').on('change', function() {
            var action = $('input[name=role]:checked', '#action').val(); 
            if (action=='2'){
            	$("#date-time").show();
            }else{
            	$("#date-time").hide();
            }
        });
    });  
</script>