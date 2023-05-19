
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card grey-bg">
            <div class="card-body">             
                <div class="form-row">
                
                    <input type="hidden" name="hidden_id" id="hidden_id" value="{{$checklist->id}}">
                    <input type="hidden" name="practice_id" id="practice_id" value="{{$checklist->practice_id}}">
                    <div class="col-md-1">
                        @if($checklist->gender =='1')
                        <img src="{{asset('assets/images/faces/oldimages.jpg')}}" class='user-image' style="width: 60px;" />
                        @else
                        <img src="{{asset('assets/images/faces/oldmen.jpg')}}" class='user-image' style=" width: 60px;" />
                        @endif
                    </div>
                    <div class="col-md-11">
                        <div class="form-row">
                            <div class="col-md-2 right-divider">
                                <label class="patientname">{{$checklist->fname}} {{$checklist->lname}}</label><br/>
                                <label for="name">
                                    @if($checklist->gender =='1')
                                        Female
                                    @else
                                        Male  
                                    @endif

                                    ({{ empty($checklist->dob) ? '' : calAge($checklist->dob)}})
                                </label>                                
                            </div>
                            <div class="col-md-3 right-divider">
                                <label for="name"><i class="text-muted i-Old-Telephone"></i> : <b>{{$checklist->phone_primary}}</b> </label>
                                <br>
                                <label for="name"><i class="text-muted i-Email"></i> : {{$checklist->email}} </label>
                            </div>
                            <div class="col-md-2 right-divider">
                                <label for="name"><i class="text-muted i-Old-Telephone"></i> : {{$checklist->emr}} </label>
                                <br>
                                <label for="name"><i class="text-muted i-Over-Time"></i> : {{date('m/d/Y', strtotime($checklist->dob))}} </label>
                            </div> 
                            <div class="col-md-2 right-divider">
                                <label for="name">Programs: </label> RPM + CCM  <br>  
                                <label for="enrollment">Enrollment Status: </label> New  <br>                   
                            </div>
                            <div class="col-md-2"> 
                                <label for="timeelapsed">Total Time: </label> 00:14:00
                                <div class="mb-4 demo-div"> 
                                    <div class="stopwatch" id="stopwatch">
                                        <button class="button" id="start"><img src="{{asset('assets/images/play.png')}}" class='user-image' style=" width: 28px;" /></button>
                                        <button class="button" id="pause"><img src="{{asset('assets/images/pause.png')}}" class='user-image' style=" width: 28px;" /></button>
                                        <button class="button" id="stop"><img src="{{asset('assets/images/stop.png')}}" class='user-image' style=" width: 28px;" /></button>
                                        <div id="time-container" class="" style="margin-top: 6px;margin-right: 60px; float: right;"></div>
									</div>
								</div>                             
							</div>
                            <div class="col-md-1"> 
                                    <button class="button" id="start"><a href="/patients/registerd-patient-edit/{{$checklist->id}}" title="Edit" ><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a></button>                  
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>