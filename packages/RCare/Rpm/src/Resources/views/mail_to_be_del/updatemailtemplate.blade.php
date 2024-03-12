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
              <!--   <form class="" method="post" action="{{ route('SaveRpmTemplate') }}"> 
                    @csrf -->
                        <form class="" method="" action="" id="updatetemplates-form"> 
                     @csrf
                         <div class="alert alert-success" id="success-alert" style="display: none;">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>Template data updated successfully! </strong><span id="text"></span>
                </div>
                    <input type="hidden" name="temp_id" value="{{ $data->id }}" />
                    <input type="hidden" name="edit" value="edit" />
                <div class="form-group">
                        <div class="row"><div class="col-md-2"><label>Content Name</label> </div>
                        <input type="text" class="form-control col-md-3" name="content_title" value="{{ $data->content_title }}">
                        <div class="col-md-2 offset-md-1"><label>Content Type</label></div>
                            <select  class="form-control col-md-3" name="template_type" id="template_type">
                            <option value="0" >Select Content Type</option>
                                @foreach($type as $templateType)
                                    <option value="{{ $templateType->id }}" <?php if($data->template_type_id == $templateType->id){echo 'selected';} ?>>{{ $templateType->template_type }}</option>
                                @endforeach   
                            </select>    
                        </div>
                </div>    
                <div class="form-group">
                    <div class="row"><label class="col-md-2">Modules</label>
                    <select class="custom-select col-md-3" name="module">
                        <option selected>Choose One...</option>
                        @foreach($service as $value)
                        <option value="{{ $value->id }}" <?php if($data->module_id == $value->id){ echo 'selected'; } ?>>{{ $value->module }}</option>
                        @endforeach
                    </select> 
                    <!--/div-->
                     <!--input type="hidden" name="module" value="2"-->
                     <input type="hidden" name="stage_code" value="0">
                    <!--div class="row"-->
                    <label class="col-md-2 offset-md-1">Sub Modules</label>
                    <select class="custom-select col-md-3" name="sub_module" onchange="get_stages(this.value)">
                        <option value="0">Choose One...</option>
                        @foreach($sub_service as $key)
                            <option value="{{ $key->id }}" <?php if($data->component_id == $key->id){ echo 'selected'; } ?>>{{ $key->components }}</option>
                         @endforeach
                    </select>
                    </div>
                </div> 
                <div class="form-group">
                    <div class="row">
                    <label class="col-md-2 " id="stage_label" >Stages</label>
                    <select class="custom-select col-md-3" name="stages" id="stages">
                        <option value="0" selected>Choose One...</option>
                        @foreach($stage as $key)
                        <option value="{{ $key->id }}" <?php if($data->stage_id == $key->id){ echo 'selected'; } ?>>{{ $key->description }}</option>
                        @endforeach
                    </select>
                    </div>
                </div> 
                <div class="form-group">
                    <div class="row">
                    <label class="col-md-2">Devices</label>
                    <select class="custom-select col-md-3" name="devices" id="devices" >
                
                        <option value="0">Choose One...</option>
                        @foreach($devices as $value)
                        <option value="{{ $value->id }}" <?php if($data->device_id == $value->id){ echo 'selected'; } ?>>{{ $value->device_name }}</option>
                        @endforeach
                
                    </select>
                    </div>  
                </div>   

                <div class="form-group" id="from">
                    <div class="row">
                            <label class="col-md-2">From : </label>
                            
                            <?php if($data->template_type_id) { 
                                $content = json_decode($data->content);
                            
                                }
                            ?>
                            <!-- @text("from", ["id" => "sender_from", "placeholder" => "Enter Email Id", "class" => "form-control col-md-3", "value" => "{{ $content->from }}"]) -->
                            <input type="text" class="form-control col-md-3" name="from" value="{{ $content->from }}">
                    </div>    
                </div>    
                <div class="form-group" id="sub">
                    <div class="row">
                            <label class="col-md-2">Subject : </label>
                            <!-- @text("subject", ["id" => "subject", "placeholder" => "Enter Subject", "class" => "form-control col-md-3"]) -->
                            <input type="text" class="form-control col-md-3" name="subject" value="{{ $content->subject }}">
                    </div>    
                </div>
        
                <h1>Content</h1>
                <textarea name="content" id="editor" cols="30" rows="10">{{ $content->message }}</textarea>
                <!-- <div id="snow-editor">              
                </div>
                <textarea name="content" style="display:none" id="hiddenArea">{{ $content->message }}</textarea> -->
                
                <br>
                <button type="submit" name="edit" id="update" class="btn btn-info btn-lg btn-block">Update</button> 
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

        $('#update').click(function(){
            // alert($('#updatetemplates-form').serialize());
            var editor = CKEDITOR.instances['editor'].getData();
            // alert(editor);
            $.ajax({
                type: 'post',
                url: '/rpm/SaveRpmTemplate',
                data: $('#updatetemplates-form').serialize() +"&editorData="+editor, // getting filed value in serialize form
                success: function(response){
                    // alert(JSON.stringify(response));
                    // console.log(JSON.stringify(response));
                    $(".alert").show();   
                    // document.getElementById("updatetemplate-form").reset();
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

    function get_stages(val){
        $.ajax({
            type: 'post',
            url: '/rpm/SaveRpmTemplate',
            data: {
                _token: '{!! csrf_token() !!}',
                stages_id:val
            },
            success: function (response) {
                document.getElementById("stages").innerHTML=response; 
            }
        });
    }
</script>
@endsection
@section('bottom-js')

<script src="{{asset('assets/js/quill.script.js')}}"></script>


@endsection