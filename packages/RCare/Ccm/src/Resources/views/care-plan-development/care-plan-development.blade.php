<div class="row mb-4" id="select-activity-5">
    <div class="col-md-12">
        <div class="card"> 
            <div class="card-body">
            <h4 class="card-title mb-3">Care Plan Development</h4>
            <ul class="nav nav-tabs" id="myIconTab" role="tablist">
                <li class="nav-item"> 
                    <a class="nav-link active" id="patient-datap-tab" data-toggle="tab" href="#patient-data" role="tab" aria-controls="patient-data" aria-selected="true"><i class="nav-icon color-icon i-Data-Storage  mr-1"></i>Patient Data</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="call-tab" data-toggle="tab" href="#calls" role="tab" aria-controls="call" aria-selected="false"><i class="nav-icon color-icon i-Telephone mr-1"></i> Call</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="verification-tab" data-toggle="tab" href="#verification-services" role="tab" aria-controls="verification" aria-selected="false"><i class="nav-icon color-icon i-Gears mr-1"></i> Verification</a>
                </li>
                <!--li class="nav-item">
                    <a class="nav-link" id="hippa-tab" data-toggle="tab" href="#hippa-services" role="tab" aria-controls="hippa" aria-selected="false"><i class="nav-icon color-icon i-Gears mr-1"></i> HIPPA Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="home-services-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home-services" aria-selected="false"><i class="nav-icon color-icon i-Home1 mr-1"></i> Home Services</a>
                </li-->
                <li class="nav-item">
                    <a class="nav-link" id="review-patient-tab" data-toggle="tab" href="#review-patient" role="tab" aria-controls="review-patient" aria-selected="false"><i class="nav-icon color-icon i-Repeat2 mr-1"></i> Review Patient Data</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="call-close-tab" data-toggle="tab" href="#call-close-cc" role="tab" aria-controls="call-close" aria-selected="false"><i class="nav-icon color-icon i-Close mr-1"></i> Call Close</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="call-wrapup-tab" data-toggle="tab" href="#call-wrapup-cc" role="tab" aria-controls="call-wrapup" aria-selected="false"><i class="nav-icon color-icon i-Folder-Zip mr-1"></i> Call Wrap Up</a>
                </li>
            </ul> 
            <div class="tab-content" id="myIconTabContent">
                <div class="tab-pane fade show active" id="patient-data" role="tabpanel" aria-labelledby="patient-data-tab">
                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data')
                </div>
                <div class="tab-pane fade" id="calls" role="tabpanel" aria-labelledby="profile-icon-tab">
                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.call')
                </div>
                <div class="tab-pane fade" id="verification-services" role="tabpanel" aria-labelledby="verification-icon-tab">

                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.hippa')
                </div>
                
                <div class="tab-pane fade" id="review-patient" role="tabpanel" aria-labelledby="review-patient-icon-tab">
                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data')  
                </div>
                <div class="tab-pane fade" id="call-close-cc" role="tabpanel" aria-labelledby="call-close-icon-tab">
                
                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.call-close') 
                </div>
                <div class="tab-pane fade" id="call-wrapup-cc" role="tabpanel" aria-labelledby="call-wrapup-icon-tab">
                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.call-wrap-up')
                </div>

            </div>
            
        </div>
    </div>
</div>
</div>


<!-- <p>hello</p> -->
<div id="parentdiv">
    <div id="app">
        <div id="showtimenew">
            <hr />
                @header("Best Time to Contact")
                <contact-time></contact-time>
            <hr />
        </div>
    </div>
</div>