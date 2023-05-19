<div class="row" style="display: none;" id="email_step_div">
    <div class="col-lg-12 mb-3">        
        <form id="email_form" name="email_form" action="{{ route("patient.enrollment.email") }}" method="post"> 
            @csrf
            <input type="hidden" name="patient_id" value="{{$patient[0]->id}}" />
            <input type="hidden" name="uid" value="{{$patient[0]->UID}}" />
            <input type="hidden" name="start_time" value="00:00:00">
            <input type="hidden" name="end_time" value="00:00:00">
            <input type="hidden" name="module_id" value="{{ getPageModuleName() }}" />
            <input type="hidden" name="component_id" value="{{ getPageSubModuleName() }}" />
            <input type="hidden" name="stage_id" value="{{ getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Email') }}" />
            <input type="hidden" name="step_id" value="0">
            <input type="hidden" name="form_name" value="enrollment_email_form">
            <input type="hidden" name="template_type_id" value=""> 
            <input type="hidden" name="content_title" id="email_content_title">
            <div class="card">
                <div class="card-body">
                    @include('Theme::components.email')
                </div>

                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit" id="save-email" class="btn  btn-primary m-1">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>