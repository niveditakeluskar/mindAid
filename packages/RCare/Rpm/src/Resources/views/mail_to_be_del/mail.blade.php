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
          <!-- 
                <form class="" method="post" action="{{ route('SaveRpmTemplate') }}"> 
                    @csrf -->
                        <form class="" method="" action="" id="savetemplate-form"> 
                     @csrf
                       <div class="alert alert-success" id="success-alert" style="display: none;">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>Template data save successfully! </strong><span id="text"></span>
                </div>
                <div class="form-group">
                        <div class="row"><div class="col-md-2"><label>Content Name</label> </div>
                        <input type="text" class="form-control col-md-3" name="content_title" required>
                        <div class="col-md-2 offset-md-1"><label>Content Type</label></div>
                        <select class="custom-select col-md-3" name="template_type" id="template_type" required>
                            @foreach($data as $value)
                                <option value="{{ $value->id }}">{{ $value->template_type }}</option>
                            @endforeach
                        </select>
                        </div>
                </div>



                <div class="form-group">
                        <div class="row"><label class="col-md-2">Modules</label>
                        <select class="custom-select col-md-3" name="module" id="module" value="2" required onchange="get_subservice(this.value);">
                            <option selected>Choose One...</option>
                            @foreach($service as $value)
                            <option value="{{ $value->id }}">{{ $value->module }}</option>
                            @endforeach
                        </select>
                        <label class="col-md-2 offset-md-1">Sub Modules</label>
                        
                        <select class="custom-select col-md-3" name="sub_module" id="sub_module" required onchange="get_stages(this.value);">
                             <option selected>Choose One...</option>
                        @foreach($subModule as $value)
                        <option value="{{ $value->id }}">{{ $value->components }}</option>
                        @endforeach
                        </select></div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                        <label class="col-md-2" id="stage_label" >Stages</label>
                            <select class="custom-select col-md-3" name="stages" id="stages" required >
            
                            </select>
                        </div>
                    </div>    
               <!--  <div class="form-group">
                    <div class="row">
      
                    <label class="col-md-2">Sub Module</label>
                    <select class="custom-select col-md-3" name="sub_module" id="sub_module" onchange="get_stages(this.value)">
                
                        <option selected>Choose One...</option>
                        @foreach($subModule as $value)
                        <option value="{{ $value->id }}">{{ $value->sub_services }}</option>
                        @endforeach
                
                    </select>
                    <input type="hidden" name="stages" value="0">
                  
                    
                    <label class="col-md-2 offset-md-1" id="stage_label" >Stages</label>
                    <select class="custom-select col-md-3" name="stages" id="stages" value="1">
            
                    </select>
                    </div>
                </div>     -->
                <!--   <input type="hidden" name="service" value="0"> -->
                <input type="hidden" name="stage_code" value="0">
                  <input type="hidden" name="stages" value="18"> 
                <div class="form-group">
                    <div class="row">
                    <label class="col-md-2">Devices</label>
                    <select class="custom-select col-md-3" name="devices" id="devices" required>
                
                        <option selected>Choose One...</option>
                        @foreach($devices as $value)
                        <option value="{{ $value->id }}">{{ $value->device_name }}</option>
                        @endforeach
                
                    </select>
                    </div>  
                </div>
                <div class="form-group" id="from">
                    <div class="row">
                            <label class="col-md-2">From : </label>
                            @text("from", ["id" => "sender_from", "placeholder" => "Enter Email Id", "required", "class" => "form-control col-md-3"])
                            
                    </div>    
                </div>

                <div class="form-group" id = "sub">
                    <div class="row">
                            <label class="col-md-2">Subject : </label>
                            @text("subject", ["id" => "subject", "placeholder" => "Enter Subject", "required", "class" => "form-control col-md-3"])
                            
                    </div>    
                </div>

                <h1>Content</h1>
                <textarea name="content" id="editor" cols="30" rows="10"></textarea>
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
</script>
<script>
    function get_subservice(val){
        $.ajax({
            type: 'post',
            url: '/rpm/SaveRpmTemplate',
            data: {
                _token: '{!! csrf_token() !!}',
                service:val
            },
            success: function (response) {
                document.getElementById("sub_module").innerHTML=response; 
            }
        });
    }



        $('#save-template').click(function(){
        
        var editor = CKEDITOR.instances['editor'].getData();
         $.ajax({
            type: 'post',
            url: '/rpm/SaveRpmTemplate',
            data: $('#savetemplate-form').serialize() +"&editorData="+editor, // getting filed value in serialize form
            success: function(response){
             $(".alert").show();   
            // document.getElementById("updatetemplate-form").reset();
            

            }
         });
          // to prevent refreshing the whole page page
            return false;
      });


    function get_stages(val){
        $.ajax({
            type: 'post',
            url: '/rpm/SaveRpmTemplate',
            data: {
                _token: '{!! csrf_token() !!}',
            sub_module:val
            },
            success: function (response) {
                document.getElementById("stages").innerHTML=response; 
            }
        });
    }

    $(document).ready(function(){ 
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
    });

    
</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
    <script type="text/javascript">
        if ($("#savetemplate-form").length > 0) {
            $("#savetemplate-form").validate({
                rules: {
                    content_title: {
                        required: true,
                    },
                    template_type: {
                        required: true,
                    },
                    module: {
                        required: true,
                    },
                    sub_module: {
                        required: true,
                    },
                    stages: {
                        required: true,
                    }
                    // ,
                    // sender_from: {
                    //     required: function(element){
                    //         return $("#template_type").val() == 1;
                    //     }
                    // },
                    // subject: {
                    //     required: function(element){
                    //         return $("#template_type").val() == 1;
                    //     }
                    // }
                },
                messages: {
                    content_title: {
                        required: "Please enter content title",
                    },
                    template_type: {
                        required: "Please enter template type",
                    },
                    module: {
                        required: "Please enter module",
                    },
                    sub_module: {
                        required: "Please enter sub module",
                    },
                    stages: {
                        required: "Please enter stage",
                    }
                    // ,
                    // sender_from: {
                    //     required: "Required if content type is email template",
                    // },
                    // subject: {
                    //     required: "Required if content type is email template",
                    // }
                },
            });
        }
    </script>
@endsection