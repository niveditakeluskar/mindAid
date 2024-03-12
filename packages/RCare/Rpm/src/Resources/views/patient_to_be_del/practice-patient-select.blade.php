@include('Theme::layouts.flash-message')			
<div class="alert" id="message" style="display: none"></div>
<!--  -->
	{{ csrf_field() }}
	<div class="row  mb-4">
		
		<!-- start Solid Bar -->
		<div class="col-lg-6  ">
	        <div class="card patient_boxes grey-bg">
				<div class="card-body">				
					<div class="form-row">
						<div class="form-group col-md-4">
                            @if($checklist->gender =='1')
                            <img src="{{asset("assets/images/faces/imagese.jpg")}}" class='user-image' style=" width: 150px;" />
                            @else
                            <img src="{{asset("assets/images/faces/10.jpg")}}" class='user-image' style=" width: 150px;" />
                            @endif
						 </div>
						  <div class="form-group col-md-8">
							
						  	  <label for="name">
							  @if($checklist->gender =='1')
								Female
							  @else
								Male
							  @endif							  
							  </label>
							  <br>
						  	  <label for="name"><i class="text-muted i-Old-Telephone"></i> : {{$checklist->phone_primary}} </label>
						  	   <br>
						  	  <label for="name"><i class="text-muted i-Over-Time"></i> : {{date('m/d/Y', strtotime($checklist->dob))}}  </label>
						  	   <br>
						  	  <label for="name"><i class="text-muted i-Email"></i> : {{$checklist->email}} </label>
						  	   <br>
						  	  <label for="name"><i class="text-muted i-Old-Telephone"></i> : {{$checklist->emr}} </label>
                                <br>
                                <label for="name"><i class="text-muted i-VPN"></i> : 2746543589</label>
						  	  <!-- <label for="name"><i class="text-muted i-VPN"></i> : {{date('m/d/Y', strtotime($checklist->created_at))}}  </label> -->
					     </div>	
					</div>
				</div>
			</div>
		</div>

		<!-- end::form -->

		<!-- start Solid Bar -->
		<div class="col-lg-6  ">
			<div class="card patient_boxes">
				<div class="card-header"><h3>Progress</h3></div>
				<div class="card-body">
					
					<div class="form-row ">
						<div class="form-group col-md-12">
							<label for="name">Programs: </label> RPM + CCM <br/>
							<label for="name">Total Time Elapsed: </label> 00:14:00 Hours
						</div>
					</div>
					
				</div>
			</div>
		</div>
		<!-- end::form -->
	</div>
	<div class="row  ">
		<!-- start Solid Bar -->
		<div class="col-lg-12 mb-4 ">
			<div class="card">
				<div class="card-header"><h3>Status / Next Step</h3></div>
                <div class="card-body">	
                    <div class="col-lg-12 mb-1">
                        <label class=" mb-1">Chronic Condition(s):</label>
                        <span></span>
                    </div>
                    <div class="col-lg-12 mb-1">
                        <label class=" mb-1">RPM Device(s):</label>
                        <span></span>
                    </div>
                    <div class="col-lg-12 mb-1">
                        <label class=" mb-1">Medications / Vaccines:</label>
                        <span></span>
                    </div>
                    <div class="col-lg-12 mb-1">
                        <label class=" ">Date of Last Contact:</label>
                        <span></span>
                    </div>
                    <div class="col-lg-12 mb-1">
                        <label class=" ">Personal Notes:</label>
                        <span></span>
                    </div>
                    <div class="col-lg-12 mb-1">
                        <label class=" ">Part of Research Study:</label>
                        <span></span>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <div id="chronoExample">
                        <div class="">Time Elapsed: <span class="values">00:00:00</span> Hours
                        </div>
                        <div class="float_left">
                            <!-- <button class="startButton"><i class="text-muted i-Play"></i> </button> -->
                            <button class="pauseButton" ><img src="{{asset("assets/images/btn-pause.svg")}}" class='user-image' style=" width: 34px;" /></button>
                            <button class="stopButton"><img src="{{asset("assets/images/btn-stop.png")}}" class='user-image' style=" width: 34px;" /></button>
                            <!-- <button class="resetButton">Reset</button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/easytimer@1.1.1/dist/easytimer.min.js"></script>

    <script>
        var timerInstance = new easytimer.Timer();
    </script>
    <script>
        var timer = new Timer();
        $('#chronoExample .startButton').click(function () {
            timer.start();
        });
        $('#chronoExample .pauseButton').click(function () {
            timer.pause();
        });
        $('#chronoExample .stopButton').click(function () {
            timer.stop();
        });
        $('#chronoExample .resetButton').click(function () {
            timer.reset();
        });
        timer.addEventListener('secondsUpdated', function (e) {
            $('#chronoExample .values').html(timer.getTimeValues().toString());
        });
        timer.addEventListener('started', function (e) {
            $('#chronoExample .values').html(timer.getTimeValues().toString());
        });
        timer.addEventListener('reset', function (e) {
            $('#chronoExample .values').html(timer.getTimeValues().toString());
        });
    </script>
	
		<!-- end::form -->
	
<!-- Device Traning -->

<div class="col-lg-12 mb-3">
    <button type="submit"  data-toggle="modal"  data-target="#modal-practice-add"class="btn btn-primary btn-lg btn-block">View Call Preparation Note</button>
</div>

<!--Modal-->
<div class="modal fade" id="modal-practice-add" tabindex="-1" role="dialog" aria-labelledby="modal-practice-add-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"> 
            <form action="" method="post" name="practice_add">
                <div class="modal-header">
                    <h5 class="modal-title mx-auto" id="modal-practice-add-title"> <span class="text-muted">Meredith Mckinsey's </span>Current Monthly Call Note( Dec 2019 ) </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12 mb-12">
                                <div class="card ">
                                    <div class="card-body">             
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <div class="float-left"> 
                                                    @if($checklist->gender =='1')
                                                    <img src="{{asset("assets/images/faces/imagese.jpg")}}" class='user-image' style=" width: 50px;" />
                                                    @else
                                                    <img src="{{asset("assets/images/faces/10.jpg")}}" class='user-image' style=" width: 50px;" />
                                                    @endif
                                                </div>
                                               <div class="row horizontal_line">
                                                <label for="name"><i class="text-muted  i-Female"></i> 
                                                    <b>
                                                        @if($checklist->gender =='1')
                                                            Female
                                                        @else
                                                            Male
                                                        @endif	
                                                        , 31 years old</b></label>
                                                <label for="name"><i class="text-muted i-VPN"></i> MRN:2746543589 </label>
                                               </div>
                                                
                                            </div>
                                             <div class="form-group col-md-3 horizontal_line">
                                                 <label for="name"><i class="text-muted  i-Old-Telephone"></i> {{$checklist->phone_primary}}</label>
                                                 <label for="name"><i class="text-muted  i-Email"></i> {{$checklist->email}}</label>
                                            </div>
                                            <div class="form-group col-md-2 horizontal_line">
                                                <label for="name"><i class="text-muted i-Men"></i> Height - 5.6 ft</label><br>
                                               
                                            </div>
                                            <div class="form-group col-md-3">
                                                 <label for="name"><i class="text-muted i-Map-Marker"></i> 585 weldson Ave,</label>
                                                 <label for="name">Okland CA 94610</label> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="row"></div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">

                                    <table id="patient-list" class="display table table-striped table-bordered" style="width:100%; border: 1px solid #00000029;">
                                        <thead>
                                            <tr>
                                                <th>Sr</th>
                                                <th>Topic</th>
                                                <th>CareManager Notes</th>
                                                <th>Action Taken</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Last Month Follow Up</td>
                                                <td></td>
                                                <td></td>
                                                
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Greatest health Concern ?</td>
                                                <td>Can't get to the Doctor</td>
                                                <td>Note in EMR</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Fallen/dizzy spells recently?</td>
                                                <td>Yes, twice</td>
                                                <td>1. Check on meds <br>2. Went over fall Prevention module</td>
                                                
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Depressed/Sad ?</td>
                                                <td>No</td>
                                                <td></td>
                                                
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Upcoming Office Visit ?</td>
                                                <td>Accountant</td>
                                                <td>1. Went over Office Visit module<br>2. Put in EMR</td>
                                                
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>Change in cognitive ability/mood ?</td>
                                                <td>None detected</td>
                                                <td></td>
                                                
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>Disease Specific question ?</td>
                                                <td>Run out of Glucose monitor Strips</td>
                                                <td>1. Looked for best price <br> 2. Communicated with patient</td>
                                               
                                            </tr>
                                             <tr>
                                                <td>8</td>
                                                <td>Other issues ?</td>
                                                <td>None reported</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>9</td>
                                                <td>Preferred Day/Time ?</td>
                                                <td>Tuesday oct 9th, 3:00</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>10</td>
                                                <td>Annual Screenings/vaccinations ?</td>
                                                <td>Yes</td>
                                                <td>Scheduled for patient</td>
                                            </tr>
                                            <tr>
                                                <td>11</td>
                                                <td>Topic for Next Month</td>
                                                <td>Do module on fall prevention</td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
