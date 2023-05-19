<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="form-row ">
                    <span class="col-md-8">
                        <h4>Patient Status</h4>
                    </span>
                    <!-- <div class="mb-4 demo-div">
                        <div class="stopwatch" id="stopwatch">
                            <div id="time-container" class="float_left" style="margin-top: 8px;"></div>
                            <button class="button" id="start"><img src="{{asset("assets/images/btn-play.jpeg")}}" class='user-image' style=" width: 34px;" /></button>
                            <button class="button" id="pause"><img src="{{asset("assets/images/btn-pause.svg")}}" class='user-image' style=" width: 34px;" /></button>
                            <button class="button" id="stop"><img src="{{asset("assets/images/btn-stop.png")}}" class='user-image' style=" width: 34px;" /></button>
                        </div>
                    </div> -->
                </div>
                
            </div>
            <div class="card-body"> 
                <div class="row">
                    <div class="col-md-12 status_section">
                        <div class="col-md-12">
                            <div>
                                <label class=" "><strong>Last Encounter:</strong></label>
                                <span></span>
                            </div>
                            <div>
                                <label class=" "><strong>Last HOC Alert:</strong></label>
                                <span></span>
                            </div>
                            <div>
                                <label class=" "><strong>Month To Date:</strong></label>
                                <span>
                                    @if($time=='0')
                                        {{ '00:00:00' }}
                                    @else
                                        {{$time}}
                                    @endif
                                </span>
                                <!-- <span>09:09:19</span> -->
                            </div>
                        </div>     
                     <div class="row">
                        <div  class="col-md-6">
                            <div  class="card">
                            <div class="card-header text-center">Medication test</i></div>
                          <!--       <span><i class="fa fa-eye" aria-hidden="true"></i></span> <i class="icon-eye-open" aria-hidden="true">-->
                                <div class="row">
                                        <img src="{{asset("assets/images/timeIcons/Morning.png")}}" class="offset-md-2" style="width: 4em; border-radius: 6em;" />
                                        <img src="{{asset("assets/images/timeIcons/Afternoon.png")}}" style="width: 4em; border-radius: 6em;" />
                                        <img src="{{asset("assets/images/timeIcons/Evening.png")}}" style="width: 4em; border-radius: 6em;" />
                                        <img src="{{asset("assets/images/timeIcons/Moon.png")}}" style="width: 4em; border-radius: 6em;" />
                                </div>
                        
                            <div class="card-footer">
                                <div class="mc-footer">
                                </div>
                            </div>
                            </div>
                        </div>
                        <div  class="col-md-6">
                            <div  class="card">
                                <div class="card-header text-center">Compliance</div>
                                
                                    <div class="row">
                                        <img src="{{asset("assets/images/timeIcons/Morning.png")}}" class="offset-md-2" style="width: 4em; border-radius: 6em;" />
                                        <img src="{{asset("assets/images/timeIcons/Afternoon.png")}}" style="width: 4em; border-radius: 6em;" />
                                        <img src="{{asset("assets/images/timeIcons/Evening.png")}}" style="width: 4em; border-radius: 6em;" />
                                        <img src="{{asset("assets/images/timeIcons/Moon.png")}}" style="width: 4em; border-radius: 6em;" />
                                    </div>
                           
                                <div class="card-footer">
                                    <div class="mc-footer">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <br>
                        <div class="row">
                       
                            <div  class="col-md-12 mb-4">
                                <div  class="card">
                                    <div class="card-header  text-center">HS</div>
                                    <p style="margin-bottom: 35px;"></p>
                                    <!-- <div  class="card-body"> -->
                                        <!-- <div class="row">
                                            <label class="col-md-2">test</label>
                                            <textarea class="col-md-4 form-control"></textarea>
                                        </div> -->
                                    <!-- </div> -->
                                    <div class="card-footer">
                                        <div class="mc-footer">
                                        </div>
                                    </div>
                                </div>
                            </div>
                         
                     
                          
                           
                        <!-- </div>      -->
                  

                    </div> 
                    <div class="col-md-12 steps_section" id="select-activity-1"> 
                                @include('Rpm::monthlyService.main-step')
                            </div>    
                     </div> 

                          
                    <div class="col-md-12 steps_section" id="select-activity-2" > 
                     
                    </div>              
       
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="current_month_notes" tabindex="-1" role="dialog" aria-labelledby="current_month_notes-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 1000px;">
        <div class="modal-content"> 
            <form action="" method="post" name="practice_add">
                <div class="modal-header">
                    <h5 class="modal-title mx-auto" id="current_month_notes-title"> <span class="text-muted">{{$checklist->fname}} {{$checklist->lname}}'s </span> <span id="month_name"></span> Monthly Call Note( Dec 2019 ) </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">  
                    
                </div>
            </form>
        </div>
    </div>
</div>
