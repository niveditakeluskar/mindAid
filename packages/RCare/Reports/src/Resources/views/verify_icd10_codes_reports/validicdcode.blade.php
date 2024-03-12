<!-- <div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Verified code</h4>
        </div>
    </div>            
</div>
<div class="separator-breadcrumb border-top"></div>  -->
<div>
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card text-left">     
                <div class="card-body">
                    <!-- <div id="success-set" style="display: none;">  
                        <div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Re-process set Successfully!</strong>
                        </div>
                    </div>
                    <div id="success-unset" style="display: none;">  
                        <div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Re-process unset Successfully!</strong>
                        </div>
                    </div> -->
                    <form id="ctt_report_form" name="ctt_report_form" method="post" action ="">
                        @csrf
                        <div class="form-row">
                            <div class="col-md-2 form-group mb-3">
                                <label for="practicename">Practice</label>
                                @selectpracticespcp("practices", ["id" => "practices", "class" => "select2"])  
                            
                            </div>
                            <div class="col-md-2 form-group mb-3">
                                <label for="practicename">Diagnosis Condition</label>
                                @selectdiagnosiscondition("diagnosis", ["id" => "diagnosis_condition", "class" => "select2"])  
                            </div>
                        
                            <div class="col-md-1 form-group mb-3">
                                <button type="button" class="btn btn-primary mt-4" id="validcttsearchbutton">Search</button>
                            </div>

                            <div class="col-md-1 form-group mb-3">
                                <button type="reset" class="btn btn-primary mt-4" id="validcttresetbutton">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12 mb-4">
            <div class="card text-left">
                <div class="card-body">
                    @include('Theme::layouts.flash-message')
                    <div class="alert alert-success" id="todo-success-alert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>Task action successfully! </strong><span id="text"></span>
                    </div>
                    <div class="table-responsive">
                        <table id="verify_report" class="display datatable table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th width ="10px">Sr No.</th>
                                    <th width ="10px">Code</th>  
                                    <th width ="10px">Condition</th>                     
                                    <th width ="10px">Patient Count</th> 
                                    <th width ="10px">Valid</th> 
                                    <th width ="10px">Invalid</th> 
                                    <th width ="10px">Verified</th>
                                    <th width ="10px">Verified By</th>
                                    <th width ="10px">Verified On</th> 
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- The Modal -->
        <div class="modal" id="patientdetailsicdmodel">  
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Patient Details</h4>  
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body"> 
                        

                        <div class="row mb-4">   
                            <div class="col-md-12 mb-4">
                            <div class="card text-left">
                                <div class="card-body">
                                    @include('Theme::layouts.flash-message')
                                    <div class="alert alert-success" id="todo-success-alert" style="display: none;">
                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    <strong>Task action successfully! </strong><span id="text"></span>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="verify_report_child" class="display datatable table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width ="10px">Sr No.</th>
                                                <th width="150px">Patient</th>
                                                <th width="150px">Practice</th>  
                                                <th width="150px">DOB</th> 
                                                <th width ="10px">Code</th>  
                                                <th width ="10px">Condition</th>                     
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
</div>
