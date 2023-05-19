
@foreach($patient as $checklist)
    <div class="row mb-4" id="Calling_page" style="display: none;">
        <div class="col-md-12 mb-4">
          <div class="success" id="success"></div>
            <div class="card-body"> 
                <div class="row mb-4">
                    <div class="col-md-12  mb-4">

                    <div class="tsf-wizard tsf-wizard-1 top" >    
                        <div class="tsf-nav-step ">

                            <ul class="gsi-step-indicator triangle gsi-style-1  gsi-transition " >
                                <li class="current" data-target="step-first" id="s1">
                                    <a href="#0">
                                        <span class="desc">
                                            <label>Step 1</label>
                                        </span>
                                    </a>
                                </li>
                                <li data-target="step-2" id="s2" class="disabled">
                                    <a href="#0">
                                        <span class="desc">
                                            <label>Step 2</label>
                                        </span>
                                    </a>
                                </li>
                                <li data-target="step-3" id="s3" class="disabled">
                                    <a href="#0">
                                        <span class="desc">
                                            <label>Step 3</label>
                                        </span>
                                    </a>
                                </li>
                                <li data-target="step-4" id="s4" class="disabled">
                                    <a href="#0">
                                        <span class="desc">
                                            <label>Step 4</label>
                                        </span>
                                    </a>
                                </li>               
                                <li data-target="step-5" id="s5" class="disabled">
                                    <a href="#0">
                                        <span class="desc">
                                            <label>Step 5</label>
                                        </span>
                                    </a>
                                </li>     
                                <li data-target="step-6" id="s6" class="disabled">
                                    <a href="#0">
                                        <span class="desc">
                                            <label>Step 6</label>
                                        </span>
                                    </a>
                                </li>                                                     
                            </ul>

                        </div>

                        <div class="tsf-container">
                            <!-- BEGIN CONTENT-->
                            <form class="tsf-content">
                                
                                <div id="step-1" class="tsf-step step-first active">
                                    @include('Rpm::patient-enrollment.call-attempt')   
                                </div>
                                <div id="step-2" class="tsf-step step-2">
                                     @include('Rpm::patient-enrollment.call-action')
                                </div>	
                                <div id="step-3" class="tsf-step step-3">
                                    @include('Rpm::patient-enrollment.module-checklist')
                                </div>
                                <div id="step-4" class="tsf-step step-4">
                                    @include('Rpm::patient-enrollment.enrollment-checklist') 
                                </div>
                                <div id="step-5" class="tsf-step step-5">
                                    @include('Rpm::patient-enrollment.enrollment-finalization-checklist')
                                </div>		
                                <div id="step-6" class="tsf-step step-6">
                                    @include('Rpm::patient-enrollment.summary')
                                </div>		
                                																
                            </form>
                        </div>
			
		</div>
                        <!-- SmartWizard html -->
                     
                    </div>
                </div>
            </div>
        </div>
</div>

@endforeach
