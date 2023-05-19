<div class="modal fade" id="org_threshold_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 635px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1">Org Threshold</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div id="org-threshold-success"></div>
            <form action="{{ route('create_threshold_org') }}" method="POST" name="org_threshold_form" id="org_threshold_form">
                {{ csrf_field() }}
                <input type="hidden" name="org_id" id="orgid">
                <div class="modal-body">
            
                    <div class="form-group">
                        <div class="row">
                            <!--div class="col-md-12 form-group mb-3 ">
                                <label for="orgname">Eff Date  <span style="color:red">*</span></label>
                                @date("eff_date", ["placeholder" => ""])
                            </div-->
                             <div class="col-md-6 form-group mb-3 ">
                                <label for="orgname">Systolic High <!-- <span style="color:red">*</span> --></label>
                                @text("systolichigh",["placeholder" => ""])
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="orgname">Systolic Low <!-- <span style="color:red">*</span> --></label>
                                @text("systoliclow",["placeholder" => ""])
                            </div>
                             <div class="col-md-6 form-group mb-3 ">
                                <label for="orgname">Diastolic High <!-- <span style="color:red">*</span> --></label>
                                @text("diastolichigh",["placeholder" => ""])
                            </div>
                            <div class="col-md-6 form-group mb-3 "> 
                                <label for="orgname">Diastolic Low <!-- <span style="color:red">*</span> --></label>
                                @text("diastoliclow",["placeholder" => ""])
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="orgname">Heart Rate High <!-- <span style="color:red">*</span> --></label>
                                @text("bpmhigh",["placeholder" => ""])
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="orgname">Heart Rate Low <!-- <span style="color:red">*</span> --></label>
                                @text("bpmlow",["placeholder" => ""])
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="orgname">Oxygen Saturation High <!-- <span style="color:red">*</span> --></label>
                                @text("oxsathigh",["placeholder" => ""])
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="orgname">Oxygen Saturation Low <!-- <span style="color:red">*</span> --></label>
                                @text("oxsatlow",["placeholder" => ""])
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="orgname">Glucose High <!-- <span style="color:red">*</span> --></label>
                                @text("glucosehigh",["placeholder" => ""])
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="orgname">Glucose Low <!-- <span style="color:red">*</span> --></label>
                                @text("glucoselow",["placeholder" => ""])
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="orgname">Temperature High <!-- <span style="color:red">*</span> --></label>
                                @text("temperaturehigh",["placeholder" => ""])
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="orgname">Temperature Lows <!-- <span style="color:red">*</span> --></label>
                                @text("temperaturelow",["placeholder" => ""])
                            </div>
                                            
                        </div>
                        
                        
                    </div>
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit"  class="btn  btn-primary m-1 save_org_threshold" >Save</button>
                                <!-- <button type="button" class="btn btn-info float-left additionalProvider" id="additionalProvider">Add Provider</button> -->
                                <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
 