<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                
                <form id="report_form" name="report_form" method="post" action ="">
                @csrf
                <div class="form-row">

                    <div class="col-md-2 form-group mb-2">
                        <label for="practicename">{{config('global.practice_group')}}</label>
                        @selectgrppractices("practicesgrp", ["id" => "practicesgrp","class"=>"custom-select show-tick select2"]) 
                    </div>
                    <div class="col-md-2 form-group mb-2">
                        <label for="practicename">Practice</label>
                         @selectGroupedPractices("practices",["id" => "practices", "class" => "form-control show-tick select2"])                       
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="provider">Provider</label>
                        @selectpracticesphysician("provider",["id" => "physician","class"=>"custom-select show-tick select2"])
                    </div>
                  
                    <!-- <div class="col-md-2 form-group mb-3">
                        <label for="module">Module</label>
                        <select name="modules" id="modules" class="custom-select show-tick select2">
                            <option value="3">CCM</option>
                            <option value="2">RPM</option>   
                        </select>
                    </div> -->
                    <div class="col-md-2 form-group mb-3">
                        <label for="month">From Date</label>
                        @date('monthly',["id" => "monthly"]) 
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="month">To Date</label>
                        @date('monthlyto',["id" => "monthlyto"])
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
                    <div class="col-md-2 form-group mb-3">  
                          <label for="callstatus">Call Answered Status</label> 
                          <select id="callstatus" name="callstatus" class="custom-select show-tick" >
                            <option value="" selected>All</option> 
                            <option value="1">Call Answered </option>
                            <option value="0">Call answered - not good time to call</option>
                            <option value="2">Call Not Answered</option>                           
                          </select>                           
                    </div>
                    <div class="col-md-2 form-group mt-4 ml-2">
                        <label for="only_code" class="checkbox checkbox-primary mr-3">
                            <input type="checkbox" name="only_code" id="only_code" class="CRclass" formcontrolname="checkbox" value="1">
                            <span>Only With Billing Code</span><span class="checkmark"></span>
                        </label>
                    </div>
                    <div class="row col-md-2 mb-2">
                        <div class="col-md-5">
                            <button type="button" class="btn btn-primary mt-4" id="month-search">Search</button>
                        </div>
                        <div class="col-md-5">
                            <button type="button" class="btn btn-primary mt-4" id="month-reset">Reset</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
