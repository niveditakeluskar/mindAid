<div class="row pb-4"  id="call_step_div">
    <div class="col-lg-12 mb-3">
        <div class="tsf-wizard tsf-wizard-1 top" >    
            <div class="tsf-nav-step ">
                <ul class="gsi-step-indicator triangle gsi-style-1  gsi-transition " >
                    <li class="current" data-target="step-first" id="s1">
                        <a href="#0"><span class="desc"><label>Questionnaire</label></span></a>
                    </li>
                    <li data-target="step-2" id="s2">
                        <a href="#0"><span class="desc"><label>Text</label></span></a>
                    </li>                                                 
                </ul>
            </div>
            <div class="tsf-container">
                <!-- BEGIN CONTENT-->
                <div class="tsf-content">
                    <div id="step-1" class="tsf-step step-first active">
                        @include('Patients::components.relationship')
                    </div>
                    <div id="step-2" class="tsf-step step-2">
                        @include('Patients::components.text')
                    </div>	
                </div>				
            </div>
        </div>
    </div>
</div>