<div class="alert" id="message" style="display: none"></div>
    @include('Rpm::patient-enrollment.patient-info')
     <!-- start Solid Bar -->
    <!--div class="row ">
        <div class="col-lg-12 mb-4 ">
            <div class="card">
                <div class="card-header"><h3>Patient Status Section</h3></div>
                <div class="card-body"> 
                    <div class="col-lg-12 mb-1">
                        <label class=" mb-1">Enrollment Status : <b class="text-muted">New</b> </label>
                        <span></span>
                    </div>
                    <div class="col-lg-12 mb-1">
                        <label class=" mb-1"></label>
                        <div class="table-responsive">
                            <table id="patient-list" class="display table table-striped" style="width:100%; border: 1px solid #00000029;">
                                <thead>
                                    <tr>
                                        <th>Communication Vehicle</th>
                                        <th><i class="text-muted i-Email"></i> Email</th>
                                        <th><i class="text-muted i-Headset"></i> Calling</th>
                                        <th><i class="text-muted i-Letter-Open"></i> Text</th>
                                        <th><i class="text-muted i-Mailbox-Empty"></i>Letter</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td>
                                            @if($checklist->contact_preference_email =='1')
                                            <img src="{{asset("assets/images/checkmark.svg")}}" class=" " style="width: 2em; border-radius: 6em; margin-left: 7px;" />
                                            @endif
                                        </td>
                                        <td>
                                            @if($checklist->contact_preference_calling =='1')
                                            <img src="{{asset("assets/images/checkmark.svg")}}" class="" style="width: 2em; border-radius: 6em; margin-left: 7px;" />
                                            @endif
                                        </td>
                                        <td>
                                            @if($checklist->contact_preference_sms =='1')
                                            <img src="{{asset("assets/images/checkmark.svg")}}" class="" style="width: 2em; border-radius: 6em; margin-left: 7px;" />
                                            @endif
                                        </td>
                                        <td>
                                            @if($checklist->contact_preference_letter =='1')
                                            <img src="{{asset("assets/images/checkmark.svg")}}" class="" style="width: 2em; border-radius: 6em; margin-left: 7px;" />
                                            @endif
                                        </td>
                                        
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="card-footer text-right">
                    <div class="stopwatch" id="stopwatch">
                        <div id="time-container" class="float_left" style="margin-top: 8px; margin-left: 83em;"></div>
                            <button class="button" id="start"><img src="{{asset("assets/images/btn-play.jpeg")}}" class='user-image' style=" width: 34px;" /></button>
                            <button class="button" id="pause"><img src="{{asset("assets/images/btn-pause.svg")}}" class='user-image' style=" width: 34px;" /></button>
                            <button class="button" id="stop"><img src="{{asset("assets/images/btn-stop.png")}}" class='user-image' style=" width: 34px;" /></button>
                    </div>
                </div>
            </div>
        </div>
    </div-->
    <!-- end -->
    <div class="card-footer text-center">
        <div class="">
            @if($checklist->contact_preference_sms =='1')
                <button type="button" class="btn btn-primary btn-icon btn-lg m-1" id="text">
                    <span class="ul-btn__icon"><i class="i-Letter-Open"></i></span>
                    <span class="ul-btn__text">Text</span>
                </button>
            @endif
            @if($checklist->contact_preference_email =='1')
                <button type="button" class="btn btn-primary btn-icon btn-lg m-1" id="email">
                    <span class="ul-btn__icon"><i class="i-Email"></i></span>
                    <span class="ul-btn__text">Email</span>
                </button>
            @endif
            @if($checklist->contact_preference_calling =='1')
                <button type="button" class="btn btn-primary btn-icon btn-lg m-1" id="Calling">
                    <span class="ul-btn__icon"><i class="i-Old-Telephone"></i></span>
                    <span class="ul-btn__text">Call</span>
                </button>
            @endif
        </div>
    </div>
    <!-- end -->