@extends('Theme::layouts.master')
@section('page-css')

@endsection

@section('main-content')
<?php
$module_id = getPageModuleName();
$stage_id = getFormStagesId($module_id, 'Relationship');
?>
<div class="breadcrusmb">

  <div class="row">
                <div class="col-md-11">
                   <h4 class="card-title mb-3">Copy Questionnaire Template</h4>
                </div>
                
              </div>          
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="alert alert-success general_success" id="success-alert" style="display: none;">
	<button type="button" class="close" data-dismiss="alert">x</button>
	<strong> Template Save successfully! </strong><span id="text"></span>
</div>
<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <form name="copy_question_form" id="copy_question_form" >
            @csrf
                <div class="card-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3 form-group mb-3">
                                <label class="">Content Title <span class="error">*</span></label>
                                @text("content_title", ["id"=>"content_title"])
                            </div> 
                            <div class="col-md-3 form-group mb-3">
                                <label for="loginuser" class="">Module <span class="error">*</span></label> 
                                 @selectMasterModule("module",["id"=>"module", "disabled"=>"disabled"])
                            </div>
                            <div class="col-md-3 form-group mb-3">
                                <label for="loginuser" class="">Sub Module <span class="error">*</span></label>
                                @select("Sub Module", "sub_module", [], ["id" => "sub_module", "class"=>"custom-select"])
                            </div>
                            <div class="col-md-3 form-group mb-3">
                                <label>Stage</label>
                                @select("Stage", "stages", [], ["id" => "stages"])
                            </div> 
                        </div>
                    </div>    

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label>From Step</label>     
                                @select("Step", "from_stage_code", [], ["id" => "from_stage_code", "class"=>"custom-select select2 capital-first"])
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label>To Step</label>     
                                @select("Step", "to_stage_code", [], ["id" => "to_stage_code", "class"=>"custom-select select2 capital-first"])
                            </div>
                        </div>
                    </div> 
                    <div id="list_of_template">
                        <ul id="template-list" class="list-unstyled"></ul>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="button" name="tree" class="btn  btn-primary m-1" id="copy-tree">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>         

@endsection
@section('page-js')
<script>
$(document).ready(function(){        
        var module_id    = '{{ getPageModuleName() }}';
        $("#module").val(module_id);
        var stage_id     = '{{ $stage_id }}';
        util.updateSubModuleList(parseInt(module_id), $("#sub_module"));
        $("[name='sub_module']").on("change", function () {
            util.updateStageList(parseInt($(this).val()), $("#stages"));
            util.updateTemplateLists(module_id, parseInt($(this).val()), $("#copy_from"), 5);
        });

        $("[name='stages']").on("change", function () {
            util.updateStageCodeList(parseInt($(this).val()), $("#from_stage_code"));
            util.updateStageCodeList(parseInt($(this).val()), $("#to_stage_code"));
        });

        //util.updateStageCodeList(stage_id, $("#from_stage_code"));
        //.updateStageCodeList(stage_id, $("#to_stage_code"));
    }); 
    $("[name='from_stage_code']").on("change", function () {
        var module_id    = '{{ getPageModuleName() }}';
        if(parseInt($(this).val()) > 0){
            util.updateTemplateList(module_id, parseInt($(this).val()), 5, $("#list_of_template"));
        }else{
            util.updateTemplateList(module_id, -1, 5, $("#list_of_template"));
        }
    });

    $("#copy-tree").on("click", function () {
        var step_name = $('#to_stage_code option:selected').text();
        var from = $("#from_stage_code").val();
        var to = $("#to_stage_code").val();
        var module_id = '{{ getPageModuleName() }}';
        $.ajax({
                    type: 'post',
                    url: '/ccm/copy-dtemplate',
                    data: $("form#copy_question_form").serialize() + '&from=' + from + '&to=' + to + '&module_id=' + module_id + '&step_name=' + step_name,
                    success: function(response) {
                        $('.general_success').show();
                        $("#from_stage_code").val("").change();
                        $("#to_stage_code").val("");
                        setTimeout(function(){ $('.general_success').fadeOut(); }, 3000);
                    },
                });
    });
  
</script>
@endsection 