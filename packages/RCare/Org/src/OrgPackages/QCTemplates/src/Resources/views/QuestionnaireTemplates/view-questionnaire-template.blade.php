@extends('Theme::layouts.master')
@section('main-content')
    <div class="breadcrusmb">
        <div class="row">
            <div class="col-md-10">
                <h4 class="card-title mb-3">Questionnaire Template Details</h4>
            </div>
            <div class="col-md-1">
                <?php 
                    $module_name = \Request::segment(1);
                    if($module_name == 'rpm') {
                ?>
                        <!-- <a class="btn btn-success btn-sm" href="{{ route("rpm-questionnaire-template") }}" >Questionnaire Template</a> -->
                        <button name="questionnaire_print" id="viewquestionnaire-print" class="btn btn-danger btn-lg btn-block">Print</button>
                <?php
                    } else if($module_name == 'ccm') {
                ?>
                        <!-- <a class="btn btn-success btn-sm " href="{{ route("ccm-questionnaire-template") }}" >Questionnaire Template</a> -->
                        <button name="questionnaire_print" id="viewquestionnaire-print" class="btn btn-danger btn-lg btn-block">Print</button>
                <?php  
                    } else if($module_name == 'patients') {
                ?>
                        <!-- <a class="btn btn-success btn-sm " href="{{ route("patients-questionnaire-template") }}" >Questionnaire Template</a> -->
                        <button name="questionnaire_print" id="viewquestionnaire-print" class="btn btn-danger btn-lg btn-block">Print</button>
                <?php  
                    }
                ?>
            </div> 
        </div>
        <!-- ss -->             
    </div>
    <div class="separator-breadcrumb border-top"></div>
    <!-- end Solid Bar -->
    <div class="row mb-4">
        <div class="col-md-12 mb-4">
            <div class="card">                           
                <div class="card-body">
                <div>
                        <div class="form-row">
                            @hidden("temp_id",["id"=>"temp_id", "value"=>"$data->id"])
                            <div class="form-group col-md-6">
                                <strong class="mr-1">Content Name</strong>	
                                {{$data->content_title }}
                            </div> 
                            <div class="form-group col-md-6">
                                <strong class="mr-1">Content Type</strong>	
                                <!-- Questionnaire	 -->
                                {{ $type[0]->template_type }}
                                {{--$data->module_id --}}
                            </div>
                        </div> 
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <strong class="mr-1">Modules</strong>	
                                {{ $module[0]->module }}
                            </div> 
                            <div class="form-group col-md-6">
                                <strong class="mr-1">Sub Modules</strong>	
                                {{ $components[0]->components }}
                            </div> 
                        </div> 
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <strong class="mr-1">Stages</strong>
                                @if(isset($stage[0]->description) && $stage[0]->description != "")
                                    {{ $stage[0]->description }}
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <strong class="mr-1">Step</strong>
                                @if(isset($stageCode[0]->description) && $stageCode[0]->description != "")
                                    {{ $stageCode[0]->description }}
                                @endif
                            </div>
                        </div> 
                    </div>
                    <div class="separator-breadcrumb border-top"></div>
                    <?php
                        $queData = json_decode($data['question']);     
                        $questionnaire = $queData->question->q;     
                    ?>
                        @foreach($questionnaire as $value)
                            <div class="form-row">
                                <div class="form-group col-md-6" id="from">
                                    <strong class="mr-1">Question</strong>
                                    @if(!empty($value->answerFormat)){{$value->questionTitle}}@endif
                                    <p class="text-muted m-0"></p>
                                </div> 
                                <div class="form-group col-md-6" id="sub">
                                    <strong class="mr-1">Answer Type</strong>	
                                    @if(!empty($value->answerFormat)){{config("form.answer_format")[$value->answerFormat]}}@endif
                                    <p class="text-muted m-0"></p>
                                </div>
                                <div class="form-group col-md-6">
                                    @if(isset($value->label))
                                    <strong class="mr-1 label">Label</strong> 
                                    @foreach($value->label as $labels)
                                        <span>{{$labels}}</span>
                                    @endforeach
                                    <p class="text-muted m-0"></p>
                                    @endif
                                </div> 
                            </div>  
                            <div class="separator-breadcrumb border-top"></div>
                        @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-js')
<script>
    $(document).ready(function() {
        util.getToDoListData(0, {{getPageModuleName()}}); 
    });
    // pdf print
    $('#viewquestionnaire-print').click(function(){
        var id=$('#temp_id').val();
        var url = window.location.pathname;
        var secondLevelLocation = url.split('/').reverse()[2];
        if(secondLevelLocation == 'rpm') {
            window.open('/rpm/print-content-template/'+id, '_blank');
        } else if(secondLevelLocation == 'ccm'){
            window.open('/ccm/print-content-template/'+id, '_blank');
        } else if(secondLevelLocation == 'patients'){
            window.open('/patients/print-content-template/'+id, '_blank');
        }
    });
</script>