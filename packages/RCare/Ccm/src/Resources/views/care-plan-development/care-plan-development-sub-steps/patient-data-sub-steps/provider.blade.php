<div class="row mb-4" id="Calling_page" >
    <div class="col-md-12">
        <!-- <div class="card">  -->
            <div class="card-body">
                <div class="row ">
                    <div class="col-md-12 ">
                        <!-- SmartWizard html -->
                        <ul class="nav nav-pills" id="providers-myPillTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pcp-icon-pill" data-toggle="pill" href="#pcp" role="tab" aria-controls="patient" aria-selected="true"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i>PCP</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="specialists-icon-pill" data-toggle="pill" href="#specialists" role="tab" aria-controls="specialists" aria-selected="false"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Specialists</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="dentist-icon-pill" data-toggle="pill" href="#dentist" role="tab" aria-controls="dentist" aria-selected="false"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Dentist</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="vision-icon-pill" data-toggle="pill" href="#vision" role="tab" aria-controls="vision" aria-selected="false"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Vision</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myPillTabContent">
                            <div class="tab-pane fade show active" id="pcp" role="tabpanel" aria-labelledby="patient-icon-pill">
                                <div class="card">  
                                    <div class="card-header"><h4>PCP Data</h4></div>
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.provider-sub-steps.pcp')
                                </div>
                            </div>
                            <div class="tab-pane fade" id="specialists" role="tabpanel" aria-labelledby="spouse-icon-pill">
                                <div class="card">  
                                    <div class="card-header"><h4>Specialists Data</h4></div>
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.provider-sub-steps.specialists')
                                </div>
                            </div>
                            <div class="tab-pane fade" id="dentist" role="tabpanel" aria-labelledby="care-giver-icon-pill">
                                <div class="card">  
                                    <div class="card-header"><h4>Dentist Data</h4></div>
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.provider-sub-steps.dentist')
                                </div>
                            </div>
                            <div class="tab-pane fade" id="vision" role="tabpanel" aria-labelledby="emergency-contact-icon-pill">
								<div class="card">  
                                    <div class="card-header"><h4>Vision Data</h4></div>    
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.provider-sub-steps.vision')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- </div> -->
        </div>
    </div>
</div>