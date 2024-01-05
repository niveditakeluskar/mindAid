<div _ngcontent-rei-c8="" class="row">

<!--     <div _ngcontent-rei-c8="" style="width:30%;margin-left: 1%; margin-right: 2%;" >
     <a href="{{ route('productivity.total.patients') }}" target="blank">
        <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
          <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Myspace"></i>
            <div _ngcontent-rei-c8="" class="content">
               <p _ngcontent-rei-c8="" class="text-muted" style="width: 86px;height: 30px;">Patients</p>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" id="totalpatient"></p>
            </div>
          </div>
        </div>
      </a>
    </div> -->
    <!-- 2 summary -->
    <!-- <div _ngcontent-rei-c8="" style="width:30%;margin-left: 1%; margin-right: 2%;" >
     <a href="/reports/enrolled-patients-details" target="blank">
        <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
          <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Myspace"></i>
            <div _ngcontent-rei-c8="" class="content">
               <p _ngcontent-rei-c8="" class="text-muted" style="width: 86px;height: 30px;">Enrolled CCM Patients</p>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" id="totalenrolledpatient"></p>
            </div>
          </div>
        </div>
      </a>
    </div> -->
    <!-- 3rd summary -->
 <!--    <div _ngcontent-rei-c8="" style="width:30%;margin-left: 1%; margin-right: 2%;" >
     <a href="{{ route('new.enrolled.patients.details') }}" target="blank">
        <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
          <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Myspace"></i>
            <div _ngcontent-rei-c8="" class="content">
               <p _ngcontent-rei-c8="" class="text-muted" style="width: 107px;height: 30px;">Enrolled RPM Patients</p>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" id="totalnewpatient"></p>
            </div>
          </div>
        </div>
      </a>
    </div>
  -->
</div>
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                <form id="report_form" name="report_form" method="post" action ="">
                @csrf 
                <div class="form-row">
                   

                    <div class="col-md-2 form-group mb-2">
                        <label for="caremanagerid">Users</label>
                       @selectorguser("care_manager_id", ["id" => "care_manager_id", "placeholder" => "Select Users","class" => "select2"])
                       <!-- selectAllexceptadmin -->
                    </div>
                    
                    <div class="col-md-2 form-group mb-2">
                        <label for="date">Date</label>
                        @date('date',["id" => "fromdate"])                     
                    </div> 

<!--                     <div class="col-md-2 form-group mb-3">
                        <label for="date">Enrolled To</label>
                        @date('date',["id" => "todate"])                  
                    </div>
 -->
                    <div class="row col-md-3 mb-3">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary mt-4" id="daily-search">Search</button>
                        </div>
                        <div class="col-md-4">
                            <button type="reset" class="btn btn-primary mt-4" id="daily-reset">Reset</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
