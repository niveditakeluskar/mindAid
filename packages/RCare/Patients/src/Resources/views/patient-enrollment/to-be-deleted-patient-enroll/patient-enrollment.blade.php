@extends('Theme::layouts_2.to-do-master')

@section('main-content')
    @foreach($patient as $checklist)		

        <div class="separator-breadcrumb "></div>
        <div class="row text-align-center">
            @include('Theme::layouts_2.flash-message')  
            <div class="col-md-12">
                {{ csrf_field() }}
                <!--Add view Patient Overview -->
                @include('Rpm::patient-enrollment.patient-overview')
                
            </div>  
            <!-- <div class="col-md-11  mb-4 patient_details"> -->
                {{-- {{ csrf_field() }}
                @include('Rpm::patient-enrollment.patient-overview')--}}
                <!-- <div class="separator-breadcrumb border-top"></div>     -->
                {{-- @include('Rpm::patient-enrollment.textAndEmail')
                @include('Rpm::patient-enrollment.calling')--}}
            <!-- </div> -->  
        </div>
    @endforeach
@endsection
<script src="https://cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>
<script>
        CKEDITOR.replace('editor');
        $("#text").click(function(){
                $("#header_text").html('Text');
                var hidden_id = $('#hidden_id').val()
                var title = $('#header_text').html();
                $("#content-template").show();
                $("#template_type_id").val(2);
                var template_type_id = 2;
                $("#textarea").show();
                $("#contactlist").show();
                $("#emaillist").hide();
                $("#email-div").hide();
                $("#Calling_page").hide(); 
            $.ajax({
                type: 'post',
                url: '/rpm/fetch-email-template',
                data: { _token: '{!! csrf_token() !!}', template_type_id:template_type_id,title:title,hidden_id:hidden_id },
                success: function (response) {
                    debugger;
                        // alert(response);
                    document.getElementById("content_title").innerHTML=response;
                    $('#content_title').val(130).change();
                }
            });       	
        });

        $("#email").click(function(){
                $("#header_text").html('Email');
                var hidden_id = $('#hidden_id').val()
                var title = $('#header_text').html();
                $("#content-template").show();
                $("#template_type_id").val(1);
                $("#textarea").hide();
                $("#contactlist").hide();
                $("#emaillist").show();
                $("#email-div").show();
                $("#Calling_page").hide();

                var template_type_id = 1;
            $.ajax({
                type: 'post',
                url: '/rpm/fetch-email-contenet',
                data: { _token: '{!! csrf_token() !!}', template_type_id:template_type_id,title:title,hidden_id:hidden_id },
                success: function (response) {
                    document.getElementById("content_title").innerHTML=response; 
                }
            });   
                
        });
	    $("#Calling").click(function(){
        	$("#Calling_page").show();
            $("#content-template").hide();
       	
        });

        $("#content_title").change(function() {
           var content_title =$("#content_title").val();
            $.ajax({
            type: 'post',
            url: '/rpm/fetch-content',
            data: {
                _token: '{!! csrf_token() !!}', content_title:content_title
            },
            success: function (response) { 
                // alert(JSON.stringify(response));
              // alert(jQuery.parseJSON(response[0].content).message);
                 $('#module_id').val(JSON.stringify(response[0].module_id));
                 $('#component_id').val(JSON.stringify(response[0].component_id)); 
                 $('#stage_id').val(JSON.stringify(response[0].stage_id));  
                 $('#template_id').val(JSON.stringify(response[0].id)); 
                 $("#subject").val(jQuery.parseJSON(response[0].content).subject);
                 $("#sender_from").val(jQuery.parseJSON(response[0].content).from); 
              var text = jQuery.parseJSON(response[0].content).message;
              $('#content_area').html($(text).text());
            CKEDITOR.instances['editor'].setData(jQuery.parseJSON(response[0].content).message);
             
            }
            });
        });

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
</script>
