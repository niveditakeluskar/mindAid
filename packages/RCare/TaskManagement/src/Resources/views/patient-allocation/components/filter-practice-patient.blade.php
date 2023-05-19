<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <div class="form-row ">
                    <div class="col-md-6 form-group mb-3">
                        <label for="practicename">Practice Name</label>
                        @selectpractices("practices", ["id" => "practices", "class" => "select2"])
                    </div>
                    <div class="col-md-6 form-group mb-3 patient-div" >
                        <label for="practicename">Care Manager </label>
                       @selectcaremanager("caremanager",["id" => "caremanager", "class" => "caremanager"])
                      <!--   @selectpatient("Patient",["id" => "patient", "class" => "form-control form-control"]) -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>