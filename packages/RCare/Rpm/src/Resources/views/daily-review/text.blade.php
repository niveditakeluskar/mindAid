<div class="row" style="/*width: 108% !important;margin-left: 91px;*/">
    <div class="col-lg-12 mb-3">
        <!-- <div class="mb-3" ><b>Call Wrap up</b></div> -->
        <form id="rpm_text_form_{{$devices[$i]->id}}" name="rpm_text_form_{{$devices[$i]->id}}" class="text_form" action="{{route("rpm.reading.text")}}" method="post"> 
            @csrf
            <?php            
            $conf = getSMSConfigue();
            //echo $patient_providers->practice['practice_group']."tesxt";
               if(isset($patient_providers->practice['practice_group'])){ 
                  $org = getOrganization($patient_providers->practice['practice_group']);   
               }
            // $stage_id = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'daily-review'); 
            ?>
            <input type="hidden" name="uid" value="{{$patient[0]->UID}}" />
            <input type="hidden" name="patient_id" value="{{$patient[0]->id}}" />
            <input type="hidden" name="start_time" value="00:00:00">
            <input type="hidden" name="end_time" value="00:00:00">
            <input type="hidden" name="module_id" value="{{ getPageModuleName() }}" />
            <input type="hidden" name="component_id" value="{{ getPageSubModuleName() }}" />
            <input type="hidden" name="stage_id" value="{{ getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Text') }}" />
            <input type="hidden" name="step_id" value="0">
            <input type="hidden" name="content_title" value="rpm_text_form_{{$devices[$i]->id}}">
            <input type="hidden" name="template_type_id" value="4"> 
            <div class="card">
                <div class="card-body">
                    <div class="textcard" id="{{$devices[$i]->id}}">
                        <button type="button" class="close">×</button>
                    </div>
        
                    @include('Patients::components.text')
                    
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">                          
                            <div class="col-lg-12 text-right" id="{{$devices[$i]->id}}" >
                        <div class="textcard" id="{{$devices[$i]->id}}">
                        <button type="button" dataid="rpm_text_form_{{$devices[$i]->id}}"  class="btn btn_sub  btn-primary m-1" > Submit</button>
                           
                        <button type="button" id="save-text" class="btn btn-outline-secondary m-1 cancel">Cancel</button>
                           </div>
                            </div>                           
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>