<?php
if($enrollinRPM > 1){
    $rmodule_id = $ccmModule;
    $rsubmodule_id = $ccmSubModule;
}
else{
    $rmodule_id = getPageModuleName();
    $rsubmodule_id = getPageSubModuleName();
}
?>
<div class="card">
<form id="relationship_form" name="relationship_form" action="{{ route("monthly.monitoring.call.relationship") }}" method="post"> 
        @csrf
        <div class="card-body">
            
            <input type="hidden" name="uid" value="{{$patient_id}}" />
            <input type="hidden" name="patient_id" value="{{$patient_id}}" />
            <input type="hidden" name="start_time" value="00:00:00">
            <input type="hidden" name="end_time" value="00:00:00">
            <input type="hidden" name="module_id" value="{{ getPageModuleName() }}" />
            <input type="hidden" name="component_id" value="{{ getPageSubModuleName() }}" />
            <input type="hidden" name="stage_id" value="{{ getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Relationship') }}" />
            <input type="hidden" name="hid_stage_id" value="{{ getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Relationship') }}" />
            
            <input type="hidden" name="form_name" value="relationship_form">
            <div class="alert alert-success" id="success-alert" style="display: none;">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong> Relationship data saved successfully! </strong><span id="text"></span>
            </div>
            {{ getRelationshipQuestionnaire($patient_id,$rmodule_id,$rsubmodule_id) }}
           
        </div>
        <div class="card-footer">
            <div class="mc-footer">
                <div class="row">
                    <div class="col-lg-12 text-right">
                        <button type="submit" id= "save-question" class="btn  btn-primary m-1">Next</button>
                        <!-- <button type="submit" id="save_relationship_form" style="display:none" class="btn  btn-primary m-1">Next</button> -->
                    </div>
                </div>
            </div>
        </div>
</form>
</div>