@extends('Theme::layouts.master')
@section('page-css')
    <!--script src="https://cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script-->
    <script src="https://cdn.ckeditor.com/4.14.0/basic/ckeditor.js"></script>
@endsection

@section('main-content')
    <div class="breadcrusmb">
        <div class="row">
            <div class="col-md-10">
                <h4 class="card-title mb-3">Add Content Template</h4>
            </div>
            <div class="col-md-1">
                <?php 
                    $module_name = \Request::segment(1);
                    if($module_name == 'rpm') {
                ?>
                        <a class="btn btn-success btn-sm" href="{{ route("rpm-content-template") }}" >Content Template</a>
                <?php
                    } else if($module_name == 'ccm') {
                ?>
                        <a class="btn btn-success btn-sm " href="{{ route("ccm-content-template") }}" >Content Template</a>
                <?php  
                    } else if($module_name == 'patients') {
                ?>
                        <a class="btn btn-success btn-sm " href="{{ route("patients-content-template") }}" >Content Template</a>
                <?php  
                    }
                ?>
            </div> 
        </div>
        <!-- ss -->             
    </div>
    <div class="separator-breadcrumb border-top" id="app"></div>
    <div class="row mb-4">
        <div class="col-md-12 mb-4">
            <div class="card text-left">
                <div class="card-body">
                    <div class="alert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong class="user-msg"></strong><span id="text"></span>
                    </div>
                    <!-- <form class="" method="post" action="{{-- route('save-template') --}}">  -->
                    <form class="" method="post" action="{{-- route('save-template') --}}" name="saveccmtemplate-form" id="saveccmtemplate-form"> 
                    <!-- <form action="{{ route("save-template") }}" method="post" name="add_ccm_content_template_form"> -->
                        {{ csrf_field() }}
                        @hidden("add",["id"=>"add", "value"=>"add"])
                        <div class="form-group">
                         
                            <div class="row">                                
                                <div class="col-md-6 mb-3">
                                    <label for="content_title" class=""> Content Title <span class="error">*</span></label>
                                   
                                    @text("content_title", ["id"=>"content_title"])
                                     </div> 
                               
                                <div class="col-md-6 mb-3">
                                    <label class=""> Content Type <span class="error">*</span></label>
                                   
                                    @selectContentType("template_type",["id"=>"template_type"])
                                     </div>  
                                  <!--   <div class="invalid-feedback"></div> -->
                                </div>
                               <div class="row">  
                                <div class="col-md-6 mb-3">
                                    <label for="loginuser" class="">Module <span class="error">*</span></label>
                                    @selectMasterModule("module",["id"=>"module", "disabled"=>"disabled"])  
                                     </div> 
                                
                                <div class="col-md-6 mb-3">
                                    <label for="loginuser" class=""> Sub Module <span class="error">*</span></label>
                                    @select("Sub Module", "sub_module", [], ["id" => "sub_module",])
                                     </div> 
                                    </div>
                               <div class="row"> 
                                <div class="col-md-6 mb-3">
                                    <label>Stage</label>                                 
                                    @select("Stage", "stages", [], ["id" => "stages"])
                                     </div>  
                                
                                <div class="col-md-6 mb-3">
                                    <label>Step</label>                                    
                                    @select("Step", "stage_code", [], ["id" => "stage_code"])
                                 </div>
                                </div>
                               <div class="row"> 
                                <div class="col-md-6 mb-3" id="from">
                                    <label> From <span class="error">*</span></label>
                                   
                                    @text("from", ["id" => "from", "placeholder" => "Enter Email Id"])
                                </div> 
                                <div class="col-md-6 mb-3" id="sub">
                                    <label> Subject <span class="error">*</span></label>
                                  
                                    @text("subject", ["id" => "subject", "placeholder" => "Enter Subject"])
                                </div>  
                            </div>
                            
                        </div>
                        <h1>Content <span class="error">*</span></h1>
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
			
			for ( instance in CKEDITOR.instances )
				CKEDITOR.instances[instance].updateElement();
			
			
			var formdata = $('#saveccmtemplate-form').serialize();
			//console.log(formdata);
			
			
			
            var editor = CKEDITOR.instances['editor'].getData();
            var pathArray = window.location.pathname.split('/');
            var url = window.location.protocol + "//" + window.location.host + "/" + pathArray[1] + "/save-template";
            var module_id    = '{{ getPageModuleName() }}';
            var sub_module = $("#sub_module").val();
            if(sub_module=="Select Sub Module"){
                sub_module="";
            }
			
            console.log(url);
			//editor = editor.replace(/&#39;/g, "\\'");
            var data = $('#saveccmtemplate-form').serialize()+"&editorData="+editor+"&module="+module_id+"&sub_module="+sub_module;
            // console.log("data"+data);
            $.ajax({
                type: 'post',
                url: url,
                data: data, // getting filed value in serialize form
                success: function(response){
                    if($.trim(response) == 'success') {
                        $(".alert").removeClass('alert-danger');
                        $(".is-invalid").hide();
                        $(".user-msg").text("Template data added successfully!");
                        $(".alert").addClass('alert-success');
                        $(".alert").show();
                        $('#editorError').css("display", "none");
                        document.getElementById("saveccmtemplate-form").reset();
                        CKEDITOR.instances['editor'].setData('');
                        var url = $(location).attr('href');
                        var secondLevelLocation = url.split('/').reverse()[1];
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
                },
                error: function (request, status, error) {
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

                        // if(request.responseJSON.errors.stages) {
                        //     $('[name="stages"]').addClass("is-invalid");
                        //     $('[name="stages"]').next(".invalid-feedback").html(request.responseJSON.errors.stages);
                        // } else {
                        //     $('[name="stages"]').removeClass("is-invalid");
                        //     $('[name="stages"]').next(".invalid-feedback").html('');
                        // }

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
            var module_id    = '{{ getPageModuleName() }}';
            $("#module").val(module_id);
            util.updateSubModuleList(parseInt(module_id), $("#sub_module"));
            util.getToDoListData(0, {{getPageModuleName()}});
        });
    </script>
@endsection