<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                
                <form id="report_form" name="report_form" method="" action ="">
                @csrf
                <div class="form-row">
                    <div class="col-md-2 form-group mb-3">
                        <label for="practicename">Practice</label>
                         @selectpractices("practices", ["id" => "practices"])
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="caremanagername">Care Manger</label>
                        @selectcaremanager("care_manager_id", ["id" => "care_manager_id", "placeholder" => "Select Care Manager"])
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="module">Module</label>
                        @selectOrgModule("modules",["id"=>"modules"]) 
                        {{-- @selectmodules("modules",["id" => "modules"]) --}}
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="month">From Month & Year</label>
                        @month('monthlyto',["id" => "monthlyto"])
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="month">To Month & Year</label>
                        @month('monthly',["id" => "monthly"])
                    </div>
                    <div class="col-md-1 form-group mb-3">
                       <button type="button" class="btn btn-primary mt-4 ml-3" id="month-search">Search</button>
                    </div>
                    <div class="col-md-1 form-group mb-3">
                       <button type="button" class="btn btn-primary mt-4 ml-1" id="reset-btn">Reset</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
 