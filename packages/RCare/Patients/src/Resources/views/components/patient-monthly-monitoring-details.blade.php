<div class="row mb-4">
   <div class="col-md-12">
      <div class="card">
         <div class="card-body">
            <div id="activities">
               <div class="row mb-4" id="select-activity-1">
                  <div class="col-md-12">
                     <div class="tsf-wizard tsf-wizard-2" >    
                        <div class="tsf-nav-step " style="width:10%;">
                           <ul class="gsi-step-indicator triangle gsi-style-1 gsi-transition " >
                              <li class="current preparation act1" id="start-tab" data-target="step-first">
                                 <a href="#0" id="preparation-step">
                                    <span class="desc"><label>Question</label></span>
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
                                 @include('Patients::components.relationship')
                              </div>
                               <div id="step-11" class="tsf-step step-11">
                                @include('Patients::components.text')
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