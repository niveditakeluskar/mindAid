@extends('Theme::layouts.master')
@section('page-css')

  <script src="https://cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script> 
<!-- <link rel="stylesheet" href="{{asset('assets/styles/vendor/quill.bubble.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/quill.snow.css')}}"> -->
@endsection

@section('main-content')
<div class="breadcrusmb">
        <div class="row">
            <div class="col-md-10">
                <h4 class="card-title mb-3">Update Content Template</h4>
            </div>
            <div class="col-md-1">
                <?php 
                    $module_name = \Request::segment(1);
                    if($module_name == 'rpm') {
                ?>
                        <!-- <a class="btn btn-success btn-sm" href="{{ route("rpm-content-template") }}" ><i class="i-Back1"></i></a> -->
                        <button name="content_print" id="content-printpdf" class="btn btn-danger btn-lg btn-block">Print</button>

                <?php
                    } else if($module_name == 'ccm') {
                ?>
                      <!--   <a class="btn btn-success btn-sm " href="{{ route("ccm-content-template") }}" ><i class="i-Back1"></i></a> -->
                        <button name="content_print" id="content-printpdf" class="btn btn-danger btn-lg btn-block">Print</button>
                <?php  
                    } else if($module_name == 'patients') {
                ?>
                      <!--   <a class="btn btn-success btn-sm " href="{{ route("patients-content-template") }}" ><i class="i-Back1"></i></a> -->
                        <button name="content_print" id="content-printpdf" class="btn btn-danger btn-lg btn-block">Print</button>
                <?php  
                    }
                ?>
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
                <?php
                    // var_dump($data);
                    if($data->template_type_id) { 
                        $content = json_decode($data->content);
                    }
                ?>
                <!--   <form class="" method="post" action="{{ route('save-template') }}"> -->
                <form class="" method="post" action="" id="updatetemplate-form" name="updatetemplate-form"> 
                    @csrf
                    @hidden("edit",["id"=>"edit", "value"=>"edit"])
                    @hidden("temp_id",["id"=>"temp_id", "value"=>"$data->id"])
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class=""><span class="error">*</span> Content Title</label>
                                @text("content_title", ["id"=>"content_title", "value"=>"$data->content_title"])
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label class=""><span class="error">*</span> Content Type</label>
                                @selectContentType("template_type",["id"=>"template_type"])
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="loginuser" class=""><span class="error">*</span>Module</label>
                                @selectMasterModule("module",["id"=>"module", "disabled"=>"disabled"])
                            </div> 
                            <div class="col-md-6 form-group mb-3">
                                <label for="sub_module" class=""><span class="error">*</span> Sub Module</label>
                                @select("Sub Module", "sub_module", [], ["id" => "sub_module"])
                            </div>   
                            <div class="col-md-6 form-group mb-3">
                                <label>Stage</label>
                                @select("Stage", "stages", [], ["id" => "stages"])
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label>Step</label>
                                @select("Step", "stage_code", [], ["id" => "stage_code"])
                            </div>
                            <div class="col-md-6 form-group mb-3" id="from">
                                <label><span class="error">*</span> From</label>
                                @text("from", ["id" => "from", "placeholder" => "Enter Email Id","value"=>"$content->from"])
                            </div>
                            <div class="col-md-6 form-group mb-3" id="sub">
                                <label><span class="error">*</span> Subject</label>
                                @text("subject", ["id" => "subject", "placeholder" => "Enter Subject",  "value"=>"$content->subject"])
                            </div> 
                            <div class="col-md-6 form-group mb-3">
                                <label>Devices</label>
                                @selectDevice("devices",["id"=>"devices"])
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
    $('#update').click(function(){
		for ( instance in CKEDITOR.instances )
				CKEDITOR.instances[instance].updateElement();
			
			
        var editor = CKEDITOR.instances['editor'].getData();
        var pathArray = window.location.pathname.split('/');
        var module_id = {{$data->module_id}};
        var url = window.location.protocol + "//" + window.location.host + "/" + pathArray[1] + "/save-template";
        var sub_module = $("#sub_module").val();
        if(sub_module=="Select Sub Module"){
            sub_module="";
        }
        var data = $('#updatetemplate-form').serialize()+"&editorData="+editor+"&module="+module_id+"&sub_module="+sub_module;
		// ;
        // console.log("data"+data);
        $.ajax({
            type: 'post',
            url: url,
            data: data, // getting filed value in serialize form
            success: function(response){
                $(".alert").removeClass('alert-danger');
                console.log(response);
                if($.trim(response) == 'success') {
                    $(".alert").addClass('alert-success');
                    $(".user-msg").text("Template data updated successfully!");
                    $(".alert").show(); 
                    $('#editorError').css("display", "none");
                    

                    var secondLevelLocation = url.split('/').reverse()[2];
                    if(secondLevelLocation == 'rpm') {
                        var redirect_url = "{{ route('rpm-content-template') }}";  
                        window.location = redirect_url;
                    } else if(secondLevelLocation == 'ccm'){
                        var redirect_url = "{{ route('ccm-content-template') }}"; 
                        window.location = redirect_url;
                    } else if(secondLevelLocation == 'patients'){
                        var redirect_url = "{{ route('patients-content-template') }}";
                        window.location = redirect_url;
                    }
                }
                // document.getElementById("updatetemplate-form").reset();
            },
            error: function (request, status, error) {
                $(".alert").removeClass('alert-success');
                console.log(request.responseJSON.errors);
                if(request.responseJSON.errors !== undefined) {
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
                        $(".user-msg").text("Please fill all Mandatory Fields!");
                        $(".alert").show(); 

                        var scrollPos = $(".main-content").offset().top;
                         $(window).scrollTop(scrollPos);
                }
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

    $("#template_type").change(function(){ 
        var selected = $(this).val();
        if(selected != 1){
            $('#from').hide();
            $('#sub').hide();
        };
        if(selected == 1){
            $('#from').show();
            $('#sub').show();
        }
    });
    
    $(document).ready(function(){
        var module_id = <?php echo ($data->module_id!='' && $data->module_id != null) ? $data->module_id : '0'; ?>;
        var submodule_id = <?php echo ($data->component_id!='' && $data->component_id != null) ? $data->component_id : '0'; ?>;
        var stage_id = <?php echo ($data->stage_id!='' && $data->stage_id != null) ? $data->stage_id : '0'; ?>;
        var stage_code = <?php echo ($data->stage_code!='' && $data->stage_code != null) ? $data->stage_code : '0'; ?>;
        var template_type = <?php echo ($data->template_type_id!='' && $data->template_type_id != null) ? $data->template_type_id : '0'; ?>;
        var device_id = <?php echo ($data->device_id!='' && $data->device_id != null) ? $data->device_id : '0'; ?>;
        $('#module').val(module_id);
        $('#template_type').val(template_type);
        $('#devices').val(device_id);
        util.updateSubModuleList(parseInt(module_id), $("#sub_module"), parseInt(submodule_id));
        util.updateStageList(parseInt(submodule_id), $("#stages"), parseInt(stage_id));
        util.updateStageCodeList(parseInt(stage_id), $("#stage_code"), parseInt(stage_code));
        util.getToDoListData(0, {{getPageModuleName()}});
        
        if(template_type != 1){
            $('#from').hide();
            $('#sub').hide();
        };
        if(template_type == 1){
            $('#from').show();
            $('#sub').show();
        }
        // pdf print 
        $('#content-printpdf').click(function(){
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
            // window.open('/ccm/print-content-template/'+id, '_blank');
        });
    });
</script>
@endsection