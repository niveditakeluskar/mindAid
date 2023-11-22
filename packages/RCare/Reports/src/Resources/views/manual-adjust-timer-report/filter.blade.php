<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                <form id="report_form" name="report_form" method="post" action ="">
                @csrf
                <div class="form-row">
                     <div class="col-md-2 form-group mb-2">
                        <label for="practicegrp">{{config('global.practice_group')}}</label>
                         @selectgrppractices("practicesgrp", ["class" => "select2","id" => "practicesgrp"]) 
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="practicename">Practice Name</label>
                         @selectGroupedPractices("practices",["id" => "practices", "class" => "form-control select2"])  
                     
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="provider">Provider Name</label>
                        @selectpracticesphysician("provider",["id" => "physician","class"=>"select2"])
                    </div>
                    <!-- <div class="col-md-3 form-group mb-3">
                        <label for="patient">Patient Name</label>
                        @select("Patient", "patient_id", [], ["id" => "patient"])
                    </div> -->
                    <div class="col-md-2 form-group mb-3">
                        <label for="module">Module Name</label>
                        <select id="modules" class="custom-select show-tick" name="modules">
                            <option value="0">None</option>
                            <option value="3" selected>CCM</option>
                            <option value="2">RPM</option>  
                            </select>
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="month">Month & Year</label>
                        @month('monthly',["id" => "monthly"])
                        <!-- @select("Patient", "patient_id", [], ["id" => "patient"]) -->
                    </div>
                    <div class="col-md-2 form-group mb-3">  
                          <label for="activedeactivestatus">Status</label> 
                          <select id="activedeactivestatus" name="activedeactivestatus" class="custom-select show-tick" >
                            <option value="" selected>All (Active,Suspended,Deactivated,Deceased)</option> 
                            <option value="1">Active</option>
                            <option value="0">Suspended</option>
                            <option value="2" >Deactivated</option>                           
                            <option value="3" >Deceased</option>
                          </select>                          
                    </div>
                    <div class="row col-md-2 mb-3">
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
