<div class="form-group">   
    <div class="row mt-2 mb-4">
        <div class="col-md-12">    
            <div class="card">
                <div class="card-body card text-left">
                <input type="hidden" name="contact_via" id="contact_via" value="">
                    <div class="row">
                        <div class="col-md-4 float-left">
                            <label for="Status">Review Data</label></b>
                            @selectreviewdata("review_data",["id" => "review_data", "class" => ""])
                        </div>
                        <div class="col-md-4 text-center" id="buttons" style="display:none">
                            <button type="button" class="btn btn-primary btn-icon btn-lg m-1 mt-3" id="text">
                                <span class="ul-btn__icon"><i class="i-Letter-Open"></i></span>
                                <span class="ul-btn__text">Text</span> 
                            </button>
                            <button type="button" class="btn btn-primary btn-icon btn-lg m-1 mt-3" id="calling">
                                <span class="ul-btn__icon"><i class="i-Old-Telephone"></i></span>
                                <span class="ul-btn__text">Call</span>
                            </button>
                        </div>  
                        <div class="col-md-8 text-center" id="questionnaireButtons" style="display:none; margin-top: 22px;" [formGroup]="radioGroup">
                            <div class="row">
                                <label class="radio radio-primary col-md-4 offset-md-1">
                                    <input type="radio" name="patient_condition" value="1" formControlName="radio" id="office_range" data-toggle="modal">
                                    <span>Patient Condition - In Call PCP Office Range</span>
                                    <span class="checkmark"></span>
                                </label>
                                <label class="radio radio-primary col-md-4 offset-md-1">
                                    <input type="radio" name="patient_condition" value="2" formControlName="radio" id="emergency_range" data-toggle="modal">
                                    <span>Patient Condition - In Emergency Range</span>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>                                 
    </div>                            
</div>
