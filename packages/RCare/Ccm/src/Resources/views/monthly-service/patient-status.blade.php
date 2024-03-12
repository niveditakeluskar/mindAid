<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                 <div class="form-row ">
                    <span class="col-md-8">
                        <h4>Patient Status</h4>
                    </span>
                    <div class="mb-4 demo-div">
                          <div class="stopwatch" id="stopwatch">
                              <div id="time-container" class="container"></div>
                              <button class="button" id="start"><img src="{{asset("assets/images/btn-play.jpeg")}}" class='user-image' style=" width: 34px;" /></button>
                              <button class="button" id="pause"><img src="{{asset("assets/images/btn-pause.svg")}}" class='user-image' style=" width: 34px;" /></button>
                              <button class="button" id="stop"><img src="{{asset("assets/images/btn-stop.png")}}" class='user-image' style=" width: 34px;" /></button>
                          </div>
                      </div>
                </div>
                
            </div>
            <div class="card-body"> 
              
                <div id="activities">
                        <div class="row mb-4">
                            <div class="col-md-2">
                                <label class="" for="select activity"><b>Select Activity</b></label>
                            </div>    
                            <div class="col-md-6">
                                <select id="activity_selection" class="  custom-select" name="select_activity" >
                                    <option value="">Choose an Activity</option>
                                    <option value="1">Preparation</option>
                                    <option value="2">Call</option>
                                    <option value="3">Follow up</option>
                                    <option value="4">Text</option>
                                    <optgroup label="Care Plan Development">
                                        <option value="5">EMR Research</option>
                                        <option value="6">Patient Phone Call</option>
                                        <option value="7">Patient Continuation</option>
                                    </optgroup>  
                                </select>
                            </div>    
                        </div> 
                        <div class="row">
                            
                        </div> 
                        <div class="col-md-12 steps_section" id="select-activity-1" style="display: none"> 
                        @include('Ccm::monthly-service.select-activity-1')
                        </div>                   
                        <div class="col-md-12 steps_section" id="select-activity-2" style="display: none"> 
                        @include('Ccm::monthly-service.select-activity-2')
                        </div>   
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="current_month_notes" tabindex="-1" role="dialog" aria-labelledby="current_month_notes-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 1000px;">
        <div class="modal-content"> 
            <form action="" method="post" name="practice_add">
                <div class="modal-header">
                    <h5 class="modal-title mx-auto" id="current_month_notes-title"> <span class="text-muted">{{$checklist->fname}} {{$checklist->lname}}'s </span> <span id="month_name"></span> Monthly Call Note( Dec 2019 ) </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">  
                    @include('Ccm::sub-steps.current-month-notes')
                </div>
            </form>
        </div>
    </div>
</div>


@section('page-js')
<script>
    $(document).ready(function(){   
		$('#activity_selection').on('change', function(){
			var conceptName = $('#activity_selection :selected').val();
            if(conceptName==1 || conceptName==2 || conceptName==3 || conceptName==4) {
				//alert(conceptName);
				util.stepWizard('tsf-wizard-1');
				$("#select-activity-1").show();
                $('#select-activity-2').hide();					
				
			}else{
				$("#select-activity-2").show();
                $('#select-activity-1').hide();
			//	pageLoadScript();
			}
		});
    });


    // function stepWizard(cl) {
    //   tsf1 = $('.'+cl).tsfWizard({
    //             stepEffect: 'basic',
    //             stepStyle: 'style1',
    //             navPosition: 'top',
    //             manySteps: true,
    //             stepTransition: true,
    //             showButtons: true,
    //             showStepNum: false,
    //             height: '300px',
    //     onBeforeNextButtonClick: function(e, validation) {
    //       console.log('onBeforeNextButtonClick');
    //       console.log(validation);
    //       //for return please write below code
    //       //  e.preventDefault();
    //     },
    //     onAfterNextButtonClick: function(e, from, to, validation) {
    //       console.log('onAfterNextButtonClick');
    //     },
    //     onBeforePrevButtonClick: function(e, from, to) {
    //       console.log('onBeforePrevButtonClick');
    //       console.log('from ' + from + ' to ' + to);
    //       //  e.preventDefault();
    //     },
    //     onAfterPrevButtonClick: function(e, from, to) {
    //       console.log('onAfterPrevButtonClick');
    //       console.log('validation ' + from + ' to ' + to);
    //     },
    //     onBeforeFinishButtonClick: function(e, validation) {
    //       console.log('onBeforeFinishButtonClick');
    //       console.log('validation ' + validation);
    //       //e.preventDefault();
    //     },
    //     onAfterFinishButtonClick: function(e, validation) {
    //       console.log('onAfterFinishButtonClick');
    //       console.log('validation ' + validation);
    //     }
    //   });

    // }
  </script>
@endsection