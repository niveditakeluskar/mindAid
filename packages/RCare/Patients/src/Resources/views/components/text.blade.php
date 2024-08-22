<div class="row">
    <div class="col-lg-12 mb-3">
        <!-- <div class="mb-3" ><b>Call Wrap up</b></div> -->
        <form id="text_form" name="text_form" action="{{ route("monthly.monitoring.text") }}" method="post"> 
            @csrf

            <?php 
               $module_id    = 3;//getPageModuleName();
               $submodule_id = 19; //getPageSubModuleName();
               $stage_id =  getFormStageId($module_id , $submodule_id, 'Text');
            ?>
            <input type="hidden" name="uid" value="{{$patient_id}}" />
            <input type="hidden" name="patient_id" value="{{$patient_id}}" />
            <input type="hidden" name="start_time" value="00:00:00">
            <input type="hidden" name="end_time" value="00:00:00">
            <input type="hidden" name="module_id" value="{{ $module_id}}" />
            <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
            <input type="hidden" name="stage_id" value="{{ $stage_id }}" />
            <input type="hidden" name="step_id" value="0">
            <input type="hidden" name="form_name" value="text_form">
            <input type="hidden" name="content_title" value="text_form">
            <input type="hidden" name="template_type_id" value=""> 
            <div class="card">
                <div class="card-body">
                    @include('Theme::components.text')
                    
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit" id="save-text" class="btn  btn-primary m-1">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @include('Messaging::text-history')
    </div>
</div>