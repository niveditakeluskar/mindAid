<div class="row mb-4" id="family_page" >
    <div class="col-md-12">
        <!-- <div class="card">  -->
            <div class="card-body">
                <div class="row ">
                    <div class="col-md-12 ">
                        <!-- SmartWizard html -->
                        <ul class="nav nav-pills" id="patient-myPillTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="patient-icon-pill" data-toggle="pill" href="#patient" role="tab" aria-controls="patient" aria-selected="true"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i>Patient</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="spouse-icon-pill" data-toggle="pill" href="#spouse" role="tab" aria-controls="spouse" aria-selected="false"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Spouse</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="care-giver-icon-pill" data-toggle="pill" href="#care-giver" role="tab" aria-controls="care-giver" aria-selected="false"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Care Giver</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="emergency-contact-icon-pill" data-toggle="pill" href="#emergency-contact" role="tab" aria-controls="emergency-contact" aria-selected="false"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Emergency Contact</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myPillTabContent">
                            <div class="tab-pane fade show active" id="patient" role="tabpanel" aria-labelledby="patient-icon-pill">
                                <div class="card">  
                                    <div class="card-header"><h4>Patient Data</h4></div>
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.family-sub-steps.patient-data')
                                </div>
                            </div>
                            <div class="tab-pane fade" id="spouse" role="tabpanel" aria-labelledby="spouse-icon-pill">
                                <div class="card">  
                                    <div class="card-header"><h4>Spouse Data</h4></div>
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.family-sub-steps.spouse')
                                </div>
                            </div>
                            <div class="tab-pane fade" id="care-giver" role="tabpanel" aria-labelledby="care-giver-icon-pill">
                                <div class="card">  
                                    <div class="card-header"><h4>Care Giver</h4></div>
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.family-sub-steps.care-giver')
                                </div>
                            </div>
                            <div class="tab-pane fade" id="emergency-contact" role="tabpanel" aria-labelledby="emergency-contact-icon-pill">
								<div class="card">  
                                    <div class="card-header"><h4>Emergency Contact</h4></div>    
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.family-sub-steps.emergency-contact')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- </div> -->
        </div>
    </div>
</div>