<div class="row mb-4" id="Calling_page" >
    <div class="col-md-12">
        <!-- <div class="card">  -->
            <div class="card-body">
                <div class="row ">
                    <div class="col-md-12 ">
                        <!-- SmartWizard html -->
                        <ul class="nav nav-pills" id="myPillTab" role="tablist">
                             <li class="nav-item review_data_allergy_tab">
                                <a class="nav-link allergiestab tabClick active" id="review-drug-icon-pill" data-toggle="pill" href="#review-drug" role="tab" aria-controls="review-drug" aria-selected="true"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Drug</a>
                            </li>
                            <li class="nav-item review_data_allergy_tab">
                                <a class="nav-link allergiestab tabClick" id="review-food-icon-pill" data-toggle="pill" href="#review-food" role="tab" aria-controls="review-food" aria-selected="false"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i>Food</a>
                            </li>                           
                            <li class="nav-item review_data_allergy_tab">
                                <a class="nav-link allergiestab tabClick" id="review-enviromental-icon-pill" data-toggle="pill" href="#review-enviromental" role="tab" aria-controls="review-enviromental" aria-selected="false"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Enviromental</a>
                            </li>
                            <li class="nav-item review_data_allergy_tab">
                                <a class="nav-link allergiestab tabClick" id="review-insect-icon-pill" data-toggle="pill" href="#review-insect" role="tab" aria-controls="review-Insect" aria-selected="false"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Insect</a>
                            </li>
                            <li class="nav-item review_data_allergy_tab">
                                <a class="nav-link allergiestab tabClick" id="review-latex-icon-pill" data-toggle="pill" href="#review-latex" role="tab" aria-controls="review-latex" aria-selected="false"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Latex</a>
                            </li>
                            <li class="nav-item review_data_allergy_tab">
                                <a class="nav-link allergiestab tabClick" id="review-pet-related-icon-pill" data-toggle="pill" href="#review-pet-related" role="tab" aria-controls="review-pet-related" aria-selected="false"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Pet-Related</a>
                            </li>
                            <li class="nav-item review_data_allergy_tab">
                                <a class="nav-link allergiestab tabClick" id="review-other-icon-pill" data-toggle="pill" href="#review-other-allergy" role="tab" aria-controls="review-other" aria-selected="false"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i> Other</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myPillTabContent">
                             <div class="tab-pane fade show active allergy_div" id="review-drug" role="tabpanel" aria-labelledby="drug-icon-pill">
                                <div class="card">  

                                    <div class="card-header"><h4>Drug Data</h4></div>      
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.allergy-sub-steps.drug')
                                </div>
                            </div>
                            <div class="tab-pane fade allergy_div" id="review-food" role="tabpanel" aria-labelledby="food-icon-pill">
                                <div class="card"> 
                                   
                                    <div class="card-header"><h4>Food</h4></div>      
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.allergy-sub-steps.food')
                                </div>
                            </div>                           
                            <div class="tab-pane fade allergy_div" id="review-enviromental" role="tabpanel" aria-labelledby="enviromental-icon-pill">
                                <div class="card">  
                             
                                    <div class="card-header"><h4>Enviromental</h4></div>      
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.allergy-sub-steps.enviromental')
                                </div>
                            </div>
                            <div class="tab-pane fade allergy_div" id="review-insect" role="tabpanel" aria-labelledby="insect-icon-pill">
                                <div class="card">  
                         
                                    <div class="card-header"><h4>Insect</h4></div>      
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.allergy-sub-steps.insect')
                                </div>
                            </div>
                            <div class="tab-pane fade allergy_div" id="review-latex" role="tabpanel" aria-labelledby="latex-icon-pill">
                                <div class="card"> 
                        
                                    <div class="card-header"><h4>Latex</h4></div>      
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.allergy-sub-steps.latex')
                                </div>
                            </div>
                            <div class="tab-pane fade allergy_div" id="review-pet-related" role="tabpanel" aria-labelledby="pet-related-icon-pill">
                                <div class="card">  
                             
                                    <div class="card-header"><h4>Pet-Related</h4></div>      
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.allergy-sub-steps.pet-related')
                                </div>
                            </div>
                            <div class="tab-pane fade allergy_div" id="review-other-allergy" role="tabpanel" aria-labelledby="other-icon-pill">
                                <div class="card">  
                         
                                    <div class="card-header"><h4>Other</h4></div>      
                                    @include('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-sub-steps.allergy-sub-steps.other-allergy')
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        <!-- </div> -->
    </div>
</div> 
