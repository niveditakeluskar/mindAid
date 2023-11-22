<div class="row  mb-4 text-align-center">
			<div class="col-md-12  mb-4 patient_details">
				<div class="tsf-wizard tsf-wizard-monthly-service top" >    
					<div class="tsf-nav-step">

						<ul class="gsi-step-indicator triangle gsi-style-1  gsi-transition " >
							<li class="current" data-target="step-first">
								<a href="#0">
									<span class="desc">
										<label>Step 1</label>
									</span>
								</a>
							</li>
							<li class="step2" data-target="step-2">
								<a href="#0">
									<span class="desc">
										<label>Step 2</label>
									</span>
								</a>
							</li>
							<li class="step3" data-target="step-3">
								<a href="#0">
									<span class="desc">
										<label>Step 3</label>
									</span>
								</a>
							</li>                                                                      
						</ul>

					</div>

					<div class="tsf-container">
						<!-- BEGIN CONTENT-->
						<form class="tsf-content">
							<div id="step-1" class="tsf-step step-first active">
								@include('Rpm::monthlyService.review-data-dropdown')
							</div>
							<div id="step-2" class="tsf-step step-2">
								@include('Rpm::monthlyService.text-section')
								@include('Rpm::monthlyService.call-section')
								@include('Rpm::monthlyService.within_guidelines')
								@include('Rpm::monthlyService.out_of_guidelines')
							</div>
							<div id="step-3" class="tsf-step step-3">
								@include('Rpm::monthlyService.summary')
							</div>										
						</form>
					</div>
				
				</div>
			</div>
		</div>
		
		