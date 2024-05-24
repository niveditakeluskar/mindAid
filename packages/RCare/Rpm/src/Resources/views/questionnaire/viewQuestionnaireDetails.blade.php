@extends('Theme::layouts.master')
@section('page-css')

@endsection

@section('main-content')
   	
	

					<!-- end Solid Bar -->
		<div class="col-lg-10 mb-3">
				   <div class="card">

							<!--begin::form-->                                
							         	<div class="card-body">
										<div class="card-title mb-3">Questionnaire Details</div>
										<div class="separator-breadcrumb border-top"></div>
                                        <div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <strong class="mr-1">Content Name</strong>	
                                                        {{$data->content_title }}
                                                    </div> 
                                                    <div class="form-group col-md-6">
                                                        <strong class="mr-1">Content Type</strong>	
                                                        Questionnaire	
                                                        {{--$data->module_id --}}
                                                    </div>
                                                </div> 
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <strong class="mr-1">Modules</strong>	
                                                        @foreach($service as $s)
                                                            @if($data->module_id == $s->id)
                                                                {{ $s->service_name }}
                                                            @endif
                                                        @endforeach
                                                    </div> 
                                                    <div class="form-group col-md-6">
                                                        <strong class="mr-1">Sub Modules</strong>	
                                                        @foreach($sub_service as $value)
                                                            @if($data->component_id == $value->id)
                                                                {{ $value->components }}
                                                            @endif
                                                        @endforeach
                                                    </div> 
                                                </div> 
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <strong class="mr-1">Stages</strong>	
                                                        @foreach($stage as $key)
                                                            @if($data->stage_id == $key->id)
                                                                {{ $key->description }}
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div> 
                                            </div>
										    <div class="separator-breadcrumb border-top"></div>
                                            <?php
                                                    // $questionnaire = json_decode($data,true);  

                                                    
                                                    $queData = json_decode($data['question']);     
                                                    $questionnaire = $queData->question->q;     
                                                    foreach($questionnaire as $value)         
                                               ?>
                                                    @foreach($questionnaire as $value)
                                                    <div class="form-row">
                                                    <div class="form-group col-md-6" id="from">
                                                        <strong class="mr-1">Question</strong>	
                                                       
                                                        <!-- <label for="phone">Phone</label> -->
                                                        
                                                            {{$value->questionTitle}}
                                                        
                                                            <p class="text-muted m-0"></p>
                                                    </div> 
                                                    <div class="form-group col-md-6" id="sub">
                                                        <strong class="mr-1">Answer Type</strong>	
                                                        
                                                        @if(!empty($value->answerFormat)){{config("form.answer_format")[$value->answerFormat]}}@endif
                                                    <!-- <label for="email">Email</label> -->
                                                  <?php  /* 
                                                        if ($value->answerFormat=='1'){
                                                            echo "Dropdown";}
                                                            if ($value->answerFormat=='2'){
                                                                echo "Textbox";}
                                                                if ($value->answerFormat=='3'){
                                                                    echo "Radio";}
                                                                    if ($value->answerFormat=='4'){
                                                                        echo "Checkbox";}
                                                                        if ($value->answerFormat=='5'){
                                                                            echo "Textarea";}
                                                                             */?>
                                                               
                                                            
                                                    
            
                                                         
                                                        <p class="text-muted m-0"></p>
                                                    </div>
                                                </div> 
                                                <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                            <strong class="mr-1">Label</strong>	
                                                            @foreach($value->label as $labels)
                                                            <span class="col-md-12">{{$labels}}</span>
                                                            @endforeach
                                                            <p class="text-muted m-0"></p>
                                                            </div>
                                                </div>  
                                                <hr>
                                                @endforeach
									    	<br><br><br>
								       </div>

							<!-- end::form -->
					</div>
		</div>
					

@endsection



@section('bottom-js')
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
    }
</script>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

