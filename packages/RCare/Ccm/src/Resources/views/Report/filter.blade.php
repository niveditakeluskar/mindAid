<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                <!-- {{ route("monthly.report.patients.search") }} -->
                <form id="report_form" name="report_form" method="post" action ="">
                @csrf
                <div class="form-row">
                    <div class="col-md-3 form-group mb-3">
                        <label for="practicename">Practice Name</label>
                        @selectpractices("practices", ["id" => "practices"])
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="provider">Provider Name</label>
                        @selectpracticesphysician("provider",["id" => "physician"])
                    </div>
                    <!-- <div class="col-md-3 form-group mb-3">
                        <label for="patient">Patient Name</label>
                        @select("Patient", "patient_id", [], ["id" => "patient"])
                    </div> -->
                    <div class="col-md-2 form-group mb-3">
                        <label for="module">Module Name</label>
                        @selectOrgModule("modules",["id"=>"modules"]) 
                        {{-- @selectmodules("modules",["id" => "modules"]) --}}
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="month">Month & Year</label>
                        @month('monthly',["id" => "monthly"])
                        <!-- @select("Patient", "patient_id", [], ["id" => "patient"]) -->
                    </div>
                    <div class="col-md-1 form-group mb-3">
                       <button type="submit" class="btn btn-primary mt-4" id="month-search">Search</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
