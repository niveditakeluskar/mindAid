<div _ngcontent-rei-c8="" class="row">

    <div _ngcontent-rei-c8="" style="width:30%;margin-left: 1%; margin-right: 2%;" >
     <a href="{{ route('productivity.total.patients') }}" target="blank">
        <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
          <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Myspace"></i>
            <div _ngcontent-rei-c8="" class="content">
               <!-- <p _ngcontent-rei-c8="" class="text-muted" data-toggle="modal"  data-target="#mypatientModal" target="allpatient" style="width: 86px;height: 30px;">Patients</p> -->
               <p _ngcontent-rei-c8="" class="text-muted" style="width: 86px;height: 30px;">Patients</p>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" id="totalpatient"></p>
            </div>
          </div>
        </div>
      </a>
    </div>
    <!-- 2 summary -->
    <div _ngcontent-rei-c8="" style="width:30%;margin-left: 1%; margin-right: 2%;" >
     <a href="/reports/enrolled-patients-details" target="blank">
        <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
          <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Myspace"></i>
            <div _ngcontent-rei-c8="" class="content">
               <!-- <p _ngcontent-rei-c8="" class="text-muted" data-toggle="modal"  data-target="#mypatientModal" target="allpatient" style="width: 86px;height: 30px;">Patients</p> -->
               <p _ngcontent-rei-c8="" class="text-muted" style="width: 86px;height: 30px;">Enrolled Patients</p>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" id="totalenrolledpatient"></p>
            </div>
          </div>
        </div>
      </a>
    </div>
    <!-- 3rd summary -->
    <div _ngcontent-rei-c8="" style="width:30%;margin-left: 1%; margin-right: 2%;" >
     <a href="{{ route('new.enrolled.patients.details') }}" target="blank">
        <div _ngcontent-rei-c8="" class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
          <div _ngcontent-rei-c8="" class="card-body text-center">
            <i _ngcontent-rei-c8="" class="i-Myspace"></i>
            <div _ngcontent-rei-c8="" class="content">
               <!-- <p _ngcontent-rei-c8="" class="text-muted" data-toggle="modal"  data-target="#mypatientModal" target="allpatient" style="width: 86px;height: 30px;">Patients</p> -->
               <p _ngcontent-rei-c8="" class="text-muted" style="width: 107px;height: 30px;">Total New Enrolled Patients</p>
               <p _ngcontent-rei-c8="" class="lead text-primary text-24 mb-2" id="totalnewpatient"></p>
            </div>
          </div>
        </div>
      </a>
    </div>
 
</div>
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                <form id="report_form" name="report_form" method="post" action ="">
                @csrf
                <div class="form-row">
                     <div class="col-md-3 form-group mb-2">
                        <label for="practicegrp">{{config('global.practice_group')}}</label>
                         @selectgrppractices("practicesgrp", ["class" => "select2","id" => "practicesgrp"]) 
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="practicename">Practice Name</label>
                         @selectGroupedPractices("practices",["id" => "practices", "class" => "form-control select2"])  
                     
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="module">Module Name</label>
                        <select id="modules" class="custom-select show-tick" name="modules">
                            <option value="0">None</option>
                            <option value="3" selected>CCM</option>
                            <option value="2">RPM</option>  
                            </select>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="month">From Month & Year</label>
                        @month('from_month',["id" => "from_month"])
                        <!-- @select("Patient", "patient_id", [], ["id" => "patient"]) -->
                    </div>
                    <!-- <div class="col-md-2 form-group mb-3">
                        <label for="month">To Month & Year</label>
                        @month('to_month',["id" => "to_month"])
                    </div> -->
                
                    <div class="row col-md-3 mb-3">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary mt-4" id="month-search">Search</button>
                        </div>
                        <div class="col-md-4">
                            <button type="reset" class="btn btn-primary mt-4" id="month-reset">Reset</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
