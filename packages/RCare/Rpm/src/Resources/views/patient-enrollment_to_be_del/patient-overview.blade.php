
<div class="alert" id="message" style="display: none"></div>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card grey-bg">
            <div class="card-body">             
                <div class="form-row">
                    <input type="hidden" name="hidden_id" id="hidden_id" value="{{$checklist->id}}">
                    <input type="hidden" name="practice_id" id="practice_id" value="{{$checklist->practice_id}}">
                    <div class="col-md-1">
                        @if($checklist->gender =='1')
                        <img src="{{asset("assets/images/faces/oldimages.jpg")}}" class='user-image' style=" width: 60px;" />
                        @else
                        <img src="{{asset("assets/images/faces/oldmen.jpg")}}" class='user-image' style=" width: 60px;" />
                        @endif
                    </div>
                    <div class="col-md-11">
                        <div class="form-row">
                            <div class="col-md-3">
								<label class="patientname">{{$checklist->fname}} {{$checklist->lname}}</label><br/>
                                <label for="name">
                                    @if($checklist->gender =='1')
                                        Female
                                    @else
                                        Male
                                    @endif
                                    ({{ calAge($checklist->dob)}})
                                </label>
                                
                                
                            </div>
                            <div class="col-md-3">
                                <label for="name"><i class="text-muted i-Old-Telephone"></i> : <b>{{$checklist->phone_primary}}</b> </label>
                                <br>
                                <label for="name"><i class="text-muted i-Email"></i> : {{$checklist->email}} </label>
                            </div>
                            <div class="col-md-3">
                                <label for="name"><i class="text-muted i-Old-Telephone"></i> : {{$checklist->emr}} </label>
                                <br>
								<label for="name"><i class="text-muted i-Over-Time"></i> : {{date('m/d/Y', strtotime($checklist->dob))}}  </label>
                                
                                <!-- <label for="name"><i class="text-muted i-VPN"></i> : {{date('m/d/Y', strtotime($checklist->created_at))}}  </label> -->
                            </div> 
                            <div class="col-md-3">
								<label for="name">Programs: </label> RPM + CCM                                 
                            </div>
                             <div class="col-md-3"> 
                                <label for ="enrollment">Enrollment Status : New </label>
                            </div>
                        </div>
                        <!-- <div class="form-row col-md-12">
                            <div id="chronoExample">
                                <div class="">Time Elapsed: <span class="values">00:00:00</span> Hours
                                </div>
                                <div class="float_left">
                                    <!- - <button class="startButton"><i class="text-muted i-Play"></i> </button> - ->
                                    <button class="pauseButton" ><img src="{{asset("assets/images/btn-pause.svg")}}" class='user-image' style=" width: 34px;" /></button>
                                    <button class="stopButton"><img src="{{asset("assets/images/btn-stop.png")}}" class='user-image' style=" width: 34px;" /></button>
                                    <!- - <button class="resetButton">Reset</button> - ->
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="form-row ">
                    <span class="col-md-8">
                        <h4>Patient Status</h4>
                    </span>
                   <div id="chronoExample">
					<div class="float_left">Time Elapsed: <span class="values">00:00:00</span> Hours
					</div>
					<div class="float_left" style="margin-top:-3px">
						<!-- <button class="startButton"><i class="text-muted i-Play"></i> </button> -->
						<button class="pauseButton" ><img src="{{asset("assets/images/btn-pause.svg")}}" class='user-image' style=" width: 29px;" /></button>
						<button class="stopButton"><img src="{{asset("assets/images/btn-stop.png")}}" class='user-image' style=" width: 30px;" /></button>
						<!-- <button class="resetButton">Reset</button> -->
						</div>
					</div>
                </div>
				
            </div>
            <div class="card-body "> 
				<div class="row">
				<div class="col-md-7 status_section"  style=" ">
                <label for="name">Communication Vehicles: </label> @if($checklist->contact_preference_email =='1')
                                        <button type="button" class="btn btn-primary btn-icon btn-lg m-1" id="email">
                                            <span class="ul-btn__icon"><i class="i-Email"></i></span>
                                            <span class="ul-btn__text"></span>
                                        </button>
                                    <!-- <img src="{{asset("assets/images/checkmark.svg")}}" class=" " style="width: 2em; border-radius: 6em; margin-left: 7px;" /> -->
                                    @endif
									
									@if($checklist->contact_preference_calling =='1')
                                        <button type="button" class="btn btn-primary btn-icon btn-lg m-1" id="Calling">
                                            <span class="ul-btn__icon"><i class="i-Headset"></i></span>
                                            <span class="ul-btn__text"></span>
                                        </button>
                                    <!-- <img src="{{asset("assets/images/checkmark.svg")}}" class="" style="width: 2em; border-radius: 6em; margin-left: 7px;" /> -->
                                    @endif
									
									@if($checklist->contact_preference_sms =='1')
                                        <button type="button" class="btn btn-primary btn-icon btn-lg m-1" id="text">
                                            <span class="ul-btn__icon"><i class="i-Letter-Open"></i></span>
                                            <span class="ul-btn__text"></span>
                                        </button>
                                    <!-- <img src="{{asset("assets/images/checkmark.svg")}}" class="" style="width: 2em; border-radius: 6em; margin-left: 7px;" /> -->
                                    @endif
						
                    <div class="mb-1 mt-1">
                        <label class=" mb-1"><strong>Chronic Condition(s):</strong></label>
                        <span>Hypertension (I10), COPD (J44.9), Diabetes (E11.9), Depression (F32.9)</span>
                    </div>
                    <div class="mb-1">
                        <label class=" mb-1"><strong>RPM Device(s):</strong></label>
                        <span>Blood Pressure Cuff</span>
                    </div>
                    <div class="mb-1">
                        <label class=" mb-1"><strong>Medications / Vaccines:</strong></label>
                        <span>Enduron (5 mg 1x daily), Lopressor (100 mg 2x daily), Zoloft (25 mg 2x daily)</span>
                    </div>
                    <div class="mb-1">
                        <label class=" "><strong>Date of Last Contact:</strong></label>
                        <span>November 23, 2019</span>
                    </div>
                    <div class="mb-1">
                        <label class=" "><strong>Personal Notes:</strong></label>
                        <span>Wife is deceased (2015).  Daughter (Julie) is his primary care giver.  Dog lover; has 3 golden retrievers (Charlie, Thor, Rex); Rex is old and sick.  Grandson goes to UT, in his third year and is studying to be an architect.  Likes to play bingo at the Catholic Church on Wednesdays.  Plays golf on Saturday morning with regular group.</span>
                    </div>
                    <div class="mb-1">
                        <label class=" "><strong>Part of Research Study:</strong></label>
                        <span>Self-Management Grant (G1C)</span>
                    </div>
                			
				</div>		
                <!-- <div class="col-md-1" ></div> -->
				<div class="col-md-5 steps_section" style=" display: inline-block; vertical-align: top; overflow: auto;  padding-left: 2%; padding-right: 2%;"> 
					<div class="form-group ">
						@include('Rpm::patient-enrollment.text-Email')
						@include('Rpm::patient-enrollment.calling')
					</div>
				</div>				
                
            </div>
        </div>
        </div>
    </div>
    
</div>

    <!-- end -->
    <!-- <div class="card-footer text-center">
        <div class=""> -->
            {{--@if($checklist->contact_preference_sms =='1')--}}
                <!-- <button type="button" class="btn btn-primary btn-icon btn-lg m-1" id="text">
                    <span class="ul-btn__icon"><i class="i-Letter-Open"></i></span>
                    <span class="ul-btn__text">Text</span>
                </button> -->
                {{--@endif
            @if($checklist->contact_preference_email =='1')--}}
                <!-- <button type="button" class="btn btn-primary btn-icon btn-lg m-1" id="email">
                    <span class="ul-btn__icon"><i class="i-Email"></i></span>
                    <span class="ul-btn__text">Email</span>
                </button> -->
                {{--@endif
            @if($checklist->contact_preference_calling =='1')--}}
                <!-- <button type="button" class="btn btn-primary btn-icon btn-lg m-1" id="Calling">
                    <span class="ul-btn__icon"><i class="i-Old-Telephone"></i></span>
                    <span class="ul-btn__text">Call</span>
                </button> -->
                {{--@endif--}}
        <!-- </div>
    </div> -->
    <!-- end -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/easytimer@1.1.1/dist/easytimer.min.js"></script>

    <script>
        $(document).ready(function(){ 
            timer.start();
        });
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
