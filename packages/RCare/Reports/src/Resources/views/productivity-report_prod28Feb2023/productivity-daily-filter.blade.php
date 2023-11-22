<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                
                <form id="productivity_report_form" name="productivity_report_form" method="post" action ="">
                @csrf 
                <div class="form-row">
                   <div class="col-md-4 form-group mb-2">
                            <label for="practicename">{{config('global.practice_group')}}</label>
                            @selectgrppractices("practicesgrp", ["id" => "practicesgrp","class"=>"custom-select show-tick select2"]) 
                    </div>
                    

                    <div class="col-md-2 form-group mb-2">
                        <label for="caremanagerid">Users</label>
                       @selectorguser("care_id", ["id" => "care_id", "placeholder" => "Select Users","class" => "select2"])
                       <!-- selectAllexceptadmin -->
                    </div>


                    <div class="col-md-4 form-group mb-3">
                        <label for="practicename">Practice</label>
                         @selectGroupedPractices("prac_id",["id" => "prac_id", "class" => "form-control select2"])  
                      
                    </div>
                   
                    <div class="col-md-3 form-group mb-3">
                        <label for="date">From</label> 
                        @date('date1',["id" => "fromdate"])
                                                
                    </div>
                     <div class="col-md-3 form-group mb-3">
                        <label for="date">To</label>
                        @date('date2',["id" => "todate"])             
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
                    <!-- <div class="col-md-2 form-group mb-3">
                        <label for="module">Module</label>
                        <select name="modules" id="modules" class="custom-select show-tick select2">
                            <option value="3">CCM</option>
                            <option value="2">RPM</option>   
                        </select>
                      
                    </div> -->

                    <div class="col-md-1 form-group mb-3"> 
                       <button type="button" class="btn btn-primary mt-4" id="daily-prod-search">Search</button>
                    </div> 
                    <div class="col-md-1 form-group mb-3">
                       <button type="button" id="resetbutton" class="btn btn-primary mt-4">Reset</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
 