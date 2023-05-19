
@foreach($patient as $checklist)
    <div class="row mb-4" id="Calling_page" style="display: none;">
        <div class="col-md-12">
            <div class="card"> 
                <div class="row ">
                    <div class="col-md-12 ">
                    <div class="tsf-wizard tsf-wizard-1 top" >    
                        <div class="tsf-nav-step">

                            <ul class="gsi-step-indicator triangle gsi-style-1  gsi-transition " >
                                <li class="current" data-target="step-first">
                                    <a href="#0">
                                        <span class="desc">
                                            <label>Step 1</label>
                                        </span>
                                    </a>
                                </li>
                                <li data-target="step-2">
                                    <a href="#0">
                                        <span class="desc">
                                            <label>Step 2</label>
                                        </span>
                                    </a>
                                </li>
                                <li data-target="step-3">
                                    <a href="#0">
                                        <span class="desc">
                                            <label>Step 3</label>
                                        </span>
                                    </a>
                                </li>               
                                <li data-target="step-4">
                                    <a href="#0">
                                        <span class="desc">
                                            <label>Step 4</label>
                                        </span>
                                    </a>
                                </li>                                                         
                            </ul>

                        </div>

                        <div class="tsf-container">
                            <!-- BEGIN CONTENT-->
                            <form class="tsf-content">
                                <div id="step-1" class="tsf-step step-first active">
                                    @include('Rpm::patient-enrollment.call-attempt')   )
                                </div>
                                <div id="step-2" class="tsf-step step-2">
                                    @include('Rpm::patient-enrollment.enrollment-checklist') 
                                </div>
                                <div id="step-3" class="tsf-step step-3">
                                    @include('Rpm::patient-enrollment.enrollment-finalization-checklist')
                                </div>		
                                <div id="step-4" class="tsf-step step-4">
                                    @include('Rpm::patient-enrollment.summary')
                                </div>																		
                            </form>
                        </div>
			
		</div>
                        <!-- SmartWizard html -->
                        <div id="smartwizard">
                            <ul>
                                <li class=""><a href="#step-1">Step 1<br /><small></small></a></li>
                                <li class=""><a href="#step-2">Step 2<br /><small></small></a></li>
                                <li class=""><a href="#step-3">Step 3<br /><small></small></a></li>
                                <li class=""><a href="#step-4">Step 4<br /><small></small></a></li>
                               <!--  <li><a href="#step-5">Step 5<br /><small></small></a></li> -->
                            </ul>

                            <div>
                                <div id="step-1" class="">
                                    <div class="card">  
                                       <div class="card-header"><h4>Call Attempt</h4></div>      
                                        @include('Rpm::patient-enrollment.call-attempt')   
                                    </div>
                                </div>
                                <!-- end step 1 -->
                                <div id="step-2" class="" >
                                    <div class="card">  
                                       <div class="card-header"><h4>Patient Enrollment checklist</h4></div>      
                                     @include('Rpm::patient-enrollment.enrollment-checklist')   
                                    </div>
                                </div>
                                <div id="step-3" class="">
                                    <div class="card">  
                                       <div class="card-header"><h4>Patient Enrollment Finalization Checklist</h4></div>      
                                     @include('Rpm::patient-enrollment.enrollment-finalization-checklist')   
                                    </div>
                                </div>

                                <div id="step-4" class="">
                                    <div class="card">  
                                       <div class="card-header"><h4>Patient Enrollment Summary</h4></div>      
                                     @include('Rpm::patient-enrollment.summary')   
                                    </div>
                                </div>
                                <div class="form-row">
                                     <div class="form-group col-md-12">
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

@endforeach
<script type="text/javascript">
$(document).ready(function(){
    $("#answered").click(function(){
            $modal = $('.script_modal');
            $modal.modal('show');
            // $("#answer").show();
            $("#not_answer").hide();
            $.ajax({
            type: 'get',
            url: '/rpm/fetchLatestScript',
            success: function (response) {
                // alert(response);
                $('#script').html(jQuery.parseJSON(response[0].content).message);
                $('#call_scripts').val(125).change();
                // alert(JSON.stringify(response[0].content).message);
                // $('#script').html(jQuery.parseJSON(response[0].content).message);
                // $modal = $('.script_modal');
                // $modal.modal('show');
            },
            dataType: 'JSON',
        });
        }); 

        $("#not_answered").click(function(){
        $("#not_answer").show();
        // $("#answer").hide();
        });
    
    $("#next_step").click(function(){
        nextStep2();
    });



 $("#step_3").click(function(){
    nextStep2();
 });

  $("#step_4").click(function(){
    nextStep2();
 });
 

  $("#back_3").click(function(){
    backStep2();
 });

  $("#back_4").click(function(){
    backStep2();
 });

  $("#back_5").click(function(){
    backStep2();
 });
 


function nextStep2(){
    // alert(step);
    $('#script_modal').modal('hide');
             var pageURL = $(location).attr("href");  
             var str = pageURL.substr(0,pageURL.indexOf('#')); 
             // alert(str);
             var n = pageURL.search("#");
             var m = pageURL.slice(n+6, n+7)
             var i = parseInt(m)+1;
            //var str2 = parseInt(str)+1;
            window.location=str+""+'#step-'+i;
}

function backStep2(){
    // alert(step);
    $('#script_modal').modal('hide');
             var pageURL = $(location).attr("href");  
             var str = pageURL.substr(0,pageURL.indexOf('#')); 
             // alert(str);
             var n = pageURL.search("#");
             var m = pageURL.slice(n+6, n+7)
             var i = parseInt(m)-1;
            //var str2 = parseInt(str)+1;
            window.location=str+""+'#step-'+i;
}


function nextStep3(step){
    // alert(step);
    // $('#script_modal').modal('hide');
            var pageURL = $(location).attr("href");  
             var str = pageURL.substr(0,pageURL.indexOf('#')); 
             // alert(str);
            //var str2 = parseInt(str)+1;
            window.location=str+""+'#step-3';
}


$('#call_scripts').on('change', function() {
  // alert( this.value );
    var id = $("#call_scripts").val(); 
    // alert(id);
        $.ajax({
            type: 'get',
            url: '/rpm/fetch-getCallScriptsById',
            data : {
                'id': id,
            },
            success: function (response) {
                // alert(JSON.stringify(response[0].content).message);
                $('#script').html(jQuery.parseJSON(response[0].content).message);
                // $modal = $('.script_modal');
                // $modal.modal('show');
            },
            dataType: 'JSON',
        });
});

    
}); 

</script>