@extends('Theme::layouts.master')
@section('page-css')
    <script src="https://cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>
@endsection

@section('main-content')
    <div class="breadcrusmb">
        <div class="row">
            <div class="col-md-10">
                <h4 class="card-title mb-3">Add Content Template</h4>
            </div>
            <div class="col-md-1">
                <a class="btn btn-success btn-sm " href="listmailtemplate" >Content Template</a>  
            </div> 
        </div>
        <!-- ss -->             
    </div>
    <div class="separator-breadcrumb border-top"></div>
    <div class="row mb-4">
        <div class="col-md-12 mb-4">
            <div class="card text-left">
                <div class="card-body">
                    <div class="alert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong class="user-msg"></strong><span id="text"></span>
                    </div>
                    <!-- <form class="" method="post" action="{{-- route('CcmSaveTemplate') --}}">  -->
                    <form class="" method="" action="{{-- route('CcmSaveTemplate') --}}" name="saveccmtemplate-form" id="saveccmtemplate-form"> 
                    <!-- <form action="{{ route("CcmSaveTemplate") }}" method="post" name="add_ccm_content_template_form"> -->
                        {{ csrf_field() }}
                        @hidden("add",["id"=>"add", "value"=>"add"])
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label class=""><span class="error">*</span> Content Title</label>
                                    @text("content_title", ["id"=>"content_title", "class"=>"col-md-6"])
                                </div> 
                                <div class="col-md-6 form-group mb-3">
                                    <label class=""><span class="error">*</span> Content Type</label>
                                    <select class="custom-select col-md-6" name="template_type" id="template_type">
                                        <option value="">Select Content Type...</option>
                                        @foreach($data as $value)
                                        <option value="{{ $value->id }}">{{ $value->template_type }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>  
                                <div class="col-md-6 form-group mb-3">
                                    <label for="loginuser" class=""><span class="error">*</span>Module</label>
                                    @selectOrgModule("module",["id"=>"module", "class"=>"col-md-6"])
                                </div> 
                                <div class="col-md-6 form-group mb-3">
                                    <label for="loginuser" class=""><span class="error">*</span> Sub Module</label>
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
                                    @text("from", ["id" => "from", "placeholder" => "Enter Email Id", "class" => "form-control col-md-6"])
                                </div>
                                <div class="col-md-6 form-group mb-3" id="sub">
                                    <label><span class="error">*</span> Subject</label>
                                    @text("subject", ["id" => "subject", "placeholder" => "Enter Subject", "class" => "form-control col-md-6"])
                                </div>
                            </div>
                        </div>
                        <h1>Content</h1>
                        <textarea name="content" id="editor" cols="30" rows="10"></textarea>
                        <div id="editorError" class="invalid-feedback"></div>
                        <br>
                        <button type="" id="save-template" class="btn btn-info btn-lg btn-block">Save</button> 
                    </form>
                </div>
            </div>
        </div>
    </div>             
@endsection
@section('page-js')
    <script>
        CKEDITOR.replace( 'editor');
        $('#save-template').click(function(e){
            // var messageLength = CKEDITOR.instances['editor'].getData().replace(/<[^>]*>/gi, '').length;
            // if( !messageLength ) {
            //     $(".alert").addClass('alert-danger');
            //     $(".user-msg").text("Something went wrong, please fill form correctly!");
            //     $(".alert").show();
            //     break;
            //     // $('[name="content"]').addClass("is-invalid");
            //     // $('[name="content"]').next(".invalid-feedback").html("Content field is required");
            //     // e.preventDefault();
            // } 
            // // else {
            // //     // $('[name="content"]').removeClass("is-invalid");
            // //     // $('[name="content"]').next(".invalid-feedback").html('');
            // // }
            var editor = CKEDITOR.instances['editor'].getData();
            $.ajax({
                type: 'post',
                url: '/ccm/CcmSaveTemplate',
                data: $('#saveccmtemplate-form').serialize() + "&editorData="+editor, // getting filed value in serialize form
                success: function(response){
                    if($.trim(response) == 'success') {
                        $(".alert").addClass('alert-success');
                        $(".user-msg").text("Template data added successfully!");
                        $(".alert").show();
                        $('#editorError').css("display", "none");
                        document.getElementById("saveccmtemplate-form").reset();
                    }
                },
                error: function (request, status, error) {
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

        $("[name='module']").on("change", function () {
            util.updateSubModuleList(parseInt($(this).val()), $("#sub_module"));
        });

        $("[name='sub_module']").on("change", function () {
            util.updateStageList(parseInt($(this).val()), $("#stages"));
        });

        $("[name='stages']").on("change", function () {
            util.updateStageCodeList(parseInt($(this).val()), $("#stage_code"));
        });

        $(document).ready(function(){
            $('#from').hide();
            $('#sub').hide();
            $(template_type).change(function(){ 
                var selected = $('#template_type').val();
                if(selected != 1){
                    $('#from').hide();
                    $('#sub').hide();
                }
                if(selected == 1){
                    $('#from').show();
                    $('#sub').show();
                }
            });
        });
    </script>
@endsection