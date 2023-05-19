<div class="row mb-4" id="call-sub-steps">
    <div class="col-md-12">
        <div class="card12"> 
            <div class="row ">
                <div class="col-md-12 ">
                    <div class="card text-left">
                        <div class="card-body" >
                            <h4 class="row card-title mb-3">
                                <div class="col-md-8">Call</div>
                                <div class="col-md-4" id="prep_time_spent" style="display:block;">
                                </div>
                            </h4>
                            <ul class="nav nav-tabs" id="myIconTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="call-icon-tab" data-toggle="tab" href="#ccm-call" role="tab" aria-controls="ccm-call" aria-selected="true"><i class="nav-icon color-icon i-Telephone mr-1"></i>Call</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="ccm-verification-icon-tab" data-toggle="tab" href="#ccm-verification" role="tab" aria-controls="ccm-verification" aria-selected="false"><i class="nav-icon color-icon i-Gears mr-1"></i> Verification</a>
                                </li>
                                <!--li class="nav-item">
                                    <a class="nav-link" id="ccm-hippa-icon-tab" data-toggle="tab" href="#ccm-hippa" role="tab" aria-controls="ccm-hippa" aria-selected="false"><i class="nav-icon color-icon i-Gears mr-1"></i> HIPPA</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="ccm-home-services-icon-tab" data-toggle="tab" href="#ccm-home-services" role="tab" aria-controls="ccm-home-services" aria-selected="false"><i class="nav-icon color-icon i-Home1 mr-1"></i> Home Services</a>
                                </li-->
                                <li class="nav-item">
                                    <a class="nav-link" id="ccm-relationship-icon-tab" data-toggle="tab" href="#ccm-relationship" role="tab" aria-controls="ccm-relationship" aria-selected="false"><i class="nav-icon color-icon i-Affiliate mr-1"></i> Relationship</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="ccm-research-follow-up-icon-tab" data-toggle="tab" href="#ccm-research-follow-up" role="tab" aria-controls="ccm-research-follow-up" aria-selected="false"><i class="nav-icon color-icon i-Spell-Check  mr-1"></i> Condition Review</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="ccm-general-questions-icon-tab" data-toggle="tab" href="#ccm-general-questions" role="tab" aria-controls="ccm-general-questions" aria-selected="false"><i class="nav-icon color-icon i-Speach-Bubble-Asking  mr-1"></i> Monthly Questions</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="ccm-call-close-icon-tab" data-toggle="tab" href="#ccm-call-close" role="tab" aria-controls="ccm-call-close" aria-selected="false"><i class="nav-icon color-icon i-Close mr-1"></i> Call Close</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="ccm-call-wrapup-icon-tab" data-toggle="tab" href="#ccm-call-wrapup" role="tab" aria-controls="ccm-call-wrapup" aria-selected="false"><i class="nav-icon color-icon i-Folder-Zip mr-1"></i> Call Wrap up</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myIconTabContent">
                                <div class="tab-pane show active" id="ccm-call" role="tabpanel" aria-labelledby="call-icon-tab">
                                    @include('Ccm::monthly-monitoring.sub-steps.call-sub-steps.call')
                                </div>
                                <div class="tab-pane" id="ccm-verification" role="tabpanel" aria-labelledby="ccm-verification-icon-tab">
                                    <!--('Ccm::monthly-monitoring.sub-steps.call-sub-steps.verification') -->
                                    @include('Ccm::monthly-monitoring.sub-steps.call-sub-steps.hippa')
                                </div>
                                
                                <div class="tab-pane " id="ccm-relationship" role="tabpanel" aria-labelledby="ccm-relationship-icon-tab">
                                    @include('Ccm::monthly-monitoring.sub-steps.call-sub-steps.relationship')
                                </div>
                                <div class="tab-pane " id="ccm-research-follow-up" role="tabpanel" aria-labelledby="ccm-research-follow-up-icon-tab">
                                    @include('Ccm::monthly-monitoring.sub-steps.call-sub-steps.research-follow-up', ['section' => 'research_follow_up'])
                                </div>
                                <div class="tab-pane " id="ccm-general-questions" role="tabpanel" aria-labelledby="ccm-general-questions-icon-tab">
                                    @include('Ccm::monthly-monitoring.sub-steps.call-sub-steps.general-questions')
                                </div>
                                <div class="tab-pane " id="ccm-call-close" role="tabpanel" aria-labelledby="ccm-call-close-icon-tab">
                                    @include('Ccm::monthly-monitoring.sub-steps.call-sub-steps.call-close')
                                </div>
                                <div class="tab-pane " id="ccm-call-wrapup" role="tabpanel" aria-labelledby="ccm-call-wrapup-icon-tab">
                                    @include('Ccm::monthly-monitoring.sub-steps.call-sub-steps.call-wrap-up')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="parentdiv">
    <div id="app">
        <div id="showtimenew">
            <hr/>
                @header("Best Time to contact")
                <contact-time></contact-time>
            <hr/>
        </div>
    </div>
</div>  

 