<div class="card">
 <form id = "rpm_carenote_form_{{$devices[$i]->id}}" name="rpm_carenote_form_{{$devices[$i]->id}}" class="notes_form" action="{{route("rpm.reading.carenote")}}" method="post"> 
            @csrf
<div class="row justify-content-center">
         <div class="col-md-12">
             <div class="form-group">
            <input type="hidden" name="device_id"  value="{{$devices[$i]->id}}" />
            <input type="hidden" name="device_name"  value="{{$devices[$i]->device_name}}" />
            <input type="hidden" name="patient_id" value="{{$patient[0]->id}}" />
            <input type="hidden" name="uid" value="{{$patient[0]->UID}}" />
            <input type="hidden" name="start_time" value="00:00:00">
            <input type="hidden" name="end_time" value="00:00:00">
            <input type="hidden" name="module_id" value="{{ getPageModuleName() }}" />
            <input type="hidden" name="component_id" value="{{ getPageSubModuleName() }}" />
            <input type="hidden" name="stage_id" value="0" />
            <input type="hidden" name="step_id" value="0">
                <div class="card-body">
                    <label>Care Manager Notes</label> 
                    <textarea name ="CareManagerNotes" id="CareManagerNotes" class="form-control forms-element"></textarea>
                     <div class="invalid-feedback"></div>
                </div>
            </div> 
       </div>
 </div>
 <div class="card-footer">
    <div class="mc-footer">
        <div class="row">                          
            <div class="col-lg-6 text-left" id="gotomm"></div>
                                <div class="col-lg-6 text-right">
                <button type="button" dataid="rpm_carenote_form_{{$devices[$i]->id}}" class="btn  btn-primary m-1" style="">Save</button>
                
            </div>                           
        </div>
    </div>
</div>

</form>
</div>
<!-- style="margin-left:75%;margin-right:10%;" -->
<hr>