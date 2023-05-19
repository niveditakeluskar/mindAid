<div class="row mb-4" id="Calling_page" >
    <div class="col-md-12">
        <div class="card-body">
            <div class="row ">
                <div class="col-md-12 ">
                    <div class="col-md-12 ">
                    <ul class="nav nav-pills" id="myPillTabServices" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link tabClick active reviewserviceslist" data-name="DME" id="dme-services-icon-pill" data-toggle="pill" href="#review-dme" role="tab" aria-controls="dme-services" aria-selected="true" value="1"><i class="nav-icon color-icon-2 i-Home1 mr-1" ></i>DME</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link tabClick reviewserviceslist" id="home-health-services-icon-pill" data-toggle="pill" href="#review-home-health-services" role="tab" aria-controls="home-health-services" aria-selected="false" value="2"><i class="nav-icon color-icon-2 i-Home1 mr-1" ></i> Home Health (skilled)</a>
                        </li>
						
                        <li class="nav-item">
                            <a class="nav-link tabClick reviewserviceslist" id="dialysis-icon-pill" data-toggle="pill" href="#review-dialysis" role="tab" aria-controls="dialysis" aria-selected="false" value="3"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Dialysis</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link tabClick reviewserviceslist" id="therapy-icon-pill" data-toggle="pill" href="#review-therapy" role="tab" aria-controls="therapy" aria-selected="false" value="4"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Therapy</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link tabClick reviewserviceslist" id="social-services-icon-pill" data-toggle="pill" href="#review-social-services" role="tab" aria-controls="social-services" aria-selected="false" value="5"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Social Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link tabClick reviewserviceslist" id="medical-supplies-icon-pill" data-toggle="pill" href="#review-medical-supplies" role="tab" aria-controls="medical-supplies" aria-selected="false" value="6"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Medical Supplies</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link tabClick reviewserviceslist" id="other-health-icon-pill" data-toggle="pill" href="#review-other-health" role="tab" aria-controls="other-health" aria-selected="false" value="7"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Other</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myPillTabContent">
                        <div class="tab-pane fade show active" id="review-dme" role="tabpanel" aria-labelledby="dme-services-icon-pill">
                            <div class="card">  
                                <div class="card-header"><h4>DME</h4></div>      
                                @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.service-sub-steps.dme')
                            </div>
                         </div>
                        <div class="tab-pane fade" id="review-home-health-services" role="tabpanel" aria-labelledby="home-health-services-icon-pill">
                            <div class="card">  
                                <div class="card-header"><h4>Home Health (skilled)</h4></div>      
                                @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.service-sub-steps.home-health-services')
                            </div>
                        </div>
                    <div class="tab-pane fade" id="review-dialysis" role="tabpanel" aria-labelledby="dialysis-icon-pill">
                        <div class="card">  
                            <div class="card-header"><h4>Dialysis</h4></div>      
                            @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.service-sub-steps.dialysis')
                        </div>
                    </div>
                    <div class="tab-pane fade" id="review-therapy" role="tabpanel" aria-labelledby="therapy-icon-pill">
                        <div class="card">  
                            <div class="card-header"><h4>Therapy</h4></div>      
                            @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.service-sub-steps.therapy')
                        </div>
                    </div>
                    <div class="tab-pane fade" id="review-social-services" role="tabpanel" aria-labelledby="social-services-icon-pill">
                        <div class="card">  
                            <div class="card-header"><h4>Social Services</h4></div>      
                            @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.service-sub-steps.social-services')
                        </div>
                    </div>
                    <div class="tab-pane fade" id="review-medical-supplies" role="tabpanel" aria-labelledby="medical-supplies-icon-pill">
                        <div class="card">  
                            <div class="card-header"><h4>Medical Supplies</h4></div>      
                            @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.service-sub-steps.medical-supplies')
                        </div>
                    </div>
                    <div class="tab-pane fade" id="review-other-health" role="tabpanel" aria-labelledby="other-health-icon-pill">
                        <div class="card">  
                            <div class="card-header"><h4>Other</h4></div>      
                            @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.service-sub-steps.other-health')
                        </div>
                    </div>
                        </div>
                   

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  
