<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="form-row ">
                    <span class="col-md-8">
                        <h4>Patient Status</h4> 
                    </span>
                </div>  
            </div>
            <div class="card-body"> 
                     <div class="row">
                        <div  class="col-md-4">
                            <div  class="card">
                                <div class="card-header text-center">Medication 
                                   <!-- <i id="mdeatils" type="button" class="text-muted i-Eye" data-toggle="modal" data-target="details-medication"></i> -->
                                   <i id="editmedication" type="button" class="editform i-Pen-4" data-toggle="modal" data-target="medication_model" ></i> 
                                   </div>
                           <!--      <i class="text-muted i-Eye"></i> -->
                                    <div class="row">
                                        <img src="{{asset("assets/images/timeIcons/Morning.png")}}" class="offset-md-4" style="width: 4em; border-radius: 6em;" />
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
                        <div  class="col-md-4">
                            <div  class="card">
                                <div class="card-header text-center">Compliance</div>
                                <div class="row">
                                        <img src="{{asset("assets/images/timeIcons/Morning.png")}}" class="offset-md-4" style="width: 4em; border-radius: 6em;" />
                                        <img src="{{asset("assets/images/timeIcons/Afternoon.png")}}" style="width: 4em; border-radius: 6em;" />
                                        <img src="{{asset("assets/images/timeIcons/Evening.png")}}" style="width: 4em; border-radius: 6em;" />
                                        <img src="{{asset("assets/images/timeIcons/Moon.png")}}" style="width: 4em; border-radius: 6em;" />
                                </div>
                                <div class="card-footer">
                                    <div class="mc-footer"></div>
                                </div>
                            </div>
                        </div>
                            <div  class="col-md-4">
                                <div  class="card">
                                    <div class="card-header  text-center">HS</div>
                                    <p style="margin-bottom: 35px;"></p>
                                    <div class="card-footer">
                                        <div class="mc-footer">
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div> 
                    <br>
                    <div class="col-md-12 steps_section" id="select-activity-1"> 
                            @include('Rpm::monthly-service.monthly-service-steps.step')  
                     </div>  
                    <div class="col-md-12 steps_section" id="select-activity-2" ></div>              
            </div>
        </div>
    </div>
</div>







<div class="modal fade" id="medication_model" tabindex="-1" role="dialog" aria-labelledby="medication_model-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"> 
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">   
                 @include('Rpm::monthly-service.medications')     
                </div> 
            <!-- </form> -->
        </div>
    </div>
</div> 


