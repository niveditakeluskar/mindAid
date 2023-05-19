<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <div class="form-row ">
                    <div class="col-md-6 form-group mb-3">
                        <label for="practicename">Practice Name</label>
                        @selectpractices("practices", ["id" => "practices"])
                    </div>
                    <div class="col-md-6 form-group mb-3 patient-div" >
                        <label for="practicename">Patient Name</label>
                        @select("Patient", "patient_id", [], ["id" => "patient"])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- <div class="" id="" aria-hidden="true">
            <div class="modal-body">
                <form action="" method="post" name="" id="">
                 {{ csrf_field() }}
                       
                        <div class="form-group">
                            <div class="row  mb-4">
                            <div class="col-md-10 mb-4 ">    
                            <div class="card">
                                 <div class="card-body card text-left">
								 <div class="row">
									<div class="col-md-4 mb-4 float-left">
										<label for="Status"><b>Practice</b></label>
										@selectpractices("status",["id" => "status", "class" => "form-control form-control"])
									</div>
									<div class="col-md-4 mb-4 float-left">
										<label for="Status">Patient</label></b>
										@selectpatient("",["id" => "", "class" => "form-control form-control"])
									</div>
								</div>
								</div>
							</div>                                 
							</div>                                 
                            </div>
                        </div>



                   
                       
                    </form>
                </div>
         </div> 

 -->
