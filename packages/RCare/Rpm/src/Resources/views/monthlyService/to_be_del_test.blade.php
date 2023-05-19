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
          
                <form class="" method="post" action="{{ route('SaveTemplate') }}"> 
                    @csrf
                <div class="form-group">
                        <div class="row"><div class="col-md-2"><label>Content Name</label> </div>
                        <input type="text" class="form-control col-md-3" name="content_title">
                        <div class="col-md-2 offset-md-1"><label>Content Type</label></div>
                        <select class="custom-select col-md-3" name="template_type" id="template_type">
                            @foreach($data as $value)
                                <option value="{{ $value->id }}">{{ $value->template_type }}</option>
                            @endforeach
                        </select>
                        </div>
                </div>    
                <div class="form-group">
                    <div class="row">
      
                    <label class="col-md-2">Sub Module</label>
                    <select class="custom-select col-md-3" name="sub_module" id="sub_module" onchange="get_stages(this.value)">
                
                        <option selected>Choose One...</option>
                        @foreach($subModule as $value)
                        <option value="{{ $value->id }}">{{ $value->sub_services }}</option>
                        @endforeach
                
                    </select>
            
                
                    <label class="col-md-2 offset-md-1" id="stage_label" >Stages</label>
                    <select class="custom-select col-md-3" name="stages" id="stages"  >
            
                    </select>
                    </div>
                </div>    

                <div class="form-group" id="from">
                    <div class="row">
                            <label class="col-md-2">From : </label>
                            @text("from", ["id" => "sender_from", "placeholder" => "Enter Email Id", "class" => "form-control col-md-3"])
                            
                    </div>    
                </div>

                <div class="form-group" id = "sub">
                    <div class="row">
                            <label class="col-md-2">Subject : </label>
                            @text("subject", ["id" => "subject", "placeholder" => "Enter Subject", "class" => "form-control col-md-3"])
                            
                    </div>    
                </div>

                <h1>Content</h1>
                <textarea name="content" id="editor" cols="30" rows="10">
                     &lt;p&gt;This is some sample content.&lt;/p&gt;
                </textarea>
                <br>
                <button type="submit" class="btn btn-info btn-lg btn-block">Save</button> 
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
            url: '/rpm/SaveTemplate',
            data: {
                _token: '{!! csrf_token() !!}',
                service:val
            },
            success: function (response) {
                document.getElementById("sub_module").innerHTML=response; 
            }
        });
    }

    function get_stages(val){
        $.ajax({
            type: 'post',
            url: '/rpm/SaveTemplate',
            data: {
                _token: '{!! csrf_token() !!}',
                stages:val
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
@endsection