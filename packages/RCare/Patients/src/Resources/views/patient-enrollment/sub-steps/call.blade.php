<div class="row pb-4"  id="call_step_div">
    <div class="col-lg-12 mb-3">
        
        <div class="tsf-wizard tsf-wizard-1 top" >    
            <div class="tsf-nav-step ">
                <ul class="gsi-step-indicator triangle gsi-style-1  gsi-transition " >
                    <li class="current" data-target="step-first" id="s1">
                        <a href="#0"><span class="desc"><label>Step 1</label></span></a>
                    </li>
                    <li data-target="step-2" id="s2" class="disabled">
                        <a href="#0"><span class="desc"><label>Step 2</label></span></a>
                    </li>
                    <li data-target="step-3" id="s3" class="disabled">
                        <a href="#0"><span class="desc"><label>Step 3</label></span></a>
                    </li>
                    <li data-target="step-4" id="s4" class="disabled">
                        <a href="#0"><span class="desc"><label>Step 4</label></span></a>
                    </li>               
                      
                                                                      
                </ul>
            </div>
            <div class="tsf-container">
                <!-- BEGIN CONTENT-->
                <!-- <form class="tsf-content"> -->
                <div class="tsf-content">
                    <div id="step-1" class="tsf-step step-first active">
                        @include('Patients::patient-enrollment.sub-steps.call-sub-steps.step-1')
                    </div>
                    <div id="step-2" class="tsf-step step-2">
                        @include('Patients::patient-enrollment.sub-steps.call-sub-steps.step-2')
                    </div>	
                    <div id="step-3" class="tsf-step step-3">
                        @include('Patients::patient-enrollment.sub-steps.call-sub-steps.step-3')
                    </div>
                    <div id="step-4" class="tsf-step step-4">
                        @include('Patients::patient-enrollment.sub-steps.call-sub-steps.step-4')
                    </div>
                   
                    
                </div>															
                <!-- </form> -->
            </div>
        </div>
    </div>
</div>