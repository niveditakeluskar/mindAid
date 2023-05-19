<div class="row mb-4" id="Calling_page" >
    <div class="col-md-12">
        <!-- <div class="card">  -->
            <div class="card-body">
                <div class="row ">
                    <div class="col-md-12 ">
                        <!-- SmartWizard html -->
                        <ul class="nav nav-pills" id="review-providers-myPillTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="review-pcp-icon-pill" data-toggle="pill" href="#review-pcp" role="tab" aria-controls="review-pcp" aria-selected="true"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i>PCP</a>
                            </li> 
                            <li class="nav-item">
                                <a class="nav-link" id="review-specialists-icon-pill" data-toggle="pill" href="#review-specialists" role="tab" aria-controls="review-specialists" aria-selected="false"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Specialists</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="review-dentist-icon-pill" data-toggle="pill" href="#review-dentist" role="tab" aria-controls="review-dentist" aria-selected="false"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Dentist</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="review-vision-icon-pill" data-toggle="pill" href="#review-vision" role="tab" aria-controls="review-vision" aria-selected="false"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Vision</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myPillTabContent">
                            <div class="tab-pane fade show active" id="review-pcp" role="tabpanel" aria-labelledby="pcp-icon-pill">
                                <div class="card">  
                                    <div class="card-header"><h4>PCP Data</h4></div>
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.provider-sub-steps.pcp')
                                </div>
                            </div>
                            <div class="tab-pane fade" id="review-specialists" role="tabpanel" aria-labelledby="spouse-icon-pill">
                                <div class="card">  
                                    <div class="card-header"><h4>Specialists Data</h4></div>
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.provider-sub-steps.specialists')
                                </div>
                            </div>
                            <div class="tab-pane fade" id="review-dentist" role="tabpanel" aria-labelledby="care-giver-icon-pill">
                                <div class="card">  
                                    <div class="card-header"><h4>Dentist Data</h4></div>
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.provider-sub-steps.dentist')
                                </div>
                            </div>
                            <div class="tab-pane fade" id="review-vision" role="tabpanel" aria-labelledby="emergency-contact-icon-pill">
                                <div class="card">  
                                    <div class="card-header"><h4>Vision Data</h4></div>    
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.provider-sub-steps.vision')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- </div> -->
        </div>
    </div>
</div>