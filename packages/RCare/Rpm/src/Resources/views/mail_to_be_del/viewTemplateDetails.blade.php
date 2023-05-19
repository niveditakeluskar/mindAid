@extends('Theme::layouts.master')
@section('page-css')

@endsection

@section('main-content')
   	
	

					<!-- end Solid Bar -->
		<div class="col-lg-10 mb-3">
				   <div class="card">

							<!--begin::form-->                                
							         	<div class="card-body">
										<div class="card-title mb-3">Template Details</div>
										<div class="separator-breadcrumb border-top"></div>
                                            <?php
                                                    $content = json_decode($data->content);
                                                if($data->template_type_id == 1){?>
                                                    <div class="form-row">
                                                    <div class="form-group col-md-6" id="from">
                                                        <strong class="mr-1">From</strong>	
                                                       
                                                        <!-- <label for="phone">Phone</label> -->
                                                            <p class="text-muted m-0">{{ $content->from }}</p>
                                                    </div> 
                                                    <div class="form-group col-md-6" id="sub">
                                                        <strong class="mr-1">Subject</strong>	
                                                    <!-- <label for="email">Email</label> -->
                                                    
                                                        <p class="text-muted m-0">{{ $content->subject }}</p>
                                                    </div>
                                                </div> 
                                                <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                            <strong class="mr-1">Message</strong>	
                                                        
                                                            <p class="text-muted m-0">{{ strip_tags($content->message) }}</p>
                                                            </div>
                                                </div>  
                                               <?php } else{ ?>
                                                            <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                            <strong class="mr-1">Message</strong>	
                                                        
                                                            <p class="text-muted m-0">{{ strip_tags($content->message) }}</p>
                                                            </div>
                                                        </div>
                                               <?php }  ?>
                                           


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
</script>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

