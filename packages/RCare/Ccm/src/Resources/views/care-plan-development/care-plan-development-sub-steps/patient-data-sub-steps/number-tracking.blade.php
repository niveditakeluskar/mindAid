<div class="row mb-4" id="Calling_page" >
    <div class="col-md-12">
        <div class="card-body">
            <div class="row ">
                <div class="col-md-12 ">
                    <div class="col-md-12 ">
                        <ul class="nav nav-pills" id="myPillTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="vitals-icon-pill" data-toggle="pill" href="#vitals" role="tab" aria-controls="vitals" aria-selected="true"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Vitals</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="labs-icon-pill" data-toggle="pill" href="#labs" role="tab" aria-controls="labs" aria-selected="false"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Labs</a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link" id="Imaging-icon-pill" data-toggle="pill" href="#Imaging" role="tab" aria-controls="Imaging" aria-selected="false"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Imaging</a>
                            </li>
                              <li class="nav-item">
                                <a class="nav-link" id="health-data-icon-pill" data-toggle="pill" href="#health_data" role="tab" aria-controls="health_data" aria-selected="false"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Health Data</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myPillTabContent">
                        <div class="tab-pane fade show active" id="vitals" role="tabpanel" aria-labelledby="vitals-icon-pill">
                            <div class="card">  
                                <div class="card-header"><h4>Vitals Data</h4></div>      
                                @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.number-tracking-sub-steps.vitals')
                            </div>
                        </div>
                        <div class="tab-pane fade" id="labs" role="tabpanel" aria-labelledby="labs-icon-pill">
                            <div class="card">  
                                <div class="card-header"><h4>Labs Data</h4></div>      
                                @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.number-tracking-sub-steps.labs')
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Imaging" role="tabpanel" aria-labelledby="Imaging-icon-pill">
                            <div class="card">  
                                <div class="card-header"><h4>Imaging Data</h4></div>      
                                @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.number-tracking-sub-steps.Imaging')
                            </div>
                        </div>
                         <div class="tab-pane fade" id="health_data" role="tabpanel" aria-labelledby="health-data-icon-pill">
                            <div class="card">  
                                <div class="card-header"><h4>Health Data</h4></div>      
                                @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.number-tracking-sub-steps.health-data')
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 