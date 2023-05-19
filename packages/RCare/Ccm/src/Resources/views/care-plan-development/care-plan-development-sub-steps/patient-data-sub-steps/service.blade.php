<div class="row mb-4" id="Calling_page" >
    <div class="col-md-12">
        <div class="card-body">
            <div class="row ">
                <div class="col-md-12 ">
                    <div class="col-md-12 ">
                    <ul class="nav nav-pills" id="myPillTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link tabClick active serviceslist" id="dme-services-icon-pill" data-toggle="pill" href="#dme" role="tab" aria-controls="dme-services" aria-selected="true" value="1"><i class="nav-icon color-icon-2 i-Home1 mr-1" ></i>DME</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link tabClick serviceslist" id="home-health-services-icon-pill" data-toggle="pill" href="#home-health-services" role="tab" aria-controls="home-health-services" aria-selected="false" value="2"><i class="nav-icon color-icon-2 i-Home1 mr-1" ></i> Home Health (skilled)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link tabClick serviceslist" id="dialysis-icon-pill" data-toggle="pill" href="#dialysis" role="tab" aria-controls="dialysis" aria-selected="false" value="3"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Dialysis</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link tabClick serviceslist" id="therapy-icon-pill" data-toggle="pill" href="#therapy" role="tab" aria-controls="therapy" aria-selected="false" value="4"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Therapy</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link tabClick serviceslist" id="social-services-icon-pill" data-toggle="pill" href="#social-services" role="tab" aria-controls="social-services" aria-selected="false" value="5"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Social Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link tabClick serviceslist" id="medical-supplies-icon-pill" data-toggle="pill" href="#medical-supplies" role="tab" aria-controls="medical-supplies" aria-selected="false" value="6"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Medical Supplies</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link tabClick serviceslist" id="other-health-icon-pill" data-toggle="pill" href="#other-health" role="tab" aria-controls="other-health" aria-selected="false" value="7"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Other</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myPillTabContent">
                        <div class="tab-pane fade show active" id="dme" role="tabpanel" aria-labelledby="dme-services-icon-pill">
                            <div class="card">  
                                <div class="card-header"><h4>DME</h4></div>      
                                @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.service-sub-steps.dme')
                            </div>
                         </div>
                        <div class="tab-pane fade" id="home-health-services" role="tabpanel" aria-labelledby="home-health-services-icon-pill">
                            <div class="card">  
                                <div class="card-header"><h4>Home Health (skilled)</h4></div>      
                                @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.service-sub-steps.home-health-services')
                            </div>
                        </div>
                    <div class="tab-pane fade" id="dialysis" role="tabpanel" aria-labelledby="dialysis-icon-pill">
                        <div class="card">  
                            <div class="card-header"><h4>Dialysis</h4></div>      
                            @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.service-sub-steps.dialysis')
                        </div>
                    </div>
                    <div class="tab-pane fade" id="therapy" role="tabpanel" aria-labelledby="therapy-icon-pill">
                        <div class="card">  
                            <div class="card-header"><h4>Therapy</h4></div>      
                            @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.service-sub-steps.therapy')
                        </div>
                    </div>
                    <div class="tab-pane fade" id="social-services" role="tabpanel" aria-labelledby="social-services-icon-pill">
                        <div class="card">  
                            <div class="card-header"><h4>Social Services</h4></div>      
                            @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.service-sub-steps.social-services')
                        </div>
                    </div>
                    <div class="tab-pane fade" id="medical-supplies" role="tabpanel" aria-labelledby="medical-supplies-icon-pill">
                        <div class="card">  
                            <div class="card-header"><h4>Medical Supplies</h4></div>      
                            @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.service-sub-steps.medical-supplies')
                        </div>
                    </div>
                    <div class="tab-pane fade" id="other-health" role="tabpanel" aria-labelledby="other-health-icon-pill">
                        <div class="card">  
                            <div class="card-header"><h4>Other</h4></div>      
                            @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.service-sub-steps.other-health')
                        </div>
                    </div>
                </div>
                        <!-- <div class="separator-breadcrumb border-top"></div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="services-list" class="display table table-striped table-bordered" style="width:100%; border: 1px solid #00000029;">
                                        <thead id="service_table1">
                                            <tr> 
                                                <th>Sr</th>
                                                <th>Types</th>
                                                <th>Purpose</th>
                                                <th>Company Name</th>
                                                <th>Prescribing Provider</th>
                                                <th>Last Modified By</th>
                                                <th>Last Modified On</th>
                                                <th>Action</th>
                                            </tr>
                                            <thead id="service_table2" style="display: none">
                                            <tr> 
                                                <th>Sr</th>
                                                <th>Types</th>
                                                <th>Purpose</th>
                                                <th>Company Name</th>
                                                <th>Prescribing Provider</th>         
                                                <th>Last Modified By</th>
                                                <th>Last Modified On</th>
                                                <th>Action</th>
                                                   <th>Frequency</th> -->
                                               <!--  <th>Service Start Date</th>   
                                                <th>Service End Date</th>   -->   
                                           <!--  </tr>
                                        </thead>
                                        <tbody>
                                        </tbody> 
                                    </table>
                                    
                                </div>
                            </div>
                        </div> --> 

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  

