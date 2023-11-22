<div class="row mb-4" id="family_page" >
    <div class="col-md-12">
        <!-- <div class="card">  -->
            <div class="card-body">
                <div class="row ">
                    <div class="col-md-12 ">
                        <!-- SmartWizard html -->
                        <ul class="nav nav-pills" id="review-patient-myPillTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="review-patientab-icon-pill" data-toggle="pill" href="#review-patientab" role="tab" aria-controls="review-patientab" aria-selected="true"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i>Patient</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="review-spouse-icon-pill" data-toggle="pill" href="#review-spouse" role="tab" aria-controls="review-spouse" aria-selected="false"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Spouse</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="review-care-giver-icon-pill" data-toggle="pill" href="#review-care-giver" role="tab" aria-controls="review-care-giver" aria-selected="false"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Care Giver</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="review-emergency-contact-icon-pill" data-toggle="pill" href="#review-emergency-contact" role="tab" aria-controls="review-emergency-contact" aria-selected="false"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Emergency Contact</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myPillTabContent">
                            <div class="tab-pane fade show active" id="review-patientab" role="tabpanel" aria-labelledby="review-patientab-icon-pill">
                                <div class="card">  
                                    <div class="card-header"><h4>Patient Data</h4></div>
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.family-sub-steps.patient-data')
                                </div>
                            </div>
                            <div class="tab-pane fade" id="review-spouse" role="tabpanel" aria-labelledby="review-spouse-icon-pill">
                                <div class="card">  
                                    <div class="card-header"><h4>Spouse Data</h4></div>
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.family-sub-steps.spouse')
                                </div>
                            </div>
                            <div class="tab-pane fade" id="review-care-giver" role="tabpanel" aria-labelledby="review-care-giver-icon-pill">
                                <div class="card">  
                                    <div class="card-header"><h4>Care Giver</h4></div>
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.family-sub-steps.care-giver')
                                </div>
                            </div>
                            <div class="tab-pane fade" id="review-emergency-contact" role="tabpanel" aria-labelledby="review-emergency-contact-icon-pill">
								<div class="card">  
                                    <div class="card-header"><h4>Emergency Contact</h4></div>    
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.family-sub-steps.emergency-contact')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- </div> -->
        </div>
    </div>
</div>