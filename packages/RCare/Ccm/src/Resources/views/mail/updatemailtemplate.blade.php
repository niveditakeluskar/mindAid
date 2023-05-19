@extends('Theme::layouts.master')
@section('page-css')

<script src="https://cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>
<!-- <link rel="stylesheet" href="{{asset('assets/styles/vendor/quill.bubble.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/quill.snow.css')}}"> -->
@endsection

@section('main-content')
<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <div class="alert" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong class="user-msg"></strong><span id="text"></span>
                </div>
                <?php
                    // var_dump($data);
                    if($data->template_type_id) { 
                        $content = json_decode($data->content);
                    }
                ?>
                <!--   <form class="" method="post" action="{{ route('CcmSaveTemplate') }}"> -->
                <form class="" method="" action="" id="updatetemplate-form" name="updatetemplate-form"> 
                    @csrf
                    @hidden("edit",["id"=>"edit", "value"=>"edit"])
                    @hidden("temp_id",["id"=>"temp_id", "value"=>"$data->id"])
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class=""><span class="error">*</span> Content Title</label>
                                @text("content_title", ["id"=>"content_title", "class"=>"col-md-6", "value"=>"$data->content_title"])
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label class=""><span class="error">*</span> Content Type</label>
                                <select class="custom-select col-md-6" name="template_type" id="template_type">
                                    <option value="">Select Content Type...</option>
                                    @foreach($type as $value)
                                    <option value="{{ $value->id }}" <?php if($data->template_type_id == $value->id){echo 'selected';} ?>>{{ $value->template_type }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="loginuser" class=""><span class="error">*</span>Module</label>
                                @selectOrgModule("module",["id"=>"module", "class"=>"col-md-6"])
                            </div> 
                            <div class="col-md-6 form-group mb-3">
                                <label for="sub_module" class=""><span class="error">*</span> Sub Module</label>
                                @select("Sub Module", "sub_module", [], ["id" => "sub_module", "class"=>"custom-select col-md-6"])
                            </div>   
                            <div class="col-md-6 form-group mb-3">
                                <label><span class="error">*</span> Stage</label>
                                @select("Stage", "stages", [], ["id" => "stages", "class"=>"col-md-6"])
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label>Stage Code</label>
                                @select("Stage Code", "stage_code", [], ["id" => "stage_code", "class"=>"custom-select col-md-6"])
                            </div>
                            <div class="col-md-6 form-group mb-3" id="from">
                                <label><span class="error">*</span> From</label>
                                @text("from", ["id" => "from", "placeholder" => "Enter Email Id", "class" => "form-control col-md-6", "value"=>"$content->from"])
                            </div>
                            <div class="col-md-6 form-group mb-3" id="sub">
                                <label><span class="error">*</span> Subject</label>
                                @text("subject", ["id" => "subject", "placeholder" => "Enter Subject", "class" => "form-control col-md-6", "value"=>"$content->subject"])
                            </div> 
                            <div class="col-md-6 form-group mb-3">
                                <label>Devices</label>
                                <select class="custom-select col-md-3" name="devices" id="devices" >
                                    <option value="">Select Device</option>
                                    @foreach($devices as $value)
                                    <option value="{{ $value->id }}" <?php if($data->device_id == $value->id){ echo 'selected'; } ?>>{{ $value->device_name }}</option>
                                    @endforeach
                                </select>
                            </div>  
                        </div>
                    </div>
                    <h1>Content</h1>
                    <textarea name="content" id="editor" cols="30" rows="10">{{ $content->message }}</textarea>
                    <div id="editorError" class="invalid-feedback"></div>
                    <br>
                    <button  name="edit" id="update" class="btn btn-info btn-lg btn-block">Update</button> 
                </form>
            </div>
        </div>
    </div>  
</div>             
@endsection
@section('page-js')
<script>
        CKEDITOR.replace( 'editor');
</script>
<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
<script src="{{asset('assets/js/vendor/quill.min.js')}}"></script> -->
<script>
    $(document).ready(function(){
        var module_id = {{$data->module_id}};
        var submodule_id = {{$data->component_id}};
        var stage_id = {{$data->stage_id}};
        var stage_code = {{$data->stage_code}};
        $('#module').val(module_id);
        util.updateSubModuleList(parseInt(module_id), $("#sub_module"), parseInt(submodule_id));
        util.updateStageList(parseInt(submodule_id), $("#stages"), parseInt(stage_id));
        util.updateStageCodeList(parseInt(stage_id), $("#stage_code"), parseInt(stage_code));

        var selected = $('#template_type').val();
        if(selected != 1){
            $('#from').hide();
            $('#sub').hide();
        };
        if(selected == 1){
            $('#from').show();
            $('#sub').show();
        }
        $(template_type).change(function(){ 
            var selected = $('#template_type').val();
            if(selected != 1){
                $('#from').hide();
                $('#sub').hide();
            };
            if(selected == 1){
                $('#from').show();
                $('#sub').show();
            }
        }); 

        $("[name='module']").on("change", function () {
            util.updateSubModuleList(parseInt($(this).val()), $("#sub_module"));
        });

        $("[name='sub_module']").on("change", function () {
            util.updateStageList(parseInt($(this).val()), $("#stages"));
        });

        $("[name='stages']").on("change", function () {
            util.updateStageCodeList(parseInt($(this).val()), $("#stage_code"));
        });

        $('#update').click(function(){
            var editor = CKEDITOR.instances['editor'].getData();
            $.ajax({
                type: 'post',
                url: '/ccm/CcmSaveTemplate',
                data: $('#updatetemplate-form').serialize() +"&editorData="+editor, // getting filed value in serialize form
                success: function(response){
                    $(".alert").removeClass('alert-danger');
                    console.log(response);
                    if($.trim(response) == 'success') {
                        $(".alert").addClass('alert-success');
                        $(".user-msg").text("Template data updated successfully!");
                        $(".alert").show(); 
                        $('#editorError').css("display", "none");
                    }
                    // document.getElementById("updatetemplate-form").reset();
                },
                error: function (request, status, error) {
                    $(".alert").removeClass('alert-success');
                    console.log(request.responseJSON.errors);
                    if(request.responseJSON.errors.content_title) {
                        $('[name="content_title"]').addClass("is-invalid");
                        $('[name="content_title"]').next(".invalid-feedback").html(request.responseJSON.errors.content_title);
                    } else {
                        $('[name="content_title"]').removeClass("is-invalid");
                        $('[name="content_title"]').next(".invalid-feedback").html('');
                    }

                    if(request.responseJSON.errors.template_type) {
                        $('[name="template_type"]').addClass("is-invalid");
                        $('[name="template_type"]').next(".invalid-feedback").html(request.responseJSON.errors.template_type);
                    } else {
                        $('[name="template_type"]').removeClass("is-invalid");
                        $('[name="template_type"]').next(".invalid-feedback").html('');
                    }

                    if(request.responseJSON.errors.module) {
                        $('[name="module"]').addClass("is-invalid");
                        $('[name="module"]').next(".invalid-feedback").html(request.responseJSON.errors.module);
                    } else {
                        $('[name="module"]').removeClass("is-invalid");
                        $('[name="module"]').next(".invalid-feedback").html('');
                    }

                    if(request.responseJSON.errors.stages) {
                        $('[name="stages"]').addClass("is-invalid");
                        $('[name="stages"]').next(".invalid-feedback").html(request.responseJSON.errors.stages);
                    } else {
                        $('[name="stages"]').removeClass("is-invalid");
                        $('[name="stages"]').next(".invalid-feedback").html('');
                    }

                    if(request.responseJSON.errors.from) {
                        $('[name="from"]').addClass("is-invalid");
                        $('[name="from"]').next(".invalid-feedback").html(request.responseJSON.errors.from);
                    } else {
                        $('[name="from"]').removeClass("is-invalid");
                        $('[name="from"]').next(".invalid-feedback").html('');
                    }

                    if(request.responseJSON.errors.subject) {
                        $('[name="subject"]').addClass("is-invalid");
                        $('[name="subject"]').next(".invalid-feedback").html(request.responseJSON.errors.subject);
                    } else {
                        $('[name="subject"]').removeClass("is-invalid");
                        $('[name="subject"]').next(".invalid-feedback").html('');
                    }

                    if(request.responseJSON.errors.sub_module) {
                        $('[name="sub_module"]').addClass("is-invalid");
                        $('[name="sub_module"]').next(".invalid-feedback").html(request.responseJSON.errors.sub_module);
                    } else {
                        $('[name="sub_module"]').removeClass("is-invalid");
                        $('[name="sub_module"]').next(".invalid-feedback").html('');
                    }

                    if(request.responseJSON.errors.editorData) {
                        $('#editorError').html("Content field is required");
                        $('#editorError').show();
                    } else {
                        $('#editorError').hide();
                    }

                    $(".alert").addClass('alert-danger');
                    $(".user-msg").text("Something went wrong, please fill form correctly!");
                    $(".alert").show(); 

                    // if(request.responseJSON.errors.content) {
                    //     $('[name="content"]').addClass("is-invalid");
                    //     $('[name="content"]').next(".invalid-feedback").html(request.responseJSON.errors.content);
                    // } else {
                    //     $('[name="content"]').removeClass("is-invalid");
                    //     $('[name="content"]').next(".invalid-feedback").html('');
                    // }
                }
            });
            // to prevent refreshing the whole page page
            return false;
        });

        //     $('#update').click(function(){ 
        //         alert( $("#snow-editor").html($("p").text()));
        //         $("#hiddenArea").val($("#snow-editor").html($("p").text()));
        // }); 
            // var html = myEditor.children[0].innerHTML;
            // html;
            // var myEditor = document.querySelector('#snow-editor');
            // var html = myEditor.children[0].innerHTML;
    });
</script>
@endsection