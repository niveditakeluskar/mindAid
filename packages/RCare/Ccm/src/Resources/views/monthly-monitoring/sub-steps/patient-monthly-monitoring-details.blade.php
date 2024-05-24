<div class="row mb-4">
   <div class="col-md-12">
      <div class="card">
         <div class="card-body">
            <div id="activities">
               <div class="row mb-4" id="select-activity-1">
                  <div class="col-md-12">
                     <div class="tsf-wizard tsf-wizard-2" >    
                        <div class="tsf-nav-step " style="width:10%;">
                           <?php $segment =  Request::segment(1);  ?>
                           <ul class="gsi-step-indicator triangle gsi-style-1 gsi-transition " >
                              <li class="current preparation act1" id="start-tab" data-target="step-first">
                                 <a href="#0" id="preparation-step">
                                    <span class="desc"><label>Preparation</label></span>
                                 </a> 
                              </li>
                              <?php if($segment=='rpm' || $enrollinRPM > 1){?>
                              <li class="review-data-1 " id="review_data_1_id" data-target="step-12">
                                 <a href="#0">
                                    <span class="desc"><label>Review RPM</label></span>
                                 </a>
                              </li>
                              <?php }?>
                              <li class="call-step-1 " id="call_step_1_id" data-target="step-2">
                                 <a href="#0">
                                    <span class="desc"><label>Call</label></span>
                                 </a>
                              </li>
                              <li class="follow-up act1" id="follow_up_id" data-target="step-10">
                                 <a href="#0" id="followup-step">
                                    <span class="desc"><label>Follow-up</label></span>
                                 </a>
                              </li>  
                              <li class="text act1" id="text_id" data-target="step-11">
                                 <a href="#0">
                                    <span class="desc"><label>Text</label></span>
                                 </a>
                              </li> 
                                 
                           </ul>
                        </div>
                        <div class="tsf-container" style="width: 90%;">
                           <!-- BEGIN CONTENT-->
                           <div class="tsf-content">
                              <div id="step-1" class="tsf-step step-first active">
                                 @include('Ccm::monthly-monitoring.sub-steps.preparation', ['section' => 'call_preparation'])
                              </div>
                              <div id="step-14" class="tsf-step step-2">
                                 @include('Ccm::monthly-monitoring.sub-steps.call')
                              </div>
                              <div id="step-10" class="tsf-step step-10">
                                 @include('Ccm::monthly-monitoring.sub-steps.follow-up')
                              </div> 
                               <div id="step-11" class="tsf-step step-11">
                                 @include('Ccm::monthly-monitoring.sub-steps.text')
                              </div>    
                              <div id="step-12" class="tsf-step step-12">
                                 @include('Rpm::review-data-link.reading')
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>